<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HumanResources\Models\Payroll;
use Modules\HumanResources\Models\PayrollItem;
use Modules\HumanResources\Models\PayrollParameter;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\EmployeeSalary;
use Modules\HumanResources\Services\PayrollCalculator;
use Modules\HumanResources\Models\Leave;
use Modules\HumanResources\Models\Overtime;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Account;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    protected $calculator;

    public function __construct(PayrollCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index()
    {
        $payrolls = Payroll::where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(12);
        return view('humanresources::payrolls.index', compact('payrolls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        $payroll = Payroll::create([
            'company_id' => auth()->user()->company_id,
            'month' => $request->month,
            'year' => $request->year,
            'status' => 'draft',
        ]);

        return redirect()->route('hr.payrolls.show', $payroll);
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('items.employee');
        return view('humanresources::payrolls.show', compact('payroll'));
    }

    /**
     * Tüm çalışanları bordroya ekle ve hesapla
     */
    public function calculateAll(Payroll $payroll)
    {
        $employees = Employee::where('company_id', $payroll->company_id)
            ->where('status', 'active')
            ->get();

        $params = PayrollParameter::where('company_id', $payroll->company_id)
            ->where('year', $payroll->year)
            ->first();

        if (!$params) {
            return back()->with('error', "{$payroll->year} yılı için yasal parametreler tanımlanmamış.");
        }

        DB::transaction(function () use ($payroll, $employees, $params) {
            foreach ($employees as $employee) {
                // 1. Ücretsiz İzinleri Bul (Çakışan günleri sayar)
                $startDate = now()->setYear($payroll->year)->setMonth($payroll->month)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();

                $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->where('type', 'unpaid')
                    ->where(function($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate]);
                    })
                    ->get()
                    ->sum(function($leave) use ($startDate, $endDate) {
                        $start = $leave->start_date->max($startDate);
                        $end = $leave->end_date->min($endDate);
                        return $start->diffInDays($end) + 1;
                    });

                // 2. Mesai Saatlerini Bul
                $overtimeHours = Overtime::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('hours');

                // 3. Çalışanın güncel maaşını bul
                $salary = EmployeeSalary::where('employee_id', $employee->id)
                    ->where('is_active', true)
                    ->first();
                
                $grossAmount = $salary ? $salary->amount : ($employee->salary ?? 0);

                // 4. Kümülatif Matrahı Bul
                $cumulative = PayrollItem::join('hr_payrolls', 'hr_payroll_items.payroll_id', '=', 'hr_payrolls.id')
                    ->where('hr_payroll_items.employee_id', $employee->id)
                    ->where('hr_payrolls.year', $payroll->year)
                    ->where('hr_payrolls.month', '<', $payroll->month)
                    ->sum('income_tax_base');

                $calc = $this->calculator->calculate(
                    $grossAmount, 
                    $cumulative, 
                    $params->toArray(),
                    0, // bonus
                    $overtimeHours,
                    $unpaidLeaveDays
                );

                PayrollItem::updateOrCreate(
                    ['payroll_id' => $payroll->id, 'employee_id' => $employee->id],
                    $calc
                );
            }
        });

        return back()->with('success', 'Tüm çalışanlar için bordro hesaplandı.');
    }

    /**
     * Bordroyu Muhasebeleştir
     */
    public function postToAccounting(Payroll $payroll)
    {
        if ($payroll->status === 'posted') {
            return back()->with('error', 'Bu bordro zaten muhasebeleştirilmiş.');
        }

        $items = $payroll->items;
        if ($items->isEmpty()) {
            return back()->with('error', 'Hesaplanmış bordro kalemi bulunamadı.');
        }

        DB::transaction(function () use ($payroll, $items) {
            $totalNet = $items->sum('final_net_paid');
            $totalSGK = $items->sum('sgk_worker_cut') + $items->sum('unemployment_worker_cut') + $items->sum('sgk_employer_cut') + $items->sum('unemployment_employer_cut');
            $totalVergi = $items->sum('calculated_income_tax') - $items->sum('income_tax_exemption') + $items->sum('calculated_stamp_tax') - $items->sum('stamp_tax_exemption');
            $totalCost = $items->sum('total_employer_cost');

            $date = now()->setYear($payroll->year)->setMonth($payroll->month)->endOfMonth();
            
            // Mali Dönemi Bul
            $period = \Modules\Accounting\Models\FiscalPeriod::where('company_id', $payroll->company_id)
                ->where('status', 'open')
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            if (!$period) {
                throw new \Exception("{$payroll->year} yılı için aktif bir mali dönem bulunamadı.");
            }

            // 1. Muhasebe Fişi Oluştur (Transaction)
            $transaction = Transaction::create([
                'company_id' => $payroll->company_id,
                'fiscal_period_id' => $period->id,
                'receipt_number' => 'M-' . time() . '-' . rand(100, 999),
                'date' => $date,
                'description' => "{$payroll->year}/{$payroll->month} Dönemi Maaş Tahakkuku",
                'type' => 'regular',
                'is_approved' => true,
            ]);

            // 2. Borç Kaydı: 770 Genel Yönetim Giderleri (Personel Giderleri)
            $acc770 = Account::where('company_id', $payroll->company_id)->where('code', 'like', '770%')->first();
            LedgerEntry::create([
                'company_id' => $payroll->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $acc770 ? $acc770->id : null,
                'debit' => $totalCost,
                'credit' => 0,
                'description' => 'Maaş ve SGK Giderleri'
            ]);

            // 3. Alacak Kaydı: 335 Personele Borçlar
            $acc335 = Account::where('company_id', $payroll->company_id)->where('code', 'like', '335%')->first();
            LedgerEntry::create([
                'company_id' => $payroll->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $acc335 ? $acc335->id : null,
                'debit' => 0,
                'credit' => $totalNet,
                'description' => 'Ödenecek Net Maaşlar'
            ]);

            // 4. Alacak Kaydı: 360 Ödenecek Vergi ve Fonlar
            $acc360 = Account::where('company_id', $payroll->company_id)->where('code', 'like', '360%')->first();
            LedgerEntry::create([
                'company_id' => $payroll->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $acc360 ? $acc360->id : null,
                'debit' => 0,
                'credit' => $totalVergi,
                'description' => 'Ödenecek Gelir ve Damga Vergisi'
            ]);

            // 5. Alacak Kaydı: 361 Ödenecek Sosyal Güvenlik Kesintileri
            $acc361 = Account::where('company_id', $payroll->company_id)->where('code', 'like', '361%')->first();
            LedgerEntry::create([
                'company_id' => $payroll->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $acc361 ? $acc361->id : null,
                'debit' => 0,
                'credit' => $totalSGK,
                'description' => 'Ödenecek SGK Primleri'
            ]);

            $payroll->update(['status' => 'posted']);
        });

        return back()->with('success', 'Bordro başarıyla muhasebeleştirildi ve yevmiye fişi oluşturuldu.');
    }
}

<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Cheque;
use Modules\Accounting\Models\PromissoryNote;

class ChequeNotePortfolioController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        // Özet istatistikler
        $stats = [
            'received_cheques_count' => Cheque::where('company_id', $companyId)
                                             ->received()
                                             ->portfolio()
                                             ->count(),
            'received_cheques_amount' => Cheque::where('company_id', $companyId)
                                              ->received()
                                              ->portfolio()
                                              ->sum('amount'),
            'issued_cheques_count' => Cheque::where('company_id', $companyId)
                                           ->issued()
                                           ->portfolio()
                                           ->count(),
            'issued_cheques_amount' => Cheque::where('company_id', $companyId)
                                            ->issued()
                                            ->portfolio()
                                            ->sum('amount'),
            'received_notes_count' => PromissoryNote::where('company_id', $companyId)
                                                   ->received()
                                                   ->portfolio()
                                                   ->count(),
            'received_notes_amount' => PromissoryNote::where('company_id', $companyId)
                                                    ->received()
                                                    ->portfolio()
                                                    ->sum('amount'),
            'issued_notes_count' => PromissoryNote::where('company_id', $companyId)
                                                 ->issued()
                                                 ->portfolio()
                                                 ->count(),
            'issued_notes_amount' => PromissoryNote::where('company_id', $companyId)
                                                  ->issued()
                                                  ->portfolio()
                                                  ->sum('amount'),
        ];
        
        // Yaklaşan vadeler (30 gün içinde)
        $upcomingCheques = Cheque::where('company_id', $companyId)
                                ->upcoming(30)
                                ->with('contact')
                                ->orderBy('due_date')
                                ->limit(10)
                                ->get();
        
        $upcomingNotes = PromissoryNote::where('company_id', $companyId)
                                      ->upcoming(30)
                                      ->with('contact')
                                      ->orderBy('due_date')
                                      ->limit(10)
                                      ->get();
        
        // Vadesi geçenler
        $overdueCheques = Cheque::where('company_id', $companyId)
                               ->overdue()
                               ->with('contact')
                               ->orderBy('due_date')
                               ->get();
        
        $overdueNotes = PromissoryNote::where('company_id', $companyId)
                                     ->overdue()
                                     ->with('contact')
                                     ->orderBy('due_date')
                                     ->get();
        
        return view('accounting::portfolio.index', compact(
            'stats',
            'upcomingCheques',
            'upcomingNotes',
            'overdueCheques',
            'overdueNotes'
        ));
    }

    public function receivedCheques()
    {
        $companyId = auth()->user()->company_id;
        
        $cheques = Cheque::where('company_id', $companyId)
                        ->received()
                        ->portfolio()
                        ->with('contact')
                        ->orderBy('due_date')
                        ->paginate(20);
        
        return view('accounting::portfolio.received-cheques', compact('cheques'));
    }

    public function issuedCheques()
    {
        $companyId = auth()->user()->company_id;
        
        $cheques = Cheque::where('company_id', $companyId)
                        ->issued()
                        ->portfolio()
                        ->with('contact')
                        ->orderBy('due_date')
                        ->paginate(20);
        
        return view('accounting::portfolio.issued-cheques', compact('cheques'));
    }

    public function receivedNotes()
    {
        $companyId = auth()->user()->company_id;
        
        $notes = PromissoryNote::where('company_id', $companyId)
                              ->received()
                              ->portfolio()
                              ->with('contact')
                              ->orderBy('due_date')
                              ->paginate(20);
        
        return view('accounting::portfolio.received-notes', compact('notes'));
    }

    public function issuedNotes()
    {
        $companyId = auth()->user()->company_id;
        
        $notes = PromissoryNote::where('company_id', $companyId)
                              ->issued()
                              ->portfolio()
                              ->with('contact')
                              ->orderBy('due_date')
                              ->paginate(20);
        
        return view('accounting::portfolio.issued-notes', compact('notes'));
    }

    public function upcomingDueDates()
    {
        $companyId = auth()->user()->company_id;
        
        // Önümüzdeki 60 gün
        $cheques = Cheque::where('company_id', $companyId)
                        ->upcoming(60)
                        ->with('contact')
                        ->orderBy('due_date')
                        ->get();
        
        $notes = PromissoryNote::where('company_id', $companyId)
                              ->upcoming(60)
                              ->with('contact')
                              ->orderBy('due_date')
                              ->get();
        
        return view('accounting::portfolio.upcoming', [
            'upcomingCheques' => $cheques,
            'upcomingNotes' => $notes
        ]);
    }
}

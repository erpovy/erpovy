<?php

namespace Modules\HumanResources\Services;

class PayrollCalculator
{
    /**
     * Bordro kalemi hesapla
     * 
     * @param float $grossSalary Brüt Sabit Maaş
     * @param float $cumulativeMatrah Önceki aydan devreden kümülatif GV matrahı
     * @param array $params Yasal parametreler
     * @param float $bonus Ek ödemeler (ikramiye vb.)
     * @param float $overtimeHours Toplam Fazla Mesai Saati
     * @param int $unpaidLeaveDays Ücretsiz İzin Gün Sayısı
     * @return array Hesaplama sonuçları
     */
    public function calculate(
        float $grossSalary, 
        float $cumulativeMatrah, 
        array $params, 
        float $bonus = 0, 
        float $overtimeHours = 0, 
        int $unpaidLeaveDays = 0
    ) {
        // 1. Ücretsiz İzin Kesintisi (30 gün üzerinden)
        $unpaidLeaveDeduction = ($grossSalary / 30) * $unpaidLeaveDays;
        $baseGross = max(0, $grossSalary - $unpaidLeaveDeduction);

        // 2. Fazla Mesai Ücreti Hesaplama
        // Not: Genelde 1 saatlik ücret = Brüt Maaş / 225 saat (Türkiye standardı)
        $hourlyRate = $grossSalary / 225;
        $overtimePay = $overtimeHours * $hourlyRate * 1.5; // Sabit 1.5 kat varsayıyoruz, modelden gelen multiplier da kullanılabilir

        $totalGross = $baseGross + $bonus + $overtimePay;

        // 3. SGK Kesintileri
        $sgkWorker = $totalGross * ($params['sgk_worker_rate'] / 100);
        $unemploymentWorker = $totalGross * ($params['unemployment_worker_rate'] / 100);
        
        // 4. Gelir Vergisi Matrahı
        $gvMatrah = $totalGross - ($sgkWorker + $unemploymentWorker);
        $newCumulativeMatrah = $cumulativeMatrah + $gvMatrah;
        
        // 5. Hesaplanan Gelir Vergisi (Dilimli)
        $calculatedGv = $this->calculateIncomeTax($newCumulativeMatrah, $gvMatrah, $params['income_tax_brackets']);
        
        // 6. Damga Vergisi
        $calculatedDv = $totalGross * $params['stamp_tax_rate'];
        
        // 7. İstisnalar (Asgari Ücret kadar olan kısım)
        $minWageGross = $params['min_wage_gross'];
        $minSgkWorker = $minWageGross * ($params['sgk_worker_rate'] / 100);
        $minUnemploymentWorker = $minWageGross * ($params['unemployment_worker_rate'] / 100);
        $minGvMatrah = $minWageGross - ($minSgkWorker + $minUnemploymentWorker);
        
        $gvExemption = $this->calculateIncomeTax($minGvMatrah, $minGvMatrah, $params['income_tax_brackets']);
        $dvExemption = $minWageGross * $params['stamp_tax_rate'];
        
        // İstisnaları Uygula (Hesaplanan vergiden fazla olamaz)
        $finalGv = max(0, $calculatedGv - $gvExemption);
        $finalDv = max(0, $calculatedDv - $dvExemption);
        
        // 8. Net Maaş
        $netSalary = $totalGross - ($sgkWorker + $unemploymentWorker + $finalGv + $finalDv);
        $finalNetPaid = $netSalary; 
        
        // 9. İşveren Maliyeti
        $sgkEmployer = $totalGross * ($params['sgk_employer_rate'] / 100);
        $unemploymentEmployer = $totalGross * ($params['unemployment_employer_rate'] / 100);
        $totalCost = $totalGross + $sgkEmployer + $unemploymentEmployer;

        return [
            'gross_salary' => $grossSalary,
            'total_gross' => $totalGross,
            'overtime_pay' => $overtimePay,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'sgk_worker_cut' => $sgkWorker,
            'unemployment_worker_cut' => $unemploymentWorker,
            'income_tax_base' => $gvMatrah,
            'cumulative_income_tax_base' => $newCumulativeMatrah,
            'calculated_income_tax' => $calculatedGv,
            'calculated_stamp_tax' => $calculatedDv,
            'income_tax_exemption' => $gvExemption,
            'stamp_tax_exemption' => $dvExemption,
            'net_salary' => $netSalary,
            'final_net_paid' => $finalNetPaid,
            'sgk_employer_cut' => $sgkEmployer,
            'unemployment_employer_cut' => $unemploymentEmployer,
            'total_employer_cost' => $totalCost
        ];
    }

    /**
     * Dilimli Gelir Vergisi Hesapla
     */
    private function calculateIncomeTax(float $newCumulative, float $currentMatrah, array $brackets)
    {
        $tax = 0;
        $remainingMatrah = $currentMatrah;
        $oldCumulative = $newCumulative - $currentMatrah;

        foreach ($brackets as $bracket) {
            $limit = $bracket['limit'];
            $rate = $bracket['rate'] / 100;

            if ($oldCumulative < $limit) {
                $taxableInThisBracket = min($remainingMatrah, $limit - $oldCumulative);
                if ($taxableInThisBracket > 0) {
                    $tax += $taxableInThisBracket * $rate;
                    $remainingMatrah -= $taxableInThisBracket;
                    $oldCumulative += $taxableInThisBracket;
                }
            }
            
            if ($remainingMatrah <= 0) break;
        }
        
        // Eğer matrah kaldıysa ve dilimler bittiyse son dilimden hesapla
        if ($remainingMatrah > 0) {
            $lastBracket = end($brackets);
            $tax += $remainingMatrah * ($lastBracket['rate'] / 100);
        }

        return $tax;
    }
}

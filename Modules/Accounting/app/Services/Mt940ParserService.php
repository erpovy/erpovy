<?php

namespace Modules\Accounting\Services;

class Mt940ParserService
{
    /**
     * MT940 dosya içeriğini parse eder.
     * 
     * @param string $content
     * @return array
     */
    public function parse(string $content): array
    {
        $transactions = [];
        $lines = explode("\n", str_replace("\r", "", $content));
        
        $currentTransaction = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // :61: İşlem Satırı (Tarih, Tutar, Tip, Referans)
            // Örn: :61:2602160216CD1250,00NTRFNONREF
            if (strpos($line, ':61:') === 0) {
                if ($currentTransaction) {
                    $transactions[] = $currentTransaction;
                }

                $data = substr($line, 4);
                
                // İlk 6 karakter tarih (YYMMDD)
                $dateStr = substr($data, 0, 6);
                $year = "20" . substr($dateStr, 0, 2);
                $month = substr($dateStr, 2, 2);
                $day = substr($dateStr, 4, 2);
                $date = "{$year}-{$month}-{$day}";

                // CD (Debit/Credit) kontrolü
                $isCredit = strpos($data, 'CR') !== false || strpos($data, 'C') !== false;
                $isDebit = strpos($data, 'DR') !== false || strpos($data, 'D') !== false;
                
                // Tutarı ayıkla (Basit mantık: Virgülü noktaya çevir)
                preg_match('/([0-9]+,[0-9]+)/', $data, $matches);
                $amount = isset($matches[1]) ? (float) str_replace(',', '.', $matches[1]) : 0;

                $currentTransaction = [
                    'date' => $date,
                    'amount' => $amount,
                    'type' => $isCredit ? 'income' : 'expense',
                    'reference' => '',
                    'description' => ''
                ];
            }
            // :86: Açıklama Satırı
            elseif (strpos($line, ':86:') === 0 && $currentTransaction) {
                $currentTransaction['description'] = trim(substr($line, 4));
            }
            // Çok satırlı açıklama devamı
            elseif ($currentTransaction && strpos($line, ':') !== 0) {
                $currentTransaction['description'] .= " " . $line;
            }
        }

        if ($currentTransaction) {
            $transactions[] = $currentTransaction;
        }

        return $transactions;
    }
}

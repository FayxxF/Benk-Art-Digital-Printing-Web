<?php

namespace App\Services;

use App\Models\OrderDetail;
use Illuminate\Support\Facades\Cache;

class AprioriService
{
    private float $minSupport;
    private float $minConfidence;
    private array $transactions = [];
    private int $totalTransactions = 0;

    public function __construct(float $minSupport = 0.1, float $minConfidence = 0.5)
    {
        $this->minSupport    = $minSupport;
        $this->minConfidence = $minConfidence;
    }

    /**
     * Load transaksi dari database (order completed)
     */
    private function loadTransactions(): void
    {
        // Ambil semua order completed, group by order_id
        $details = OrderDetail::whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->with('product')
            ->get()
            ->groupBy('order_id');

        $this->transactions = [];
        foreach ($details as $orderId => $items) {
            $productIds = $items->pluck('product_id')->unique()->sort()->values()->toArray();
            if (count($productIds) > 0) {
                $this->transactions[] = $productIds;
            }
        }

        $this->totalTransactions = count($this->transactions);
    }

    /**
     * Hitung support untuk sebuah itemset
     */
    private function calculateSupport(array $itemset): float
    {
        if ($this->totalTransactions === 0) return 0;

        $count = 0;
        foreach ($this->transactions as $transaction) {
            // Cek apakah semua item dalam itemset ada di transaksi
            if (count(array_intersect($itemset, $transaction)) === count($itemset)) {
                $count++;
            }
        }

        return $count / $this->totalTransactions;
    }

    /**
     * Generate semua 1-itemset yang lolos min support
     */
    private function getFrequentOneItemsets(): array
    {
        $items = [];
        foreach ($this->transactions as $transaction) {
            foreach ($transaction as $item) {
                $items[$item] = ($items[$item] ?? 0) + 1;
            }
        }

        $frequent = [];
        foreach ($items as $item => $count) {
            $support = $count / $this->totalTransactions;
            if ($support >= $this->minSupport) {
                $frequent[] = [
                    'itemset' => [$item],
                    'support' => $support,
                    'count'   => $count,
                ];
            }
        }

        return $frequent;
    }

    /**
     * Generate kandidat k-itemset dari (k-1)-itemset
     */
    private function generateCandidates(array $frequentItemsets): array
    {
        $candidates = [];
        $count = count($frequentItemsets);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $itemsetA = $frequentItemsets[$i]['itemset'];
                $itemsetB = $frequentItemsets[$j]['itemset'];

                // Join: gabungkan jika k-1 item pertama sama
                $prefix_a = array_slice($itemsetA, 0, -1);
                $prefix_b = array_slice($itemsetB, 0, -1);

                if ($prefix_a === $prefix_b) {
                    $candidate = array_unique(array_merge($itemsetA, $itemsetB));
                    sort($candidate);
                    if (!in_array($candidate, array_column($candidates, 'itemset'))) {
                        $candidates[] = ['itemset' => $candidate];
                    }
                }
            }
        }

        return $candidates;
    }

    /**
     * Jalankan algoritma Apriori — return semua frequent itemsets
     */
    private function runApriori(): array
    {
        $this->loadTransactions();

        if ($this->totalTransactions === 0) return [];

        $allFrequent = [];

        // L1: frequent 1-itemsets
        $currentFrequent = $this->getFrequentOneItemsets();
        $allFrequent = array_merge($allFrequent, $currentFrequent);

        // L2, L3, ... sampai tidak ada lagi
        while (!empty($currentFrequent) && count($currentFrequent) > 1) {
            $candidates = $this->generateCandidates($currentFrequent);

            $nextFrequent = [];
            foreach ($candidates as $candidate) {
                $support = $this->calculateSupport($candidate['itemset']);
                if ($support >= $this->minSupport) {
                    $candidate['support'] = $support;
                    $candidate['count']   = round($support * $this->totalTransactions);
                    $nextFrequent[]       = $candidate;
                    $allFrequent[]        = $candidate;
                }
            }

            $currentFrequent = $nextFrequent;
        }

        return $allFrequent;
    }

    /**
     * Generate association rules dari frequent itemsets
     */
    public function generateRules(): array
    {
        return Cache::remember('apriori_rules', 3600, function () {
            $frequentItemsets = $this->runApriori();

            if (empty($frequentItemsets)) return [];

            $rules = [];

            foreach ($frequentItemsets as $itemset) {
                if (count($itemset['itemset']) < 2) continue;

                // Generate semua subset sebagai antecedent
                $subsets = $this->getSubsets($itemset['itemset']);

                foreach ($subsets as $antecedent) {
                    $consequent = array_values(array_diff($itemset['itemset'], $antecedent));
                    if (empty($consequent)) continue;

                    $supportAntecedent = $this->calculateSupport($antecedent);
                    if ($supportAntecedent === 0) continue;

                    $confidence = $itemset['support'] / $supportAntecedent;

                    if ($confidence >= $this->minConfidence) {
                        $rules[] = [
                            'antecedent'  => $antecedent,
                            'consequent'  => $consequent,
                            'support'     => round($itemset['support'], 4),
                            'confidence'  => round($confidence, 4),
                            'lift'        => round($confidence / $this->calculateSupport($consequent), 4),
                        ];
                    }
                }
            }

            // Sort by confidence desc, lalu support desc
            usort($rules, fn($a, $b) =>
                $b['confidence'] <=> $a['confidence'] ?: $b['support'] <=> $a['support']
            );

            return $rules;
        });
    }

    /**
     * Rekomendasikan produk berdasarkan produk yang sedang dilihat
     */
    public function recommend(int $productId, int $limit = 5): array
    {
        $rules = $this->generateRules();

        $recommendations = [];
        foreach ($rules as $rule) {
            // Cari rule yang antecedent-nya mengandung product ini
            if (in_array($productId, $rule['antecedent'])) {
                foreach ($rule['consequent'] as $recProductId) {
                    if ($recProductId !== $productId) {
                        $key = $recProductId;
                        if (!isset($recommendations[$key])) {
                            $recommendations[$key] = [
                                'product_id' => $recProductId,
                                'confidence' => $rule['confidence'],
                                'support'    => $rule['support'],
                                'lift'       => $rule['lift'],
                            ];
                        } else {
                            // Ambil confidence tertinggi
                            if ($rule['confidence'] > $recommendations[$key]['confidence']) {
                                $recommendations[$key]['confidence'] = $rule['confidence'];
                            }
                        }
                    }
                }
            }
        }

        // Sort by confidence
        usort($recommendations, fn($a, $b) => $b['confidence'] <=> $a['confidence']);

        return array_slice($recommendations, 0, $limit);
    }

    /**
     * Generate semua non-empty proper subsets dari array
     */
    private function getSubsets(array $items): array
    {
        $subsets = [];
        $count   = count($items);
        $total   = pow(2, $count);

        for ($i = 1; $i < $total - 1; $i++) {
            $subset = [];
            for ($j = 0; $j < $count; $j++) {
                if ($i & (1 << $j)) {
                    $subset[] = $items[$j];
                }
            }
            $subsets[] = $subset;
        }

        return $subsets;
    }

    /**
     * Hapus cache rules (panggil saat ada order baru)
     */
    public static function clearCache(): void
    {
        Cache::forget('apriori_rules');
    }
}

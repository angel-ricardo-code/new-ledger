<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function overview(Request $request): JsonResponse
    {
        $months = (int) ($request->months ?? 6);
        $result = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            $txs = Transaction::where('user_id', auth()->id())
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();

            $income = $txs->where('type', 'income')->sum('amount');
            $expense = $txs->where('type', 'expense')->sum('amount');
            $reconciliation = $txs->where('type', 'reconciliation')->sum('amount');

            $result[] = [
                'month' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'reconciliation' => (float) $reconciliation,
                'balance' => (float) ($income - $expense + $reconciliation),
            ];
        }

        return response()->json($result);
    }

    public function topTransactions(Request $request): JsonResponse
    {
        $month = $request->month ?? now()->format('Y-m');
        [$year, $monthNum] = explode('-', $month);
        $limit = (int) ($request->limit ?? 5);

        $query = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->with('category');

        $income = (clone $query)->where('type', 'income')
            ->orderBy('amount', 'desc')
            ->take($limit)
            ->get()
            ->map(fn($t) => $this->formatTransaction($t));

        $expense = (clone $query)->where('type', 'expense')
            ->orderBy('amount', 'desc')
            ->take($limit)
            ->get()
            ->map(fn($t) => $this->formatTransaction($t));

        return response()->json([
            'top_income' => $income,
            'top_expense' => $expense,
        ]);
    }

    public function heatmap(Request $request): JsonResponse
    {
        $year = (int) ($request->year ?? now()->year);
        $start = "{$year}-01-01";
        $end = "{$year}-12-31";

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$start, $end])
            ->get()
            ->groupBy(fn($t) => $t->date->format('Y-m-d'));

        $result = [];
        $current = new \DateTime($start);
        $endDate = new \DateTime($end);

        while ($current <= $endDate) {
            $date = $current->format('Y-m-d');
            $dayTx = $transactions->get($date, collect());

            $expense = (float) $dayTx->where('type', 'expense')->sum('amount');
            $income = (float) $dayTx->where('type', 'income')->sum('amount');
            $reconciliation = (float) $dayTx->where('type', 'reconciliation')->sum('amount');

            $result[] = [
                'date' => $date,
                'income' => $income,
                'expense' => $expense,
                'reconciliation' => $reconciliation,
                'net' => $income - $expense + $reconciliation,
            ];

            $current->modify('+1 day');
        }

        return response()->json($result);
    }

    public function weekday(Request $request): JsonResponse
    {
        $month = $request->month ?? now()->format('Y-m');
        [$year, $monthNum] = explode('-', $month);

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->where('type', 'expense')
            ->get();

        $daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $weekdayData = array_fill(0, 7, ['day' => 0, 'label' => '', 'total' => 0, 'count' => 0]);

        foreach ($daysOfWeek as $i => $label) {
            $weekdayData[$i] = ['day' => $i, 'label' => $label, 'total' => 0, 'count' => 0];
        }

        foreach ($transactions as $t) {
            $dow = (int) $t->date->format('w');
            $weekdayData[$dow]['total'] += (float) $t->amount;
            $weekdayData[$dow]['count']++;
        }

        return response()->json($weekdayData);
    }

    public function forecast(Request $request): JsonResponse
    {
        $horizon = min((int) ($request->horizon ?? 3), 6);

        $transactions = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->where('date', '<', now()->startOfMonth()->toDateString())
            ->get(['date', 'amount']);

        $monthlyData = $transactions->groupBy(fn($t) => $t->date->format('Y-m'))
            ->map(fn($g, $month) => [
                'month' => $month,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'expense' => round((float) $g->sum('amount'), 2),
            ])
            ->sortBy('month')
            ->values()
            ->toArray();

        $values = array_column($monthlyData, 'expense');
        $count = count($values);

        $predictions = [];
        $mae = 0;
        $method = 'insufficient_data';

        if ($count >= 24) {
            $method = 'holt-winters';
            $result = $this->optimizeHoltWinters($values, 12, $horizon);
            $predictions = $result['predictions'];
            $mae = $result['mae'];
        } elseif ($count >= 12) {
            $method = 'holt-linear';
            $result = $this->optimizeHoltLinear($values, $horizon);
            $predictions = $result['predictions'];
            $mae = $result['mae'];
        } elseif ($count >= 6) {
            $method = 'moving-average';
            $result = $this->movingAverage($values, $horizon);
            $predictions = $result['predictions'];
            $mae = $result['mae'];
        }

        $lastMonth = count($monthlyData) > 0 ? end($monthlyData)['month'] : null;
        foreach ($predictions as &$pred) {
            $nextMonth = $lastMonth
                ? date('Y-m', strtotime($lastMonth . ' +1 month'))
                : now()->addMonth()->format('Y-m');
            $lastMonth = $nextMonth;
            $pred['month'] = $nextMonth;
            $pred['label'] = \Carbon\Carbon::createFromFormat('Y-m', $nextMonth)->format('M Y');
            $pred['predicted'] = max(0, round($pred['predicted'], 2));
            $pred['lower'] = max(0, round($pred['lower'], 2));
            $pred['upper'] = round($pred['upper'], 2);
        }
        unset($pred);

        return response()->json([
            'historical' => $monthlyData,
            'predictions' => $predictions,
            'mae' => round($mae, 2),
            'method' => $method,
            'total_months' => $count,
        ]);
    }

    private function formatTransaction($t): array
    {
        return [
            'id' => $t->id,
            'amount' => (float) $t->amount,
            'note' => $t->note,
            'date' => $t->date->format('Y-m-d'),
            'category' => $t->category ? [
                'id' => $t->category->id,
                'name' => $t->category->name,
                'color_hex' => $t->category->color_hex,
                'icon' => $t->category->icon,
            ] : null,
        ];
    }

    private function optimizeHoltWinters(array $data, int $season, int $horizon): array
    {
        $best = null;
        $alphas = [0.1, 0.2, 0.3, 0.4, 0.5];
        $betas = [0.05, 0.1, 0.2];
        $gammas = [0.1, 0.2, 0.3, 0.4, 0.5];

        foreach ($alphas as $alpha) {
            foreach ($betas as $beta) {
                foreach ($gammas as $gamma) {
                    $result = $this->runHoltWinters($data, $season, $alpha, $beta, $gamma, $horizon);
                    if ($best === null || $result['mae'] < $best['mae']) {
                        $best = $result;
                    }
                }
            }
        }

        return $best ?? ['predictions' => [], 'mae' => 0];
    }

    private function runHoltWinters(array $data, int $season, float $alpha, float $beta, float $gamma, int $horizon): array
    {
        $n = count($data);

        $level = array_sum(array_slice($data, 0, $season)) / $season;

        $trend = 0;
        $trendCount = 0;
        for ($i = $season; $i < min(2 * $season, $n); $i++) {
            $trend += ($data[$i] - $data[$i - $season]) / $season;
            $trendCount++;
        }
        $trend = $trendCount > 0 ? $trend / $trendCount : 0;

        $seasonals = [];
        for ($i = 0; $i < $season; $i++) {
            $sum = 0;
            $cnt = 0;
            for ($j = $i; $j < $n; $j += $season) {
                $sum += $data[$j];
                $cnt++;
            }
            $seasonals[$i] = $cnt > 0 ? $sum / $cnt : 0;
        }
        $avgSeasonal = array_sum($seasonals) / $season;
        foreach ($seasonals as &$s) {
            $s -= $avgSeasonal;
        }
        unset($s);

        $errors = [];
        for ($i = $season; $i < $n; $i++) {
            $forecast = $level + $trend + $seasonals[$i % $season];
            $error = $data[$i] - $forecast;
            $errors[] = abs($error);

            $lastLevel = $level;
            $level = $alpha * ($data[$i] - $seasonals[$i % $season]) + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $lastLevel) + (1 - $beta) * $trend;
            $seasonals[$i % $season] = $gamma * ($data[$i] - $level) + (1 - $gamma) * $seasonals[$i % $season];
        }

        $mae = count($errors) > 0 ? array_sum($errors) / count($errors) : 0;

        $predictions = [];
        for ($k = 1; $k <= $horizon; $k++) {
            $pred = $level + $k * $trend + $seasonals[($n - $season + $k) % $season];
            $predictions[] = [
                'predicted' => max(0, $pred),
                'lower' => max(0, $pred - 1.96 * $mae),
                'upper' => $pred + 1.96 * $mae,
            ];
        }

        return compact('mae', 'predictions');
    }

    private function optimizeHoltLinear(array $data, int $horizon): array
    {
        $best = null;
        $alphas = [0.1, 0.2, 0.3, 0.4, 0.5, 0.7];
        $betas = [0.05, 0.1, 0.2, 0.3];

        foreach ($alphas as $alpha) {
            foreach ($betas as $beta) {
                $result = $this->runHoltLinear($data, $alpha, $beta, $horizon);
                if ($best === null || $result['mae'] < $best['mae']) {
                    $best = $result;
                }
            }
        }

        return $best ?? ['predictions' => [], 'mae' => 0];
    }

    private function runHoltLinear(array $data, float $alpha, float $beta, int $horizon): array
    {
        $n = count($data);
        $level = $data[0];
        $trend = $data[1] - $data[0];
        $errors = [];

        for ($i = 1; $i < $n; $i++) {
            $forecast = $level + $trend;
            $error = $data[$i] - $forecast;
            $errors[] = abs($error);

            $lastLevel = $level;
            $level = $alpha * $data[$i] + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $lastLevel) + (1 - $beta) * $trend;
        }

        $mae = count($errors) > 0 ? array_sum($errors) / count($errors) : 0;

        $predictions = [];
        for ($k = 1; $k <= $horizon; $k++) {
            $pred = $level + $k * $trend;
            $predictions[] = [
                'predicted' => max(0, $pred),
                'lower' => max(0, $pred - 1.96 * $mae),
                'upper' => $pred + 1.96 * $mae,
            ];
        }

        return compact('mae', 'predictions');
    }

    private function movingAverage(array $data, int $horizon): array
    {
        $window = min(3, count($data));
        $recent = array_slice($data, -$window);
        $avg = array_sum($recent) / $window;

        $errors = [];
        foreach ($data as $v) {
            $errors[] = abs($v - $avg);
        }
        $mae = count($errors) > 0 ? array_sum($errors) / count($errors) : 0;

        $predictions = [];
        for ($k = 1; $k <= $horizon; $k++) {
            $predictions[] = [
                'predicted' => max(0, $avg),
                'lower' => max(0, $avg - 1.96 * $mae),
                'upper' => $avg + 1.96 * $mae,
            ];
        }

        return compact('mae', 'predictions');
    }
}

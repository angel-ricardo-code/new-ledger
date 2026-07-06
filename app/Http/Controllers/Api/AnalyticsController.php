<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function overview(Request $request): JsonResponse
    {
        $months = (int) ($request->months ?? 6);
        return response()->json(Cache::remember('overview_' . auth()->id() . '_' . $months, 300, function () use ($months) {
            $startDate = now()->subMonths($months - 1)->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');

            $rows = Transaction::where('user_id', auth()->id())
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw("to_char(date, 'YYYY-MM') as month_key, type, SUM(amount) as total")
                ->groupByRaw("to_char(date, 'YYYY-MM'), type")
                ->orderBy('month_key')
                ->get();

            $grouped = [];
            foreach ($rows as $row) {
                $key = $row->month_key;
                if (!isset($grouped[$key])) {
                    $grouped[$key] = ['income' => 0, 'expense' => 0, 'reconciliation' => 0];
                }
                $grouped[$key][$row->type] = (float) $row->total;
            }

            $result = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthKey = $date->format('Y-m');
                $data = $grouped[$monthKey] ?? ['income' => 0, 'expense' => 0, 'reconciliation' => 0];
                $result[] = [
                    'month' => $monthKey,
                    'label' => $date->format('M Y'),
                    'income' => $data['income'],
                    'expense' => $data['expense'],
                    'reconciliation' => $data['reconciliation'],
                    'balance' => $data['income'] - $data['expense'] + $data['reconciliation'],
                ];
            }

            return $result;
        }));
    }

    public function topTransactions(Request $request): JsonResponse
    {
        $month = $request->month ?? now()->format('Y-m');
        $limit = (int) ($request->limit ?? 5);
        $startDate = "{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $query = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
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
        return response()->json(Cache::remember('heatmap_' . auth()->id() . '_' . $year, 300, function () use ($year) {
            $start = "{$year}-01-01";
            $end = "{$year}-12-31";

            $rows = Transaction::where('user_id', auth()->id())
                ->whereBetween('date', [$start, $end])
                ->selectRaw("date, type, SUM(amount) as total")
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get();

            $transactions = $rows->groupBy(fn($r) => $r->date->format('Y-m-d'));

            $result = [];
            $current = new \DateTime($start);
            $endDate = new \DateTime($end);

            while ($current <= $endDate) {
                $date = $current->format('Y-m-d');
                $dayTx = $transactions->get($date, collect());

                $expense = (float) $dayTx->where('type', 'expense')->sum('total');
                $income = (float) $dayTx->where('type', 'income')->sum('total');
                $reconciliation = (float) $dayTx->where('type', 'reconciliation')->sum('total');

                $result[] = [
                    'date' => $date,
                    'income' => $income,
                    'expense' => $expense,
                    'reconciliation' => $reconciliation,
                    'net' => $income - $expense + $reconciliation,
                ];

                $current->modify('+1 day');
            }

            return $result;
        }));
    }

    public function weekday(Request $request): JsonResponse
    {
        $month = $request->month ?? now()->format('Y-m');
        $startDate = "{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $rows = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->where('type', 'expense')
            ->selectRaw("EXTRACT(DOW FROM date) as dow, SUM(amount) as total, COUNT(*) as count")
            ->groupByRaw("EXTRACT(DOW FROM date)")
            ->get()
            ->keyBy('dow');

        $daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $weekdayData = [];
        foreach ($daysOfWeek as $i => $label) {
            $row = $rows->get($i);
            $weekdayData[] = [
                'day' => $i,
                'label' => $label,
                'total' => (float) ($row->total ?? 0),
                'count' => (int) ($row->count ?? 0),
            ];
        }

        return response()->json($weekdayData);
    }

    public function forecast(Request $request): JsonResponse
    {
        $horizon = min((int) ($request->horizon ?? 3), 6);

        return response()->json(Cache::remember('forecast_' . auth()->id(), 3600, function () use ($horizon) {

        $monthlyData = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->where('date', '<', now()->startOfMonth()->toDateString())
            ->where('date', '>=', now()->subMonths(60)->startOfMonth()->toDateString())
            ->selectRaw("to_char(date, 'YYYY-MM') as month_key, SUM(amount) as total")
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month_key,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $r->month_key)->format('M Y'),
                'expense' => round((float) $r->total, 2),
            ])
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

        return [
            'historical' => $monthlyData,
            'predictions' => $predictions,
            'mae' => round($mae, 2),
            'method' => $method,
            'total_months' => $count,
        ];
        }));
    }

    private function formatTransaction($t): array
    {
        return [
            'id' => $t->id,
            'type' => $t->type,
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

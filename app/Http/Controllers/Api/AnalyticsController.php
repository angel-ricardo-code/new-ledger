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

            $txs = Transaction::whereYear('date', $year)
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

        $query = Transaction::whereYear('date', $year)
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

    public function weekday(Request $request): JsonResponse
    {
        $month = $request->month ?? now()->format('Y-m');
        [$year, $monthNum] = explode('-', $month);

        $transactions = Transaction::whereYear('date', $year)
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
}

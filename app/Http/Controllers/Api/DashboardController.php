<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $month = $request->month ?? date('Y-m');
        [$year, $monthNum] = explode('-', $month);

        $startDate = "{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $transactions = Transaction::whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->with('category')
            ->get();

        $totals = $transactions->groupBy('type')->map(fn($g) => $g->sum('amount'));
        $incomeTotal = $totals->get('income', 0);
        $expenseTotal = $totals->get('expense', 0);
        $balance = $incomeTotal - $expenseTotal;

        $dailySeries = $transactions->groupBy(fn($t) => $t->date->format('Y-m-d'))
            ->map(function ($items, $date) {
                $result = ['date' => $date];
                foreach ($items->groupBy('type') as $type => $group) {
                    $result[$type] = (float) $group->sum('amount');
                }
                return $result;
            })
            ->sortBy('date')
            ->values();

        $categorySeries = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items, $categoryId) {
                return [
                    'category_id' => $categoryId,
                    'name' => $items->first()?->category?->name ?? 'Sin categoría',
                    'color' => $items->first()?->category?->color_hex ?? '#6E6E73',
                    'icon' => $items->first()?->category?->icon ?? 'circle',
                    'total' => (float) $items->sum('amount'),
                ];
            })
            ->values();

        $prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
        $prevStart = "{$prevMonth}-01";
        $prevEnd = date('Y-m-t', strtotime($prevStart));
        $prevTx = Transaction::whereBetween('date', [$prevStart, $prevEnd])->get();
        $prevIncome = $prevTx->where('type', 'income')->sum('amount');
        $prevExpense = $prevTx->where('type', 'expense')->sum('amount');
        $prevBalance = $prevIncome - $prevExpense;

        return response()->json([
            'balance' => $balance,
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal,
            'daily_series' => $dailySeries,
            'category_series' => $categorySeries,
            'vs_previous' => $balance - $prevBalance,
        ]);
    }
}

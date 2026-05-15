<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $month = $request->month ?? date('Y-m');

        return Cache::remember('dashboard_' . $month . '_user_' . auth()->id(), 60, function () use ($month) {
        [$year, $monthNum] = explode('-', $month);

        $startDate = "{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->with('category')
            ->get();

        $totals = $transactions->groupBy('type')->map(fn($g) => $g->sum('amount'));
        $incomeTotal = $totals->get('income', 0);
        $expenseTotal = $totals->get('expense', 0);
        $reconciliationTotal = $totals->get('reconciliation', 0);
        $balance = $incomeTotal - $expenseTotal + $reconciliationTotal;

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
        $prevTx = Transaction::where('user_id', auth()->id())->whereBetween('date', [$prevStart, $prevEnd])->get();
        $prevTotals = $prevTx->groupBy('type')->map(fn($g) => $g->sum('amount'));
        $prevIncome = $prevTotals->get('income', 0);
        $prevExpense = $prevTotals->get('expense', 0);
        $prevReconciliation = $prevTotals->get('reconciliation', 0);
        $prevBalance = $prevIncome - $prevExpense + $prevReconciliation;

        $lastRecon = Transaction::where('user_id', auth()->id())->where('type', 'reconciliation')
            ->latest('date')
            ->first();

        // KPI data
        $daysInMonth = (int) now()->format('j');
        $expenseDays = $transactions->where('type', 'expense')
            ->groupBy(fn($t) => $t->date->format('Y-m-d'))
            ->count();
        $avgDailyExpense = $daysInMonth > 0 ? $expenseTotal / $daysInMonth : 0;

        $topExpenseCat = $categorySeries->sortByDesc('total')->first();

        $biggestDay = $dailySeries->filter(fn($d) => ($d['expense'] ?? 0) > 0)
            ->sortByDesc(fn($d) => $d['expense'] ?? 0)
            ->first();

        $biggestTx = $transactions->where('type', 'expense')
            ->sortByDesc('amount')
            ->first();

        return response()->json([
            'balance' => $balance,
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal,
            'reconciliation_total' => $reconciliationTotal,
            'last_reconciliation' => $lastRecon ? [
                'date' => $lastRecon->date->format('Y-m-d'),
                'amount' => (float) $lastRecon->amount,
                'note' => $lastRecon->note,
            ] : null,
            'daily_series' => $dailySeries,
            'category_series' => $categorySeries,
            'vs_previous' => $balance - $prevBalance,
            'kpi' => [
                'avg_daily_expense' => round($avgDailyExpense, 2),
                'top_expense_category' => $topExpenseCat ? [
                    'name' => $topExpenseCat['name'],
                    'amount' => $topExpenseCat['total'],
                    'color' => $topExpenseCat['color'],
                    'icon' => $topExpenseCat['icon'],
                ] : null,
                'biggest_spending_day' => $biggestDay ? [
                    'date' => $biggestDay['date'],
                    'total' => $biggestDay['expense'],
                ] : null,
                'biggest_transaction' => $biggestTx ? [
                    'amount' => (float) $biggestTx->amount,
                    'note' => $biggestTx->note,
                    'date' => $biggestTx->date->format('Y-m-d'),
                    'category' => $biggestTx->category ? [
                        'name' => $biggestTx->category->name,
                        'color_hex' => $biggestTx->category->color_hex,
                        'icon' => $biggestTx->category->icon,
                    ] : null,
                ] : null,
                'days_without_expenses' => max(0, $daysInMonth - $expenseDays),
                'days_in_month' => $daysInMonth,
            ],
        ]);
        });
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BudgetController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = auth()->id();
        $month = request('month', date('Y-m'));
        [$year, $monthNum] = explode('-', $month);

        $budgets = Budget::where('user_id', $userId)
            ->with('category')
            ->get()
            ->map(function ($budget) use ($year, $monthNum) {
                $spent = (float) Transaction::where('user_id', auth()->id())
                    ->where('category_id', $budget->category_id)
                    ->where('type', 'expense')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthNum)
                    ->sum('amount');

                return [
                    'id' => $budget->id,
                    'category_id' => $budget->category_id,
                    'limit' => (float) $budget->limit,
                    'spent' => $spent,
                    'percentage' => $budget->limit > 0 ? round($spent / $budget->limit * 100, 1) : 0,
                    'category' => [
                        'name' => $budget->category->name,
                        'color_hex' => $budget->category->color_hex,
                        'icon' => $budget->category->icon,
                    ],
                ];
            });

        return response()->json($budgets);
    }

    public function store(StoreBudgetRequest $request): JsonResponse
    {
        $data = $request->validated();

        $budget = Budget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category_id' => $data['category_id'],
            ],
            ['limit' => $data['limit']]
        );

        $this->forgetDashboardCache();

        return response()->json([
            'id' => $budget->id,
            'category_id' => $budget->category_id,
            'limit' => (float) $budget->limit,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $budget = Budget::where('user_id', auth()->id())->findOrFail($id);
        $budget->delete();

        $this->forgetDashboardCache();

        return response()->json(['ok' => true]);
    }

    private function forgetDashboardCache(): void
    {
        $suffix = '_user_' . auth()->id();
        $month = request('month', date('Y-m'));
        Cache::forget('dashboard_' . $month . $suffix);
        $prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
        Cache::forget('dashboard_' . $prevMonth . $suffix);
    }
}

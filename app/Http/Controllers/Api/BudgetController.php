<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ClearsDashboardCache;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BudgetController extends Controller
{
    use ClearsDashboardCache {
        forgetDashboardCache as traitForgetDashboardCache;
    }

    protected function forgetDashboardCache(?string $date = null): void
    {
        $suffix = '_user_' . auth()->id();
        foreach (range(-12, 12) as $offset) {
            Cache::forget('dashboard_' . date('Y-m', strtotime("$offset months")) . $suffix);
        }
    }
    public function index(): JsonResponse
    {
        $userId = auth()->id();
        $month = request('month', date('Y-m'));
        $startDate = "{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $budgets = Budget::where('user_id', $userId)
            ->with('category')
            ->get();

        $categoryIds = $budgets->pluck('category_id')->filter()->values()->toArray();
        $spentByCategory = [];
        if (!empty($categoryIds)) {
            $spentByCategory = Transaction::where('user_id', $userId)
                ->whereIn('category_id', $categoryIds)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw("category_id, SUM(amount) as total")
                ->groupBy('category_id')
                ->pluck('total', 'category_id')
                ->map(fn($v) => (float) $v)
                ->toArray();
        }

        $budgets = $budgets->map(function ($budget) use ($spentByCategory) {
            $spent = $spentByCategory[$budget->category_id] ?? 0;
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

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ClearsDashboardCache;
use App\Http\Requests\StoreReconciliationRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class ReconciliationController extends Controller
{
    use ClearsDashboardCache;
    public function store(StoreReconciliationRequest $request): JsonResponse
    {
        $expected = (float) Transaction::where('user_id', auth()->id())
            ->whereIn('type', ['income', 'expense', 'reconciliation'])
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount END), 0)
                - COALESCE(SUM(CASE WHEN type = 'expense' THEN amount END), 0)
                + COALESCE(SUM(CASE WHEN type = 'reconciliation' THEN amount END), 0)
                as expected
            ")->value('expected');

        $difference = (float) $request->counted - $expected;

        if ($difference == 0) {
            return response()->json([
                'message' => 'El balance coincide exactamente',
                'difference' => 0,
            ]);
        }

        $note = 'Reconciliación';
        if ($request->note) {
            $note .= ': ' . $request->note;
        }

        $transaction = Transaction::create([
            'date' => now()->format('Y-m-d'),
            'amount' => $difference,
            'type' => 'reconciliation',
            'category_id' => null,
            'note' => $note,
        ]);

        $this->forgetDashboardCache(now()->format('Y-m-d'));

        return response()->json($transaction, 201);
    }

    public function index(): JsonResponse
    {
        $reconciliations = Transaction::where('user_id', auth()->id())->where('type', 'reconciliation')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($reconciliations);
    }
}

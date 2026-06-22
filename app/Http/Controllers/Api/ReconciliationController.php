<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReconciliationRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ReconciliationController extends Controller
{
    public function store(StoreReconciliationRequest $request): JsonResponse
    {
        $all = Transaction::where('user_id', auth()->id())->whereIn('type', ['income', 'expense', 'reconciliation'])->get();
        $expected = $all->sum(fn($t) => match ($t->type) {
            'income' => $t->amount,
            'expense' => -$t->amount,
            'reconciliation' => $t->amount,
            default => 0,
        });

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

        $month = now()->format('Y-m');
        $suffix = '_user_' . auth()->id();
        Cache::forget('dashboard_' . $month . $suffix);
        Cache::forget('dashboard_' . now()->subMonth()->format('Y-m') . $suffix);

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

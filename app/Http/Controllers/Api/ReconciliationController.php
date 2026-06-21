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
        $key = $request->validated()['idempotency_key'] ?? null;

        if ($key) {
            $existing = Transaction::where('user_id', auth()->id())
                ->where('idempotency_key', $key)->first();
            if ($existing) {
                return response()->json($existing, 200);
            }
        }

        $request->validate(['date' => ['nullable', 'date', 'before_or_equal:today']]);
        $all = Transaction::where('user_id', auth()->id())->whereIn('type', ['income', 'expense', 'reconciliation'])->get();
        $expected = $all->sum(fn($t) => match ($t->type) {
            'income' => $t->amount,
            'expense' => -$t->amount,
            'reconciliation' => $t->amount,
            default => 0,
        });

        $difference = (float) $request->counted - $expected;

        if (abs($difference) < 0.01) {
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
            'date' => $request->input('date', now()->format('Y-m-d')),
            'amount' => $difference,
            'type' => 'reconciliation',
            'category_id' => null,
            'note' => $note,
            'idempotency_key' => $key,
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ClearsDashboardCache;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    use ClearsDashboardCache;
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::where('user_id', auth()->id())->with('category');

        if ($request->filled('month')) {
            $startDate = $request->month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('note', 'ilike', "%{$q}%");
            });
        }

        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($transactions);
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = Transaction::create($request->validated());
        $transaction->load('category');
        $this->forgetDashboardCache($request->date ?? now()->format('Y-m-d'));
        Cache::forget('forecast_' . auth()->id());
        return response()->json($transaction, 201);
    }

    public function update(StoreTransactionRequest $request, int $id): JsonResponse
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $oldDate = $transaction->date->format('Y-m-d');
        $transaction->update($request->validated());
        $transaction->load('category');
        $this->forgetDashboardCache($oldDate);
        $this->forgetDashboardCache($transaction->date->format('Y-m-d'));
        Cache::forget('forecast_' . auth()->id());
        return response()->json($transaction);
    }

    public function destroy(int $id): JsonResponse
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $date = $transaction->date->format('Y-m-d');
        $transaction->delete();
        $this->forgetDashboardCache($date);
        Cache::forget('forecast_' . auth()->id());
        return response()->json(null, 204);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index(Request $request): Response|JsonResponse
    {
        $query = Transaction::with('category');

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->boolean('export')) {
            $transactions = $query->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
            $csv = "date,type,amount,category,note\n";
            foreach ($transactions as $t) {
                $category = str_replace('"', '""', $t->category?->name ?? '');
                $note = str_replace('"', '""', $t->note ?? '');
                $csv .= sprintf(
                    "%s,%s,%.2f,\"%s\",\"%s\"\n",
                    $t->date->format('Y-m-d'),
                    $t->type,
                    $t->amount,
                    $category,
                    $note
                );
            }

            $month = $request->month ?? date('Y-m');
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=ledger_export_{$month}.csv",
            ]);
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
        return response()->json($transaction, 201);
    }

    public function update(StoreTransactionRequest $request, int $id): JsonResponse
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->validated());
        $transaction->load('category');
        return response()->json($transaction);
    }

    public function destroy(int $id): JsonResponse
    {
        Transaction::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}

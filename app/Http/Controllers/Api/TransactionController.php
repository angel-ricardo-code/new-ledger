<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function index(Request $request): Response|JsonResponse
    {
        $query = Transaction::where('user_id', auth()->id())->with('category');

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('date', $year)->whereMonth('date', $month);
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
        $this->forgetDashboardCache($request->date ?? now()->format('Y-m-d'));
        return response()->json($transaction, 201);
    }

    public function update(StoreTransactionRequest $request, int $id): JsonResponse
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $transaction->update($request->validated());
        $transaction->load('category');
        $this->forgetDashboardCache($transaction->date->format('Y-m-d'));
        return response()->json($transaction);
    }

    public function destroy(int $id): JsonResponse
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $this->forgetDashboardCache($transaction->date->format('Y-m-d'));
        $transaction->delete();
        return response()->json(null, 204);
    }

    private function forgetDashboardCache(string $date): void
    {
        $month = substr($date, 0, 7);
        $suffix = '_user_' . auth()->id();
        Cache::forget('dashboard_' . $month . $suffix);
        $prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
        Cache::forget('dashboard_' . $prevMonth . $suffix);
    }
}

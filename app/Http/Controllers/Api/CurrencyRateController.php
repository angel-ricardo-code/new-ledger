<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index(): JsonResponse
    {
        $rates = CurrencyRate::where('user_id', auth()->id())->get()->keyBy('currency');
        return response()->json($rates);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'rates' => 'required|array',
            'rates.*.currency' => 'required|string|in:USD,EUR,MXN',
            'rates.*.rate_to_cup' => 'required|numeric|min:0.000001',
        ]);

        foreach ($request->rates as $rate) {
            CurrencyRate::updateOrCreate(
                ['user_id' => auth()->id(), 'currency' => $rate['currency']],
                ['rate_to_cup' => $rate['rate_to_cup']]
            );
        }

        return response()->json(CurrencyRate::where('user_id', auth()->id())->get()->keyBy('currency'));
    }
}

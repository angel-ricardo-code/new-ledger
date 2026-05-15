<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::whereNull('user_id')->orWhere('user_id', auth()->id())->orderBy('name')->get());
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
        return response()->json($category, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:income,expense'],
            'color_hex' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'in:utensils,car,zap,heart,film,shopping-bag,home,briefcase,book,gift,coffee,credit-card,smartphone,plane,dumbbell,music,paw-print,wallet,graduation-cap,circle,laptop,trending-up'],
        ]);

        $category->update($validated);
        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        Category::where('user_id', auth()->id())->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}

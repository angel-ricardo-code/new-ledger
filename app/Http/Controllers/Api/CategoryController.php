<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

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
        DashboardController::clearUserDashboardCache();
        return response()->json($category, 201);
    }

    public function update(StoreCategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $category->update($request->validated());
        DashboardController::clearUserDashboardCache();
        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        Category::where('user_id', auth()->id())->findOrFail($id)->delete();
        DashboardController::clearUserDashboardCache();
        return response()->json(null, 204);
    }
}

<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReconciliationController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::patch('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::patch('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::post('/reconciliation', [ReconciliationController::class, 'store']);
Route::get('/reconciliation', [ReconciliationController::class, 'index']);

Route::get('/analytics/overview', [AnalyticsController::class, 'overview']);
Route::get('/analytics/top-transactions', [AnalyticsController::class, 'topTransactions']);
Route::get('/analytics/weekday', [AnalyticsController::class, 'weekday']);

<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CurrencyRateController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\ReconciliationController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

RateLimiter::for('api', fn (Request $job) => Limit::perMinute(60)->by($job->user()?->id ?: $job->ip()));
RateLimiter::for('login', fn (Request $job) => Limit::perMinute(5)->by($job->ip()));
RateLimiter::for('register', fn (Request $job) => Limit::perHour(3)->by($job->ip()));

// Public routes
Route::post('/register', [AuthController::class, 'register'])->middleware([StartSession::class, 'throttle:register']);
Route::post('/login', [AuthController::class, 'login'])->middleware([StartSession::class, 'throttle:login']);

// Protected routes
Route::middleware(['throttle:api', 'auth:sanctum'])->group(function () {

Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::patch('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::patch('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/budgets', [BudgetController::class, 'index']);
Route::post('/budgets', [BudgetController::class, 'store']);
Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::post('/reconciliation', [ReconciliationController::class, 'store']);
Route::get('/reconciliation', [ReconciliationController::class, 'index']);

Route::get('/analytics/overview', [AnalyticsController::class, 'overview']);
Route::get('/analytics/top-transactions', [AnalyticsController::class, 'topTransactions']);
Route::get('/analytics/weekday', [AnalyticsController::class, 'weekday']);
Route::get('/analytics/heatmap', [AnalyticsController::class, 'heatmap']);

Route::get('/analytics/forecast', [AnalyticsController::class, 'forecast']);

Route::get('/export', [ExportController::class, 'report']);

Route::get('/currency-rates', [CurrencyRateController::class, 'index']);
Route::put('/currency-rates', [CurrencyRateController::class, 'update']);

});

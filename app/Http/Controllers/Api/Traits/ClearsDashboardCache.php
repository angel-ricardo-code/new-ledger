<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsDashboardCache
{
    protected function forgetDashboardCache(?string $date = null): void
    {
        $suffix = '_user_' . auth()->id();
        $month = $date ? substr($date, 0, 7) : date('Y-m');
        Cache::forget('dashboard_' . $month . $suffix);
        $nextMonth = date('Y-m', strtotime($month . '-01 +1 month'));
        Cache::forget('dashboard_' . $nextMonth . $suffix);
    }
}

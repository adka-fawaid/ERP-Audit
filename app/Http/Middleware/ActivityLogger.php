<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!auth()->check() || auth()->user()->role === 'admin' || !$request->isMethod('GET') || !$response->isSuccessful()) {
            return $response;
        }

        $activities = [
            'dashboard' => ['VIEW_DASHBOARD', 'Membuka Dashboard'],
            'programUtilization.index' => ['VIEW_PROGRAM', 'Membuka Utilisasi Program'],
            'anomalyLog.index' => ['VIEW_ANOMALY', 'Membuka Log Anomali'],
            'accessMatrix.index' => ['VIEW_ACCESS_MATRIX', 'Membuka Matriks Akses'],
        ];

        $routeName = optional($request->route())->getName();

        if (!isset($activities[$routeName])) {
            return $response;
        }

        [$activity, $description] = $activities[$routeName];
        ActivityLog::record($request, $activity, $description);

        return $response;
    }
}
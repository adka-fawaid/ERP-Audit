<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class AnomalyLogController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $search = trim($request->input('search', ''));
        $query = TrHist::whereRaw('DAYOFWEEK(trans_date) = 1');

        $years = (clone $query)
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($month !== 'all') {
            $query->whereMonth('trans_date', $month);
        }

        if ($year !== 'all') {
            $query->whereYear('trans_date', $year);
        }

        $stats = (clone $query)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT tr_user) as users')
            ->first();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('tr_user', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%");
            });
        }

        $users = (clone $query)
            ->select('tr_user')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('tr_user')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $anomalies = $query
            ->orderByDesc('trans_date')
            ->orderByDesc('trans_time')
            ->paginate(25)
            ->withQueryString();

        return view('anomalyLog.index', [
            'month' => $month,
            'year' => $year,
            'years' => $years,
            'search' => $search,
            'totalAnomalies' => $stats->total ?? 0,
            'usersInvolved' => $stats->users ?? 0,
            'weeklyActivities' => $stats->total ?? 0,
            'users' => $users,
            'anomalies' => $anomalies,
        ]);
    }
}
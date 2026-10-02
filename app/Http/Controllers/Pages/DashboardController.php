<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $years = TrHist::query()
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $query = TrHist::query();

        if ($month !== 'all') {
            $query->whereMonth('trans_date', $month);
        }

        if ($year !== 'all') {
            $query->whereYear('trans_date', $year);
        }

        $totalTransactions = (clone $query)->count();

        $totalActiveUsers = (clone $query)
            ->distinct('user')
            ->count('user');

        $totalPrograms = (clone $query)
            ->distinct('program')
            ->count('program');

        $sundayTransactions = (clone $query)
            ->whereRaw('DAYOFWEEK(trans_date) = 1')
            ->count();

        $dailyTransactions = (clone $query)
            ->selectRaw('trans_date, COUNT(*) as total')
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get();

        $topPrograms = (clone $query)
            ->selectRaw('program, COUNT(*) as total')
            ->groupBy('program')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $transTypes = (clone $query)
            ->selectRaw('trans_type, COUNT(*) as total')
            ->groupBy('trans_type')
            ->orderByDesc('total')
            ->get();

        return view('dashboard.index', compact(
            'month',
            'year',
            'years',
            'totalTransactions',
            'totalActiveUsers',
            'totalPrograms',
            'sundayTransactions',
            'dailyTransactions',
            'topPrograms',
            'transTypes'
        ));
    }
}
<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;    
use App\Models\TrHist;
use Illuminate\Http\Request;

class ProgramUtilizationController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $search = trim($request->input('search', ''));
        $status = $request->input('status', 'all');
        $years = TrHist::query()
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $query = TrHist::query();

        // Filter bulan
        if ($month !== 'all') {
            $query->whereMonth('trans_date', $month);
        }

        // Filter tahun
        if ($year !== 'all') {
            $query->whereYear('trans_date', $year);
        }

        // Statistik
        $allPrograms = (clone $query)
            ->select('program')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('program')
            ->get();

        $totalPrograms = $allPrograms->count();
        $activePrograms = $allPrograms->where('total', '>=', 50)->count();
        $rarePrograms = $allPrograms->whereBetween('total', [1, 49])->count();
        $totalTransactions = (clone $query)->count();

        // Filter search untuk tabel
        if ($search !== '') {
            $query->where('program', 'like', '%' . $search . '%');
        }

        // Ranking Program
        $programQuery = (clone $query)
            ->select('program')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN trans_type = 'ISS-SO' THEN 1 ELSE 0 END) as iss_so")
            ->selectRaw("SUM(CASE WHEN trans_type = 'RCT-PO' THEN 1 ELSE 0 END) as rct_po")
            ->selectRaw("SUM(CASE WHEN trans_type = 'ISS-WO' THEN 1 ELSE 0 END) as iss_wo")
            ->groupBy('program')
            ->orderByDesc('total');

        if ($status === 'active') {
            $programQuery->having('total', '>=', 50);
        }

        if ($status === 'rare') {
            $programQuery->havingBetween('total', [1, 49]);
        }

        // Pagination
        $programs = $programQuery
            ->paginate(10)
            ->withQueryString();

        return view('programUtilization.index', compact(
            'month',
            'year',
            'years',
            'search',
            'status',
            'totalPrograms',
            'activePrograms',
            'rarePrograms',
            'totalTransactions',
            'programs'
        ));
    }
}
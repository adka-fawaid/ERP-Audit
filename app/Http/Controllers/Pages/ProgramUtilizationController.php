<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class ProgramUtilizationController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->periodValue($request->input('month', now()->month), 1, 12, now()->month);
        $year = $this->periodValue($request->input('year', now()->year), 2000, 2100, now()->year);
        $program = $request->input('program') ?? '';
        $transType = $request->input('trans_type') ?? '';
        $status = $request->input('status') ?? 'all';
        $years = TrHist::query()
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $baseQuery = TrHist::query();
        if ($month !== 'all') {
            $baseQuery->whereMonth('trans_date', $month);
        }
        if ($year !== 'all') {
            $baseQuery->whereYear('trans_date', $year);
        }
        $programTotals = (clone $baseQuery)
            ->select('program')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('program')
            ->get()
            ->pluck('total', 'program');
        $totalPrograms = $programTotals->count();
        $activePrograms = $programTotals
            ->filter(fn ($total) => $total >= 50)
            ->count();
        $rarePrograms = $programTotals
            ->filter(fn ($total) => $total >= 1 && $total <= 49)
            ->count();
        $totalTransactions = (clone $baseQuery)->count();
        $programsList = (clone $baseQuery)
            ->whereNotNull('program')
            ->select('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');
        $transTypes = (clone $baseQuery)
            ->whereNotNull('trans_type')
            ->select('trans_type')
            ->distinct()
            ->orderBy('trans_type')
            ->pluck('trans_type');
        $query = clone $baseQuery;
        if ($program !== '') {
            $query->where('program', $program);
        }
        if ($transType !== '') {
            $query->where('trans_type', $transType);
        }
        if ($status === 'active') {
            $activeProgramsList = $programTotals
                ->filter(fn ($total) => $total >= 50)
                ->keys();
            $query->whereIn('program', $activeProgramsList);
        }
        if ($status === 'rare') {
            $rareProgramsList = $programTotals
                ->filter(fn ($total) => $total >= 1 && $total <= 49)
                ->keys();
            $query->whereIn('program', $rareProgramsList);
        }
        $programs = $query
            ->select('program', 'trans_type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('program', 'trans_type')
            ->orderByDesc('total')
            ->paginate(10)
            ->withQueryString();
        return view('programUtilization.index', compact(
            'month',
            'year',
            'years',
            'program',
            'transType',
            'status',
            'programTotals',
            'programsList',
            'transTypes',
            'totalPrograms',
            'activePrograms',
            'rarePrograms',
            'totalTransactions',
            'programs'
        ));
    }
    private function periodValue($value, int $minimum, int $maximum, $fallback)
    {
        if ($value === 'all') {
            return $value;
        }
        return is_numeric($value) && (int) $value >= $minimum && (int) $value <= $maximum
            ? (int) $value
            : $fallback;
    }
}
<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class MatrixController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $userSearch = trim($request->input('user', ''));
        $programSearch = trim($request->input('program', ''));
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

        $totalUsers = (clone $query)
            ->distinct('user')
            ->count('user');

        $totalPrograms = (clone $query)
            ->distinct('program')
            ->count('program');

        $totalAccess = (clone $query)
            ->selectRaw('COUNT(DISTINCT user, program) as total')
            ->value('total');

        $focusProgram = null;

        if ($programSearch !== '') {
            $focusProgram = (clone $query)
                ->where('program', 'like', "%{$programSearch}%")
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->first();
        }

        if ($focusProgram) {
            $focusUsers = (clone $query)
                ->where('program', $focusProgram->program)
                ->select('user')
                ->distinct()
                ->pluck('user');

            $programs = (clone $query)
                ->whereIn('user', $focusUsers)
                ->where('program', '!=', $focusProgram->program)
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->limit(9)
                ->pluck('program');

            $programs = collect([$focusProgram->program])->merge($programs);
        } else {
            $programs = (clone $query)
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->limit(10)
                ->pluck('program');
        }

        $usersQuery = clone $query;

        if ($userSearch !== '') {
            $usersQuery->where('user', 'like', "%{$userSearch}%");
        }

        if ($focusProgram) {
            $usersQuery->whereIn('user', $focusUsers);
        }

        $users = $usersQuery
            ->select('user')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user')
            ->orderByDesc('total')
            ->get();

        $matrix = (clone $query)
            ->whereIn('user', $users->pluck('user'))
            ->whereIn('program', $programs)
            ->select('user', 'program')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user', 'program')
            ->get()
            ->groupBy('user');

        $maxMatrixValue = $matrix
            ->flatten()
            ->max('total') ?? 0;

        $topUsers = (clone $query)
            ->select('user')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('accessMatrix.index', compact(
            'month',
            'year',
            'years',
            'userSearch',
            'programSearch',
            'programs',
            'users',
            'matrix',
            'topUsers',
            'totalUsers',
            'totalPrograms',
            'totalAccess',
            'maxMatrixValue'
        ));
    }
}
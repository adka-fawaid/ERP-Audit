<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class QadUserActivityController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->periodValue($request->input('month', now()->month), 1, 12, now()->month);
        $year = $this->periodValue($request->input('year', now()->year), 2000, 2100, now()->year);
        $userFilter = trim((string) $request->input('user', ''));
        $years = TrHist::query()
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
        $applyPeriod = function ($query) use ($month, $year) {
            if ($month !== 'all') {
                $query->whereMonth('trans_date', $month);
            }
            if ($year !== 'all') {
                $query->whereYear('trans_date', $year);
            }
        };
        $baseQuery = TrHist::query()->from('tr_hist as activity');
        $applyPeriod($baseQuery);
        $availableUsers = (clone $baseQuery)
            ->leftJoin('mst_anggota', 'mst_anggota.nik', '=', 'activity.tr_user')
            ->whereNotNull('activity.tr_user')
            ->select('activity.tr_user', 'mst_anggota.nama')
            ->distinct()
            ->orderBy('mst_anggota.nama')
            ->orderBy('activity.tr_user')
            ->get();
        $usersQuery = (clone $baseQuery)
            ->whereNotNull('activity.tr_user')
            ->selectRaw('activity.tr_user as user')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('activity.tr_user')
            ->orderByDesc('total')
            ->orderBy('activity.tr_user');
        if ($userFilter !== '') {
            $usersQuery->where('activity.tr_user', $userFilter);
        }
        foreach (['trans_type' => 'last_trans_type', 'program' => 'last_program', 'trans_date' => 'last_trans_date', 'trans_time' => 'last_trans_time'] as $column => $alias) {
            $latestQuery = TrHist::query()
                ->from('tr_hist as latest')
                ->whereColumn('latest.tr_user', 'activity.tr_user');
            $applyPeriod($latestQuery);
            $usersQuery->addSelect([
                $alias => $latestQuery
                    ->orderByDesc('latest.trans_date')
                    ->orderByDesc('latest.trans_time')
                    ->limit(1)
                    ->select("latest.{$column}"),
            ]);
        }
        $users = $usersQuery
            ->paginate(10)
            ->withQueryString();
        return view('qadUsers.index', compact(
            'month',
            'year',
            'years',
            'userFilter',
            'availableUsers',
            'users'
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

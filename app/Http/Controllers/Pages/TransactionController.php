<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\TrHist;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->periodValue($request->input('month', now()->month), 1, 12, now()->month);
        $year = $this->periodValue($request->input('year', now()->year), 2000, 2100, now()->year);
        $transType = $request->input('trans_type') ?? '';
        $program = $request->input('program') ?? '';
        $user = $request->input('user') ?? '';
        $years = TrHist::query()
            ->whereNotNull('trans_date')
            ->selectRaw('YEAR(trans_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
        $query = TrHist::query();
        $this->applyPeriod($query, $month, $year);
        $transactionTypes = (clone $query)
            ->whereNotNull('trans_type')
            ->select('trans_type')
            ->distinct()
            ->orderBy('trans_type')
            ->pluck('trans_type');
        $programs = (clone $query)
            ->whereNotNull('program')
            ->select('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');
        $users = (clone $query)
            ->whereNotNull('tr_user')
            ->select('tr_user')
            ->distinct()
            ->orderBy('tr_user')
            ->pluck('tr_user');
        $this->applyFilters($query, $transType, $program, $user);
        $transactions = (clone $query)
            ->select([
                'trans_number',
                'tr_user',
                'program',
                'trans_type',
                'trans_date',
                'trans_time',
                'location',
            ])
            ->orderByDesc('trans_date')
            ->orderByDesc('trans_time')
            ->paginate(10)
            ->withQueryString();
        $dailyTransactions = (clone $query)
            ->selectRaw('trans_date, COUNT(*) as total')
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get();
        $transTypes = (clone $query)
            ->selectRaw('trans_type, COUNT(*) as total')
            ->groupBy('trans_type')
            ->orderByDesc('total')
            ->get();
        return view('transactions.index', compact(
            'month',
            'year',
            'years',
            'transType',
            'program',
            'user',
            'transactionTypes',
            'programs',
            'users',
            'transactions',
            'dailyTransactions',
            'transTypes'
        ));
    }

    private function applyPeriod($query, $month, $year): void
    {
        if ($month !== 'all') {
            $query->whereMonth('trans_date', $month);
        }
        if ($year !== 'all') {
            $query->whereYear('trans_date', $year);
        }
    }
    private function applyFilters($query, string $transType, string $program, string $user): void
    {
        if ($transType !== '') {
            $query->where('trans_type', $transType);
        }
        if ($program !== '') {
            $query->where('program', $program);
        }
        if ($user !== '') {
            $query->where('tr_user', $user);
        }
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
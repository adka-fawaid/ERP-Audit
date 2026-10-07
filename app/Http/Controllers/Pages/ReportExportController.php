<?php

namespace App\Http\Controllers\Pages;

use App\Exports\ExcelFile;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\TrHist;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function dashboard(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $rows = TrHist::query()
            ->whereBetween('trans_date', [$from, $to])
            ->orderBy('trans_date')
            ->orderBy('trans_time')
            ->get([
                'trans_number',
                'trans_date',
                'trans_time',
                'tr_user',
                'program',
                'trans_type',
                'location',
            ])
            ->map(fn ($row) => [
                $row->trans_date,
                $row->trans_time,
                $row->tr_user,
                $row->program,
                $row->trans_type,
                $row->location,
                $row->trans_number,
            ])
            ->prepend([
                'Tanggal',
                'Waktu',
                'User',
                'Program',
                'Transaction Type',
                'Location',
                'Nomor Transaksi',
            ])
            ->all();
        return $this->download(
            $request,
            'Dashboard',
            'qad-dashboard',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Transaksi', 'rows' => $rows],
            ]
        );
    }

    public function transactions(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = TrHist::query()
            ->whereBetween('trans_date', [$from, $to]);
        foreach (['trans_type', 'program', 'user'] as $filter) {
            if (!$request->filled($filter)) {
                continue;
            }
            $query->where($filter === 'user' ? 'tr_user' : $filter, $request->input($filter));
        }
        $rows = $query
            ->orderByDesc('trans_date')
            ->orderByDesc('trans_time')
            ->get([
                'trans_number',
                'tr_user',
                'program',
                'trans_type',
                'trans_date',
                'trans_time',
                'location',
            ])
            ->map(fn ($row, $index) => [
                $index + 1,
                $row->trans_number,
                $row->tr_user,
                $row->program,
                $row->trans_type,
                $row->trans_date,
                $row->trans_time,
                $row->location,
            ])
            ->prepend([
                'No',
                'Trans Number',
                'User',
                'Program',
                'Trans Type',
                'Tanggal',
                'Waktu',
                'Location',
            ])
            ->all();
        return $this->download(
            $request,
            'Total Transaksi',
            'qad-total-transaksi',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Transaksi', 'rows' => $rows],
            ]
        );
    }

    public function qadUsers(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = TrHist::query()
            ->whereBetween('trans_date', [$from, $to]);
        if ($request->filled('user')) {
            $query->where('tr_user', $request->input('user'));
        }
        $rows = $query
            ->orderByDesc('trans_date')
            ->orderByDesc('trans_time')
            ->get([
                'tr_user',
                'trans_type',
                'program',
                'trans_date',
                'trans_time',
            ])
            ->groupBy('tr_user')
            ->sortByDesc(fn ($activities) => $activities->count())
            ->values()
            ->map(fn ($activities, $index) => [
                $index + 1,
                $activities->first()->tr_user,
                $activities->count(),
                $activities->first()->trans_type . ' - ' . $activities->first()->program,
                $activities->first()->trans_date,
                $activities->first()->trans_time,
            ])
            ->prepend([
                'No',
                'User',
                'Total Transaksi',
                'Aktivitas Terakhir',
                'Tanggal',
                'Waktu',
            ])
            ->all();
        return $this->download(
            $request,
            'User QAD',
            'qad-user-activity',
            $from,
            $to,
            $reason,
            [
                ['name' => 'User QAD', 'rows' => $rows],
            ]
        );
    }
    public function programUtilization(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = TrHist::query()
            ->whereBetween('trans_date', [$from, $to]);
        if ($request->filled('program')) {
            $query->where('program', $request->input('program'));
        }
        $programTotals = (clone $query)
            ->select('program')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('program')
            ->get()
            ->pluck('total', 'program');

        if ($request->filled('trans_type')) {
            $query->where('trans_type', $request->input('trans_type'));
        }
        $detailQuery = (clone $query)
            ->select('program', 'trans_type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('program', 'trans_type')
            ->orderByDesc('total');
        if ($request->input('status') === 'active') {
            $detailQuery->whereIn('program', $programTotals->filter(fn ($total) => $total >= 50)->keys());
        } elseif ($request->input('status') === 'rare') {
            $detailQuery->whereIn('program', $programTotals->filter(fn ($total) => $total >= 1 && $total <= 49)->keys());
        }
        $rows = $detailQuery
            ->get()
            ->map(fn ($program, $index) => [
                $index + 1,
                $program->program,
                $program->trans_type,
                $program->total,
                $programTotals->get($program->program) >= 50 ? 'Aktif' : 'Jarang',
            ])
            ->prepend([
                'No',
                'Program',
                'Trans Type',
                'Total Transaksi',
                'Status',
            ])
            ->all();
        return $this->download(
            $request,
            'Utilisasi Program',
            'qad-utilisasi-program',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Utilisasi Program', 'rows' => $rows],
            ]
        );
    }
    public function anomalyLog(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = TrHist::query()
            ->whereRaw('DAYOFWEEK(trans_date) = 1')
            ->whereBetween('trans_date', [$from, $to]);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where(
                    'tr_user',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'program',
                    'like',
                    "%{$search}%"
                );
            });
        }
        $rows = $query
            ->orderByDesc('trans_date')
            ->orderByDesc('trans_time')
            ->get([
                'tr_user',
                'program',
                'trans_type',
                'trans_date',
                'trans_time',
                'location',
            ])
            ->map(fn ($row) => [
                $row->tr_user,
                $row->program,
                $row->trans_type,
                $row->trans_date,
                $row->trans_time,
                $row->location,
                'ANOMALI',
            ])
            ->prepend([
                'User',
                'Program',
                'Transaction Type',
                'Tanggal',
                'Waktu',
                'Location',
                'Status',
            ])
            ->all();
        return $this->download(
            $request,
            'Log Anomali',
            'qad-log-anomali',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Log Anomali', 'rows' => $rows],
            ]
        );
    }
    public function accessMatrix(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = TrHist::query()
            ->whereBetween('trans_date', [$from, $to]);
        $userSearch = $request->input('user', '');
        $programSearch = $request->input('program', '');
        $focusProgram = null;
        if ($programSearch !== '') {
            $focusProgram = (clone $query)
                ->where(
                    'program',
                    'like',
                    "%{$programSearch}%"
                )
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->first();
        }
        if ($focusProgram) {
            $focusUsers = (clone $query)
                ->where('program', $focusProgram->program)
                ->distinct()
                ->pluck('tr_user');
            $programs = (clone $query)
                ->whereIn('tr_user', $focusUsers)
                ->where('program', '!=', $focusProgram->program)
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->limit(9)
                ->pluck('program');
            $programs = collect([
                $focusProgram->program,
            ])->merge($programs);
        } else {
            $programs = (clone $query)
                ->select('program')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('program')
                ->orderByDesc('total')
                ->limit(10)
                ->pluck('program');
            $focusUsers = collect();
        }
        $usersQuery = clone $query;
        if ($userSearch !== '') {
            $usersQuery->where(
                'tr_user',
                'like',
                '%' . $userSearch . '%'
            );
        }
        if ($focusProgram) {
            $usersQuery->whereIn('tr_user', $focusUsers);
        }
        $users = $usersQuery
            ->select('tr_user')
            ->distinct()
            ->orderBy('tr_user')
            ->pluck('tr_user');
        $counts = (clone $query)
            ->whereIn('tr_user', $users)
            ->whereIn('program', $programs)
            ->select('tr_user', 'program')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('tr_user', 'program')
            ->get()
            ->keyBy(fn ($row) => $row->tr_user . "\0" . $row->program);
        $matrixRows = [
            array_merge(['User'], $programs->all()),
        ];
        foreach ($users as $user) {
            $matrixRows[] = array_merge(
                [$user],
                $programs
                    ->map(fn ($program) =>
                        $counts->get($user . "\0" . $program)->total ?? 0
                    )
                    ->all()
            );
        }
        $details = (clone $query)
            ->whereIn('tr_user', $users)
            ->whereIn('program', $programs)
            ->orderBy('tr_user')
            ->orderBy('program')
            ->orderBy('trans_date')
            ->get([
                'tr_user',
                'program',
                'trans_type',
                'trans_date',
                'trans_time',
                'location',
            ])
            ->map(fn ($row) => [
                $row->tr_user,
                $row->program,
                $row->trans_type,
                $row->trans_date,
                $row->trans_time,
                $row->location,
            ])
            ->prepend([
                'User',
                'Program',
                'Transaction Type',
                'Tanggal',
                'Waktu',
                'Location',
            ])
            ->all();
        return $this->download(
            $request,
            'Matriks Akses',
            'qad-matriks-akses',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Matriks Akses', 'rows' => $matrixRows],
                ['name' => 'Detail Transaksi', 'rows' => $details],
            ]
        );
    }
    public function activityLog(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);
        $query = ActivityLog::query()
            ->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        if ($request->filled('activity')) {
            $query->where(
                'activity',
                $request->input('activity')
            );
        }
        if ($request->filled('user')) {
            $query->where(
                'user',
                'like',
                '%' . $request->input('user') . '%'
            );
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where(
                    'user',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'activity',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'ip_address',
                    'like',
                    "%{$search}%"
                );
            });
        }
        $rows = $query
            ->latest()
            ->get([
                'created_at',
                'user',
                'activity',
                'ip_address',
                'browser',
                'description',
            ])
            ->map(fn ($log) => [
                $log->created_at?->format('d/m/Y H:i:s'),
                $log->user,
                $log->activity,
                $log->ip_address,
                $log->browser,
                $log->description,
            ])
            ->prepend([
                'Waktu',
                'User',
                'Aktivitas',
                'IP Address',
                'Browser',
                'Deskripsi',
            ])
            ->all();
        return $this->download(
            $request,
            'Log Aktivitas',
            'qad-log-aktivitas',
            $from,
            $to,
            $reason,
            [
                ['name' => 'Log Aktivitas', 'rows' => $rows],
            ]
        );
    }
    private function exportOptions(Request $request): array
    {
        $data = $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'reason' => [
                'required',
                'in:audit,department,monitoring,analysis,reporting,other',
            ],
            'reason_other' => [
                'required_if:reason,other',
                'nullable',
                'string',
                'max:1000',
            ],
        ]);
        $reasons = [
            'audit' => 'Audit',
            'department' => 'Kebutuhan Department',
            'monitoring' => 'Monitoring',
            'analysis' => 'Analisis Data',
            'reporting' => 'Pelaporan',
            'other' => trim($data['reason_other'] ?? ''),
        ];
        return [
            $data['from_date'],
            $data['to_date'],
            $reasons[$data['reason']],
        ];
    }
    private function download(
        Request $request,
        string $report,
        string $filename,
        string $from,
        string $to,
        string $reason,
        array $sheets
    ) {
        $download = ExcelFile::download(
            $filename . '-' . $from . '-' . $to . '.xlsx',
            $sheets
        );
        ActivityLog::record(
            $request,
            'EXPORT_REPORT',
            'Export ' . $report .
            ' periode ' . Carbon::parse($from)->format('d/m/Y') .
            ' - ' . Carbon::parse($to)->format('d/m/Y') .
            '. Alasan: ' . $reason . '.'
        );
        return $download;
    }
}
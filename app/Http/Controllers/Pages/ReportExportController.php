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

    public function programUtilization(Request $request)
    {
        [$from, $to, $reason] = $this->exportOptions($request);

        $query = TrHist::query()
            ->whereBetween('trans_date', [$from, $to]);

        if ($request->filled('search')) {
            $query->where(
                'program',
                'like',
                '%' . $request->input('search') . '%'
            );
        }

        $programs = $query
            ->select('program')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN trans_type = 'ISS-SO' THEN 1 ELSE 0 END) as iss_so")
            ->selectRaw("SUM(CASE WHEN trans_type = 'RCT-PO' THEN 1 ELSE 0 END) as rct_po")
            ->selectRaw("SUM(CASE WHEN trans_type = 'ISS-WO' THEN 1 ELSE 0 END) as iss_wo")
            ->groupBy('program')
            ->orderByDesc('total');

        if ($request->input('status') === 'active') {
            $programs->having('total', '>=', 50);
        } elseif ($request->input('status') === 'rare') {
            $programs->havingBetween('total', [1, 49]);
        }

        $rows = $programs
            ->get()
            ->map(fn ($program) => [
                $program->program,
                $program->total,
                $program->iss_so,
                $program->rct_po,
                $program->iss_wo,
                $program->total >= 50 ? 'Aktif' : 'Jarang',
            ])
            ->prepend([
                'Program',
                'Total Transaksi',
                'ISS-SO',
                'RCT-PO',
                'ISS-WO',
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
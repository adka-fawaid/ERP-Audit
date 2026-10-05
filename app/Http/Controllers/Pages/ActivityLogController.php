<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()
            ->select('activity_logs.*')
            ->selectSub(
                DB::table('qad_user_roles')
                    ->select('role')
                    ->whereColumn('qad_user_roles.nik', 'activity_logs.user')
                    ->limit(1),
                'user_role'
            )
            ->selectSub(
                DB::table('mst_anggota')
                    ->select('nama')
                    ->whereColumn('mst_anggota.nik', 'activity_logs.user')
                    ->limit(1),
                'user_name'
            )
            ->latest('activity_logs.created_at');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('activity_logs.user', 'like', "%{$search}%")
                    ->orWhere('activity_logs.activity', 'like', "%{$search}%")
                    ->orWhere('activity_logs.description', 'like', "%{$search}%")
                    ->orWhere('activity_logs.ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('activity')) {
            $query->where('activity_logs.activity', $request->activity);
        }

        if ($request->filled('user')) {
            $query->where(
                'activity_logs.user',
                'like',
                '%' . $request->user . '%'
            );
        }

        if ($request->filled('start_date')) {
            $query->whereDate(
                'activity_logs.created_at',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'activity_logs.created_at',
                '<=',
                $request->end_date
            );
        }

        $activities = [
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'VIEW_DASHBOARD' => 'View Dashboard',
            'VIEW_PROGRAM' => 'View Utilisasi Program',
            'VIEW_ANOMALY' => 'View Log Anomali',
            'VIEW_ACCESS_MATRIX' => 'View Matriks Akses',
            'EXPORT_REPORT' => 'Export Report',
            'VIEW_USER' => 'View User',
            'CREATE_USER' => 'Create User',
            'UPDATE_USER' => 'Update User',
            'DELETE_USER' => 'Delete User',
            'CHANGE_ROLE' => 'Change Role',
        ];

        $logs = $query
            ->paginate(25)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'rows' => view(
                    'logActivity.partials.rows',
                    compact('logs', 'activities')
                )->render(),
                'pagination' => $logs->links()->render(),
                'total' => $logs->total(),
                'firstItem' => $logs->firstItem(),
                'lastItem' => $logs->lastItem(),
            ]);
        }

        $users = ActivityLog::query()
            ->whereNotNull('user')
            ->distinct()
            ->orderBy('user')
            ->pluck('user');

        return view('logActivity.index', compact(
            'logs',
            'users',
            'activities'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

/**
 * SystemLogController — Admin & Moderator Audit Trail Inspector.
 */
class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('organization_name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $actions = SystemLog::select('action')->distinct()->pluck('action');

        return view('logs.index', compact('logs', 'actions'));
    }

    public function show(SystemLog $log)
    {
        $log->load('user');
        return view('logs.show', compact('log'));
    }

    /**
     * Export system activity logs as CSV file.
     */
    public function exportCsv()
    {
        $logs = SystemLog::with('user')->latest()->get();
        $filename = 'nutrishare_audit_logs_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Timestamp', 'Level', 'User', 'Action', 'Description', 'IP Address', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    strtoupper($log->level),
                    $log->user->name ?? 'System',
                    $log->action,
                    $log->description,
                    $log->ip_address ?? '127.0.0.1',
                    $log->user_agent ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

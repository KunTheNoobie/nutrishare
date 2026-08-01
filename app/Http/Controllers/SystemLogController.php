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
}

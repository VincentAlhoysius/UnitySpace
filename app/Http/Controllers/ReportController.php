<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports (Admins and Moderators only).
     */
    public function index()
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        $reports = Report::with('reporter')->orderBy('created_at', 'desc')->paginate(10);
        return view('reports.index', compact('reports'));
    }

    /**
     * Display the report submission form.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:intoleransi,diskriminasi',
            'description' => 'required|string',
            'evidence' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120', // max 5MB
            'is_anonymous' => 'nullable|boolean',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidence', 'public');
        }

        $reporterId = null;
        // If not anonymous and user is logged in
        if (!$request->has('is_anonymous') && Auth::check()) {
            $reporterId = Auth::id();
        }

        Report::create([
            'reporter_id' => $reporterId,
            'report_type' => $request->report_type,
            'description' => $request->description,
            'evidence_path' => $evidencePath,
            'status' => 'pending',
        ]);

        // Send a session flash message
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diajukan secara aman. Terima kasih atas kontribusi Anda dalam menjaga toleransi kampus!');
    }

    /**
     * Update the status of a report.
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,reviewed,resolved',
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $request->status,
        ]);

        // If reporter is authenticated, notify them of status update
        if ($report->reporter) {
            $report->reporter->notify(new \App\Notifications\ReportStatusNotification($report));
        }

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}

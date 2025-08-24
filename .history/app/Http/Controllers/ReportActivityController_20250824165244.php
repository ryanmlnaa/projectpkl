<?php

namespace App\Http\Controllers;

use App\Models\ReportActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportActivityController extends Controller
{
    public function index()
    {
        $reports = ReportActivity::latest()->get();
        return view('report.activity', compact('reports'));
    }

    public function store(Request $request)
    {
        // Debug untuk melihat data yang diterima
        // dd($request->all());

        $validated = $request->validate([
            'sales' => 'required|string|max:255',
            'aktivitas' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hasil_kendala' => 'nullable|string',
            'status' => 'required|in:selesai,proses'
        ]);

        // Handle file upload
        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        try {
            ReportActivity::create($validated);
            return redirect()->route('reports.activity')->with('success', 'Report berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $report = ReportActivity::findOrFail($id);
        return view('report.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sales' => 'required|string|max:255',
            'aktivitas' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hasil_kendala' => 'nullable|string',
            'status' => 'required|in:selesai,proses'
        ]);

        $report = ReportActivity::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('evidence')) {
            // Delete old file if exists
            if ($report->evidence) {
                Storage::disk('public')->delete($report->evidence);
            }
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        try {
            $report->update($validated);
            return redirect()->route('reports.activity')->with('success', 'Report berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $report = ReportActivity::findOrFail($id);

            // Delete file if exists
            if ($report->evidence) {
                Storage::disk('public')->delete($report->evidence);
            }

            $report->delete();
            return redirect()->route('reports.activity')->with('success', 'Report berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function debugStorage()
    {
        $reports = ReportActivity::whereNotNull('evidence')->get();
        $debug = [];

        foreach ($reports as $report) {
            $debug[] = [
                'id' => $report->id,
                'evidence_path' => $report->evidence,
                'file_exists_public' => Storage::disk('public')->exists($report->evidence),
                'file_exists_storage' => file_exists(storage_path('app/public/' . $report->evidence)),
                'full_path' => storage_path('app/public/' . $report->evidence),
                'public_url' => asset('storage/' . $report->evidence),
                'storage_link_exists' => is_link(public_path('storage'))
            ];
        }

        $info = [
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
            'storage_link_exists' => is_link(public_path('storage')),
            'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : 'Not a symlink',
            'reports_with_evidence' => $reports->count(),
            'debug_data' => $debug
        ];

        return response()->json($info, 200, [], JSON_PRETTY_PRINT);
    }

    // Method untuk fix storage link
    public function fixStorage()
    {
        try {
            // Hapus storage link lama jika ada
            if (is_link(public_path('storage'))) {
                unlink(public_path('storage'));
            }

            // Buat ulang storage link
            \Artisan::call('storage:link');

            return redirect()->back()->with('success', 'Storage link berhasil diperbaiki!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbaiki storage link: ' . $e->getMessage());
        }
    }

    // Method untuk clean up file yang tidak ada
    public function cleanupMissingFiles()
    {
        $reports = ReportActivity::whereNotNull('evidence')->get();
        $cleaned = 0;

        foreach ($reports as $report) {
            if (!Storage::disk('public')->exists($report->evidence)) {
                $report->update(['evidence' => null]);
                $cleaned++;
            }
        }

        return redirect()->back()->with('success', "Berhasil membersihkan {$cleaned} file yang hilang dari database.");
    }
}

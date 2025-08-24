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

    public function exportPdf()
    {
        // Implementasi export PDF
        $reports = ReportActivity::all();
        // Add your PDF export logic here
        return response()->json(['message' => 'Export PDF functionality needs to be implemented']);
    }
}<?php

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

    public function exportPdf()
    {
        // Implementasi export PDF
        $reports = ReportActivity::all();
        // Add your PDF export logic here
        return response()->json(['message' => 'Export PDF functionality needs to be implemented']);
    }
}

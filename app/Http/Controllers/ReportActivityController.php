<?php

namespace App\Http\Controllers;

use App\Models\ReportActivity;
use Illuminate\Http\Request;

class ReportActivityController extends Controller
{
    public function index()
    {
        $reports = ReportActivity::latest()->get();
        return view('report.activity', compact('reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales' => 'required',
            'aktivitas' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'required',
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hasil_kendala' => 'nullable',
            'status' => 'required'
        ]);

        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        ReportActivity::create($validated);

        return redirect()->route('reports.activity')->with('success', 'Report berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $report = ReportActivity::findOrFail($id);
        return view('report.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sales' => 'required',
            'aktivitas' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'required',
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hasil_kendala' => 'nullable',
            'status' => 'required'
        ]);

        $report = ReportActivity::findOrFail($id);

        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        $report->update($validated);

        return redirect()->route('reports.activity')->with('success', 'Report berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $report = ReportActivity::findOrFail($id);
        $report->delete();

        return redirect()->route('reports.activity')->with('success', 'Report berhasil dihapus!');
    }
}

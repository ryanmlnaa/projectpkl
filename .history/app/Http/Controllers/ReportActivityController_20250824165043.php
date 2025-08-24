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
        try {
            $reports = ReportActivity::latest()->get();
            $date = now()->format('d-m-Y');

            if ($reports->count() == 0) {
                return redirect()->back()->with('error', 'Tidak ada data untuk diekspor');
            }

            // Redirect ke halaman print
            return view('report.print', compact('reports', 'date'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat halaman print: ' . $e->getMessage());
        }
    }

    // SOLUSI 2: HTML Response untuk Print
    public function printView()
    {
        $reports = ReportActivity::latest()->get();
        $date = now()->format('d-m-Y');

        return view('report.print-simple', compact('reports', 'date'));
    }

    // SOLUSI 3: Export CSV (Alternative)
    public function exportCsv()
    {
        $reports = ReportActivity::latest()->get();
        $date = now()->format('d-m-Y_H-i-s');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Report_Activity_' . $date . '.csv"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'No', 'Sales', 'Aktivitas', 'Tanggal', 'Lokasi',
                'Evidence', 'Hasil/Kendala', 'Status', 'Dibuat'
            ]);

            // Data rows
            foreach ($reports as $index => $report) {
                fputcsv($file, [
                    $index + 1,
                    $report->sales,
                    $report->aktivitas,
                    $report->tanggal,
                    $report->lokasi,
                    $report->evidence ? 'Ya' : 'Tidak',
                    $report->hasil_kendala ?? '',
                    ucfirst($report->status),
                    $report->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // SOLUSI 4: Using mPDF (Alternative PDF Library)
    public function exportPdfWithMpdf()
    {
        // Jika ingin menggunakan mPDF instead of DomPDF
        // composer require mpdf/mpdf

        try {
            $reports = ReportActivity::latest()->get();
            $date = now()->format('d-m-Y');

            if ($reports->count() == 0) {
                return redirect()->back()->with('error', 'Tidak ada data untuk diekspor');
            }

            $mpdf = new \Mpdf\Mpdf([
                'orientation' => 'L', // Landscape
                'format' => 'A4'
            ]);

            $html = view('report.pdf', compact('reports', 'date'))->render();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('Report_Activity_' . $date . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="Report_Activity_' . $date . '.pdf"');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }

    // SOLUSI 5: Export Multiple Formats
    public function export(Request $request)
    {
        $format = $request->query('format', 'print');

        switch ($format) {
            case 'pdf':
                return $this->exportPdfWithDomPDF();
            case 'csv':
                return $this->exportCsv();
            case 'json':
                return $this->exportJson();
            default:
                return $this->exportPdf(); // Print view
        }
    }

    private function exportJson()
    {
        $reports = ReportActivity::latest()->get();
        $date = now()->format('d-m-Y_H-i-s');

        $data = [
            'export_info' => [
                'date' => now()->format('Y-m-d H:i:s'),
                'total_records' => $reports->count(),
                'exported_by' => auth()->user()->name ?? 'System'
            ],
            'reports' => $reports->map(function($report) {
                return [
                    'id' => $report->id,
                    'sales' => $report->sales,
                    'aktivitas' => $report->aktivitas,
                    'tanggal' => $report->tanggal,
                    'lokasi' => $report->lokasi,
                    'evidence' => $report->evidence ? asset('storage/' . $report->evidence) : null,
                    'hasil_kendala' => $report->hasil_kendala,
                    'status' => $report->status,
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at
                ];
            })
        ];

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="Report_Activity_' . $date . '.json"');
    }
}

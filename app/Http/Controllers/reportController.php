<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Menampilkan daftar report
    public function index()
    {
        try {
            // Clear any cache dan get fresh data
            $reports = Report::orderBy('created_at', 'desc')->get();
            
            // Log untuk debugging
            Log::info('Reports loaded', ['count' => $reports->count()]);
            
            // Debug: cek apakah data ada di database
            $totalReports = Report::count();
            Log::info('Total reports in database: ' . $totalReports);
            
            return view('report.activity', compact('reports'));
            
        } catch (\Exception $e) {
            Log::error('Error loading reports: ' . $e->getMessage());
            
            // Jika error, kirim array kosong
            $reports = collect();
            return view('report.activity', compact('reports'))
                ->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    // Menyimpan report baru
    public function store(Request $request)
    {
        try {
            Log::info('Store method called', $request->all());
            
            $request->validate([
                'sales' => 'required|string|max:255',
                'aktivitas' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string|max:255',
                'hasil_kendala' => 'nullable|string',
                'status' => 'required|string',
                'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $data = $request->only(['sales', 'aktivitas', 'tanggal', 'lokasi', 'hasil_kendala', 'status']);

            // Handle file upload
            if ($request->hasFile('evidence')) {
                $file = $request->file('evidence');
                $filename = time().'_'.$file->getClientOriginalName();
                
                // Simpan ke storage/app/public/uploads
                $path = $file->storeAs('uploads', $filename, 'public');
                $data['evidence'] = $path;
                
                Log::info('File uploaded successfully', ['path' => $path]);
            }

            // Create report dengan transaction untuk memastikan data tersimpan
            DB::beginTransaction();
            
            $report = Report::create($data);
            
            DB::commit();
            
            Log::info('Report created successfully', [
                'report_id' => $report->id, 
                'evidence' => $report->evidence ?? 'no evidence'
            ]);

            // Clear any cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            return redirect()->route('reports.index')
                ->with('success', 'Report berhasil ditambahkan.')
                ->with('new_report_id', $report->id); // Tambahkan ID untuk debugging

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating report: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // Method untuk debug data
    public function debugData()
    {
        $reports = Report::all();
        
        echo "<h3>Debug Report Data</h3>";
        echo "<p>Total reports: " . $reports->count() . "</p>";
        
        foreach($reports as $report) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<strong>ID:</strong> " . $report->id . "<br>";
            echo "<strong>Sales:</strong> " . $report->sales . "<br>";
            echo "<strong>Aktivitas:</strong> " . $report->aktivitas . "<br>";
            echo "<strong>Tanggal:</strong> " . $report->tanggal . "<br>";
            echo "<strong>Created At:</strong> " . $report->created_at . "<br>";
            echo "<strong>Evidence:</strong> " . ($report->evidence ?? 'Tidak ada') . "<br>";
            echo "</div>";
        }
        
        // Check database connection
        try {
            DB::connection()->getPdo();
            echo "<p style='color: green;'>Database connection: OK</p>";
        } catch (\Exception $e) {
            echo "<p style='color: red;'>Database connection error: " . $e->getMessage() . "</p>";
        }
    }

    // Update report
    public function update(Request $request, Report $report)
    {
        try {
            $request->validate([
                'sales' => 'required|string|max:255',
                'aktivitas' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string|max:255',
                'hasil_kendala' => 'nullable|string',
                'status' => 'required|string',
                'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $data = $request->only(['sales', 'aktivitas', 'tanggal', 'lokasi', 'hasil_kendala', 'status']);

            if ($request->hasFile('evidence')) {
                // Hapus file lama jika ada
                if ($report->evidence && Storage::disk('public')->exists($report->evidence)) {
                    Storage::disk('public')->delete($report->evidence);
                }

                $file = $request->file('evidence');
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
                $data['evidence'] = $path;
            }

            $report->update($data);

            return redirect()->route('reports.index')->with('success', 'Report berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating report: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
        }
    }

    // Hapus report
    public function destroy(Report $report)
    {
        try {
            // Hapus file evidence jika ada
            if ($report->evidence && Storage::disk('public')->exists($report->evidence)) {
                Storage::disk('public')->delete($report->evidence);
            }

            $report->delete();
            
            return redirect()->route('reports.index')->with('success', 'Report berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting report: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // Method untuk refresh data
    public function refresh()
    {
        // Clear cache jika ada
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        return redirect()->route('reports.index')->with('success', 'Data berhasil direfresh.');
    }

    // Export PDF
    public function exportPdf()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        
        foreach($reports as $report) {
            $report->image_data = null;
            
            if($report->evidence) {
                $possiblePaths = [
                    storage_path('app/public/' . $report->evidence),
                    public_path('storage/' . $report->evidence), 
                    public_path($report->evidence)
                ];
                
                foreach($possiblePaths as $path) {
                    if(file_exists($path)) {
                        try {
                            $imageData = file_get_contents($path);
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mimeType = finfo_buffer($finfo, $imageData);
                            finfo_close($finfo);
                            
                            if(in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
                                $base64 = base64_encode($imageData);
                                $report->image_data = 'data:' . $mimeType . ';base64,' . $base64;
                            }
                            break;
                        } catch(\Exception $e) {
                            Log::error('Error processing image: ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }
        }
        
        $pdf = Pdf::loadView('report.pdf', compact('reports'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'Arial',
                      'dpi' => 96,
                      'enable_php' => true
                  ]);
                  
        return $pdf->download('activity_report_' . date('Ymd_His') . '.pdf');
    }

    // method competitor untuk menampilkan view
    public function competitor()
    {
        return view('report.competitor');
    }
}
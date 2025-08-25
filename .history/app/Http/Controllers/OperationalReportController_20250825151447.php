<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class OperationalReportController extends Controller
{
    public function index()
    {
        // Ambil data pelanggan agar bisa ditampilkan di tabel
        $pelanggans = Pelanggan::orderBy('created_at', 'desc')->paginate(10);
        return view('report.operational.index', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'   => 'required|string|max:100|unique:pelanggans,id_pelanggan',
            'nama_pelanggan' => 'required|string|max:255',
            'bandwidth'      => 'required|string|max:100',
            'alamat'         => 'required|string',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'nomor_telepon'  => 'required|string|max:50',
            'cluster'        => 'required|string|max:100',
            'kode_fat'       => 'nullable|string|max:100',
        ]);

        try {
            Pelanggan::create($validated);
            return redirect()->route('report.operational.index')
                ->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil disimpan!");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $pelanggan)
    {
        $pelangganData = Pelanggan::findOrFail($pelanggan);

        $validated = $request->validate([
            'id_pelanggan'   => 'required|string|max:100|unique:pelanggans,id_pelanggan,' . $pelangganData->id,
            'nama_pelanggan' => 'required|string|max:255',
            'bandwidth'      => 'required|string|max:100',
            'alamat'         => 'required|string',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'nomor_telepon'  => 'required|string|max:50',
            'cluster'        => 'required|string|max:100',
            'kode_fat'       => 'nullable|string|max:100',
        ]);

        try {
            $pelangganData->update($validated);
            return redirect()->route('report.operational.index')
                ->with('success', "Data pelanggan berhasil diperbarui!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy($pelanggan)
    {
        try {
            $pelangganData = Pelanggan::findOrFail($pelanggan);
            $nama = $pelangganData->nama_pelanggan;
            $pelangganData->delete();
            return back()->with('success', "Data pelanggan {$nama} berhasil dihapus!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

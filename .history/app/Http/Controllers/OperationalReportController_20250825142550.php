<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class OperationalReportController extends Controller
{
    /**
     * Tampilkan form input data pelanggan saja
     */
    public function index()
    {
        return view('reports.operational.index');
    }

    /**
     * Simpan data pelanggan baru
     */
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
        ], [
            'id_pelanggan.required' => 'ID Pelanggan wajib diisi',
            'id_pelanggan.unique' => 'ID Pelanggan sudah terdaftar, gunakan ID lain',
            'nama_pelanggan.required' => 'Nama Pelanggan wajib diisi',
            'bandwidth.required' => 'Bandwidth wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'nomor_telepon.required' => 'Nomor Telepon wajib diisi',
            'cluster.required' => 'Cluster wajib dipilih',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
        ]);

        try {
            Pelanggan::create($validated);

            return redirect()->route('reports.operational.index')
                ->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil disimpan!");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Update data pelanggan (jika diperlukan dari form input)
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'id_pelanggan'   => 'required|string|max:100|unique:pelanggans,id_pelanggan,' . $pelanggan->id,
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
            $pelanggan->update($validated);

            return redirect()->route('reports.operational.index')
                ->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil diperbarui!");

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus data pelanggan (jika diperlukan)
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $nama = $pelanggan->nama_pelanggan;
            $pelanggan->delete();

            return back()->with('success', "Data pelanggan {$nama} berhasil dihapus!");

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}

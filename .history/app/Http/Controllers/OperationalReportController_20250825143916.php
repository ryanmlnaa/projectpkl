<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperationalReportController extends Controller
{
    /**
     * Tampilkan form input data pelanggan
     */
    public function index()
    {
        // Debug: Pastikan view ada
        if (!view()->exists('reports.operational.index')) {
            abort(404, 'View reports.operational.index tidak ditemukan');
        }

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

        DB::beginTransaction();

        try {
            // Trim whitespace dari data input
            $validated = array_map(function($value) {
                return is_string($value) ? trim($value) : $value;
            }, $validated);

            $pelanggan = Pelanggan::create($validated);

            DB::commit();

            return redirect()->route('reports.operational.index')
                ->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil disimpan!");

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();

            // Log error untuk debugging
            Log::error('Database error saat menyimpan pelanggan: ' . $e->getMessage());

            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->withErrors(['id_pelanggan' => 'ID Pelanggan sudah terdaftar']);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan database saat menyimpan data']);

        } catch (\Exception $e) {
            DB::rollback();

            // Log error untuk debugging
            Log::error('Error saat menyimpan pelanggan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data']);
        }
    }

    /**
     * Update data pelanggan
     */
    public function update(Request $request, $pelangganId)
    {
        // Cari pelanggan berdasarkan ID atau primary key
        $pelanggan = Pelanggan::findOrFail($pelangganId);

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
        ], [
            'id_pelanggan.required' => 'ID Pelanggan wajib diisi',
            'id_pelanggan.unique' => 'ID Pelanggan sudah terdaftar',
            'nama_pelanggan.required' => 'Nama Pelanggan wajib diisi',
            'bandwidth.required' => 'Bandwidth wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'nomor_telepon.required' => 'Nomor Telepon wajib diisi',
            'cluster.required' => 'Cluster wajib dipilih',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
        ]);

        DB::beginTransaction();

        try {
            // Trim whitespace dari data input
            $validated = array_map(function($value) {
                return is_string($value) ? trim($value) : $value;
            }, $validated);

            $pelanggan->update($validated);

            DB::commit();

            return redirect()->route('reports.operational.index')
                ->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil diperbarui!");

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();

            Log::error('Database error saat update pelanggan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan database saat memperbarui data']);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saat update pelanggan: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data']);
        }
    }

    /**
     * Hapus data pelanggan
     */
    public function destroy($pelangganId)
    {
        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($pelangganId);

        DB::beginTransaction();

        try {
            $nama = $pelanggan->nama_pelanggan;
            $pelanggan->delete();

            DB::commit();

            return back()->with('success', "Data pelanggan {$nama} berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saat hapus pelanggan: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus data']);
        }
    }

    /**
     * Tampilkan daftar pelanggan (optional - jika ingin menampilkan data)
     */
    public function show()
    {
        try {
            $pelanggans = Pelanggan::orderBy('created_at', 'desc')->paginate(10);

            return view('reports.operational.show', compact('pelanggans'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data pelanggan: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengambil data']);
        }
    }
}

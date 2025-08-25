<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class OperationalReportController extends Controller
{
    // 🔹 Tampilkan form + hasil (read/search)
    public function index(Request $request)
    {
        // filter pencarian (poin 2)
        $filterField = $request->get('filter_field'); // id_pelanggan, nama_pelanggan, bandwidth, alamat, nomor_telepon, cluster
        $filterQuery = $request->get('filter_query');

        $pelanggans = Pelanggan::query()
            ->when($filterField && $filterQuery, function ($q) use ($filterField, $filterQuery) {
                if (in_array($filterField, ['latitude','longitude'])) {
                    // cari latitude/longitude jika diisi angka
                    return $q->where($filterField, $filterQuery);
                }
                return $q->where($filterField, 'like', "%{$filterQuery}%");
            })
            ->latest()
            ->paginate(10);

        return view('report.operational', compact('pelanggans', 'filterField', 'filterQuery'));
    }

    // 🔹 Simpan data pelanggan (poin 1 & 3)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'   => 'required|string|max:100|unique:pelanggans,id_pelanggan',
            'nama_pelanggan' => 'required|string|max:255',
            'bandwidth'      => 'required|string|max:100',
            'alamat'         => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'nomor_telepon'  => 'required|string|max:50',
            'cluster'        => 'required|string|max:100',
            'kode_fat'       => 'nullable|string|max:100',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('reports.operational')
            ->with('success', 'Data pelanggan berhasil disimpan.');
    }

    // 🔹 Update cepat titik koordinat / data lain (opsional untuk drag marker lalu simpan)
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'bandwidth'      => 'required|string|max:100',
            'alamat'         => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'nomor_telepon'  => 'required|string|max:50',
            'cluster'        => 'required|string|max:100',
            'kode_fat'       => 'nullable|string|max:100',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('reports.operational')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // 🔹 Hapus (opsional CRUD lengkap)
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return back()->with('success', 'Data pelanggan dihapus.');
    }
}

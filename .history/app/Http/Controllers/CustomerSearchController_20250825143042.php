<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSearchController extends Controller
{
    /**
     * Tampilkan halaman pencarian pelanggan & kode FAT
     */
    public function index(Request $request)
    {
        $filterField = $request->get('filter_field');
        $filterQuery = $request->get('filter_query');

        // Query builder untuk pencarian
        $query = Pelanggan::query();

        // Apply filter berdasarkan field dan query
        if ($filterField && $filterQuery) {
            switch ($filterField) {
                case 'id_pelanggan':
                    $query->where('id_pelanggan', 'like', "%{$filterQuery}%");
                    break;
                case 'nama_pelanggan':
                    $query->where('nama_pelanggan', 'like', "%{$filterQuery}%");
                    break;
                case 'bandwidth':
                    $query->where('bandwidth', 'like', "%{$filterQuery}%");
                    break;
                case 'alamat':
                    $query->where('alamat', 'like', "%{$filterQuery}%");
                    break;
                case 'nomor_telepon':
                    $query->where('nomor_telepon', 'like', "%{$filterQuery}%");
                    break;
                case 'cluster':
                    $query->where('cluster', 'like', "%{$filterQuery}%");
                    break;
                case 'kode_fat':
                    $query->where('kode_fat', 'like', "%{$filterQuery}%");
                    break;
                case 'latitude':
                    $query->where('latitude', 'like', "%{$filterQuery}%");
                    break;
                case 'longitude':
                    $query->where('longitude', 'like', "%{$filterQuery}%");
                    break;
                default:
                    // Search di semua field jika tidak spesifik
                    $query->where(function($q) use ($filterQuery) {
                        $q->where('id_pelanggan', 'like', "%{$filterQuery}%")
                          ->orWhere('nama_pelanggan', 'like', "%{$filterQuery}%")
                          ->orWhere('bandwidth', 'like', "%{$filterQuery}%")
                          ->orWhere('alamat', 'like', "%{$filterQuery}%")
                          ->orWhere('nomor_telepon', 'like', "%{$filterQuery}%")
                          ->orWhere('cluster', 'like', "%{$filterQuery}%")
                          ->orWhere('kode_fat', 'like', "%{$filterQuery}%");
                    });
                    break;
            }
        }

        // Order by latest dan paginate
        $pelanggans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('customer.search', compact('pelanggans', 'filterField', 'filterQuery'));
    }

    /**
     * Update data pelanggan dari halaman pencarian
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_pelanggan'   => 'required|string|max:100|unique:pelanggans,id_pelanggan,' . $id,
            'nama_pelanggan' => 'required|string|max:255',
            'bandwidth'      => 'required|string|max:100',
            'nomor_telepon'  => 'required|string|max:50',
            'alamat'         => 'required|string',
            'cluster'        => 'required|string|max:100',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'kode_fat'       => 'nullable|string|max:100',
        ], [
            'id_pelanggan.required' => 'ID Pelanggan wajib diisi',
            'id_pelanggan.unique' => 'ID Pelanggan sudah digunakan oleh pelanggan lain',
            'nama_pelanggan.required' => 'Nama Pelanggan wajib diisi',
            'bandwidth.required' => 'Bandwidth wajib diisi',
            'nomor_telepon.required' => 'Nomor Telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'cluster.required' => 'Cluster wajib dipilih',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
        ]);

        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->update($validated);

            return back()->with('success', "Data pelanggan {$validated['nama_pelanggan']} berhasil diperbarui!");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus data pelanggan dari halaman pencarian
     */
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $nama = $pelanggan->nama_pelanggan;

            $pelanggan->delete();

            return back()->with('success', "Data pelanggan {$nama} berhasil dihapus!");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }

    /**
     * Pencarian berdasarkan kode FAT saja
     */
    public function searchByFAT(Request $request)
    {
        $kodeFAT = $request->get('kode_fat');

        $query = Pelanggan::query();

        if ($kodeFAT) {
            $query->where('kode_fat', 'like', "%{$kodeFAT}%");
        }

        $pelanggans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('customer.search', compact('pelanggans'))
            ->with('filterField', 'kode_fat')
            ->with('filterQuery', $kodeFAT);
    }

    /**
     * Advanced search dengan multiple filters
     */
    public function advancedSearch(Request $request)
    {
        $query = Pelanggan::query();

        // Filter berdasarkan cluster
        if ($request->filled('cluster_filter')) {
            $query->where('cluster', $request->cluster_filter);
        }

        // Filter berdasarkan range bandwidth
        if ($request->filled('bandwidth_min')) {
            $query->whereRaw('CAST(REGEXP_REPLACE(bandwidth, "[^0-9]", "") AS UNSIGNED) >= ?', [$request->bandwidth_min]);
        }

        if ($request->filled('bandwidth_max')) {
            $query->whereRaw('CAST(REGEXP_REPLACE(bandwidth, "[^0-9]", "") AS UNSIGNED) <= ?', [$request->bandwidth_max]);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan keberadaan kode FAT
        if ($request->filled('has_fat_code')) {
            if ($request->has_fat_code == 'yes') {
                $query->whereNotNull('kode_fat')->where('kode_fat', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('kode_fat')->orWhere('kode_fat', '');
                });
            }
        }

        // Filter berdasarkan keberadaan koordinat
        if ($request->filled('has_coordinates')) {
            if ($request->has_coordinates == 'yes') {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            } else {
                $query->where(function($q) {
                    $q->whereNull('latitude')->orWhereNull('longitude');
                });
            }
        }

        $pelanggans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('customer.search', compact('pelanggans'))
            ->with('isAdvancedSearch', true);
    }

    /**
     * Export data hasil pencarian (bonus feature)
     */
    public function exportSearch(Request $request)
    {
        // Implementasi export ke Excel/CSV bisa ditambahkan di sini
        // Menggunakan library seperti Laravel Excel
        
        return back()->with('info', 'Fitur export akan segera tersedia!');
    }

    /**
     * Get statistics untuk dashboard pencarian
     */
    public function getStatistics()
    {
        $stats = [
            'total_customers' => Pelanggan::count(),
            'customers_with_fat' => Pelanggan::whereNotNull('kode_fat')->where('kode_fat', '!=', '')->count(),
            'customers_with_coordinates' => Pelanggan::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'clusters' => Pelanggan::select('cluster', DB::raw('count(*) as total'))->groupBy('cluster')->get(),
        ];

        return response()->json($stats);
    }
}

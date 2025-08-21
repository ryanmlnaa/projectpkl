@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <!-- FORM INPUT ACTIVITY -->
    <div class="col-lg-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Tambah Report Activity</h5>
        </div>
        <div class="card-body">
          <form enctype="multipart/form-data">
            <div class="form-group">
              <label><strong>Sales</strong></label>
              <input type="text" class="form-control" placeholder="Nama Sales">
            </div>
            <div class="form-group">
              <label><strong>Aktivitas / Kegiatan</strong></label>
              <input type="text" class="form-control" placeholder="Contoh: Kunjungan PT ABC">
            </div>
            <div class="form-group">
              <label><strong>Tanggal</strong></label>
              <input type="date" class="form-control">
            </div>
            <div class="form-group">
              <label><strong>Lokasi</strong></label>
              <input type="text" class="form-control" placeholder="Contoh: Bondowoso">
            </div>
            <div class="form-group">
              <label><strong>Evidence (Foto Progress)</strong></label>
              <input type="file" class="form-control-file">
            </div>
            <div class="form-group">
              <label><strong>Hasil / Kendala</strong></label>
              <textarea class="form-control" rows="3" placeholder="Tuliskan hasil kegiatan atau kendala yang ditemui"></textarea>
            </div>
            <div class="form-group">
              <label><strong>Status</strong></label>
              <select class="form-control">
                <option>Selesai</option>
                <option>Proses</option>
                <option>Pending</option>
              </select>
            </div>
            <button class="btn btn-success btn-block">Simpan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- TABEL DATA -->
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Daftar Report Activity</h5>
          <button class="btn btn-sm btn-outline-light">Export PDF</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
              <thead class="thead-light">
                <tr>
                  <th>No</th>
                  <th>Sales</th>
                  <th>Aktivitas</th>
                  <th>Tanggal</th>
                  <th>Lokasi</th>
                  <th>Evidence</th>
                  <th>Hasil/Kendala</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>John Doe</td>
                  <td>Kunjungan PT ABC</td>
                  <td>2025-08-20</td>
                  <td>Bondowoso</td>
                  <td>
                    <img src="https://via.placeholder.com/80" class="img-thumbnail">
                  </td>
                  <td>Berhasil presentasi produk, kendala pada penjadwalan lanjutan.</td>
                  <td><span class="badge badge-success">Selesai</span></td>
                  <td>
                    <button class="btn btn-sm btn-primary">Edit</button>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Jane Smith</td>
                  <td>Follow Up via Telepon</td>
                  <td>2025-08-21</td>
                  <td>Surabaya</td>
                  <td>
                    <img src="https://via.placeholder.com/80" class="img-thumbnail">
                  </td>
                  <td>Client masih mempertimbangkan, kendala: keputusan di level manajemen.</td>
                  <td><span class="badge badge-warning">Proses</span></td>
                  <td>
                    <button class="btn btn-sm btn-primary">Edit</button>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

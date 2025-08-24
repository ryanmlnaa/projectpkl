    @extends('layouts.app')

    @section('content')
    <div class="row">
        {{-- Form Tambah Report --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Tambah Report Activity</div>
                <div class="card-body">
                    {{-- pesan sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Sales</label>
                            <input type="text" name="sales" class="form-control" placeholder="Nama Sales" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Aktivitas / Kegiatan</label>
                            <input type="text" name="aktivitas" class="form-control" placeholder="Contoh: Kunjungan PT ABC" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Bondowoso" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Evidence (Foto Progress)</label>
                            <input type="file" name="evidence" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Hasil / Kendala</label>
                            <textarea name="hasil_kendala" class="form-control" placeholder="Tuliskan hasil kegiatan atau kendala yang ditemui"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="selesai">Selesai</option>
                                <option value="proses">Proses</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar Report --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Daftar Report Activity</span>
                    <a href="{{ route('reports.exportPdf') }}" class="btn btn-sm btn-light">Export PDF</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sales</th>
                                <th>Aktivitas</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Evidence</th>
                                <th>Hasil / Kendala</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $i => $report)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $report->sales }}</td>
                                    <td>{{ $report->aktivitas }}</td>
                                    <td>{{ $report->tanggal }}</td>
                                    <td>{{ $report->lokasi }}</td>
                                    <td>
                                        @if($report->evidence)
                                            <img src="{{ asset('storage/'.$report->evidence) }}" width="80">
                                        @endif
                                    </td>
                                    <td>{{ $report->hasil_kendala }}</td>
                                    <td>
                                        <span class="badge {{ $report->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Tombol Edit --}}
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $report->id }}">
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="editModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Report</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Sales</label>
                                                        <input type="text" name="sales" class="form-control" value="{{ $report->sales }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Aktivitas</label>
                                                        <input type="text" name="aktivitas" class="form-control" value="{{ $report->aktivitas }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Tanggal</label>
                                                        <input type="date" name="tanggal" class="form-control" value="{{ $report->tanggal }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Lokasi</label>
                                                        <input type="text" name="lokasi" class="form-control" value="{{ $report->lokasi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Evidence (Opsional)</label><br>
                                                        @if($report->evidence)
                                                            <img src="{{ asset('storage/'.$report->evidence) }}" width="80" class="mb-2"><br>
                                                        @endif
                                                        <input type="file" name="evidence" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Hasil / Kendala</label>
                                                        <textarea name="hasil_kendala" class="form-control">{{ $report->hasil_kendala }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Status</label>
                                                        <select name="status" class="form-control" required>
                                                            <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                            <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada report</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS untuk modal --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection

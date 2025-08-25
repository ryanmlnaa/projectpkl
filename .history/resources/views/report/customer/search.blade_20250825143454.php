@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-search"></i> Cari Pelanggan & Kode FAT</h4>
            </div>

            <div class="card-body">

                {{-- Flash message --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- =========================================
                FORM CARI PELANGGAN + FILTER
                ========================================= --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Pencarian</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('customer.search') }}" class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Filter Berdasarkan</label>
                                        <select name="filter_field" class="form-control">
                                            <option value="">-- Pilih Kolom --</option>
                                            <option value="id_pelanggan" {{ ($filterField ?? '') === 'id_pelanggan' ? 'selected' : '' }}>ID Pelanggan</option>
                                            <option value="nama_pelanggan" {{ ($filterField ?? '') === 'nama_pelanggan' ? 'selected' : '' }}>Nama Pelanggan</option>
                                            <option value="bandwidth" {{ ($filterField ?? '') === 'bandwidth' ? 'selected' : '' }}>Bandwidth</option>
                                            <option value="alamat" {{ ($filterField ?? '') === 'alamat' ? 'selected' : '' }}>Alamat</option>
                                            <option value="nomor_telepon" {{ ($filterField ?? '') === 'nomor_telepon' ? 'selected' : '' }}>Nomor Telepon</option>
                                            <option value="cluster" {{ ($filterField ?? '') === 'cluster' ? 'selected' : '' }}>Cluster</option>
                                            <option value="kode_fat" {{ ($filterField ?? '') === 'kode_fat' ? 'selected' : '' }}>Kode FAT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Kata Kunci</label>
                                        <input type="text" name="filter_query" value="{{ $filterQuery ?? '' }}" class="form-control" placeholder="Ketik kata kunci...">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('customer.search') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================================
                TABEL HASIL PENCARIAN
                ========================================= --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-table"></i>
                                    Hasil Pencarian
                                    @if(isset($pelanggans) && $pelanggans->total() > 0)
                                        <span class="badge bg-warning text-dark">{{ $pelanggans->total() }} data ditemukan</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>ID Pelanggan</th>
                                                <th>Nama</th>
                                                <th>Bandwidth</th>
                                                <th>Alamat</th>
                                                <th>Koordinat</th>
                                                <th>Telepon</th>
                                                <th>Cluster</th>
                                                <th>Kode FAT</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pelanggans as $i => $p)
                                                <tr>
                                                    <td>{{ $pelanggans->firstItem() + $i }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $p->id_pelanggan }}</span>
                                                    </td>
                                                    <td>{{ $p->nama_pelanggan }}</td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $p->bandwidth }}</span>
                                                    </td>
                                                    <td>{{ Str::limit($p->alamat, 30) }}</td>
                                                    <td>
                                                        @if($p->latitude && $p->longitude)
                                                            <small class="text-muted">
                                                                {{ number_format($p->latitude, 4) }}, {{ number_format($p->longitude, 4) }}
                                                            </small>
                                                            <br>
                                                            <a class="btn btn-xs btn-outline-info mt-1" target="_blank"
                                                                href="https://www.google.com/maps?q={{ $p->latitude }},{{ $p->longitude }}">
                                                                <i class="fas fa-map-marker-alt"></i> Maps
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $p->nomor_telepon }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $p->cluster }}</span>
                                                    </td>
                                                    <td>
                                                        @if($p->kode_fat)
                                                            <span class="badge bg-warning text-dark">{{ $p->kode_fat }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            {{-- Tombol Edit --}}
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            {{-- Tombol Delete --}}
                                                            <form action="{{ route('customer.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pelanggan {{ $p->nama_pelanggan }}?')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>

                                                {{-- Modal Edit untuk setiap pelanggan --}}
                                                <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Edit Data Pelanggan</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('customer.update', $p->id) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">ID Pelanggan</label>
                                                                            <input type="text" name="id_pelanggan" value="{{ $p->id_pelanggan }}" class="form-control" required>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Nama Pelanggan</label>
                                                                            <input type="text" name="nama_pelanggan" value="{{ $p->nama_pelanggan }}" class="form-control" required>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Bandwidth</label>
                                                                            <input type="text" name="bandwidth" value="{{ $p->bandwidth }}" class="form-control" required>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Nomor Telepon</label>
                                                                            <input type="text" name="nomor_telepon" value="{{ $p->nomor_telepon }}" class="form-control" required>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label">Alamat</label>
                                                                            <textarea name="alamat" rows="2" class="form-control" required>{{ $p->alamat }}</textarea>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Cluster</label>
                                                                            <select name="cluster" class="form-control" required>
                                                                                <option value="Cluster A" {{ $p->cluster == 'Cluster A' ? 'selected' : '' }}>Cluster A</option>
                                                                                <option value="Cluster B" {{ $p->cluster == 'Cluster B' ? 'selected' : '' }}>Cluster B</option>
                                                                                <option value="Cluster C" {{ $p->cluster == 'Cluster C' ? 'selected' : '' }}>Cluster C</option>
                                                                                <option value="Cluster D" {{ $p->cluster == 'Cluster D' ? 'selected' : '' }}>Cluster D</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Latitude</label>
                                                                            <input type="text" name="latitude" value="{{ $p->latitude }}" class="form-control">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Longitude</label>
                                                                            <input type="text" name="longitude" value="{{ $p->longitude }}" class="form-control">
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label">Kode FAT</label>
                                                                            <input type="text" name="kode_fat" value="{{ $p->kode_fat }}" class="form-control" placeholder="mis. FAT-XYZ-01">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="fas fa-save"></i> Simpan Perubahan
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted py-5">
                                                        <i class="fas fa-search fa-3x mb-3"></i>
                                                        <br>
                                                        <h5>Belum ada data</h5>
                                                        <p>Silakan gunakan filter pencarian atau tambah data pelanggan baru</p>
                                                        <a href="{{ route('reports.operational.index') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus"></i> Tambah Data Pelanggan
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                @if(isset($pelanggans) && $pelanggans->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pelanggans->withQueryString()->links() }}
                    </div>
                @endif

                {{-- Link kembali ke input data --}}
                <div class="text-center mt-4">
                    <a href="{{ route('reports.operational.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Kembali ke Input Data Pelanggan
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.75em;
    }
    .btn-xs {
        padding: 0.15rem 0.3rem;
        font-size: 0.7rem;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

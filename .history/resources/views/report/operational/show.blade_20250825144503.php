@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-table"></i> Data Pelanggan</h4>
                <a href="{{ route('reports.operational.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>

            <div class="card-body">
                {{-- Flash message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Tabel Data Pelanggan --}}
                @if($pelanggans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pelanggan</th>
                                    <th>Nama</th>
                                    <th>Bandwidth</th>
                                    <th>Telepon</th>
                                    <th>Cluster</th>
                                    <th>Kode FAT</th>
                                    <th>Koordinat</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelanggans as $index => $pelanggan)
                                    <tr>
                                        <td>{{ $pelanggans->firstItem() + $index }}</td>
                                        <td><strong>{{ $pelanggan->id_pelanggan }}</strong></td>
                                        <td>{{ $pelanggan->nama_pelanggan }}</td>
                                        <td>{{ $pelanggan->bandwidth }}</td>
                                        <td>{{ $pelanggan->nomor_telepon }}</td>
                                        <td><span class="badge bg-primary">{{ $pelanggan->cluster }}</span></td>
                                        <td>{{ $pelanggan->kode_fat ?? '-' }}</td>
                                        <td>
                                            @if($pelanggan->latitude && $pelanggan->longitude)
                                                <small>
                                                    {{ number_format($pelanggan->latitude, 6) }},
                                                    {{ number_format($pelanggan->longitude, 6) }}
                                                </small>
                                                <br>
                                                <a href="https://maps.google.com?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}"
                                                   target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </a>
                                            @else
                                                <small class="text-muted">Tidak ada</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $pelanggan->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $pelanggan->id }}"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <form action="{{ route('reports.operational.destroy', $pelanggan->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus data {{ $pelanggan->nama_pelanggan }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Detail --}}
                                    <div class="modal fade" id="detailModal{{ $pelanggan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pelanggan: {{ $pelanggan->nama_pelanggan }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <td><strong>ID Pelanggan:</strong></td>
                                                                    <td>{{ $pelanggan->id_pelanggan }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Nama:</strong></td>
                                                                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Bandwidth:</strong></td>
                                                                    <td>{{ $pelanggan->bandwidth }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Telepon:</strong></td>
                                                                    <td>{{ $pelanggan->nomor_telepon }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Cluster:</strong></td>
                                                                    <td>{{ $pelanggan->cluster }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Kode FAT:</strong></td>
                                                                    <td>{{ $pelanggan->kode_fat ?? '-' }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <strong>Alamat:</strong><br>
                                                                <p class="text-muted">{{ $pelanggan->alamat }}</p>
                                                            </div>

                                                            @if($pelanggan->latitude && $pelanggan->longitude)
                                                                <div class="mb-3">
                                                                    <strong>Koordinat:</strong><br>
                                                                    <small>
                                                                        Lat: {{ number_format($pelanggan->latitude, 6) }}<br>
                                                                        Lng: {{ number_format($pelanggan->longitude, 6) }}
                                                                    </small>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <strong>Peta:</strong><br>
                                                                    <div id="miniMap{{ $pelanggan->id }}" style="height: 200px; width: 100%;"></div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pelanggans->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada data pelanggan</h5>
                        <a href="{{ route('reports.operational.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Tambah Data Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-table"></i> Data Pelanggan</h4>
            <a href="{{ route('report.operational.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($pelanggans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Bandwidth</th>
                                <th>Telepon</th>
                                <th>Cluster</th>
                                <th>FAT</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggans as $index => $p)
                                <tr>
                                    <td>{{ $pelanggans->firstItem() + $index }}</td>
                                    <td>{{ $p->id_pelanggan }}</td>
                                    <td>{{ $p->nama_pelanggan }}</td>
                                    <td>{{ $p->bandwidth }}</td>
                                    <td>{{ $p->nomor_telepon }}</td>
                                    <td><span class="badge bg-primary">{{ $p->cluster }}</span></td>
                                    <td>{{ $p->kode_fat ?? '-' }}</td>
                                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <form action="{{ route('report.operational.destroy', $p->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pelanggans->links() }}
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">Belum ada data</h5>
                    <a href="{{ route('report.operational.index') }}" class="btn btn-primary">Tambah Data</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

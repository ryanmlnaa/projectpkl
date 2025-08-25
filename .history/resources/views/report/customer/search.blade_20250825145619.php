@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Pencarian Pelanggan</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Halaman pencarian masih dalam pengembangan.
                <a href="{{ route('report.operational.show') }}">Lihat semua data</a>
            </div>
        </div>
    </div>
</div>
@endsection

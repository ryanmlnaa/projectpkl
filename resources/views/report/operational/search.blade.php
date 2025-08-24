@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-info text-white">
      <h4 class="mb-0"><i class="fas fa-search"></i> Cari Pelanggan</h4>
    </div>
    <div class="card-body">
      {{-- form filter pelanggan --}}
      @include('reports.operational.partials.form-search')

      {{-- tabel hasil --}}
      @include('reports.operational.partials.table')
    </div>
  </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-warning text-dark">
      <h4 class="mb-0"><i class="fas fa-network-wired"></i> Kotak Distribusi (FAT)</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('reports.operational.fat.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Kode FAT</label>
            <input type="text" name="kode_fat" class="form-control" placeholder="mis. FAT-XYZ-01" required>
          </div>
          <div class="col-md-8">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

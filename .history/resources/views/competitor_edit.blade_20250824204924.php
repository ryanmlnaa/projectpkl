@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-warning">
      <h4 class="mb-0 text-white"><i class="fas fa-edit"></i> Edit Competitor</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('competitor.update', $competitor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <option value="">-- Pilih Cluster --</option>
            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $cl)
                <option value="{{ $cl }}">Cluster {{ $cl }}</option>
            @endforeach

        <div class="mb-3">
          <label class="form-label">Nama Competitor</label>
          <input type="text" name="competitor_name" class="form-control" value="{{ $competitor->competitor_name }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Harga</label>
          <input type="number" name="harga" class="form-control" value="{{ $competitor->harga }}" required>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        <a href="{{ route('competitor.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>
@endsection

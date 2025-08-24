@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fas fa-user-plus"></i> Input Data Pelanggan</h4>
    </div>
    <div class="card-body">
      {{-- form input pelanggan (potongan dari code kamu) --}}
      @include('reports.operational.partials.form-input')
    </div>
  </div>
</div>
@endsection

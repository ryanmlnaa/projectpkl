@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fas fa-users"></i> Report Competitor</h4>
    </div>
    <div class="card-body">

     <!-- FORM INPUT COMPETITOR -->
      {{-- 🔹 Ubah action form ke route competitor.store --}}
      <form id="competitorForm" action="{{ route('competitor.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label"><strong>Pilih Cluster</strong></label>
            <select class="form-control select2" name="cluster" id="clusterSelect" required>
            <option value="">-- Pilih Cluster --</option>
            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $cl)
                <option value="{{ $cl }}">Cluster {{ $cl }}</option>
            @endforeach
            </select>
          </div>
        </div>

        <div id="competitorInputs" style="display: none;">
          <div class="border p-3 rounded bg-light mb-3">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label">Nama Competitor</label>
                <input type="text" name="competitor_name[]" class="form-control" placeholder="Ketik nama competitor..." required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Harga</label>
                <input type="number" name="harga[]" class="form-control" placeholder="Masukkan harga" required>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeRow d-none">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <div id="moreCompetitors"></div>

          <button type="button" class="btn btn-outline-primary btn-sm" id="addMoreBtn">
            <i class="fas fa-plus"></i> Tambah Competitor Lain
          </button>
        </div>

        <div class="mt-4" id="saveBtn" style="display: none;">
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Data</button>
        </div>
      </form>

      <!-- TABEL HASIL -->
      <hr>
      <h5 class="mb-3">Data Competitor</h5>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-bg-primary text-">
            <tr>
              <th>No</th>
              <th>Cluster</th>
              <th>Nama Competitor</th>
              <th>Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {{-- 🔹 Looping data competitor --}}
            @forelse($competitors as $index => $item)
            <tr>
              <td>{{ $index+1 }}</td>
              <td><span class="badge bg-info">{{ $item->cluster }}</span></td>
              <td>{{ $item->competitor_name }}</td>
              <td><strong>Rp {{ number_format($item->harga, 0, ',', '.') }}</strong></td>
              <td>
                {{-- 🔹 Edit --}}
                <a href="{{ route('competitor.edit', $item->id) }}" class="btn btn-sm btn-primary">
                  <i class="fas fa-edit"></i>
                </a>

                {{-- 🔹 Delete --}}
                <form action="{{ route('competitor.destroy', $item->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data?')">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada data competitor</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- SCRIPT -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
  $('#clusterSelect').select2({ placeholder: "Cari cluster..." });
</script>

<script>
  // Tampilkan form competitor setelah cluster dipilih
  document.getElementById("clusterSelect").addEventListener("change", function() {
    document.getElementById("competitorInputs").style.display = this.value ? "block" : "none";
    document.getElementById("saveBtn").style.display = this.value ? "block" : "none";
  });

  // Tambah competitor lain
  document.getElementById("addMoreBtn").addEventListener("click", function() {
    let div = document.createElement("div");
    div.classList.add("border", "p-3", "rounded", "bg-light", "mb-3");
    div.innerHTML = `
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label">Nama Competitor</label>
          <input type="text" name="competitor_name[]" class="form-control" placeholder="Ketik nama competitor..." required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Harga</label>
          <input type="number" name="harga[]" class="form-control" placeholder="Masukkan harga" required>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    `;
    document.getElementById("moreCompetitors").appendChild(div);

    // tombol hapus
    div.querySelector(".removeRow").addEventListener("click", function() {
      div.remove();
    });
  });
</script>
@endsection

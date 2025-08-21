@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fas fa-users"></i> Report Competitor</h4>
    </div>
    <div class="card-body">
      <!-- FORM PILIH CLUSTER -->
      <form id="competitorForm" action="#" method="POST">
        @csrf
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label"><strong>Pilih Cluster</strong></label>
            <select class="form-control" id="clusterSelect" required>
              <option value="">-- Pilih Cluster --</option>
              <option value="Cluster A">Cluster A</option>
              <option value="Cluster B">Cluster B</option>
              <option value="Cluster C">Cluster C</option>
              <option value="Cluster D">Cluster D</option>
            </select>
          </div>
        </div>

        <!-- FORM INPUT COMPETITOR -->
        <div id="competitorInputs" style="display: none;">
          <div class="border p-3 rounded bg-light mb-3">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label">Nama Competitor</label>
                <input type="text" name="competitor_name[]" class="form-control" list="competitorList" placeholder="Ketik nama competitor..." required>
                <datalist id="competitorList">
                  <option value="Competitor 1">
                  <option value="Competitor 2">
                  <option value="Competitor 3">
                  <option value="Competitor 4">
                </datalist>
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
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Cluster</th>
              <th>Nama Competitor</th>
              <th>Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><span class="badge bg-info">Cluster A</span></td>
              <td>Competitor 1</td>
              <td><strong>Rp 25.000</strong></td>
              <td>
                <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td><span class="badge bg-warning">Cluster B</span></td>
              <td>Competitor 2</td>
              <td><strong>Rp 30.000</strong></td>
              <td>
                <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- SCRIPT -->
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
          <input type="text" name="competitor_name[]" class="form-control" list="competitorList" placeholder="Ketik nama competitor..." required>
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

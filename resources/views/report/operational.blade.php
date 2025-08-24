@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Operational Report (Input Data Pelanggan)</h4>
    </div>

    <div class="card-body">

      {{-- 🔹 Flash message --}}
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
           (1) FORM INPUT DATA PELANGGAN
          ========================================= --}}
      <form action="{{ route('reports.operational.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">ID Pelanggan</label>
            <input type="text" name="id_pelanggan" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Bandwidth</label>
            <input type="text" name="bandwidth" class="form-control" placeholder="mis. 50 Mbps" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="2" class="form-control" required></textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Cluster</label>
            <select name="cluster" class="form-control" required>
              <option value="">-- Pilih Cluster --</option>
              <option>Cluster A</option>
              <option>Cluster B</option>
              <option>Cluster C</option>
              <option>Cluster D</option>
            </select>
          </div>

          {{-- (2) Titik Koordinat via Google Maps (draggable marker) --}}
          <div class="col-md-3">
            <label class="form-label">Latitude</label>
            <input type="text" id="latInput" name="latitude" class="form-control" placeholder="-6.200000">
          </div>
          <div class="col-md-3">
            <label class="form-label">Longitude</label>
            <input type="text" id="lngInput" name="longitude" class="form-control" placeholder="106.816666">
          </div>

          <div class="col-12">
            <label class="form-label mb-2">Peta Lokasi (geser jarum untuk set koordinat)</label>
            <div id="map" style="width: 100%; height: 360px;" class="rounded border"></div>
          </div>

          {{-- (3) Kode FAT (Kotak Distribusi) --}}
          <div class="col-md-3">
            <label class="form-label">Kode FAT (Kotak Distribusi)</label>
            <input type="text" name="kode_fat" class="form-control" placeholder="mis. FAT-XYZ-01">
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-success mt-2">
              <i class="fas fa-save"></i> Simpan Data
            </button>
          </div>
        </div>
      </form>

      <hr>

      {{-- =========================================
           (2) FORM CARI PELANGGAN + FILTER
          ========================================= --}}
      <form method="GET" action="{{ route('reports.operational') }}" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
          <label class="form-label">Filter Berdasarkan</label>
          <select name="filter_field" class="form-control">
            <option value="">-- Pilih Kolom --</option>
            <option value="id_pelanggan"  {{ ($filterField ?? '')==='id_pelanggan' ? 'selected' : '' }}>ID Pelanggan</option>
            <option value="nama_pelanggan"{{ ($filterField ?? '')==='nama_pelanggan' ? 'selected' : '' }}>Nama Pelanggan</option>
            <option value="bandwidth"     {{ ($filterField ?? '')==='bandwidth' ? 'selected' : '' }}>Bandwidth</option>
            <option value="alamat"        {{ ($filterField ?? '')==='alamat' ? 'selected' : '' }}>Alamat</option>
            {{-- Titik koordinat: biasanya cari manual via map, tetap disediakan opsional --}}
            <option value="latitude"      {{ ($filterField ?? '')==='latitude' ? 'selected' : '' }}>Latitude</option>
            <option value="longitude"     {{ ($filterField ?? '')==='longitude' ? 'selected' : '' }}>Longitude</option>
            <option value="nomor_telepon" {{ ($filterField ?? '')==='nomor_telepon' ? 'selected' : '' }}>Nomor Telepon</option>
            <option value="cluster"       {{ ($filterField ?? '')==='cluster' ? 'selected' : '' }}>Cluster</option>
          </select>
        </div>
        <div class="col-md-5">
          <label class="form-label">Kata Kunci</label>
          <input type="text" name="filter_query" value="{{ $filterQuery ?? '' }}" class="form-control" placeholder="Ketik kata kunci...">
        </div>
        <div class="col-md-4">
          <button class="btn btn-outline-primary">
            <i class="fas fa-search"></i> Cari
          </button>
          <a href="{{ route('reports.operational') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
      </form>

      {{-- =========================================
           TABEL HASIL
          ========================================= --}}
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>ID Pelanggan</th>
              <th>Nama</th>
              <th>Bandwidth</th>
              <th>Alamat</th>
              <th>Koordinat</th>
              <th>Telepon</th>
              <th>Cluster</th>
              <th>Kode FAT</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($pelanggans as $i => $p)
            <tr>
              <td>{{ $pelanggans->firstItem() + $i }}</td>
              <td>{{ $p->id_pelanggan }}</td>
              <td>{{ $p->nama_pelanggan }}</td>
              <td>{{ $p->bandwidth }}</td>
              <td>{{ $p->alamat }}</td>
              <td>
                @if($p->latitude && $p->longitude)
                  {{ $p->latitude }}, {{ $p->longitude }}
                  <br>
                  <a class="btn btn-xs btn-outline-info mt-1"
                     target="_blank"
                     href="https://www.google.com/maps?q={{ $p->latitude }},{{ $p->longitude }}">
                     Lihat di Maps
                  </a>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>{{ $p->nomor_telepon }}</td>
              <td>{{ $p->cluster }}</td>
              <td>{{ $p->kode_fat ?? '-' }}</td>
              <td class="d-flex gap-1">
                {{-- Tombol edit cepat (modal sederhana atau redirect ke form update) --}}
                <form action="{{ route('reports.operational.update', $p->id) }}" method="POST" class="d-inline">
                  @csrf @method('PUT')
                  <input type="hidden" name="nama_pelanggan" value="{{ $p->nama_pelanggan }}">
                  <input type="hidden" name="bandwidth" value="{{ $p->bandwidth }}">
                  <input type="hidden" name="alamat" value="{{ $p->alamat }}">
                  <input type="hidden" name="latitude" value="{{ $p->latitude }}">
                  <input type="hidden" name="longitude" value="{{ $p->longitude }}">
                  <input type="hidden" name="nomor_telepon" value="{{ $p->nomor_telepon }}">
                  <input type="hidden" name="cluster" value="{{ $p->cluster }}">
                  <input type="hidden" name="kode_fat" value="{{ $p->kode_fat }}">
                  <button type="submit" class="btn btn-sm btn-primary" title="Update cepat data terakhir">
                    <i class="fas fa-save"></i>
                  </button>
                </form>

                <form action="{{ route('reports.operational.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger" title="Hapus">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted">Belum ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div>
        {{ $pelanggans->withQueryString()->links() }}
      </div>

    </div>
  </div>
</div>

{{-- =========================================
     GOOGLE MAPS: draggable marker
     - ganti YOUR_GOOGLE_MAPS_API_KEY
   ========================================= --}}
<script>
  let map, marker;

  function initMap() {
    const defaultCenter = { lat: -6.200000, lng: 106.816666 }; // Jakarta default
    const latInput = document.getElementById('latInput');
    const lngInput = document.getElementById('lngInput');

    const startCenter = {
      lat: parseFloat(latInput.value) || defaultCenter.lat,
      lng: parseFloat(lngInput.value) || defaultCenter.lng,
    };

    map = new google.maps.Map(document.getElementById("map"), {
      center: startCenter,
      zoom: 14,
    });

    marker = new google.maps.Marker({
      position: startCenter,
      map,
      draggable: true,
    });

    // 🔹 update input saat marker digeser
    marker.addListener("dragend", (e) => {
      latInput.value = e.latLng.lat().toFixed(7);
      lngInput.value = e.latLng.lng().toFixed(7);
    });

    // 🔹 klik peta: pindahkan marker
    map.addListener("click", (e) => {
      marker.setPosition(e.latLng);
      latInput.value = e.latLng.lat().toFixed(7);
      lngInput.value = e.latLng.lng().toFixed(7);
    });

    // 🔹 jika user ketik manual lat/lng lalu blur, pindahkan marker
    function moveMarkerFromInputs() {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        const pos = { lat, lng };
        marker.setPosition(pos);
        map.panTo(pos);
      }
    }
    latInput.addEventListener('change', moveMarkerFromInputs);
    lngInput.addEventListener('change', moveMarkerFromInputs);
  }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
@endsection

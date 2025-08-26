@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Input Data Pelanggan</h4>
        </div>
        <div class="card-body">
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

            {{-- FORM INPUT --}}
            <form action="{{ route('report.operational.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">ID Pelanggan *</label>
                        <input type="text" name="id_pelanggan" class="form-control" value="{{ old('id_pelanggan') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Pelanggan *</label>
                        <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cluster *</label>
                        <select name="cluster" id="cluster" class="form-control" required>
                            <option value="">-- Pilih Cluster --</option>
                            @php
                                $uniqueClusters = $competitors->pluck('cluster')->unique();
                            @endphp
                            @foreach($uniqueClusters as $cluster)
                                <option value="{{ $cluster }}" {{ old('cluster') == $cluster ? 'selected' : '' }}>
                                    {{ $cluster }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bandwidth *</label>
                        <select name="bandwidth" id="bandwidth" class="form-control" required>
                            <option value="">-- Pilih Kecepatan --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nomor Telepon *</label>
                        <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat *</label>
                        <textarea name="alamat" rows="2" class="form-control" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kode FAT</label>
                        <input type="text" id="kode_fat" name="kode_fat" class="form-control" placeholder="FAT-XYZ-01" value="{{ old('kode_fat') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" placeholder="-6.200000" value="{{ old('latitude') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" placeholder="106.816666" value="{{ old('longitude') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </form>

            {{-- Google Maps --}}
            <div class="mt-4">
                <h5>Pilih Lokasi Pelanggan:</h5>
                <div id="map" style="height:400px; width:100%; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2);"></div>
            </div>

            {{-- TABEL DATA PELANGGAN --}}
            <div class="mt-4">
                <h5>Data Pelanggan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Bandwidth</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Cluster</th>
                                <th>Kode FAT</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggans as $p)
                                <tr>
                                    <td>{{ $p->id_pelanggan }}</td>
                                    <td>{{ $p->nama_pelanggan }}</td>
                                    <td>{{ $p->bandwidth }}</td>
                                    <td>{{ $p->nomor_telepon }}</td>
                                    <td>{{ $p->alamat }}</td>
                                    <td>{{ $p->cluster }}</td>
                                    <td>{{ $p->kode_fat }}</td>
                                    <td>{{ $p->latitude }}</td>
                                    <td>{{ $p->longitude }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data pelanggan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
  let map, marker;

  // Initialize Google Maps
  function initMap() {
    const defaultLoc = { lat: -6.200000, lng: 106.816666 };

    map = new google.maps.Map(document.getElementById("map"), {
      center: defaultLoc,
      zoom: 13
    });

    marker = new google.maps.Marker({
      position: defaultLoc,
      map: map,
      draggable: true
    });

    // update form saat marker digeser
    google.maps.event.addListener(marker, 'dragend', function(event) {
      document.getElementById("latitude").value = event.latLng.lat().toFixed(6);
      document.getElementById("longitude").value = event.latLng.lng().toFixed(6);
    });

    // update form saat klik peta
    google.maps.event.addListener(map, 'click', function(event) {
      marker.setPosition(event.latLng);
      document.getElementById("latitude").value = event.latLng.lat().toFixed(6);
      document.getElementById("longitude").value = event.latLng.lng().toFixed(6);
    });
  }

  // Dynamic Bandwidth based on Cluster
  $(document).ready(function() {
    $('#cluster').change(function() {
      var selectedCluster = $(this).val();
      var bandwidthSelect = $('#bandwidth');
      
      // Reset bandwidth options
      bandwidthSelect.html('<option value="">-- Pilih Kecepatan --</option>');
      
      if (selectedCluster !== '') {
        // AJAX call to get bandwidth options
        $.ajax({
          url: "{{ route('operational.getKecepatanByCluster') }}",
          type: "GET",
          data: {
            cluster: selectedCluster,
            _token: "{{ csrf_token() }}"
          },
          success: function(data) {
            $.each(data, function(index, kecepatan) {
              if (kecepatan) { // Only add non-null values
                bandwidthSelect.append(
                  '<option value="' + kecepatan + '">' + kecepatan + '</option>'
                );
              }
            });
          },
          error: function() {
            alert('Gagal mengambil data kecepatan');
          }
        });
      }
    });

    // Auto-generate Kode FAT based on cluster
    $('#cluster').change(function() {
      var selectedCluster = $(this).val();
      if (selectedCluster !== '') {
        // Generate kode FAT format: FAT-[CLUSTER]-XX
        var clusterCode = selectedCluster.replace('Cluster ', '');
        var randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');
        var kodeFat = 'FAT-' + clusterCode + '-' + randomNum;
        
        $('#kode_fat').val(kodeFat);
      } else {
        $('#kode_fat').val('');
      }
    });
  });
</script>

@endsection
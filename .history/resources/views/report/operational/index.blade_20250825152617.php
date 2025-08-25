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
                        <label class="form-label">Bandwidth *</label>
                        <input type="text" name="bandwidth" class="form-control" placeholder="mis. 50 Mbps" value="{{ old('bandwidth') }}" required>
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
                        <label class="form-label">Cluster *</label>
                        <select name="cluster" class="form-control" required>
                            <option value="">-- Pilih Cluster --</option>
                            <option value="Cluster A" {{ old('cluster') == 'Cluster A' ? 'selected' : '' }}>Cluster A</option>
                            <option value="Cluster B" {{ old('cluster') == 'Cluster B' ? 'selected' : '' }}>Cluster B</option>
                            <option value="Cluster C" {{ old('cluster') == 'Cluster C' ? 'selected' : '' }}>Cluster C</option>
                            <option value="Cluster D" {{ old('cluster') == 'Cluster D' ? 'selected' : '' }}>Cluster D</option>
                        </select>
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
                <div id="map" style="height: 400px; width: 100%; border-radius:10px;"></div>
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

    // Contoh lokasi FAT (kamu bisa ganti sesuai data real)
    const fatZones = [
        { name: "FAT-01", lat: -6.210, lng: 106.820 },
        { name: "FAT-02", lat: -6.190, lng: 106.810 },
        { name: "FAT-03", lat: -6.205, lng: 106.830 },
    ];

    function initMap() {
        const defaultLoc = { lat: -6.200000, lng: 106.816666 }; // Jakarta

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: defaultLoc,
        });

        marker = new google.maps.Marker({
            position: defaultLoc,
            map: map,
            draggable: true
        });

        // Update form saat marker digeser
        google.maps.event.addListener(marker, 'dragend', function(event) {
            updateForm(event.latLng.lat(), event.latLng.lng());
        });

        // Update form saat klik peta
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            updateForm(event.latLng.lat(), event.latLng.lng());
        });
    }

    // Cari FAT terdekat berdasarkan koordinat
    function getNearestFAT(lat, lng) {
        let minDist = Infinity;
        let nearest = null;
        fatZones.forEach(fat => {
            let dist = Math.sqrt(Math.pow(fat.lat - lat, 2) + Math.pow(fat.lng - lng, 2));
            if (dist < minDist) {
                minDist = dist;
                nearest = fat.name;
            }
        });
        return nearest;
    }

    // Update input form
    function updateForm(lat, lng) {
        document.getElementById("latitude").value = lat.toFixed(6);
        document.getElementById("longitude").value = lng.toFixed(6);

        // Isi otomatis kode FAT
        let nearestFat = getNearestFAT(lat, lng);
        document.getElementById("kode_fat").value = nearestFat ?? "";
    }
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Input Data Pelanggan</h4>
        </div>
        <div class="card-body">

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
                            <option value="Cluster A">Cluster A</option>
                            <option value="Cluster B">Cluster B</option>
                            <option value="Cluster C">Cluster C</option>
                            <option value="Cluster D">Cluster D</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kode FAT</label>
                        <input type="text" name="kode_fat" class="form-control" placeholder="FAT-XYZ-01">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" placeholder="-6.200000" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" placeholder="106.816666" readonly>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </form>

            {{-- GOOGLE MAPS --}}
            <div class="mt-4">
                <h5>Pilih Lokasi Pelanggan:</h5>
                <div id="map" style="height:400px; width:100%; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2);"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    let map, marker;

    function initMap() {
        const defaultLoc = { lat: -6.200000, lng: 106.816666 }; // Default Jakarta

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: defaultLoc,
        });

        marker = new google.maps.Marker({
            position: defaultLoc,
            map: map,
            draggable: true
        });

        // Update input saat marker digeser
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById("latitude").value = event.latLng.lat();
            document.getElementById("longitude").value = event.latLng.lng();
        });

        // Update input saat klik peta
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            document.getElementById("latitude").value = event.latLng.lat();
            document.getElementById("longitude").value = event.latLng.lng();
        });
    }
</script>
@endsection

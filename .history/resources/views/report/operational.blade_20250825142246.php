@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Input Data Pelanggan</h4>
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
                FORM INPUT DATA PELANGGAN SAJA
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

                        {{-- Titik Koordinat via Google Maps --}}
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

                        {{-- Kode FAT --}}
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

                {{-- Link ke halaman pencarian --}}
                <div class="text-center mt-4">
                    <a href="{{ route('customer.search') }}" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Cari Pelanggan & Kode FAT
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Google Maps Script --}}
    <script>
        let map, marker;

        function initMap() {
            const defaultCenter = { lat: -6.200000, lng: 106.816666 };
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

            marker.addListener("dragend", (e) => {
                latInput.value = e.latLng.lat().toFixed(7);
                lngInput.value = e.latLng.lng().toFixed(7);
            });

            map.addListener("click", (e) => {
                marker.setPosition(e.latLng);
                latInput.value = e.latLng.lat().toFixed(7);
                lngInput.value = e.latLng.lng().toFixed(7);
            });

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
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
@endsection

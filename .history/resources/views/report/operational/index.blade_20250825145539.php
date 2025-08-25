@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Input Data Pelanggan</h4>
            </div>

            <div class="card-body">

                {{-- Flash message --}}
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

                {{-- Form Input Data Pelanggan --}}
                <form action="{{ route('reports.operational.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">ID Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" name="id_pelanggan" class="form-control" value="{{ old('id_pelanggan') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Bandwidth <span class="text-danger">*</span></label>
                            <input type="text" name="bandwidth" class="form-control" placeholder="mis. 50 Mbps" value="{{ old('bandwidth') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2" class="form-control" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Cluster <span class="text-danger">*</span></label>
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
                            <input type="text" name="kode_fat" class="form-control" placeholder="mis. FAT-XYZ-01" value="{{ old('kode_fat') }}">
                        </div>

                        {{-- Koordinat --}}
                        <div class="col-md-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" id="latInput" name="latitude" class="form-control" placeholder="-6.200000" value="{{ old('latitude', '-6.200000') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" id="lngInput" name="longitude" class="form-control" placeholder="106.816666" value="{{ old('longitude', '106.816666') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label mb-2">Peta Lokasi (klik atau drag marker untuk set koordinat)</label>
                            <div id="map" style="width: 100%; height: 360px;" class="rounded border"></div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Data
                            </button>
                            <a href="{{ route('reports.operational.show') }}" class="btn btn-info ms-2">
                                <i class="fas fa-list"></i> Lihat Data
                            </a>
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
                title: 'Drag marker untuk mengatur lokasi'
            });

            // Event listener untuk drag marker
            marker.addListener("dragend", (e) => {
                latInput.value = e.latLng.lat().toFixed(7);
                lngInput.value = e.latLng.lng().toFixed(7);
            });

            // Event listener untuk klik map
            map.addListener("click", (e) => {
                marker.setPosition(e.latLng);
                latInput.value = e.latLng.lat().toFixed(7);
                lngInput.value = e.latLng.lng().toFixed(7);
            });

            // Function untuk update marker dari input
            function moveMarkerFromInputs() {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    const pos = { lat, lng };
                    marker.setPosition(pos);
                    map.panTo(pos);
                }
            }

            // Event listener untuk perubahan input
            latInput.addEventListener('blur', moveMarkerFromInputs);
            lngInput.addEventListener('blur', moveMarkerFromInputs);
        }

        // Error handling untuk Google Maps
        window.gm_authFailure = function() {
            alert('Google Maps API Key tidak valid atau expired!');
        };
    </script>

    {{-- Ganti YOUR_API_KEY dengan API key Google Maps yang valid --}}
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap">
    </script>
@endsection

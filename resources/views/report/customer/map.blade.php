{{-- resources/views/report/customer/map.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marked-alt fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-1">Peta Lokasi Pelanggan</h4>
                                <p class="mb-0 opacity-75">Visualisasi lokasi pelanggan berdasarkan koordinat GPS</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">{{ $pelanggans->count() }}</h5>
                            <small class="opacity-75">Lokasi Pelanggan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Controls -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="fitAllMarkers()">
                                <i class="fas fa-expand-alt me-1"></i>Lihat Semua
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="findMyLocation()">
                                <i class="fas fa-location-arrow me-1"></i>Lokasi Saya
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="toggleClusters()">
                                <i class="fas fa-layer-group me-1"></i>Toggle Cluster
                            </button>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('customer.search') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="customerMap" style="height: 70vh; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Info Panel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pelanggan di Peta
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row" id="customerInfo">
                        <div class="col-md-3 text-center">
                            <div class="border-end pe-3">
                                <h4 class="text-primary mb-1">{{ $pelanggans->count() }}</h4>
                                <small class="text-muted">Total Pelanggan</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-end pe-3">
                                <h4 class="text-success mb-1">{{ $pelanggans->whereNotNull('kode_fat')->count() }}</h4>
                                <small class="text-muted">Dengan Kode FAT</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-end pe-3">
                                <h4 class="text-info mb-1">{{ $pelanggans->groupBy('cluster')->count() }}</h4>
                                <small class="text-muted">Cluster Aktif</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-warning mb-1">{{ $pelanggans->whereNull('kode_fat')->count() }}</h4>
                            <small class="text-muted">Tanpa Kode FAT</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Detail Modal -->
<div class="modal fade" id="customerDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="customerDetailBody">
                <!-- Detail akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- MarkerCluster Plugin -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
let map;
let markers = [];
let markerClusterGroup;

// Initialize map
function initMap() {
    // Default center: Denpasar, Bali
    const defaultCenter = [-8.6705, 115.2126];
    
    map = L.map('customerMap').setView(defaultCenter, 11);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Initialize marker cluster group
    markerClusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50
    });
    
    // Add customer markers
    addCustomerMarkers();
    
    // Fit map to show all markers
    if (markers.length > 0) {
        fitAllMarkers();
    }
}

// Add customer markers to map
function addCustomerMarkers() {
    const customers = @json($pelanggans);
    
    customers.forEach(function(customer) {
        if (customer.latitude && customer.longitude) {
            // Create custom icon based on cluster
            const icon = createCustomIcon(customer.cluster, customer.kode_fat);
            
            // Create marker
            const marker = L.marker([customer.latitude, customer.longitude], { icon: icon })
                .bindPopup(createPopupContent(customer));
            
            markers.push(marker);
            markerClusterGroup.addLayer(marker);
        }
    });
    
    map.addLayer(markerClusterGroup);
}

// Create custom icon based on cluster and FAT status
function createCustomIcon(cluster, kodeFat) {
    const color = kodeFat ? '#28a745' : '#ffc107'; // Green if has FAT, yellow if not
    
    return L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: ${color}; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-wifi" style="color: white; font-size: 12px;"></i>
               </div>`,
        iconSize: [25, 25],
        iconAnchor: [12, 12]
    });
}

// Create popup content for marker
function createPopupContent(customer) {
    return `
        <div style="min-width: 250px;">
            <h6 class="mb-2">${customer.nama_pelanggan}</h6>
            <div class="row g-2">
                <div class="col-6"><small><strong>ID:</strong> ${customer.id_pelanggan}</small></div>
                <div class="col-6"><small><strong>Bandwidth:</strong> ${customer.bandwidth}</small></div>
                <div class="col-6"><small><strong>Cluster:</strong> ${customer.cluster}</small></div>
                <div class="col-6"><small><strong>FAT:</strong> ${customer.kode_fat || 'Tidak Ada'}</small></div>
                <div class="col-12"><small><strong>Alamat:</strong> ${customer.alamat}</small></div>
                <div class="col-12"><small><strong>Telepon:</strong> ${customer.nomor_telepon}</small></div>
            </div>
            <div class="mt-2">
                <button class="btn btn-sm btn-primary" onclick="showCustomerDetail(${customer.id})">
                    Detail Lengkap
                </button>
            </div>
        </div>
    `;
}

// Fit map to show all markers
function fitAllMarkers() {
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Find user location
function findMyLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            map.setView([lat, lng], 15);
            
            // Add user location marker
            L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'user-location-marker',
                    html: '<div style="background-color: #007bff; width: 15px; height: 15px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                })
            }).addTo(map).bindPopup('Lokasi Anda').openPopup();
        });
    } else {
        alert('Geolocation tidak didukung oleh browser Anda');
    }
}

// Toggle marker clustering
function toggleClusters() {
    if (map.hasLayer(markerClusterGroup)) {
        map.removeLayer(markerClusterGroup);
        markers.forEach(marker => map.addLayer(marker));
    } else {
        markers.forEach(marker => map.removeLayer(marker));
        map.addLayer(markerClusterGroup);
    }
}

// Show customer detail in modal
function showCustomerDetail(customerId) {
    fetch(`/customer/detail/${customerId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('customerDetailBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('customerDetailModal')).show();
        })
        .catch(error => {
            alert('Gagal memuat detail pelanggan');
        });
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush
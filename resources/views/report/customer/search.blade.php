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
                            <i class="fas fa-search fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-1">Cari Pelanggan & Kode FAT</h4>
                                <p class="mb-0 opacity-75">Pencarian data pelanggan berdasarkan berbagai kriteria</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">{{ isset($pelanggans) ? $pelanggans->total() : 0 }}</h5>
                            <small class="opacity-75">Total Data</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error') || $errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') ?: $errors->first('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filter Pencarian
                        </h6>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="showBasicSearch()">
                                <i class="fas fa-search me-1"></i>Basic
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="showAdvancedSearch()">
                                <i class="fas fa-sliders-h me-1"></i>Advanced
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Basic Search Form -->
                    <div id="basicSearchForm">
                        <form method="GET" action="{{ route('customer.search') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="filter_field" class="form-label fw-semibold">Filter Field</label>
                                    <select class="form-select" id="filter_field" name="filter_field">
                                        <option value="">Semua Field</option>
                                        <option value="id_pelanggan" {{ request('filter_field') == 'id_pelanggan' ? 'selected' : '' }}>ID Pelanggan</option>
                                        <option value="nama_pelanggan" {{ request('filter_field') == 'nama_pelanggan' ? 'selected' : '' }}>Nama Pelanggan</option>
                                        <option value="bandwidth" {{ request('filter_field') == 'bandwidth' ? 'selected' : '' }}>Bandwidth</option>
                                        <option value="alamat" {{ request('filter_field') == 'alamat' ? 'selected' : '' }}>Alamat</option>
                                        <option value="provinsi" {{ request('filter_field') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                                        <option value="kabupaten" {{ request('filter_field') == 'kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                                        <option value="nomor_telepon" {{ request('filter_field') == 'nomor_telepon' ? 'selected' : '' }}>Nomor Telepon</option>
                                        <option value="cluster" {{ request('filter_field') == 'cluster' ? 'selected' : '' }}>Cluster</option>
                                        <option value="kode_fat" {{ request('filter_field') == 'kode_fat' ? 'selected' : '' }}>Kode FAT</option>
                                        <option value="latitude" {{ request('filter_field') == 'latitude' ? 'selected' : '' }}>Latitude</option>
                                        <option value="longitude" {{ request('filter_field') == 'longitude' ? 'selected' : '' }}>Longitude</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="filter_query" class="form-label fw-semibold">Kata Kunci</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="filter_query" name="filter_query" 
                                               placeholder="Masukkan kata kunci pencarian..." 
                                               value="{{ $filterQuery ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <div class="btn-group w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Cari
                                        </button>
                                        <a href="{{ route('customer.search') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Advanced Search Form (Hidden by default) -->
                    <div id="advancedSearchForm" style="display: none;">
                        <form method="GET" action="{{ route('customer.search.advanced') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Cluster</label>
                                    <select class="form-select" name="cluster_filter">
                                        <option value="">Semua Cluster</option>
                                        @if(isset($pelanggans))
                                            @foreach($pelanggans->pluck('cluster')->unique()->sort() as $cluster)
                                                <option value="{{ $cluster }}">{{ $cluster }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Provinsi</label>
                                    <select class="form-select" name="provinsi_filter">
                                        <option value="">Semua Provinsi</option>
                                        @if(isset($pelanggans))
                                            @foreach($pelanggans->pluck('provinsi')->unique()->sort() as $provinsi)
                                                <option value="{{ $provinsi }}">{{ $provinsi }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Kabupaten</label>
                                    <select class="form-select" name="kabupaten_filter">
                                        <option value="">Semua Kabupaten</option>
                                        @if(isset($pelanggans))
                                            @foreach($pelanggans->pluck('kabupaten')->unique()->sort() as $kabupaten)
                                                <option value="{{ $kabupaten }}">{{ $kabupaten }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Range Bandwidth</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control" name="bandwidth_min" placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control" name="bandwidth_max" placeholder="Max">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tanggal Registrasi</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="form-control" name="date_from">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control" name="date_to">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Kode FAT</label>
                                    <select class="form-select" name="has_fat_code">
                                        <option value="">Semua</option>
                                        <option value="yes">Ada FAT</option>
                                        <option value="no">Tidak Ada FAT</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Koordinat</label>
                                    <select class="form-select" name="has_coordinates">
                                        <option value="">Semua</option>
                                        <option value="yes">Ada Koordinat</option>
                                        <option value="no">Tidak Ada Koordinat</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-filter me-1"></i>Advanced Search
                                        </button>
                                        <a href="{{ route('customer.search') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    @if(isset($pelanggans) && $pelanggans->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('customer.map') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-map-marked-alt me-1"></i>Lihat di Peta
                            </a>
                        </div>
                        <small class="text-muted">
                            Menampilkan {{ $pelanggans->firstItem() }}-{{ $pelanggans->lastItem() }} dari {{ $pelanggans->total() }} data
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Results Table -->
    @if(isset($pelanggans) && $pelanggans->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-table me-2"></i>Hasil Pencarian
                        <span class="badge bg-primary ms-2">{{ $pelanggans->total() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">ID Pelanggan</th>
                                    <th width="18%">Nama</th>
                                    <th width="8%">Bandwidth</th>
                                    <th width="20%">Alamat</th>
                                    <th width="12%">Telepon</th>
                                    <th width="10%">Cluster</th>
                                    <th width="10%">Kode FAT</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelanggans as $index => $pelanggan)
                                <tr>
                                    <td>{{ $pelanggans->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $pelanggan->id_pelanggan }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $pelanggan->nama_pelanggan }}</div>
                                                <small class="text-muted">{{ $pelanggan->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $pelanggan->bandwidth }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($pelanggan->alamat, 40) }}</small>
                                        @if($pelanggan->provinsi)
                                            <br><small class="text-info">{{ $pelanggan->provinsi }}, {{ $pelanggan->kabupaten }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $pelanggan->nomor_telepon }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $pelanggan->cluster }}</span>
                                    </td>
                                    <td>
                                        @if($pelanggan->kode_fat)
                                            <span class="badge bg-success">{{ $pelanggan->kode_fat }}</span>
                                        @else
                                            <span class="badge bg-warning">Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="editPelanggan({{ $pelanggan->id }})" 
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deletePelanggan({{ $pelanggan->id }}, '{{ $pelanggan->nama_pelanggan }}')" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @if($pelanggan->latitude && $pelanggan->longitude)
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="viewLocation({{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}, '{{ $pelanggan->nama_pelanggan }}')" 
                                                    title="Lihat Lokasi">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    {{ $pelanggans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    @if(request()->has('filter_query') || request()->has('filter_field'))
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                        <p class="text-muted">Coba ubah kata kunci pencarian atau filter yang digunakan</p>
                        <a href="{{ route('customer.search') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-1"></i>Cari Lagi
                        </a>
                    @else
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h5>Pencarian Pelanggan & Kode FAT</h5>
                        <p class="text-muted">Gunakan form pencarian di atas untuk mencari data pelanggan</p>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" onclick="showAllData()">
                                <i class="fas fa-list me-1"></i>Lihat Semua Data
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="showAdvancedSearch()">
                                <i class="fas fa-sliders-h me-1"></i>Advanced Search
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editModalBody">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showBasicSearch() {
    document.getElementById('basicSearchForm').style.display = 'block';
    document.getElementById('advancedSearchForm').style.display = 'none';
}

function showAdvancedSearch() {
    document.getElementById('basicSearchForm').style.display = 'none';
    document.getElementById('advancedSearchForm').style.display = 'block';
}

function editPelanggan(id) {
    // Load edit form via AJAX
    fetch(`{{ url('/customer') }}/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editModalBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(error => {
            alert('Gagal memuat form edit');
            console.error('Error:', error);
        });
}

function deletePelanggan(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus data pelanggan "${nama}"?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/customer') }}/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function viewLocation(lat, lng, nama) {
    // Open Google Maps in new tab
    const url = `https://www.google.com/maps?q=${lat},${lng}`;
    window.open(url, '_blank');
}

function showAllData() {
    // Submit form tanpa filter untuk menampilkan semua data
    const form = document.querySelector('#basicSearchForm form');
    const filterField = form.querySelector('[name="filter_field"]');
    const filterQuery = form.querySelector('[name="filter_query"]');
    
    filterField.value = '';
    filterQuery.value = '';
    
    form.submit();
}

// Auto focus search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('filter_query');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endpush

@push('styles')
<style>
.avatar-sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.8rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.card {
    transition: all 0.3s ease;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush
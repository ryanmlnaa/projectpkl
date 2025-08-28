<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            padding: 2rem 0;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem;
            border: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background-color: white;
            transform: translateY(-1px);
        }
        
        .form-control:hover {
            border-color: #ced4da;
            background-color: white;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }
        
        .text-danger {
            font-weight: 500;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            background: #5a6268;
        }
        
        .btn:disabled {
            transform: none !important;
        }
        
        .input-group {
            position: relative;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }
        
        .readonly-section {
            background: #f1f3f4 !important;
            border-left: 4px solid #6c757d !important;
        }
        
        .readonly-section .form-control[readonly] {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
        }
        
        .readonly-section .form-label {
            color: #6c757d !important;
        }
        
        .readonly-section .section-title {
            color: #6c757d !important;
        }
        
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }
        
        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }
        
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .coordinate-helper {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .form-control.is-valid {
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 2.94-2.94.94.94L3.23 7.67z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.5 5.5 1 1m0 0 1 1m-1-1 1-1m-1 1-1 1'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
            <div>Menyimpan perubahan...</div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card fade-in">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Data Customer
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="editForm" action="{{ route('customer.update', $pelanggan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <!-- Hidden fields untuk filter parameters -->
                            <input type="hidden" name="filter_field" value="{{ request('filter_field') }}">
                            <input type="hidden" name="filter_query" value="{{ request('filter_query') }}">
                            
                            <!-- Informasi yang Dapat Diedit -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-edit me-2"></i>
                                    Informasi yang Dapat Diedit
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="id_pelanggan" class="form-label required-field">ID Pelanggan</label>
                                        <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan" 
                                               value="{{ old('id_pelanggan', $pelanggan->id_pelanggan ?? '') }}" required>
                                        @error('id_pelanggan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nama_pelanggan" class="form-label required-field">Nama Pelanggan</label>
                                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" 
                                               value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan ?? '') }}" required>
                                        @error('nama_pelanggan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="bandwidth" class="form-label required-field">Bandwidth</label>
                                        <select class="form-control" id="bandwidth" name="bandwidth" required>
                                            <option value="">Pilih Bandwidth</option>
                                            <option value="10 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '10 Mbps' ? 'selected' : '' }}>10 Mbps</option>
                                            <option value="15 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '15 Mbps' ? 'selected' : '' }}>15 Mbps</option>
                                            <option value="20 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '20 Mbps' ? 'selected' : '' }}>20 Mbps</option>
                                            <option value="25 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '25 Mbps' ? 'selected' : '' }}>25 Mbps</option>
                                            <option value="30 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '30 Mbps' ? 'selected' : '' }}>30 Mbps</option>
                                            <option value="35 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '35 Mbps' ? 'selected' : '' }}>35 Mbps</option>
                                            <option value="40 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '40 Mbps' ? 'selected' : '' }}>40 Mbps</option>
                                            <option value="45 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '45 Mbps' ? 'selected' : '' }}>45 Mbps</option>
                                            <option value="50 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '50 Mbps' ? 'selected' : '' }}>50 Mbps</option>
                                            <option value="75 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '75 Mbps' ? 'selected' : '' }}>75 Mbps</option>
                                            <option value="100 Mbps" {{ old('bandwidth', $pelanggan->bandwidth ?? '') == '100 Mbps' ? 'selected' : '' }}>100 Mbps</option>
                                        </select>
                                        @error('bandwidth')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor_telepon" class="form-label required-field">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" 
                                               value="{{ old('nomor_telepon', $pelanggan->nomor_telepon ?? '') }}" required>
                                        @error('nomor_telepon')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="alamat" class="form-label required-field">Alamat Lengkap</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Tetap (Read-only) -->
                            <div class="form-section readonly-section">
                                <div class="section-title">
                                    <i class="fas fa-lock me-2"></i>
                                    Informasi Tetap (Tidak dapat diubah)
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="provinsi" class="form-label">Provinsi</label>
                                        <input type="text" class="form-control" id="provinsi_display" 
                                               value="{{ $pelanggan->provinsi ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="provinsi" value="{{ $pelanggan->provinsi ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kabupaten" class="form-label">Kabupaten</label>
                                        <input type="text" class="form-control" id="kabupaten_display" 
                                               value="{{ $pelanggan->kabupaten ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="kabupaten" value="{{ $pelanggan->kabupaten ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Teknis (Read-only) -->
                            <div class="form-section readonly-section">
                                <div class="section-title">
                                    <i class="fas fa-network-wired me-2"></i>
                                    Informasi Teknis (Tidak dapat diubah)
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="cluster" class="form-label">Cluster</label>
                                        <input type="text" class="form-control" id="cluster_display" 
                                               value="{{ $pelanggan->cluster ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="cluster" value="{{ $pelanggan->cluster ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kode_fat" class="form-label">Kode FAT</label>
                                        <input type="text" class="form-control" id="kode_fat_display" 
                                               value="{{ $pelanggan->kode_fat ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="kode_fat" value="{{ $pelanggan->kode_fat ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Koordinat Lokasi (Read-only) -->
                            <div class="form-section readonly-section">
                                <div class="section-title">
                                    <i class="fas fa-map me-2"></i>
                                    Koordinat Lokasi (Tidak dapat diubah)
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control" id="latitude_display" 
                                               value="{{ $pelanggan->latitude ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="latitude" value="{{ $pelanggan->latitude ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control" id="longitude_display" 
                                               value="{{ $pelanggan->longitude ?? 'Belum diisi' }}" readonly>
                                        <input type="hidden" name="longitude" value="{{ $pelanggan->longitude ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="coordinate-helper">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Info:</strong> Koordinat lokasi sudah ditetapkan dan tidak dapat diubah.
                                                Jika perlu perubahan koordinat, hubungi administrator sistem.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" onclick="goBackToSearch()">
                                    <i class="fas fa-times me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to go back to search page
        function goBackToSearch() {
            const filterField = document.querySelector('input[name="filter_field"]').value;
            const filterQuery = document.querySelector('input[name="filter_query"]').value;
            
            let searchUrl = '/report/customer/search';
            if (filterField || filterQuery) {
                const params = new URLSearchParams();
                if (filterField) params.append('filter_field', filterField);
                if (filterQuery) params.append('filter_query', filterQuery);
                searchUrl += '?' + params.toString();
            }
            
            window.location.href = searchUrl;
        }
        
        // Form validation and submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const originalText = submitBtn.innerHTML;
            
            // Clear previous validation
            form.querySelectorAll('.form-control').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback && !feedback.textContent.includes('sudah digunakan')) {
                    feedback.remove();
                }
            });
            
            // Basic validation
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]:not([readonly])');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    let feedback = field.parentNode.querySelector('.invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback d-block';
                        field.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = 'Field ini wajib diisi';
                    isValid = false;
                } else {
                    field.classList.add('is-valid');
                }
            });
            
            // Validate phone number
            const phone = document.getElementById('nomor_telepon');
            const phoneRegex = /^[0-9+\-\s\(\)]+$/;
            if (phone.value && !phoneRegex.test(phone.value)) {
                phone.classList.add('is-invalid');
                let feedback = phone.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    phone.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'Format nomor telepon tidak valid';
                isValid = false;
            }
            
            // Validate bandwidth selection
            const bandwidth = document.getElementById('bandwidth');
            if (!bandwidth.value) {
                bandwidth.classList.add('is-invalid');
                let feedback = bandwidth.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    bandwidth.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'Pilih paket bandwidth';
                isValid = false;
            }
            
            if (!isValid) {
                // Smooth scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }
            
            // Show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            loadingOverlay.style.display = 'flex';
            
            // Submit form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || 'Data customer berhasil diperbarui!');
                    
                    // Close modal if exists
                    const modal = document.getElementById('editModal');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    }
                    
                    // Redirect using URL from server response
                    setTimeout(() => {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // Fallback redirect
                            goBackToSearch();
                        }
                    }, 1500);
                } else if (data.errors) {
                    // Handle validation errors
                    Object.keys(data.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            let feedback = input.parentNode.querySelector('.invalid-feedback');
                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                input.parentNode.appendChild(feedback);
                            }
                            feedback.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Terjadi kesalahan: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                loadingOverlay.style.display = 'none';
            });
        });
        
        // Show success message
        function showSuccessMessage(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 5000);
        }
        
        // Show error message
        function showErrorMessage(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 5000);
        }
        
        // Real-time input formatting
        document.getElementById('nomor_telepon').addEventListener('input', function(e) {
            // Auto format phone number (basic)
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('62')) {
                value = '+' + value;
            } else if (value.startsWith('08')) {
                value = '+62' + value.substring(1);
            }
            // Don't update if user is manually editing
            if (e.target.value.length > value.length) return;
        });
        
        // Add smooth animations on focus for editable fields only
        document.querySelectorAll('.form-control:not([readonly])').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
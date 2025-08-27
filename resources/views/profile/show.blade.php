@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col">
                    <div class="card shadow h-100">
                        <div class="card-header bg-transparent">
                            <h3 class="text-primary mb-0">
                                <i class="ni ni-single-02 text-primary"></i>
                                Profile Settings
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Profile Form Section - Full Width -->
                                <div class="col-lg-12">
                                    <div class="card shadow-lg border-0">
                                        <div class="card-header bg-white border-0">
                                            <h3 class="mb-0">Edit Profile</h3>
                                            <p class="text-sm mb-0">Update informasi profile Anda</p>
                                        </div>
                                        <div class="card-body">
                                            <!-- Alert Messages -->
                                            <div id="alertContainer">
                                                @if(session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon"><i class="ni ni-check-bold"></i></span>
                                                        <span class="alert-text">{{ session('success') }}</span>
                                                        <button type="button" class="close" data-dismiss="alert">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif

                                                @if(session('error'))
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon"><i class="ni ni-support-16"></i></span>
                                                        <span class="alert-text">{{ session('error') }}</span>
                                                        <button type="button" class="close" data-dismiss="alert">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif

                                                @if ($errors->any())
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon"><i class="ni ni-support-16"></i></span>
                                                        <div class="alert-text">
                                                            <ul class="mb-0">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        <button type="button" class="close" data-dismiss="alert">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Form Edit Profile -->
                                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
    
                                                    <!-- Upload Photo Section -->
                                                    <div class="form-group">
                                                        <label class="form-control-label">Foto Profile</label>
                                                        <div class="d-flex align-items-center">
                                                            <!-- Preview foto saat ini -->
                                                            <div class="avatar-preview mr-3">
                                                                @if(Auth::user()->profile_photo_path && file_exists(storage_path('app/public/' . Auth::user()->profile_photo_path)))
                                                                    <img id="photoPreview" 
                                                                        src="{{ Storage::url(Auth::user()->profile_photo_path) }}" 
                                                                        class="rounded-circle" 
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                @else
                                                                    <div id="photoPreview" 
                                                                        class="rounded-circle d-flex align-items-center justify-content-center" 
                                                                        style="width: 80px; height: 80px; background: #f8f9fa; border: 2px dashed #dee2e6;">
                                                                        <i class="fas fa-user text-muted" style="font-size: 30px;"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Input file -->
                                                            <div class="flex-grow-1">
                                                                <input type="file" 
                                                                    class="form-control @error('profile_photo') is-invalid @enderror" 
                                                                    id="profile_photo" 
                                                                    name="profile_photo" 
                                                                    accept="image/*"
                                                                    onchange="previewImage(this)">
                                                                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                                                @error('profile_photo')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Nama Lengkap -->
                                                    <div class="form-group">
                                                        <label class="form-control-label">Nama Lengkap</label>
                                                        <input type="text" 
                                                            class="form-control @error('name') is-invalid @enderror" 
                                                            name="name" 
                                                            value="{{ old('name', Auth::user()->name) }}" 
                                                            required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <!-- Email -->
                                                    <div class="form-group">
                                                        <label class="form-control-label">Email</label>
                                                        <input type="email" 
                                                            class="form-control @error('email') is-invalid @enderror" 
                                                            name="email" 
                                                            value="{{ old('email', Auth::user()->email) }}" 
                                                            required>
                                                        <small class="text-muted">Email tidak dapat diubah</small>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <!-- Submit Button -->
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>

                                                <script>
                                                function previewImage(input) {
                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();
                                                        
                                                        reader.onload = function(e) {
                                                            const preview = document.getElementById('photoPreview');
                                                            preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">`;
                                                        }
                                                        
                                                        reader.readAsDataURL(input.files[0]);
                                                    }
                                                }
                                                </script>
                                            <!-- Change Password Section -->
                                            <hr class="my-5">
                                            <div class="row">
                                                <div class="col">
                                                    <h4 class="mb-3">
                                                        <i class="ni ni-lock-circle-open text-primary"></i>
                                                        Ubah Password
                                                    </h4>
                                                    <p class="text-muted">Klik tombol di bawah untuk mengubah password Anda</p>
                                                    <a href="{{ route('profile.change.password') }}" class="btn btn-outline-warning">
                                                        <i class="ni ni-key-25"></i>
                                                        Ubah Password
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    margin-bottom: 1rem;
}

.form-control-alternative:focus {
    box-shadow: 0 1px 3px rgba(50, 50, 93, 0.15), 0 1px 0 rgba(0, 0, 0, 0.02);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.avatar-preview img {
    transition: transform 0.3s ease;
}

.avatar-preview img:hover {
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show alert function
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <span class="alert-icon">
                    <i class="ni ni-${type === 'success' ? 'check-bold' : type === 'danger' ? 'support-16' : 'bell-55'}"></i>
                </span>
                <span class="alert-text">${message}</span>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        alertContainer.innerHTML = alertHTML;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                $(alert).alert('close');
            }
        }, 5000);
    }
});
</script>
@endsection
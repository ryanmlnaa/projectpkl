{{-- resources/views/report/customer/edit-form.blade.php --}}
<form method="POST" action="{{ route('customer.update', $pelanggan->id) }}" id="editForm">
    @csrf
    @method('PUT')
    
    <div class="row g-3">
        <div class="col-md-6">
            <label for="edit_id_pelanggan" class="form-label">ID Pelanggan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_id_pelanggan" name="id_pelanggan" 
                   value="{{ old('id_pelanggan', $pelanggan->id_pelanggan) }}" required>
            @error('id_pelanggan')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_nama_pelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nama_pelanggan" name="nama_pelanggan" 
                   value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
            @error('nama_pelanggan')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_bandwidth" class="form-label">Bandwidth <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_bandwidth" name="bandwidth" 
                   value="{{ old('bandwidth', $pelanggan->bandwidth) }}" required>
            @error('bandwidth')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nomor_telepon" name="nomor_telepon" 
                   value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}" required>
            @error('nomor_telepon')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-12">
            <label for="edit_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
            <textarea class="form-control" id="edit_alamat" name="alamat" rows="2" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
            @error('alamat')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_provinsi" name="provinsi" 
                   value="{{ old('provinsi', $pelanggan->provinsi) }}" required>
            @error('provinsi')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_kabupaten" class="form-label">Kabupaten <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_kabupaten" name="kabupaten" 
                   value="{{ old('kabupaten', $pelanggan->kabupaten) }}" required>
            @error('kabupaten')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4">
            <label for="edit_cluster" class="form-label">Cluster <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_cluster" name="cluster" 
                   value="{{ old('cluster', $pelanggan->cluster) }}" required>
            @error('cluster')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4">
            <label for="edit_kode_fat" class="form-label">Kode FAT</label>
            <input type="text" class="form-control" id="edit_kode_fat" name="kode_fat" 
                   value="{{ old('kode_fat', $pelanggan->kode_fat) }}">
            @error('kode_fat')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4"></div>
        
        <div class="col-md-6">
            <label for="edit_latitude" class="form-label">Latitude</label>
            <input type="number" step="0.0000001" class="form-control" id="edit_latitude" name="latitude" 
                   value="{{ old('latitude', $pelanggan->latitude) }}">
            <small class="text-muted">Contoh: -8.6705</small>
            @error('latitude')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6">
            <label for="edit_longitude" class="form-label">Longitude</label>
            <input type="number" step="0.0000001" class="form-control" id="edit_longitude" name="longitude" 
                   value="{{ old('longitude', $pelanggan->longitude) }}">
            <small class="text-muted">Contoh: 115.2126</small>
            @error('longitude')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Simpan Perubahan
        </button>
    </div>
</form>

<script>
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable submit button dan show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (response.ok) {
            // Close modal and reload page
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            window.location.reload();
        } else {
            throw new Error('Response not ok');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat menyimpan data');
        console.error('Error:', error);
    })
    .finally(() => {
        // Restore submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
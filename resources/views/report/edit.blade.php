<!DOCTYPE html>
<html>
<head>
    <title>Edit Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Edit Report</h2>

    <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Sales</label>
            <input type="text" name="sales" class="form-control" value="{{ $report->sales }}" required>
        </div>
        <div class="mb-3">
            <label>Aktivitas</label>
            <input type="text" name="aktivitas" class="form-control" value="{{ $report->aktivitas }}" required>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $report->tanggal }}" required>
        </div>
        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $report->lokasi }}" required>
        </div>
        <div class="mb-3">
            <label>Evidence</label><br>
            @if($report->evidence)
                <img src="{{ asset('storage/'.$report->evidence) }}" width="100" class="mb-2"><br>
            @endif
            <input type="file" name="evidence" class="form-control">
        </div>
        <div class="mb-3">
            <label>Hasil / Kendala</label>
            <textarea name="hasil_kendala" class="form-control">{{ $report->hasil_kendala }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>Proses</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('reports.activity') }}" class="btn btn-secondary">Kembali</a>
    </form>

</body>
</html>

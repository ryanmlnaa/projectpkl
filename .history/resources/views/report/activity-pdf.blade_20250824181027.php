<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Activity</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .status-selesai {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        .status-proses {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AKTIVITAS SALES</h1>
        <p>Periode: {{ date('d F Y') }}</p>
        <p>Total Data: {{ count($reports) }} aktivitas</p>
    </div>

    @if(count($reports) > 0)
   <table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Sales</th>
            <th width="15%">Aktivitas</th>
            <th width="10%">Tanggal</th>
            <th width="12%">Lokasi</th>
            <th width="16%">Evidence</th>
            <th width="20%">Hasil / Kendala</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $i => $report)
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $report->sales }}</td>
            <td>{{ $report->aktivitas }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $report->lokasi }}</td>

            <!-- Tambahkan Evidence -->
            <td class="text-center">
                @if($report->evidence && file_exists(public_path('storage/evidence/'.$report->evidence)))
                    <img src="{{ public_path('storage/'.$report->evidence) }}" width="100">
                        alt="evidence" width="80" style="border-radius:4px;">
                @else
                    (Tidak ada)
                @endif
            </td>


            <td>{{ $report->hasil_kendala ?? '-' }}</td>
            <td class="text-center">
                @if($report->status == 'selesai')
                    <span class="status-selesai">Selesai</span>
                @else
                    <span class="status-proses">Proses</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    @else
    <div class="no-data">
        <p>Tidak ada data report activity yang tersedia.</p>
    </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Report Activity System</p>
    </div>
</body>
</html>

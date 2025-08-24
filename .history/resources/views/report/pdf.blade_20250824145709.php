<!DOCTYPE html>
<html>
<head>
    <title>Report Activity</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            font-size: 10px;
        }
        th, td { 
            border: 1px solid #333; 
            padding: 6px; 
            text-align: left; 
            vertical-align: middle;
        }
        th { 
            background: #f0f0f0; 
            font-weight: bold;
            text-align: center;
        }
        .evidence-cell {
            text-align: center;
            width: 80px;
        }
        .evidence-img {
            max-width: 50px;
            max-height: 50px;
            border: 1px solid #ccc;
        }
        .no-image {
            color: #999;
            font-style: italic;
            font-size: 9px;
        }
        .status-selesai {
            background: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-proses {
            background: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
        }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">PT ICONET</div>
        <div class="subtitle">Internet Service Provider</div>
        <div class="title">LAPORAN AKTIVITAS SALES</div>
        <div style="font-size: 10px; color: #888;">Dicetak: {{ date('d F Y, H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="13%">SALES</th>
                <th width="18%">AKTIVITAS</th>
                <th width="10%">TANGGAL</th>
                <th width="13%">LOKASI</th>
                <th width="12%">EVIDENCE</th>
                <th width="20%">HASIL/KENDALA</th>
                <th width="9%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $i => $report)
                <tr>
                    <td class="center">{{ $i+1 }}</td>
                    <td>{{ $report->sales }}</td>
                    <td>{{ $report->aktivitas }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $report->lokasi }}</td>
                    <td class="evidence-cell">
                        @if(isset($report->image_data) && $report->image_data)
                            <img src="{{ $report->image_data }}" class="evidence-img">
                        @elseif(isset($report->evidence_base64) && $report->evidence_base64)
                            <img src="{{ $report->evidence_base64 }}" class="evidence-img">
                        @else
                            <span class="no-image">No Image</span>
                        @endif
                    </td>
                    <td style="font-size: 9px;">{{ $report->hasil_kendala ?: '-' }}</td>
                    <td class="center">
                        <span class="status-{{ $report->status }}">
                            {{ strtoupper($report->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center" style="padding: 20px; color: #999;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: center; font-size: 9px; color: #666;">
        <p>PT ICONET - Laporan Activity Sales</p>
        <p>Generated: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
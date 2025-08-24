<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Activity - Export PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #2c5aa0;
            font-size: 24px;
        }

        .header .subtitle {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #2c5aa0;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stats div {
            text-align: center;
            flex: 1;
        }

        .stats .number {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
        }

        .stats .label {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #2c5aa0;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status-selesai {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            text-align: center;
        }

        .status-proses {
            background-color: #ffc107;
            color: #000;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            text-align: center;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .evidence-img {
            max-width: 50px;
            max-height: 50px;
            border-radius: 4px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>PT PLN AKPOL</h1>
        <div class="subtitle">Report Activity Summary</div>
        <div class="subtitle">Generated on: {{ date('d F Y H:i', strtotime($date ?? now())) }}</div>
    </div>

    <!-- Summary Stats -->
    @php
        $totalReports = $reports->count();
        $selesai = $reports->where('status', 'selesai')->count();
        $proses = $reports->where('status', 'proses')->count();
    @endphp

    <div class="info-box">
        <div class="stats">
            <div>
                <div class="number">{{ $totalReports }}</div>
                <div class="label">Total Reports</div>
            </div>
            <div>
                <div class="number">{{ $selesai }}</div>
                <div class="label">Completed</div>
            </div>
            <div>
                <div class="number">{{ $proses }}</div>
                <div class="label">In Progress</div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    @if($reports->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Sales</th>
                    <th width="20%">Activity</th>
                    <th width="10%">Date</th>
                    <th width="12%">Location</th>
                    <th width="8%">Evidence</th>
                    <th width="23%">Result/Issues</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $index => $report)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $report->sales }}</td>
                        <td>{{ $report->aktivitas }}</td>
                        <td style="text-align: center;">
                            {{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}
                        </td>
                        <td>{{ $report->lokasi }}</td>
                        <td style="text-align: center;">
                            @if($report->evidence)
                                <span style="color: #28a745; font-weight: bold;">✓</span>
                            @else
                                <span style="color: #dc3545;">✗</span>
                            @endif
                        </td>
                        <td>
                            @if($report->hasil_kendala)
                                {{ Str::limit($report->hasil_kendala, 100) }}
                            @else
                                <em style="color: #666;">No notes</em>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="status-{{ $report->status }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>No Data Available</h3>
            <p>There are no activity reports to display at this time.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by PLN AKPOL Activity Management System</p>
        <p>Generated on {{ now()->format('d F Y \a\t H:i:s') }} | Total Records: {{ $reports->count() }}</p>
    </div>
</body>
</html>

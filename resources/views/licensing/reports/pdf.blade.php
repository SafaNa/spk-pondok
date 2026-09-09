<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; padding: 24px 32px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e3a5f; padding-bottom: 12px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #1e3a5f; }
        .header p { font-size: 10px; color: #64748b; margin-top: 4px; }
        .stats { display: flex; gap: 12px; margin-bottom: 16px; }
        .stat-card { flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; text-align: center; }
        .stat-card .num { font-size: 20px; font-weight: bold; color: #1e3a5f; }
        .stat-card .label { font-size: 9px; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background-color: #1e3a5f; }
        thead th { color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 20px; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportTitle }}</h1>
        <p>Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Santri</th>
                <th>Rayon / Kamar</th>
                <th>Kategori / Alasan</th>
                <th>Tgl Mulai</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($licenses as $i => $license)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $license->student->name ?? '-' }}</td>
                    <td>{{ $license->student->rayon->name ?? '-' }} / {{ $license->student->room->name ?? '-' }}</td>
                    <td>
                        {{ $license->leaveCategory->name ?? '-' }}
                        @if($license->leaveReason)
                            <br><span style="color:#64748b">{{ $license->leaveReason->reason }}</span>
                        @endif
                    </td>
                    <td>{{ $license->start_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $license->end_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $license->actual_return_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if($license->actual_return_date)
                            @if($license->is_late)
                                <span class="badge badge-red">Telat {{ $license->late_days }}h</span>
                            @else
                                <span class="badge badge-green">Tepat Waktu</span>
                            @endif
                        @else
                            @if(now()->startOfDay()->gt($license->end_date))
                                <span class="badge badge-red">Blm Kembali</span>
                            @else
                                <span class="badge badge-amber">Sedang Izin</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $licenses->count() }} data perizinan</div>
</body>
</html>

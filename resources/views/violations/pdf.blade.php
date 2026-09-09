<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; padding: 20px 28px; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; }
        .header h1 { font-size: 15px; font-weight: bold; color: #1e3a5f; }
        .header p { font-size: 9px; color: #64748b; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #1e3a5f; }
        thead th { color: #fff; padding: 6px 7px; text-align: left; font-size: 9px; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        tbody td { padding: 5px 7px; border-bottom: 1px solid #e2e8f0; font-size: 9px; vertical-align: top; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; }
        .badge-ringan { background: #fef9c3; color: #854d0e; }
        .badge-sedang { background: #ffedd5; color: #9a3412; }
        .badge-berat  { background: #fee2e2; color: #991b1b; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 14px; font-size: 8px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th>Nama Santri</th>
                <th>Kamar</th>
                <th>Bidang</th>
                <th>Kategori</th>
                <th>Jenis Pelanggaran</th>
                <th>Sanksi</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $r)
                @php
                    $cat      = $r->violationType->category->name ?? '-';
                    $catClass = match($cat) { 'Ringan' => 'badge-ringan', 'Sedang' => 'badge-sedang', 'Berat' => 'badge-berat', default => '' };
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $r->student->name ?? '-' }}</strong></td>
                    <td>{{ $r->student->room->name ?? '-' }}</td>
                    <td>{{ $r->violationType->department->acronym ?? '-' }}</td>
                    <td><span class="badge {{ $catClass }}">{{ $cat }}</span></td>
                    <td>{{ $r->violationType->name ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($r->sanction ?? '-', 60) }}</td>
                    <td>{{ $r->date?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if($r->sanction_status === 'completed')
                            <span class="badge badge-green">Selesai</span>
                        @else
                            <span class="badge badge-red">Belum</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;padding:16px;color:#94a3b8;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $records->count() }} catatan pelanggaran</div>
</body>
</html>

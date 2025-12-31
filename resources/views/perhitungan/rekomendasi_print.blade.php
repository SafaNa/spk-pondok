<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekomendasi Santri - SPK P2AL II</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 80px;
            display: block;
            margin: 0 auto 10px;
        }

        .header h1 {
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 12pt;
        }

        .meta {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11pt;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .footer p {
            margin-bottom: 60px;
        }

        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('favicon.png') }}" alt="Logo">
        <h1>Pondok Pesantren Annuqayah Latee II</h1>
        <p>Laporan Rekomendasi Kepulangan Santri</p>
        <p><i>Metode SMART (Simple Multi Attribute Rating Technique)</i></p>
    </div>

    <div class="meta">
        <p><strong>Tanggal Cetak:</strong> {{ date('d F Y') }}</p>
        <p><strong>Total Data:</strong> {{ $santri->count() }} Santri</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">NIS</th>
                <th style="width: 35%">Nama Santri</th>
                <th style="width: 15%">Nilai Akhir</th>
                <th style="width: 25%">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santri as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->nilai_akhir, 2, ',', '.') }}</td>
                    <td>
                        @if($item->nilai_akhir >= 0.7)
                            Direkomendasikan
                        @elseif($item->nilai_akhir >= 0.4)
                            Pertimbangkan
                        @else
                            Tidak Direkomendasikan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sumenep, {{ date('d F Y') }}<br>Pengurus Pondok,</p>
        <br>
        <p><b>( _______________________ )</b></p>
    </div>
</body>

</html>
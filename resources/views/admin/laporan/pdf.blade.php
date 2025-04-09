<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }

        p {
            font-size: 14px;
            color: #555;
        }

        hr {
            border: 1px solid #ddd;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .total-jam {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>

    <header>
        <h1>Laporan Absensi</h1>
        <p>Periode:
            @if ($tanggalMulai && $tanggalSelesai)
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
            @else
                Seluruh Data Absensi
            @endif
        </p>
    </header>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @if ($kehadirans->isEmpty())
                <tr>
                    <td colspan="6">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @else
                @foreach ($kehadirans as $kehadiran)
                    <tr>
                        <td>{{ $kehadiran->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y') }}</td>
                        <td>
                            {{ $kehadiran->status === 'Sakit' ? '-' : ($kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i:s') : '-') }}
                        </td>
                        <td>
                            @if ($kehadiran->jam_keluar)
                                {{ \Carbon\Carbon::parse($kehadiran->jam_keluar)->format('H:i:s') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ ucfirst($kehadiran->status) }}</td>
                        <td>
                            {{ $kehadiran->status !== 'Sakit' ? $kehadiran->jam_kerja ?? '-' : '-' }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>

    <div class="footer">
        <p>Dibuat pada: {{ $waktuDibuat }}</p>
    </div>

</body>

</html>

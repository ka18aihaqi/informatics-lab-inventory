<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita Acara</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .container { width: 100%; }

        .header-table, .header-table td {
            border: none !important;
        }

        .main-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }

        .content { margin-top: 20px; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table" width="100%">
            <tr>
                <td width="150px">
                    <img src="{{ public_path('img/iflabs.png') }}" width="120px">
                </td>
                <td style="text-align: right; font-size: 12px;">
                    LABORATORIUM PRAKTIKUM INFORMATIKA<br>
                    Gedung F Lantai 3 IFLAB 1 s/d IFLAB 4<br>
                    Gedung TULT Lantai 6 & 7<br>
                    Fakultas Informatika<br>
                    Universitas Telkom<br>
                    Bandung
                </td>
            </tr>
        </table>

        <div class="main-title">BERITA ACARA PEMINDAHAN ASET IFLAB</div>

        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
        
            $sortedLogs = $transferLogs->sortBy('transferred_at');
        
            $firstTransfer = $sortedLogs->first();
            $lastTransfer = $sortedLogs->last();
        
            $startTime = $firstTransfer ? Carbon::parse($firstTransfer->transferred_at)->format('H:i') : '-';
            $endTime = $lastTransfer ? Carbon::parse($lastTransfer->transferred_at)->format('H:i') : '-';
        @endphp

        <div class="content">
            <p>
                @if($firstTransfer)
                    Pada Hari {{ Carbon::parse($firstTransfer->transferred_at)->translatedFormat('l') }} 
                    Tanggal {{ Carbon::parse($firstTransfer->transferred_at)->format('d') }} 
                    Bulan {{ Carbon::parse($firstTransfer->transferred_at)->translatedFormat('F') }} 
                    Tahun {{ Carbon::parse($firstTransfer->transferred_at)->format('Y') }} 
                    dari jam {{ $startTime }} sampai dengan jam {{ $endTime }} telah dilakukan pemindahan aset sebagai berikut:
                @else
                    Tidak ada data pemindahan aset pada periode ini.
                @endif
            </p>            

            @if($transferLogs->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Nama</th>
                            <th>Ruangan Asal</th>
                            <th>Ruangan Tujuan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Transfer</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transferLogs->sortBy('transferred_at') as $index => $transferLog)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transferLog->item_name ?? '-' }}</td>
                                <td>{{ $transferLog->fromLocation->name ?? '-' }}</td>
                                <td>{{ $transferLog->toLocation->name ?? '-' }}</td>
                                <td>{{ $transferLog->quantity }}</td>
                                <td>{{ \Carbon\Carbon::parse($transferLog->transferred_at)->translatedFormat('d F Y') }}</td>
                                <td>{{ $transferLog->note ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>

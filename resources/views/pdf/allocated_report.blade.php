<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Berita Acara</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .container { width: 100%; }
        .header-table, .header-table td { border: none !important; }
        .main-title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0; }
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

        <div class="main-title">PENDATAAN INVENTARIS SPESIFIKASI PC INFORMATICS LABORATORY</div>

        <div class="content">
            <p><strong>Location:</strong> {{ $location->name }}</p>
            <p>{{ $location->description }}</p>
        
            @if ($location->allocatedComputer->isNotEmpty() || $location->allocatedItem->isNotEmpty())
                <!-- Tabel PC -->
                @if ($location->allocatedComputer->isNotEmpty())
                    <h4>Allocated Computer</h4>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th>Computer Brand</th>
                                <th>Disk Drive 1</th>
                                <th>Disk Drive 2</th>
                                <th>Processor</th>
                                <th>VGA</th>
                                <th>RAM</th>
                                <th>Monitor</th>
                                <th>Year (Approx)</th>
                                <th>UPS Status</th>
                                <th>QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($location->allocatedComputer as $index => $pc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ optional($pc->computer)->item_name ?? '-' }}
                                        {{ optional($pc->computer)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->diskDrive1)->item_name ?? '-' }}
                                        {{ optional($pc->diskDrive1)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->diskDrive2)->item_name ?? '-' }}
                                        {{ optional($pc->diskDrive2)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->processor)->item_name ?? '-' }}
                                        {{ optional($pc->processor)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->vgaCard)->item_name ?? '-' }}
                                        {{ optional($pc->vgaCard)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->ram)->item_name ?? '-' }}
                                        {{ optional($pc->ram)->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional($pc->monitor)->item_name ?? '-' }}
                                        {{ optional($pc->monitor)->description ?? '-' }}
                                    </td>
                                    <td>{{ $pc->year_approx ?? '-' }}</td>
                                    <td>{{ $pc->ups_status ?? '-' }}</td>
                                    <td><img src="{{ public_path('storage/' . $pc->qr_code) }}" alt="QR Code" width="50" height="50">                            </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            
                @if ($location->allocatedItem->isNotEmpty())
                    <!-- Barang Tambahan -->
                    <h4>Allocated Items</h4>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($location->allocatedItem as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if (in_array($item->otherItem->item_type, ['disk_drive', 'processor', 'vga_card', 'ram', 'monitor']))
                                            {{ $item->otherItem->item_name }} {{ $item->otherItem->description }}
                                        @else
                                            {{ $item->otherItem->item_name }}
                                        @endif
                                    </td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td>{{ $item->stock ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                Tidak ada data pada lokasi ini.
            @endif
        </div>
        
    </div>
</body>
</html>

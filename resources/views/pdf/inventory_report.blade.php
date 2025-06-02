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

        <div class="main-title">PENDATAAN INVENTARIS INFORMATICS LABORATORY</div>

        <div class="content">
            <p><strong>{{ $title }}</strong></p>

            @if ($itemType == 'all_items')
                @foreach ($items as $category => $groupItems)
                    <h4>{{ $category }}</h4>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">No.</th>
                                @if ($category == 'Computers')
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Stock</th>
                                @elseif ($category == 'Disk Drives')
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Brand</th>
                                    <th>Stock</th>
                                @elseif ($category == 'Processors')
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Frequency</th>
                                    <th>Stock</th>
                                @elseif ($category == 'VGA Cards')
                                    <th>Brand</th>
                                    <th>Size</th>
                                    <th>Stock</th>
                                @elseif ($category == 'RAM')
                                    <th>Size</th>
                                    <th>Type</th>
                                    <th>Stock</th>
                                @elseif ($category == 'Monitor')
                                    <th>Brand</th>
                                    <th>Resolution</th>
                                    <th>Inch</th>
                                    <th>Stock</th>
                                @elseif ($category == 'Other Items')
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Stock</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupItems as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    @if ($category == 'Computers')
                                        @php
                                            [$model] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $item->item_name }}</td>                                                          
                                        <td>{{ $model }}</td>
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'Disk Drives')
                                        @php
                                            [$size, $brand] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $size ? $size . ' GB' : 'N/A' }}</td>                                                              
                                        <td>{{ $brand }}</td>
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'Processors')
                                        @php
                                            [$model, $frequency] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $model }}</td>
                                        <td>{{ $frequency }}</td>                                                              
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'VGA Cards')
                                        @php
                                            [$size] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $size }}</td>                                                              
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'RAM')
                                        @php
                                            [$size] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $size }}</td>                                                              
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'Monitor')
                                        @php
                                            [$resolution, $inch] = explode(' - ', $item->description);
                                        @endphp
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $resolution }}</td>                                                              
                                        <td>{{ $inch }}</td>                                                              
                                        <td>{{ $item->stock }}</td>
                                    @elseif ($category == 'Other Items')
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->stock }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            @if ($itemType === 'computer')
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'disk_drive')
                                <th>Type</th>
                                <th>Size</th>
                                <th>Brand</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'processor')
                                <th>Type</th>
                                <th>Model</th>
                                <th>Frequency</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'vga_card')
                                <th>Brand</th>
                                <th>Size</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'ram')
                                <th>Size</th>
                                <th>Type</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'monitor')
                                <th>Brand</th>
                                <th>Resolution</th>
                                <th>Inch</th>
                                <th>Stock</th>
                            @elseif ($itemType === 'other_item')
                                <th>Name</th>
                                <th>Description</th>
                                <th>Stock</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                @if ($itemType === 'computer')
                                    @php
                                        [$model] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $item->item_name }}</td>                                                          
                                    <td>{{ $model }}</td>
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'disk_drive')
                                    @php
                                        [$size, $brand] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $size }}</td>                                                              
                                    <td>{{ $brand }}</td>
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'processor')
                                    @php
                                        [$model, $frequency] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $model }}</td>
                                    <td>{{ $frequency }}</td>                                                              
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'vga_card')
                                    @php
                                        [$size] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $size }}</td>                                                              
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'ram')
                                    @php
                                        [$size] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $size }}</td>                                                              
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'monitor')
                                    @php
                                        [$resolution, $inch] = explode(' - ', $item->description);
                                    @endphp
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $resolution }}</td>                                                              
                                    <td>{{ $inch }}</td>                                                              
                                    <td>{{ $item->stock }}</td>
                                @elseif ($itemType === 'other_item')
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->stock }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</body>
</html>

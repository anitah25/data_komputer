<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }

        td {
            padding: 6px;
            word-wrap: break-word;
            max-width: 150px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .komputer-detail {
            width: 100%;
            margin-bottom: 20px;
        }

        .komputer-detail td,
        .komputer-detail th {
            padding: 5px;
        }

        .detail-label {
            width: 25%;
            font-weight: bold;
        }

        .detail-value {
            width: 75%;
        }

        .barcode-container {
            text-align: center;
            margin: 15px 0;
            width: 100%;
        }

        .barcode-image {
            max-width: 180px;
            max-height: 90px;
            margin: 0 auto;
            display: block;
        }

        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            margin: 10px 0;
            gap: 10px;
        }

        .gallery-item {
            width: 32%;
            margin-bottom: 10px;
            text-align: center;
            display: inline-block;
        }

        .gallery-image {
            width: 100%;
            height: 90px;
            object-fit: contain;
            border: 1px solid #ddd;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: center;
            color: #777;
        }

        /* Mengoptimalkan tampilan untuk landscape */
        @page {
            size: landscape;
            margin: 15mm 10mm 15mm 10mm;
            /* Top, Right, Bottom, Left */
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <div class="header">
        <div>
            <p>Tanggal Export: {{ now()->format('d F Y, H:i') }}</p>
        </div>
    </div>

    <!-- Komputer Details Section -->
    <h2>Detail Komputer</h2>
    <table class="komputer-detail">
        <tr>
            <td class="detail-label">Kode Barang</td>
            <td class="detail-value">{{ $komputer->kode_barang }}</td>
        </tr>
        <tr>
            <td class="detail-label">Nama Komputer</td>
            <td class="detail-value">{{ $komputer->nama_komputer }}</td>
        </tr>
        <tr>
            <td class="detail-label">Ruangan</td>
            <td class="detail-value">
                {{ $komputer->ruangan ? $komputer->ruangan->nama_ruangan : 'Tidak tersedia' }}
            </td>
        </tr>
        <tr>
            <td class="detail-label">Pengguna</td>
            <td class="detail-value">{{ $komputer->nama_pengguna_sekarang }}</td>
        </tr>
        <tr>
            <td class="detail-label">Spesifikasi</td>
            <td class="detail-value">
                Processor: {{ $komputer->spesifikasi_processor }},
                RAM: {{ $komputer->spesifikasi_ram }},
                Storage: {{ $komputer->spesifikasi_penyimpanan }}
            </td>
        </tr>
        <tr>
            <td class="detail-label">Kondisi</td>
            <td class="detail-value">{{ $komputer->kondisi_komputer }}</td>
        </tr>
    </table>


    <table class="komputer-detail">
        <tr>
            <td style="width: 30%; vertical-align: top; text-align: center;">
                <h2>Kode Barang</h2>
                @if($barcodeImage)
                    <div style="text-align: center; margin-bottom: 10px;">
                        <img src="{{ $barcodeImage }}" alt="Barcode" class="barcode-image">
                        <p>{{ basename($komputer->barcode) }}</p>
                    </div>
                @endif
            </td>
            <td style="width: 70%; vertical-align: top;">
                <table>
                    <tr>
                        <td>
                            <h2>Foto Komputer</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if(count($galleryImages) > 0)
                                <div style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 15px;">
                                    @foreach($galleryImages as $index => $image)
                                        @if($index < 6) {{-- Batasi maksimal 6 gambar --}}
                                            <div
                                                style="display: inline-block; margin-bottom: 10px; text-align: center;">
                                                <img src="{{ $image }}" alt="Foto Komputer" class="gallery-image">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p>Tidak ada foto</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Riwayat Perbaikan Section -->
    <h2>Riwayat Pemeliharaan</h2>
    <table>
        <thead>
            <tr>
                @foreach($headerRow as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini diekspor dari Sistem Informasi Komputer ESDM pada {{ now()->format('d F Y, H:i:s') }}</p>
    </div>
</body>

</html>
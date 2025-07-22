@extends('admin.components.layout')

@section('content')

    <style>
        .detail-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .spec-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 15px;
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }

        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .spec-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            padding-left: 15px;
        }

        .timeline-item:before {
            content: "";
            position: absolute;
            left: -30px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: var(--primary-color);
            z-index: 1;
        }

        .timeline-item:after {
            content: "";
            position: absolute;
            left: -23px;
            top: 16px;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-item:last-child:after {
            display: none;
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .detail-header {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .detail-header:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: var(--primary-color);
        }

        .device-image-container {
            height: 300px;
            overflow: hidden;
            border-radius: 12px;
            position: relative;
        }

        .device-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease;
        }

        .device-image-container:hover .device-image {
            transform: scale(1.05);
        }

        .qr-container {
            padding: 15px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
        }

        /* Additional styles for gallery */
        .gallery-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .carousel-item {
            height: 300px;
            background-color: #f8f9fa;
        }

        .carousel-item img {
            object-fit: contain;
            height: 100%;
            width: 100%;
        }

        .thumbnails-container {
            display: flex;
            overflow-x: auto;
            gap: 8px;
            padding: 10px 0;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .thumbnail:hover {
            transform: translateY(-2px);
        }

        .thumbnail.active {
            border-color: var(--primary-color);
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 10%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-container:hover .carousel-control-prev,
        .gallery-container:hover .carousel-control-next {
            opacity: 0.8;
        }

        .carousel-indicators {
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <a href="{{ route('komputer.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div class="d-flex align-items-center mb-2">
                    <h2 class="mb-0">
                        <i class="bi bi-pc-display text-primary"></i>
                        {{ $komputer->nama_komputer }}
                    </h2>
                </div>
                <p class="text-muted">{{ $komputer->kode_barang }}</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group me-2" role="group">
                        <a href="{{ route('komputer.edit', $komputer->kode_barang) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Edit Data
                        </a>
                        <a href="{{ route('komputer.riwayat.index', $komputer->kode_barang) }}" class="btn btn-outline-success" >
                            <i class="bi bi-tools"></i> Riwayat Perbaikan
                        </a>
                    </div>
                    <div class="btn-group" role="group">
                        <form action="{{ route('komputer.destroy', $komputer->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Gallery section with carousel -->
                <div class="gallery-container mb-4">
                    @if($komputer->galleries->isNotEmpty())
                        <div id="komputerGallery" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-indicators">
                                @foreach($komputer->galleries as $index => $gallery)
                                    <button type="button" data-bs-target="#komputerGallery" data-bs-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($komputer->galleries as $index => $gallery)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                            alt="{{ $komputer->nama_komputer }} - Photo {{ $index + 1 }}" class="d-block w-100">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#komputerGallery"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#komputerGallery"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="position-absolute bottom-0 end-0 p-3">
                                <span class="badge rounded-pill bg-dark bg-opacity-75">
                                    <i class="bi bi-images"></i> {{ $komputer->galleries->count() }} Foto
                                </span>
                            </div>
                        </div>

                        <!-- Thumbnails navigation -->
                        <div class="thumbnails-container">
                            @foreach($komputer->galleries as $index => $gallery)
                                <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                    class="thumbnail {{ $index == 0 ? 'active' : '' }}" data-bs-target="#komputerGallery"
                                    data-bs-slide-to="{{ $index }}" alt="Thumbnail {{ $index + 1 }}">
                            @endforeach
                        </div>
                    @else
                        <div class="device-image-container">
                            <img src="https://via.placeholder.com/800x600/0d6efd/ffffff?text={{ $komputer->nama_komputer }}"
                                alt="{{ $komputer->nama_komputer }}" class="device-image">
                            <div class="position-absolute bottom-0 end-0 p-3">
                                <span class="badge rounded-pill bg-dark bg-opacity-75">
                                    <i class="bi bi-camera"></i> Tidak Ada Foto
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-info-circle text-primary"></i> Informasi Umum</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-door-open"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Ruangan</small>
                                        <strong>{{ $komputer->ruangan->nama_ruangan }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Pengguna</small>
                                        <strong>{{ $komputer->nama_pengguna_sekarang }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-pc-display-horizontal"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penggunaan Sekarang</small>
                                        <strong>{{ $komputer->penggunaan_sekarang }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tahun Pengadaan</small>
                                        <strong>{{ $komputer->tahun_pengadaan }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kesesuaian Mendukung Pekerjaan</small>
                                        @php
                                            $kesesuaianClass = [
                                                'Sangat Sesuai' => 'bg-success',
                                                'Sesuai' => 'bg-success',
                                                'Kurang Sesuai' => 'bg-warning text-dark',
                                                'Tidak Sesuai' => 'bg-danger'
                                            ];

                                            $kesesuaianText = [
                                                'Sangat Sesuai' => 'Sangat Sesuai',
                                                'Sesuai' => 'Sesuai',
                                                'Kurang Sesuai' => 'Kurang Sesuai',
                                                'Tidak Sesuai' => 'Tidak Sesuai'
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $kesesuaianClass[$komputer->kesesuaian_pc] ?? 'bg-secondary' }}">
                                            {{ $kesesuaianText[$komputer->kesesuaian_pc] ?? 'Tidak Diketahui' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div class="w-100">
                                <small class="text-muted d-block">Merek Komputer</small>
                                <strong>{{ $komputer->merek_komputer }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-cpu text-primary"></i> Spesifikasi Teknis</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-cpu"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Processor</small>
                                        <strong>{{ $komputer->spesifikasi_processor }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-memory"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">RAM</small>
                                        <strong>{{ $komputer->spesifikasi_ram }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-gpu-card"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">VGA</small>
                                        <strong>{{ $komputer->spesifikasi_vga }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-hdd"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penyimpanan</small>
                                        <strong>{{ $komputer->spesifikasi_penyimpanan }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-windows"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Sistem Operasi</small>
                                        <strong>{{ $komputer->sistem_operasi }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kondisi</small>
                                        @php
                                            $kondisiClass = [
                                                'Sangat Baik' => 'bg-success',
                                                'Baik' => 'bg-success',
                                                'Cukup' => 'bg-warning text-dark',
                                                'Kurang' => 'bg-warning text-dark',
                                                'Rusak' => 'bg-danger'
                                            ];

                                            $kondisiText = [
                                                'Sangat Baik' => 'Sangat Baik',
                                                'Baik' => 'Baik',
                                                'Cukup' => 'Cukup',
                                                'Kurang' => 'Kurang',
                                                'Rusak' => 'Rusak'
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $kondisiClass[$komputer->kondisi_komputer] ?? 'bg-secondary' }}">
                                            {{ $kondisiText[$komputer->kondisi_komputer] ?? 'Tidak Diketahui' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($komputer->keterangan_kondisi))
                            <div class="alert alert-light mt-3">
                                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Detail Kondisi:</h6>
                                <p class="mb-0">{!! nl2br(e($komputer->keterangan_kondisi)) !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card detail-card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Histori Pemeliharaan</h4>
                        <a href="{{ route('komputer.riwayat.index', $komputer->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Lihat Selengkapnya
                        </a>
                    </div>
                    <div class="card-body">
                        @if($komputer->maintenanceHistories->isEmpty())
                            <p class="text-muted text-center">Belum ada data pemeliharaan</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Teknisi</th>
                                            <th>Hasil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($komputer->maintenanceHistories as $item)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td>{{ $item->teknisi ?? '-' }}</td>
                                                <td>{{ $item->hasil_maintenance ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card detail-card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-text text-primary"></i> Informasi Tambahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Terakhir diperbarui</span>
                            <span>{{ \Carbon\Carbon::parse($komputer->updated_at)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Usia perangkat</span>
                            <span>{{ date('Y') - intval($komputer->tahun_pengadaan) }} tahun</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card detail-card sticky-md-top mb-4" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-upc-scan text-primary"></i> Identifikasi</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="qr-container mb-3">
                            <img src="{{ asset('storage/' . $komputer->barcode) }}"
                                alt="Barcode {{ $komputer->kode_barang }}" class="img-fluid">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-outline-primary" id="printBarcode">
                                <i class="bi bi-printer"></i> Cetak Barcode
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-inline-block p-3 bg-white">
                        <img src="{{ asset('storage/qrcodes/' . $komputer->kode_barang . '.png') }}"
                            alt="QR Code {{ $komputer->kode_barang }}" class="img-fluid">
                    </div>
                    <p class="mt-3">Scan QR code ini untuk melihat detail perangkat</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="printQr">
                        <i class="bi bi-printer"></i> Cetak QR Code
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Modal -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak Informasi Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            id="printFullDetail">
                            <div>
                                <h6 class="mb-1">Detail Lengkap</h6>
                                <small class="text-muted">Semua informasi perangkat</small>
                            </div>
                            <i class="bi bi-file-earmark-text"></i>
                        </button>
                        <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            id="printSpecOnly">
                            <div>
                                <h6 class="mb-1">Spesifikasi Teknis</h6>
                                <small class="text-muted">Hanya informasi spesifikasi perangkat</small>
                            </div>
                            <i class="bi bi-cpu"></i>
                        </button>
                        <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            id="printHistoryOnly">
                            <div>
                                <h6 class="mb-1">Histori Pemeliharaan</h6>
                                <small class="text-muted">Riwayat pemeliharaan perangkat</small>
                            </div>
                            <i class="bi bi-clock-history"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Pemeliharaan Modal -->
    {{-- <div class="modal fade" id="tambahPemeliharaanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Pemeliharaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pemeliharaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="asset_id" value="{{ $komputer->id }}">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                            <div class="form-text">Masukkan keterangan pemeliharaan yang dilakukan</div>
                        </div>
                        <div class="mb-3">
                            <label for="teknisi" class="form-label">Teknisi/Petugas</label>
                            <input type="text" class="form-control" id="teknisi" name="teknisi" required>
                        </div>
                        <div class="mb-3">
                            <label for="hasil_maintenance" class="form-label">Hasil Pemeliharaan</label>
                            <textarea class="form-control" id="hasil_maintenance" name="hasil_maintenance"
                                rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    --}}
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data perangkat dengan kode barang
                        <strong>{{ $komputer->kode_barang }}</strong>?
                    </p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('komputer.destroy', $komputer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus Perangkat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Activate thumbnail on click
                $('.thumbnail').click(function () {
                    $('.thumbnail').removeClass('active');
                    $(this).addClass('active');
                });

                // Update active thumbnail when carousel slides
                $('#komputerGallery').on('slide.bs.carousel', function (e) {
                    $('.thumbnail').removeClass('active');
                    $('.thumbnail').eq(e.to).addClass('active');
                });
            });
        </script>
    @endpush

@endsection
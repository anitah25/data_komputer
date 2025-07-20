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
    </style>

    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('komputer.index') }}">Daftar Perangkat</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Perangkat</li>
                    </ol>
                </nav>
                <h2 class="mb-0">
                    <i class="bi bi-pc-display text-primary"></i> 
                    {{ $perangkat->nomor_komputer }}
                </h2>
                <p class="text-muted">{{ $perangkat->nomor_aset }}</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <div class="btn-group" role="group">
                    <a href="{{ route('komputer.edit', $perangkat->id) }}" class="btn btn-outline-primary action-btn">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                    <a href="#" class="btn btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i class="bi bi-qr-code"></i> QR Code
                    </a>
                    <a href="#" class="btn btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#printModal">
                        <i class="bi bi-printer"></i> Cetak
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="device-image-container shadow-sm mb-4">
                    @if($perangkat->foto_path)
                        <img src="{{ asset('storage/' . $perangkat->foto_path) }}" alt="{{ $perangkat->nomor_komputer }}" class="device-image">
                    @else
                        <img src="https://via.placeholder.com/800x600/0d6efd/ffffff?text={{ $perangkat->nomor_komputer }}" alt="{{ $perangkat->nomor_komputer }}" class="device-image">
                    @endif
                    <div class="position-absolute bottom-0 end-0 p-3">
                        <span class="badge rounded-pill bg-dark bg-opacity-75">
                            <i class="bi bi-camera"></i> Foto Perangkat
                        </span>
                    </div>
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
                                        <strong>{{ $perangkat->nama_ruangan }}</strong>
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
                                        <strong>{{ $perangkat->nama_pengguna }}</strong>
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
                                        <strong>{{ $perangkat->tahun_pengadaan }}</strong>
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
                                                'sangat_sesuai' => 'bg-success',
                                                'sesuai' => 'bg-success',
                                                'kurang_sesuai' => 'bg-warning text-dark',
                                                'tidak_sesuai' => 'bg-danger'
                                            ];
                                            
                                            $kesesuaianText = [
                                                'sangat_sesuai' => 'Sangat Sesuai',
                                                'sesuai' => 'Sesuai',
                                                'kurang_sesuai' => 'Kurang Sesuai',
                                                'tidak_sesuai' => 'Tidak Sesuai'
                                            ];
                                        @endphp
                                        <span class="badge {{ $kesesuaianClass[$perangkat->kesesuaian_pc] ?? 'bg-secondary' }}">
                                            {{ $kesesuaianText[$perangkat->kesesuaian_pc] ?? 'Tidak Diketahui' }}
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
                                <small class="text-muted d-block">Penggunaan Sekarang</small>
                                <strong>{{ $perangkat->penggunaan_sekarang }}</strong>
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
                                        <strong>{{ $perangkat->processor }}</strong>
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
                                        <strong>{{ $perangkat->ram }}</strong>
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
                                        <strong>{{ $perangkat->vga }}</strong>
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
                                        <strong>{{ $perangkat->penyimpanan }}</strong>
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
                                        <strong>{{ $perangkat->sistem_operasi }}</strong>
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
                                                'sangat_baik' => 'bg-success',
                                                'baik' => 'bg-success',
                                                'cukup' => 'bg-warning text-dark',
                                                'kurang' => 'bg-warning text-dark',
                                                'rusak' => 'bg-danger'
                                            ];
                                            
                                            $kondisiText = [
                                                'sangat_baik' => 'Sangat Baik',
                                                'baik' => 'Baik',
                                                'cukup' => 'Cukup',
                                                'kurang' => 'Kurang',
                                                'rusak' => 'Rusak'
                                            ];
                                        @endphp
                                        <span class="badge {{ $kondisiClass[$perangkat->kondisi_komputer] ?? 'bg-secondary' }}">
                                            {{ $kondisiText[$perangkat->kondisi_komputer] ?? 'Tidak Diketahui' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($perangkat->detail_kondisi))
                        <div class="alert alert-light mt-3">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Detail Kondisi:</h6>
                            <p class="mb-0">{!! nl2br(e($perangkat->detail_kondisi)) !!}</p>
                        </div>
                        @endif
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
                            <img src="{{ asset('storage/barcodes/' . $perangkat->nomor_aset . '.png') }}" alt="Barcode {{ $perangkat->nomor_aset }}" class="img-fluid">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-outline-primary action-btn me-2" id="printBarcode">
                                <i class="bi bi-printer"></i> Cetak Barcode
                            </button>
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
                                <i class="bi bi-qr-code"></i> QR Code
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Histori Pemeliharaan</h4>
                    </div>
                    <div class="card-body">
                        @if($histori->isEmpty())
                            <p class="text-muted text-center">Belum ada data pemeliharaan</p>
                        @else
                            <div class="timeline">
                                @foreach($histori as $item)
                                    <div class="timeline-item">
                                        <p class="mb-0">{{ $item->tanggal }} - {{ $item->keterangan }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="text-center mt-3">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambahPemeliharaanModal">
                                <i class="bi bi-plus-circle"></i> Tambah Catatan Pemeliharaan
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card detail-card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-text text-primary"></i> Informasi Tambahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Terakhir diperbarui</span>
                            <span>{{ \Carbon\Carbon::parse($perangkat->tanggal_terakhir_update)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Usia perangkat</span>
                            <span>{{ date('Y') - intval($perangkat->tahun_pengadaan) }} tahun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4 mb-2">
            <a href="{{ route('komputer.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Perangkat
            </a>
            <div>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Hapus Perangkat
                </button>
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
                        <img src="{{ asset('storage/qrcodes/' . $perangkat->nomor_aset . '.png') }}" alt="QR Code {{ $perangkat->nomor_aset }}" class="img-fluid">
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
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printFullDetail">
                            <div>
                                <h6 class="mb-1">Detail Lengkap</h6>
                                <small class="text-muted">Semua informasi perangkat</small>
                            </div>
                            <i class="bi bi-file-earmark-text"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printSpecOnly">
                            <div>
                                <h6 class="mb-1">Spesifikasi Teknis</h6>
                                <small class="text-muted">Hanya informasi spesifikasi perangkat</small>
                            </div>
                            <i class="bi bi-cpu"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printHistoryOnly">
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
    <div class="modal fade" id="tambahPemeliharaanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Pemeliharaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pemeliharaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_perangkat" value="{{ $perangkat->id }}">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                            <div class="form-text">Masukkan keterangan pemeliharaan yang dilakukan</div>
                        </div>
                        <div class="mb-3">
                            <label for="petugas" class="form-label">Petugas</label>
                            <input type="text" class="form-control" id="petugas" name="petugas" required>
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
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data perangkat dengan nomor aset <strong>{{ $perangkat->nomor_aset }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('komputer.destroy', $perangkat->id) }}" method="POST">
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
        $(document).ready(function() {
            // Print barcode
            $("#printBarcode").click(function() {
                printImage("{{ asset('storage/barcodes/' . $perangkat->nomor_aset . '.png') }}", "Barcode - {{ $perangkat->nomor_aset }}");
            });
            
            // Print QR code
            $("#printQr").click(function() {
                printImage("{{ asset('storage/qrcodes/' . $perangkat->nomor_aset . '.png') }}", "QR Code - {{ $perangkat->nomor_aset }}");
            });
            
            // Print function for images
            function printImage(src, title) {
                var win = window.open();
                win.document.write('<html><head><title>' + title + '</title>');
                win.document.write('<style>body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }</style>');
                win.document.write('</head><body>');
                win.document.write('<img src="' + src + '" style="max-width: 100%;">');
                win.document.write('</body></html>');
                win.document.close();
                win.print();
                win.close();
            }
            
            // Print functions for details
            $("#printFullDetail, #printSpecOnly, #printHistoryOnly").click(function() {
                var url = "{{ route('komputer.print', $perangkat->id) }}";
                var printType = "";
                
                if (this.id === "printSpecOnly") {
                    printType = "spec";
                } else if (this.id === "printHistoryOnly") {
                    printType = "history";
                } else {
                    printType = "full";
                }
                
                window.open(url + "?type=" + printType, '_blank');
            });
        });
    </script>
    @endpush

@endsection
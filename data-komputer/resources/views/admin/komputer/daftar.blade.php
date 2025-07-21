@extends('admin.components.layout')

@section('content')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <strong><i class="bi bi-check-circle-fill me-2"></i>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-list-ul text-primary"></i> Daftar Perangkat Komputer
                </h2>
            </div>
            @can ('superadmin', auth()->user())
                <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                    <a href="{{ route('komputer.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Perangkat Baru
                    </a>
                </div>
            @endcan
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form class="row mb-3" action="{{ route('komputer.index') }}" method="GET">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control" id="searchInput"
                                placeholder="Cari perangkat..." value="{{ request('keyword') }}">
                            <button class="btn btn-primary" type="submit">
                                Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <div class="input-group flex-nowrap" style="min-width: 260px;">
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <select class="form-select" name="ruangan" id="filterRuangan" onchange="this.form.submit()">
                                    <option value="">Semua Ruangan</option>
                                    @foreach ($ruangan as $item)
                                        <option value="{{ $item->id }}" {{ request('ruangan') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group flex-nowrap" style="max-width: 220px;">
                                <span class="input-group-text bg-light"><i class="bi bi-reception-4"></i></span>
                                <select class="form-select" name="kondisi" id="filterKondisi" onchange="this.form.submit()">
                                    <option value="">Semua Kondisi</option>
                                    <option value="Sangat Baik" {{ request('kondisi') == 'Sangat Baik' ? 'selected' : '' }}>
                                        Sangat Baik</option>
                                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Cukup" {{ request('kondisi') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                    <option value="Kurang" {{ request('kondisi') == 'Kurang' ? 'selected' : '' }}>Kurang
                                    </option>
                                    <option value="Rusak" {{ request('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            @if(request('keyword') || request('ruangan') || request('kondisi'))
                                <a href="{{ route('komputer.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="">
                    <table class="table table-hover" id="perangkatTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Komputer</th>
                                <th>Lokasi</th>
                                <th>Pengguna</th>
                                <th>Penggunaan</th>
                                <th>Spesifikasi</th>
                                <th>Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($komputers as $index => $komputer)
                                <tr>
                                    <td>{{ ($komputers->currentPage() - 1) * $komputers->perPage() + $index + 1 }}</td>
                                    <td>{{ $komputer->kode_barang }}</td>
                                    <td>{{ $komputer->nama_komputer }}</td>
                                    <td>{{ $komputer->ruangan->nama_ruangan }}</td>
                                    <td>{{ $komputer->nama_pengguna_sekarang }}</td>
                                    <td>{{ $komputer->penggunaan_sekarang }}</td>
                                    <td>
                                        <small>
                                            <i class="bi bi-cpu"></i> {{ $komputer->spesifikasi_processor }}<br>
                                            <i class="bi bi-memory"></i> {{ $komputer->spesifikasi_ram }}<br>
                                            <i class="bi bi-hdd"></i> {{ $komputer->spesifikasi_penyimpanan }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($komputer->kondisi_komputer == 'Sangat Baik')
                                            <span class="badge bg-success">{{ $komputer->kondisi_komputer }}</span>
                                        @elseif($komputer->kondisi_komputer == 'Baik')
                                            <span
                                                class="badge bg-success-subtle text-success-emphasis">{{ $komputer->kondisi_komputer }}</span>
                                        @elseif($komputer->kondisi_komputer == 'Cukup')
                                            <span class="badge bg-info">{{ $komputer->kondisi_komputer }}</span>
                                        @elseif($komputer->kondisi_komputer == 'Kurang')
                                            <span class="badge bg-warning text-dark">{{ $komputer->kondisi_komputer }}</span>
                                        @elseif($komputer->kondisi_komputer == 'Rusak')
                                            <span class="badge bg-danger">{{ $komputer->kondisi_komputer }}</span>
                                        @else
                                            <span
                                                class="badge bg-secondary">{{ $komputer->kondisi_komputer ?? 'Tidak Diketahui' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('komputer.show', $komputer->kode_barang) }}"><i
                                                            class="bi bi-eye"></i> Detail</a></li>
                                                @can ('superadmin', auth()->user())
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('komputer.edit', $komputer->kode_barang) }}"><i
                                                                class="bi bi-pencil"></i> Edit</a></li>
                                                @endcan
                                                <li><a class="dropdown-item"
                                                        href="{{ route('komputer.edit', $komputer->kode_barang) }}"><i
                                                            class="bi bi-tools"></i> Riwayat Perbaikan</a></li>
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#barcodeModal-{{ $komputer->kode_barang }}"><i
                                                            class="bi bi-upc-scan"></i> Lihat Barcode</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('komputer.destroy', $komputer->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i
                                                                class="bi bi-trash"></i> Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Barcode Modal -->
                                        <div class="modal fade" id="barcodeModal-{{ $komputer->id }}" tabindex="-1"
                                            aria-labelledby="barcodeModalLabel-{{ $komputer->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="barcodeModalLabel-{{ $komputer->id }}">
                                                            Barcode Komputer: {{ $komputer->nama_komputer }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        @if($komputer->barcode && Storage::disk('public')->exists($komputer->barcode))
                                                            <div class="mb-3">
                                                                <img src="{{ Storage::url($komputer->barcode) }}"
                                                                    alt="Barcode {{ $komputer->kode_barang }}" class="img-fluid">
                                                            </div>
                                                            <div class="mt-2">
                                                                <p class="mb-1"><strong>Kode Barang:</strong>
                                                                    {{ $komputer->kode_barang }}</p>
                                                                <p class="mb-1"><strong>Nama Komputer:</strong>
                                                                    {{ $komputer->nama_komputer }}</p>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning">
                                                                <i class="bi bi-exclamation-triangle"></i> Barcode belum tersedia
                                                                untuk komputer ini.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        @if($komputer->barcode && Storage::disk('public')->exists($komputer->barcode))
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="printBarcode('{{ Storage::url($komputer->barcode) }}', '{{ $komputer->kode_barang }}', '{{ $komputer->nama_komputer }}')">
                                                                <i class="bi bi-printer"></i> Print Barcode
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-inbox text-secondary" style="font-size: 2rem;"></i>
                                            <h5 class="mt-3">Tidak ada data komputer</h5>
                                            <p class="text-secondary">Belum ada data komputer yang tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $komputers->links('pagination::bootstrap-5') }}
                </div>

                <!-- Pagination Info -->
                <div class="text-center text-muted mt-2">
                    <small>
                        Menampilkan {{ $komputers->firstItem() ?? 0 }} sampai {{ $komputers->lastItem() ?? 0 }} dari
                        {{ $komputers->total() }} data
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Add JavaScript for printing barcode -->
    {{--
    <script>
        function printBarcode(barcodeUrl, assetNumber, computerName) {
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
                        <html>
                        <head>
                            <title>Print Barcode</title>
                            <style>
                                body { font-family: Arial, sans-serif; text-align: center; }
                                .container { margin: 20px auto; max-width: 400px; }
                                img { max-width: 100%; height: auto; }
                                p { margin: 5px 0; font-size: 14px; }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <div class="mb-3">
                                    <img src="${barcodeUrl}" alt="Barcode ${assetNumber}" class="img-fluid">
                                </div>
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Kode Barang:</strong> ${assetNumber}</p>
                                    <p class="mb-1"><strong>Nama Komputer:</strong> ${computerName}</p>
                                </div>
                            </div>
                            <script>
                                window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 500); }
    </script>
    </body>

    </html>
    `);

    printWindow.document.close();
    }
    </script> --}}
@endsection
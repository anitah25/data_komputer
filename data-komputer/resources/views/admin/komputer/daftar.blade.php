@extends('admin.components.layout')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-list-ul text-primary"></i> Daftar Perangkat Komputer
                </h2>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                <a href="{{ route('komputer.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Perangkat Baru
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari perangkat...">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <select class="form-select w-auto" id="filterRuangan">
                                <option value="">Semua Ruangan</option>
                                <option value="Ruang Rapat">Ruang Rapat</option>
                                <option value="Ruang Server">Ruang Server</option>
                                <option value="Ruang Kerja">Ruang Kerja</option>
                            </select>
                            <select class="form-select w-auto" id="filterKondisi">
                                <option value="">Semua Kondisi</option>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Kurang">Kurang</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="perangkatTable">
                        <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Aset</th>
                            <th>Nama Komputer</th>
                            <th>Lokasi</th>
                            <th>Pengguna</th>
                            <th>Spesifikasi</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($komputers as $index => $komputer)
                            <tr>
                                <td>{{ ($komputers->currentPage() - 1) * $komputers->perPage() + $index + 1 }}</td>
                                <td>{{ $komputer->nomor_aset }}</td>
                                <td>{{ $komputer->nama_komputer }}</td>
                                <td>{{ $komputer->lokasi_penempatan }}</td>
                                <td>{{ $komputer->nama_pengguna_sekarang }}</td>
                                <td>
                                    <small>
                                        <i class="bi bi-cpu"></i> {{ $komputer->spesifikasi_processor }}<br>
                                        <i class="bi bi-memory"></i> {{ $komputer->spesifikasi_ram }}<br>
                                        <i class="bi bi-hdd"></i> {{ $komputer->spesifikasi_penyimpanan }}
                                    </small>
                                </td>
                                <td>
                                    @if(str_contains(strtolower($komputer->kesesuaian_pc), 'sesuai'))
                                        <span class="badge bg-success">{{ $komputer->kesesuaian_pc }}</span>
                                    @elseif(str_contains(strtolower($komputer->kesesuaian_pc), 'kurang'))
                                        <span class="badge bg-warning text-dark">{{ $komputer->kesesuaian_pc }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $komputer->kesesuaian_pc }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Aksi
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('komputer.show', $komputer->id) }}"><i class="bi bi-eye"></i> Detail</a></li>
                                            <li><a class="dropdown-item" href="{{ route('komputer.edit', $komputer->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-upc-scan"></i> Lihat Barcode</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('komputer.destroy', $komputer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash"></i> Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
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
                        Menampilkan {{ $komputers->firstItem() ?? 0 }} sampai {{ $komputers->lastItem() ?? 0 }} dari {{ $komputers->total() }} data
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

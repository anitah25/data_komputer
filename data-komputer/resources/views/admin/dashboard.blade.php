@extends('admin.components.layout')

@section('content')

<div class="row align-items-center">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold text-primary mb-3">Sistem Informasi Komputer ESDM</h1>
        <p class="lead">Selamat datang di Sistem Informasi Pengelolaan Data Komputer Kementerian Energi dan Sumber Daya Mineral. Sistem ini memudahkan pengelolaan aset teknologi informasi secara terpusat.</p>
        <div class="d-grid gap-2 d-md-flex mt-4">
            <a href="tambah-perangkat.php" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-plus-circle"></i> Tambah Data Perangkat
            </a>
            <a href="daftar-perangkat.php" class="btn btn-outline-primary btn-lg px-4">
                <i class="bi bi-list-ul"></i> Lihat Daftar Perangkat
            </a>
        </div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="https://via.placeholder.com/600x400/0d6efd/ffffff?text=SI-KOMPUTER+ESDM" alt="Sistem Informasi Komputer" class="img-fluid rounded shadow">
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h2 class="text-center mb-4">Fitur Utama</h2>
    </div>
</div>

<div class="row g-4 text-center">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body p-4">
                <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                    <i class="bi bi-pc-display" style="font-size: 2rem;"></i>
                </div>
                <h3>Pengelolaan Aset</h3>
                <p>Kelola data perangkat komputer dengan mudah, termasuk spesifikasi, pengguna, dan kondisi terkini.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body p-4">
                <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                    <i class="bi bi-clipboard-data" style="font-size: 2rem;"></i>
                </div>
                <h3>Pemeliharaan</h3>
                <p>Pantau dan kelola histori pemeliharaan perangkat untuk memastikan kinerja yang optimal.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body p-4">
                <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                    <i class="bi bi-bar-chart" style="font-size: 2rem;"></i>
                </div>
                <h3>Laporan & Analisis</h3>
                <p>Buat laporan dan analisis untuk pengambilan keputusan terkait pengelolaan aset IT.</p>
            </div>
        </div>
    </div>
</div>

@endsection

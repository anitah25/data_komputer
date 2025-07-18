<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-pc-display"></i> SI-KOMPUTER ESDM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                // Dapatkan nama file saat ini
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/komputer/tambah') ? 'active' : ''}}" href="{{ route('komputer.create') }}"><i class="bi bi-plus-circle"></i> Tambah Perangkat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/komputer') ? 'active' : ''}}" href="{{ route('komputer.index') }}"><i class="bi bi-list-ul"></i> Daftar Perangkat</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

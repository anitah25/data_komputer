<?php
// Pastikan session sudah dimulai di setiap halaman yang menyertakan navbar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
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
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php"><i class="bi bi-house"></i> Beranda</a>
                </li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'tambah-perangkat.php') ? 'active' : ''; ?>" href="tambah-perangkat.php"><i class="bi bi-plus-circle"></i> Tambah Perangkat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'daftar-perangkat.php') ? 'active' : ''; ?>" href="daftar-perangkat.php"><i class="bi bi-list-ul"></i> Daftar Perangkat</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'daftar-perangkat.php') ? 'active' : ''; ?>" href="daftar-perangkat.php"><i class="bi bi-list-ul"></i> Daftar Perangkat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

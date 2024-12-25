<?php
// Cek koneksi database
include "koneksi.php"; // Pastikan file ini memiliki koneksi database yang benar

if ($konek) {
    $status_koneksi = "Connected";
} else {
    $status_koneksi = "Disconnected";
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="images/loggo.png" style="width: 50px; height: 50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'datakaryawan.php') ? 'active' : ''; ?>" href="datakaryawan.php">Data Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'absensi.php') ? 'active' : ''; ?>" href="absensi.php">Rekap Absensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'scan.php') ? 'active' : ''; ?>" href="scan.php">Scan Kartu</a>
                </li>
            </ul>
        </div>

        <!-- Status koneksi database -->
        <div class="alert alert-<?php echo ($status_koneksi == 'Connected') ? 'success' : 'danger'; ?> mb-0" style="font-size: 14px; padding: 5px 10px;">
            Database Status: <?php echo $status_koneksi; ?>
        </div>
    </div>
</nav>

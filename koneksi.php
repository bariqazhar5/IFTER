<?php
// Cek apakah konstanta sudah didefinisikan sebelum mendefinisikannya
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost'); // Host database
}

if (!defined('DB_USER')) {
    define('DB_USER', 'root'); // Username database
}

if (!defined('DB_PASS')) {
    define('DB_PASS', ''); // Password database
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'absensitfid'); // Nama database
}

// Membuat koneksi
$konek = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Status koneksi
if ($konek) {
    $status_koneksi = "Connected";
} else {
    $status_koneksi = "Not Connected";
}
?>

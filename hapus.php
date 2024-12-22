<?php
include "koneksi.php";

// Baca ID dari URL
$id = $_GET['id'] ?? null;

// Validasi ID
if (!$id || !is_numeric($id)) {
    echo "
    <script>
        alert('ID tidak valid');
        location.replace('datakaryawan.php');
    </script>";
    exit;
}

// Hapus data menggunakan prepared statement
$stmt = $konek->prepare("DELETE FROM karyawan WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "
    <script>
        alert('Data berhasil dihapus');
        location.replace('datakaryawan.php');
    </script>";
} else {
    echo "
    <script>
        alert('Gagal menghapus data: {$stmt->error}');
        location.replace('datakaryawan.php');
    </script>";
}

$stmt->close();
$konek->close();
?>

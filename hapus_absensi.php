<?php
include "koneksi.php";

// Periksa apakah ada parameter 'id' di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data absensi berdasarkan id
    $delete_sql = $konek->prepare("DELETE FROM absensi WHERE id = ?");
    $delete_sql->bind_param("i", $id);

    if ($delete_sql->execute()) {
        // Menampilkan pesan berhasil dan kemudian redirect
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href = 'absensi.php'; // Redirect ke halaman absensi
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data.');
                window.location.href = 'absensi.php'; // Redirect ke halaman absensi
              </script>";
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan!');
            window.location.href = 'absensi.php'; // Redirect ke halaman absensi
          </script>";
    exit;
}
?>

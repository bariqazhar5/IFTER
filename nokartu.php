<?php
include "koneksi.php";

// Query untuk membaca data dari tabel tmprfid
$sql = mysqli_query($konek, "SELECT * FROM tmprfid LIMIT 1");

// Periksa apakah data ditemukan
if ($data = mysqli_fetch_array($sql)) {
    // Jika ada data, tampilkan nomor kartu
    $nokartu = $data['nokartu'];
} else {
    // Jika tidak ada data, set nomor kartu ke pesan peringatan
    $nokartu = "Tidak ada kartu yang terdeteksi";
}
?>

<div class="form-group">
    <label>No. Kartu</label>
    <input type="text" name="nokartu" id="nokartu" placeholder="Tempelkan Kartu" class="form-control" style="width: 200px" value="<?php echo htmlspecialchars($nokartu); ?>">
</div>

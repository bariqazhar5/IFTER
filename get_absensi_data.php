<?php
include 'koneksi.php';

$query = "SELECT DATE(jam_masuk) as tanggal, COUNT(*) as jumlah_hadir FROM absensi GROUP BY DATE(jam_masuk)";
$result = $konek->query($query);


$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>

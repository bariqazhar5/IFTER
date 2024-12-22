<?php
include "koneksi.php";
date_default_timezone_set('Asia/Jakarta');

// Ambil nilai filter dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$month = isset($_GET['month']) ? (int)$_GET['month'] : '';
$week = isset($_GET['week']) ? (int)$_GET['week'] : '';
$year = date('Y');

// Tentukan rentang tanggal jika minggu dipilih
$week_start = $week_end = null;
if ($week && $month) {
    $start_of_month = strtotime("$year-$month-01");
    $week_start = date('Y-m-d', strtotime("+$week week -1 week", $start_of_month));
    $week_end = date('Y-m-d', strtotime("+$week week -1 day", $start_of_month));
}

// Siapkan query
$sql_query = "
    SELECT a.id, b.nama, b.jabatan, a.tanggal, a.jam_masuk, a.jam_pulang 
    FROM absensi a 
    JOIN karyawan b ON a.nokartu = b.nokartu 
    WHERE (b.nama LIKE ? OR b.jabatan LIKE ? OR a.tanggal LIKE ?)
";

if ($month) {
    $sql_query .= " AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?";
}
if ($week_start && $week_end) {
    $sql_query .= " AND a.tanggal BETWEEN ? AND ?";
}

$sql_query .= " ORDER BY a.tanggal DESC";

$sql = $konek->prepare($sql_query);

// Bind parameter sesuai filter
$search_param = "%" . $search . "%";
if ($week_start && $week_end) {
    $sql->bind_param("sssisss", $search_param, $search_param, $search_param, $month, $year, $week_start, $week_end);
} elseif ($month) {
    $sql->bind_param("sssii", $search_param, $search_param, $search_param, $month, $year);
} else {
    $sql->bind_param("sss", $search_param, $search_param, $search_param);
}

// Eksekusi query
$sql->execute();
$result = $sql->get_result();

// Set header untuk file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="rekap_absensi.csv"');

// Buka file output (php://output)
$output = fopen('php://output', 'w');

// Tulis header CSV
fputcsv($output, ['No', 'Nama', 'Jabatan', 'Tanggal', 'Jam Masuk', 'Jam Pulang']);

// Tulis data ke file CSV
$no = 1;
while ($data = $result->fetch_assoc()) {
    // Format tanggal dengan benar
    $tanggal_terformat = date('Y-m-d', strtotime($data['tanggal'])); 

    // Tulis data ke CSV dengan format tanggal yang benar (tanpa tanda kutip)
    fputcsv($output, [
        $no++, 
        $data['nama'], 
        $data['jabatan'], 
        $tanggal_terformat, // Gunakan tanggal tanpa tanda kutip
        $data['jam_masuk'], 
        $data['jam_pulang']
    ]);
}

// Tutup file output
fclose($output);
?>

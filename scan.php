<?php
include "koneksi.php";

// Fetch pengaturan waktu absensi
$query = "SELECT * FROM pengaturan LIMIT 1";
$result = $konek->query($query);
$pengaturan = $result->fetch_assoc();

// Query untuk menghitung staff yang sudah masuk
$query_sudah_masuk = "SELECT COUNT(*) FROM absensi WHERE jam_masuk IS NOT NULL";
$result_sudah_masuk = $konek->query($query_sudah_masuk);
$sudah_masuk = $result_sudah_masuk->fetch_row()[0];

// Query untuk menghitung total staff
$query_total_staff = "SELECT COUNT(*) FROM karyawan";
$result_total_staff = $konek->query($query_total_staff);
$total_staff = $result_total_staff->fetch_row()[0];

$absensi_diaktifkan = $pengaturan['aktif'] ?? 0;
$waktu_masuk = $pengaturan['waktu_masuk'] ?? "07:00:00";
$batas_masuk = $pengaturan['batas_masuk'] ?? 2; 
$waktu_pulang = $pengaturan['waktu_pulang'] ?? "15:00:00";
$batas_pulang = $pengaturan['batas_pulang'] ?? 2;

// Konversi waktu batas absensi
$batas_masuk_akhir = date("H:i:s", strtotime("$waktu_masuk +$batas_masuk hours"));
$batas_pulang_akhir = date("H:i:s", strtotime("$waktu_pulang +$batas_pulang hours"));

// Periksa waktu saat ini
$waktu_sekarang = date("H:i:s");
$pesan = "Menunggu kartu RFID...";
$rfid_diterima = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rfid = $_POST['rfid'] ?? '';

    if (!empty($rfid)) {
        $rfid_diterima = true;
        
        // Periksa apakah absensi diaktifkan
        if ($absensi_diaktifkan) {
            if (
                ($waktu_sekarang >= $waktu_masuk && $waktu_sekarang <= $batas_masuk_akhir) ||
                ($waktu_sekarang >= $waktu_pulang && $waktu_sekarang <= $batas_pulang_akhir)
            ) {
                // Waktu absensi valid
                $pesan = "Absensi berhasil untuk kartu RFID: $rfid.";
            } else {
                // Di luar waktu absensi
                $pesan = "Gagal: Di luar waktu absensi.";
            }
        } else {
            // Absensi nonaktif, bebas waktu
            $pesan = "Absensi berhasil untuk kartu RFID: $rfid.";
        }
    } else {
        $pesan = "Gagal: Kartu RFID tidak terdeteksi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "header.php"; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Scan Kartu RFID Staff SDN KarangPawulang">
    <title>Scan Kartu</title>

    <!-- Menambahkan Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Menambahkan Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Script untuk membaca kartu RFID -->
    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function() {
                $("#cekkartu").load('bacakartu.php')
            }, 1000); // Set interval pembacaan kartu RFID
        });
    </script>
    <link rel="stylesheet" type="text/css" href="css/header.css">

</head>

<body>

    <!-- Sidebar -->
    <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="">
                    <h2 class="text-2xl font-bold mt-2">Absensi</h2>
                </div>
            </div>

            <div id="datetime" class="text-lg font-semibold text-sm mb-5"></div>

            <ul class="space-y-4 mt-5">
                <li>
                    <a href="index.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                        <i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD
                    </a>
                </li>
                <li>
                    <a href="datakaryawan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                        <i class="fas fa-users mr-3"></i> DATA STAFF
                    </a>
                </li>
                <li>
                    <a href="absensi.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                        <i class="fas fa-history mr-3"></i> RIWAYAT ABSENSI
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" class="flex items-center text-white  hover:bg-gray-700 p-2 rounded">
                        <i class="fas fa-cogs mr-3"></i> PENGATURAN
                    </a>
                </li>
                <li>
                    <a href="scan.php" class="flex items-center text-white bg-gray-700 hover:bg-gray-700 p-2 rounded">
                        <i class="fas fa-qrcode mr-3"></i> SCAN KARTU
                    </a>
                </li>
            </ul>
        </div>

        <!-- Mobile Sidebar -->
        <div class="lg:hidden bg-blue-500 text-white p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">Absensi</h2>
            <button id="burger" class="text-white text-3xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobileSidebar" class="fixed inset-0 bg-blue-500 text-white z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">Absensi</h2>
                    <button id="closeSidebar" class="text-3xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <ul class="space-y-4">
                    <li>
                        <a href="#" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                            <i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD
                        </a>
                    </li>
                    <li>
                        <a href="datakaryawan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                            <i class="fas fa-users mr-3"></i> DATA STAFF
                        </a>
                    </li>
                    <li>
                        <a href="absensi.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                            <i class="fas fa-history mr-3"></i> RIWAYAT ABSENSI
                        </a>
                    </li>
                    <li>
                        <a href="pengaturan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                            <i class="fas fa-cogs mr-3"></i> PENGATURAN
                        </a>
                    </li>
                    <li>
                        <a href="scan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
                            <i class="fas fa-qrcode mr-3"></i> SCAN KARTU
                        </a>
                    </li>
                </ul>
            </div>
            <div class="fixed bottom-0 w-full bg-gray-800 text-center text-white py-2">
                <p>&copy; <?php echo date('Y'); ?> Sistem Absensi SDN KarangPawulang</p>
            </div>
        </div>

        <!-- Isi Konten -->
        <div class="container py-6 px-4 mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Kartu RFID Prompt -->
                    <div class="lg:w-1/2 p-6 flex flex-col items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                        <h2 class="text-3xl font-bold mb-4">Scan Kartu RFID</h2>
                        <p class="text-lg mb-6">Silakan tempelkan kartu RFID Anda di perangkat.</p>
                        <div id="cekkartu" class="p-4 bg-white text-blue-700 rounded-lg shadow-lg font-medium">
                            <?php echo htmlspecialchars($pesan); ?>
                        </div>
                        <form method="POST" action="">
                            <input type="text" name="rfid" placeholder="Masukkan RFID" class="form-control mt-4">
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                    <!-- Informasi Tambahan -->
                    <div class="lg:w-1/2 p-6">
                        <h3 class="text-xl font-semibold mb-4">Panduan Penggunaan</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-id-card text-blue-500 mr-3"></i>
                                Tempelkan kartu RFID di area pembaca.
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                Tunggu hingga data berhasil dibaca.
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-yellow-500 mr-3"></i>
                                Pastikan kartu Anda terdaftar di sistem.
                            </li>
                        </ul>
                        <hr class="my-6">
                        <h3 class="text-xl font-semibold mb-4">Statistik Hari Ini</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-100 p-4 rounded-lg shadow">
                                <h4 class="text-lg font-bold text-blue-700">Total Absensi</h4>
                                <p class="text-2xl font-bold text-blue-900"><?php echo $sudah_masuk; ?></p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-lg shadow">
                                <h4 class="text-lg font-bold text-green-700">Staff Hadir</h4>
                                <p class="text-2xl font-bold text-green-900"><?php echo $total_staff; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Tambahkan JS Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            function updateDateTime() {
                const today = new Date();
                const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

                const day = days[today.getDay()];
                const date = today.getDate();
                const month = months[today.getMonth()];
                const year = today.getFullYear();
                let hours = today.getHours();
                let minutes = today.getMinutes();
                let seconds = today.getSeconds();

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                const time = hours + ":" + minutes + ":" + seconds;
                const dateStr = `${day}, ${date} ${month} ${year}`;

                document.getElementById("datetime").textContent = `${dateStr} | ${time}`;
            }

            setInterval(updateDateTime, 1000);
            updateDateTime();

            const burger = document.getElementById('burger');
            const sidebar = document.getElementById('mobileSidebar');
            const closeSidebar = document.getElementById('closeSidebar');

            burger.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });

            closeSidebar.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        </script>
</body>

</html>
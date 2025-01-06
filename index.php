<?php
include "koneksi.php";

// Query untuk menghitung staff yang sudah masuk
$query_sudah_masuk = "SELECT COUNT(*) FROM absensi WHERE jam_masuk IS NOT NULL";
$result_sudah_masuk = $konek->query($query_sudah_masuk);
$sudah_masuk = $result_sudah_masuk->fetch_row()[0];

// Query untuk menghitung total staff
$query_total_staff = "SELECT COUNT(*) FROM karyawan";
$result_total_staff = $konek->query($query_total_staff);
$total_staff = $result_total_staff->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama - Admin</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 font-roboto">

    <!-- Sidebar -->
    <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-5">   
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="">
                    <h2 class="text-2xl font-bold mt-2">Kehadiran</h2>
                </div>
            </div>

            <div id="datetime" class="text-lg font-semibold text-sm mb-5"></div>

            <ul class="space-y-4 mt-5">
                <li>
                    <a href="#" class="flex items-center text-white bg-gray-700 hover:bg-gray-700 p-2 rounded">
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
                        <i class="fas fa-history mr-3"></i> RIWAYAT KEHADIRAN
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

        <!-- Mobile Sidebar -->
        <div class="lg:hidden bg-blue-500 text-white p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">Kehadiran</h2>
            <button id="burger" class="text-white text-3xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobileSidebar" class="fixed inset-0 bg-blue-500 text-white z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">Kehadiran</h2>
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
                            <i class="fas fa-history mr-3"></i> RIWAYAT KEHADIRAN
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
                <p>&copy; <?php echo date('Y'); ?> Sistem Kehadiran SDN KarangPawulang</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow bg-gray-50 p-8">
            <div class="container mx-auto space-y-8">
                <!-- Dashboard Welcome Section -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-400 text-white shadow-lg rounded-lg p-8 text-center">
                    <h1 class="text-4xl font-extrabold mb-2">Selamat Datang di Sistem Kehadiran</h1>
                    <h2 class="text-2xl font-semibold">SDN KarangPawulang</h2>
                    <p class="mt-4 text-lg font-medium">Manajemen Kehadiran staff yang efektif dan efisien.</p>
                </div>

                <!-- Absensi Stats Section -->
                <div class="flex justify-center items-center">
                    <div class="grid  md:grid-cols-3 gap-3">
                        <!-- Staff Present -->
                        <div class="bg-white shadow-md rounded-lg p-2 flex flex-col items-center">
                            <div class="bg-blue-100 text-blue-500 rounded-full p-4 mb-4">
                                <i class="fas fa-user-check text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Staff Hadir</h3>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $sudah_masuk . " / " . $total_staff; ?>
                            </p>
                        </div>

                        <!-- Rekap Absensi -->
                        <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center">
                            <div class="bg-green-100 text-green-500 rounded-full p-4 mb-4">
                                <i class="fas fa-calendar-alt text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Rekap Kehadiran</h3>
                            <p class="text-lg font-medium text-gray-600 text-center">Lihat rekap Kehadiran lengkap staff</p>
                            <a href="absensi.php" class="mt-4 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Lihat Rekap</a>
                        </div>

                        <!-- Total Staff -->
                        <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center">
                            <div class="bg-red-100 text-red-500 rounded-full p-4 mb-4">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Staff</h3>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $total_staff; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white shadow-lg rounded-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-700 text-center mb-6">Grafik Kehadiran</h3>
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <center>
        <p>&copy; <?php echo date('Y'); ?> Sistem Kehadiran SDN KarangPawulang</p>
    </center>
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

        const ctx = document.getElementById('absensiChart').getContext('2d');
        const absensiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Jumlah Hadir',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        window.onload = function() {
            fetch('get_absensi_data.php')
                .then(response => response.json())
                .then(data => {
                    const labels = [];
                    const chartData = [];

                    data.forEach(item => {
                        labels.push(item.tanggal);
                        chartData.push(item.jumlah_hadir);
                    });

                    absensiChart.data.labels = labels;
                    absensiChart.data.datasets[0].data = chartData;
                    absensiChart.update();
                });
        };

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
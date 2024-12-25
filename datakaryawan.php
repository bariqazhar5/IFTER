<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "header.php"; ?>

    <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Link ke file CSS kustom -->
    <link rel="stylesheet" type="text/css" href="css/header.css">
</head>

<body class="bg-gray-100 font-roboto">

    <!-- Sidebar -->
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar Desktop -->
        <div class="lg:w-1/5 w-full bg-gradient-to-b from-blue-700 to-blue-500 text-white min-h-screen p-6 lg:block hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="Logo">
                    <h2 class="text-2xl font-bold mt-2">Absensi</h2>
                </div>
            </div>

            <div id="datetime" class="text-lg font-semibold mb-5"></div>

            <ul class="space-y-4 mt-5">
                <li>
                    <a href="index.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD
                    </a>
                </li>
                <li>
                    <a href="datakaryawan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                        <i class="fas fa-users mr-3"></i> DATA STAFF
                    </a>
                </li>
                <li>
                    <a href="absensi.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                        <i class="fas fa-history mr-3"></i> RIWAYAT ABSENSI
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                        <i class="fas fa-cogs mr-3"></i> PENGATURAN
                    </a>
                </li>
                <li>
                    <a href="scan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                        <i class="fas fa-qrcode mr-3"></i> SCAN KARTU
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Mobile -->
        <div class="lg:hidden bg-blue-600 text-white p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">Absensi</h2>
            <button id="burger" class="text-white text-3xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobileSidebar" class="fixed inset-0 bg-blue-600 text-white z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">Absensi</h2>
                    <button id="closeSidebar" class="text-3xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <ul class="space-y-4">
                    <li>
                        <a href="index.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD
                        </a>
                    </li>
                    <li>
                        <a href="datakaryawan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                            <i class="fas fa-users mr-3"></i> DATA STAFF
                        </a>
                    </li>
                    <li>
                        <a href="absensi.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                            <i class="fas fa-history mr-3"></i> RIWAYAT ABSENSI
                        </a>
                    </li>
                    <li>
                        <a href="pengaturan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                            <i class="fas fa-cogs mr-3"></i> PENGATURAN
                        </a>
                    </li>
                    <li>
                        <a href="scan.php" class="flex items-center text-white hover:bg-blue-800 p-3 rounded-lg">
                            <i class="fas fa-qrcode mr-3"></i> SCAN KARTU
                        </a>
                    </li>
                </ul>
            </div>
            <div class="fixed bottom-0 w-full bg-gray-800 text-center text-white py-2">
                <p>&copy; <?php echo date('Y'); ?> Sistem Absensi SDN KarangPawulang</p>
            </div>
        </div>

        <!-- ISI -->
        <main class="container py-6 px-4 mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-blue-700">Data Staff</h3>
                        <a href="tambah.php" class="btn btn-primary">Tambah Data Staff</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-blue-700 text-white">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">No. Kartu</th>
                                    <th class="text-center">NIP/NUPTK</th>
                                    <th>Nama</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Wali Kelas</th>
                                    <th class="text-center">Lahir</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "koneksi.php";
                                $query = "SELECT * FROM karyawan";
                                $result = mysqli_query($konek, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    $no = 1;
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td class='text-center'>{$no}</td>
                                            <td class='text-center'>{$data['nokartu']}</td>
                                            <td class='text-center'>{$data['nisn']}</td>
                                            <td>{$data['nama']}</td>
                                            <td class='text-center'>{$data['jabatan']}</td>
                                            <td class='text-center'>{$data['wali']}</td>
                                            <td class='text-center'>{$data['lahir']}</td>
                                            <td>{$data['alamat']}</td>
                                            <td class='text-center'>
                                                <a href='edit.php?id={$data['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                <a href='hapus.php?id={$data['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                            </td>
                                        </tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center text-warning'>Tidak ada data ditemukan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

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
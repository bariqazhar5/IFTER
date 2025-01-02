<!DOCTYPE html>
<html>

<head>
    <?php include "header.php"; ?>
    <title>Rekapitulasi Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        <main class="container my-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="m-0">Rekapitulasi Absensi</h3>
        </div>
        <div class="card-body">
            <!-- Form Filter -->
            <form method="GET" action="" class="row gx-3 gy-2 align-items-center mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Cari Nama, Jabatan, atau Tanggal" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="month">
                        <option value="">Pilih Bulan</option>
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $selected = (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '';
                            echo "<option value='$m' $selected>" . date("F", mktime(0, 0, 0, $m, 1)) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="week">
                        <option value="">Pilih Minggu</option>
                        <option value="1" <?php echo (isset($_GET['week']) && $_GET['week'] == 1) ? 'selected' : ''; ?>>Minggu 1</option>
                        <option value="2" <?php echo (isset($_GET['week']) && $_GET['week'] == 2) ? 'selected' : ''; ?>>Minggu 2</option>
                        <option value="3" <?php echo (isset($_GET['week']) && $_GET['week'] == 3) ? 'selected' : ''; ?>>Minggu 3</option>
                        <option value="4" <?php echo (isset($_GET['week']) && $_GET['week'] == 4) ? 'selected' : ''; ?>>Minggu 4</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-auto">
                    <a href="absensi.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
            <hr>

            <!-- Tabel Rekapitulasi -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "koneksi.php";
                        date_default_timezone_set('Asia/Jakarta');

                        // Ambil nilai filter
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

                        $no = 0;
                        while ($data = $result->fetch_assoc()) {
                            $no++;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($no); ?></td>
                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($data['tanggal']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($data['jam_masuk']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($data['jam_pulang']); ?></td>
                                <td class="text-center">
                                    <a href="edit_absensi.php?id=<?php echo urlencode($data['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="hapus_absensi.php?id=<?php echo urlencode($data['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Data?');">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                <a href="downloadcsv.php?search=<?php echo urlencode($search); ?>&month=<?php echo $month; ?>&week=<?php echo $week; ?>" class="btn btn-success">Unduh CSV</a>
            </div>
        </div>
    </div>

    <p class="text-center mt-4 text-muted">&copy; <?php echo date('Y'); ?> Sistem Absensi SDN KarangPawulang</p>
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
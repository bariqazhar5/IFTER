<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "header.php"; ?>
    <title>Rekapitulasi Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="Logo">
                    <h2 class="text-2xl font-bold mt-2">Kehadiran</h2>
                </div>
            </div>
            <ul class="space-y-4 mt-5">
                <li><a href="index.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded"><i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD</a></li>
                <li><a href="datakaryawan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded"><i class="fas fa-users mr-3"></i> DATA STAFF</a></li>
                <li><a href="absensi.php" class="flex items-center text-white bg-gray-700 hover:bg-gray-700 p-2 rounded"><i class="fas fa-history mr-3"></i> RIWAYAT KEHADIRAN</a></li>
                <li><a href="pengaturan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded"><i class="fas fa-cogs mr-3"></i> PENGATURAN</a></li>
                <li><a href="scan.php" class="flex items-center text-white hover:bg-gray-700 p-2 rounded"><i class="fas fa-qrcode mr-3"></i> SCAN KARTU</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <main class="container my-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="m-0">Rekapitulasi Kehadiran</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="row gx-3 gy-2 align-items-center mb-4">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Cari Nama, Jabatan, atau Tanggal" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="month">
                                <option value="">Pilih Bulan</option>
                                <?php for ($m = 1; $m <= 12; $m++) {
                                    $selected = (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '';
                                    echo "<option value='$m' $selected>" . date("F", mktime(0, 0, 0, $m, 1)) . "</option>";
                                } ?>
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

                    <!-- Table -->
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
                                $search = isset($_GET['search']) ? $_GET['search'] : '';
                                $month = isset($_GET['month']) ? (int)$_GET['month'] : '';
                                $week = isset($_GET['week']) ? (int)$_GET['week'] : '';
                                $year = date('Y');

                                $sql_query = "
                                SELECT a.id, b.nama, a.tanggal, a.jam_masuk, a.jam_pulang 
                                FROM absensi a
                                JOIN karyawan b ON a.nokartu = b.nokartu
                                WHERE (b.nama LIKE ? OR b.jabatan LIKE ? OR a.tanggal LIKE ?)
                                ";
                                if ($month) {
                                    $sql_query .= " AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?";
                                }
                                $sql_query .= " ORDER BY a.tanggal DESC";

                                $sql = $konek->prepare($sql_query);
                                $search_param = "%" . $search . "%";
                                if ($month) {
                                    $sql->bind_param("sssii", $search_param, $search_param, $search_param, $month, $year);
                                } else {
                                    $sql->bind_param("sss", $search_param, $search_param, $search_param);
                                }
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
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="downloadcsv.php?search=<?php echo urlencode($search); ?>&month=<?php echo $month; ?>&week=<?php echo $week; ?>" class="btn btn-success">Unduh excel</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

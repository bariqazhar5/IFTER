<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Rekapitulasi Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <style>
        /* Flexbox untuk memastikan footer selalu di bawah */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex-grow: 1; /* Isi ruang yang tersisa */
        }
        footer {
            margin-top: auto; /* Menarik footer ke bawah */
        }
    </style>

</head>
<body>
    <?php include "menu.php"; ?>

    <main class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Rekapitulasi Absensi</h3>
            </div>
            <div class="card-body">
                <!-- Form Filter -->
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Cari Nama, Jabatan, atau Tanggal" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <select class="form-select" name="week">
                            <option value="">Pilih Minggu</option>
                            <option value="1" <?php echo (isset($_GET['week']) && $_GET['week'] == 1) ? 'selected' : ''; ?>>Minggu 1</option>
                            <option value="2" <?php echo (isset($_GET['week']) && $_GET['week'] == 2) ? 'selected' : ''; ?>>Minggu 2</option>
                            <option value="3" <?php echo (isset($_GET['week']) && $_GET['week'] == 3) ? 'selected' : ''; ?>>Minggu 3</option>
                            <option value="4" <?php echo (isset($_GET['week']) && $_GET['week'] == 4) ? 'selected' : ''; ?>>Minggu 4</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="absensi.php" class="btn btn-danger w-100">Reset</a>
                    </div>
                </form>
                <hr>

                <!-- Tabel Rekapitulasi -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: grey; color:white">
                                <th style="text-align: center;">No.</th>
                                <th style="text-align: center;">Nama</th>
                                <th style="text-align: center;">Tanggal</th>
                                <th style="text-align: center;">Jam Masuk</th>
                                <th style="text-align: center;">Jam Pulang</th>
                                <th style="text-align: center;">Aksi</th>
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
                                    <td style="text-align: center;"><?php echo htmlspecialchars($no); ?></td>
                                    <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                    <td style="text-align: center;"><?php echo htmlspecialchars($data['tanggal']); ?></td>
                                    <td style="text-align: center;"><?php echo htmlspecialchars($data['jam_masuk']); ?></td>
                                    <td style="text-align: center;"><?php echo htmlspecialchars($data['jam_pulang']); ?></td>
                                    <td style="text-align: center;">
                                        <a href="edit_absensi.php?id=<?php echo urlencode($data['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="hapus_absensi.php?id=<?php echo urlencode($data['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus Data?');">Hapus</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <a href="downloadcsv.php?search=<?php echo urlencode($search); ?>&month=<?php echo $month; ?>&week=<?php echo $week; ?>" class="btn btn-success w-100">Unduh CSV</a>
                </div>

            </div>
        </div>
    </main>

    <?php include "footer.php"; ?>
</body>
</html>

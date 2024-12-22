<!DOCTYPE html>
<html lang="id">
<head>
    <?php include "header.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Menu Utama</title>

    <!-- Menambahkan Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tambahkan link CSS Bootstrap -->
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

    <!-- ISI -->
    <div class="container-fluid py-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <h1>Selamat Datang</h1>
                <h2>SISTEM ABSENSI STAFF</h2>
                <h3>SDN KarangPawulang</h3>

                <!-- Menampilkan jumlah staff yang sudah dan belum absen masuk -->
                <?php
                include "koneksi.php";

                // Menghitung jumlah staff yang sudah absen masuk
                $query_sudah_masuk = "SELECT COUNT(*) FROM absensi WHERE jam_masuk IS NOT NULL";
                $result_sudah_masuk = $konek->query($query_sudah_masuk);
                $sudah_masuk = $result_sudah_masuk->fetch_row()[0];

                // Menghitung total jumlah staff
                $query_total_staff = "SELECT COUNT(*) FROM karyawan";
                $result_total_staff = $konek->query($query_total_staff);
                $total_staff = $result_total_staff->fetch_row()[0];

                // Menampilkan jumlah staff yang hadir dan total staff
                echo "<p><strong>Staff hadir: </strong>" . $sudah_masuk . " / " . $total_staff . "</p>";
                ?>

                <!-- Tombol Mulai -->
                <a href="absensi.php" class="btn btn-primary mt-3">Rekap Absensi</a>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>

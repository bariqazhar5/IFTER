<?php
include "koneksi.php";

if (isset($_POST['btnSimpan'])) {
    // Baca inputan form dan sanitasi
    $nokartu = isset($_POST['nokartu']) ? mysqli_real_escape_string($konek, $_POST['nokartu']) : '';
    $nisn = mysqli_real_escape_string($konek, $_POST['nisn']);
    $nama = mysqli_real_escape_string($konek, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($konek, $_POST['jabatan']);
    $wali = mysqli_real_escape_string($konek, $_POST['wali']);
    $lahir = mysqli_real_escape_string($konek, $_POST['lahir']);
    $alamat = mysqli_real_escape_string($konek, $_POST['alamat']);

    // Persiapkan query untuk mencegah SQL Injection
    $query = "INSERT INTO karyawan (nokartu, nisn, nama, jabatan, wali, lahir, alamat) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($konek, $query)) {
        // Ikat parameter
        mysqli_stmt_bind_param($stmt, "sssssss", $nokartu, $nisn, $nama, $jabatan, $wali, $lahir, $alamat);

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            echo "
                    <script>
                        alert('Tersimpan');
                        location.replace('datakaryawan.php');
                    </script>
                ";
        } else {
            echo "
                    <script> 
                        alert('Gagal Tersimpan');
                        location.replace('datakaryawan.php');
                    </script>
                ";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "
                <script>
                    alert('Query preparation failed!');
                    location.replace('datakaryawan.php');
                </script>
            ";
    }
}

// Kosongkan table tmprfid (perbaiki query)
$deleteQuery = "DELETE FROM tmprfid";
mysqli_query($konek, $deleteQuery);
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "header.php"; ?>
    <title>Tambah Data Staff</title>

    <!-- Baca kartu RFID Otomatis -->
    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function() {
                $("#norfid").load('nokartu.php', function(response) {
                    // Jika pesan adalah "Tidak ada kartu yang terdeteksi", tampilkan pesan peringatan
                    if (response.trim() === "Tidak ada kartu yang terdeteksi.") {
                        $("#norfid").html('<p style="color: red;">Tidak ada kartu yang terdeteksi. Silakan coba lagi.</p>');
                    } else {
                        $("#norfid").html('<p> ' + response + '</p>');
                    }
                });
            }, 2000);
        });
    </script>


    <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link ke file CSS kustom -->
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="">
                    <h2 class="text-2xl font-bold mt-2">Kehadiran</h2>
                </div>
            </div>

            <div id="datetime" class="text-lg font-semibold text-sm mb-5">Jumat, 3 Januari 2025 | 0:26:45</div>

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

        <!-- Isi Halaman -->
        <div class="container py-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="mb-4 text-center">Tambah Data Karyawan</h3>

                    <!-- Form Input -->
                    <form method="POST">
                        <div id="norfid"></div>

                        <div class="form-group mb-3">
                            <label for="nisn">No. NIP/NUPTK</label>
                            <input type="text" name="nisn" id="nisn" placeholder="Masukkan NIP/NUPTK" class="form-control" style="width: 100%;">
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama">Nama Karyawan</label>
                            <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Karyawan" class="form-control" style="width: 100%;">
                        </div>

                        <div class="form-group mb-3">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" placeholder="Masukkan Jabatan" class="form-control" style="width: 100%;">
                        </div>

                        <div class="form-group mb-3">
                            <label for="lahir">Tanggal Lahir</label>
                            <input type="date" name="lahir" id="lahir" placeholder="Masukkan Tanggal Lahir" class="form-control" style="width: 100%;">
                        </div>

                        <div class="form-group mb-3">
                            <label for="wali">Wali Kelas</label>
                            <input type="text" name="wali" id="wali" placeholder="Masukkan Wali Kelas" class="form-control" style="width: 100%;">
                        </div>

                        <div class="form-group mb-3">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat" rows="3" style="width: 100%;"></textarea>
                        </div>

                        <!-- Tombol Simpan dan Kembali -->
                        <div class="text-center">
                            <button class="btn btn-primary" name="btnSimpan" id="btnSimpan">Simpan</button>
                            <a href="datakaryawan.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
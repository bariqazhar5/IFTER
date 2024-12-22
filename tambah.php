<?php
    include "koneksi.php";

    if (isset($_POST['btnSimpan'])) {
        // Baca inputan form dan sanitasi
        $nokartu = mysqli_real_escape_string($konek, $_POST['nokartu']);
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
        $(document).ready(function(){
            setInterval(function(){
                $("#norfid").load('nokartu.php')
            }, 2000); // Timer pembacan file nokartu.php, 1 detik =1000
        });
    </script>

    <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link ke file CSS kustom -->
    <link rel="stylesheet" type="text/css" href="css/header.css">
</head>
<body>

    <?php include "menu.php"; ?>

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

    <?php include "footer.php"; ?>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

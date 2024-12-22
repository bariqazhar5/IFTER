<!DOCTYPE html>
<html lang="id">
<head>
    <?php include "header.php"; ?>
    
        <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Link ke file CSS kustom -->
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
    <main class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Data Karyawan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
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

                            if (!$konek) {
                                echo "<tr><td colspan='9' class='text-center text-danger'>Koneksi database gagal: " . mysqli_connect_error() . "</td></tr>";
                            } else {
                                $query = "SELECT * FROM karyawan";
                                $result = mysqli_query($konek, $query);

                                if (!$result) {
                                    echo "<tr><td colspan='9' class='text-center text-danger'>Kesalahan query: " . mysqli_error($konek) . "</td></tr>";
                                } elseif (mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='9' class='text-center text-warning'>Tidak ada data ditemukan.</td></tr>";
                                } else {
                                    $no = 0;
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $no++;
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
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tombol tambah karyawan -->
        <div class="mt-4 text-end">
            <a href="tambah.php" class="btn btn-primary">Tambah Data Karyawan</a>
        </div>
    </main>

    <?php include "footer.php"; ?>
</body>
</html>

<?php
include "koneksi.php";

// Periksa apakah ada parameter 'id' di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data absensi berdasarkan id
    $sql = $konek->prepare("SELECT * FROM absensi WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan!";
        exit;
    }

    // Proses form untuk mengupdate data absensi
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = $_POST['nama'];
        $tanggal = $_POST['tanggal'];
        $jam_masuk = $_POST['jam_masuk'];
        $jam_pulang = $_POST['jam_pulang'];

        // Update data absensi
        $update_sql = $konek->prepare("UPDATE absensi SET nama = ?, tanggal = ?, jam_masuk = ?, jam_pulang = ? WHERE id = ?");
        $update_sql->bind_param("ssssi", $nama, $tanggal, $jam_masuk, $jam_pulang, $id);

        if ($update_sql->execute()) {
            echo "<script>
                    alert('Data berhasil diperbarui!');
                    location.replace('absensi.php');
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui data.');
                  </script>";
        }
    }
} else {
    echo "ID tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "header.php"; ?>
    <title>Edit Absensi</title>

    <!-- Tambahkan link CSS Bootstrap -->
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

    <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="">
                    <h2 class="text-2xl font-bold mt-2">Absensi</h2>
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
                    <a href="datakaryawan.php" class="flex items-center text-white  hover:bg-gray-700 p-2 rounded">
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

        <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; Width: 100%;">
            <div class="card shadow-lg border-0 rounded" style="width: 50%;">
                <div class="card-body">
                    <h3 class="text-center mb-4">Edit Absensi</h3>
                    <!-- Form Edit Absensi -->
                    <form method="POST">
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="<?php echo htmlspecialchars($data['tanggal']); ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="time" name="jam_masuk" id="jam_masuk" value="<?php echo htmlspecialchars($data['jam_masuk']); ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jam_pulang">Jam Pulang</label>
                            <input type="time" name="jam_pulang" id="jam_pulang" value="<?php echo htmlspecialchars($data['jam_pulang']); ?>" class="form-control" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">Update Absensi</button>
                            <a href="absensi.php" class="btn btn-secondary btn-lg ms-3">Kembali</a>
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
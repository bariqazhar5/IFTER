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
</head>
<body>
    <?php include "menu.php"; ?>

    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
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

    <?php include "footer.php"; ?>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

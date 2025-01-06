<?php
include "koneksi.php";

// Baca ID
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan'); location.replace('datakaryawan.php');</script>";
    exit;
}

// Ambil data berdasarkan ID
$stmt = $konek->prepare("SELECT * FROM karyawan WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$hasil = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$hasil) {
    echo "<script>alert('Data tidak ditemukan'); location.replace('datakaryawan.php');</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Baca inputan form
    $fields = ['nokartu', 'nisn', 'nama', 'jabatan', 'wali', 'lahir', 'alamat'];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = trim($_POST[$field] ?? '');
    }

    // Validasi data tidak boleh kosong
    if (empty($data['nokartu']) || empty($data['nisn']) || empty($data['nama']) || empty($data['jabatan'])) {
        echo "<script>alert('Harap isi semua field wajib');</script>";
    } else {
        try {
            // Update data menggunakan prepared statement
            $stmt = $konek->prepare(
                "UPDATE karyawan SET nokartu = ?, nama = ?, nisn = ?, jabatan = ?, wali = ?, lahir = ?, alamat = ? WHERE id = ?"
            );
            $stmt->bind_param("ssssssss", $data['nokartu'], $data['nama'], $data['nisn'], $data['jabatan'], $data['wali'], $data['lahir'], $data['alamat'], $id);

            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil disimpan'); location.replace('datakaryawan.php');</script>";
            } else {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "<script>alert('Gagal menyimpan data: {$e->getMessage()}');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "header.php"; ?>
    <title>Ubah Data Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <div class="container py-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3>Ubah Data Staff</h3>
                    <form method="POST">
                        <?php
                        // Daftar input field yang akan ditampilkan
                        $inputFields = [
                            'nokartu' => 'Nomor Kartu RFID',
                            'nisn' => 'NIP/NUPTK',
                            'nama' => 'Nama Karyawan',
                            'jabatan' => 'Jabatan',
                            'wali' => 'Wali',
                            'lahir' => 'Tanggal Lahir',
                            'alamat' => 'Alamat'
                        ];

                        // Loop untuk menampilkan input field
                        foreach ($inputFields as $name => $label) {
                            $value = htmlspecialchars($hasil[$name] ?? '');
                            echo '<div class="form-group">';
                            if ($name == 'lahir') {
                                echo "<label>$label</label><input type='date' name='$name' class='form-control' value='$value'>";
                            } elseif ($name == 'alamat') {
                                echo "<label>$label</label><textarea name='$name' class='form-control'>$value</textarea>";
                            } else {
                                echo "<label>$label</label><input type='text' name='$name' class='form-control' value='$value' required>";
                            }
                            echo '</div>';
                        }
                        ?>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="datakaryawan.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <?php include "footer.php"; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
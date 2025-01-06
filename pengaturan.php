<?php
include "koneksi.php";

// Default values if the table is empty
$default_pengaturan = [
    'pagi_mulai' => date('H:i:s'), // Current time
    'pagi_batas' => 2,
    'sore_mulai' => date('H:i:s'),
    'sore_batas' => 2,
    'aktifkan_absensi' => 1
];

// Fetch current settings from the database
$sql_select = "SELECT * FROM pengaturan WHERE id = 1";
$result = $konek->query($sql_select);

if ($result && $result->num_rows > 0) {
    $pengaturan = $result->fetch_assoc();
} else {
    $pengaturan = $default_pengaturan;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pagi_mulai = $_POST['pagi_mulai'];
    $pagi_batas = $_POST['pagi_batas'];
    $sore_mulai = $_POST['sore_mulai'];
    $sore_batas = $_POST['sore_batas'];
    $aktifkan_absensi = isset($_POST['aktifkan_absensi']) ? 1 : 0;

    // Update settings in the database
    $sql_update = "
        INSERT INTO pengaturan (id, pagi_mulai, pagi_batas, sore_mulai, sore_batas, aktifkan_absensi)
        VALUES (1, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        pagi_mulai = VALUES(pagi_mulai),
        pagi_batas = VALUES(pagi_batas),
        sore_mulai = VALUES(sore_mulai),
        sore_batas = VALUES(sore_batas),
        aktifkan_absensi = VALUES(aktifkan_absensi)
    ";

    $stmt = $konek->prepare($sql_update);
    $stmt->bind_param("sdsdi", $pagi_mulai, $pagi_batas, $sore_mulai, $sore_batas, $aktifkan_absensi);
    $stmt->execute();

    // Refresh the page to reflect changes
    header("Location: pengaturan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <div class="lg:w-1/5 w-full bg-cover bg-center text-white min-h-screen p-6 lg:block hidden" style="background-image: url('./images/bkg1.png');">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-5">
                    <img class="w-12 h-auto" src="./images/loggo.png" alt="">
                    <h2 class="text-2xl font-bold mt-2">Kehadiran</h2>
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
                        <i class="fas fa-history mr-3"></i> RIWAYAT KEHADIRAN
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" class="flex items-center text-white bg-gray-700 hover:bg-gray-700 p-2 rounded">
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
            <h2 class="text-lg font-bold">Kehadiran</h2>
            <button id="burger" class="text-white text-3xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobileSidebar" class="fixed inset-0 bg-blue-500 text-white z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">Kehadiran</h2>
                    <button id="closeSidebar" class="text-3xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <ul class="space-y-4">
                    <li>
                        <a href="#" class="flex items-center text-white hover:bg-gray-700 p-2 rounded">
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
            <div class="fixed bottom-0 w-full bg-gray-800 text-center text-white py-2">
                <p>&copy; <?php echo date('Y'); ?> Sistem Kehadiran SDN KarangPawulang</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="container my-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="m-0">Pengaturan Waktu Kehadiran</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="pagi_mulai" class="form-label">Waktu Kehadiran Pagi</label>
                            <input type="time" class="form-control" id="pagi_mulai" name="pagi_mulai" value="<?php echo htmlspecialchars($pengaturan['pagi_mulai']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="pagi_batas" class="form-label">Batas Waktu Kehadiran Pagi (jam)</label>
                            <input type="number" step="0.1" class="form-control" id="pagi_batas" name="pagi_batas" value="<?php echo htmlspecialchars($pengaturan['pagi_batas']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="sore_mulai" class="form-label">Waktu Kehadiran Sore</label>
                            <input type="time" class="form-control" id="sore_mulai" name="sore_mulai" value="<?php echo htmlspecialchars($pengaturan['sore_mulai']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="sore_batas" class="form-label">Batas Waktu Kehadiran Sore (jam)</label>
                            <input type="number" step="0.1" class="form-control" id="sore_batas" name="sore_batas" value="<?php echo htmlspecialchars($pengaturan['sore_batas']); ?>" required>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="aktifkan_absensi" name="aktifkan_absensi" <?php echo $pengaturan['aktifkan_absensi'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="aktifkan_absensi">Aktifkan Pengaturan Waktu Kehadiran</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
        </main>
    </div>


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
            document.getElementById("datetime").textContent = `${day}, ${date} ${month} ${year} | ${time}`;
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
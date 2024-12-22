<?php
include "koneksi.php";

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Fetch current attendance mode (1 = Masuk, 2 = Pulang)
function getModeAbsen($konek) {
    $sql = $konek->prepare("SELECT mode FROM status LIMIT 1");
    if ($sql->execute()) {
        $result = $sql->get_result();
        $data = $result->fetch_assoc();
        return $data['mode'] ?? 1; // Default to 1 (Masuk) if mode is not set
    }
    throw new Exception("Error reading mode: " . $sql->error);
}

// Fetch RFID card number
function getRFIDCard($konek) {
    $sql = $konek->prepare("SELECT nokartu FROM tmprfid LIMIT 1");
    if ($sql->execute()) {
        $result = $sql->get_result();
        $data = $result->fetch_assoc();
        return $data['nokartu'] ?? "";
    }
    throw new Exception("Error reading card: " . $sql->error);
}

// Check if the card exists in the database
function getEmployeeName($konek, $nokartu) {
    $sql = $konek->prepare("SELECT nama FROM karyawan WHERE nokartu = ? LIMIT 1");
    $sql->bind_param("s", $nokartu);
    if ($sql->execute()) {
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['nama'];
        }
        return null;
    }
    throw new Exception("Error checking employee: " . $sql->error);
}

// Insert or update attendance based on the mode
function handleAttendance($konek, $nokartu, $nama, $tanggal, $jam, $mode_absen) {
    if ($mode_absen == 1) { // Mode "Masuk"
        // Check if already marked as "Masuk"
        $sql = $konek->prepare("SELECT * FROM absensi WHERE nokartu = ? AND tanggal = ? AND jam_masuk IS NOT NULL LIMIT 1");
        $sql->bind_param("ss", $nokartu, $tanggal);
        if ($sql->execute()) {
            $result = $sql->get_result();
            if ($result->num_rows > 0) {
                return "Anda Sudah Absen Masuk";
            }
            // Insert entry time
            $sql = $konek->prepare("INSERT INTO absensi (nokartu, tanggal, jam_masuk, nama) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssss", $nokartu, $tanggal, $jam, $nama);
            if ($sql->execute()) {
                return "Selamat Datang $nama";
            }
            return "Error inserting attendance: " . $sql->error;
        }
    } else { // Mode "Pulang"
        // Check if already marked as "Pulang"
        $sql = $konek->prepare("SELECT * FROM absensi WHERE nokartu = ? AND tanggal = ? AND jam_pulang IS NOT NULL LIMIT 1");
        $sql->bind_param("ss", $nokartu, $tanggal);
        if ($sql->execute()) {
            $result = $sql->get_result();
            if ($result->num_rows > 0) {
                return "Anda Sudah Absen Pulang";
            }
            // Update exit time
            $sql = $konek->prepare("UPDATE absensi SET jam_pulang = ? WHERE nokartu = ? AND tanggal = ?");
            $sql->bind_param("sss", $jam, $nokartu, $tanggal);
            if ($sql->execute()) {
                return "Selamat Jalan $nama";
            }
            return "Error updating exit attendance: " . $sql->error;
        }
    }
    return "Unexpected error.";
}

// Main logic
try {
    $mode_absen = getModeAbsen($konek);
    $nokartu = getRFIDCard($konek);

    // Display message based on card status
    if ($nokartu == "") {
        echo "<h3>Absen: " . ($mode_absen == 1 ? "Masuk" : "Pulang") . "</h3>
              <h3>Silahkan Tempelkan Kartu</h3>
              <img src='images/rfid.png' style='width: 200px'><br>
              <img src='images/animasi2.gif'>";
    } else {
        $nama = getEmployeeName($konek, $nokartu);
        if ($nama === null) {
            echo "<h1>Maaf! Kartu Tidak Dikenali</h1>";
        } else {
            $tanggal = date('Y-m-d');
            $jam = date('H:i:s');
            $message = handleAttendance($konek, $nokartu, $nama, $tanggal, $jam, $mode_absen);
            echo "<h1>$message</h1>";
        }
    }

    // Clear RFID card from tmprfid table
    $konek->query("DELETE FROM tmprfid");
} catch (Exception $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>

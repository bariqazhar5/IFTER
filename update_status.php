<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = $konek->prepare("UPDATE absensi SET status = ? WHERE id = ?");
    $sql->bind_param("si", $status, $id);

    if ($sql->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate status']);
    }
    exit;
}
?>

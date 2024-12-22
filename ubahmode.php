<?php
    include "koneksi.php";

    //baca mode absensi terakhir
    $mode = mysqli_query($konek, "select * from status");
    $data_mode = mysqli_fetch_array($mode);
    $mode_absen = $data_mode['mode'];

    //Status Terakhir ditambah 1
    $mode_absen = $mode_absen + 1;
    if($mode_absen > 2)
        $mode_absen = 1;

    //Simpan Mode Absen distatus
    $simpan = mysqli_query($konek, "update status set mode='$mode_absen'");
    
    if($simpan)
        echo "berhasil";
    else   
        echo "gagal";


?>
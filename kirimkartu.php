<?php
    include "koneksi.php";

    //BAca no kartu
    $nokartu = $_GET['nokartu'];
    //kosongkan Tabel Tmprid
    mysqli_query($konek, "delete from tmprfid");

    //simpan no kartu baru ked tabel tmprfid
    $simpan = mysqli_query($konek, "insert into tmprfid(nokartu)values('$nokartu')");
    if($simpan)
        echo "Berhasil";
    else
        echo "Gagal";

?>
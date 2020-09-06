<?php
include "../../koneksi.php";
$id_spj = isset($_POST['id_spj']) ? $_POST['id_spj'] : '';
$id_keg = isset($_POST['id_keg']) ? $_POST['id_keg'] : '';
$jumlah = isset($_POST['jml']) ? $_POST['jml'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tabel = 'b'.$bulan;

    $q = mysql_query("update target_spj set `$tabel`='$jumlah' WHERE id_spj='$id_spj' ") or die(mysql_error());

    if ($q) {
        $q2 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$tabel`) as jumlah FROM target_spj WHERE id_kegiatan='$id_keg'"));
        mysql_query("UPDATE `target` SET `$tabel`='$q2[jumlah]' WHERE id_kegiatan='$id_keg'");
        $msg = array('res'=> 1);
    } else {
        $msg = array('res'=> 0);
    }
    
    echo json_encode($msg);
    // echo $jumlah;
?>
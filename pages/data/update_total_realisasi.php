<?php
include "../../koneksi.php";
$id_spj = isset($_GET['id_spj']) ? $_GET['id_spj'] : '';
$total_realisasi = isset($_GET['total_realisasi']) ? $_GET['total_realisasi'] : '';

    mysql_query("update spj set total_realisasi='$total_realisasi' WHERE id_spj='$id_spj' ") or die(mysql_error());
?>
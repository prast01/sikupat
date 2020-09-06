<?php
include "../../koneksi.php";
$id_spj = isset($_GET['id_spj']) ? $_GET['id_spj'] : '';
$jml = isset($_GET['jml']) ? $_GET['jml'] : '';

    mysql_query("update spj set real_kegiatan='$jml' WHERE id_spj='$id_spj' ") or die(mysql_error());
?>
<?php
include "../../koneksi.php";
$id_spj = isset($_GET['id_spj']) ? $_GET['id_spj'] : '';
$pagu_anggaran = isset($_GET['pagu_anggaran']) ? $_GET['pagu_anggaran'] : '';

    mysql_query("update spj set pagu_anggaran='$pagu_anggaran' WHERE id_spj='$id_spj' ") or die(mysql_error());
?>
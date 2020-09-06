<?php
	include "koneksi.php";
	header("Content-Type:application/json");
	$data = json_decode(file_get_contents('php://input'), true);
	$id_spj = $data['id_spj'];
	$bulan = $data['bulan'];
	$kegiatan = $data['kegiatan'];
	$nominal = $data['nominal'];
	$seksi = $data['seksi'];
	$id_kegiatan = '6';
	$nip = $data['nip'];
	
	$d2 = mysql_fetch_assoc(mysql_query("SELECT kd_peg FROM pegawai WHERE nip='$nip'"));

	$q = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg, pl, seksi_pl, st) VALUES('$id_spj', '$bulan', '$kegiatan', '$nominal', '1', 'DJ002', '$id_kegiatan', '$d2[kd_peg]', '$seksi', '1')");

	echo 1;
?>
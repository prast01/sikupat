<?php
	include "koneksi.php";
	header("Content-Type:application/json");
	$data = json_decode(file_get_contents('php://input'), true);
	$kode_rekening = $data['kode_rekening'];
	$bulan = $data['bulan'];
	$alasan = $data['alasan'];
	$rok = $data['rok'];
	$nominal = $data['nominal'];
	$seksi = $data['seksi'];
	$id_kegiatan = $data['dpa_sikupat'];
	$nip = $data['nip'];
	
	$d = mysql_fetch_assoc(mysql_query("SELECT id_spj, kseksi FROM spj WHERE id_kegiatan='$id_kegiatan' AND kode_rekening='$kode_rekening'"));
	$d2 = mysql_fetch_assoc(mysql_query("SELECT kd_peg FROM pegawai WHERE nip='$nip'"));

	if ($d['id_spj'] != '') {
		if ($rok == '0') {
			if ($id_kegiatan == '6') {
				$q = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg, pl, seksi_pl, st) VALUES('$d[id_spj]', '$bulan', '$alasan', '$nominal', '1', '$d[kseksi]', '$id_kegiatan', '$d2[kd_peg]', '$seksi', '2')");
			} else {
				$q = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg, pl, seksi_pl, st) VALUES('$d[id_spj]', '$bulan', '$alasan', '$nominal', '1', '$seksi', '$id_kegiatan', '$d2[kd_peg]', '$seksi', '2')");
			}
		} else {
			$nominal2 = $rok+$nominal;
			if ($id_kegiatan == '6') {
				$q = mysql_query("UPDATE target_detail SET nominal='$nominal2' WHERE seksi_pl='$seksi' AND id_keg='$id_kegiatan' AND id_spj='$d[id_spj]' AND bulan='$bulan' AND nominal='$nominal'");
			} else {
				$q = mysql_query("UPDATE target_detail SET nominal='$nominal2' WHERE seksi='$seksi' AND id_keg='$id_kegiatan' AND id_spj='$d[id_spj]' AND bulan='$bulan' AND nominal='$nominal'");
			}
		}
	} else {
		$msg = array("res" => 0);
	}

	echo '1';
?>
<?php
	include "koneksi.php";

	$q = mysql_query("SELECT * FROM bibs WHERE tgl_transfer != '0000-00-00' AND MONTH(tgl_kegiatan)='3'");
	while ($d = mysql_fetch_assoc($q)) {
		$kd_transaksi = $d['kd_transaksi'];
		$tgl_kegiatan = $d['tgl_kegiatan'];
		$tgl_transfer = $d['tgl_transfer'];
		$uraian = $d['uraian'];
		$id_spj = $d['id_spj'];
		$kseksi = $d['kseksi'];
		$kbidang = $d['kbidang'];
		$nominal = $d['nominal'];
		$q2 = mysql_query("SELECT * FROM bibs_detail WHERE kd_transaksi='$kd_transaksi'");

		while($d2 = mysql_fetch_assoc($q2)){
			$kd_peg = $d2['kd_peg'];
			$rupiah = $d2['rupiah'];
			$cek = mysql_num_rows(mysql_query("SELECT * FROM lkh_bibs WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'"));

			if ($cek == '0') {
				if ($rupiah == '0') {
					mysql_query("INSERT INTO lkh_bibs(kd_peg, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian) VALUES('$kd_peg', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$kseksi', '$kbidang', '$nominal', '$uraian')");
				} else {
					mysql_query("INSERT INTO lkh_bibs(kd_peg, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian) VALUES('$kd_peg', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$kseksi', '$kbidang', '$rupiah', '$uraian')");
				}
			}
			// else {
			// 	if ($rupiah == '0') {
			// 		mysql_query("UPDATE lkh_bibs SET tgl_transfer='$tgl_transfer', nominal='$nominal' WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'");
			// 	} else {
			// 		mysql_query("UPDATE lkh_bibs SET tgl_transfer='$tgl_transfer', nominal='$rupiah' WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'");
			// 	}
			// }
			
		}
	}
?>
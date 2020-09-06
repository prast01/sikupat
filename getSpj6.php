<?php
	include "koneksi.php";

	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$cari = $_POST['cari'];
		if ($cari == 'kegiatan') {
			$q = mysql_query("SELECT * FROM kegiatan WHERE kseksi='$id'");
			echo "<option value='' selected disabled>--Pilih--</option>";
			while ($a = mysql_fetch_assoc($q)) {
				echo "<option value='$a[id_kegiatan]'>$a[nm_kegiatan]</option>";
			}
		} elseif ($cari == 'det') {
			$q = mysql_query("SELECT * FROM tb_dak_detail WHERE id_spj_dak='$id' AND st='0'");
			// echo "<option value='' selected disabled>--Pilih--</option>";
			while ($a = mysql_fetch_assoc($q)) {
				$str = substr($a['uraian'], 0, 30);
				echo "<option value='$a[id_dak_detail]'>$str</option>";
			}
		} else {
			$q = mysql_query("SELECT * FROM spj_dak WHERE id_kegiatan='$id'");
			echo "<option value='' selected disabled>--Pilih--</option>";
			while ($a = mysql_fetch_assoc($q)) {
				$str = substr($a['uraian_kegiatan'], 0, 30);
				echo "<option value='$a[id_spj]'>($a[kode_rekening]) $str</option>";
			}
		}
		
	}
?>
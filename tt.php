<?php
	include "koneksi.php";

	$q = mysql_query("SELECT * FROM spj_dak");
	while ($d = mysql_fetch_assoc($q)) {
		$kd = $d['kode_rekening'];
		$str = str_replace('.', '', $d['kode_rekening']);
		$id = $d['id_spj'];
		mysql_query("UPDATE spj_dak SET kode_rekening = REPLACE(kode_rekening, '$kd', '$str') WHERE id_spj='$id'");
	}
?>
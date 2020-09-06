<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$q = mysql_query("SELECT * FROM kegiatan WHERE kseksi='$id' OR id_kegiatan IN (SELECT id_kegiatan FROM spj WHERE kbidang2='DK007' AND kseksi='$id' GROUP BY id_kegiatan)");
		echo "<option value='' selected disabled>--Pilih--</option>";
		while ($a = mysql_fetch_assoc($q)) {
			echo "<option value='$a[id_kegiatan]'>$a[nm_kegiatan]</option>";
		}
	}
?>
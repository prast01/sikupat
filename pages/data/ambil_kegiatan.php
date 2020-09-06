<?php
	include "../../koneksi.php";

	if (isset($_GET['ckseksi'])) {
		$id = $_GET['ckseksi'];
		$q = mysql_query("SELECT id_kegiatan, nm_kegiatan FROM kegiatan WHERE kseksi='$id'");
		echo "<option value='' selected disabled>--Pilih--</option>";
		while ($a = mysql_fetch_assoc($q)) {
			echo "<option value='$a[id_kegiatan]'>$a[nm_kegiatan]</option>";
		}
	}
?>
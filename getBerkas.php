<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$q = mysql_query("SELECT a.detail FROM berkas a, spj b WHERE a.kode_rekening=b.kode_rekening AND b.id_spj='$id'");
		$a = mysql_fetch_assoc($q);
		echo "<h3>Berkas yang harus disertakan :</h3>";
		echo $a['detail'];
	}
?>
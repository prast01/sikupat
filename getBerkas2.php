<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$q = mysql_query("SELECT gol FROM spj  WHERE id_spj='$id'");
		$a = mysql_fetch_assoc($q);

		echo $a['gol'];
	}
?>
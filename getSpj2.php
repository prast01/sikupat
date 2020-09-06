<?php
	include "koneksi.php";

	$sblm = mktime(0, 0, 0, date('n'), date('j')-7, date('Y'));

	$tgl = date("Y-m-d", $sblm);

	$sblm2 = mktime(0, 0, 0, date('n'), date('j')-3, date('Y'));

	$tgl2 = date("Y-m-d", $sblm2);

	$query = "UPDATE bibs_copy SET hapus='1' WHERE tgl_kegiatan < '$tgl' AND tgl_transfer ='0000-00-00' AND terima='0' AND jenis='0'";

	mysql_query($query);

	$query2 = "UPDATE bibs_copy SET hapus='1' WHERE tgl_tolak < '$tgl2' AND tgl_transfer ='0000-00-00' AND terima='1' AND tolak='1' AND jenis='0'";

	mysql_query($query2);
?>
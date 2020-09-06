<?php
	include "koneksi.php";

	session_start();
	
	$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
	$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
	$r = mysql_fetch_array($a);
	$kseksi 	= $r['kseksi'];

	$bb = array(
		'01' => '1',
		'02' => '2',
		'03' => '3',
		'04' => '4',
		'05' => '5',
		'06' => '6',
		'07' => '7',
		'08' => '8',
		'09' => '9',
		'10' => '10',
		'11' => '11',
		'12' => '12'
	);
	
	$b = $bb[date('m')];
	$b2 = $bb[date('m')]-1;

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		if ($id != '6') {
			$q = mysql_query("SELECT id_spj, uraian_kegiatan, kode_rekening FROM spj WHERE id_kegiatan='$id' AND kseksi='$kseksi'");
		} else {
			$q = mysql_query("SELECT id_spj, uraian_kegiatan, kode_rekening FROM spj WHERE id_spj IN (SELECT id_spj FROM target_detail WHERE seksi_pl='$kseksi' AND id_keg='6' AND (bulan='$b' OR bulan='$b2'))");
		}
		echo "<option value='' selected disabled>--Pilih--</option>";
		while ($a = mysql_fetch_assoc($q)) {
			echo "<option value='$a[id_spj]'>($a[kode_rekening]) $a[uraian_kegiatan]</option>";
		}
	}
?>
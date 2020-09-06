<?php
	include "koneksi.php";
	session_start();
	
	$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
	$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
	$r = mysql_fetch_array($a);
	$kseksi 	= $r['kseksi'];

	if (isset($_GET['id'])) {
		$bulan = array(
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

		$id = $_GET['id'];
		$id_keg = $_GET['id_keg'];
		$fi = 'b'.$bulan[date('m')];
		$f = $bulan[date('m')]-1;
		$fi2 = 'b'.$f;
		$bl = $bulan[date('m')];
		$bl2 = $bulan[date('m')]-1;

		if ($id_keg != '6') {
			$q = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi='$kseksi' AND bulan='$bl' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi='1' AND id_kegiatan='$id_keg' AND kseksi='$kseksi') AND st='0'");
		} else {
			$q = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi_pl='$kseksi' AND bulan='$bl' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi='1' AND id_kegiatan='$id_keg' AND kseksi='$kseksi') AND st='0'");
		}

		if ($id_keg != '6') {
			$q2 = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi='$kseksi' AND bulan='$bl2' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi2='1' AND id_kegiatan='$id_keg' AND kseksi='$kseksi') AND st='0'");
		} else {
			$q2 = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi_pl='$kseksi' AND bulan='$bl2' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi2='1' AND id_kegiatan='$id_keg' AND kseksi='$kseksi') AND st='0'");
		}

		if ($id_keg != '6') {
			$q3 = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi='$kseksi' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND (st='1' OR st='2')");
		} else {
			$q3 = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi_pl='$kseksi' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$id') AND (st='1' OR st='2')");
		}
		
		// $q = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id' AND seksi='$kseksi' AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi='1' AND id_kegiatan='$id_keg')");
		echo "<option value='' selected disabled>--Pilih--</option>";
		while ($a = mysql_fetch_assoc($q)) {
			echo "<option value='$a[id_target_detail]'>$a[uraian]</option>";
		}
		while ($a2 = mysql_fetch_assoc($q2)) {
			echo "<option value='$a2[id_target_detail]'>$a2[uraian]</option>";
		}
		while ($a3 = mysql_fetch_assoc($q3)) {
			echo "<option value='$a3[id_target_detail]'>$a3[uraian]</option>";
		}
	}
?>
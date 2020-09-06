<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$pegawai = array(
			'5' => "DJ001",
			'6' => "DJ002",
			'7' => "DJ004",
			'8' => "DJ005",
			'9' => "DJ003",
			'10' => "DJ006",
			'11' => "DJ008",
			'12' => "DJ010",
			'13' => "DJ011",
			'14' => "DJ007",
			'15' => "DJ009",
			'16' => "DJ012",
			'17' => "DJ013"
		);
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

		$kseksi = $pegawai[$id];
		$fi = 'b'.$bulan[date('m')];
		$fi2 = 'b'.$bulan[date('m')]+1;
		// $q = mysql_query("SELECT * FROM target_detail WHERE id_spj IN (SELECT id_spj FROM spj WHERE kode_rekening='51215003' OR kode_rekening='51201004' OR kode_rekening='51218001' OR kode_rekening='51218002') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE ($fi='1' OR $fi2='1') AND kseksi='DJ002')");
		
		$q = mysql_query("SELECT * FROM target_detail WHERE id_spj IN (SELECT id_spj FROM spj WHERE (kode_rekening='51215003' OR kode_rekening='51201004' OR kode_rekening='51218001' OR kode_rekening='51218002') AND kbidang2='') AND seksi_pl='$kseksi' AND st='0'");
		$data = array();
		$no = 0;
		while ($h = mysql_fetch_assoc($q)) {
			$data[$no]['rok'] = "APBD-".$h['uraian'];
			$no++;
		}

		// $q2 = mysql_query("SELECT * FROM target_detail WHERE id_spj IN (SELECT id_spj FROM spj WHERE (kode_rekening='51215003' OR kode_rekening='51201004' OR kode_rekening='51218001' OR kode_rekening='51218002') AND kbidang2='DK007') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE ($fi='1' OR $fi2='1') AND kseksi='$kseksi')");

		$q2 = mysql_query("SELECT * FROM target_detail WHERE id_spj IN (SELECT id_spj FROM spj WHERE (kode_rekening='51215003' OR kode_rekening='51201004' OR kode_rekening='51218001' OR kode_rekening='51218002') AND kbidang2='DK007') AND seksi_pl='$kseksi' AND st='0'");

		$no = $no+1;
		while ($h2 = mysql_fetch_assoc($q2)) {
			$data[$no]['rok'] = "DAK-".$h2['uraian'];
			$no++;
		}
		
		echo json_encode($data);
	}
?>
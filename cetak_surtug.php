<?php
	/** format tanggal Indo**/
	
	date_default_timezone_set('Asia/Jakarta');
	function tgl_ind($date) {
	
	/** ARRAY HARI DAN BULAN**/	
			$Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
			$Bulan = array("Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nov","Desember");
			
	/** MEMISAHKAN FORMAT TANGGAL, BULAN, TAHUN, DENGAN SUBSTRING**/		
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl = substr($date, 8, 2);
		$waktu = substr($date, 11, 8);		
		$hari = date("w", strtotime($date));
		
		$result = $tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu."";
		return $result;
		} 
		
	$tgl = date('Y-m-d'); // sesuaikan dari hasil output query select tabel database
	$tanggal = tgl_ind($tgl); // ini adalah kode untuk menampilkan fungsi pada file functiopn_tanggal.php 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Cetak</title>
		<style>
			.footer{
				position : fixed;
				left : 0;
				bottom : 0;
				width : 100%;
				text-align : left;
			}
		</style>
	</head>
	<body onLoad="window.print()" onclick="">
		<table border="0" style="border-collapse:collapse; border-bottom:1px solid" width="100%">
			<thead>
				<tr>
					<th width="25%">
						<img src="img/logo-jepara.png" width="100px" style="padding: 5px 5px 5px 5px; border: 0px solid">
					</th>
					<td colspan="2" align="center">
						<h2 style="margin-bottom:-20px">PEMERINTAH KABUPATEN JEPARA</h2>
						<h1 style="margin-bottom:-10px">DINAS KESEHATAN</h1>
						<p>Jl. Kartini No. 44 Telp. (0291) 591427, 591743 Fax. (0291) 591427<br>
						E-mail : <u>dinkeskabjepara@yahoo.co.id</u><br>
						J E P A R A 59441
						</p>
					</td>
				</tr>
			</thead>
		</table>
		<br>
		<table border="0" width="100%">
			<tr>
				<td align="center" colspan="4"><u>SURAT PERINTAH TUGAS</u><br>
				Nomor :
			  </td>
			</tr>
			<tr>
				<td colspan="4"><br></td>
			</tr>
			<tr>
				<td colspan="4">Yang bertanda tangan dibawah ini:</td>
			</tr>
			<tr>
				<td width="8%"></td><td width="10%">Nama</td>
				<td width="1%">:</td>
				<td width="81%">Mudrikatun, S.SiT, SKM, MM.Kes, MH</td>
			</tr>
			<tr>
				<td></td><td>NIP</td><td>:</td><td>19690610 199003 2 010</td>
			</tr>
			<tr>
				<td></td><td>Jabatan</td><td>:</td><td>Kepala Dinas Kesehatan Kab. Jepara</td>
			</tr>
		</table>
		<br>
		<table border="0" width="100%">
			<tr>
				<td colspan="4">Menugaskan kepada:</td>
			</tr>
			<tr>
				<td width="8%" align="right">1.</td><td width="10%">Nama</td>
				<td width="1%">:</td>
				<td width="81%"></td>
			</tr>
			<tr>
				<td></td><td>Jabatan</td><td>:</td><td></td>
			</tr>
		</table>
		<br>
		<table border="0" width="100%">
			<tr>
				<td colspan="4">Untuk Keperluan</td>
			</tr>
			<tr>
				<td width="8%"></td><td width="10%">Tanggal</td>
				<td width="1%">:</td>
				<td width="81%"></td>
			</tr>
			<tr>
				<td></td><td>Tempat</td><td>:</td><td></td>
			</tr>
		</table>
		<br>
		<br>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="5%"></td>
				<td width="30%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px"></p>
					<p></p>
				</td>
				<td width="26%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px"></p>
					<p></p>
			  </td>
				<td width="39%">
					<p style="margin-bottom:-10px">Jepara, <?php echo $tanggal; ?></p>
					<p style="margin-bottom:-10px">Kepala Dinas Kesehatan</p>
					<p>Kabupaten Jepara</p>
			  </td>
			</tr>
			<tr>
				<td width="5%"></td>
				<td width="30%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>
					<p></p>
				</td>
				<td width="26%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>
					<p></p>
				
			  </td>
				<td>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">Mudrikatun, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>
				</td>
			</tr>
		</table>

	</body>
</html>

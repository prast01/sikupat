<?php
	include "koneksi.php";
	date_default_timezone_set("Asia/Jakarta");
	$bulan = array(
		'1' => 'Januari',
		'2' => 'Februari',
		'3' => 'Maret',
		'4' => 'April',
		'5' => 'Mei',
		'6' => 'Juni',
		'7' => 'Juli',
		'8' => 'Agustus',
		'9' => 'September',
		'10' => 'Oktober',
		'11' => 'November',
		'12' => 'Desember'
	);

	if (isset($_GET['bln'])) {
		$bln = $_GET['bln'];
		//library phpqrcode
		include "assets/phpqrcode/qrlib.php";
		 
		//direktory tempat menyimpan hasil generate qrcode jika folder belum dibuat maka secara otomatis akan membuat terlebih dahulu
		$tempdir = "temp/"; 
		if (!file_exists($tempdir))
			mkdir($tempdir);

		$text = "Dibuat Oleh Si Kupat Dinas Kesehatan Kab. Jepara";

		//namafile setelah jadi qrcode
		$namafile = "ALL-".date("YmdHis").".png";
		//kualitas dan ukuran qrcode
		$quality = 'H'; 
		$ukuran = 4; 
		$padding = 0;
	 
		QRCode::png($text,$tempdir.$namafile,QR_ECLEVEL_H,$ukuran,$padding);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Cetak</title>
		<style>
			@media print {
				.pagebreak {
					clear: both;
					page-break-after: always;
				}
			}
		</style>
	</head>
	<body onload="window.print()" onclick="">
		<?php
			$q = mysql_query("SELECT kseksi, nm_seksi FROM user WHERE level_user='2'");
			while ($data = mysql_fetch_assoc($q)) {
				$q2 = mysql_query("SELECT id_kegiatan, nm_kegiatan FROM kegiatan WHERE kseksi='$data[kseksi]'");
				while ($data2 = mysql_fetch_assoc($q2)) {
		?>
		<table border="0" style="border-collapse:collapse; border-bottom:1px solid" width="100%">
			<thead>
				<tr>
					<th width="25%">
						<!-- <img src="temp/<?php echo $namafile; ?>" width="50px"> -->
						<img src="temp/<?php echo $namafile; ?>" width="100px" style="padding: 5px 5px 5px 5px; border: 1px solid">
					</th>
					<th colspan="2">
						<h4 style="margin-bottom:-20px">PEMERINTAH KABUPATEN JEPARA</h4>
						<h2 style="margin-bottom:-10px">DINAS KESEHATAN</h2>
						<h4 style="margin-bottom:-20px">DATA TARGET BULAN <?php echo strtoupper($bulan[$bln]); ?> TAHUN <?php echo date('Y'); ?></h4>
						<h4>SEMUA SUBAG/SEKSI/UPT</h4>
					</th>
				</tr>
			</thead>
		</table>
		<br>
		<table border="0" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<td width="20%" valign="top">Subag/Seksi/UPT</td>
					<td width="2%" valign="top">:</td>
					<td align="left" valign="top"><?php echo strtoupper($data['nm_seksi']); ?></td>
				</tr>
				<tr>
					<td width="20%" valign="top">Kegiatan</td>
					<td width="2%" valign="top">:</td>
					<td align="left"><?php echo strtoupper($data2['nm_kegiatan']); ?></td>
				</tr>
			</thead>
		</table>
		<br>
		<table border="1" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<th width="15%">No. Rekening</th>
					<th>Uraian</th>
					<th width="15%">Nominal</th>
					<th width="15%">Realisasi</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					$id_keg = $data2['id_kegiatan'];
					$q3 = mysql_query("SELECT * FROM spj WHERE id_kegiatan='$id_keg'");
					while ($data3 = mysql_fetch_assoc($q3)) {
						$id_spj = $data3['id_spj'];
				?>
				<tr>
					<td valign="top"><?php echo $data3['kode_rekening']; ?></td>
					<td valign="top">
						<b><?php echo $data3['uraian_kegiatan']; ?></b><br>
						<?php
							$q4 = mysql_query("SELECT uraian, nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'");
							while ($data4 = mysql_fetch_assoc($q4)) {
						?>
						- <?php echo $data4['uraian']; ?><br>
						<?php
							}
						?>
					</td>
					<td align="right" valign="top">
						<br>
						<?php
							$q5 = mysql_query("SELECT uraian, nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'");
							while ($data5 = mysql_fetch_assoc($q5)) {
								$total = $total+$data5['nominal'];
						?>
						<?php echo number_format($data5['nominal'], 0, ',', '.'); ?><br>
						<?php
							}
						?>
					</td>
					<td></td>
				</tr>
				<?php
					}
				?>
				<tr>
					<th colspan="2" align="right">TOTAL</th>
					<th align="right"><?php echo number_format($total, 0, ',', '.'); ?></th>
					<th></th>
				</tr>
			</tbody>
		</table>
		<div class="pagebreak"></div>
		<?php
				}
			}
		?>
	</body>
</html>
<?php
	}
?>
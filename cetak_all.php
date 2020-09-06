<?php
	include "koneksi.php";
	date_default_timezone_set("Asia/Jakarta");
	$hari = array(
		'0' => 'Minggu',
		'1' => 'Senin',
		'2' => 'Selasa',
		'3' => 'Rabu',
		'4' => 'Kamis',
		'5' => 'Jumat',
		'6' => 'Sabtu'
	);
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

		$text = "Dibuat Dengan Aplikasi Si Kupat Dinas Kesehatan Kab. Jepara pada ".$hari[date('w')].", ".date("d-m-Y H:i:s");

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
				td, th {
					font-size: 12px;
				}
			}
			.footer{
				position : fixed;
				left : 0;
				bottom : 0;
				width : 100%;
				text-align : left;
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
						<img src="temp/<?php echo $namafile; ?>" width="70px" style="padding: 5px 5px 5px 5px; border: 1px solid">
					</th>
					<th colspan="2">
						<h4 style="margin-bottom:-15px">PEMERINTAH KABUPATEN JEPARA</h4>
						<h2 style="margin-bottom:-15px">DINAS KESEHATAN</h2>
						<h4>DATA TARGET BULAN <?php echo strtoupper($bulan[$bln]); ?> TAHUN <?php echo date('Y'); ?></h4>
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
					<th width="10%">Minggu ke</th>
					<th width="15%">Pelaksana</th>
					<th width="15%">Nominal</th>
					<th width="10%">Realisasi</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					$id_keg = $data2['id_kegiatan'];
					$q3 = mysql_query("SELECT * FROM spj a WHERE a.id_kegiatan='$id_keg'");
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
					<td align="center" valign="top">
						<br>
						<?php
							$q6 = mysql_query("SELECT minggu, uraian, nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'");
							while ($data6 = mysql_fetch_assoc($q6)) {
						?>
						<?php echo $data6['minggu']; ?><br>
						<?php
							}
						?>
					</td>
					<td align="center" valign="top">
						<br>
						<?php
							$q7 = mysql_query("SELECT b.nama FROM target_detail a, pegawai b WHERE a.id_spj='$id_spj' AND a.bulan='$bln' AND a.pl=b.kd_peg");
							while ($data7 = mysql_fetch_assoc($q7)) {
						?>
						<?php echo $data7['nama']; ?><br>
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
					<th colspan="4" align="right">TOTAL</th>
					<th align="right"><?php echo number_format($total, 0, ',', '.'); ?></th>
					<th></th>
				</tr>
			</tbody>
		</table>
		<br>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="5%"></td>
				<td width="30%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px">Mengetahui,</p>
					<p>Kasubag/Kasie/Ka. UPT</p>
				</td>
				<td width="30%"></td>
				<td>
					<p style="margin-bottom:-10px">Jepara, <?php echo date("d-m-Y"); ?></p>
					<p style="margin-bottom:-10px">Kepala Dinas Kesehatan</p>
					<p>Kabupaten Jepara</p>
				</td>
			</tr>
			<tr>
				<td width="5%"></td>
				<td width="30%">
					<br>
					<p style="margin-bottom:-10px;">.................................................</p>
					<p>NIP.</p>
				</td>
				<td width="30%"></td>
				<td>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">Mudrikatun, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>
				</td>
			</tr>
		</table>
		<div class="pagebreak"></div>
		<?php
				}
			}
		?>
		<div class="footer">
			<i>Dicetak oleh Aplikasi Sikupat</i>
		</div>
	</body>
</html>
<?php
	}
?>
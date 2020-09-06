<?php
	include "koneksi.php";

	if (isset($_GET['tahun']) && isset($_GET['bulan'])) {
		$tahun = $_GET['tahun'];
		$bulan = $_GET['bulan'];
		$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan order by a.id ASC");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Cetak Registrasi SPJ</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onload="window.print()">
	<div onclick="window.close()">
		<table width="100%" align="center">
			<tr>
				<td colspan="6" align="center" style="font-size: 18px; font: bold;"><u>LAPORAN PENDAFTARAN SPJ</u><br><br><?php echo $tahun."/".$bulan; ?></td>
			</tr>
		</table><br/><br/>
		<table border="1" width="100%" style="border-collapse: collapse;">
			<thead>
				<tr>
					<td style="text-align:center"><font color=""><b>Urutan SPJ</b></font></td>
					<td style="text-align:center"><font color=""><b>Rekening</b></font></td>
					<td style="text-align:center"><font color=""><b>Uraian</b></font></td>
					<td style="text-align:center"><font color=""><b>Nominal</b></font></td>
					<td style="text-align:center"><font color=""><b>Pelaksana</b></font></td>
					<td style="text-align:center"><font color=""><b>Tgl Kegiatan</b></font></td>
					<td style="text-align:center"><font color=""><b>Tgl Transfer</b></font></td>
					<td style="text-align:center"><font color=""><b>Status</b></font></td>
					<td style="text-align:center"><font color=""><b>Catatan</b></font></td>
				</tr>
			</thead>
			<tbody>
				<?php 
					$no = 1;
					while ($hasil = mysql_fetch_array($query)){
						$id = $hasil['id'];
						$kd_transaksi = $hasil['kd_transaksi'];
						if ($hasil['tgl_transfer'] == '0000-00-00') {
							$tgl_transfer = "-";
						} else {
							$tgl_transfer = $hasil['tgl_transfer'];
						}
						$tgl_kegiatan = $hasil['tgl_kegiatan'];
						$nominal = number_format($hasil['nominal'],0,',','.');
						$kode_rek = $hasil['kode_rekening'];
						$uraian = $hasil['uraian'];
				?>		

				<tr>
					<td valign="top" align="center" bgcolor=""><?php echo sprintf("%04s", $id); ?></td>
					<td valign="top" align="left" bgcolor="">
						<?php echo $kode_rek ?>
					</td>
					<td valign="top" align="left" bgcolor=""><?php echo $uraian; ?></td>
					<td valign="top" align="left" bgcolor=""><?php echo $nominal; ?></td>
					<td valign="top" align="left" bgcolor="">
					<?php
							$a = mysql_query("SELECT * FROM bibs_detail a, pegawai b WHERE a.kd_peg=b.kd_peg AND a.kd_transaksi='$kd_transaksi'");
							if (mysql_num_rows($a) > 0) {
								// echo "<ol>";
								while ($da = mysql_fetch_assoc($a)) {
									echo "- ".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']."<br>";
								}
								// echo "</ol>";
							} else {
								echo "-";
							}
					?>
					</td>
					<td valign="top" align="left" bgcolor=""><?php echo $tgl_kegiatan; ?></td>
					<td valign="top" align="left" bgcolor=""><?php echo $tgl_transfer; ?></td>
					<td>
						<?php
							if ($tgl_transfer == '-') {
								if($hasil['validasi'] == '1'){
									echo "Sudah Divalidasi";
								} else {
									if ($hasil['tolak'] == '0') {
										echo "Proses";
									} elseif ($hasil['tolak'] == '1') {
										echo "Ditolak";
									}
								}
							} else {
								echo "Sudah Ditransfer";
							}
						?>
					</td>
					<td valign="top" align="left" bgcolor=""><?php echo $hasil['alasan']; ?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</body>
</html>
<?php
	}
?>
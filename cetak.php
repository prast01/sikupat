<?php
include "koneksi.php";

function Terbilang($x)
{
	$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($x < 12)
		return " " . $abil[$x];
	elseif ($x < 20)
		return Terbilang($x - 10) . "belas";
	elseif ($x < 100)
		return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
	elseif ($x < 200)
		return " seratus" . Terbilang($x - 100);
	elseif ($x < 1000)
		return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
	elseif ($x < 2000)
		return " seribu" . Terbilang($x - 1000);
	elseif ($x < 1000000)
		return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
	elseif ($x < 1000000000)
		return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
	elseif ($x < 1000000000000)
		return Terbilang($x / 1000000000) . " milyar" . Terbilang(fmod($x, 1000000000));
	elseif ($x < 1000000000000000)
		return Terbilang($x / 1000000000000) . " trilyun" . Terbilang(fmod($x, 1000000000000));
}

function TW($bln)
{
	$tw = 0;
	if ($bln < 4) {
		$tw = 1;
	} elseif ($bln < 7) {
		$tw = 2;
	} elseif ($bln < 10) {
		$tw = 3;
	} else {
		$tw = 4;
	}

	return $tw;
}

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

if (isset($_GET['id_keg'])) {
	$ckseksi = $_GET['ckseksi'];
	$id_keg = $_GET['id_keg'];
	$bln = $_GET['bln'];

	if ($_GET['jenis'] == 1) {
		$jenis = "target";
	} else {
		$jenis = "realisasi";
	}

	//library phpqrcode
	include "assets/phpqrcode/qrlib.php";

	//direktory tempat menyimpan hasil generate qrcode jika folder belum dibuat maka secara otomatis akan membuat terlebih dahulu
	$tempdir = "temp/";
	if (!file_exists($tempdir))
		mkdir($tempdir);

	// $url = "http://dkk.sikdkkjepara.net/sikupat/cetak.php?jenis=".$_GET['jenis']."&bln=".$bln."&id_keg=".$id_keg;
	$text = "Dibuat Dengan Aplikasi Si Kupat Dinas Kesehatan Kab. Jepara pada " . $hari[date('w')] . ", " . date("d-m-Y H:i:s");

	//namafile setelah jadi qrcode
	$namafile = $jenis . "-" . date("YmdHis") . ".png";
	//kualitas dan ukuran qrcode
	$quality = 'H';
	$ukuran = 4;
	$padding = 0;

	QRCode::png($text, $tempdir . $namafile, QR_ECLEVEL_H, $ukuran, $padding);

	$d = mysql_fetch_assoc(mysql_query("SELECT a.kseksi, a.nm_seksi, a.eselon2, a.eselon3, a.kd_peg_eselon2, a.kd_peg_eselon3, b.nm_kegiatan FROM user a, kegiatan b WHERE a.kseksi=b.kseksi AND b.id_kegiatan='$id_keg'"));
	$kseksi = $d['kseksi'];
	$nama_seksi = $d['nm_seksi'];
	$nama_keg = $d['nm_kegiatan'];
	$eselon2 = $d['eselon2'];
	$eselon3 = $d['eselon3'];
	$kd_peg_eselon3 = $d['kd_peg_eselon3'];
	$kd_peg_eselon2 = $d['kd_peg_eselon2'];

	if ($kseksi == "DJ014" || $kseksi == "DJ015" || $kseksi == "DJ016") {
		$g = mysql_fetch_assoc(mysql_query("SELECT * FROM user WHERE kseksi='$ckseksi'"));
		$kseksi = $g['kseksi'];
		$nama_seksi = $g['nm_seksi'];
		$eselon2 = $g['eselon2'];
		$eselon3 = $g['eselon3'];
		$kd_peg_eselon3 = $g['kd_peg_eselon3'];
		$kd_peg_eselon2 = $g['kd_peg_eselon2'];
	}

	$e = mysql_fetch_assoc(mysql_query("SELECT * FROM pegawai WHERE kd_peg='$kd_peg_eselon3'"));
	$nama = $e['nama'];
	$nip = $e['nip'];
	$gel_dep = $e['gel_dep'];
	$gel_bel = $e['gel_bel'];

	$f = mysql_fetch_assoc(mysql_query("SELECT * FROM pegawai WHERE kd_peg='$kd_peg_eselon2'"));
	$nama2 = $f['nama'];
	$nip2 = $f['nip'];
	$gel_dep2 = $f['gel_dep'];
	$gel_bel2 = $f['gel_bel'];

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Cetak</title>
		<style>
			.footer {
				position: fixed;
				left: 0;
				bottom: 0;
				width: 100%;
				text-align: left;
			}

			td {
				font-size: 14px
			}
		</style>
	</head>

	<body onLoad="window.print()" onclick="">
		<table border="0" style="border-collapse:collapse; border-bottom:1px solid" width="100%">
			<thead>
				<tr>
					<th width="25%">
						<!-- <img src="temp/<?php echo $namafile; ?>" width="50px"> -->
						<img src="temp/<?php echo $namafile; ?>" width="80px" style="padding: 5px 5px 5px 5px; border: 1px solid">
					</th>
					<th colspan="2">
						<h4 style="margin-bottom:-20px">PEMERINTAH KABUPATEN JEPARA</h4>
						<h2 style="margin-bottom:-10px">DINAS KESEHATAN</h2>
						<h4>DATA <?php echo strtoupper($jenis); ?> ROK BULAN <?php echo strtoupper($bulan[$bln]); ?> TAHUN <?php echo '2020'; ?></h4>
					</th>
				</tr>
			</thead>
		</table>
		<br>
		<table border="0" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<td width="20%" valign="top"><?php //echo $eselon3; 
													?>Subag/Seksi/UPT</td>
					<td width="2%" valign="top">:</td>
					<td align="left" valign="top"><?php echo strtoupper($nama_seksi); ?></td>
				</tr>
				<tr>
					<td width="20%" valign="top">Kegiatan</td>
					<td width="2%" valign="top">:</td>
					<td align="left"><?php echo strtoupper($nama_keg); ?></td>
				</tr>
			</thead>
		</table>
		<br>
		<table border="1" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<th>
						<table width="1300" border="0" style="border-collapse:collapse">
							<tr>
								<td width="750">Rek/Uraian</td>
								<td width="50" align="center" valign="top" style="border-left:1px solid black">Mgg ke</td>
								<td width="200" style="border-left:1px solid black; padding-left: 5px">Pelaksana</td>
								<td width="100" style="border-left:1px solid black; padding-right: 5px">Rp</td>
								<td width="200" style="border-left:1px solid black; padding-left: 5px">Ket</td>
							</tr>
						</table>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				// if ($ckseksi == 'DJ002' && $id_keg == '6') {
				// 	$q = mysql_query("SELECT * FROM spj a WHERE a.id_kegiatan='$id_keg' AND a.kseksi='$ckseksi' AND a.id_spj != '53' AND a.id_spj != '57' AND a.id_spj != '58'");
				// } else {
				$q = mysql_query("SELECT * FROM spj a WHERE a.id_kegiatan='$id_keg' AND a.kseksi='$ckseksi'");
				// }

				while ($data = mysql_fetch_assoc($q)) {
				?>
					<tr>
						<td valign="top">
							<table width="1300" border="0" style="border-collapse:collapse">
								<tr>
									<td width="750">
										<b><?php echo $data['kode_rekening']; ?> -
											<?php echo $data['uraian_kegiatan']; ?></b>
									</td>
									<td width="50" align="center" style=""></td>
									<td width="200" align="center" style=""></td>
									<td width="100" style=""></td>
									<td width="200" style=""></td>
								</tr>
							</table>

							<table width="1300" border="0" style="border-collapse:collapse">
								<?php
								if ($id_keg != '6') {
									$q2 = mysql_query("SELECT b.nama,a.uraian,a.minggu,a.nominal,a.ket FROM target_detail a, pegawai b WHERE a.id_spj='$data[id_spj]' AND a.bulan='$bln' AND a.pl=b.kd_peg");
								} else {
									$q2 = mysql_query("SELECT b.nama,a.uraian,a.minggu,a.nominal,a.ket FROM target_detail a, pegawai b WHERE a.id_spj='$data[id_spj]' AND a.bulan='$bln' AND a.pl=b.kd_peg AND a.seksi_pl='DJ002'");
								}

								while ($data2 = mysql_fetch_assoc($q2)) {
									$total = $total + $data2['nominal'];
								?>
									<tr>
										<td width="750" valign="top">- <?php echo $data2['uraian']; ?></td>
										<td width="50" valign="top" align="center" style="border-left:1px solid black"><?php echo $data2['minggu']; ?></td>
										<td width="200" valign="top" style="border-left:1px solid black; padding-left: 5px"><?php echo ucwords($data2['nama']); ?></td>
										<td width="100" valign="top" align="right" style="border-left:1px solid black; padding-right: 5px"><?php echo number_format($data2['nominal'], 0, ',', '.'); ?></td>
										<td width="200" valign="top" style="border-left:1px solid black; padding-left: 5px"><?php echo $data2['ket']; ?></td>
									</tr>
								<?php
								}
								?>
							</table>
						</td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="1" align="center">
						<table width="1300" border="0" style="border-collapse:collapse">
							<tr>
								<td colspan="5" width="1300" align="center"><b>Total <?php echo number_format($total, 0, ',', '.'); ?> (<?php echo ucwords(Terbilang($total)); ?> Rupiah)</b></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
			<!-- <tfoot>
				<tr>
					<th colspan="4" align="right">TOTAL</th>
					<th align="right"><?php echo number_format($total, 0, ',', '.'); ?></th>
					<th></th>
				</tr>
			</tfoot> -->
		</table>
		<br>
		<?php
		$a = mysql_num_rows(mysql_query("SELECT * FROM target_detail WHERE bulan='$bln' and seksi_pl='$ckseksi' and id_keg='6' and seksi='DJ002' and id_keg_pl='$id_keg'"));
		$s = mysql_fetch_assoc(mysql_query("SELECT nm_kegiatan FROM kegiatan WHERE id_kegiatan='6'"));

		if ($a == "0") {
		} else {
			if ($ckseksi != 'DJ002') {
		?>
				<table border="0" style="border-collapse:collapse;" width="100%">
					<thead>
						<tr>
							<td width="20%" valign="top">Kegiatan</td>
							<td width="2%" valign="top">:</td>
							<td align="left"><?php echo strtoupper($s['nm_kegiatan']); ?></td>
						</tr>
					</thead>
				</table>
				<br>
				<table border="1" style="border-collapse:collapse;" width="100%">
					<thead>
						<tr>
							<th width="">Rek/Uraian</th>
							<th width="3%">Mgg</th>
							<th width="">Pelaksana</th>
							<th width="5%">Rp</th>
							<th width="20%">Ket</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE bulan='$bln' and seksi_pl='$ckseksi' and id_keg='6' and seksi='DJ002' and id_keg_pl='$id_keg'"));
						if ($d['nominal'] == '') {
							$nom = 0;
						} else {
							$nom = $d['nominal'];
						}
						$q = mysql_query("SELECT * FROM target_detail a WHERE a.bulan='$bln' and a.seksi_pl='$ckseksi' and a.id_keg='6' and a.seksi='DJ002' and a.id_keg_pl='$id_keg' AND st='0' ORDER BY a.id_spj ASC");
						$total = 0;
						while ($data = mysql_fetch_assoc($q)) {
							if ($data['minggu'] != '') {
								$waktu = $data['minggu'];
							} else {
								$waktu = '';
							}
							$q4 = mysql_fetch_assoc(mysql_query("SELECT nama FROM pegawai WHERE kd_peg='$data[pl]'"));
							$q5 = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$data[id_spj]'"));
						?>

							<tr>
								<td valign="top"><b><?php echo $q5['kode_rekening']; ?> - <?php echo $q5['uraian_kegiatan']; ?></b><br><?php echo $data['uraian']; ?></td>
								<td align="center" valign="top"><?php echo $waktu; ?></td>
								<td align="center" valign="top"><?php echo ucwords($q4['nama']); ?></td>
								<td align="right" valign="top" style="padding-right: 5px"><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
								<td valign="top" style="padding-left: 5px"><?php echo $data['ket']; ?></td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td colspan="5" align="center"><b>Total <?php echo number_format($nom, 0, ',', '.'); ?> (<?php echo ucwords(Terbilang($nom)); ?> Rupiah) </b></td>
						</tr>
					</tbody>
				</table>
		<?php
			}
		}
		?>
		<br>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="2%" rowspan="2"></td>
				<td width="30%" rowspan="2">
					<table width="100%" height="150" border="1" style="border-collapse:collapse">
						<tr>
							<td align="center" colspan="4"><b>PERSETUJUAN</b></td>
						</tr>
						<tr>
							<td width="10%" align="center"><b>No</b></td>
							<td width="60%" align="center"><b>JABATAN</b></td>
							<td align="center" colspan="2"><b>PARAF</b></td>
						</tr>
						<?php if ($ckseksi != "DJ001") { ?>
							<tr>
								<td align="center">1</td>
								<td>Sekretaris Dinas Kesehatan</td>
								<td></td>
								<td>1.</td>
							</tr>
						<?php } ?>
						<tr>
							<td align="center"><?php if ($ckseksi == "DJ001") { ?>1<?php } else { ?>2<?php } ?></td>
							<td><?php echo $eselon2; ?></td>
							<td><?php if ($ckseksi == "DJ001") { ?>1.<?php } else { ?>2.<?php } ?></td>
							<td></td>
						</tr>

						<tr>
							<td align="center"><?php if ($ckseksi == "DJ001") { ?>2<?php } else { ?>3<?php } ?></td>
							<td><?php echo $eselon3 . " " . $nama_seksi; ?></td>
							<td></td>
							<td><?php if ($ckseksi == "DJ001") { ?>2<?php } else { ?>3.<?php } ?></td>
						</tr>
						<?php if ($ckseksi != "DJ001") { ?>
							<tr>
								<td align="center">4</td>
								<td>Kasubag Renval Keuangan</td>
								<td>4.</td>
								<td></td>
							</tr>
						<?php } ?>
					</table>


				</td>
				<td width="4%" rowspan="2"></td>
				<td width="30%" rowspan="2">
					<?php
					$tri = "tw" . TW($bln);
					$t = TW($bln) - 1;
					$tri2 = "tw" . $t;

					// triwulan
					if ($t == 0) {
						$x = mysql_fetch_assoc(mysql_query("SELECT $tri FROM tb_tw WHERE id_kegiatan='$id_keg'"));
						$nom_tw = $x[$tri];
					} else {
						$x = mysql_fetch_assoc(mysql_query("SELECT $tri, $tri2 FROM tb_tw WHERE id_kegiatan='$id_keg'"));
						$nom_tw = $x[$tri] + $x[$tri2];
					}

					//realisasi
					// $w = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) as nominal FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND MONTH(a.tgl_transfer) <= '$bln' AND a.tgl_transfer != '0000-00-00' AND a.kseksi='$ckseksi' AND b.id_kegiatan = '$id_keg'"));
					$w = mysql_fetch_assoc(mysql_query("SELECT total_realisasi FROM total_realisasi WHERE kseksi='$ckseksi' AND id_kegiatan='$id_keg'"));
					$w2 = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) as total FROM spj WHERE kseksi='$ckseksi' AND id_kegiatan='$id_keg' GROUP BY id_kegiatan"));
					$nom_real = $w['total_realisasi'] + $w2['total'];

					$nom_sel = $nom_tw - $nom_real;
					?>
					<table width="100%" height="150" border="1" style="border-collapse:collapse">
						<tr>
							<td align="center" colspan="2"><b>TRI WULAN KE - <?php echo TW($bln); ?></b></td>
						</tr>
						<tr>
							<td width="35%">Target</td>
							<td align="right"><?php echo number_format($nom_tw, 0, ',', '.'); ?></td>
						</tr>
						<tr>
							<td width="35%">Realisasi</td>
							<td align="right"><?php echo number_format($nom_real, 0, ',', '.'); ?></td>
						</tr>
						<tr>
							<td width="35%">Selisih</td>
							<td align="right"><?php echo number_format($nom_sel, 0, ',', '.'); ?></td>
						</tr>
					</table>
				</td>
				<td width="4%" rowspan="2"></td>
				<td width="30%">
					<p style="margin-bottom:-10px">Jepara, <?php echo date("d-m-Y"); ?></p>
					<p style="margin-bottom:-10px">Kepala Dinas Kesehatan</p>
					<p>Kabupaten Jepara</p>
				</td>
			</tr>
			<tr>
				<td>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">MUDRIKATUN, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>
				</td>
			</tr>
		</table>
		<?php
		if ($ckseksi == 'DJ002' && $id_keg == '6') {
		?>
			<!-- <br>
		<table border="0" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<td width="20%" valign="top">Kegiatan</td>
					<td width="2%" valign="top">:</td>
					<td align="left"><?php echo strtoupper($nama_keg); ?></td>
				</tr>
			</thead>
		</table>
		<br>
		<table border="1" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<th>
						<table width="1300" border="0" style="border-collapse:collapse">
							<tr>
								<td width="750">Rek/Uraian</td>
								<td width="50" align="center" valign="top" style="border-left:1px solid black">Mgg ke</td>
								<td width="200" style="border-left:1px solid black; padding-left: 5px">Pelaksana</td>
								<td width="100" style="border-left:1px solid black; padding-right: 5px">Rp</td>
								<td width="200" style="border-left:1px solid black; padding-left: 5px">Ket</td>
							</tr>
						</table>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				$q3 = mysql_query("SELECT * FROM spj a WHERE a.id_kegiatan='6' AND a.kseksi='DJ002' AND (a.gol = '1' OR a.gol = '2')");
				while ($data = mysql_fetch_assoc($q3)) {
				?>
				<tr>
					<td valign="top">
						<table width="1300" border="0" style="border-collapse:collapse">
							<tr>
								<td width="750">
								<b><?php echo $data['kode_rekening']; ?> - 
								<?php echo $data['uraian_kegiatan']; ?></b>
								</td>
								<td width="50" align="center" style=""></td>
								<td width="200" align="center" style=""></td>
								<td width="100" style=""></td>
								<td width="200" style=""></td>
							</tr>
						</table>
						
						<table width="1300" border="0" style="border-collapse:collapse">
							<?php
							$q2 = mysql_query("SELECT b.nama,a.uraian,a.minggu,a.nominal,a.ket FROM target_detail a, pegawai b WHERE a.id_spj='$data[id_spj]' AND a.bulan='$bln' AND a.pl=b.kd_peg");
							while ($data2 = mysql_fetch_assoc($q2)) {
								$total = $total + $data2['nominal'];
							?>
							<tr>
								<td width="750" valign="top">- <?php echo $data2['uraian']; ?></td>
								<td width="50" valign="top" align="center" style="border-left:1px solid black"><?php echo $data2['minggu']; ?></td>
								<td width="200" valign="top" style="border-left:1px solid black; padding-left: 5px"><?php echo ucwords($data2['nama']); ?></td>
								<td width="100" valign="top" align="right" style="border-left:1px solid black; padding-right: 5px"><?php echo number_format($data2['nominal'], 0, ',', '.'); ?></td>
								<td width="200" valign="top" style="border-left:1px solid black; padding-left: 5px"><?php echo $data2['ket']; ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="1" align="center">
						<table width="1300" border="0" style="border-collapse:collapse">
							<tr>
								<td colspan="5" width="1300" align="center"><b>Total <?php echo number_format($total, 0, ',', '.'); ?> (<?php echo ucwords(Terbilang($total)); ?> Rupiah)</b></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="5%"></td>
				<td width="34%" rowspan="2">
					<table width="100%" height="150" border="1" style="border-collapse:collapse">
						<tr>
							<td align="center" colspan="4"><b>PERSETUJUAN</b></td>
						</tr>
						<tr>
							<td width="10%" align="center"><b>No</b></td>
							<td width="60%" align="center"><b>JABATAN</b></td>
							<td align="center" colspan="2"><b>PARAF</b></td>
						</tr>
						<?php if ($ckseksi != "DJ001") { ?>
						<tr>
							<td align="center">1</td><td>Sekretaris Dinas Kesehatan</td><td></td><td>1.</td>
						</tr>
						<?php } ?>
						<tr>
							<td align="center"><?php if ($ckseksi == "DJ001") { ?>1<?php } else { ?>2<?php } ?></td><td><?php echo $eselon2; ?></td><td><?php if ($ckseksi == "DJ001") { ?>1.<?php } else { ?>2.<?php } ?></td><td></td>
						</tr>
						
						<tr>
							<td align="center"><?php if ($ckseksi == "DJ001") { ?>2<?php } else { ?>3<?php } ?></td><td><?php echo $eselon3 . " " . $nama_seksi; ?></td><td></td><td><?php if ($ckseksi == "DJ001") { ?>2<?php } else { ?>3.<?php } ?></td>
						</tr>
						<?php if ($ckseksi != "DJ001") { ?>
						<tr>
							<td align="center">4</td><td>Kasubag Renval Keuangan</td><td>4.</td><td></td>
						</tr>
						<?php } ?>
					</table>		
				
				
				</td>
				<td width="26%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px"></p>
					<p></p>				</td>
				<td width="35%">
					<p style="margin-bottom:-10px">Jepara, <?php echo date("d-m-Y"); ?></p>
					<p style="margin-bottom:-10px">Kepala Dinas Kesehatan</p>
					<p>Kabupaten Jepara</p>				</td>
			</tr>
			<tr>
				<td width="5%"></td>
				<td width="26%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"></p>				</td>
				<td>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">MUDRIKATUN, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>				</td>
			</tr>
		</table> -->
		<?php
		}
		?>
	</body>

	</html>
<?php
}
?>
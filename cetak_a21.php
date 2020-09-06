<?php
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

if (isset($_GET['id'])) {
	include "koneksi.php";
	$id = $_GET['id'];

	// foto
	$foto[0] = '';
	$foto[1] = '';
	$foto[2] = '';
	$foto[3] = '';
	$q = mysql_query("SELECT * FROM file_berkas WHERE kd_transaksi='$id'");
	$i = 0;
	while ($d = mysql_fetch_assoc($q)) {
		$src = "pages/data/file_kegiatan/" . $d['berkas'];
		$foto[$i] = $src;
		$i++;
	}

	// DPA
	$data = mysql_fetch_assoc(mysql_query("SELECT * FROM bibs a, spj b, kegiatan c WHERE a.id_spj=b.id_spj AND b.id_kegiatan=c.id_kegiatan AND a.kd_transaksi='$id'"));
	$nominal = Terbilang($data['nominal']) . " Rupiah";
	$nomor = sprintf("%05s", $data['id']) . '-2';
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>CETAK A2-1</title>
		<style>
			#garis {
				border-bottom: 1px dashed black;
				margin-top: 2px;
				margin-bottom: 10px;
				text-align: center;
			}

			#potong {
				margin-top: 0px;
				margin-bottom: -10px;
				font-family: "Courier New";
			}

			#garis2 {
				border-bottom: 1px dashed black;
			}

			#potong2 {
				margin-top: 0px;
				margin-bottom: 0px;
				text-align: justify;
			}

			#potong3 {
				margin-top: 0px;
				margin-bottom: 0px;
			}

			body {
				font-size: 11pt
			}

			td {
				font-family: "Courier New";
				font-size: 10pt
			}

			th {
				font-family: "Courier New";
			}
		</style>
	</head>

	<body onload="window.print()">
		<div style="text-align: right;">
			<p id="potong">A2-1</p>
		</div>
		<table border="0" width="100%">
			<tr>
				<th colspan="3" align="center">
					<h2 style="margin-bottom: 0px; margin-top: 5px">PEMERINTAH KABUPATEN JEPARA</h2>
				</th>
			</tr>
			<tr>
				<td width="20%" valign="top">Nama Kegiatan</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2"><?php echo $data['nm_kegiatan']; ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">Rekening</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2">10201.<?php echo $data['kode'] . "." . $data['kode_rekening'] . " - " . $data['uraian_kegiatan']; ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">Tahun Anggaran</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2"><?php echo date("Y"); ?></p>
					</div>
				</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<th colspan="3" align="center">
					<h2 style="margin-bottom: 0px; text-decoration: underline; margin-top: 5px">TANDA BUKTI PENGELUARAN</h2>
				</th>
			</tr>
			<tr>
				<td width="20%" valign="top">Sudah terima dari</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2">PEMERINTAH KABUPATEN JEPARA</p>
					</div>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">Uang sejumlah</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2"><?php echo ucwords($nominal); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">Untuk</td>
				<td width="2%" valign="top">:</td>
				<td valign="top">
					<div id="garis2">
						<p id="potong2"><?php echo $data['uraian']; ?></p>
					</div>
				</td>
			</tr>
		</table>
		<br>
		<table border="0" width="100%">
			<tr>
				<td width="48%" valign="top">
					<table width="100%" style="border-bottom: 3px solid black; border-top: 3px solid black;">
						<tr>
							<td width="45%" style="font-size: 15pt" valign="top">
								TERBILANG Rp
							</td>
							<td align="right" style="font-size: 15pt" valign="top"><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
						</tr>
					</table>
				</td>
				<td width="1%">&nbsp;</td>
				<td>
					<p id="potong2">Jepara, </p>
					<p id="potong2">Yang berhak menerima</p>
					<table border="0" width="100%">
						<tr>
							<td width="30%">Tanda tangan</td>
							<td width="5%">:</td>
							<td>
								<div id="garis2">
									&nbsp;
								</div>
							</td>
						</tr>
						<tr>
							<td width="30%">Nama</td>
							<td width="5%">:</td>
							<td>
								<div id="garis2">
									&nbsp;
								</div>
							</td>
						</tr>
						<tr>
							<td width="30%">Alamat</td>
							<td width="5%">:</td>
							<td>
								<div id="garis2">
									&nbsp;
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<td width="50%" align="center">
					<p id="potong3">Setuju dibayarkan,</p>
					<p id="potong3">Pengguna Anggaran</p>
					<br><br><br>
					<p id="potong3" style="text-decoration: underline;">MUDRIKATUN, S.SiT,SKM,MM.Kes,MH</p>
					<p id="potong3">NIP. 19690610 199003 2 010</p>
				</td>
				<td width="50%" align="center">
					<p id="potong3">&nbsp;</p>
					<p id="potong3">Bendahara Pengeluaran</p>
					<br><br><br>
					<p id="potong3" style="text-decoration: underline;">SITI CHOTIMATUZ ZAROCH, SE</p>
					<p id="potong3">NIP. 19700611 200701 2 006</p>
				</td>
			</tr>
		</table>
		<p id="potong3">No. daftar: <?php echo $nomor; ?></p>
		<div id="garis">
			<p id="potong">Potong Disini</p>
		</div>
		<ul>
			<li>LAMPIRAN FOTO</li>
		</ul>
		<table border="1" width="100%" style="border-collapse: collapse">
			<tr>
				<td width="50%" align="center" style="vertical-align: middle">
					<?php
					if ($foto[0] != '') {
					?>
						<img src="<?php echo $foto[0]; ?>" style="width: 350px; height: 250px">
					<?php
					} else {
						echo "-";
					}
					?>
				</td>
				<td width="50%" align="center" style="vertical-align: middle">
					<?php
					if ($foto[1] != '') {
					?>
						<img src="<?php echo $foto[1]; ?>" style="width: 350px; height: 250px">
					<?php
					} else {
						echo "-";
					}
					?>
				</td>
			</tr>
			<tr>
				<td width="50%" align="center" style="vertical-align: middle">
					<?php
					if ($foto[2] != '') {
					?>
						<img src="<?php echo $foto[2]; ?>" style="width: 350px; height: 250px">
					<?php
					} else {
						echo "-";
					}
					?>
				</td>
				<td width="50%" align="center" style="vertical-align: middle">
					<?php
					if ($foto[3] != '') {
					?>
						<img src="<?php echo $foto[3]; ?>" style="width: 350px; height: 250px">
					<?php
					} else {
						echo "-";
					}
					?>
				</td>
			</tr>
		</table>
	</body>

	</html>
<?php
}
?>
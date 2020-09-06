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
			return Terbilang($x / 1000000000) . " milyar" . Terbilang(fmod($x,1000000000));
		elseif ($x < 1000000000000000)
			return Terbilang($x / 1000000000000) . " trilyun" . Terbilang(fmod($x,1000000000000));
	}

	if (isset($_GET['id'])) {
		include "koneksi.php";
		$id = $_GET['id'];
		// DPA
		$data = mysql_fetch_assoc(mysql_query("SELECT * FROM bibs a, spj b, kegiatan c, user d WHERE a.id_spj=b.id_spj AND b.id_kegiatan=c.id_kegiatan AND a.kseksi=d.kseksi AND a.kd_transaksi='$id'"));
		$nominal = Terbilang($data['nominal'])." Rupiah";
		$nomor = sprintf("%05s", $data['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>CETAK FORM VERIFIKASI</title>
	<style>
		#garis {
			border : 1px solid black;
			padding-left: 5px;
			border-radius: 5px;
		}
		#garis2 {
			border-bottom : 1px dashed black;
		}
		td {
			font-family: "Arial";
			font-size: 9pt
		}
		body{
			margin: 0mm 0mm 0mm 0mm;
		}
	</style>
</head>
<body onload="window.print()">
	<table border="0" width="100%">
		<tr>
			<td width="20%">&nbsp;</td>
			<td align="center" width="60%">
				<table border="0" width="100%">
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="70%" align="center">FORMULIR VERIFIKASI SPJ</td>
						<td>
							<div id="garis">
								No. <?php echo $nomor; ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%">Nama Kegiatan</td>
						<td width="2%">:</td>
						<td>
							<div id="garis2">
							10201.<?php echo $data['kode'].".".$data['kode_rekening']; ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%">Uraian</td>
						<td width="2%">:</td>
						<td>
							<div id="garis2">
							<?php echo substr($data['uraian'], 0, 40); ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%">Nominal</td>
						<td width="2%">:</td>
						<td>
							<div id="garis2">
							<?php echo number_format($data['nominal'], 0, ',', '.'); ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%">Tgl Terima SPJ</td>
						<td width="2%">:</td>
						<td>
							<div id="garis2">
							<?php echo $data['tgl_terima']; ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%">Seksi</td>
						<td width="2%">:</td>
						<td>
							<div id="garis2">
							<?php echo $data['nm_seksi']; ?>
							</div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="25%" valign="top">Hasil</td>
						<td width="2%" valign="top">:</td>
						<td>
							<div style="height: 360px"></div>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td colspan="3">
							<b>JANGAN DIBUANG...!!! DIBUANG SAYANG...!!!</b>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
				</table>
			</td>
			<td width="20%">&nbsp;</td>
		</tr>
	</table>
</body>
</html>
<?php
	}
?>
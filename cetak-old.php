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
		return Terbilang($x / 1000000000) . " milyar" . Terbilang(fmod($x,1000000000));
	  elseif ($x < 1000000000000000)
		return Terbilang($x / 1000000000000) . " trilyun" . Terbilang(fmod($x,1000000000000));
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
		$text = "Dibuat Dengan Aplikasi Si Kupat Dinas Kesehatan Kab. Jepara pada ".$hari[date('w')].", ".date("d-m-Y H:i:s");

		//namafile setelah jadi qrcode
		$namafile = $jenis."-".date("YmdHis").".png";
		//kualitas dan ukuran qrcode
		$quality = 'H'; 
		$ukuran = 4; 
		$padding = 0;
	 
		QRCode::png($text,$tempdir.$namafile,QR_ECLEVEL_H,$ukuran,$padding);

		$d = mysql_fetch_assoc(mysql_query("SELECT a.kseksi, a.nm_seksi, a.eselon2, a.eselon3, a.kd_peg_eselon2, a.kd_peg_eselon3, b.nm_kegiatan FROM user a, kegiatan b WHERE a.kseksi=b.kseksi AND b.id_kegiatan='$id_keg'"));
		$kseksi = $d['kseksi'];
		$nama_seksi = $d['nm_seksi'];
		$nama_keg = $d['nm_kegiatan'];
		$eselon2 = $d['eselon2'];
		$eselon3 = $d['eselon3'];
		$kd_peg_eselon3 = $d['kd_peg_eselon3'];
		$kd_peg_eselon2 = $d['kd_peg_eselon2'];
		
		if($kseksi=="DJ014" || $kseksi=="DJ015" || $kseksi=="DJ016"){
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
			.footer{
				position : fixed;
				left : 0;
				bottom : 0;
				width : 100%;
				text-align : left;
			}
			td {
				font-size : 11px
			}
		</style>
	</head>
	<body onLoad="window.print()" onclick="">
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
						<h4>DATA <?php echo strtoupper($jenis); ?> ROK BULAN <?php echo strtoupper($bulan[$bln]); ?> TAHUN <?php echo '2020'; ?></h4>
					</th>
				</tr>
			</thead>
		</table>
		<br>
		<table border="0" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<td width="20%" valign="top"><?php //echo $eselon3; ?>Subag/Seksi/UPT</td>
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
					<th width="6%">Rek</th>
					<th width="">Uraian</th>
					<th width="5%">Mgg ke</th>
					<th width="">Pelaksana</th>
					<th width="5%">Rp</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					$q = mysql_query("SELECT * FROM spj a WHERE a.id_kegiatan='$id_keg' AND a.kseksi='$ckseksi'");
					while ($data = mysql_fetch_assoc($q)) {
				?>
				<tr>
					<td valign="top"><?php echo $data['kode_rekening']; ?></td>
					<td valign="top">
						<b><?php echo $data['uraian_kegiatan']; ?></b>
					<?php
						$q2 = mysql_query("SELECT uraian FROM target_detail WHERE id_spj='$data[id_spj]' AND bulan='$bln'");
						while ($data2 = mysql_fetch_assoc($q2)) {
					?>
						<br>- <?php echo $data2['uraian']; ?>
					<?php
						}
					?>
					</td>
					<td align="center" valign="top">
					<br>
					<?php
						$q4 = mysql_query("SELECT minggu FROM target_detail WHERE id_spj='$data[id_spj]' AND bulan='$bln'");
						while ($data4 = mysql_fetch_assoc($q4)) {
					?>
						<?php echo $data4['minggu']; ?><br>
					<?php
						}
					?>
					</td>
					<td align="center" valign="top">
					<br>
					<?php
						$q5 = mysql_query("SELECT b.nama FROM target_detail a, pegawai b WHERE a.id_spj='$data[id_spj]' AND a.bulan='$bln' AND a.pl=b.kd_peg");
						while ($data5 = mysql_fetch_assoc($q5)) {
					?>
						 <?php echo ucwords($data5['nama']); ?><br>
					<?php
						}
					?>
					</td>
					<td align="right" valign="top">
					<br>
					<?php
						$q3 = mysql_query("SELECT nominal FROM target_detail WHERE id_spj='$data[id_spj]' AND bulan='$bln'");
						while ($data3 = mysql_fetch_assoc($q3)) {
							$total = $total+$data3['nominal'];
					?>
						<?php echo number_format($data3['nominal'], 0, ',', '.'); ?><br>
					<?php
						}
					?>
					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td colspan="3" align="center"><b>Terbilang : <?php echo ucwords(Terbilang($total)); ?> Rupiah </b></td>
					<td align="right"><b>Total</b></td>
					<td align="right"><b><?php echo number_format($total, 0, ',', '.'); ?></b></td>
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
			$a = mysql_num_rows(mysql_query("SELECT * FROM target_detail WHERE bulan='$bln' and seksi_pl='$ckseksi' and id_keg='$id_keg' and seksi='DJ002'"));
			if($a=="0"){
			
			}else{
		?>
		
		<table border="1" style="border-collapse:collapse;" width="100%">
			<thead>
				<tr>
					<th width="6%">Rek</th>
					<th width="">Uraian</th>
					<th width="5%">Mgg ke</th>
					<th width="">Pelaksana</th>
					<th width="5%">Rp</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE bulan='$bln' and seksi_pl='$ckseksi' and id_keg='$id_keg' and seksi='DJ002'"));
					if ($d['nominal'] == '') {
						$nom = 0;
					} else {
						$nom = $d['nominal'];
					}
					$q = mysql_query("SELECT * FROM target_detail a WHERE a.bulan='$bln' and a.seksi_pl='$ckseksi' and a.id_keg='$id_keg' and a.seksi='DJ002'");
					$total=0;
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
					<td valign="top"><?php echo $q5['kode_rekening']; ?></td>
					<td valign="top"><?php echo $data['uraian']; ?></td>
					<td align="center" valign="top"><?php echo $waktu; ?></td>
					<td align="center" valign="top"><?php echo ucwords($q4['nama']); ?></td>
					<td align="right"><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="3" align="center"><b>Terbilang : <?php echo ucwords(Terbilang($nom)); ?> Rupiah </b></td>
					<td align="right"><b>Total</b></td>
					<td align="right"><b><?php echo number_format($nom, 0, ',', '.'); ?></b></td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<br>
		<table border="0" width="100%">
			<tr style="height: 10px">
				<td width="5%"></td>
				<td width="30%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px"></p>
					<p><?php echo $eselon3." ".$nama_seksi; ?></p>
				</td>
				<td width="30%">
					<p style="margin-bottom:-10px">&nbsp;</p>
					<p style="margin-bottom:-10px"></p>
					<p><?php echo $eselon2; ?></p>
				</td>
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
					<p style="margin-bottom:-10px; text-decoration: underline"><?php echo $gel_dep." "; ?><?php echo $nama; ?>, <?php echo $gel_bel; ?></p>
					<p>NIP. <?php echo $nip; ?></p>
				</td>
				<td width="30%">
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline"><?php echo $gel_dep2." "; ?><?php echo $nama2.""; ?><?php if($kseksi!="DJ012" && $kseksi!="DJ013"){ ?>, <?php } ?> <?php echo $gel_bel2; ?></p>
					<p><?php if($kseksi!="DJ012" && $kseksi!="DJ013"){ ?>NIP.<?php } ?> <?php echo $nip2; ?></p>
				
				</td>
				<td>
					<br>
					<p style="margin-bottom:-10px; text-decoration: underline">MUDRIKATUN, S.SiT, SKM, MM.Kes, MH</p>
					<p>NIP. 19690610 199003 2 010</p>
				</td>
			</tr>
		</table>

		<div class="footer">
			<i>Dicetak oleh Aplikasi Sikupat</i>
		</div>
	</body>
</html>
<?php
	}
?>
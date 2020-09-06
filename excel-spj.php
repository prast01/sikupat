<?php
	include 'koneksi.php';
	$id_spj = ($_GET['id_spj']) ? $_GET['id_spj'] : '' ;
	$bulan = ($_GET['bulan']) ? $_GET['bulan'] : '' ;
	$q = mysql_fetch_assoc(mysql_query("SELECT uraian_kegiatan AS kegiatan, kode_rekening FROM spj WHERE id_spj='$id_spj'"));
	$nm = "export-spj-".$q['kode_rekening'].".xls";
    header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=$nm");
	$q = mysql_fetch_assoc(mysql_query("SELECT uraian_kegiatan AS kegiatan, kode_rekening FROM spj WHERE id_spj='$id_spj'"));
	if ($bulan != 'all') {
		$query = mysql_query("SELECT b.kode_rekening, a.*, c.nm_seksi FROM bibs a, spj b, user c WHERE a.id_spj=b.id_spj AND a.kseksi=c.kseksi AND a.id_spj='$id_spj' AND a.tgl_transfer != '0000-00-00' AND MONTH(a.tgl_kegiatan) = '$bulan' ORDER BY a.kseksi ASC");
	} else {
		$query = mysql_query("SELECT b.kode_rekening, a.*, c.nm_seksi FROM bibs a, spj b, user c WHERE a.id_spj=b.id_spj AND a.kseksi=c.kseksi AND a.id_spj='$id_spj' AND a.tgl_transfer != '0000-00-00' ORDER BY a.kseksi ASC");
	}
?>
	<center>
		<h3>DATA SPJ<br><?php echo $q['kode_rekening']." - ".strtoupper($q['kegiatan']); ?></h3>
	</center>
    <table border="1">
		<thead>
			<tr>		
				<th>NO</th>
				<th>KODE REKENING</th>
				<th>URAIAN KEGIATAN</th>
				<th>SEKSI</th>
				<th>TGL KEGIATAN</th>
				<th>TGL TRANSFER</th>
				<th>NOMINAL</th>
			</tr>
		</thead>
		<tbody>
				<?php 
					$no = 1;

					$total = 0;
					while ($hasil = mysql_fetch_array($query)){
						$kode_rekening = $hasil['kode_rekening'];
						$uraian_kegiatan = $hasil['uraian'];
						$tgl_kegiatan = $hasil['tgl_kegiatan'];
						$tgl_transfer = $hasil['tgl_transfer'];
						$nominal = $hasil['nominal'];
						$nama = $hasil['nm_seksi'];
						$total = $total+$nominal;
				?>		

			<tr>	
				<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
				<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
				<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
				<td valign="top" align="left" bgcolor=""><?php echo $nama; ?></td>
				<td valign="top" align="left" bgcolor=""><?php echo $tgl_kegiatan; ?></td>
				<td valign="top" align="left" bgcolor=""><?php echo $tgl_transfer; ?></td>
				<td valign="top" align="right" bgcolor="">
				<?php
						echo number_format($nominal, 0, ',', '.');
				?>
				</td>
			</tr>
			<?php 
					error_reporting(0);
				}
			?>
			<tr>
				<th colspan="6">Total</th>
				<th><?php echo number_format($total,0,".","."); ?></th>
			</tr>
		</tbody>
    </table>
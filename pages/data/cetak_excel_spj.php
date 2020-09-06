  <?php
    $filename ="Lap_Excel_SPJ.xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
    ?>

<style>
    th {
        text-align: center;
		font-size: 10px;
    }            
    td{
        font-size: 12px;
    }
</style>
<script>
  //  window.print();
</script>
<?php
include "../../koneksi.php";
$ckseksi	= isset($_GET['ckseksi']) ? $_GET['ckseksi'] : '';
$ckkegiatan	= isset($_GET['ckkegiatan']) ? $_GET['ckkegiatan'] : '';
?>
<table border="1" width="100%">
	<tr>	
		<td style="text-align:center"><font color=""><b>NO</b></font></td>
		<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
		<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
		<td style="text-align:center"><font color=""><b>NAMA KEGIATAN</b></font></td>
		<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
		<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
		<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
		<td style="text-align:center"><font color=""><b>REALISASI(%)</b></font></td>
		<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
	</tr>	
	<?php 
		$no = 1;
		$lihat	= isset($_POST['lihat']) ? $_POST['lihat'] : '';
			if($ckkegiatan!=""){
			$query = mysql_query("select * from spj where id_kegiatan='$ckkegiatan'");
			}elseif($ckseksi!=""){
			$query = mysql_query("select * from spj where kseksi='$ckseksi'");
			}else{
			$query = mysql_query("select * from spj");
			}
		while ($hasil = mysql_fetch_array($query)){
		$id_spj	= $hasil['id_spj'];
		$id_kegiatan	= $hasil['id_kegiatan'];
			$query3 = mysql_query("select * from kegiatan where id_kegiatan='$id_kegiatan' order by nm_kegiatan");
			$hasil3 = mysql_fetch_array($query3);
			$nm_kegiatan	= $hasil3['nm_kegiatan'];
		$kseksi3	= $hasil['kseksi'];
			$query2 = mysql_query("select * from user where kseksi='$kseksi3'");
			$hasil2 = mysql_fetch_array($query2);
			$nm_seksi	= $hasil2['nm_seksi'];
		$kode_rekening	= $hasil['kode_rekening'];
		$pagu_anggaran	= $hasil['pagu_anggaran'];
		// $total_realisasi	= $hasil['total_realisasi'];
		$qa = mysql_fetch_assoc(mysql_query("SELECT total_realisasi FROM total_realisasi WHERE id_spj='$id_spj'"));

		// $total_realisasi	= $hasil['total_realisasi'];
		// if ($kseksi2 == 'DJ001') {
		// 	$total_realisasi	= $hasil['total_realisasi'];
		// } else {
			$total_realisasi	= $hasil['total_realisasi']+$qa['total_realisasi'];
		// }
		$uraian_kegiatan	= $hasil['uraian_kegiatan'];
		$persen_realisasi	= ($total_realisasi/$pagu_anggaran) *100 ;
		$persen_realisasi2 = round($persen_realisasi,2);
		$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
	?>
		<tr>		
			<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo $pagu_anggaran; ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo $total_realisasi; ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($persen_realisasi2, 2, ',', '.'); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo $sisa_pagu_anggaran; ?></td>
		</tr>
	<?php 
		error_reporting(0);
		$sum_pagu_anggaran += $pagu_anggaran;
		$total_pagu_anggaran = $sum_pagu_anggaran;
		
		$sum_total_realisasi += $total_realisasi;
		$total_total_realisasi = $sum_total_realisasi;
		
		$sum_sisa_pagu_anggaran += $sisa_pagu_anggaran;
		$total_sisa_pagu_anggaran = $sum_sisa_pagu_anggaran;
		
		$total_realisasi_persen	= (($total_total_realisasi / $total_pagu_anggaran)*100);
		$total_realisasi_persen2 = round($total_realisasi_persen,2);
		}
	?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td align="right"><?php echo $total_pagu_anggaran; ?></td>
			<td align="right"><?php echo $total_total_realisasi; ?></td>
			<td align="right"><?php echo number_format($total_realisasi_persen2, 2, ',', '.'); ?></td>
			<td align="right"><?php echo $total_sisa_pagu_anggaran; ?></td>
		</tr>
</table>

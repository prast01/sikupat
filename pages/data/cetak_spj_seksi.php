<style> 
    th{
		text-align:right;
    }

</style>


<script>
    window.print();
</script>

<?php
mysql_connect("localhost","root","");
mysql_select_db("realisasi_kegiatan");

$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
$q = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
$r = mysql_fetch_array($q);
$nm_seksi 	= $r['nm_seksi'];

?>
<table width="100%">
    <tr>
        <td align="center" style="font-size: 18px; font: bold;">LAPORAN SPJ BIDANG <?php echo $nm_seksi ?></td>
    </tr>
</table><br />
<table border="1" width="100%">
	<thead>
		<tr>		
			<td style="text-align:center"><font color=""><b>NO</b></font></td>
			<td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
			<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
			<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
			<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
			<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
			<td style="text-align:center"><font color=""><b>REALISASI(%)</b></font></td>
		</tr>
	</thead>
	<tbody>
			<?php 
			$no = 1;
				$query = mysql_query("select * from kegiatan where kbidang='$kbidang' order by kseksi asc,realisasi_persen desc");
				while ($hasil = mysql_fetch_array($query)){
				$id_kegiatan	= $hasil['id_kegiatan'];
				$nm_kegiatan	= $hasil['nm_kegiatan'];
				$kseksi			= $hasil['kseksi'];

				$a = mysql_query("SELECT * FROM user where kseksi='$kseksi'")or die(mysql_error());
				$r = mysql_fetch_array($a);
				$nm_seksi 	= $r['nm_seksi'];
				$kbidang	= $r['kbidang'];
				
				$a = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
				$r = mysql_fetch_array($a);
				$nm_bidang 	= $r['nm_seksi'];
				
				$a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'")or die(mysql_error());
				$r = mysql_fetch_array($a);
				$jml_pagu_anggaran 	= $r['jml_pagu_anggaran'];
				
				$a = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'")or die(mysql_error());
				$r = mysql_fetch_array($a);
				$jml_total_realisasi 	= $r['jml_total_realisasi'];
				
				$jml_sisa_pagu_anggaran	= $jml_pagu_anggaran - $jml_total_realisasi;
				
				$realisasi_persen	= (($jml_total_realisasi / $jml_pagu_anggaran)*100);
												
				mysql_query("update kegiatan set realisasi_persen='$realisasi_persen' where kbidang='$kbidang' and kseksi='$kseksi' and id_kegiatan='$id_kegiatan'")or die(mysql_error());
				
			?>		

		<tr>	
			<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_pagu_anggaran,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_total_realisasi,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_sisa_pagu_anggaran,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo round($realisasi_persen,2); ?></td>
		</tr>
		<?php 
				error_reporting(0);
				$sum_pagu_anggaran += $jml_pagu_anggaran;
				$total_pagu_anggaran = $sum_pagu_anggaran;
				
				$sum_total_realisasi += $jml_total_realisasi;
				$total_total_realisasi = $sum_total_realisasi;
				
				$sum_sisa_pagu_anggaran += $jml_sisa_pagu_anggaran;
				$total_sisa_pagu_anggaran = $sum_sisa_pagu_anggaran;
				
				$total_realisasi_persen	= (($total_total_realisasi / $total_pagu_anggaran)*100);
		}
		?>
	</tbody>
			<tr>
				<th colspan="3">Total</th>
				<th><?php echo number_format($total_pagu_anggaran,0,".","."); ?></th>
				<th><?php echo number_format($total_total_realisasi,0,".","."); ?></th>
				<th><?php echo number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
				<th><?php echo round($total_realisasi_persen,2); ?></th>
			</tr>
</table>

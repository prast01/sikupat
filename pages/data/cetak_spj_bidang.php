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


?>
<table width="100%">
    <tr>
        <td align="center" style="font-size: 18px; font: bold;">LAPORAN SPJ PER BIDANG</td>
    </tr>
</table><br />
<table border="1" width="100%">
	<thead>
		<tr>		
			<td style="text-align:center"><font color=""><b>NO</b></font></td>
			<td style="text-align:center"><font color=""><b>BIDANG</b></font></td>
			<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
			<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
			<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
			<td style="text-align:center"><font color=""><b>REALISASI(%)</b></font></td>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 1;
				$query = mysql_query("select * from bidang order by realisasi_persen desc");
				while ($hasil = mysql_fetch_array($query)){
				$kbidang 				= $hasil['kbidang'];
				$nm_bidang 				= $hasil['nm_bidang'];
				$jml_pagu_anggaran 		= $hasil['pagu_anggaran'];
				$jml_total_realisasi 	= $hasil['total_realisasi'];
				$jml_sisa_pagu_anggaran	= $jml_pagu_anggaran - $jml_total_realisasi;
				$realisasi_persen 		= $hasil['realisasi_persen'];
				
		?>
		<tr>	
			<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
			<td valign="top" align="left" bgcolor=""><?php echo $nm_bidang; ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_pagu_anggaran,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_total_realisasi,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_sisa_pagu_anggaran,0,".","."); ?></td>
			<td valign="top" align="right" bgcolor=""><?php echo $realisasi_persen; ?></td>
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
				<th colspan="2">Total</th>
				<th><?php echo number_format($total_pagu_anggaran,0,".","."); ?></th>
				<th><?php echo number_format($total_total_realisasi,0,".","."); ?></th>
				<th><?php echo number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
				<th><?php echo round($total_realisasi_persen,2); ?></th>
			</tr>
</table>

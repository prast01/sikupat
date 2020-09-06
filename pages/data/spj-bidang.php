<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">

<style> 
	body{
		background:#FFFFFF;
	}
    th{
        color: black;
        font: Times New Roman;
		text-align:right;
		font-size: 11px;
    }
    td{
        font-size: 12px;
        color: black;
        font: Times New Roman;
    }
a:link {
	color: #3333FF;
}
a:visited {
	color: #0000FF;
}
</style>

<?php 
	error_reporting(E_ALL^E_WARNING);
	$no = 1;
	$query = mysql_query("select * from user where level_user='1'");
	while ($hasil = mysql_fetch_array($query)){
		$kbidang 	= $hasil['kbidang'];
		
		$a = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
		$r = mysql_fetch_array($a);
		$nm_bidang 	= $r['nm_seksi'];

		if ($kbidang == 'DK007') {
			$a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where kbidang2='$kbidang'")or die(mysql_error());
			$r = mysql_fetch_array($a);

			$jml_pagu_anggaran 	= $r['jml_pagu_anggaran'];
			
			$a = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM spj where kbidang2='$kbidang'")or die(mysql_error());
			$a2 = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM total_realisasi where kbidang2='$kbidang'")or die(mysql_error());
			$r = mysql_fetch_array($a);
			$r2 = mysql_fetch_array($a2);
			
			$jml_total_realisasi 	= $r['jml_total_realisasi']+$r2['jml_total_realisasi'];
		} else {
			$a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where kbidang='$kbidang'")or die(mysql_error());
			$r = mysql_fetch_array($a);

			$jml_pagu_anggaran 	= $r['jml_pagu_anggaran'];
			
			$a = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM spj where kbidang='$kbidang'")or die(mysql_error());
			$a2 = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM total_realisasi where kbidang='$kbidang'")or die(mysql_error());
			$r = mysql_fetch_array($a);
			$r2 = mysql_fetch_array($a2);

			$jml_total_realisasi 	= $r['jml_total_realisasi']+$r2['jml_total_realisasi'];
		}
		
		
		
		$jml_sisa_pagu_anggaran	= $jml_pagu_anggaran - $jml_total_realisasi;
		
		$realisasi_persen	= (($jml_total_realisasi / $jml_pagu_anggaran)*100);

		$ke = mysql_fetch_assoc(mysql_query("SELECT AVG(real_kegiatan) as rata FROM spj WHERE kbidang='$kbidang' AND pagu_anggaran != '0'"));

		mysql_query("update bidang set pagu_anggaran='$jml_pagu_anggaran',total_realisasi='$jml_total_realisasi',realisasi_persen='$realisasi_persen', kegiatan_persen='$ke[rata]' where kbidang='$kbidang'")or die(mysql_error());
	}
	


$act	= isset($_GET['act']) ? $_GET['act'] : '';

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$level_user 	= $r['level_user'];

?>

<div class="tab-content">			
	<div class="panel-body" >	
			<div class="panel panel-default" style="">
				<?php
				 $kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
					$a = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
					$r = mysql_fetch_array($a);
					$nm_seksi 	= $r['nm_seksi'];
				 
				?>
				<div class="panel-heading">
				<?php if($act=="detail"){ ?>
						<h3 class="panel-title">DAFTAR SPJ <?php echo $nm_seksi ?></h3>
				<?php }elseif($act=="tampil"){ ?>
						<h3 class="panel-title">GRAFIK</h3>
				<?php } ?>
				</div>
				<div class="panel-body">
				<?php if($act=="tampil"){ ?>
				
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
					  $rw = mysql_query("select * from bidang order by realisasi_persen desc")or die(mysql_error());
					  while($r = mysql_fetch_array($rw)){
					  $kbidang = $r['kbidang'];
					  $realisasi_persen = $r['realisasi_persen'];
					  $kegiatan_persen = $r['kegiatan_persen'];
					  $nm_bidang = $r['nm_bidang'];
				
					  $grafik1[] = array($nm_bidang, doubleval($realisasi_persen));
					  $grafik2[] = array($nm_bidang);
					  $grafik3[] = array($nm_bidang, doubleval($kegiatan_persen));
					  }
				
					  $data_grafik1 = json_encode($grafik1);
					  $grafik2		= json_encode($grafik2);
					  $data_grafik2		= json_encode($grafik3);
				?>

				<div id="container" style="height:400px"></div>
				<script>
				$(document).ready(function(){
				$(function () {
					var chart = Highcharts.chart('container', {
						chart: {
							type: 'column'
						},
						xAxis: {
							categories: <?=$grafik2?>
						},
						plotOptions: {
							series: {
							borderWidth: 0,
							dataLabels: {
								enabled: true,
								format: '{point.y:.2f}%'
							}
						}
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Persen'
							}
						},
						title: {
							text: 'GRAFIK REALISASI PENYERAPAN ANGGARAN KEGIATAN',
							align: 'center',
							x: 0
						},
						colors: ['#000099','#006600', '#d24087', '#4ec4ce'],
						series: [
							{
								data: <?=$data_grafik1?>,
								name: "Penyerapan"
								
							},
							{
								data: <?=$data_grafik2?>,
								name: "Kegiatan"
								
							},
						]
					});
				 
					// the button action
					$('#button').click(function () {
						var selectedPoints = chart.getSelectedPoints();
				 
						if (chart.lbl) {
							chart.lbl.destroy();
						}
						chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
							.attr({
								padding: 10,
								r: 5,
								fill: Highcharts.getOptions().colors[1],
								zIndex: 5
							})
							.css({
								color: 'white'
							})
							.add();
					});
				});
				});
				</script>								
				
				<?php } ?>
				
				<?php if($act=="edit"){ ?>
				<?php 
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<td style="text-align:center"><font color=""><b>NO</b></font></td>
									<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
									<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
									<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
									<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
									<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$query = mysql_query("select * from spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'");
										while ($hasil = mysql_fetch_array($query)){
										$id_spj	= $hasil['id_spj'];
										$kode_rekening	= $hasil['kode_rekening'];
										$pagu_anggaran	= $hasil['pagu_anggaran'];
										$total_realisasi	= $hasil['total_realisasi'];
										$uraian_kegiatan	= $hasil['uraian_kegiatan'];
										$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
									?>		
		
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
									<td valign="top" align="right" bgcolor=""><?php echo number_format($pagu_anggaran,0,".","."); ?></td>
									<td valign="top" align="right" bgcolor=""><input name="total_realisasi<?php echo $id_spj; ?>" type="text" id="total_realisasi<?php echo $id_spj; ?>" value="<?php echo $total_realisasi; ?>" onChange="update_total_realisasi(this,<?php echo $id_spj; ?>)" /></td>
									<td valign="top" align="right" bgcolor=""><?php echo number_format($sisa_pagu_anggaran,0,".","."); ?></td>
								</tr>
								<?php 
								error_reporting(0);
								$sum_pagu_anggaran += $pagu_anggaran;
								$total_pagu_anggaran = $sum_pagu_anggaran;
								
								$sum_total_realisasi += $total_realisasi;
								$total_total_realisasi = $sum_total_realisasi;
								
								$sum_sisa_pagu_anggaran += $sisa_pagu_anggaran;
								$total_sisa_pagu_anggaran = $sum_sisa_pagu_anggaran;
								}
								?>
							</tbody>
								<tr>
									<th></th>
									<th></th>
									<th>Total</th>
									<th><?php echo number_format($total_pagu_anggaran,0,".","."); ?></th>
									<th><?php echo number_format($total_total_realisasi,0,".","."); ?></th>
									<th><?php echo number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
								</tr>
							
					   </table>
					</form>
					<?php 
					$nm_kegiatan 	= isset($_POST['nm_kegiatan']) ? $_POST['nm_kegiatan'] : '';
					$simpan 		= isset($_POST['simpan']) ? $_POST['simpan'] : '';
					
					if($simpan){
						$insert = mysql_query("update kegiatan set nm_kegiatan='$nm_kegiatan' where id_spj='$id_spj'")or die(mysql_error());
						if($insert){
							echo "<script>alert('Data berhasil diupdate');window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";  
						}
					}
					?>
					
					<?php }?>
				</div>
			</div>
<div class="table-responsive">	
	<div class="widget-box">
		<div class="widget-header">			
			<h5 class="widget-title">DAFTAR SPJ PER-BIDANG</h5>
			<span class="widget-toolbar">
				<a href="#" data-action="reload">
					<i class="ace-icon fa fa-refresh"></i>
				</a>

				<a href="#" data-action="collapse">
					<i class="ace-icon fa fa-chevron-up"></i>
				</a>

				<a href="#" data-action="close">
					<i class="ace-icon fa fa-times"></i>
				</a>
			</span>
			<span class="widget-toolbar">
			</span>
		</div>
			<div class="widget-body">
				<div class="widget-main">		
				<table id="table1" class="table table-bordered table-hover" width="100%">
                    <thead>
						<tr>		
							<td style="text-align:center"><font color=""><b>NO</b></font></td>
							<td style="text-align:center"><font color=""><b>BIDANG</b></font></td>
							<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
							<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""><b>PENYERAPAN (%)</b></font></td>
							<td style="text-align:center"><font color=""><b>REALISASI KEGIATAN (%)</b></font></td>
							<td style="text-align:center"><font color=""><b><center><a href="pages/data/cetak_spj_bidang.php" target="_blank">Cetak</a></center></b></font></td>
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
									$kegiatan_persen 		= $hasil['kegiatan_persen'];
								
						?>
						<tr>	
							<td valign="top" align="center" bgcolor=""><?php echo $no; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_bidang; ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_pagu_anggaran,0,".","."); ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_total_realisasi,0,".","."); ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_sisa_pagu_anggaran,0,".","."); ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo $realisasi_persen; ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo $kegiatan_persen; ?></td>
							<td valign="top" align="center" bgcolor="">
							<a href="home.php?cat=data&page=spj&act=tampil&kbidang=<?php echo $kbidang ?>">Detail</a>
							</td>
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
									$no++;
									$t_keg = $t_keg + $kegiatan_persen;
								}

								$jml = $no-1;
								$per = ($t_keg/$jml);
						?>
					</tbody>
							<tr>
								<th></th>
								<th>Total</th>
								<th><?php echo number_format($total_pagu_anggaran,0,".","."); ?></th>
								<th><?php echo number_format($total_total_realisasi,0,".","."); ?></th>
								<th><?php echo number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
								<th><?php echo round($total_realisasi_persen,2); ?></th>
								<th><?php echo round($per,2); ?></th>
								<th></th>
							</tr>
               </table>
			</div>
		</div>
	</div>
</div>

<script src="assets/lib/jquery/jquery.js"></script>
<script>
  $(function () {
	$('#table1').DataTable({
	  "paging": true,
	  "lengthChange": true,
	  "searching": true,
	  "ordering": true,
	  "info": true,
	  "autoWidth": false
	});
  });
</script>

<script type="text/javascript" src="jquery-1.8.0.min.js"></script>
<script>

	  function update_total_realisasi(jml,id_spj){
		  var total_realisasi = jml.value;
			$.ajax({
				url: "pages/data/update_total_realisasi.php?total_realisasi="+total_realisasi+"&id_spj="+id_spj,
				type: "POST",
				success: function(response){
				}
			});
	  }
</script>
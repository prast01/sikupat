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
$act	= isset($_GET['act']) ? $_GET['act'] : '';
$ckseksi	= isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';
$ckkegiatan	= isset($_REQUEST['ckkegiatan']) ? $_REQUEST['ckkegiatan'] : '';
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$kseksi2 	= $r['kseksi'];
$level_user = $r['level_user'];
?>
<div class="tab-content">			
	<div class="panel-body" >
			<div class="panel panel-default" style="">
				<?php if($act=="edit"){ ?>
				<br />
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					   <div class="form-group">
							<label class="col-sm-2 control-label">Seksi/Subag</label>
							<div class="col-sm-4">
							<select class="form-control" name="ckseksi">
							<?php if($ckseksi!=""){ ?>
								<?php
								$query = mysql_query("select * from user where level_user='2' and kseksi='$ckseksi'");
								$hasil = mysql_fetch_array($query);
								$kseksi	= $hasil['kseksi'];
								$nm_seksi	= $hasil['nm_seksi'];
								?>
								<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from user where level_user='2'");
								while ($hasil = mysql_fetch_array($query)){
								$kseksi	= $hasil['kseksi'];
								$nm_seksi	= $hasil['nm_seksi'];
								?>
								<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
								<?php } ?>
							<?php }else{ ?>
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from user where level_user='2'");
								while ($hasil = mysql_fetch_array($query)){
								$kseksi	= $hasil['kseksi'];
								$nm_seksi	= $hasil['nm_seksi'];
								?>
								<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
								<?php } ?>
							<?php } ?>
							</select>
							</div>
						</div>						
					
					   <div class="form-group">
							<label class="col-sm-2 control-label">Nama Kegiatan</label>
							<div class="col-sm-4">
							<select class="form-control" name="ckkegiatan" >
							<?php if($ckkegiatan!=""){ ?>
								<?php 
								$query = mysql_query("select * from kegiatan where id_kegiatan='$ckkegiatan'");
								$hasil = mysql_fetch_array($query);
								$id_kegiatan	= $query['id_kegiatan'];
								$nm_kegiatan	= $query['nm_kegiatan'];
								?>
								<option value="<?php echo $id_kegiatan ?>"><?php echo $nm_kegiatan ?></option>
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from kegiatan  order by nm_kegiatan");
								while ($hasil = mysql_fetch_array($query)){
								$id_kegiatan	= $hasil['id_kegiatan'];
								$nm_kegiatan	= $hasil['nm_kegiatan'];
								?>
								<option value="<?php echo $id_kegiatan ?>"><?php echo $nm_kegiatan ?></option>
								<?php } ?>
							<?php }else{ ?>
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from kegiatan order by nm_kegiatan");
								while ($hasil = mysql_fetch_array($query)){
								$id_kegiatan	= $hasil['id_kegiatan'];
								$nm_kegiatan	= $hasil['nm_kegiatan'];
								?>
								<option value="<?php echo $id_kegiatan ?>"><?php echo $nm_kegiatan ?></option>
								<?php } ?>
							<?php } ?>
							</select>
							</div>
							<div class="col-sm-3">
							<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
							<label class="col-sm-1 control-label"></label>
							<a href="pages/data/cetak_excel_spj.php?ckkegiatan=<?php echo $ckkegiatan ?>&ckseksi=<?php echo $ckseksi ?>" target="_blank">
							Export Excel
							</a>
							</div>
						</form>					
				<div class="panel-heading">
						<h3 class="panel-title">&nbsp;</h3>
				</div>
				
				<?php }?>
				<div class="panel-body">
				<div class="table-responsive">	
				
				<div class="widget-header">			
					<h5 class="widget-title">SPJ</h5>
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

				<?php if($act=="edit"){ ?>
				<?php 
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
				?>
					
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
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
							</thead>
							<tbody>
									<?php 
										$no = 1;
									    $lihat	= isset($_POST['lihat']) ? $_POST['lihat'] : '';
										if($lihat){
											if($ckkegiatan!=""){
											$query = mysql_query("select * from spj where id_kegiatan='$ckkegiatan'");
											}elseif($ckseksi!=""){
											$query = mysql_query("select * from spj where kseksi='$ckseksi'");
											}else{
											$query = mysql_query("select * from spj");
											}
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

										$qa = mysql_fetch_assoc(mysql_query("SELECT total_realisasi FROM total_realisasi WHERE id_spj='$id_spj'"));

										// $total_realisasi	= $hasil['total_realisasi'];
										// if ($kseksi2 == 'DJ001') {
										// 	$total_realisasi	= $hasil['total_realisasi'];
										// } else {
											$total_realisasi	= $hasil['total_realisasi']+$qa['total_realisasi'];
										// }
										
										$uraian_kegiatan	= $hasil['uraian_kegiatan'];
										$persen_realisasi	= ($total_realisasi/$pagu_anggaran) *100 ;
										$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
									?>		
		
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><a href="home.php?cat=data&page=spj_detail&spj=<?php echo $id_spj; ?>" target="_blank"><?php echo $kode_rekening; ?></a></td>
									<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
									<td valign="top" align="right" bgcolor="">
										<?php //if($kseksi2=='DJ001'){ ?>
										<!-- <input name="pagu_anggaran<?php echo $id_spj; ?>" type="text" id="pagu_anggaran<?php echo $id_spj; ?>" value="<?php echo $pagu_anggaran; ?>" onChange="update_pagu_anggaran(this,<?php echo $id_spj; ?>)" /> -->
										<?php //}else{ ?>
										<?php echo number_format($pagu_anggaran, 0, ',', '.'); ?>
										<?php //} ?>
									</td>
									<td valign="top" align="right" bgcolor="">
										<?php //if($kseksi2=='DJ001'){ ?>
										<!-- <input name="pagu_anggaran<?php echo $id_spj; ?>" type="text" id="total_realisasi<?php echo $id_spj; ?>" value="<?php echo $total_realisasi; ?>" onChange="update_total_realisasi(this,<?php echo $id_spj; ?>)" /> -->
										<?php //}else{ ?>
										<?php echo number_format($total_realisasi, 0, ',', '.'); ?>
										<?php //} ?>
									</td>
									<td valign="top" align="right" bgcolor=""><?php echo round($persen_realisasi,2); ?></td>
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
								
								$total_realisasi_persen	= (($total_total_realisasi / $total_pagu_anggaran)*100);
								}
								
								?>
							</tbody>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th>Total</th>
									<th><?php echo number_format($total_pagu_anggaran,0,".","."); ?></th>
									<th><?php echo number_format($total_total_realisasi,0,".","."); ?></th>
									<th><?php echo round($total_realisasi_persen,2); ?></th>
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


				<div class="panel-body">
				<div class="table-responsive">	

				<?php if($act=="kegiatan"){ ?>
				<?php 
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
				?>
					
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<td style="text-align:center"><font color=""><b>NO</b></font></td>
									<td style="text-align:center"><font color=""><b>NAMA KEGIATAN</b></font></td>
									<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
									<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
									<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
									<td style="text-align:center"><font color=""><b>REALISASI(%)</b></font></td>
									<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$query = mysql_query("select * from kegiatan order by id_kegiatan ASC");
										while ($hasil = mysql_fetch_array($query)){
										$id_kegiatan	= $hasil['id_kegiatan'];
										$nm_kegiatan	= $hasil['nm_kegiatan'];
											$query3 = mysql_query("select *, sum(pagu_anggaran) as pagu_anggaran,sum(total_realisasi) as total_realisasi from spj where id_kegiatan='$id_kegiatan' group by id_kegiatan ");
											$hasil3 = mysql_fetch_array($query3);
										$kseksi	= $hasil['kseksi'];
											$query2 = mysql_query("select * from user where kseksi='$kseksi'");
											$hasil2 = mysql_fetch_array($query2);
											$nm_seksi	= $hasil2['nm_seksi'];
										$pagu_anggaran	= $hasil3['pagu_anggaran'];
										
										$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi WHERE id_kegiatan='$id_kegiatan' group by id_kegiatan"));

										// $total_realisasi	= $hasil3['total_realisasi'];
										$total_realisasi	= $hasil3['total_realisasi']+$qa['total_realisasi'];
										$persen_realisasi	= ($total_realisasi/$pagu_anggaran) *100 ;
										$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
									?>		
		
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
									<td valign="top" align="right" bgcolor=""><?php echo number_format($pagu_anggaran,0,".","."); ?></td>
									<td valign="top" align="right" bgcolor=""><?php echo number_format($total_realisasi,0,".","."); ?></td>
									<td valign="top" align="right" bgcolor=""><?php echo round($persen_realisasi,2); ?></td>
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
									<th></th>
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

			
<?php
$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
$q = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
$r = mysql_fetch_array($q);
$nm_seksi 	= $r['nm_seksi'];

?>			
<!--			
<div class="table-responsive">	
	<div class="widget-box">
		<div class="widget-header">
			<h5 class="widget-title">DAFTAR SPJ BIDANG <?php echo $nm_seksi ?></h5>

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
							<td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
							<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
							<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
							<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""><b>REALISASI(%)</b></font></td>
							<td style="text-align:center"><font color=""><b><center><a href="pages/data/cetak_spj_seksi.php?kbidang=<?php echo $kbidang; ?>" target="_blank">Cetak</a></center></b></font></td>
						</tr>
                    </thead>
					<tbody>
							<?php 
							$no = 1;
							$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
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
							<td valign="top" align="center" bgcolor="">
							<?php if($level_user!='4'){ ?>
							<a href="home.php?cat=data&page=spj&act=edit&id_kegiatan=<?php echo $id_kegiatan ?>">SPJ</a>
							<?php } ?>
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
								<th><?php echo round($total_realisasi_persen,2); ?></th>
								<th></th>
							</tr>
               </table>
			</div>
		</div>
		-->
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
	  
	  function update_pagu_anggaran(jml,id_spj){
		  var pagu_anggaran = jml.value;
			$.ajax({
				url: "pages/data/update_pagu_anggaran.php?pagu_anggaran="+pagu_anggaran+"&id_spj="+id_spj,
				type: "POST",
				success: function(response){
				}
			});
	  }
</script>
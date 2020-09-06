<?php 
$act	= isset($_GET['act']) ? $_GET['act'] : '';

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a 			= mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r 			= mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];

$tgl			= date('Y/m/d');
$thn 			= date("Y");
$bln 			= date("m");
$bl 			= sprintf("%02s", $bln);
$blnini 		= $thn."-".$bl;
$bulan 			= isset($_REQUEST['bulan']) ? $_REQUEST['bulan'] : '';
$tahun 			= isset($_REQUEST['tahun']) ? $_REQUEST['tahun'] : '';
$cbt 			= $tahun."-".$bulan;

$ckseksi 		= isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';
$ckd_rek 		= isset($_REQUEST['ckd_rek']) ? $_REQUEST['ckd_rek'] : '';
$curaian_kegiatan 		= isset($_REQUEST['curaian_kegiatan']) ? $_REQUEST['curaian_kegiatan'] : '';
?>


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
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">

var htmlobjek;
$(document).ready(function(){
  //apabila terjadi event onchange terhadap object <select id=fakultas>
  $("#ckd_rek").change(function(){
    var ckd_rek = $("#ckd_rek").val();
    var ckseksi = "<?php echo $kseksi; ?>";
    $.ajax({
        url: "pages/data/ambil_kegiatan.php",
        data: "ckd_rek="+ckd_rek+"&ckseksi="+ckseksi,
        cache: false,
        success: function(msg){
            //jika data sukses diambil dari server kita tampilkan
            //di <select id=progdi>
            $("#curaian_keg").html(msg);
        }
    });
  });
 });

</script>


<div class="tab-content">			
	<div class="panel-body" >	
			<div class="panel panel-default" style="">
				<div class="panel-heading">
				<?php if($act=="tampil"){ ?>
						<h3 class="panel-title">INPUT REALISASI KEGIATAN</h3>
				<?php }elseif($act=="edit"){ ?>
						<h3 class="panel-title">EDIT REALISASI KEGIATAN</h3>
				<?php }?>
				</div>
				<div class="panel-body">
				<?php if($act=="tampil"){ ?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					  <div class="box-body">
					  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Tanggal</label>
								<div class="col-sm-2">
								<input type="date" name="tgl_kegiatan" placeholder=""  class="form-control" required />
								</div>
							</div>
			  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-4">
								<select class="form-control" name="ckd_rek" id="ckd_rek" required >
									<option value="">-Pilih-</option>
									<?php
									$query=mysql_query("select * from kegiatan where kseksi='$kseksi' group by kode_rekening");
									while($row=mysql_fetch_array($query)){
									?>
									<option value="<?php  echo $row['kode_rekening']; ?>">
										<?php  echo $row['kode_rekening']; ?>
									</option>
									<?php 
									}
									?>      
								</select>
								</div>
							</div>
			   
						   <div class="form-group">
								<label class="col-sm-2 control-label">Uraian Kegiatan</label>
								<div class="col-sm-4">
								<select class="form-control" name="curaian_keg" id="curaian_keg" required >
									<option value=""></option>
									<option value="">-Pilih Kode Rekening Dulu-</option>
                                </select>
								</div>
							</div>
			   
						   <div class="form-group">
								<label class="col-sm-2 control-label">Pagu Anggaran</label>
								<div class="col-sm-2">
								<input type="number" name="pagu_anggaran" placeholder="" value="0"  class="form-control" />
								</div>
							</div>
							
						   <div class="form-group">
								<label class="col-sm-2 control-label">Total Realisasi</label>
								<div class="col-sm-2">
								<input type="number" name="total_realisasi" placeholder="" value="0"  class="form-control" />
								</div>
							</div>
							
						   <div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-4">
								<button type="submit" class="btn btn-info pull-left" name="simpan" value="simpan">Simpan</button>
								</div>
							</div>
	
						</div>
					</form>
					<?php 
                    $tgl_kegiatan 	= isset($_POST['tgl_kegiatan']) ? $_POST['tgl_kegiatan'] : '';
                    $ckd_rek 		= isset($_POST['ckd_rek']) ? $_POST['ckd_rek'] : '';
                    $pagu_anggaran 	= isset($_POST['pagu_anggaran']) ? $_POST['pagu_anggaran'] : '';
                    $total_realisasi = isset($_POST['total_realisasi']) ? $_POST['total_realisasi'] : '';
                    $simpan 		= isset($_POST['simpan']) ? $_POST['simpan'] : '';
                    
                    if($simpan){
                        $insert = mysql_query("insert into realisasi_kegiatan(tgl_kegiatan,kseksi,kode_rekening,pagu_anggaran,total_realisasi) values ('$tgl_kegiatan','$kseksi','$ckd_rek','$pagu_anggaran','$total_realisasi')")or die(mysql_error());
                        if($insert){
                            echo "<script>alert('Data berhasil disimpan');window.location='home.php?cat=data&page=realisasi_kegiatan&act=tampil'</script>";  
                        }
                    }
                    ?>
                    
                    
				<?php }elseif($act=="edit"){ ?>
				<?php 
					$id_realisasi	= isset($_GET['id_realisasi']) ? $_GET['id_realisasi'] : '';
					$query = mysql_query("select * from realisasi_kegiatan where id_realisasi='$id_realisasi'");
					$hasil = mysql_fetch_array($query);
					$tgl_kegiatan	= $hasil['tgl_kegiatan'];
					$kode_rekening	= $hasil['kode_rekening'];
					$pagu_anggaran	= $hasil['pagu_anggaran'];
					$total_realisasi= $hasil['total_realisasi'];
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					  <div class="box-body">
					  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Tanggal</label>
								<div class="col-sm-2">
								<input type="date" name="tgl_kegiatan" placeholder="" value="<?php echo $tgl_kegiatan ?>" class="form-control" required />
								</div>
							</div>
			  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-4">
								<select class="form-control" name="ckd_rek" id="ckd_rek" required >
									<?php
									$query=mysql_query("select * from kegiatan where kode_rekening='$kode_rekening'");
									while($row=mysql_fetch_array($query)){
									?>
									<option value="<?php  echo $row['kode_rekening']; ?>">
										<?php  echo $row['kode_rekening']; ?>
									</option>
									<?php 
									}
									?>      
                                
									<option value="">-Pilih-</option>
									<?php
									$query=mysql_query("select * from kegiatan where kseksi='$kseksi' group by kode_rekening");
									while($row=mysql_fetch_array($query)){
									?>
									<option value="<?php  echo $row['kode_rekening']; ?>">
										<?php  echo $row['kode_rekening']; ?>
									</option>
									<?php 
									}
									?>      
								</select>
								</div>
							</div>
			   
						   <div class="form-group">
								<label class="col-sm-2 control-label">Uraian Kegiatan</label>
								<div class="col-sm-4">
								<select class="form-control" name="curaian_keg" id="curaian_keg" required >
									<?php
									$query=mysql_query("select * from kegiatan where kode_rekening='$kode_rekening' and kseksi='$kseksi'");
									while($row=mysql_fetch_array($query)){
									?>
									<option value="<?php  echo $row['uraian_kegiatan']; ?>">
										<?php  echo $row['uraian_kegiatan']; ?>
									</option>
									<?php 
									}
									?>      
                                </select>
								</div>
							</div>
			   
						   <div class="form-group">
								<label class="col-sm-2 control-label">Pagu Anggaran</label>
								<div class="col-sm-2">
								<input type="number" name="pagu_anggaran" placeholder="" value="<?php echo $pagu_anggaran ?>"  class="form-control" />
								</div>
							</div>
							
						   <div class="form-group">
								<label class="col-sm-2 control-label">Total Realisasi</label>
								<div class="col-sm-2">
								<input type="number" name="total_realisasi" placeholder="" value="<?php echo $total_realisasi ?>"  class="form-control" />
								</div>
							</div>
							
						   <div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-4">
								<button type="submit" class="btn btn-info pull-left" name="update" value="update">Update</button>
								</div>
							</div>
	
						</div>
					</form>
					<?php 
					$tgl_kegiatan 	= isset($_POST['tgl_kegiatan']) ? $_POST['tgl_kegiatan'] : '';
					$ckd_rek 		= isset($_POST['ckd_rek']) ? $_POST['ckd_rek'] : '';
					$pagu_anggaran 	= isset($_POST['pagu_anggaran']) ? $_POST['pagu_anggaran'] : '';
					$total_realisasi= isset($_POST['total_realisasi']) ? $_POST['total_realisasi'] : '';
					$update 		= isset($_POST['update']) ? $_POST['update'] : '';
					
					if($update){
						$insert = mysql_query("update realisasi_kegiatan set tgl_kegiatan='$tgl_kegiatan',kode_rekening='$ckd_rek',pagu_anggaran='$pagu_anggaran',total_realisasi='$total_realisasi' where id_realisasi='$id_realisasi'")or die(mysql_error());
						if($insert){
							echo "<script>alert('Data berhasil diupdate');window.location='home.php?cat=data&page=realisasi_kegiatan&act=tampil'</script>";  
						}
					}
					?>
				<?php }?>
				</div>
			</div>
			
            <form id="form-wizard" class="form-horizontal" method="post" onsubmit="return validasi()">
                <div id="form-wizard-1" class="step">
                    <div class="">
                        <table class="table table-bordered ">
                          <thead>
                            <tr>
                              <th><center>Seksi</center></th>
                              <th><center>Kode Rekening</center></th>
                              <th><center>Uraian Kegaiatan</center></th>
                              <th colspan="2">
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr class="gradeX">
                              <td>
                              <center>
                                <select class="form-control" name="ckseksi" id="ckseksi">
                                    <?php
                                    $query=mysql_query("select * from user where username='$username'");
                                    while($row=mysql_fetch_array($query)){
                                    ?>
                                    <option value="<?php  echo $row['kseksi']; ?>">
                                        <?php  echo $row['nm_seksi']; ?>
                                    </option>
                                    <?php 
                                    }
                                    ?>      
                                </select>
                              </center>								
                              </td>
                              <td>
                              <center>
                                <select class="form-control" name="ckd_rek" id="ckd_rek">
                                <?php if($ckd_rek!=''){?>
                                    <?php
                                    $query=mysql_query("select * from kegiatan where kode_rekening='$ckd_rek'");
                                    while($row=mysql_fetch_array($query)){
                                    ?>
                                    <option value="<?php  echo $row['kode_rekening']; ?>">
                                        <?php  echo $row['kode_rekening']; ?>
                                    </option>
                                    <?php 
                                    }
                                    ?>      
                                <?php }else{ ?>
                                    <option value="">-Pilih-</option>
                                    <?php
                                    $query=mysql_query("select * from kegiatan where kseksi='$kseksi' group by kode_rekening");
                                    while($row=mysql_fetch_array($query)){
                                    ?>
                                    <option value="<?php  echo $row['kode_rekening']; ?>">
                                        <?php  echo $row['kode_rekening']; ?>
                                    </option>
                                    <?php 
                                    }
                                    ?>      
                                </select>
                                <?php } ?>
                              </center>								
                              </td>
                              <td>
                              	<center>
                                <?php if($curaian_kegiatan!=''){?>
                              	<input type="text" name="curaian_kegiatan" value="<?php echo $curaian_kegiatan ?>"  class="form-control" />
                                <?php }else{ ?>
                              	<input type="text" name="curaian_kegiatan" placeholder=""  class="form-control" />
                                <?php } ?>
                              	</center>
                              </td>
                              <td><center><input id="next" class="btn btn-inverse" type="submit" name="lihat" value="Ok" /></center></td>
                              <td><center>
                              <?php if ($tahun!=''){ ?>
                              <a href="pages/data/cetak/lap_rujuk.php?cbulan=<?php echo $bulan ?>&ctahun=<?php echo $tahun ?>&cnik=<?php echo $cnik; ?>&cnama=<?php echo $cnama; ?>&cfaskes=<?php echo $cfaskes; ?>&ckkecamatan=<?php echo $ckkecamatan; ?>&ckkelurahan=<?php echo $ckkelurahan; ?>" class="btn btn-info pull-left" target="_blank">Cetak</a>
                              <?php } ?>
                              </center></td>
                            </tr>
                          </tbody>
                        </table>

<div class="table-responsive">	
	<div class="widget-box">
		<div class="widget-header">
			<h5 class="widget-title">DAFTAR REALISASI KEGIATAN</h5>

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
							<td style="text-align:center"><font color=""><b>No</b></font></td>
							<!--<td style="text-align:center"><font color=""><b>TGL KEGIATAN</b></font></td>
							<td style="text-align:center"><font color=""><b>NAMA KEGIATAN</b></font></td>-->
							<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
							<td style="text-align:center"><font color=""><b>BIDANG</b></font></td>
							<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
							<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
							<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
							<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
							<td style="text-align:center"><font color=""></font></td>
						</tr>
                    </thead>
					<tbody>
							<?php 
							$no = 1;
							$lihat = isset($_POST['lihat']) ? $_POST['lihat'] : '';
							if($lihat){  
								$query = mysql_query("select `kegiatan`.`kode_rekening` AS `kode_rekening`,`kegiatan`.`kseksi` AS `kseksi`,`kegiatan`.`uraian_kegiatan` AS `uraian_kegiatan`,`realisasi_kegiatan`.`pagu_anggaran` AS `pagu_anggaran`,`realisasi_kegiatan`.`total_realisasi` AS `total_realisasi`,`realisasi_kegiatan`.`id_realisasi` AS `id_realisasi` from (`kegiatan` join `realisasi_kegiatan` on(((`kegiatan`.`kseksi` = `realisasi_kegiatan`.`kseksi`) and (`kegiatan`.`kode_rekening` = `realisasi_kegiatan`.`kode_rekening`)))) where kegiatan.kode_rekening like '%$ckd_rek%' and kegiatan.kseksi like '%$ckseksi%' and kegiatan.uraian_kegiatan like '%$curaian_kegiatan%'");
							}else{
								$query = mysql_query("select `kegiatan`.`kode_rekening` AS `kode_rekening`,`kegiatan`.`kseksi` AS `kseksi`,`kegiatan`.`uraian_kegiatan` AS `uraian_kegiatan`,`realisasi_kegiatan`.`pagu_anggaran` AS `pagu_anggaran`,`realisasi_kegiatan`.`total_realisasi` AS `total_realisasi`,`realisasi_kegiatan`.`id_realisasi` AS `id_realisasi` from (`kegiatan` join `realisasi_kegiatan` on(((`kegiatan`.`kseksi` = `realisasi_kegiatan`.`kseksi`) and (`kegiatan`.`kode_rekening` = `realisasi_kegiatan`.`kode_rekening`)))) where kegiatan.kseksi='$kseksi' ");
							}

								while ($hasil = mysql_fetch_array($query)){
								$id_realisasi	= $hasil['id_realisasi'];
								$kseksi	= $hasil['kseksi'];
									$a = mysql_query("SELECT * FROM user where kseksi='$kseksi'")or die(mysql_error());
									$r = mysql_fetch_array($a);
									$nm_seksi 	= $r['nm_seksi'];
									$kbidang 	= $r['kbidang'];
									
									$a = mysql_query("SELECT * FROM user where kseksi='$kbidang'")or die(mysql_error());
									$r = mysql_fetch_array($a);
									$nm_bidang 	= $r['nm_seksi'];
								
								$uraian_kegiatan 	= $hasil['uraian_kegiatan'];
								$kode_rekening 	= $hasil['kode_rekening'];
								$pagu_anggaran		= $hasil['pagu_anggaran'];
								$total_realisasi	= $hasil['total_realisasi'];
								$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
							?>		

						<tr>	
							<td valign="top" align="left" bgcolor=""><?php echo $no++; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_bidang ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo "Rp ".number_format($pagu_anggaran,0,".","."); ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo "Rp ".number_format($total_realisasi,0,".","."); ?></td>
							<td valign="top" align="right" bgcolor=""><?php echo "Rp ".number_format($sisa_pagu_anggaran,0,".","."); ?></td>
							<td valign="top" align="center" bgcolor="">
							<a href="home.php?cat=data&page=realisasi_kegiatan&act=edit&id_realisasi=<?php echo $id_realisasi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
							<a href="home.php?cat=data&page=realisasi_kegiatan&del&id_realisasi=<?php echo $id_realisasi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img src='img/hapus.png' title="Hapus" /></a>
							</td>
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
                    	<th></th>
                    	<th></th>
                    	<th>Total</th>
                    	<th><?php echo "Rp ".number_format($total_pagu_anggaran,0,".","."); ?></th>
                    	<th><?php echo "Rp ".number_format($total_total_realisasi,0,".","."); ?></th>
                    	<th><?php echo "Rp ".number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
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

<?php 
if(isset($_GET['del'])){
    $id_realisasi = isset($_GET['id_realisasi']) ? $_GET['id_realisasi'] : '';
	
    mysql_query("delete from realisasi_kegiatan WHERE id_realisasi='{$id_realisasi}'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=realisasi_kegiatan&act=tampil'</script>";
}
?>
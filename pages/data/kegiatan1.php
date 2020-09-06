<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">

<style> 
	body{
		background:#FFFFFF;
	}
    th{
        color: black;
        font: Times New Roman;
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

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
?>

<div class="tab-content">			
	<div class="panel-body" >	
			<div class="panel panel-default" style="">
				<div class="panel-heading">
				<?php if($act=="tampil"){ ?>
						<h3 class="panel-title">INPUT KEGIATAN</h3>
				<?php }elseif($act=="edit"){ ?>
						<h3 class="panel-title">EDIT KEGIATAN</h3>
				<?php }?>
				</div>
				<div class="panel-body">
				<?php if($act=="tampil"){ ?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					  <div class="box-body">
					  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-1">
								<input type="text" name="kd_rek" placeholder=""  class="form-control" />
								</div>
							</div>
			  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Uraian Kegiatan</label>
								<div class="col-sm-4">
								<input type="text" name="uraian_kegiatan" placeholder=""  class="form-control" />
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
				<?php }elseif($act=="edit"){ ?>
				<?php 
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
					$query = mysql_query("select * from kegiatan where id_kegiatan='$id_kegiatan'");
					$hasil = mysql_fetch_array($query);
					$kode_rekening	= $hasil['kode_rekening'];
					$uraian_kegiatan	= $hasil['uraian_kegiatan'];
					
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					  <div class="box-body">
					  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-2">
								<input type="text" name="kd_rek" placeholder=""  class="form-control" value="<?php echo $kode_rekening ?>" />
								</div>
							</div>
			  
						   <div class="form-group">
								<label class="col-sm-2 control-label">Uraian Kegiatan</label>
								<div class="col-sm-4">
								<input type="text" name="uraian_kegiatan" placeholder=""  class="form-control" value="<?php echo $uraian_kegiatan ?>" />
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
					$kd_rek 		= isset($_POST['kd_rek']) ? $_POST['kd_rek'] : '';
					$uraian_kegiatan 	= isset($_POST['uraian_kegiatan']) ? $_POST['uraian_kegiatan'] : '';
					$update 		= isset($_POST['update']) ? $_POST['update'] : '';
					
					if($update){
						$insert = mysql_query("update kegiatan set kode_rekening='$kd_rek',uraian_kegiatan='$uraian_kegiatan' where id_kegiatan='$id_kegiatan'")or die(mysql_error());
						if($insert){
							echo "<script>alert('Data berhasil diupdate');window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";  
						}
					}
					?>
					
					<?php }?>
				</div>
			</div>
			<?php 
			$kd_rek 		= isset($_POST['kd_rek']) ? $_POST['kd_rek'] : '';
			$uraian_kegiatan 	= isset($_POST['uraian_kegiatan']) ? $_POST['uraian_kegiatan'] : '';
			$simpan 		= isset($_POST['simpan']) ? $_POST['simpan'] : '';
			
			if($simpan){
				$insert = mysql_query("insert into kegiatan(kode_rekening,uraian_kegiatan,kseksi) values ('$kd_rek','$uraian_kegiatan','$kseksi')")or die(mysql_error());
				if($insert){
					echo "<script>alert('Data berhasil disimpan');window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";  
				}
			}
			?>
<div class="table-responsive">	
	<div class="widget-box">
		<div class="widget-header">
			<h5 class="widget-title">DAFTAR KEGIATAN</h5>

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
							<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
							<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
							<td style="text-align:center"><font color="">AKSI</font></td>
						</tr>
                    </thead>
					<tbody>
							<?php 
							$no = 1;
								$query = mysql_query("select * from kegiatan where kseksi='$kseksi'");
								while ($hasil = mysql_fetch_array($query)){
								$id_kegiatan	= $hasil['id_kegiatan'];
								$kode_rekening	= $hasil['kode_rekening'];
								$uraian_kegiatan	= $hasil['uraian_kegiatan'];
							?>		

						<tr>	
							<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
							<td valign="top" align="center" bgcolor="">
							<a href="home.php?cat=data&page=kegiatan&act=edit&id_kegiatan=<?php echo $id_kegiatan ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
							<a href="home.php?cat=data&page=kegiatan&del&id_kegiatan=<?php echo $id_kegiatan ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img src='img/hapus.png' title="Hapus" /></a>
							</td>
						</tr>
						<?php }?>
					</tbody>
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
    $id_kegiatan = isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
	
    mysql_query("delete from kegiatan WHERE id_kegiatan='{$id_kegiatan}'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";
}
?>
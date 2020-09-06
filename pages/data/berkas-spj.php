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

date_default_timezone_set("Asia/Jakarta");
$act	= isset($_GET['act']) ? $_GET['act'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date("Y") ;
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$kbidang 	= $r['kbidang'];

?>

<div class="tab-content">			
	<div class="panel-body" >	
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				<?php if($act=="tampil"){ ?>
						<h3 class="panel-title">INPUT BERKAS SPJ</h3>
				<?php }elseif($act=="ubah"){ ?>
						<h3 class="panel-title">UBAH BERKAS SPJ</h3>
				<?php }?>
			</div>
			<div class="panel-body">
				<?php
					if($act=="tampil"){
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<div class="form-group">
									<label class="col-sm-2 control-label">Kegiatan</label>
									<div class="col-sm-4">
									<select name="rekening" id="rekening" class="form-control select2" required="">
										<option value="">-Pilih-</option>
										<?php
											$quer2=mysql_query("SELECT * from group_rek_spj_2 WHERE kode_rekening NOT IN (SELECT kode_rekening FROM berkas)");
											while($row=mysql_fetch_assoc($quer2)){
										?>
										<option value="<?php echo $row['kode_rekening']; ?>">
												<?php  echo $row['uraian_kegiatan']; ?>
										</option>
										<?php
											}
										?>  
									</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Detail Berkas</label>
									<div class="col-sm-4">
									<textarea name="detail" cols="30" rows="5" class="form-control" id="elm2"></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
									<button type="submit" class="btn btn-info pull-left" name="simpan" value="simpan">Simpan</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php
						if(isset($_POST['simpan'])){
							$rek = $_POST['rekening'];
							$detail = $_POST['detail'];
							$cek = mysql_num_rows(mysql_query("SELECT * FROM berkas WHERE kode_rekening='$rek'"));
							

							if ($cek == 0) {
								$hasil = mysql_query("INSERT INTO berkas(kode_rekening, detail) VALUES('$rek', '$detail')");
								echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=berkas-spj&act=tampil';</script>";
							} else {
								echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=berkas-spj&act=tampil';</script>";
							}
							
						}
					}elseif($act=="ubah"){
						$id = $_GET['id'];
						$s = mysql_fetch_assoc(mysql_query("SELECT * FROM berkas WHERE id='$id'"));
						
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								<div class="form-group">
									<label class="col-sm-2 control-label">Kegiatan</label>
									<div class="col-sm-4">
										<select name="rekening" id="kd_kegiatan" class="form-control" required="" onChange="getSpj(this)">
											<option value="">-Pilih-</option>
											<?php
												$quer2=mysql_query("select * from group_rek_spj_2");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['kode_rekening'] == $row['kode_rekening']){ echo "selected"; } ?> value="<?php echo $row['kode_rekening']; ?>">
													<?php  echo $row['uraian_kegiatan']; ?>
											</option>
											<?php
												}
											?>  
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Detail Berkas</label>
									<div class="col-sm-4">
									<textarea name="detail" cols="30" rows="5" class="form-control" id="elm2"><?php echo $s['detail']; ?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
									<button type="submit" class="btn btn-info" name="ubah" value="simpan">Simpan</button>
									<a href="home.php?cat=data&page=berkas-spj&act=tampil" class="btn btn-danger">Batal</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				<?php
						if(isset($_POST['ubah'])){
							$id = $_POST['id'];
							$rek = $_POST['rekening'];
							$detail = $_POST['detail'];
							$hasil = mysql_query("UPDATE berkas SET kode_rekening='$rek', detail='$detail' WHERE  id='$id'");

							if ($hasil) {
								echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=berkas-spj&act=tampil';</script>";
							} else {
								echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=berkas-spj&act=tampil';</script>";
							}
							
						}
					}
				?>
			</div>
		</div>
		<div class="table-responsive">	
			<div class="widget-box">
				<div class="widget-header">
					<h5 class="widget-title">DAFTAR</h5>

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
									<td style="text-align:center"><font color=""><b>Kode Rekening</b></font></td>
									<td style="text-align:center"><font color=""><b>Detail Berkas</b></font></td>
									<td style="text-align:center"><font color=""><b>Aksi</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$q = mysql_query("SELECT * FROM berkas a, group_rek_spj_2 b WHERE a.kode_rekening=b.kode_rekening");
										while($d = mysql_fetch_assoc($q)){
									?>
									<tr>	
										<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
										<td valign="top" align="center" bgcolor=""><?php echo $d['kode_rekening']."<br>".$d['uraian_kegiatan']; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $d['detail']; ?></td>
										<td valign="top" align="center" bgcolor="">
										<a href="home.php?cat=data&page=berkas-spj&act=ubah&id=<?php echo $d['id'] ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=berkas-spj&del&id=<?php echo $d['id'] ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img width="26px" src='img/remove.png' title="Hapus" /></a>
										</td>
									</tr>
									<?php
										}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="assets/lib/jquery/jquery.js"></script>
<script>
  $(function () {
		$('table').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false
		});
  });

	function getSpj(item) {
			var value = item.value;
			// console.log(value);
			var drop = $('#kd_spj');
			drop.empty();

			$.ajax({
				url: "getSpj.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					drop.html(msg);
				}
			});
		}

		$(function() {
			// $( "#datepicker" ).datepicker({ format: 'dd/mm/yyyy'});
			$('#datepicker').datepicker({
					format: "dd-mm-yyyy"
			});
			$('#datepicker2').datepicker({
					format: "dd-mm-yyyy"
			});
		});

</script>


<?php 
if(isset($_GET['del'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
    mysql_query("delete from berkas WHERE id='{$id}'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=berkas-spj&act=tampil'</script>";
}
?>
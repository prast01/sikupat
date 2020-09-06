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
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
// $a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
// $r = mysql_fetch_array($a);
// $kseksi 	= $r['kseksi'];
// $kbidang 	= $r['kbidang'];
?>

<div class="tab-content">			
	<div class="panel-body" >	
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				<h3 class="panel-title">TAMBAH NOTIFIKASI</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					<div class="box-body">
						<?php
							if(!isset($_GET['edit'])){
						?>
						<div class="form-group">
							<label class="col-sm-2 control-label">Notifikasi</label>
							<div class="col-sm-4">
								<textarea name="notif" required cols="30" rows="5" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Subbag/Seksi/UPT</label>
							<div class="col-sm-8">
								<div class="row">
									<?php
										$a = mysql_query("SELECT kseksi, nm_seksi FROM user WHERE level_user='2'");
										while($d = mysql_fetch_assoc($a)){
									?>
									<div class="col-md-4">
										<div class="checkbox">
											<label><input type="checkbox" name="seksi[]" value="<?php echo $d['kseksi']; ?>"><?php echo $d['nm_seksi']; ?></label>
										</div>
									</div>
									<?php
										}
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-4">
							<button type="submit" class="btn btn-info pull-left" name="simpan" value="simpan">Simpan</button>
							</div>
						</div>
						<?php
							} else {
								$w = mysql_fetch_assoc(mysql_query("SELECT * FROM notif WHERE id_notif='$_GET[id]'"));
						?>
						<div class="form-group">
							<label class="col-sm-2 control-label">Notifikasi</label>
							<div class="col-sm-4">
								<textarea name="notif" required cols="30" rows="5" class="form-control"><?php echo $w['notif']; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Subbag/Seksi/UPT</label>
							<div class="col-sm-8">
								<div class="row">
									<?php
										$a = mysql_query("SELECT kseksi, nm_seksi FROM user WHERE level_user='2'");
										while($d = mysql_fetch_assoc($a)){
											$x = mysql_query("SELECT * FROM notif_detail WHERE id_notif='$_GET[id]' AND kseksi='$d[kseksi]'");

											if(mysql_num_rows($x) > 0){
									?>
									<div class="col-md-4">
										<div class="checkbox">
											<label><input type="checkbox" checked name="seksi[]" value="<?php echo $d['kseksi']; ?>"><?php echo $d['nm_seksi']; ?></label>
										</div>
									</div>
									<?php
											} else {
									?>
									<div class="col-md-4">
										<div class="checkbox">
											<label><input type="checkbox" name="seksi[]" value="<?php echo $d['kseksi']; ?>"><?php echo $d['nm_seksi']; ?></label>
										</div>
									</div>
									<?php
											}
										}
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-4">
								<input type="hidden" name="id" value="<?php echo $w['id_notif']; ?>">
								<a href="home.php?cat=data&page=notif" class="btn btn-danger">Batal</a>
								<button type="submit" class="btn btn-info" name="ubah" value="simpan">Ubah</button>
							</div>
						</div>
						<?php
							}
						?>
					</div>
				</form>
			</div>
		</div>
		<?php
			if (isset($_POST['simpan'])) {
				$date = date("Y-m-d H:i:s");
				$notif = $_POST['notif'];
				$seksi = $_POST['seksi'];

				function seksi($s = array()){
					$a = mysql_fetch_assoc(mysql_query("SELECT id_notif FROM notif WHERE status='1'"));
					$id = $a['id_notif'];
					$q = 0;
					for($i=0; $i< count($s); $i++){
						$ha = mysql_query("INSERT INTO notif_detail(id_notif, kseksi) VALUES('$id', '$s[$i]')");
						if($ha){
							$q = 1;
						} else {
							$q = 0;
						}
					}

					return $q;
				}

				$q1 = mysql_query("UPDATE notif SET status='0' WHERE status='1'");

				if ($q1) {
					$q2 = mysql_query("INSERT INTO notif(notif, status, createdAt) VALUES('$notif', '1', '$date')");
					if ($q2) {
						$q3 = seksi($seksi);
						if ($q3) {
							echo "<script>alert('Data Berhasil Disimpan.'); window.location='home.php?cat=data&page=notif'</script>";
						} else {
							echo "<script>alert('Data Gagal Disimpan.'); window.location='home.php?cat=data&page=notif'</script>";
						}
					}
				} else {
					$q2 = mysql_query("INSERT INTO notif(notif, status, createdAt) VALUES('$notif', '1', '$date')");
					if ($q2) {
						$q3 = seksi($seksi);
						if ($q3) {
							echo "<script>alert('Data Berhasil Disimpan.'); window.location='home.php?cat=data&page=notif'</script>";
						} else {
							echo "<script>alert('Data Gagal Disimpan.'); window.location='home.php?cat=data&page=notif'</script>";
						}
					}
				}
				

			}

			if(isset($_POST['ubah'])){
				$id = $_POST['id'];
				$notif = $_POST['notif'];
				$seksi = $_POST['seksi'];

				function seksi($s = array(), $id){
					$q = 0;
					for($i=0; $i< count($s); $i++){
						$ha = mysql_query("INSERT INTO notif_detail(id_notif, kseksi) VALUES('$id', '$s[$i]')");
						if($ha){
							$q = 1;
						} else {
							$q = 0;
						}
					}

					return $q;
				}

				function delSeksi($id){
					$q = 0;
					$a = mysql_query("DELETE FROM notif_detail WHERE id_notif='$id'");
					if ($a) {
						$q = 1;
					} else {
						$q = 0;
					}

					return $q;
					
				}

				$q1 = mysql_query("UPDATE notif SET notif='$notif' WHERE id_notif='$id'");
				if ($q1) {
					$q2 = delSeksi($id);
					if ($q2) {
						$q3 = seksi($seksi, $id);
						if ($q3) {
							echo "<script>alert('Data Berhasil Diubah.'); window.location='home.php?cat=data&page=notif'</script>";
						} else {
							echo "<script>alert('Data Berhasil Diubah.'); window.location='home.php?cat=data&page=notif'</script>";
						}
					} else {
						echo "<script>alert('Data Gagal Diubah.'); window.location='home.php?cat=data&page=notif'</script>";
					}
				} else {
					echo "<script>alert('Data Gagal Diubah.'); window.location='home.php?cat=data&page=notif'</script>";
				}
			}
		?>
		<div class="table-responsive">	
			<div class="widget-box">
				<div class="widget-header">
					<h5 class="widget-title">DAFTAR NOTIFIKASI</h5>
				</div>
				<div class="widget-body">
					<div class="widget-main">		
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<td style="text-align:center"><font color=""><b>NO</b></font></td>
									<td style="text-align:center"><font color=""><b>Notifikasi</b></font></td>
									<td style="text-align:center"><font color=""><b>Status</b></font></td>
									<td style="text-align:center"><font color=""><b>Aksi</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$query = mysql_query("SELECT * FROM notif ORDER BY createdAt DESC");
										while ($hasil = mysql_fetch_array($query)){
											$id = $hasil['id_notif'];
											$notif = $hasil['notif'];
											if ($hasil['status'] == '0') {
												$status = "OFF";
											} else {
												$status = "ON";
											}
											
									?>
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $notif; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $status; ?></td>
									<td valign="top" align="center" bgcolor="">
										<?php
										if(isset($_GET['id'])){
											if($id != $_GET['id']){
										?>
										<a href="home.php?cat=data&page=notif&edit&id=<?php echo $id ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=notif&del&id=<?php echo $id ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img src='img/hapus.png' title="Hapus" /></a>
										<?php
											}
										} else {
										?>
										<a href="home.php?cat=data&page=notif&edit&id=<?php echo $id ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=notif&del&id=<?php echo $id ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img src='img/hapus.png' title="Hapus" /></a>
										<?php
										}
										?>
									</td>
								</tr>
								<?php }?>
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
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
    mysql_query("DELETE from notif WHERE id_notif='$id'")or die(mysql_error());
    mysql_query("DELETE from notif_detail WHERE id_notif='$id'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=notif'</script>";
}
?>
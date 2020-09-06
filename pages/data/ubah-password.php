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

// $username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
// $a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
// $r = mysql_fetch_array($a);
// $kseksi 	= $r['kseksi'];
// $password 	= $r['password'];

?>

<div class="tab-content">			
	<div class="panel-body" >	
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				<h3 class="panel-title">Ubah Password</h3>
			</div>
			<div class="panel-body">
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<div class="form-group">
									<label class="col-sm-2 control-label">Password Lama</label>
									<div class="col-sm-4">
									<input type="text" class="form-control" disabled name="password-lama" value="<?php echo $password; ?>">
									<input type="hidden" name="kseksi" value="<?php echo $kseksi; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Password Baru</label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="password-baru">
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
						if (isset($_POST['simpan'])) {
							$password_baru = $_POST['password-baru'];
							$kseksi = $_POST['kseksi'];

							mysql_query("UPDATE user SET password='$password_baru' WHERE kseksi='$kseksi'");

							echo "<script>alert('Password Berhasil Disimpan'); location.href='home.php?cat=data&page=ubah-password';</script>";
						}
					?>
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
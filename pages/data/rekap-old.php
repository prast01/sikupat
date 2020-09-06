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
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date("Y") ;
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;


$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$kbidang 	= $r['kbidang'];
$mon = array(
	'01' => 'Januari',
	'02' => 'Februari',
	'03' => 'Maret',
	'04' => 'April',
	'05' => 'Mei',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'Agustus',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'November',
	'12' => 'Desember',
);


?>

<div class="tab-content">			
	<div class="panel-body" >	
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				<h3 class="panel-title">REKAP PENERIMAAN DATA SPJ</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">	
					<div class="widget-box">
						<div class="widget-body">
							<div class="widget-main">	
								<!-- <div class="row">
									<div class="col-sm-12">
										<form action="" method="post">
											<div class="form-group">
												<div class="col-sm-1">
													<select name="tahun" class="form-control">
														<?php
															for ($i=date("Y"); $i > (date("Y") - 5) ; $i--) { 
																echo "<option ";
																if ($i == $tahun) {
																	echo "selected";
																}
																echo " value='$i'>$i</option>";
															}
														?>
													</select>
												</div>
												<div class="col-sm-1">
													<select name="bulan" class="form-control">
														<?php
															for ($i=1; $i <= 12 ; $i++) {
																if ($i < 10) {
																	$i = '0'.$i;
																}
																echo "<option ";
																if ($i == $bulan) {
																	echo "selected";
																}
																echo " value='$i'>$mon[$i]</option>";
															}
														?>
													</select>
												</div>
												<div class="col-sm-2">
													<button type="submit" name="lihat" class="btn btn-primary btn-sm">Cari</button>&nbsp;&nbsp;
												</div>
											</div>
										</form>
									</div>
								</div> -->
								<table id="table1" class="table table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<td style="text-align:center"><font color=""><b>No</b></font></td>
											<td style="text-align:center"><font color=""><b>Bulan</b></font></td>
											<td style="text-align:center"><font color=""><b>Jumlah SPJ Dalam Proses</b></font></td>
											<td style="text-align:center"><font color=""><b>Jumlah SPJ Ditolak</b></font></td>
											<td style="text-align:center"><font color=""><b>Jumlah SPJ Divalidasi</b></font></td>
											<td style="text-align:center"><font color=""><b>Jumlah SPJ Sudah Ditransfer</b></font></td>
											<td style="text-align:center"><font color=""><b>Jumlah Total SPJ</b></font></td>
										</tr>
									</thead>
									<tbody>
										<?php
											for($i = 1; $i <= 12 ; $i++){
												if($i < 10){
													$b = '0'.$i;
												} else {
													$b = $i;
												}

												$q = "SELECT * FROM bibs WHERE YEAR(tgl_kegiatan)='$tahun' AND MONTH(tgl_kegiatan)='$b' AND kseksi != '' AND kbidang != ''";
												$proses = 0;
												$tolak = 0;
												$valid = 0;
												$trans = 0;

												$qu = mysql_query($q);
												while ($dat = mysql_fetch_assoc($qu)) {
													if ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '0' && $dat['validasi'] == '0') {
														$proses++;
													} elseif ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '1' && $dat['validasi'] == '0') {
														$tolak++;
													} elseif ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '0' && $dat['validasi'] == '1') {
														$valid++;
													} else {
														$trans++;
													}
												}

												$total = $proses+$tolak+$valid+$trans;
										?>
										<tr>
											<td style="text-align:center"><font color=""><b><?php echo $i; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $mon[$b]; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $proses; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $tolak; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $valid; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $trans; ?></b></font></td>
											<td style="text-align:center"><font color=""><b><?php echo $total; ?></b></font></td>
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

		function getBerkas(item) {
			var value = item.value;
			// console.log(value);
			var drop = $('#div');

			$.ajax({
				url: "getBerkas.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					drop.html(msg);
				}
			});

			$.ajax({
				url: "getBerkas2.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					if (msg === '1') {
						document.getElementById('pelaksana').style.display = 'none';
						document.getElementById('pelaksana2').style.display = 'block';
						console.log(msg);
					} else {
						document.getElementById('pelaksana').style.display = 'block';
						document.getElementById('pelaksana2').style.display = 'none';
						console.log(msg);
					}
				}
			});

			$("#peg").val("");
			$("#peg2 option:selected").removeAttr("selected");
			$("#rup").val("");

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

		
		var ct = 0;

		function new_form() {
			ct++;
			var c = ct;
			c--;
			var drop = $('#pelaksana2');
			if(ct === 1){
				var space = $('#space0');
			} else {
				var space = $('#space'+c);
			}
			$.ajax({
				url: "getBerkas3.php",
				data: "id="+ct,
				cache: false,
				success: function(msg){
					// drop.html(msg).after('<div id="space"></div>');
					space.after(msg);
				}
			});
		}
// function to delete the newly added set of elements
		function delt(eleId)
		{
			var elem = document.getElementById(eleId);
			elem.parentNode.removeChild(elem);
			// return false;
		}
</script>


<?php 
if(isset($_GET['del'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$id'");
    mysql_query("delete from bibs WHERE kd_transaksi='{$id}'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['valid'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET validasi='1' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Divalidasi'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['batal'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET validasi='0' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Dibatalkan'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
// if(isset($_GET['buka'])){
//     $id = isset($_GET['id']) ? $_GET['id'] : '';
	
// 		mysql_query("UPDATE bibs SET tolak='0', alasan='' WHERE kd_transaksi='$id'");
// 		mysql_query("UPDATE bibs SET tolak='0' WHERE kd_transaksi='$id'");
//     echo "<script>alert('Data Berhasil Dibuka Kembali'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
// }
?>
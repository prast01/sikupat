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
								<table class="table table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<td rowspan="3" style="text-align:center; vertical-align:middle;" width="2%"><b>No</b></td>
											<td rowspan="3" style="text-align:center; vertical-align:middle;" width="8%"><b>Bulan</b></td>
											<td colspan="10" style="text-align:center" width="57%"><b>Pengajuan SPJ</b></td>
											<td rowspan="2" colspan="2" style="text-align:center; vertical-align:middle;" width="12%"><b>SPJ LS</b></td>
											<td rowspan="2" colspan="2" style="text-align:center; vertical-align:middle;" width="12%"><b>SPJ Sudah Ditransfer</b></td>
											<td rowspan="2" colspan="2" style="text-align:center; vertical-align:middle;" width="12%"><b>Total SPJ</b></td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center"><b>SPJ Belum Diterima</b></td>
											<td colspan="2" style="text-align:center"><b>SPJ Dalam Proses</b></td>
											<td colspan="2" style="text-align:center"><b>SPJ Direvisi</b></td>
											<td colspan="2" style="text-align:center"><b>SPJ Divalidasi</b></td>
											<td colspan="2" style="text-align:center"><b>Total</b></td>
										</tr>
										<tr>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
											<td style="text-align:center">SPJ</td>
											<td style="text-align:center">Rp</td>
										</tr>
									</thead>
									<tbody>
										<?php
											$tpenga = 0;
											$tsemua = 0;
											$ttrans = 0;
											// $tvalid = 0;
											// $ttolak = 0;
											$gt = 0;
											$gt2 = 0;
											$gt3 = 0;
											$gtl = 0;
											$tls = 0;

											$s = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) as total FROM spj"));
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
												$daftar = 0;
												$ls = 0;

												$ndaftar = 0;
												$nproses = 0;
												$ntolak = 0;
												$nvalid = 0;
												$ntrans = 0;
												$nls = 0;
												
												$qu = mysql_query($q);
												while ($dat = mysql_fetch_assoc($qu)) {
													if ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '0' && $dat['validasi'] == '0' && $dat['terima'] == '0' && $dat['jenis'] == '0' && $dat['merah'] != '1') {
														$daftar++;
														$ndaftar = $ndaftar + $dat['nominal'];
													} elseif ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '0' && $dat['validasi'] == '0' && $dat['terima'] == '1' && $dat['jenis'] == '0' && $dat['merah'] != '1') {
														$proses++;
														$nproses = $nproses + $dat['nominal'];
													} elseif ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '1' && $dat['validasi'] == '0' && $dat['jenis'] == '0' && $dat['merah'] != '1') {
														$tolak++;

														if ($b == date('m') || $b == (date('m')-1)) {
															$ntolak = $ntolak + $dat['nominal'];
														}
													} elseif ($dat['tgl_transfer'] == '0000-00-00' && $dat['tolak'] == '0' && $dat['validasi'] == '1' && $dat['jenis'] == '0' && $dat['merah'] != '1') {
														$valid++;
														// if ($b == date('m') || $b == (date('m')-1)) {
															$nvalid = $nvalid + $dat['nominal'];
														// }
													} elseif ($dat['tgl_transfer'] != '0000-00-00' && $dat['jenis'] == '0' && $dat['merah'] != '1') {
														$trans++;
														$ntrans = $ntrans + $dat['nominal'];
													} elseif($dat['jenis'] == '1' && $dat['merah'] != '1'){
														$ls++;
														$nls = $nls + $dat['nominal'];
													}

												}

												$total = $daftar+$proses+$tolak+$valid;
												$ntotal = $ndaftar+$nproses+$ntolak+$nvalid;
												$total2 = $total+$trans+$ls;
												$ntotal2 = $ntotal+$ntrans+$nls;

												$ttrans = $ttrans + $ntrans;
												$tpenga = $tpenga + $ntotal;
												$tls = $tls + $nls;

												$gt = $gt + $total;
												$gt2 = $gt2 + $trans;
												$gt3 = $gt3 + $total2;
												$gtl = $gtl + $ls;
										?>
										<tr>
											<td style="text-align:center"><b><?php echo $i; ?></b></td>
											<td style="text-align:center"><b><?php echo $mon[$b]; ?></b></td>
											<td style="text-align:center"><b><?php echo $daftar; ?></b></td>
											<td style="text-align:right"><?php echo number_format($ndaftar, 0, ',', '.'); ?></td>
											<td style="text-align:center"><b><?php echo $proses; ?></b></td>
											<td style="text-align:right"><?php echo number_format($nproses, 0, ',', '.'); ?></td>
											<td style="text-align:center"><b><?php echo $tolak; ?></b></td>
											<td style="text-align:right">
											<?php
												if ($b == date('m') || $b == (date('m')-1)) {
													// $ttolak = $ttolak + $ntolak;
													echo "".number_format($ntolak, 0, ',', '.'); 
												} 
											?>
											</td>
											<td style="text-align:center"><b><?php echo $valid; ?></b></td>
											<td style="text-align:right">
											<?php
												// if ($b == date('m') || $b == (date('m')-1)) {
													// $tvalid = $tvalid + $nvalid;
													echo "".number_format($nvalid, 0, ',', '.'); 
												// } 
											?>
											</td>
											<td style="text-align:center"><b><?php echo $total; ?></b></td>
											<td style="text-align:right"><?php echo number_format($ntotal, 0, ',', '.'); ?></td>
											<td style="text-align:center"><b><?php echo $ls; ?></b></td>
											<td style="text-align:right"><?php echo number_format($nls, 0, ',', '.'); ?></td>
											<td style="text-align:center"><b><?php echo $trans; ?></b></td>
											<td style="text-align:right"><?php echo number_format($ntrans, 0, ',', '.'); ?></td>
											<td style="text-align:center"><b><?php echo $total2; ?></b></td>
											<td style="text-align:right"><?php echo number_format($ntotal2, 0, ',', '.'); ?></td>
										</tr>
										<?php
											}

											$ttrans = ($s['total'] + $ttrans);
											$tsemua = $tpenga + $ttrans + $tls;
										?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="10" style="text-align:right">TOTAL</th>
											<th style="text-align:center"><b><?php echo $gt; ?></b></th>
											<th style="text-align:right"><?php echo number_format($tpenga, 0, ',', '.'); ?></th>
											<th style="text-align:center"><b><?php echo $gtl; ?></b></th>
											<th style="text-align:right"><?php echo number_format($tls, 0, ',', '.'); ?></th>
											<th style="text-align:center"><b><?php echo $gt2; ?></b></th>
											<th style="text-align:right"><?php echo number_format($ttrans, 0, ',', '.'); ?></th>
											<th style="text-align:center"><b><?php echo $gt3; ?></b></th>
											<th style="text-align:right"><?php echo number_format($tsemua, 0, ',', '.'); ?></th>
										</tr>
										<!-- <tr>
											<th colspan="2">TOTAL</th>
											<th style="text-align:center">Rp. <?php echo number_format($tdaftar, 0, ',', '.'); ?></th>
											<th style="text-align:center">Rp. <?php echo number_format($tproses, 0, ',', '.'); ?></th>
											<th style="text-align:center">Rp. <?php echo number_format($ttolak, 0, ',', '.'); ?></th>
											<th style="text-align:center">Rp. <?php echo number_format($tvalid, 0, ',', '.'); ?></th>
											<th></th>
											<th style="text-align:center">Rp. <?php echo number_format($ttrans, 0, ',', '.'); ?></th>
											<th style="text-align:center"></th>
										</tr>
										<tr>
											<th colspan="7">GRAND TOTAL</th>
											<th style="text-align:center">Rp. <?php echo number_format($gt, 0, ',', '.'); ?></th>
											<th style="text-align:center"></th>
										</tr> -->
									</tfoot>
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
		$('#table1').DataTable({
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
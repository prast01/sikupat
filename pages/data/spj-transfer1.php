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
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date("Y") ;
// $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;
?>

<div class="tab-content">			
	<div class="panel-body" >
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
						<div class="row">
							<div class="col-sm-12">
								<form action="" method="post">
									<div class="form-group">
										<div class="col-sm-1">
											<!-- <select name="tahun" class="form-control">
												<?php
													for ($i=date("Y"); $i > (date("Y") - 5) ; $i--) { 
														echo "<option ";
														if ($i == $tahun) {
															echo "selected";
														}
														echo " value='$i'>$i</option>";
													}
												?>
											</select> -->
										</div>
										<div class="col-sm-1">
											<!-- <select name="bulan" class="form-control">
												<?php
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
											</select> -->
										</div>
										<div class="col-sm-1">
											<!-- <button type="submit" name="lihat" class="btn btn-primary btn-sm">Cari</button> -->
										</div>
									</div>
								</form>
							</div>
						</div>
						<table id="" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<!-- <td style="text-align:center"><font color=""><b>NO</b></font></td> -->
									<td style="text-align:center"><font color=""><b>Urutan SPJ</b></font></td>
									<td style="text-align:center"><font color=""><b>Kode Rekening</b></font></td>
									<td style="text-align:center"><font color=""><b>Uraian</b></font></td>
									<td style="text-align:center"><font color=""><b>Nominal</b></font></td>
									<td style="text-align:center"><font color=""><b>Pelaksana</b></font></td>
									<td style="text-align:center"><font color=""><b>Tgl Kegiatan</b></font></td>
									<td style="text-align:center"><font color=""><b>Tgl Transfer</b></font></td>
									<td style="text-align:center"><font color=""><b>Seksi/Subag/UPT</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										// if ($kseksi == 'DJ001') {
										// 	$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_transfer)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer != '0000-00-00' AND a.kseksi != '' AND c.gol='1' order by a.id ASC");
										// } else {
											$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima, e.nm_seksi FROM bibs a, spj c, kegiatan d, user e WHERE YEAR(a.tgl_transfer)='$tahun' AND a.id_spj=c.id_spj AND a.kseksi=e.kseksi AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer != '0000-00-00' AND c.kode_rekening = '52206001' order by a.kseksi, a.tgl_kegiatan ASC");
										// }
										$total = 0;
										while ($hasil = mysql_fetch_array($query)){
											$id = $hasil['id'];
											$kd_transaksi = $hasil['kd_transaksi'];
											if ($hasil['tgl_transfer'] == '0000-00-00') {
												$tgl_transfer = "-";
											} else {
												$tgl_transfer = $hasil['tgl_transfer'];
											}
											$nm_seksi = $hasil['nm_seksi'];
											$tgl_kegiatan = $hasil['tgl_kegiatan'];
											$nominal = number_format($hasil['nominal'],0,',','.');
											$total = $total + $hasil['nominal'];

											$kode_rek = $hasil['kode_rekening'];
											$uraian = $hasil['uraian'];
									?>		

									<tr>	
										<td valign="top" align="center" bgcolor=""><?php echo $no; ?></td>
										<!-- <td valign="top" align="center" bgcolor=""><?php echo sprintf("%05s", $id); ?></td> -->
										<td valign="top" align="left" bgcolor="">
											<!-- <a href="#" title="Nama Kegiatan" data-toggle="popover" data-trigger="focus" data-content="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a> -->
											<a href="#" title="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $uraian; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nominal; ?></td>
										<td valign="top" align="left" bgcolor="">
												<?php
														$a = mysql_query("SELECT * FROM bibs_detail a, pegawai b WHERE a.kd_peg=b.kd_peg AND a.kd_transaksi='$kd_transaksi'");
														if (mysql_num_rows($a) > 0) {
															echo "<ol>";
															while ($da = mysql_fetch_assoc($a)) {
																	echo "<li>".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']."</li>";
																
															}
															echo "</ol>";
														} else {
															echo "-";
														}
												?>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $tgl_kegiatan; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $tgl_transfer; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
									</tr>
								<?php
										$no++;
									}
								?>
							</tbody>
							<tfoot>
								<tr>
										<td colspan="3" align="right"><b>Total Keseluruhan</b></td>
										<td><b><?php echo number_format($total,0,',','.'); ?></b></td>
										<td colspan="4"></td>
								</tr>
							</tfoot>
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
if(isset($_GET['buka'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET tolak='0', alasan='' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Dibuka Kembali'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
?>
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
$id_spj	= isset($_GET['spj']) ? $_GET['spj'] : '';

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$level_user = $r['level_user'];

$bulan = (isset($_POST['bulan'])) ? $_POST['bulan'] : 'all' ;
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

if (isset($_POST['excel'])) {
	$url = "http://sikupat2020.sikdkkjepara.net/excel-spj.php?id_spj=$id_spj&bulan=$bulan";
	echo '<script>window.open ("'.$url.'", "_blank")</script>';
}
?>
<div class="tab-content">			
	<div class="panel-body" >	
			<div class="panel panel-default" style="">
				<div class="panel-heading">
						<h3 class="panel-title">Pengajuan SPJ</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form action="" method="post">
								<div class="form-group">
									<div class="col-md-1">
										Bulan
									</div>
									<div class="col-md-2">
										<select name="bulan" class="form-control">
											<option <?php if($bulan == 'all'){echo "selected"; } ?> value="all">Semua</option>
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
									<div class="col-md-1">
										<button type="submit" class="btn btn-primary btn-sm" name="cari">Cari</button>
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-success btn-sm" name="excel">Export Excel</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">	
								<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
									<table id="table1" class="table table-bordered table-hover" width="100%">
										<thead>
											<tr>		
												<td style="text-align:center"><font color=""><b>NO</b></font></td>
												<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
												<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
												<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
												<td style="text-align:center"><font color=""><b>TGL KEGIATAN</b></font></td>
												<td style="text-align:center"><font color=""><b>TGL TRANSFER</b></font></td>
												<td style="text-align:center"><font color=""><b>NOMINAL</b></font></td>
											</tr>
										</thead>
										<tbody>
												<?php 
													$no = 1;
													if (isset($_POST['bulan']) && $_POST['bulan'] != 'all') {
														$query = mysql_query("SELECT b.kode_rekening, a.*, c.nm_seksi FROM bibs a, spj b, user c WHERE a.id_spj=b.id_spj AND a.kseksi=c.kseksi AND a.id_spj='$id_spj' AND a.tgl_transfer != '0000-00-00' AND MONTH(a.tgl_kegiatan) = '$bulan'");
													} else {
														$query = mysql_query("SELECT b.kode_rekening, a.*, c.nm_seksi FROM bibs a, spj b, user c WHERE a.id_spj=b.id_spj AND a.kseksi=c.kseksi AND a.id_spj='$id_spj' AND a.tgl_transfer != '0000-00-00'");
													}
													
													$total = 0;
													while ($hasil = mysql_fetch_array($query)){
														$kode_rekening = $hasil['kode_rekening'];
														$uraian_kegiatan = $hasil['uraian'];
														$tgl_kegiatan = $hasil['tgl_kegiatan'];
														$tgl_transfer = $hasil['tgl_transfer'];
														$nominal = $hasil['nominal'];
														$nama = $hasil['nm_seksi'];
														$total = $total+$nominal;
												?>		
					
											<tr>	
												<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $nama; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $tgl_kegiatan; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $tgl_transfer; ?></td>
												<td valign="top" align="right" bgcolor="">
												<?php
														echo number_format($nominal, 0, ',', '.');
												?>
												</td>
											</tr>
											<?php 
													error_reporting(0);
												}
											?>
										</tbody>
										<tfoot>
											<tr>
												<th colspan="6">Total</th>
												<th><?php echo number_format($total,0,".","."); ?></th>
											</tr>
										</tfoot>
									</table>
								</form>
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
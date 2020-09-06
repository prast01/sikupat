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

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$level_user 	= $r['level_user'];

?>

<div class="tab-content">			
	<div class="panel-body" >	
			<div class="panel panel-default" style="">
				<?php if($act=="edit"){ ?>
				<div class="panel-heading">
						<h3 class="panel-title">INPUT SPJ</h3>
				</div>
				<?php }?>
				<div class="panel-body">
				<?php if($act=="edit"){ ?>
				<?php 
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<td style="text-align:center"><font color=""><b>NO</b></font></td>
									<td style="text-align:center"><font color=""><b>KODE REKENING</b></font></td>
									<td style="text-align:center"><font color=""><b>URAIAN KEGIATAN</b></font></td>
									<td style="text-align:center"><font color=""><b>PAGU ANGGARAN</b></font></td>
									<td style="text-align:center"><font color=""><b>TOTAL REALISASI</b></font></td>
									<td style="text-align:center"><font color=""><b>SISA PAGU ANGGARAN</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$query = mysql_query("select * from spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'");
										while ($hasil = mysql_fetch_array($query)){
										$id_spj	= $hasil['id_spj'];
										$kode_rekening	= $hasil['kode_rekening'];
										$pagu_anggaran	= $hasil['pagu_anggaran'];
										$total_realisasi	= $hasil['total_realisasi'];
										$uraian_kegiatan	= $hasil['uraian_kegiatan'];
										$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
									?>		
		
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $kode_rekening; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
									<td valign="top" align="right" bgcolor=""><?php echo number_format($pagu_anggaran,0,".","."); ?></td>
									<td valign="top" align="right" bgcolor=""><input name="total_realisasi<?php echo $id_spj; ?>" type="text" id="total_realisasi<?php echo $id_spj; ?>" value="<?php echo $total_realisasi; ?>" onChange="update_total_realisasi(this,<?php echo $id_spj; ?>)" /></td>
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
									<th><?php echo "Rp ".number_format($total_pagu_anggaran,0,".","."); ?></th>
									<th><?php echo "Rp ".number_format($total_total_realisasi,0,".","."); ?></th>
									<th><?php echo "Rp ".number_format($total_sisa_pagu_anggaran,0,".","."); ?></th>
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
							<td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
							<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
							<td style="text-align:center"><font color=""><b>BIDANG</b></font></td>
							<td style="text-align:center"><font color=""><b>Aksi</b></font></td>
						</tr>
                    </thead>
					<tbody>
							<?php 
							$no = 1;
							//$kseksi	= isset($_GET['kseksi']) ? $_GET['kseksi'] : '';
							if($level_user=='4'){
								$query = mysql_query("select * from kegiatan order by kbidang, kseksi");
							}else{
								$query = mysql_query("select * from kegiatan where kseksi='$kseksi'");
							}
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
								
							?>		

						<tr>	
							<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
							<td valign="top" align="left" bgcolor=""><?php echo $nm_bidang; ?></td>
							<td valign="top" align="center" bgcolor="">
							<a href="home.php?cat=data&page=spj&act=edit&id_kegiatan=<?php echo $id_kegiatan ?>">SPJ</a>&nbsp;&nbsp;
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
</script>
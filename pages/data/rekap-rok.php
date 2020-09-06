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
$bulan = isset($_REQUEST['bulan']) ? $_REQUEST['bulan'] : '';
$ckseksi = isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';

$bln = date("m");
if($bln==01){
	$bulan2 = "b1";
}elseif($bln==01){
	$bulan2 = "b2";
}elseif($bln==02){
	$bulan2 = "b3";
}elseif($bln==03){
	$bulan2 = "b4";
}elseif($bln==04){
	$bulan2 = "b5";
}elseif($bln==05){
	$bulan2 = "b6";
}elseif($bln==06){
	$bulan2 = "b7";
}elseif($bln==07){
	$bulan2 = "b8";
}elseif($bln==08){
	$bulan2 = "b9";
}elseif($bln==09){
	$bulan2 = "b11";
}elseif($bln==10){
	$bulan2 = "b12";
}elseif($bln==12){
	$bulan2 = "b13";
}

?>

<div class="tab-content">			
	<div class="panel-body" >
			<div class="panel panel-default" style="">
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
				<div class="form-group">
					<label class="col-sm-2 control-label">Seksi/Subag</label>
					<div class="col-sm-4">
						<select class="form-control" name="ckseksi" id="ckseksi">
								<option value="">-All-</option>
								<?php
								$query = mysql_query("select * from user where level_user='2' and kseksi not in ('DJ014','DJ015','DJ016')");
								while ($hasil = mysql_fetch_array($query)){
								$kseksi	= $hasil['kseksi'];
								$nm_seksi	= $hasil['nm_seksi'];
								?>
								<option <?php if($ckseksi == $kseksi){ echo "selected"; } ?> value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
								<?php 
								} 
								?>
						</select>
					</div>
				</div>
			
				<div class="form-group">
					<label class="col-sm-2 control-label">Bulan</label>
					<div class="col-sm-4">
                        <select name="bulan" id="bulan" class="form-control">
                            <?php 
                            if($bulan==""){
                                
                                if($bl=="b1"){
                            ?>
                                <option value="b1">Januari</option>
                            <?php
                            }elseif ($bl=="b2") {
                            ?>    
                                <option value="b2">Februari</option>
                            <?php
                            }elseif ($bl=="b3") {
                            ?>    
                                <option value="b3">Maret</option>
                            <?php
                            }elseif ($bl=="b4") {
                            ?>    
                                <option value="b4">April</option>
                            <?php
                            }elseif ($bl=="b5") {
                            ?>    
                                <option value="b5">Mei</option>
                            <?php
                            }elseif ($bl=="b6") {
                            ?>    
                                <option value="b6">Juni</option>
                            <?php
                            }elseif ($bl=="b7") {
                            ?>    
                                <option value="b7">Juli</option>
                            <?php
                            }elseif ($bl=="b8") {
                            ?>    
                                <option value="b8">Agustus</option>
                            <?php
                            }elseif ($bl=="b9") {
                            ?>    
                                <option value="b9">September</option>
                            <?php
                            }elseif ($bl=="b10") {
                            ?>    
                                <option value="b10">Oktober</option>
                            <?php
                            }elseif ($bl=="b11") {
                            ?>    
                                <option value="b11">November</option>
                            <?php
                            }elseif ($bl=="b12") {
                            ?>    
                                <option value="b12">Desember</option>
                            <?php
                                }
                            ?>
                                
                                <option value="b1">Januari</option>
                                <option value="b2">Februari</option>
                                <option value="b3">Maret</option>
                                <option value="b4">April</option>
                                <option value="b5">Mei</option>
                                <option value="b6">Juni</option>
                                <option value="b7">Juli</option>
                                <option value="b8">Agustus</option>
                                <option value="b9">September</option>
                                <option value="b10">Oktober</option>
                                <option value="b11">November</option>
                                <option value="b12">Desember</option>
                                
                                
                            <?php    
                            }else{
                            ?>
                            
                            <?php 
                            if($bulan=="b1"){
                            ?>
                                <option value="b1">Januari</option>
                            <?php
                            }elseif ($bulan=="b2") {
                            ?>    

                                <option value="b2">Februari</option>
                            <?php
                            }elseif ($bulan=="b3") {
                            ?>    
                                <option value="b3">Maret</option>
                            <?php
                            }elseif ($bulan=="b04") {
                            ?>    
                                <option value="b4">April</option>
                            <?php
                            }elseif ($bulan=="b5") {
                            ?>    
                                <option value="b5">Mei</option>
                            <?php
                            }elseif ($bulan=="b6") {
                            ?>    
                                <option value="b6">Juni</option>
                            <?php
                            }elseif ($bulan=="b7") {
                            ?>    
                                <option value="b7">Juli</option>
                            <?php
                            }elseif ($bulan=="b8") {
                            ?>    
                                <option value="b8">Agustus</option>
                            <?php
                            }elseif ($bulan=="b9") {
                            ?>    
                                <option value="b9">September</option>
                            <?php
                            }elseif ($bulan=="b10") {
                            ?>    
                                <option value="b10">Oktober</option>
                            <?php
                            }elseif ($bulan=="b11") {
                            ?>    
                                <option value="b11">November</option>
                            <?php
                            }elseif ($bulan=="b12") {
                            ?>    
                                <option value="b12">Desember</option>
                            <?php
                            }
                            ?>
                            <option value="b1">Januari</option>
                            <option value="b2">Februari</option>
                            <option value="b3">Maret</option>
                            <option value="b4">April</option>
                            <option value="b5">Mei</option>
                            <option value="b6">Juni</option>
                            <option value="b7">Juli</option>
                            <option value="b8">Agustus</option>
                            <option value="b9">September</option>
                            <option value="b10">Oktober</option>
                            <option value="b11">November</option>
                            <option value="b12">Desember</option>
                            <?php 
                            }
                            ?>
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"></label>
					<div class="col-sm-1 col-xs-12">
						<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
					</div>
				</div>
            </form>			
				<div class="panel-body">
				<div class="table-responsive">	
				
				<div class="widget-header">			
					<h5 class="widget-title">REKAP ROK</h5>
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
				</div>
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<td style="text-align:center"><font color=""><b>NO</b></font></td>
									<td style="text-align:center"><font color=""><b>KEGIATAN</b></font></td>
									<td style="text-align:center"><font color=""><b>SEKSI</b></font></td>
									<td style="text-align:center"><font color=""><b>ROK</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										$lihat = isset($_POST['lihat']) ? $_POST['lihat'] : '';
										if($lihat){
											$query = mysql_query("select `kegiatan`.`nm_kegiatan` AS `nm_kegiatan`,`tb_valid_rok`.`$bulan` AS `valid`,`user`.`nm_seksi` AS `nm_seksi`,`tb_valid_rok`.`id_valid` AS `id_valid` from ((`kegiatan` join `tb_valid_rok` on((`kegiatan`.`id_kegiatan` = `tb_valid_rok`.`id_kegiatan`))) join `user` on((`tb_valid_rok`.`kseksi` = `user`.`kseksi`))) where (`tb_valid_rok`.`kseksi` like '%$ckseksi%') group by `tb_valid_rok`.`kseksi`,`tb_valid_rok`.`id_kegiatan`");
										}else{
											$query = mysql_query("select `kegiatan`.`nm_kegiatan` AS `nm_kegiatan`,`tb_valid_rok`.`$bulan2` AS `valid`,`user`.`nm_seksi` AS `nm_seksi`,`tb_valid_rok`.`id_valid` AS `id_valid` from ((`kegiatan` join `tb_valid_rok` on((`kegiatan`.`id_kegiatan` = `tb_valid_rok`.`id_kegiatan`))) join `user` on((`tb_valid_rok`.`kseksi` = `user`.`kseksi`))) group by `tb_valid_rok`.`kseksi`,`tb_valid_rok`.`id_kegiatan`");
										}
										while ($hasil = mysql_fetch_array($query)){
											$nm_kegiatan	= $hasil['nm_kegiatan'];
											$nm_seksi	= $hasil['nm_seksi'];
											$valid	= $hasil['valid'];
									?>		
		
								<tr>	
									<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
									<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
									<td valign="top" align="left" bgcolor="">
									<?php 
									if($valid=="1"){
									echo "ACC";
									}
									?>
									</td>
								</tr>
								<?php
								}
								?>
							</tbody>
					   </table>
					</form>
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

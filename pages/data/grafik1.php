<style>
	body{
		background:#FFFFFF;
	}

.table {
    width: 100%;
}
select.form-control {
    width: 200px;
    background-color: #fff;
    border: 1px solid #ccc;
}
</style>
<html>
<head>
    <title>Realisasi Kegiatan</title>
	<script src="pages/data/grafik/jquery.min.js"></script>
	<script src="pages/data/grafik/highcharts.js"></script>
	<script src="pages/data/grafik/exporting.js"></script>
	
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
	
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=fakultas>
	  $("#ckseksi").change(function(){
		var ckseksi = $("#ckseksi").val();
		$.ajax({
			url: "getSpj3.php",
			data: "id="+ckseksi,
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				//di <select id=progdi>
				$("#ckeg").html(msg);
			}
		});
	  });
	 });
	
	</script>
	
	
</head>
<body>

<?php
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$q 		= mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r 		= mysql_fetch_array($q);
$kseksi 	= $r['kseksi'];


$lihat 	= isset($_REQUEST['lihat']) ? $_REQUEST['lihat'] : '';
$ckseksi = isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';
$ckeg = isset($_REQUEST['ckeg']) ? $_REQUEST['ckeg'] : '';
?>
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-content nopadding">
                <div id="form-wizard-1" class="step">
                    <div class="">
						<form id="form-wizard" class="form-horizontal" method="post" onSubmit="">
							<table class="table table-bordered ">
							  <thead>
								<tr class="gradeX">
								  <th>Seksi</th>
								  <td>
									<select class="form-control" name="ckseksi" id="ckseksi" required>
									<?php if($ckseksi!=''){ ?>
										<?php
										$query=mysql_query("select * from user where level_user='2' and kseksi='$ckseksi'");
										while($row=mysql_fetch_array($query)){
										?>
										<option value="<?php  echo $row['kseksi']; ?>">
											<?php  echo $row['nm_seksi']; ?>
										</option>
										<?php 
										}
										?>  
										<option value="">-Pilih-</option>
										<?php
										$query=mysql_query("select * from user where level_user='2' AND kbidang != 'DK007'");
										while($row=mysql_fetch_array($query)){
										?>
										<option value="<?php  echo $row['kseksi']; ?>">
											<?php  echo $row['nm_seksi']; ?>
										</option>
										<?php 
										}
										?>      
										    
									<?php }else{ ?>
										<option value="">-Pilih-</option>
										<?php
										$query=mysql_query("select * from user where level_user='2' AND kbidang != 'DK007'");
										while($row=mysql_fetch_array($query)){
										?>
										<option value="<?php  echo $row['kseksi']; ?>">
											<?php  echo $row['nm_seksi']; ?>
										</option>
										<?php 
										}
										?>  
									<?php } ?>    
									</select>
									</td>
								  <th>Kegiatan</th>
								  <td>
									<select class="form-control" name="ckeg" id="ckeg" required >
									<?php if($ckeg!=''){?>
										<?php
										$query=mysql_query("select * from kegiatan where id_kegiatan='$ckeg'");
										while($row=mysql_fetch_array($query)){
										?>
										<option value="<?php  echo $row['id_kegiatan']; ?>">
											<?php  echo $row['nm_kegiatan']; ?>
										</option>
										<?php 
										}
										?>  
										<option value="">-Pilih-</option>
										<?php
										$query=mysql_query("select * from kegiatan where kseksi='$kseksi' OR id_kegiatan IN (SELECT id_kegiatan FROM spj WHERE kbidang2='DK007' AND kseksi='$kseksi' GROUP BY id_kegiatan)");
										while($row=mysql_fetch_array($query)){
										?>
										<option value="<?php  echo $row['id_kegiatan']; ?>">
											<?php  echo $row['nm_kegiatan']; ?>
										</option>
										<?php 
										}
										?>  
									<?php }else{ ?>
										<option value=""></option>
										<option value="">-Pilih Seksi Dulu-</option>
									<?php } ?>
									</select>
								  </td>
								  <th><input id="next" class="btn btn-inverse" type="submit" name="lihat" value="Ok" /></th>
								</tr>
							  </thead>
							</table>
						</form>
<?php if($lihat){ ?>							
<?php
      $rw = mysql_query("select `spj`.`uraian_kegiatan` AS `uraian_kegiatan`,sum(`spj`.`pagu_anggaran`) AS `jml_pagu_anggaran`,sum(`spj`.`total_realisasi`) AS `jml_total_realisasi`,`user`.`kseksi` AS `kseksi`,`user`.`nm_seksi` AS `nm_seksi`,`spj`.`id_kegiatan` AS `id_kegiatan` from (`user` join `spj` on((`user`.`kseksi` = `spj`.`kseksi`))) where ((`user`.`kseksi` = '$ckseksi') and (`spj`.`id_kegiatan` = '$ckeg'))")or die(mysql_error());
	  
      while($r = mysql_fetch_array($rw)){
			$kseksi = $r['kseksi'];
			
			$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS jml_total_realisasi FROM total_realisasi WHERE kseksi='$ckseksi' AND id_kegiatan='$ckeg' group by id_kegiatan"));


      $jml_pagu_anggaran = $r['jml_pagu_anggaran'];
      // $jml_total_realisasi = $r['jml_total_realisasi'];
      $jml_total_realisasi = $qa['jml_total_realisasi']+$r['jml_total_realisasi'];
	  $sisa_pagu_anggaran = $jml_pagu_anggaran-$jml_total_realisasi;      
      $nm_seksi = $r['nm_seksi'];
      $id_kegiatan = $r['id_kegiatan'];
	  
		  $q = mysql_query("select * from kegiatan where id_kegiatan='$id_kegiatan'")or die(mysql_error());
		  $r = mysql_fetch_array($q);
		  $nm_kegiatan = $r['nm_kegiatan'];
	  
	  $grafik3[] = array($nm_seksi);	  
	  $grafik4[] = array($nm_seksi, doubleval($jml_pagu_anggaran));
	  $grafik5[] = array($nm_seksi, doubleval($jml_total_realisasi));
	  }

	  $grafik3		= json_encode($grafik3);
	  $data_grafik4 = json_encode($grafik4);
	  $data_grafik5 = json_encode($grafik5);
?>


<div id="container2" style="height:400px"></div>
<script>
$(document).ready(function(){
$(function () {
    var chart = Highcharts.chart('container2', {
        chart: {
            plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
        },
		title: {
			text: 'Realisasi Kegiatan <?php echo $nm_kegiatan; ?><br> Seksi/Subag <?php echo $nm_seksi; ?><br> Pagu Anggaran <?=number_format($jml_pagu_anggaran,0,',','.')?>'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>Rp.{point.y:.f}</b>'
		},
        plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b><br>Rp.{point.y:.f}',
					distance: -50,
				}
				
				
				
			}
        },
        colors: ['#006600', '#d24087', '#4ec4ce'],
		series: [{
			name: 'Total',
			data: [
				{ name: 'Total Realisasi', y: <?=$jml_total_realisasi?> },
				{ name: 'Sisa Pagu Anggaran', y: <?=$sisa_pagu_anggaran?> },
			]
		}]
    });
 
});
});
</script>
<?php } ?>

			</div>
		</div>
	</div>
</div>
</body>
</html>
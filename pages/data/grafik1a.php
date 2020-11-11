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
  <meta http-equiv="refresh" content="10">
</head>
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
			url: "pages/data/ambil_kegiatan.php",
			data: "ckseksi="+ckseksi,
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
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-content nopadding">
                <div id="form-wizard-1" class="step">
                    <div class="">
						<form id="form-wizard" class="form-horizontal" method="post" onSubmit="">
							<table class="table table-bordered ">
							</table>
						</form>
						
<?php
	  
      $cari = mysql_query("select * from kegiatan where kirim = '0' order by kbidang,kseksi ")or die(mysql_error());
      $rc = mysql_fetch_array($cari);
      $id_kegiatan = $rc['id_kegiatan'];

      $rw = mysql_query("select `spj`.`uraian_kegiatan` AS `uraian_kegiatan`,sum(`spj`.`pagu_anggaran`) AS `jml_pagu_anggaran`,sum(`spj`.`total_realisasi`) AS `jml_total_realisasi`,`user`.`kseksi` AS `kseksi`,`user`.`nm_seksi` AS `nm_seksi`,`spj`.`id_kegiatan` AS `id_kegiatan` from (`user` join `spj` on((`user`.`kseksi` = `spj`.`kseksi`))) where (`spj`.`id_kegiatan` = '$id_kegiatan')")or die(mysql_error());

	  
      while($r = mysql_fetch_array($rw)){
      $kseksi = $r['kseksi'];
	  
	  $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS jml_total_realisasi FROM total_realisasi WHERE kseksi='$kseksi' AND id_kegiatan='$id_kegiatan' group by id_kegiatan"));

      $jml_pagu_anggaran = $r['jml_pagu_anggaran'];
      $nm_seksi = $r['nm_seksi'];
      // $jml_total_realisasi = $r['jml_total_realisasi'];
      $jml_total_realisasi = $qa['jml_total_realisasi']+$r['jml_total_realisasi'];
	  $sisa_pagu_anggaran = $jml_pagu_anggaran-$jml_total_realisasi;      
	  
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
	  
 	 $update = mysql_query("update kegiatan set kirim='1' where id_kegiatan = '$id_kegiatan'")or die(mysql_error());
	//$a = mysql_query("SELECT count(kirim) as jml FROM kegiatan where kirim='0'")or die(mysql_error());
	 $q1 = mysql_query("select count(kirim) as jml from kegiatan where kirim='0'")or die(mysql_error());
	 $r1 = mysql_fetch_array($q1);
	 $jml = $r1['jml'];
  if($jml=='0'){
    echo $jml;
//	echo "<script>window.location='http://dkk.sikdkkjepara.net/realisasi-kegiatan/home1.php?cat=data&page=grafik1b'</script>";
	echo "<script>window.location='?cat=data&page=grafik1b'</script>";
  }
  
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
			text: 'Realisasi Kegiatan <?php echo $nm_kegiatan; ?><br> Seksi/Subag <?php echo $nm_seksi; ?><br> Pagu Anggaran Rp <?php echo number_format($jml_pagu_anggaran,0,".","."); ?>,-'
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

			</div>
		</div>
	</div>
</div>
</body>
</html>
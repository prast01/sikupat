<style>
	body{
		background:#FFFFFF;
	}

.table {
    width: 100%;
}
select.form-control {
    width: 120px;
    background-color: #fff;
    border: 1px solid #ccc;
}
</style>
<html>
<head>
    <title>Realisasi Kegiatan</title>
</head>
<body>

<?php
mysql_connect("localhost","root","");
mysql_select_db("realisasi_kegiatan");
?>
<div class="tab-content">			
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-content nopadding">
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
					  $rw = mysql_query("select `spj`.`id_spj` AS `id_spj`,`spj`.`tgl_kegiatan` AS `tgl_kegiatan`,`spj`.`id_kegiatan` AS `id_kegiatan`,`spj`.`kode_rekening` AS `kode_rekening`,`spj`.`kseksi` AS `kseksi`,sum(`spj`.`pagu_anggaran`) AS `jml_pagu_anggaran`,sum(`spj`.`total_realisasi`) AS `jml_total_realisasi`,((sum(`spj`.`total_realisasi`) / sum(`spj`.`pagu_anggaran`)) * 100) AS `persen` from `spj` group by `spj`.`kseksi` order by persen desc")or die(mysql_error());
					  while($r = mysql_fetch_array($rw)){
					  $kseksi = $r['kseksi'];
					  $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS jml_total_realisasi FROM total_realisasi WHERE kseksi='$kseksi' group by kseksi"));

					  $jml_pagu_anggaran = $r['jml_pagu_anggaran'];
					//   $jml_total_realisasi = $r['jml_total_realisasi'];
					  $jml_total_realisasi = $qa['jml_total_realisasi']+$r['jml_total_realisasi'];
					//   $persen = round($r['persen'],2);
					  $persen = round(($jml_total_realisasi/$jml_pagu_anggaran)*100,2);
					  
					  $q = mysql_query("select * from user where kseksi='$kseksi'")or die(mysql_error());
					  $r = mysql_fetch_array($q);
					  $nm_seksi = $r['nm_seksi'];

					  
				
					  $grafik1[] = array($nm_seksi, doubleval($persen));
					  $grafik2[] = array($nm_seksi);
					  }
				
					  $data_grafik1 = json_encode($grafik1);
					  $grafik2		= json_encode($grafik2);
				?>

				<div id="container" style="height:400px"></div>
				<script>
				$(document).ready(function(){
				$(function () {
					var chart = Highcharts.chart('container', {
						chart: {
							type: 'column'
						},
						xAxis: {
							categories: <?=$grafik2?>
						},
						plotOptions: {
							series: {
							borderWidth: 0,
							dataLabels: {
								enabled: true,
								format: '{point.y:.2f}%'
							}
						}
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Persen'
							}
						},
						title: {
							text: 'Realisasi Kegiatan DKK Jepara',
							align: 'center',
							x: 0
						},
						colors: ['#000099','#006600', '#d24087', '#4ec4ce'],
						series: [
						{
							data: <?=$data_grafik1?>,
							name: "Seksi/Subag"
							 
						},
						]
					});
				 
					// the button action
					$('#button').click(function () {
						var selectedPoints = chart.getSelectedPoints();
				 
						if (chart.lbl) {
							chart.lbl.destroy();
						}
						chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
							.attr({
								padding: 10,
								r: 5,
								fill: Highcharts.getOptions().colors[1],
								zIndex: 5
							})
							.css({
								color: 'white'
							})
							.add();
					});
				});
				});
				</script>
			</div>
<!--			
			<div class="widget-content nopadding">
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
					  $rw = mysql_query("select `realisasi_kegiatan`.`id_realisasi` AS `id_realisasi`,`realisasi_kegiatan`.`tgl_kegiatan` AS `tgl_kegiatan`,`realisasi_kegiatan`.`id_kegiatan` AS `id_kegiatan`,`realisasi_kegiatan`.`kode_rekening` AS `kode_rekening`,`realisasi_kegiatan`.`kseksi` AS `kseksi`,sum(`realisasi_kegiatan`.`pagu_anggaran`) AS `jml_pagu_anggaran`,sum(`realisasi_kegiatan`.`total_realisasi`) AS `jml_total_realisasi`,((sum(`realisasi_kegiatan`.`total_realisasi`) / sum(`realisasi_kegiatan`.`pagu_anggaran`)) * 100) AS `persen`,(sum(`realisasi_kegiatan`.`pagu_anggaran`) - sum(`realisasi_kegiatan`.`total_realisasi`)) AS `sisa` from `realisasi_kegiatan` group by `realisasi_kegiatan`.`kseksi` order by sisa desc")or die(mysql_error());
					  while($r = mysql_fetch_array($rw)){
					  $kseksi = $r['kseksi'];
					  $jml_pagu_anggaran = $r['jml_pagu_anggaran'];
					  $jml_total_realisasi = $r['jml_total_realisasi'];
					  $jml_sisa_pagu_anggaran = $r['sisa'];
					  
					  $q = mysql_query("select * from user where kseksi='$kseksi'")or die(mysql_error());
					  $r = mysql_fetch_array($q);
					  $nm_seksi = $r['nm_seksi'];
				
					  $grafik3[] = array($nm_seksi, doubleval($jml_pagu_anggaran));
					  $grafik4[] = array($nm_seksi, doubleval($jml_total_realisasi));
					  $grafik5[] = array($nm_seksi, doubleval($jml_sisa_pagu_anggaran));
					  $grafik6[] = array($nm_seksi);
					  }
				
					  $data_grafik3 = json_encode($grafik3);
					  $data_grafik4 = json_encode($grafik4);
					  $data_grafik5 = json_encode($grafik5);
					  $grafik6		= json_encode($grafik6);
				?>

				<div id="container2" style="height:400px"></div>
				<script>
				$(document).ready(function(){
				$(function () {
					var chart = Highcharts.chart('container2', {
						chart: {
							type: 'column'
						},
						xAxis: {
							categories: <?=$grafik6?>
						},
						plotOptions: {
							series: {
							borderWidth: 0,
							dataLabels: {
								enabled: true,
								format: '{point.y:.f}'
							}
						}
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Rupiah'
							}
						},
						title: {
							text: '',
							align: 'center',
							x: 0
						},
						colors: ['#000099','#006600', '#d24087', '#4ec4ce'],
						series: [
						{
							data: <?=$data_grafik3?>,
							name: "Pagu Anggaran"
							 
						},
						{
							data: <?=$data_grafik4?>,
							name: "Total Realisasi"
							 
						},
						{
							data: <?=$data_grafik5?>,
							name: "Sisa Pagu Anggaran"
							 
						}
						]
					});
				 
					// the button action
					$('#button').click(function () {
						var selectedPoints = chart.getSelectedPoints();
				 
						if (chart.lbl) {
							chart.lbl.destroy();
						}
						chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
							.attr({
								padding: 10,
								r: 5,
								fill: Highcharts.getOptions().colors[1],
								zIndex: 5
							})
							.css({
								color: 'white'
							})
							.add();
					});
				});
				});
				</script>
			</div>			
-->			
			
		</div>
	</div>
</div>
</body>
</html>
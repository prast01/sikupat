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
						// $rw = mysql_query("SELECT * FROM user WHERE level_user='2'")or die(mysql_error());
						$rw = mysql_query("SELECT * FROM tesa")or die(mysql_error());
						
						while($r = mysql_fetch_array($rw)){
							$kseksi = $r['kseksi'];
							$nm_seksi = $r['nm_seksi'];

							$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi WHERE kseksi='$kseksi' group by kseksi"));

							// $qu = mysql_fetch_assoc(mysql_query("SELECT SUM(pagu_anggaran) AS pagu, SUM(total_realisasi) AS total_realisasi FROM spj WHERE kseksi='$kseksi' GROUP BY kseksi"));

							$realisasi = $qa['total_realisasi']+$r['total_realisasi'];
							$pagu = $r['pagu'];

							$persen_real = round(($realisasi/$pagu)*100, 2);

							//target
							$bulan = date("n");

							if($bulan == '1'){
								$bln = $bulan;
							}elseif ($bulan == '12') {
								$bln = $bulan+1;
							} else {
								$bln = $bulan;
							}
							$qe = mysql_query("SELECT * FROM target WHERE kseksi='$kseksi'");
							$target = 0;
							while ($data = mysql_fetch_assoc($qe)) {
								for($i = 1; $i < $bln; $i++){
									$target = $target+$data['b'.$i];
								}
							}

							$persen_target = round(($target/$pagu)*100, 2);


							//lebih(kurang)
							$lb = 0;
							if ($realisasi > $target) {
								$lb = $realisasi-$target;
							} else {
								$lb = $target - $realisasi;
							}

							$persen_lb = round(($lb/$pagu)*100, 2);
							
						
							$grafik1[] = array($nm_seksi, doubleval($persen_real));
							$grafik3[] = array($nm_seksi, doubleval($persen_target));
							$grafik4[] = array($nm_seksi, doubleval($persen_lb));
							$grafik2[] = array($nm_seksi);
						}
					
						$data_grafik1 = json_encode($grafik1);
						$data_grafik2 = json_encode($grafik3);
						$data_grafik3 = json_encode($grafik4);
						$grafik2		= json_encode($grafik2);

						// echo $data_grafik2;
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
						colors: ['#000099','#d24087', '#006600', '#4ec4ce'],
						series: [
						{
							data: <?=$data_grafik1?>,
							name: "Realisasi"
							 
						},
						{
							data: <?=$data_grafik2?>,
							name: "Target"
						},
						{
							data: <?=$data_grafik3?>,
							name: "Lebih/(Kurang)"
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
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
<div class="tab-content">			
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-body">
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
							<label for="">Sampai Dengan Bulan : </label>
						</div>
						<div class="col-sm-1">
							<select name="bulan" class="form-control">
								<?php
									$bulan = (isset($_POST['bulan'])) ? $_POST['bulan'] : date("m") ;;
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
							</select>
						</div>
						<div class="col-sm-2">
							<button type="submit" name="lihat" class="btn btn-primary btn-sm">Lihat</button>&nbsp;&nbsp;
							<!-- <?php
								if($kseksi == 'DJ001'){
							?>
							<a href="print-regis-spj.php?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>" target="_blank"><img src='img/print.png' title="Cetak" /></a>
							<?php
								}
							?> -->
						</div>
					</div>
				</form>
			</div>
			<div class="widget-content nopadding">
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
						$rx = mysql_query("SELECT * FROM user WHERE level_user='2'")or die(mysql_error());
						while ($a = mysql_fetch_assoc($rx)) {
							if ($a['kbidang'] != 'DK007') {
								$rw = mysql_query("SELECT * FROM tesa2")or die(mysql_error());
								
								while($r = mysql_fetch_array($rw)){
									$kseksi = $r['kseksi'];
									$nm_seksi = $r['nm_seksi'];
		
									// $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi2 WHERE kseksi='$kseksi' group by kseksi"));
									$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) AS total_realisasi FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND b.kseksi='$kseksi' AND a.tgl_transfer != '0000-00-00' AND a.validasi = '1' AND MONTH(a.tgl_transfer) <= '$bulan' GROUP BY b.kseksi"));
		
									// $qu = mysql_fetch_assoc(mysql_query("SELECT SUM(pagu_anggaran) AS pagu, SUM(total_realisasi) AS total_realisasi FROM spj WHERE kseksi='$kseksi' GROUP BY kseksi"));
		
									$realisasi = $qa['total_realisasi']+$r['total_realisasi'];
									$pagu = $r['pagu'];
		
									$persen_real = round(($realisasi/$pagu)*100, 2);
		
									//target
									// $bulan = date("n");
		
									// if($bulan == '1'){
									// 	$bln = $bulan;
									// }elseif ($bulan == '12') {
									// 	$bln = $bulan+1;
									// } else {
									// 	$bln = $bulan;
									// }
									$qe = mysql_query("SELECT * FROM target WHERE kseksi='$kseksi'");
									$target = 0;
									while ($data = mysql_fetch_assoc($qe)) {
										for($i = 1; $i <= $bulan; $i++){
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
		
									
									$ke = mysql_fetch_assoc(mysql_query("SELECT AVG(real_kegiatan) as rata FROM spj WHERE kseksi='$kseksi' AND pagu_anggaran != '0'"));
		
									mysql_query("UPDATE grafik_real SET realisasi='$persen_real', target='$persen_target', selisih='$persen_lb', kegiatan='$ke[rata]' WHERE kseksi='$kseksi'");
									
								
									// $grafik1[] = array($nm_seksi, doubleval($persen_real));
									// $grafik3[] = array($nm_seksi, doubleval($persen_target));
									// $grafik4[] = array($nm_seksi, doubleval($persen_lb));
									// $grafik2[] = array($nm_seksi);
								}
							} else {
								$rw = mysql_query("SELECT * FROM tesa2_dak")or die(mysql_error());
								
								while($r = mysql_fetch_array($rw)){
									$kseksi = $r['kseksi2'];
									$nm_seksi = $r['nm_seksi'];
									$id_keg = $r['id_kegiatan'];
		
									// $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi2 WHERE kseksi='$kseksi' group by kseksi"));
									$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) AS total_realisasi FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND b.kseksi2='$kseksi' AND a.tgl_transfer != '0000-00-00' AND a.validasi = '1' AND MONTH(a.tgl_transfer) <= '$bulan' GROUP BY b.kseksi2"));
		
									// $qu = mysql_fetch_assoc(mysql_query("SELECT SUM(pagu_anggaran) AS pagu, SUM(total_realisasi) AS total_realisasi FROM spj WHERE kseksi='$kseksi' GROUP BY kseksi"));
		
									$realisasi = $qa['total_realisasi']+$r['total_realisasi'];
									$pagu = $r['pagu'];
		
									$persen_real = round(($realisasi/$pagu)*100, 2);
		
									//target
									// $bulan = date("n");
		
									// if($bulan == '1'){
									// 	$bln = $bulan;
									// }elseif ($bulan == '12') {
									// 	$bln = $bulan+1;
									// } else {
									// 	$bln = $bulan;
									// }
									$qe = mysql_query("SELECT * FROM target WHERE kseksi='$kseksi'");
									$target = 0;
									while ($data = mysql_fetch_assoc($qe)) {
										for($i = 1; $i <= $bulan; $i++){
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
		
									
									$ke = mysql_fetch_assoc(mysql_query("SELECT AVG(real_kegiatan) as rata FROM spj WHERE kseksi='$kseksi' AND pagu_anggaran != '0'"));
		
									mysql_query("UPDATE grafik_real SET realisasi='$persen_real', target='$persen_target', selisih='$persen_lb', kegiatan='$ke[rata]' WHERE kseksi='$kseksi'");
									
								
									// $grafik1[] = array($nm_seksi, doubleval($persen_real));
									// $grafik3[] = array($nm_seksi, doubleval($persen_target));
									// $grafik4[] = array($nm_seksi, doubleval($persen_lb));
									// $grafik2[] = array($nm_seksi);
								}
							}
							
						}

						$q = mysql_query("SELECT * FROM grafik_real a, user b WHERE a.kseksi=b.kseksi ORDER BY a.selisih ASC");

						while ($qa = mysql_fetch_assoc($q)) {
							$nm_seksi = $qa['nm_seksi'];
							$persen1 = $qa['realisasi'];
							$persen2 = $qa['target'];
							$persen3 = $qa['selisih'];
							$persen4 = $qa['kegiatan'];

							$grafik1[] = array($nm_seksi, doubleval($persen1));
							$grafik3[] = array($nm_seksi, doubleval($persen2));
							$grafik4[] = array($nm_seksi, doubleval($persen3));
							$grafik5[] = array($nm_seksi, doubleval($persen4));
							$grafik2[] = array($nm_seksi);
						}
					
						$data_grafik1 = json_encode($grafik1);
						$data_grafik2 = json_encode($grafik3);
						$data_grafik3 = json_encode($grafik4);
						$data_grafik4 = json_encode($grafik5);
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
						colors: ['#000099', '#006600', '#d24087', '#4ec4ce'],
						series: [
							{
								data: <?=$data_grafik1?>,
								name: "Realisasi"
								
							},
							{
								data: <?=$data_grafik2?>,
								name: "ROK"
							},
							{
								data: <?=$data_grafik3?>,
								name: "Lebih/(Kurang)"
							},
							{
								data: <?=$data_grafik4?>,
								name: "Kegiatan"
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
			
		</div>
	</div>
</div>
</body>
</html>
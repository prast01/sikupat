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

<?php
// mysql_connect("localhost","root","");
// mysql_select_db("realisasi_kegiatan");
?>
<div class="tab-content">			
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-body">
				<form action="" method="post">
					<div class="form-group">
						<div class="col-sm-1">
							<label for="">Subag/Seksi/UPT : </label>
						</div>
						<div class="col-sm-3">
							<select name="seksi" class="form-control" style="width:400px">
								<option value="">Dinas Kesehatan Kabupaten Jepara</option>
								<?php
									$seksi = (isset($_POST['seksi'])) ? $_POST['seksi'] : '' ;

									$query = mysql_query("select * from user where level_user='2'");
									while ($hasil = mysql_fetch_array($query)){
										echo "<option ";
										if ($hasil['kseksi'] == $seksi) {
											echo "selected";
										}
										echo " value='$hasil[kseksi]'>$hasil[nm_seksi]</option>";
									}
								?>
							</select>
						</div>
						<div class="col-sm-2">
							<button type="submit" name="lihat" class="btn btn-primary btn-sm">Lihat</button>
						</div>
					</div>
				</form>
			</div>
			<div class="widget-content nopadding">
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
						$bulan = array(
							'1' => 'Januari',
							'2' => 'Februari',
							'3' => 'Maret',
							'4' => 'April',
							'5' => 'Mei',
							'6' => 'Juni',
							'7' => 'Juli',
							'8' => 'Agustus',
							'9' => 'September',
							'10' => 'Oktober',
							'11' => 'November',
							'12' => 'Desember'
						);

						if ($seksi != '') {
							$query = mysql_fetch_assoc(mysql_query("select * from user where kseksi='$seksi'"));
							$nama = $query['nm_seksi'];
							$q = mysql_fetch_assoc(mysql_query("SELECT SUM(pagu_anggaran) as pagu, SUM(total_realisasi) as total_realisasi FROM spj WHERE kseksi='$seksi'"));
						} else {
							$nama = 'Dinas Kesehatan Kabupaten Jepara';
							$q = mysql_fetch_assoc(mysql_query("SELECT SUM(pagu_anggaran) as pagu, SUM(total_realisasi) as total_realisasi FROM spj"));
						}
						$pagu = $q['pagu'];
						$real = $q['total_realisasi'];

						$total_p = 0;
						$total_r = 0;
						for ($i=1; $i <=12 ; $i++) {
							$field = 'b'.$i;
							if ($seksi != '') {
								// target
								$q2 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as total FROM target WHERE kseksi='$seksi'"));
								// realisasi
								$q3 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND bibs.tgl_transfer > '2019-03-31' AND MONTH(bibs.tgl_transfer) = '$i' AND bibs.kseksi='$seksi' GROUP BY bibs.kseksi"));
							} else {
								// target
								$q2 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as total FROM target"));
								// realisasi
								$q3 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND bibs.tgl_transfer > '2019-03-31' AND MONTH(bibs.tgl_transfer) = '$i'"));
							}
							// target
							$total_p = $total_p+$q2['total'];
							$ps = ($total_p/$pagu)*100;
							$persen_t = number_format($ps, 2, '.', ',');

							// realisasi
							if ($i == 3) {
								$total_r = $total_r+$q3['nominal']+$real;
							} elseif ($i > date('m')) {
								$total_r = 0;
							} else {
								$total_r = $total_r+$q3['nominal'];
							}
							$pr = ($total_r/$pagu)*100;
							$persen_r = number_format($pr, 2, '.', ',');
							
							$x[] = array($bulan[$i]);
							$grafik1[] = array($bulan[$i], doubleval($persen_t));
							$grafik2[] = array($bulan[$i], doubleval($persen_r));
						}
						
						$data_grafik1 = json_encode($grafik1);
						$data_grafik2 = json_encode($grafik2);
						$kategori		= json_encode($x);
						$nm = json_encode($nama);

						// echo $data_grafik1;
				?>

				<div id="container" style="height:400px"></div>
				<script>
				$(document).ready(function(){
				$(function () {
					var nm = 'Realisasi dan Target Kegiatan<br>'+<?=$nm?>;
					var chart = Highcharts.chart('container', {
						chart: {
							type: 'column'
						},
						xAxis: {
							categories: <?=$kategori?>
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
							text: nm,
							align: 'center',
							x: 0
						},
						colors: ['#000099', '#006600', '#d24087', '#4ec4ce'],
						series: [
						{
							data: <?=$data_grafik2?>,
							name: "Realisasi"
						},
						{
							data: <?=$data_grafik1?>,
							name: "Target"
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
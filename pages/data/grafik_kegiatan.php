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
$ckseksi	= isset($_POST['ckseksi']) ? $_POST['ckseksi'] : '';
$ckkegiatan	= isset($_POST['ckkegiatan']) ? $_POST['ckkegiatan'] : '';

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
?>
<div class="tab-content">			
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="row" style="padding: 10px 10px 10px 10px">
				<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					<div class="form-group">
						<label class="col-sm-2 control-label">Seksi/Subag</label>
						<div class="col-sm-4">
							<select class="form-control" style="width:500px" name="ckseksi" id="kd_seksi" onchange="getKegiatan(this)">
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from user where level_user='2' AND kbidang!='DK007'");
								while ($hasil = mysql_fetch_array($query)){
								$kseksi	= $hasil['kseksi'];
								$nm_seksi	= $hasil['nm_seksi'];
								?>
								<option <?php if($ckseksi == $kseksi){ echo "selected"; } ?> value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama Kegiatan</label>
						<div class="col-sm-4">
							<select class="form-control" style="width:500px" name="ckkegiatan" id="keg">
								<option disabled selected value="">-Pilih-</option>
								<?php
									if(isset($_POST['lihat'])){
										$query = mysql_query("SELECT * FROM kegiatan WHERE kseksi='$_POST[ckseksi]'");
										while($hasil = mysql_fetch_array($query)){
											$id_kegiatan	= $hasil['id_kegiatan'];
											$nm_kegiatan	= $hasil['nm_kegiatan'];
								?>
								<option <?php if($_POST['ckkegiatan'] == $id_kegiatan){ echo "selected"; } ?> value="<?php echo $id_kegiatan ?>"><?php echo $nm_kegiatan ?></option>
								<?php
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-4">
							<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
						</div>
					</div>
				</form>
			</div>
			
			<div class="widget-content nopadding">
				<script src="pages/data/grafik/jquery.min.js"></script>
				<script src="pages/data/grafik/highcharts.js"></script>
				<script src="pages/data/grafik/exporting.js"></script>
				<?php
					if(isset($_POST['lihat'])){
						$rw = mysql_query("SELECT * FROM kegiatan WHERE id_kegiatan ='$_POST[ckkegiatan]'")or die(mysql_error());

						while ($qa = mysql_fetch_assoc($rw)) {
							$id_keg = $qa['id_kegiatan'];

							// target
							$a = mysql_fetch_assoc(mysql_query("SELECT * FROM persen_target WHERE id_kegiatan='$id_keg'"));
							// realisasi
							$b = mysql_fetch_assoc(mysql_query("SELECT * FROM persen_realisasi WHERE id_kegiatan='$id_keg'"));
							for ($i=1; $i <= 12 ; $i++) { 
								$tabel = 'b'.$i;
								// $bulan = 'Bulan ke - '.$i;
								$x[] = array($bulan[$i]);
								$yTarget[] = array($bulan[$i], doubleval($a[$tabel]));
								$yReal[] = array($bulan[$i], doubleval($b[$tabel]));
							}
						}
					
						$data_grafik1 = json_encode($yTarget);
						$data_grafik2 = json_encode($yReal);
						$grafik2		= json_encode($x);
				?>

				<div id="container" style="height:400px"></div>
				<script src="assets/lib/jquery/jquery.js"></script>
				<script type="text/javascript" src="jquery-1.8.0.min.js"></script>
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
								name: "Target"
								
							},
							{
								data: <?=$data_grafik2?>,
								name: "Realisasi"
							}
							]
						});
					});
				});
				</script>
				
				<?php
					}
				?>
			</div>
			
		</div>
	</div>
</div>
<script>
	
	function getKegiatan(item) {
		var value = item.value;
		// console.log(value);
		var drop = $('#keg');
		drop.empty();

		$.ajax({
			url: "getSpj3.php",
			data: "id="+value,
			cache: false,
			success: function(msg){
				drop.html(msg);
			}
		});
	}
</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">

<style> 
	body{
		background:#FFFFFF;
	}
    th{
        color: black;
        font: Times New Roman;
		font-size: 11px;
    }
    td{
        font-size: 11px;
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
$tempdir = "temp/"; 
if (file_exists($tempdir)){
	array_map('unlink', glob("$tempdir/*.*"));
	rmdir($tempdir);
}
$act	= isset($_GET['act']) ? $_GET['act'] : '';
$ckseksi	= isset($_POST['ckseksi']) ? $_POST['ckseksi'] : '';
$ckkegiatan	= isset($_POST['ckkegiatan']) ? $_POST['ckkegiatan'] : '';
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi2 	= $r['kseksi'];
$level_user = $r['level_user'];
$lock = 0;

// if (isset($_POST['kunci'])) {
// 	$ckseksi	= isset($_POST['ckseksi']) ? $_POST['ckseksi'] : '';
// 	$ckkegiatan	= isset($_POST['ckkegiatan']) ? $_POST['ckkegiatan'] : '';

// 	$q = mysql_query("UPDATE target_spj SET kunci = '1' WHERE kseksi='$ckseksi' AND id_kegiatan='$ckkegiatan'");

// 	if ($q) {
// 		echo "<script>alert('Data Target Kegiatan Ini Sudah Terkunci.')</script>";
// 	} else {
// 		# code...
// 	}
	
// }
?>
<div class="tab-content">			
	<div class="panel-body" >
		<div class="panel panel-default" style="">
			<br>
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
				<div class="form-group">
					<label class="col-sm-2 control-label">Seksi/Subag</label>
					<div class="col-sm-4">
						<select class="form-control" name="ckseksi" id="kd_seksi" onchange="getKegiatan(this)">
							<option value="">-Pilih-</option>
							<?php
							$query = mysql_query("select * from user where level_user='2'");
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
						<select class="form-control" name="ckkegiatan" id="keg">
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
					<div class="col-sm-2">
						<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
					</div>
					<?php
						if(isset($_POST['lihat']) && $kseksi2 == 'DJ001'){
					?>
					<!-- <div class="col-sm-2">
						<button type="submit" class="btn btn-info pull-left" name="kunci">Kunci</button>
					</div> -->
					<?php
						}
					?>
				</div>
			</form>

			<div class="panel-body">
				<div class="panel-heading">
					<h3 class="panel-title">Daftar Data Rekening</h3>
				</div>
				<div class="table-responsive" style="font-size:10px">
					<!-- <table id="table1" class="table table-bordered table-hover" width="100%"> -->
					<form method="POST">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered table-hover" width="100%">
									<thead>
										<tr>		
											<th width="2%" style="text-align:center">NO</th>
											<th width="10%" style="text-align:center">KODE REKENING</th>
											<th width="17%" style="text-align:center">URAIAN KEGIATAN</th>
											<th></th>
											<?php
												for ($i=1; $i <= 12 ; $i++) { 
											?>
											<th style="text-align:center">BULAN KE-<?php echo $i; ?></th>
											<?php
												}
											?>
										</tr>
									</thead>
										<?php
											if(isset($_POST['lihat'])){
										?>
									<tbody>
										<?php
												$a = array();
												$b = array();
												for ($i=1; $i <= 12 ; $i++) {
													$a[$i] = 0;
													$b[$i] = 0;
													$target[$i] = 0;
													$realisasi[$i] = 0;
												}
												if($_POST['ckseksi'] != '' && $_POST['ckkegiatan'] != ''){
													$id_seksi = $_POST['ckseksi'];
													$id_keg = $_POST['ckkegiatan'];
													$no = 1;
													$q = mysql_query("SELECT * FROM spj WHERE id_kegiatan='$id_keg' AND kseksi='$id_seksi'");
													$pagu = 0;
													while ($h = mysql_fetch_assoc($q)) {
														$pagu = $pagu + $h['pagu_anggaran'];
										?>
										<tr>		
											<td style="text-align:center"><?php echo $no; ?></td>
											<td style="text-align:center"><?php echo $h['kode_rekening']; ?></td>
											<td><?php echo $h['uraian_kegiatan']; ?></td>
											<td>
												Target <br><br> Realisasi
											</td>
											<?php
													$nomi = 0;
														for ($i=1; $i <= 12 ; $i++) {
															$bln = sprintf("%02s",$i);
															// target
															$tabel = "b".$i;
															$h1 = mysql_fetch_assoc(mysql_query("SELECT * FROM target_spj WHERE id_spj='$h[id_spj]'"));
															if ($h1[$tabel] != '') {
																$nominal = $h1[$tabel];
																$a[$i] = $a[$i]+$h1[$tabel];
															} else {
																$nominal = 0;
																$a[$i] = $a[$i]+0;
															}

															// if($h1['kunci'] == '1'){
															// 	$lock = 1;
															// } else {
															// 	$lock = 0;
															// }
															

															// realisasi
															$h2 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.id_spj = '$h[id_spj]' AND bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND bibs.tgl_transfer > '2019-03-31' AND MONTH(bibs.tgl_transfer) = '$bln'"));
															if ($h2['nominal'] != '') {
																if ($i == 3) {
																	$nominal2 = $h2['nominal'];
																	$b[$i] = $b[$i]+$h2['nominal']+$h['total_realisasi'];
																} else {
																	$nominal2 = $h2['nominal'];
																	$b[$i] = $b[$i]+$h2['nominal'];
																}

																if ($i <= 8) {
																	if ($i == 3) {
																		$nomi = $nomi+$h2['nominal']+$h['total_realisasi'];
																	} else {
																		$nomi = $nomi+$h2['nominal'];
																	}
																}
															} else {
																if ($i == 3) {
																	$nominal2 = 0;
																	$b[$i] = $b[$i]+0+$h['total_realisasi'];
																} else {
																	$nominal2 = 0;
																	$b[$i] = $b[$i]+0;
																}

																if ($i <= 8) {
																	if ($i == 3) {
																		$nomi = $nomi+0+$h['total_realisasi'];
																	} else {
																		$nomi = $nomi+0;
																	}
																}
															}

											?>
											<td style="text-align:center">
												<?php
													if ($lock) {
														$rd = "readonly";
													} else {
														if($kseksi2 != $id_seksi && $kseksi2 != 'DJ001'){
															$rd = "readonly";
														} else {
															$rd = "";
														}
													}
													
												?>
												<input type="number" onclick="editTarget('target','<?php echo $h['id_spj']; ?>', '<?php echo $i; ?>', '<?php echo $h['uraian_kegiatan']; ?>', 'target', '<?php echo $kseksi2; ?>', '<?php echo $id_seksi; ?>', '<?php echo $id_keg; ?>')" readonly value="<?php echo $nominal; ?>" name="" id="<?php echo $h['id_spj'].$i; ?>" onchange="update_target(this, '<?php echo $h['id_spj']; ?>', '<?php echo $i; ?>', '<?php echo $id_keg; ?>')" style="width:110px; <?php if($i == date('m')){ echo "border:1px solid red"; } ?>">

												<input type="number" onclick="editTarget('realisasi','<?php echo $h['id_spj']; ?>', '<?php echo $i; ?>', '<?php echo $h['uraian_kegiatan']; ?>', 'realisasi', '<?php echo $kseksi2; ?>', '<?php echo $id_seksi; ?>', '<?php echo $id_keg; ?>')" readonly value="<?php echo $nominal2; ?>" name="" id="" style="width:110px; margin-top:5px; border:1px solid red">
											</td>
											<?php
														}
														// $h3 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.id_spj = '$h[id_spj]' AND bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND bibs.tgl_transfer > '2019-03-31' AND MONTH(bibs.tgl_transfer) <= '8'"));
														// $h3 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.id_spj = '$h[id_spj]' AND bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND MONTH(bibs.tgl_transfer) = '9'"));
														// $n = $h3['nominal'];

														// $uraian = "Bayar ".$h['uraian_kegiatan']." bulan September";

														// if ($n > 0) {
														// 	$qw = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg) VALUES('$h[id_spj]', '9', '$uraian', '$n', '4', '$id_seksi', '$id_keg')");
														// 	if ($qw) {
														// 		$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$h[id_spj]' AND bulan='9'"));
														// 		$nom = $d['nominal'];
														// 		$field = 'b9';
														// 		mysql_query("UPDATE target_spj SET $field='$nom' WHERE id_spj='$h[id_spj]'");
																
														// 		$q3 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as jumlah FROM target_spj WHERE id_kegiatan='$id_keg'"));

														// 		mysql_query("UPDATE `target` SET `$field`='$q3[jumlah]' WHERE id_kegiatan='$id_keg'");
														// 	}
														// }
											?>
										</tr>
										<?php
														$no++;
													}
												} else {
													echo "<script>alert('Subag/Seksi/UPT dan Kegiatan Harus Diisi.');</script>";
												}
										?>
										<tr>
											<td colspan="4" style="text-align:right">Target s/d Bln ini (Rp)</td>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
													if ($i == 1) {
														$target[$i] = $a[$i];
													} elseif($i > 1 && $i <= 12) {
														$target[$i] = $target[$i-1] + $a[$i];
													} else {
														$target[$i] = $a[$i];
													}
											?>
											<td style="text-align:center">
												<?php echo number_format($target[$i], 0, ',', '.'); ?>
											</td>
											<?php
												}
											?>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right">Target s/d Bln ini (%)</td>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
													// $target[$i] = $target[$i] + $a[$i];
													$p = ($target[$i]/$pagu)*100;
													$tab = 'b'.$i;
													mysql_query("UPDATE persen_target SET `$tab`='$p' WHERE id_kegiatan='$id_keg'");
											?>
											<td style="text-align:center">
												<?php echo number_format($p, 2, ',', '.'); ?>%
											</td>
											<?php
												}
											?>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right">Realisasi Bulan ini (Rp)</td>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
													// if ($i == 1) {
													// 	$realisasi[$i] = $b[$i];
													// } elseif($i > 1 && $i < date('m')) {
													// 	$realisasi[$i] = $realisasi[$i-1] + $b[$i];
													// } elseif ($i == 12) {
													// 	$realisasi[$i] = $realisasi[$i-1] + $b[$i];
													// } else {
													// 	$realisasi[$i] = $b[$i];
													// }
													// if ($i == date('m')) {
											?>
											<td style="text-align:center">
												<?php echo number_format($b[$i], 0, ',', '.'); ?>
											</td>
											<?php
												}
											?>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right">Realisasi s/d Bln ini (Rp)</td>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
													if ($i == 1) {
														$realisasi[$i] = $b[$i];
													} elseif($i > 1 && $i <= 12) {
														$realisasi[$i] = $realisasi[$i-1] + $b[$i];
													} else {
														$realisasi[$i] = $b[$i];
													}

													if($i <= date('m')){
											?>
											<td style="text-align:center">
												<?php echo number_format($realisasi[$i], 0, ',', '.'); ?>
											</td>
											<?php
													} else {
											?>
											<td style="text-align:center">
												<?php echo number_format(0, 0, ',', '.'); ?>
											</td>
											<?php
													}
												}
											?>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right">Realisasi s/d Bln ini (%)</td>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
													// $realisasi[$i] = $realisasi[$i] + $b[$i];
													// if ($i == date('m')) {
													// 	$p2 = 0;
													// } else {
													// 	$p2 = ($realisasi[$i]/$pagu)*100;
													// }
													$p2 = ($realisasi[$i]/$pagu)*100;
													$tab = 'b'.$i;
													mysql_query("UPDATE persen_realisasi SET `$tab`='$p2' WHERE id_kegiatan='$id_keg'");
													if($i <= date('m')){
											?>
											<td style="text-align:center">
												<?php echo number_format($p2, 2, ',', '.'); ?>%
											</td>
											<?php
													} else {
											?>
											<td style="text-align:center">
												<?php echo number_format(0, 0, ',', '.'); ?>
											</td>
											<?php
													}
												}
											?>
										</tr>
									</tbody>
										<?php
											}
										?>
									<tfoot>
										<?php
											if(isset($_POST['lihat'])){
										?>
										<tr>
											<th colspan="4" class="text-right">CETAK TARGET</th>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
											?>
											<th style="text-align:center">
												<span style="cursor:pointer"><i class="fa fa-print text-success" style="font-size:20px" title="Cetak Target" onclick="cetak('1','<?php echo $i; ?>','<?php echo $_POST['ckkegiatan']; ?>')"></i></span>
											</th>
											<?php
												}
											?>
										</tr>
										<?php
											}
										?>
										<tr>
											<th colspan="4" class="text-right">CETAK TARGET SEMUA SUBAG/SEKSI/UPT</th>
											<?php
												for ($i=1; $i <= 12 ; $i++) {
											?>
											<th style="text-align:center">
												<span style="cursor:pointer"><i class="fa fa-print text-danger" style="font-size:20px" title="Cetak Semua Target" onclick="cetak('2','<?php echo $i; ?>')"></i></span>
											</th>
											<?php
												}
											?>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalTarget" tabindex="-1" role="dialog" aria-labelledby="modalIks" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<?php
// echo "<pre>";
// print_r($b);
// echo "</pre>";
?>
<script src="assets/lib/jquery/jquery.js"></script>
<script src="assets/jquery.min.js"></script>
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

<!-- <script type="text/javascript" src="jquery-1.8.0.min.js"></script> -->

<script>

function editTarget(page, id_spj, bln, title, jenis, s1, s2, keg) {
	$("#modalTarget").modal();
	$('.modal-content').empty();
	var data = {'page':page,'id_spj': id_spj, 'bln': bln, 'title': title, 'jenis':jenis, 's1':s1, 's2':s2, 'keg':keg};
	$.ajax({
		type:'POST',
		url:'getTarget.php',
		data:data,
		success:function(data){
			$('.modal-content').prepend(data);
		}
	});
}

var i = 1;
function simpanTarget(id_spj, bln) {
	var uraian = $('#uraian').val();
	var nominal = $('#nominal').val();
	var page = $('#page').val();
	var minggu = $('#minggu').val();
	var keg = $('#keg').val();
	var seksi = $('#seksi').val();

	var data = {'page':page,'id_spj': id_spj, 'bln': bln, 'uraian':uraian, 'nominal':nominal, 'minggu': minggu, 'seksi': seksi, 'keg': keg};
	$.ajax({
		type:'POST',
		url:'getTarget.php',
		data:data,
		success:function(data){
			var json=JSON.parse(data);
			if (json.res == 1) {
				var html = '<tr id="add-'+i+'">';
					html += '<td>'+uraian+'</td>';
					html += '<td>Minggu ke - '+minggu+'</td>';
					html += '<td align="right">'+json.nomi2+'</td>';
					html += '<td align="center"><span style="cursor:pointer" title="Hapus" onclick="del(\'add-'+i+'\', \''+id_spj+'\', \''+bln+'\', \''+uraian+'\', \''+nominal+'\')">X</span></td>';
					html += '</tr>';
				$('#tbody').append(html);
				$('#uraian').val("");
				$('#nominal').val("");
				$("#"+id_spj+bln).val(json.nom);
				$('#nomi span').text(json.nomi);
				i++;
			} else {
				console.log('Gagal');
			}
		}
	});
}
function del(id, id_spj, bln, uraian, nominal, seksi, keg) {
	var data = {'page':'del', 'id_spj': id_spj, 'bln': bln, 'uraian':uraian, 'nominal':nominal, 'seksi':seksi, 'keg':keg};
	$.ajax({
		type:'POST',
		url:'getTarget.php',
		data:data,
		success:function(data){
			var json=JSON.parse(data);
			if (json.res == 1) {
				$("#"+id).remove();
				$("#"+id_spj+bln).val(json.nom);
				$('#nomi span').html(json.nomi);
			} else {
				console.log('Gagal');
			}
		}
	});
}

function selesai() {
	location.reload();
}

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

var x = 0;
function update_target(jml,id_spj, bln, id_keg){
	var jumlah = jml.value;
	var data = {'jml': jumlah, 'id_spj': id_spj, 'bulan': bln, 'id_keg': id_keg}
		$.ajax({
			url: "pages/data/update_target.php",
			type: "POST",
			data: data,
			success: function(data){
				var json = JSON.parse(data);
				if (json.res == 1) {
					// x = intval(x) + 1;

					location.reload();
				}
				// console.log(data);
			}
		});
}

function cetak(jenis, bln, id_keg) {
	if (jenis === '1') {
		window.open("cetak.php?jenis="+jenis+"&bln="+bln+"&id_keg="+id_keg, "_blank");
	} else {
		window.open("cetak_all.php?bln="+bln, "_blank");
	}
}
</script>
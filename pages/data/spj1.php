<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">

<style>
	body {
		background: #FFFFFF;
	}

	th {
		color: black;
		font: Times New Roman;
		text-align: right;
		font-size: 11px;
	}

	td {
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
$ckseksi	= isset($_POST['ckseksi']) ? $_POST['ckseksi'] : '';
$ckkegiatan	= isset($_POST['ckkegiatan']) ? $_POST['ckkegiatan'] : '';
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'") or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$kseksi2 	= $r['kseksi'];
$level_user = $r['level_user'];
?>
<div class="tab-content">
	<div class="panel-body">
		<div class="panel panel-default" style="">
			<?php if ($act == "edit") { ?>
				<br />
				<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
					<div class="form-group">
						<label class="col-sm-2 control-label">Seksi/Subag</label>
						<div class="col-sm-4">
							<select class="form-control" name="ckseksi" id="kd_seksi" onchange="getKegiatan(this)">
								<option value="">-Pilih-</option>
								<?php
								$query = mysql_query("select * from user where level_user='2' AND kbidang!='DK007'");
								while ($hasil = mysql_fetch_array($query)) {
									$kseksi	= $hasil['kseksi'];
									$nm_seksi	= $hasil['nm_seksi'];
								?>
									<option <?php if ($ckseksi == $kseksi) {
												echo "selected";
											} ?> value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
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
								if (isset($_POST['lihat'])) {
									$query = mysql_query("SELECT * FROM kegiatan WHERE kseksi='$_POST[ckseksi]' OR id_kegiatan IN (SELECT id_kegiatan FROM spj WHERE kbidang2='DK007' AND kseksi='$_POST[ckseksi]' GROUP BY id_kegiatan)");
									while ($hasil = mysql_fetch_array($query)) {
										$id_kegiatan	= $hasil['id_kegiatan'];
										$nm_kegiatan	= $hasil['nm_kegiatan'];
								?>
										<option <?php if ($_POST['ckkegiatan'] == $id_kegiatan) {
													echo "selected";
												} ?> value="<?php echo $id_kegiatan ?>"><?php echo $nm_kegiatan ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-sm-3">
							<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
							<label class="col-sm-1 control-label"></label>
							<a href="pages/data/cetak_excel_spj.php?ckkegiatan=<?php echo $ckkegiatan ?>&ckseksi=<?php echo $ckseksi ?>" target="_blank">
								Export Excel
							</a>
						</div>
				</form>
				<div class="panel-heading">
					<h3 class="panel-title">&nbsp;</h3>
				</div>

			<?php } ?>
			<div class="panel-body">
				<div class="table-responsive">

					<div class="widget-header">
						<h5 class="widget-title">SPJ</h5>
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
					<?php
					if ($act == 'kegiatan') {
					?>
						<div class="widget-body">
							<form action="" method="post">
								<div class="form-group">
									<div class="col-sm-1">
										<!-- <select name="tahun" class="form-control">
									<?php
									for ($i = date("Y"); $i > (date("Y") - 5); $i--) {
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
											$bulan = (isset($_POST['bulan'])) ? $_POST['bulan'] : date("m");;
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
											for ($i = 1; $i <= 12; $i++) {
												if ($i < 10) {
													$i = '0' . $i;
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
												if ($kseksi == 'DJ001') {
												?>
								<a href="print-regis-spj.php?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>" target="_blank"><img src='img/print.png' title="Cetak" /></a>
								<?php
												}
								?> -->
									</div>
								</div>
							</form>
						</div>

					<?php
					}

					if ($act == "edit") { ?>
						<?php
						$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
						?>

						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<td style="text-align:center">
										<font color=""><b>NO</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>KODE REKENING</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>URAIAN KEGIATAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>NAMA KEGIATAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>SEKSI</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>PAGU ANGGARAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>TOTAL REALISASI</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>REALISASI(%)</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>SISA PAGU ANGGARAN</b></font>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
								$lihat	= isset($_POST['lihat']) ? $_POST['lihat'] : '';
								if ($lihat) {
									if ($ckkegiatan != "") {
										$query = mysql_query("SELECT * from spj where id_kegiatan='$ckkegiatan' AND kseksi='$ckseksi'");
									} else {
										$query = mysql_query("SELECT * from spj");
									}
								} else {
									$query = mysql_query("select * from spj WHERE id_kegiatan != '0'");
								}
								while ($hasil = mysql_fetch_array($query)) {
									$id_spj	= $hasil['id_spj'];
									$id_kegiatan	= $hasil['id_kegiatan'];
									$query3 = mysql_query("SELECT * from kegiatan where id_kegiatan='$id_kegiatan' order by nm_kegiatan");
									$hasil3 = mysql_fetch_array($query3);
									$nm_kegiatan	= $hasil3['nm_kegiatan'];
									$kseksi3	= $hasil['kseksi'];
									$query2 = mysql_query("select * from user where kseksi='$kseksi3'");
									$hasil2 = mysql_fetch_array($query2);
									$nm_seksi	= $hasil2['nm_seksi'];
									$kode_rekening	= $hasil['kode_rekening'];
									$pagu_anggaran	= $hasil['pagu_anggaran'];

									$qa = mysql_fetch_assoc(mysql_query("SELECT total_realisasi FROM total_realisasi WHERE id_spj='$id_spj'"));

									// $total_realisasi	= $hasil['total_realisasi'];
									// if ($kseksi2 == 'DJ001') {
									// 	$total_realisasi	= $hasil['total_realisasi'];
									// } else {
									$total_realisasi	= $hasil['total_realisasi'] + $qa['total_realisasi'];
									// }

									$uraian_kegiatan	= $hasil['uraian_kegiatan'];
									$persen_realisasi	= ($total_realisasi / $pagu_anggaran) * 100;
									$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
								?>

									<tr>
										<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
										<td valign="top" align="left" bgcolor=""><a href="home.php?cat=data&page=spj_detail&spj=<?php echo $id_spj; ?>" target="_blank"><?php echo $kode_rekening; ?></a></td>
										<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
										<td valign="top" align="right" bgcolor="">
											<?php if ($kseksi2 == 'DJ001') { ?>
												<input name="pagu_anggaran<?php echo $id_spj; ?>" type="text" id="pagu_anggaran<?php echo $id_spj; ?>" value="<?php echo $pagu_anggaran; ?>" onChange="update_pagu_anggaran(this,<?php echo $id_spj; ?>)" />
											<?php } else { ?>
												<?php echo number_format($pagu_anggaran, 0, ',', '.'); ?>
											<?php } ?>
										</td>
										<td valign="top" align="right" bgcolor="">
											<?php //if($kseksi2=='DJ001'){ 
											?>
											<!-- <input name="pagu_anggaran<?php echo $id_spj; ?>" type="text" id="total_realisasi<?php echo $id_spj; ?>" value="<?php echo $total_realisasi; ?>" onChange="update_total_realisasi(this,<?php echo $id_spj; ?>)" /> -->
											<?php //}else{ 
											?>
											<?php echo number_format($total_realisasi, 0, ',', '.'); ?>
											<?php //} 
											?>
										</td>
										<td valign="top" align="right" bgcolor=""><?php echo round($persen_realisasi, 2); ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo number_format($sisa_pagu_anggaran, 0, ".", "."); ?></td>
									</tr>
								<?php
									error_reporting(0);
									$sum_pagu_anggaran += $pagu_anggaran;
									$total_pagu_anggaran = $sum_pagu_anggaran;

									$sum_total_realisasi += $total_realisasi;
									$total_total_realisasi = $sum_total_realisasi;

									$sum_sisa_pagu_anggaran += $sisa_pagu_anggaran;
									$total_sisa_pagu_anggaran = $sum_sisa_pagu_anggaran;

									$total_realisasi_persen	= (($total_total_realisasi / $total_pagu_anggaran) * 100);
								}

								?>
							</tbody>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>Total</th>
								<th><?php echo number_format($total_pagu_anggaran, 0, ".", "."); ?></th>
								<th><?php echo number_format($total_total_realisasi, 0, ".", "."); ?></th>
								<th><?php echo round($total_realisasi_persen, 2); ?></th>
								<th><?php echo number_format($total_sisa_pagu_anggaran, 0, ".", "."); ?></th>
							</tr>
						</table>
						</form>
						<?php
						$nm_kegiatan 	= isset($_POST['nm_kegiatan']) ? $_POST['nm_kegiatan'] : '';
						$simpan 		= isset($_POST['simpan']) ? $_POST['simpan'] : '';

						if ($simpan) {
							$insert = mysql_query("update kegiatan set nm_kegiatan='$nm_kegiatan' where id_spj='$id_spj'") or die(mysql_error());
							if ($insert) {
								echo "<script>alert('Data berhasil diupdate');window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";
							}
						}
						?>

					<?php } ?>
				</div>
			</div>


			<div class="panel-body">
				<div class="table-responsive">

					<?php if ($act == "kegiatan") { ?>
						<?php
						$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
						?>

						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<td style="text-align:center">
										<font color=""><b>NO</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>NAMA KEGIATAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>SEKSI</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>PAGU ANGGARAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>TOTAL REALISASI</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>PENYERAPAN (%)</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>SISA PAGU ANGGARAN</b></font>
									</td>
									<td style="text-align:center">
										<font color=""><b>REALISASI KEGIATAN (%)</b></font>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
								$query = mysql_query("select * from kegiatan order by id_kegiatan ASC");
								while ($hasil = mysql_fetch_array($query)) {
									$id_kegiatan	= $hasil['id_kegiatan'];
									$nm_kegiatan	= $hasil['nm_kegiatan'];
									$query3 = mysql_query("SELECT *, sum(pagu_anggaran) as pagu_anggaran,sum(total_realisasi) as total_realisasi from spj where id_kegiatan='$id_kegiatan' group by id_kegiatan");
									$hasil3 = mysql_fetch_array($query3);
									$kseksi	= $hasil['kseksi'];
									$query2 = mysql_query("select * from user where kseksi='$kseksi'");
									$hasil2 = mysql_fetch_array($query2);
									$nm_seksi	= $hasil2['nm_seksi'];
									$pagu_anggaran	= $hasil3['pagu_anggaran'];

									// $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi2 WHERE id_kegiatan='$id_kegiatan' group by id_kegiatan"));
									// $qa = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) AS total_realisasi FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND b.id_kegiatan='$id_kegiatan' AND a.tgl_transfer != '0000-00-00' AND a.validasi = '1' AND MONTH(a.tgl_transfer) = '$bulan' GROUP BY b.id_kegiatan"));
									$qa = mysql_fetch_assoc(mysql_query("SELECT SUM(a.nominal) as total_realisasi FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND MONTH(a.tgl_transfer) <= '$bulan' AND a.tgl_transfer != '0000-00-00' AND a.kseksi != '' AND b.id_kegiatan='$id_kegiatan' group by b.id_kegiatan"));


									// $total_realisasi	= $hasil3['total_realisasi'];
									$total_realisasi	= $qa['total_realisasi'] + $hasil3['total_realisasi'];
									$persen_realisasi	= ($total_realisasi / $pagu_anggaran) * 100;
									$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;

									$ke = mysql_fetch_assoc(mysql_query("SELECT AVG(real_kegiatan) as rata FROM spj WHERE id_kegiatan='$id_kegiatan' AND pagu_anggaran != '0'"));
								?>

									<tr>
										<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo number_format($pagu_anggaran, 0, ".", "."); ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo number_format($total_realisasi, 0, ".", "."); ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo round($persen_realisasi, 2); ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo number_format($sisa_pagu_anggaran, 0, ".", "."); ?></td>
										<td valign="top" align="right" bgcolor=""><?php echo round($ke['rata'], 2); ?></td>
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
								<th><?php echo number_format($total_pagu_anggaran, 0, ".", "."); ?></th>
								<th><?php echo number_format($total_total_realisasi, 0, ".", "."); ?></th>
								<th></th>
								<th><?php echo number_format($total_sisa_pagu_anggaran, 0, ".", "."); ?></th>
								<th></th>
							</tr>
						</table>
						</form>
						<?php
						$nm_kegiatan 	= isset($_POST['nm_kegiatan']) ? $_POST['nm_kegiatan'] : '';
						$simpan 		= isset($_POST['simpan']) ? $_POST['simpan'] : '';

						if ($simpan) {
							$insert = mysql_query("update kegiatan set nm_kegiatan='$nm_kegiatan' where id_spj='$id_spj'") or die(mysql_error());
							if ($insert) {
								echo "<script>alert('Data berhasil diupdate');window.location='home.php?cat=data&page=kegiatan&act=tampil'</script>";
							}
						}
						?>

					<?php } ?>
				</div>
			</div>
			<script src="assets/lib/jquery/jquery.js"></script>
			<script>
				$(function() {
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
				function getKegiatan(item) {
					var value = item.value;
					// console.log(value);
					var drop = $('#keg');
					drop.empty();

					$.ajax({
						url: "getSpj3.php",
						data: "id=" + value,
						cache: false,
						success: function(msg) {
							drop.html(msg);
						}
					});
				}

				function update_total_realisasi(jml, id_spj) {
					var total_realisasi = jml.value;
					$.ajax({
						url: "pages/data/update_total_realisasi.php?total_realisasi=" + total_realisasi + "&id_spj=" + id_spj,
						type: "POST",
						success: function(response) {}
					});
				}

				function update_pagu_anggaran(jml, id_spj) {
					var pagu_anggaran = jml.value;
					$.ajax({
						url: "pages/data/update_pagu_anggaran.php?pagu_anggaran=" + pagu_anggaran + "&id_spj=" + id_spj,
						type: "POST",
						success: function(response) {
							location.reload();
						}
					});
				}
			</script>
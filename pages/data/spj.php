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
error_reporting(E_ALL ^ E_WARNING);
$act	= isset($_GET['act']) ? $_GET['act'] : '';
$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
$ckseksi = isset($_REQUEST['ckseksi']) ? $_REQUEST['ckseksi'] : '';

$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'") or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$level_user = $r['level_user'];

?>
<div class="tab-content">
	<div class="panel-body">
		<div class="panel panel-default" style="">
			<?php if ($act == "edit") { ?>
				<div class="panel-heading">
					<h3 class="panel-title">Detail Rekening</h3>
				</div>
			<?php } ?>
			<div class="panel-body">
				<?php if ($act == "edit") { ?>
					<?php
					$id_kegiatan	= isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : '';
					?>
					<div class="table-responsive">
						<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
							<table id="table1" class="table table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<td style="text-align:center">
											<font color=""><b>NO</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>SUBAG/SEKSI/UPT</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>KODE REKENING</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>URAIAN KEGIATAN</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>PAGU ANGGARAN</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>TOTAL REALISASI</b></font>
										</td>
										<td style="text-align:center">
											<font color=""><b>SISA PAGU ANGGARAN</b></font>
										</td>
									</tr>
								</thead>
								<tbody>
									<?php
									$total_pagu_anggaran = 0;
									$total_total_realisasi = 0;
									$total_sisa_pagu_anggaran = 0;
									$no = 1;
									$query = mysql_query("select * from spj a, user b where a.id_kegiatan='$id_kegiatan' AND a.kseksi=b.kseksi ORDER BY a.kseksi ASC");
									while ($hasil = mysql_fetch_array($query)) {
										$id_spj	= $hasil['id_spj'];
										$kode_rekening	= $hasil['kode_rekening'];
										$pagu_anggaran	= $hasil['pagu_anggaran'];
										if ($id_kegiatan == "28") {
											$q = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi_fukms WHERE id_spj='$id_spj' GROUP BY id_spj"));
										} else {
											$q = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) AS total_realisasi FROM total_realisasi WHERE id_spj='$id_spj' GROUP BY id_spj"));
										}

										$total_realisasi	= $hasil['total_realisasi'] + $q['total_realisasi'];
										$uraian_kegiatan	= $hasil['uraian_kegiatan'];
										$sisa_pagu_anggaran	= $pagu_anggaran - $total_realisasi;
										$kbid = $hasil['kbidang'];
										$kseksi = $hasil['kseksi'];
									?>

										<tr>
											<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
											<td valign="top" align="center" bgcolor=""><?= $hasil['nm_seksi']; ?></td>
											<td valign="top" align="left" bgcolor=""><a href="home.php?cat=data&page=spj_detail&spj=<?php echo $id_spj; ?>" target="_blank"><?php echo $kode_rekening; ?></a></td>
											<td valign="top" align="left" bgcolor=""><?php echo $uraian_kegiatan; ?></td>
											<td valign="top" align="right" bgcolor="">
												<?php
												// if($hasil['kseksi'] == $kseksi){
												?>
												<!-- <input name="pagu_anggaran<?php echo $id_spj; ?>" type="text" id="pagu_anggaran<?php echo $id_spj; ?>" value="<?php echo $pagu_anggaran; ?>" onChange="update_pagu_anggaran(this,<?php echo $id_spj; ?>)" /> -->
												<?php
												// } else {
												echo number_format($pagu_anggaran, 0, ',', '.');
												// }
												?>
											</td>
											<td valign="top" align="right" bgcolor="">
												<?php
												// if($hasil['kseksi'] == $kseksi){
												?>
												<!-- <input name="total_realisasi<?php echo $id_spj; ?>" type="text" id="total_realisasi<?php echo $id_spj; ?>" value="<?php echo $total_realisasi; ?>" onChange="update_total_realisasi(this,<?php echo $id_spj; ?>)" /> -->
												<?php
												// } else {
												echo number_format($total_realisasi, 0, ',', '.');
												// }
												?>
											</td>
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
									}
									?>
								</tbody>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th>Total</th>
									<th><?php echo number_format($total_pagu_anggaran, 0, ".", "."); ?></th>
									<th><?php echo number_format($total_total_realisasi, 0, ".", "."); ?></th>
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

			<?php
			$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : '';
			$q = mysql_query("SELECT * FROM user where kseksi='$kbidang'") or die(mysql_error());
			$r = mysql_fetch_array($q);
			$nm_seksi 	= $r['nm_seksi'];
			if (!isset($_GET['id_kegiatan'])) {
			?>

				<div class="table-responsive">
					<div class="widget-box">
						<div class="widget-header">
							<h5 class="widget-title">DAFTAR SPJ BIDANG <?php echo $nm_seksi ?></h5>

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
							<span class="widget-toolbar">
							</span>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
									<div class="box-body">

										<div class="form-group">
											<label class="col-sm-1 control-label">Seksi/Subag</label>
											<div class="col-sm-4">
												<select class="form-control" name="ckseksi" required>
													<?php if ($ckseksi != "") { ?>
														<?php
														$query = mysql_query("select * from user where level_user='2' and kbidang='$kbidang' and kseksi='$ckseksi'");
														$hasil = mysql_fetch_array($query);
														$kseksi	= $hasil['kseksi'];
														$nm_seksi	= $hasil['nm_seksi'];
														?>
														<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
														<option value="">-Pilih-</option>
														<?php
														$query = mysql_query("select * from user where level_user='2' and kbidang='$kbidang'");
														while ($hasil = mysql_fetch_array($query)) {
															$kseksi	= $hasil['kseksi'];
															$nm_seksi	= $hasil['nm_seksi'];
														?>
															<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
														<?php } ?>
													<?php } else { ?>
														<option value="">-Pilih-</option>
														<?php
														$query = mysql_query("select * from user where level_user='2' and kbidang='$kbidang'");
														while ($hasil = mysql_fetch_array($query)) {
															$kseksi	= $hasil['kseksi'];
															$nm_seksi	= $hasil['nm_seksi'];
														?>
															<option value="<?php echo $kseksi ?>"><?php echo $nm_seksi ?></option>
														<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class="col-sm-2">
												<button type="submit" class="btn btn-info pull-left" name="lihat" value="lihat">Cari</button>
											</div>
										</div>
									</div>
								</form>

								<table id="table1" class="table table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<td style="text-align:center">
												<font color=""><b>NO</b></font>
											</td>
											<td style="text-align:center">
												<font color=""><b>KEGIATAN</b></font>
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
												<font color=""><b>SISA PAGU ANGGARAN</b></font>
											</td>
											<td style="text-align:center">
												<font color=""><b>REALISASI(%)</b></font>
											</td>
											<td style="text-align:center">
												<font color=""><b>
														<center><a href="pages/data/cetak_spj_seksi.php?kbidang=<?php echo $kbidang; ?>" target="_blank">Cetak</a></center>
													</b></font>
											</td>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										$lihat	= isset($_POST['lihat']) ? $_POST['lihat'] : '';
										$kbidang	= isset($_GET['kbidang']) ? $_GET['kbidang'] : $kbid;
										if ($lihat) {
											$query = mysql_query("select * from kegiatan where kbidang='$kbidang' and kseksi like '%$ckseksi%' order by kseksi asc,realisasi_persen desc");
										} else {
											$query = mysql_query("select * from kegiatan where kbidang='$kbidang' order by kseksi asc,realisasi_persen desc");
										}
										while ($hasil = mysql_fetch_array($query)) {
											$id_kegiatan	= $hasil['id_kegiatan'];
											$nm_kegiatan	= $hasil['nm_kegiatan'];
											$kseksi			= $hasil['kseksi'];

											$a = mysql_query("SELECT * FROM user where kseksi='$kseksi'") or die(mysql_error());
											$r = mysql_fetch_array($a);
											$nm_seksi 	= $r['nm_seksi'];
											$kbidang	= $r['kbidang'];

											$a = mysql_query("SELECT * FROM user where kseksi='$kbidang'") or die(mysql_error());
											$r = mysql_fetch_array($a);
											$nm_bidang 	= $r['nm_seksi'];

											if ($kbidang != 'DK007') {
												$a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'") or die(mysql_error());
												// $a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where id_kegiatan='$id_kegiatan'")or die(mysql_error());
												$r = mysql_fetch_array($a);
												$jml_pagu_anggaran 	= $r['jml_pagu_anggaran'];

												$a = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM spj where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'") or die(mysql_error());
												$a2 = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM total_realisasi where id_kegiatan='$id_kegiatan' and kseksi='$kseksi'") or die(mysql_error());
												$r = mysql_fetch_array($a);
												$r2 = mysql_fetch_array($a2);
												$jml_total_realisasi 	= $r['jml_total_realisasi'] + $r2['jml_total_realisasi'];
											} else {
												$a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where id_kegiatan='$id_kegiatan' and kseksi2='$kseksi'") or die(mysql_error());
												// $a = mysql_query("SELECT sum(pagu_anggaran) as jml_pagu_anggaran FROM spj where id_kegiatan='$id_kegiatan'")or die(mysql_error());
												$r = mysql_fetch_array($a);
												$jml_pagu_anggaran 	= $r['jml_pagu_anggaran'];

												$a = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM spj where id_kegiatan='$id_kegiatan' and kseksi2='$kseksi'") or die(mysql_error());
												if ($id_kegiatan == "28") {
													$a2 = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM total_realisasi_fukms where id_kegiatan='$id_kegiatan' and kseksi2='$kseksi'") or die(mysql_error());
												} else {
													$a2 = mysql_query("SELECT sum(total_realisasi) as jml_total_realisasi FROM total_realisasi where id_kegiatan='$id_kegiatan' and kseksi2='$kseksi'") or die(mysql_error());
												}

												$r = mysql_fetch_array($a);
												$r2 = mysql_fetch_array($a2);
												$jml_total_realisasi 	= $r['jml_total_realisasi'] + $r2['jml_total_realisasi'];
											}

											$jml_sisa_pagu_anggaran	= $jml_pagu_anggaran - $jml_total_realisasi;

											$realisasi_persen	= (($jml_total_realisasi / $jml_pagu_anggaran) * 100);

											mysql_query("update kegiatan set realisasi_persen='$realisasi_persen' where kbidang='$kbidang' and kseksi='$kseksi' and id_kegiatan='$id_kegiatan'") or die(mysql_error());

										?>

											<tr>
												<td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $nm_kegiatan; ?></td>
												<td valign="top" align="left" bgcolor=""><?php echo $nm_seksi; ?></td>
												<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_pagu_anggaran, 0, ".", "."); ?></td>
												<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_total_realisasi, 0, ".", "."); ?></td>
												<td valign="top" align="right" bgcolor=""><?php echo number_format($jml_sisa_pagu_anggaran, 0, ".", "."); ?></td>
												<td valign="top" align="right" bgcolor=""><?php echo round($realisasi_persen, 2); ?></td>
												<td valign="top" align="center" bgcolor="">
													<?php if ($level_user != '4') { ?>
														<a href="home.php?cat=data&page=spj&act=edit&id_kegiatan=<?php echo $id_kegiatan ?>">SPJ</a>
													<?php } ?>
												</td>
											</tr>
										<?php
											error_reporting(0);
											$sum_pagu_anggaran += $jml_pagu_anggaran;
											$total_pagu_anggaran = $sum_pagu_anggaran;

											$sum_total_realisasi += $jml_total_realisasi;
											$total_total_realisasi = $sum_total_realisasi;

											$sum_sisa_pagu_anggaran += $jml_sisa_pagu_anggaran;
											$total_sisa_pagu_anggaran = $sum_sisa_pagu_anggaran;

											$total_realisasi_persen	= (($total_total_realisasi / $total_pagu_anggaran) * 100);
										}
										?>
									</tbody>
									<tr>
										<th></th>
										<th></th>
										<th>Total</th>
										<th><?php echo number_format($total_pagu_anggaran, 0, ".", "."); ?></th>
										<th><?php echo number_format($total_total_realisasi, 0, ".", "."); ?></th>
										<th><?php echo number_format($total_sisa_pagu_anggaran, 0, ".", "."); ?></th>
										<th><?php echo round($total_realisasi_persen, 2); ?></th>
										<th></th>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
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
						success: function(response) {}
					});
				}
			</script>
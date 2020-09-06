<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="pages/style-galery.css">
<style> 
	body{
		background:#FFFFFF;
	}
    th{
        color: black;
        font: Times New Roman;
    }
    td{
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
date_default_timezone_set("Asia/Jakarta");
$act	= isset($_GET['act']) ? $_GET['act'] : '';
// $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date("Y") ;
// $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;

if (isset($_GET['tgl']) && !isset($_POST['bulan'])) {
	$tg = explode("-", $_GET['tgl']);
	$tahun = $tg[0];
	$bulan = $tg[1];

	if (isset($_GET['kd_peg'])) {
		$l = mysql_fetch_assoc(mysql_query("SELECT gel_dep, nama, gel_bel FROM pegawai WHERE kd_peg='$_GET[kd_peg]'"));
		$nm = $l['gel_dep']." ".$l['nama']." ".$l['gel_bel'];
	}
} else {
	$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date("Y") ;
	$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;
}


$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi 	= $r['kseksi'];
$kbidang 	= $r['kbidang'];

function cekPagu($spj, $nominal)
{
	$a = mysql_fetch_assoc(mysql_query("SELECT pagu_anggaran, total_realisasi FROM spj WHERE id_spj='$spj'"));
	$b = mysql_fetch_assoc(mysql_query("SELECT total_realisasi FROM total_realisasi WHERE id_spj='$spj'"));

	$pagu = $a['pagu_anggaran'];
	$total = $a['total_realisasi'] + $b['total_realisasi'] + $nominal;

	if($total > $pagu){
		$hasil = 0;
	} else {
		$hasil = 1;
	}

	return $hasil;
}


?>

<div class="tab-content">			
	<div class="panel-body" >	
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				<?php if($act=="tampil" || isset($_POST['bulan'])){ ?>
						<h3 class="panel-title">INPUT KEGIATAN</h3>
				<?php }elseif($act=="ubah"){ ?>
						<h3 class="panel-title">UBAH KEGIATAN</h3>
				<?php }elseif($act=="lkh" && !isset($_POST['bulan'])){ ?>
						<h3 class="panel-title">LKH PEGAWAI - <?php echo $nm; ?></h3>
				<?php }elseif($act=="tolak"){ ?>
						<h3 class="panel-title">TOLAK SPJ</h3>
				<?php }?>
			</div>
			<div class="panel-body">
				<?php
					if($act=="tampil" || isset($_POST['bulan'])){
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6">
									<?php
										if($act == 'tampil'){
									?>
									<input type="hidden" name="kd_transaksi" value="<?php echo $kodeBarang; ?>">
									<?php
										} else {
									?>
									<input type="hidden" name="kd_transaksi" value="<?php echo $_GET['kd_transaksi']; ?>">
									<?php
										}
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Jenis SPJ</label>
										<div class="col-sm-8">
										<select name="jenis_spj" id="" class="form-control" required="">
											<option value="">-Pilih-</option>
											<option value="0">SPJ GU</option>
											<option value="1">SPJ LS</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Tanggal Kegiatan</label>
										<div class="col-sm-8">
										<input type="text" class="form-control" id="datepicker" autocomplete="off" name="tgl_kegiatan" value="" required>
										</div>
									</div>
									<?php
										if($kseksi == 'DJ001'){
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Tanggal Transfer</label>
										<div class="col-sm-8">
										<input type="text" name="tgl_transfer" id="datepicker2" autocomplete="off" placeholder=""  class="form-control" />
										</div>
									</div>
									<?php
										}
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kegiatan</label>
										<div class="col-sm-8">
										<select name="kd_kegiatan" id="kd_kegiatan" class="form-control" required="" onchange="getSpj(this)">
											<option value="">-Pilih-</option>
											<?php
											if($_GET['act'] == 'ubah'){
												$quer2=mysql_query("select * from kegiatan WHERE kseksi='$kseksi' AND kirim='0'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($id_kegiatan == $row['id_kegiatan']) {echo 'selected'; } ?> value="<?php echo $row['id_kegiatan']; ?>">
													<?php  echo $row['nm_kegiatan']; ?>
											</option>
											<?php
												}
											} else {
												$quer2=mysql_query("select * from kegiatan WHERE kseksi='$kseksi' AND kirim='0'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option value="<?php echo $row['id_kegiatan']; ?>">
													<?php  echo $row['nm_kegiatan']; ?>
											</option>
											<?php
												}
											}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">SPJ</label>
										<div class="col-sm-8">
										<select name="id_spj" id="kd_spj" class="form-control" required="" onchange="getBerkas(this)">
											<option value="">-Pilih-</option>  
											<?php
											if($_GET['act'] == 'ubah'){
												$quer2=mysql_query("select * from spj WHERE id_kegiatan='$id_kegiatan'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($id_spj == $row['id_spj']) {echo 'selected'; } ?> value="<?php echo $row['id_spj']; ?>">
													<?php  echo $row['uraian_kegiatan']; ?>
											</option>
											<?php
												}
											}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Nominal</label>
										<div class="col-sm-8">
										<input type="text" class="form-control" name="nominal" value="" required>
										</div>
									</div>
									<!-- <div class="form-group" id="pelaksana">
										<label class="col-sm-2 control-label">Pelaksana</label>
										<div class="col-sm-8">
											<select name="pegawai[]" id="peg2" class="select2 form-control" multiple="multiple">
											<?php
												$quer2=mysql_query("select * from pegawai");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
												}
											?>  
											</select>
										</div>
									</div> -->
									<!-- <div class="form-group" id="pelaksana2" style="display:none"> -->
									<div class="form-group" id="pelaksana2">
										<label class="col-sm-2 control-label">Pelaksana</label>
										<div class="col-sm-6">
											<select name="pegawai2[]" class="form-control" id="peg" onchange="pihakLuar(this, '')">
											<option value="000"></option>
											<?php
												$quer2=mysql_query("select * from pegawai ORDER BY nama ASC");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
												}
											?>  
											</select>
										</div>
										<div class="col-sm-2">
											<input type="text" class="form-control" name="rupiah[]" id="rup" placeholder="Nominal">
											<input type="text" name="ket[]" id="ket" style="display:none; width:128px; margin-top:10px" placeholder="Keterangan">
										</div>
										<div class="col-sm-2">
												<a href="javascript:new_form()" class="btn btn-primary btn-sm">Add</a>
										</div>
									</div>
									<div id="space0"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Uraian</label>
										<div class="col-sm-8">
										<textarea name="uraian" cols="30" rows="5" class="form-control" required></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-8">
											<input type="hidden" name="sk" value="<?php echo $kseksi; ?>">
											<input type="hidden" name="bd" value="<?php echo $kbidang; ?>">
										<button type="submit" class="btn btn-info pull-left" name="simpan" value="simpan">Simpan</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6" id="div"></div>
							</div>
						</div>
					</form>
					<?php
						if(isset($_POST['simpan'])){
							$query = "SELECT max(kd_transaksi) as maxKode FROM bibs";
							$hasil = mysql_query($query);
							$data = mysql_fetch_array($hasil);
							$kodeBarang = $data['maxKode'];

							$noUrut = (int) substr($kodeBarang, 3, 6);

							$noUrut++;

							$char = "TRX";
							$kodeBarang2 = $char . sprintf("%06s", $noUrut);
							// $kd_transaksi = $_POST['kd_transaksi'];
							$kd_transaksi = $kodeBarang2;
							$tgl_kegiatan = date("Y-m-d", strtotime($_POST['tgl_kegiatan']));

							if (isset($_POST['tgl_transfer']) && $_POST['tgl_transfer'] != '') {
								$tgl_transfer = date("Y-m-d", strtotime($_POST['tgl_transfer']));
							} else {
								$tgl_transfer = '';
							}

							$b = date("w", strtotime($tgl_kegiatan));
							if($b == '5'){
								$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							} elseif($b == '6'){
								$batas = strtotime('+6 day', strtotime($_POST['tgl_kegiatan']));
							} elseif($b == '0'){
								$batas = strtotime('+5 day', strtotime($_POST['tgl_kegiatan']));
							} else {
								$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							}

							$hari_ini = strtotime(date("Y-m-d"));
							
							$id_spj = $_POST['id_spj'];
							$nominal = $_POST['nominal'];
							$uraian = mysql_real_escape_string($_POST['uraian']);
							$sk = $_POST['sk'];
							$bd = $_POST['bd'];

							$pegawai = array();
							if (isset($_POST['pegawai'])) {
								$pegawai = $_POST['pegawai'];
							}
							$cek = count($pegawai);

							$pegawai2 = array();
							if (isset($_POST['pegawai2'])) {
								$pegawai2 = $_POST['pegawai2'];
							}
							$jml = count($pegawai2);

							function cek(){
								$hasil = '0';
								$id_spj = $_POST['id_spj'];
								$tgl_kegiatan = date("Y-m-d", strtotime($_POST['tgl_kegiatan']));
								$q = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$id_spj'"));
								if ($q['gol'] == '0') {
									$hasil = '1';
								} else {
									$pegawai = array();
									$pegawai = $_POST['pegawai2'];
									$rupiah = array();
									$rupiah = $_POST['rupiah'];
									$nominal = $_POST['nominal'];

									foreach ($pegawai as $key => $value) {
										if($value == '000'){
											$hasil = '1';
											continue;
										} elseif ($value == '9999') {
											$hasil = '1';
											continue;
										} else {
											$cek = mysql_query("SELECT * FROM bibs a, bibs_detail b, spj c WHERE a.kd_transaksi=b.kd_transaksi AND a.id_spj=c.id_spj AND b.kd_peg='$value' AND a.tgl_kegiatan='$tgl_kegiatan' AND c.gol='1'");
											if (mysql_num_rows($cek) > 0) {
												$hasil = '0';
												break;
											} else {
												$to = 0;
												for ($a=0; $a < count($rupiah) ; $a++) { 
													$to = $to+$rupiah[$a];
												}

												if ($nominal == $to) {
													$hasil = '1';
												} else {
													$hasil = '2';
												}
											}
										}
									}
								}
								
								return $hasil;
							}

							function simpan($kd){
								$kd_transaksi = $kd;
								$id_spj = $_POST['id_spj'];
								$rupiah = $_POST['rupiah'];
								$ket = $_POST['ket'];
								$q = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$id_spj'"));
								// if ($q['gol'] == '0') {
								// 	$pegawai = array();
								// 	$pegawai = $_POST['pegawai'];
								// 	foreach ($pegawai as $key => $value) {
								// 		mysql_query("INSERT INTO bibs_detail (kd_transaksi, kd_peg) VALUES('$kd_transaksi', '$value')");
								// 	}
								// } else {
									$pegawai = array();
									$pegawai = $_POST['pegawai2'];
									for ($i=0; $i < count($pegawai); $i++) {
										mysql_query("INSERT INTO bibs_detail (kd_transaksi, kd_peg, rupiah, ket) VALUES('$kd_transaksi', '$pegawai[$i]', '$rupiah[$i]', '$ket[$i]')");
									}
								// }
								
							}

							$pagu = cekPagu($id_spj, $nominal);

							
							if($hari_ini > $batas){
								echo "<script>alert('Data Gagal Disimpan. Tanggal Kegiatan SPJ Melewati batas 5 Hari Kerja.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							} else {
								$jenis = $_POST['jenis_spj'];
								if ($jenis != "") {
									if($pagu == '1'){
										// if ($cek == '0' && $jml == '0') {
										// 	$hasil = mysql_query("INSERT INTO bibs(kd_transaksi, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian) VALUES('$kd_transaksi', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$sk', '$bd', '$nominal', '$uraian')");
										// 	echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
										// } else {
											$cek2 = cek();
											if ($cek2 == '1') {
												$hasil = mysql_query("INSERT INTO bibs(kd_transaksi, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian, jenis) VALUES('$kd_transaksi', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$sk', '$bd', '$nominal', '$uraian', '$jenis')");
												if ($hasil) {
													simpan($kd_transaksi);
												}
	
												if ($hasil) {
													echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
												} else {
													echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
												}
											} elseif ($cek2 == '2') {
												echo "<script>alert('Data Gagal Disimpan. Nominal dan Rician Berbeda.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
											} else {
												echo "<script>alert('Data Gagal Disimpan. Cek Nama Pelaksana.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
											}
										// }
									} else {
										echo "<script>alert('Data Gagal Disimpan. Total Realisasi Melebihi Pagu Anggaran.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
									}
								} else {
									echo "<script>alert('Data Gagal Disimpan. Pilih Jenis SPJ Dahulu.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
								}
								
							}
							
						}
					} elseif($act == 'lkh' && !isset($_POST['bulan'])){
						$kd_peg = $_GET['kd_peg'];
						$tgl = $_GET['tgl'];
						$id = $_GET['id'];
						$dat = mysql_fetch_assoc(mysql_query("SELECT uraian FROM bibs WHERE id='$id'"));
				?>
				<div class="row">
					<div class="col-md-1">
						<a href="home.php?cat=data&page=registrasi-spj&act=tampil&tgl=<?php echo $tahun; ?>-<?php echo $bulan; ?>" class="btn btn-sm btn-danger">Kembali</a>
					</div>
					<div class="col-md-6">
						<p>Nama Kegiatan :</p>
						<p><?php echo $dat['uraian']; ?></p>
						<p>Tanggal <?php echo date("d-m-Y", strtotime($tgl)); ?></p>
					</div>
				</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<td>No</td>
									<td>Jam Mulai</td>
									<td>Jam Selesai</td>
									<td>Kegiatan</td>
									<td>Tempat</td>
									<td>Keterangan</td>
								</tr>
							</thead>
							<tbody>
								<?php
									$no = 1;
									$q = mysql_query("SELECT * FROM lkh WHERE kd_peg='$kd_peg' AND tgl='$tgl'");
									while($d = mysql_fetch_assoc($q)){
								?>
								<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $d['jam_mulai']; ?></td>
										<td><?php echo $d['jam_selesai']; ?></td>
										<td><?php echo $d['kegiatan']; ?></td>
										<td><?php echo $d['tempat']; ?></td>
										<td><?php echo $d['hasil']; ?></td>
								</tr>
								<?php
										$no++;
									}
								?>
							</tbody>
						</table>
					</div>
				<?php
					} elseif($act=="ubah"){
						$id = $_GET['id'];
						$s = mysql_fetch_assoc(mysql_query("SELECT a.tgl_kegiatan, a.tgl_transfer, a.id_spj, b.id_kegiatan, a.nominal, a.uraian, b.gol, a.jenis FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND a.kd_transaksi='$id'"));
						if ($s['tgl_transfer'] == '0000-00-00') {
							$tgl_transfer = '';
						} else {
							$tgl_transfer = date("m/d/Y", strtotime($s['tgl_transfer']));
						}
						
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6">
									<?php
										if($act == 'tampil'){
									?>
									<input type="hidden" name="kd_transaksi" value="<?php echo $kodeBarang; ?>">
									<?php
										} else {
									?>
									<input type="hidden" name="kd_transaksi" value="<?php echo $_GET['id']; ?>">
									<?php
										}
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Jenis SPJ</label>
										<div class="col-sm-8">
										<select name="jenis_spj" id="" class="form-control" required="">
											<option value="">-Pilih-</option>
											<option value="0" <?php if($s['jenis'] == '0'){ echo "selected"; } ?>>SPJ GU</option>
											<option value="1" <?php if($s['jenis'] == '1'){ echo "selected"; } ?>>SPJ LS</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Tanggal Kegiatan</label>
										<div class="col-sm-8">
										<input type="text" class="form-control" id="datepicker" autocomplete="off" name="tgl_kegiatan" value="<?php echo date("m/d/Y", strtotime($s['tgl_kegiatan'])); ?>" required>
										</div>
									</div>
									<?php
										if($kseksi == 'DJ001'){
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Tanggal Transfer</label>
										<div class="col-sm-8">
										<input type="text" name="tgl_transfer" id="datepicker2" autocomplete="off" placeholder="" value="<?php echo $tgl_transfer; ?>" class="form-control" />
										</div>
									</div>
									<?php
										}
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kegiatan</label>
										<div class="col-sm-8">
										<select name="kd_kegiatan" id="kd_kegiatan" class="form-control" required="" onChange="getSpj(this)">
											<option value="">-Pilih-</option>
											<?php
											if($_GET['act'] == 'ubah'){
												$quer2=mysql_query("select * from kegiatan WHERE kseksi='$kseksi' AND kirim='0'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['id_kegiatan'] == $row['id_kegiatan']) {echo 'selected'; } ?> value="<?php echo $row['id_kegiatan']; ?>">
													<?php  echo $row['nm_kegiatan']; ?>
											</option>
											<?php
												}
											} else {
												$quer2=mysql_query("select * from kegiatan WHERE kseksi='$kseksi' AND kirim='0'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option value="<?php echo $row['id_kegiatan']; ?>">
													<?php  echo $row['nm_kegiatan']; ?>
											</option>
											<?php
												}
											}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">SPJ</label>
										<div class="col-sm-8">
										<select name="id_spj" id="kd_spj" class="form-control" required="" onchange="getBerkas(this)">
											<option value="">-Pilih-</option>  
											<?php
											if($_GET['act'] == 'ubah'){
												$quer2=mysql_query("select * from spj WHERE id_kegiatan='$s[id_kegiatan]'");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['id_spj'] == $row['id_spj']) {echo 'selected'; } ?> value="<?php echo $row['id_spj']; ?>">
													<?php  echo $row['uraian_kegiatan']; ?>
											</option>
											<?php
												}
											}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Nominal</label>
										<div class="col-sm-8">
										<input type="text" class="form-control" name="nominal" value="<?php echo $s['nominal']; ?>" required>
										</div>
									</div>
									<!-- <div class="form-group" id="pelaksana" style="display:<?php if($s['gol'] == '1'){echo 'none';} else {echo 'block';} ?>">
										<label class="col-sm-2 control-label">Pelaksana</label>
										<div class="col-sm-8">
											<select name="pegawai[]" id="peg2" class="select2" style="width: 100%" multiple="multiple">
											<?php
												$quer2=mysql_query("select * from pegawai");
												while($row=mysql_fetch_assoc($quer2)){
													if($s['gol'] == '0'){
														$q = mysql_query("SELECT * FROM bibs_detail WHERE kd_transaksi='$id' AND kd_peg='$row[kd_peg]'");
														if(mysql_num_rows($q)>0){
											?>
											<option selected value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
														} else {
											?>
											<option value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
														}
													} else {
											?>
											<option value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
													}
												}
											?>  
											</select>
										</div>
									</div> -->
									<?php
										// if($s['gol'] == '1'){
											$q = mysql_query("SELECT * FROM bibs_detail WHERE kd_transaksi='$id'");
											$i=0;
											while($q2 = mysql_fetch_assoc($q)){
												if($i == '0'){
									?>
									<!-- <div class="form-group" id="pelaksana2" style="display:<?php if($s['gol'] == '1'){echo 'block';} else {echo 'none';} ?>"> -->
									<div class="form-group" id="pelaksana2">
										<label class="col-sm-2 control-label">Pelaksana</label>
										<div class="col-sm-6">
											<select name="pegawai2[]" class="form-control" id="peg" onchange="pihakLuar(this, '<?php echo $i; ?>')">
											<option value="000"></option>
											<?php
												$quer2=mysql_query("select * from pegawai ORDER BY nama ASC");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($q2['kd_peg'] == $row['kd_peg']){ echo "selected"; } ?> value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
												}
											?>  
											</select>
										</div>
										<div class="col-sm-2">
												<input type="text" class="form-control" name="rupiah[]" id="rup" value="<?php echo $q2['rupiah']; ?>">
											<input type="text" name="ket[]" id="ket<?php echo $i; ?>" style="display:<?php if($q2['kd_peg'] == "9999"){ echo "block"; } else { echo "none"; } ?>; width:128px; margin-top:10px" placeholder="Keterangan" value="<?php if($q2['kd_peg'] == "9999"){ echo $q2['ket']; } ?>">
										</div>
										<div class="col-sm-2">
												<a href="javascript:new_form()" class="btn btn-primary btn-sm">Add</a>
										</div>
									</div>
									<div id="space0"></div>
									<?php
												} else {
									?>
									<!-- <div class="form-group" id="<?php echo $i; ?>" style="display:<?php if($s['gol'] == '1'){echo 'block';} else {echo 'none';} ?>"> -->
									<div class="form-group" id="<?php echo $i; ?>">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<select name="pegawai2[]" class="form-control" id="peg" onchange="pihakLuar(this, '<?php echo $i; ?>')">
											<option value="000"></option>
											<?php
												$quer2=mysql_query("select * from pegawai ORDER BY nama ASC");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($q2['kd_peg'] == $row['kd_peg']){ echo "selected"; } ?> value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
												}
											?>  
											</select>
										</div>
										<div class="col-sm-2">
												<input type="text" class="form-control" name="rupiah[]" id="rup" value="<?php echo $q2['rupiah']; ?>">
											<input type="text" name="ket[]" id="ket<?php echo $i; ?>" style="display:<?php if($q2['kd_peg'] == "9999"){ echo "block"; } else { echo "none"; } ?>; width:128px; margin-top:10px" placeholder="Keterangan" value="<?php if($q2['kd_peg'] == "9999"){ echo $q2['ket']; } ?>">
										</div>
										<div class="col-sm-2">
												<a href="javascript:delt('<?php echo $i; ?>')" class="btn btn-danger btn-sm">Del</a>
										</div>
									</div>
									<div id="space<?php echo $i; ?>"></div>
									<?php
												}

												$i++;
											}
										// } else {
									?>
									<!-- <div class="form-group" id="pelaksana2" style="display:none">
										<label class="col-sm-2 control-label">Pelaksana</label>
										<div class="col-sm-6">
											<select name="pegawai2[]" class="form-control" id="peg">
											<option value="000"></option>
											<?php
												$quer2=mysql_query("select * from pegawai ORDER BY nama ASC");
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option value="<?php echo $row['kd_peg']; ?>">
													<?php  echo $row['nama']; ?>
											</option>
											<?php
												}
											?>  
											</select>
										</div>
										<div class="col-sm-2">
												<input type="text" class="form-control" name="rupiah[]" id="rup">
										</div>
										<div class="col-sm-2">
												<a href="javascript:new_form()" class="btn btn-primary btn-sm">Add</a>
										</div>
									</div>
									<div id="space0"></div> -->
									<?php
										// }
									?>
									<div class="form-group">
										<label class="col-sm-2 control-label">Uraian</label>
										<div class="col-sm-8">
										<textarea name="uraian" cols="30" rows="5" class="form-control" required><?php echo $s['uraian']; ?></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-8">
										<button type="submit" class="btn btn-info" name="ubah" value="simpan">Simpan</button>
										<a href="home.php?cat=data&page=registrasi-spj&act=tampil" class="btn btn-danger">Batal</a>
										</div>
									</div>
								</div>
								<div class="col-sm-6" id="div"></div>
							</div>
						</div>
					</form>
				<?php
						if(isset($_POST['ubah'])){
							$kd_transaksi = $_POST['kd_transaksi'];
							$tgl_kegiatan = date("Y-m-d", strtotime($_POST['tgl_kegiatan']));

							if (isset($_POST['tgl_transfer']) && $_POST['tgl_transfer'] != '') {
								$tgl_transfer = date("Y-m-d", strtotime($_POST['tgl_transfer']));
							} else {
								$tgl_transfer = '';
							}
							
							$b = date("w", strtotime($tgl_kegiatan));
							if($b == '5'){
								$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							} elseif($b == '6'){
								$batas = strtotime('+6 day', strtotime($_POST['tgl_kegiatan']));
							} elseif($b == '0'){
								$batas = strtotime('+5 day', strtotime($_POST['tgl_kegiatan']));
							} else {
								$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							}

							$hari_ini = strtotime(date("Y-m-d"));
							
							$id_spj = $_POST['id_spj'];
							$nominal = $_POST['nominal'];
							$uraian = mysql_real_escape_string($_POST['uraian']);

							$pegawai = array();
							if (isset($_POST['pegawai'])) {
								$pegawai = $_POST['pegawai'];
							}
							$cek = count($pegawai);

							$pegawai2 = array();
							if (isset($_POST['pegawai2'])) {
								$pegawai2 = $_POST['pegawai2'];
							}
							$jml = count($pegawai2);

							function cek(){
								$hasil = '0';
								$id_spj = $_POST['id_spj'];
								$tgl_kegiatan = date("Y-m-d", strtotime($_POST['tgl_kegiatan']));
								$q = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$id_spj'"));
								if ($q['gol'] == '0') {
									$hasil = '1';
								} else {
									$pegawai = array();
									$pegawai = $_POST['pegawai2'];
									$rupiah = array();
									$rupiah = $_POST['rupiah'];
									$nominal = $_POST['nominal'];
									foreach ($pegawai as $key => $value) {
										if($value == '000'){
											$hasil = '1';
											continue;
										} elseif ($value == '9999') {
											$hasil = '1';
											continue;
										} else {
											$cek = mysql_query("SELECT * FROM bibs a, bibs_detail b, spj c WHERE a.kd_transaksi=b.kd_transaksi AND a.id_spj=c.id_spj AND b.kd_peg='$value' AND a.tgl_kegiatan='$tgl_kegiatan' AND c.gol='1'");
											if (mysql_num_rows($cek) > 0) {
												$cek2 = mysql_num_rows(mysql_query("SELECT * FROM bibs a, bibs_detail b WHERE a.kd_transaksi=b.kd_transaksi AND a.id_spj='$id_spj' AND b.kd_peg='$value' AND a.tgl_kegiatan='$tgl_kegiatan'"));
												if ($cek2 > 0) {
													$to = 0;
													for ($a=0; $a < count($rupiah) ; $a++) { 
														$to = $to+$rupiah[$a];
													}
		
													if ($nominal == $to) {
														$hasil = '1';
													} else {
														$hasil = '2';
													}
													continue;
												} else {
													$hasil = '0';
													break;
												}
											} else {
												$to = 0;
												for ($a=0; $a < count($rupiah) ; $a++) { 
													$to = $to+$rupiah[$a];
												}

												if ($nominal == $to) {
													$hasil = '1';
												} else {
													$hasil = '2';
												}
												continue;
											}
										}
									}
								}
								
								return $hasil;
							}

							function simpan($kd){
								$kd_transaksi = $kd;
								$id_spj = $_POST['id_spj'];
								$rupiah = $_POST['rupiah'];
								$ket = $_POST['ket'];
								$q = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$id_spj'"));
								// if ($q['gol'] == '0') {
								// 	$pegawai = array();
								// 	$pegawai = $_POST['pegawai'];
								// 	foreach ($pegawai as $key => $value) {
								// 		mysql_query("INSERT INTO bibs_detail (kd_transaksi, kd_peg) VALUES('$kd_transaksi', '$value')");
								// 	}
								// } else {
									$pegawai = array();
									$pegawai = $_POST['pegawai2'];
									for ($i=0; $i < count($pegawai); $i++) {
										mysql_query("INSERT INTO bibs_detail (kd_transaksi, kd_peg, rupiah, ket) VALUES('$kd_transaksi', '$pegawai[$i]', '$rupiah[$i]', '$ket[$i]')");
									}
								// }
							}

							$pagu = cekPagu($id_spj, $nominal);

							// if($hari_ini > $batas){
							if($pagu == '0'){
								// echo "<script>alert('Data Gagal Disimpan. Tanggal Kegiatan SPJ Melewati batas 5 Hari Kerja.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
								echo "<script>alert('Data Gagal Disimpan. Total Realisasi Melebihi Pagu Anggaran.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							} else {
								// if ($cek == '0' && $jml == '0') {
								// 	$hasil = mysql_query("UPDATE bibs SET tgl_kegiatan='$tgl_kegiatan', tgl_transfer='$tgl_transfer', id_spj='$id_spj', kseksi='$kseksi', kbidang='$kbidang', nominal='$nominal', uraian='$uraian' WHERE  kd_transaksi='$kd_transaksi'");
								// } else {
									$cek2 = cek();
									$jenis = $_POST['jenis_spj'];
									if ($jenis != "") {
										if ($cek2 == '1') {
											$hasil = mysql_query("UPDATE bibs SET tgl_kegiatan='$tgl_kegiatan', tgl_transfer='$tgl_transfer', id_spj='$id_spj', kseksi='$kseksi', kbidang='$kbidang', nominal='$nominal', uraian='$uraian', jenis='$jenis' WHERE  kd_transaksi='$kd_transaksi'");
											if ($hasil) {
												mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$kd_transaksi'");
												simpan($kd_transaksi);
											}
	
	
											if ($hasil) {
												echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
											} else {
												echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
											}
										} elseif ($cek2 == '2') {
											echo "<script>alert('Data Gagal Disimpan. Nominal dan Rician Berbeda.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
										}  else {
											echo "<script>alert('Data Gagal Disimpan. Cek Nama Pelaksana.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
										}
									} else {
										echo "<script>alert('Data Gagal Disimpan. Jenis SPJ Harus Dipilih.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
									}
									
								// }
							}
							
						}
					}elseif($act=="setuju"){
						$id = $_GET['id'];
						$s = mysql_fetch_assoc(mysql_query("SELECT a.tgl_kegiatan, a.tgl_transfer, a.id_spj, b.id_kegiatan, a.nominal, a.uraian FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND a.kd_transaksi='$id'"));
						if ($s['tgl_transfer'] == '0000-00-00') {
							$tgl_transfer = '';
						} else {
							$tgl_transfer = date("m/d/Y", strtotime($s['tgl_transfer']));
						}
						
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<?php
									if($act == 'tampil'){
								?>
								<input type="hidden" name="kd_transaksi" value="<?php echo $kodeBarang; ?>">
								<?php
									} else {
								?>
								<input type="hidden" name="kd_transaksi" value="<?php echo $_GET['id']; ?>">
								<?php
									}
								?>
								<?php
									if($kseksi == 'DJ001'){
								?>
								<div class="form-group">
									<label class="col-sm-2 control-label">Tanggal Transfer</label>
									<div class="col-sm-8">
									<input type="text" name="tgl_transfer" id="datepicker2" autocomplete="off" placeholder="" value="<?php echo $tgl_transfer; ?>" class="form-control" />
									</div>
								</div>
								<?php
									}
								?>
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-8">
									<button type="submit" class="btn btn-info" name="setuju" value="simpan">Simpan</button>
									<a href="home.php?cat=data&page=registrasi-spj&act=tampil" class="btn btn-danger">Batal</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				<?php
						if(isset($_POST['setuju'])){
							$kd_transaksi = $_POST['kd_transaksi'];

							if (isset($_POST['tgl_transfer']) && $_POST['tgl_transfer'] != '') {
								$tgl_transfer = date("Y-m-d", strtotime($_POST['tgl_transfer']));
							} else {
								$tgl_transfer = '';
							}

							$q = mysql_fetch_assoc(mysql_query("SELECT * FROM bibs WHERE kd_transaksi='$kd_transaksi'"));
							$q2 = mysql_query("SELECT * FROM bibs_detail WHERE kd_transaksi='$kd_transaksi'");

							while($data = mysql_fetch_assoc($q2)){
								$row[] = $data;
							}
							
							$hasil = mysql_query("UPDATE bibs SET tgl_transfer='$tgl_transfer' WHERE  kd_transaksi='$kd_transaksi'");

							if ($hasil) {
								foreach ($row as $key) {
									$kd_peg = $key['kd_peg'];
									$rupiah = $key['rupiah'];
									$tgl_kegiatan = $q['tgl_kegiatan'];
									$id_spj = $q['id_spj'];
									$kseksi = $q['kseksi'];
									$kbidang = $q['kbidang'];
									$nominal = $q['nominal'];
									$uraian = $q['uraian'];

									if ($key['ket'] != '') {
										$uraian = $uraian." - ".$key['ket'];
									}

									$cek = mysql_num_rows(mysql_query("SELECT * FROM lkh_bibs WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'"));
									// echo $cek;
									if ($cek > 0) {
										if ($rupiah == '0') {
											mysql_query("UPDATE lkh_bibs SET tgl_transfer='$tgl_transfer', nominal='$nominal' WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'");
										} else {
											mysql_query("UPDATE lkh_bibs SET tgl_transfer='$tgl_transfer', nominal='$rupiah' WHERE kd_peg='$kd_peg' AND tgl_kegiatan='$tgl_kegiatan' AND id_spj='$id_spj' AND kseksi='$kseksi' AND kbidang='$kbidang'");
										}
									} else {
										if ($rupiah == '0') {
											mysql_query("INSERT INTO lkh_bibs(kd_peg, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian) VALUES('$kd_peg', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$kseksi', '$kbidang', '$nominal', '$uraian')");
										} else {
											mysql_query("INSERT INTO lkh_bibs(kd_peg, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian) VALUES('$kd_peg', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$kseksi', '$kbidang', '$rupiah', '$uraian')");
										}
										
									}
								}
								echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							} else {
								echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							}
							
						}
					}elseif($act=="tolak" || $act == "buka"){
						$id = $_GET['id'];
						$s = mysql_fetch_assoc(mysql_query("SELECT * FROM bibs WHERE kd_transaksi='$id'"));
				?>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
						<div class="box-body">
							<div class="row">
								<input type="hidden" name="kd_transaksi" value="<?php echo $_GET['id']; ?>">
								<?php
									if($kseksi == 'DJ001'){
								?>
								<div class="form-group">
									<label class="col-sm-2 control-label">Catatan</label>
									<div class="col-sm-8">
									<textarea name="alasan" id="elm2" cols="30" rows="5" class="form-control"><?php echo $s['alasan']; ?></textarea>
									</div>
								</div>
								<?php
									}
								?>
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-8">
									<input type="hidden" name="act" value="<?php echo $act; ?>">
									<button type="submit" class="btn btn-info" name="tolak" value="simpan">Simpan</button>
									<a href="home.php?cat=data&page=registrasi-spj&act=tampil" class="btn btn-danger">Batal</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				<?php
						if(isset($_POST['tolak'])){
							$kd_transaksi = $_POST['kd_transaksi'];
							$alasan = $_POST['alasan'];
							$tgl = date("Y-m-d");
							
							if($_POST['act'] == 'tolak'){
								$hasil = mysql_query("UPDATE bibs SET tolak='1', alasan='$alasan' WHERE  kd_transaksi='$kd_transaksi'");
							} else {
								$hasil = mysql_query("UPDATE bibs SET tolak='0', alasan='$alasan', terima='1', tgl_terima='$tgl' WHERE  kd_transaksi='$kd_transaksi'");
							}

							if ($hasil) {
								echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							} else {
								echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
							}
							
						}
					}
				?>
			</div>
		</div>
		<div class="table-responsive">	
			<div class="widget-box">
				<div class="widget-header">
					<h5 class="widget-title">DAFTAR</h5>

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
						<div class="row">
							<div class="col-sm-12">
								<form action="" method="post">
									<div class="form-group">
										<div class="col-sm-1">
											<select name="tahun" class="form-control">
												<?php
													for ($i=date("Y"); $i > (date("Y") - 5) ; $i--) { 
														echo "<option ";
														if ($i == $tahun) {
															echo "selected";
														}
														echo " value='$i'>$i</option>";
													}
												?>
											</select>
										</div>
										<div class="col-sm-1">
											<select name="bulan" class="form-control">
												<option <?php if($bulan == 'all'){ echo "selected"; } ?> value="all">Semua Bulan</option>
												<?php
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
											<button type="submit" name="lihat" class="btn btn-primary btn-sm">Cari</button>&nbsp;&nbsp;
											<?php
												if($kseksi == 'DJ001'){
											?>
											<a href="print-regis-spj.php?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>" target="_blank"><img src='img/print.png' title="Cetak" /></a>
											<?php
												}
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
						<table id="table1" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr>		
									<!-- <td style="text-align:center"><font color=""><b>NO</b></font></td> -->
									<td style="text-align:center"><font color=""><b>Urutan SPJ</b></font></td>
									<td style="text-align:center"><font color=""><b>Rekening</b></font></td>
									<td style="text-align:center"><font color=""><b>Kegiatan</b></font></td>
									<td style="text-align:center"><font color=""><b>Uraian</b></font></td>
									<td style="text-align:center"><font color=""><b>Nominal</b></font></td>
									<td style="text-align:center"><font color=""><b>Pelaksana</b></font></td>
									<td style="text-align:center"><font color=""><b>Tgl Kegiatan</b></font></td>
									<td style="text-align:center"><font color=""><b>Tgl Transfer</b></font></td>
									<?php
										// if($kseksi=='DJ001'){
									?>
									<td style="text-align:center"><font color=""><b>Tgl Terima SPJ</b></font></td>
									<?php
										// }
									?>
									<td style="text-align:center"><font color=""><b>Status</b></font></td>
									<td style="text-align:center"><font color=""><b>Catatan</b></font></td>
									<td style="text-align:center"><font color=""><b>Aksi</b></font></td>
								</tr>
							</thead>
							<tbody>
									<?php 
										$no = 1;
										if ($kseksi == 'DJ001') {
											if($bulan != 'all'){
												$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima, a.terima, a.jenis FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' order by a.id ASC");
											} else {
												$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima, a.terima, a.jenis FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' order by a.id ASC");
											}
										} else {
											if($bulan != 'all'){
												$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima, a.terima, a.jenis FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.kseksi='$kseksi' order by a.id ASC");
											} else {
												$query = mysql_query("SELECT a.id, a.kd_transaksi, a.tgl_kegiatan, a.tgl_transfer, a.nominal, c.kode_rekening, c.uraian_kegiatan, a.uraian, d.nm_kegiatan, a.kseksi, a.tolak, a.alasan, a.validasi, a.tgl_terima, a.terima, a.jenis FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.kseksi='$kseksi' order by a.id ASC");
											}
										}
										while ($hasil = mysql_fetch_array($query)){
											$id = $hasil['id'];
											$kd_transaksi = $hasil['kd_transaksi'];
											if ($hasil['tgl_transfer'] == '0000-00-00') {
												$tgl_transfer = "-";
											} else {
												$tgl_transfer = $hasil['tgl_transfer'];
											}
											
											if ($hasil['tgl_terima'] == '') {
												$tgl_terima = "-";
											} else {
												$tgl_terima = $hasil['tgl_terima'];
											}
											$tgl_kegiatan = $hasil['tgl_kegiatan'];
											$nominal = number_format($hasil['nominal'],0,',','.');
											$kode_rek = $hasil['kode_rekening'];
											$uraian = $hasil['uraian'];

											if ($hasil['jenis'] == "1") {
												$uraian = $uraian." - PENGAJUAN LS";
											}

											$kk = substr($hasil['nm_kegiatan'], 0, 20);
									?>		

									<tr>	
										<!-- <td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td> -->
										<td valign="top" align="center" bgcolor=""><?php echo sprintf("%05s", $id); ?></td>
										<td valign="top" align="left" bgcolor="">
											<!-- <a href="#" title="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>" data-toggle="popover" data-trigger="focus" data-content="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a> -->
											<a href="#" title="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $kk; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $uraian; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $nominal; ?></td>
										<td valign="top" align="left" bgcolor="">
												<?php
														$a = mysql_query("SELECT * FROM bibs_detail a, pegawai b WHERE a.kd_peg=b.kd_peg AND a.kd_transaksi='$kd_transaksi'");
														if (mysql_num_rows($a) > 0) {
															echo "<ol>";
															while ($da = mysql_fetch_assoc($a)) {
																if ($kseksi=='DJ001') {
																	if ($da['kd_peg'] == "9999") {
																		echo "<li>".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']." (".$da['ket'].")</li>";
																	} else {
																		echo "<li><a href='home.php?cat=data&page=registrasi-spj&act=lkh&kd_peg=$da[kd_peg]&tgl=$tgl_kegiatan&id=$id'>".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']."</a></li>";
																	}
																} else {
																	if ($da['kd_peg'] == "9999") {
																		echo "<li>".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']." (".$da['ket'].")</li>";
																	} else {
																		echo "<li>".$da['gel_dep']." ".$da['nama']." ".$da['gel_bel']."</li>";
																	}
																}
																
															}
															echo "</ol>";
														} else {
															echo "-";
														}
												?>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $tgl_kegiatan; ?></td>
										<td valign="top" align="left" bgcolor=""><?php echo $tgl_transfer; ?></td>
										<?php
											// if($kseksi=='DJ001'){
										?>
										<td valign="top" align="left" bgcolor=""><?php echo $tgl_terima; ?></td>
										<?php
											// }
										?>
										<td>
											<?php
												if ($tgl_transfer == '-') {
													if($hasil['validasi'] == '1'){
														echo "Sudah Divalidasi";
													} else {
														if ($hasil['tolak'] == '0') {
															if($hasil['terima'] == '0'){
																echo "SPJ Belum Diterima";
															} else {
																echo "Proses";
															}
														} elseif ($hasil['tolak'] == '1') {
															echo "Ditolak";
															// echo "<br>";
															// echo "<ul><li>".$hasil['alasan']."</li></ul>";
														}
													}
												} else {
													echo "Sudah Ditransfer";
												}
											?>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $hasil['alasan']; ?></td>
										<td valign="top" align="center" bgcolor="">
										<?php
										if($tgl_transfer == '-'){
											if(isset($_GET['id']) && $kseksi == $hasil['kseksi']){
												if($_GET['id'] != $kd_transaksi && $hasil['validasi'] == '0'){
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=registrasi-spj&del&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img width="26px" src='img/remove.png' title="Hapus" /></a>
										<br><br>
										<?php
												}
											} elseif($kseksi == $hasil['kseksi'] && $hasil['tolak'] == '0' && $hasil['validasi'] == '0') {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=registrasi-spj&del&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img width="26px" src='img/remove.png' title="Hapus" /></a>
										<br><br>
										<?php
											}
										}
										?>
										<?php
										if($tgl_transfer == '-' && $kseksi=='DJ001'){
											if($hasil['tolak'] == '0') {
												if($hasil['validasi'] == '0'){
													if($hasil['terima'] == '0'){
										?>
										<a href="home.php?cat=data&page=registrasi-spj&terima&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Sudah Menerima SPJ ??')"><img width="20px" src='img/setuju.png' title="Terima" /></a>
										<?php
													} else {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&valid&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Memvalidasi Data Ini ??')"><img width="20px" src='img/cek.png' title="Validasi" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=registrasi-spj&act=tolak&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menolak Data Ini ??')"><img src='img/hapus.png' title="Tolak" /></a>
										<?php
													}
												} else {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=setuju&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Mengisi Tanggal Transfer Data Ini ??')"><img width="20px" src='img/setuju.png' title="Isi Tanggal Transfer" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=registrasi-spj&batal&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Membatalkan Data Ini ??')"><img src='img/hapus.png' title="Batalkan Validasi" /></a>
										<?php
												}
											} elseif ($hasil['tolak'] == '1') {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=buka&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Membuka Data Ini ??')"><img width="20px" src='img/cek.png' title="Buka" /></a>
										<?php
											}
										}
										?>
										</td>
									</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="assets/lib/jquery/jquery.js"></script>
<script>
  $(function () {
		$('table').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false
		});
  });

	function getSpj(item) {
			var value = item.value;
			// console.log(value);
			var drop = $('#kd_spj');
			drop.empty();

			$.ajax({
				url: "getSpj.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					drop.html(msg);
				}
			});
		}

		function getBerkas(item) {
			var value = item.value;
			// console.log(value);
			var drop = $('#div');

			$.ajax({
				url: "getBerkas.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					drop.html(msg);
				}
			});

			$.ajax({
				url: "getBerkas2.php",
				data: "id="+value,
				cache: false,
				success: function(msg){
					if (msg === '1') {
						// document.getElementById('pelaksana').style.display = 'none';
						// document.getElementById('pelaksana2').style.display = 'block';
						console.log(msg);
					} else {
						// document.getElementById('pelaksana').style.display = 'block';
						// document.getElementById('pelaksana2').style.display = 'none';
						console.log(msg);
					}
				}
			});

			$("#peg").val("");
			$("#peg2 option:selected").removeAttr("selected");
			$("#rup").val("");

		}

		function pihakLuar(e, id) {
			var kode = e.value;
			console.log(kode);
			if (kode === "9999") {
				document.getElementById('ket'+id).style.display = 'block';
			} else {
				document.getElementById('ket'+id).style.display = 'none';
			}
		}

		$(function() {
			// $( "#datepicker" ).datepicker({ format: 'dd/mm/yyyy'});
			$('#datepicker').datepicker({
					format: "dd-mm-yyyy"
			});
			$('#datepicker2').datepicker({
					format: "dd-mm-yyyy"
			});
		});

		
		var ct = 0;

		function new_form() {
			ct++;
			var c = ct;
			c--;
			var drop = $('#pelaksana2');
			if(ct === 1){
				var space = $('#space0');
			} else {
				var space = $('#space'+c);
			}
			$.ajax({
				url: "getBerkas3.php",
				data: "id="+ct,
				cache: false,
				success: function(msg){
					// drop.html(msg).after('<div id="space"></div>');
					space.after(msg);
				}
			});
		}
// function to delete the newly added set of elements
		function delt(eleId)
		{
			var elem = document.getElementById(eleId);
			elem.parentNode.removeChild(elem);
			// return false;
		}
</script>


<?php 
if(isset($_GET['del'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$id'");
    mysql_query("delete from bibs WHERE kd_transaksi='{$id}'")or die(mysql_error());
    echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['valid'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET validasi='1' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Divalidasi'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['batal'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET validasi='0' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Dibatalkan'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['terima'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	$tgl = date('Y-m-d');
		mysql_query("UPDATE bibs SET terima='1', tgl_terima='$tgl' WHERE kd_transaksi='$id'");
    echo "<script>alert('SPJ Sudah Diterima'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
?>
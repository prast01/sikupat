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
	if ($kseksi == 'DJ001') {
		$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : 'all' ;
	} else {
		$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date("m") ;
	}
}

$guls = isset($_POST['guls']) ? $_POST['guls'] : 'all' ;
$sek = isset($_POST['sek']) ? $_POST['sek'] : 'all' ;
$stat = isset($_POST['stat']) ? $_POST['stat'] : 'all' ;

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


function upload($file, $id_spj, $id)
{
	$q = mysql_fetch_assoc(mysql_query("SELECT * FROM spj WHERE id_spj='$id_spj'"));
	if ($q['kode_rekening'] == '511.08.001' || $q['kode_rekening'] == '511.08.002' || $q['kode_rekening'] == '511.08.003' || $q['kode_rekening'] == '511.09.001') {
		$hsl = array("res"=>1, "msg"=>NULL);
	} else {
		$jml = count($file['tmp_name']);
		if ($jml < '5') {
			$allowed_ext = array('jpg','jpeg','png');
			$tes = array();
			for ($i=0; $i < $jml ; $i++) { 
				$file_name   = $file['name'][$i];
				$file_ext    = strtolower(end(explode('.', $file_name)));
				$file_tmp    = $file['tmp_name'][$i];
				if(in_array($file_ext, $allowed_ext) === true){
					if($file_size < 10000000){
						$name = date("YmdHis")."_".$file_name;
						$lokasi = 'pages/data/file_kegiatan/'.$name;
						$a = move_uploaded_file($file_tmp, $lokasi);
						if ($a) {
							mysql_query("INSERT INTO file_berkas(kd_transaksi, berkas) VALUES('$id', '$name')");
							// $tes[] = $name;
							$hsl = array("res"=>1, "msg"=>NULL);
						} else {
							$hsl = array("res"=>0, "msg"=> "Gagal Upload File-2".$lokasi);
						}
					} else {
						$hsl = array("res"=>0, "msg"=> "Gagal Upload File");
					}
				}else {
					$hsl = array("res"=>0, "msg"=> "Ekstensi file tidak diijinkan");
				}
			}
		} else {
			$hsl = array("res"=>0, "msg"=> "Jumlah File Melebihi 4 buah! Maksimal Unggah 4 File");
		}
		

	}
	

	return $hsl;
}

function _delFoto($id)
{
	$q = mysql_query("SELECT * FROM file_berkas WHERE kd_transaksi='$id'");
	$hasil2 = array();
	$hasil = 0;
	while ($d = mysql_fetch_assoc($q)) {
		$src = "pages/data/file_kegiatan/".$d['berkas'];
		$hapus = unlink($src);
		if ($hapus) {
			$hasil2[] = $src;
			$hasil = 1;
		} else {
			$hasil = 0;
		}
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
										<label class="col-sm-2 control-label">DPA</label>
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
												$quer2=mysql_query("select * from kegiatan WHERE (kseksi='$kseksi' OR id_kegiatan IN (SELECT id_kegiatan FROM spj WHERE kbidang2='DK007' AND kseksi='$kseksi' GROUP BY id_kegiatan)) AND kirim='0'");
												// $quer2=mysql_query("select * from kegiatan WHERE kirim='0'");
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
										<label class="col-sm-2 control-label">Rekening</label>
										<div class="col-sm-8">
										<select name="id_spj" id="kd_spj" class="form-control" required="" onchange="getBerkas(this); getTarget(this);">
											<option value="">-Pilih-</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kegiatan</label>
										<div class="col-sm-8">
										<select name="id_target" id="kd_target" class="form-control" required="" onchange="getSpj2(this)">
											<option value="">-Pilih-</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Nominal</label>
										<div class="col-sm-8">
										<input type="text" class="form-control" name="nominal" id="nominal" value="" required>
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
											<textarea name="uraian" id="uraian" cols="30" rows="5" class="form-control" required></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Hasil Foto</label>
										<div class="col-sm-8">
											<input type="file" name="file[]" class="form-control" multiple>
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
							$kd_transaksi = $kodeBarang2;
							$tgl_kegiatan = date("Y-m-d", strtotime($_POST['tgl_kegiatan']));

							if (isset($_POST['tgl_transfer']) && $_POST['tgl_transfer'] != '') {
								$tgl_transfer = date("Y-m-d", strtotime($_POST['tgl_transfer']));
							} else {
								$tgl_transfer = '';
							}

							$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));

							$hari_ini = strtotime(date("Y-m-d"));
							
							$id_spj = $_POST['id_spj'];
							$id_target = $_POST['id_target'];
							$nominal = $_POST['nominal'];
							$uraian = mysql_real_escape_string($_POST['uraian']);

							if ($_POST['sk'] == 'DJ001') {
								$l = mysql_fetch_assoc(mysql_query("SELECT kseksi, kbidang FROM spj WHERE id_spj='$id_spj'"));
								$sk = $l['kseksi'];
								$bd = $l['kbidang'];
							} else {
								$sk = $_POST['sk'];
								$bd = $_POST['bd'];
							}

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
								echo "<script>alert('Data Gagal Disimpan. Tanggal Kegiatan SPJ Melewati batas 7 Hari Kerja.');</script>";
							} else {
								$ok = 0;
								$jenis = $_POST['jenis_spj'];
								if ($jenis != "") {
									if($pagu == '1'){
										$cek2 = cek();
										if ($cek2 == '1') {
											$cek3 = upload($_FILES['file'], $id_spj, $kd_transaksi);
											if ($cek3['res']) {
												$hasil = mysql_query("INSERT INTO bibs(kd_transaksi, tgl_kegiatan, tgl_transfer, id_spj, kseksi, kbidang, nominal, uraian, jenis, berkas, id_target) VALUES('$kd_transaksi', '$tgl_kegiatan', '$tgl_transfer', '$id_spj', '$sk', '$bd', '$nominal', '$uraian', '$jenis', '$cek3[msg]', '$id_target')");
												if ($hasil) {
													simpan($kd_transaksi);
												}
	
												if ($hasil) {
													echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
												} else {
													echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
												}
											} else {
												echo "<script>alert('".$cek3['msg']."'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
											}
											
										} elseif ($cek2 == '2') {
											echo "<script>alert('Data Gagal Disimpan. Nominal dan Rician Berbeda.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
										} else {
											echo "<script>alert('Data Gagal Disimpan. Cek Nama Pelaksana.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
										}
									} else {
										echo "<script>alert('Data Gagal Disimpan. Total Realisasi Melebihi Pagu Anggaran.');</script>";
									}
								} else {
									echo "<script>alert('Data Gagal Disimpan. Pilih Jenis SPJ Dahulu.');</script>";
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
						$s = mysql_fetch_assoc(mysql_query("SELECT a.tgl_kegiatan, a.tgl_transfer, a.id_spj, b.id_kegiatan, a.nominal, a.uraian, b.gol, a.jenis, a.kseksi, a.kbidang, a.berkas, a.id_target FROM bibs a, spj b WHERE a.id_spj=b.id_spj AND a.kd_transaksi='$id'"));
						if ($s['tgl_transfer'] == '0000-00-00') {
							$tgl_transfer = '';
						} else {
							$tgl_transfer = date("m/d/Y", strtotime($s['tgl_transfer']));
						}
						
						$bulan = array(
							'01' => '1',
							'02' => '2',
							'03' => '3',
							'04' => '4',
							'05' => '5',
							'06' => '6',
							'07' => '7',
							'08' => '8',
							'09' => '9',
							'10' => '10',
							'11' => '11',
							'12' => '12'
						);
						$fi = 'b'.$bulan[date('m')];
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
										<label class="col-sm-2 control-label">DPA</label>
										<div class="col-sm-8">
										<select name="kd_kegiatan" id="kd_kegiatan" class="form-control" required="" onchange="getSpj(this)">
											<option value="">-Pilih-</option>
											<?php
												if($kseksi == 'DJ001'){
													$quer2=mysql_query("select * from kegiatan WHERE id_kegiatan IN (SELECT id_kegiatan FROM spj id_kegiatan) AND kirim='0'");
												} else {
													$quer2=mysql_query("select * from kegiatan WHERE (kseksi='$kseksi' OR id_kegiatan IN (SELECT id_kegiatan FROM spj WHERE kbidang2='DK007' AND kseksi='$kseksi' GROUP BY id_kegiatan)) AND kirim='0'");
												}
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['id_kegiatan'] == $row['id_kegiatan']) {echo 'selected'; } ?> value="<?php echo $row['id_kegiatan']; ?>">
													<?php  echo $row['nm_kegiatan']; ?>
											</option>
											<?php
												}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Rekening</label>
										<div class="col-sm-8">
										<select name="id_spj" id="kd_spj" class="form-control" required="" onchange="getBerkas(this); getTarget(this);">
											<option value="">-Pilih-</option>
											<?php
												if($kseksi == 'DJ001'){
													$quer2=mysql_query("SELECT id_spj, uraian_kegiatan, kode_rekening FROM spj WHERE id_kegiatan='$s[id_kegiatan]' AND kseksi='$s[kseksi]'");
												} else {
													$quer2=mysql_query("SELECT id_spj, uraian_kegiatan, kode_rekening FROM spj WHERE id_kegiatan='$s[id_kegiatan]' AND kseksi='$kseksi'");
												}
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['id_spj'] == $row['id_spj']) {echo 'selected'; } ?> value="<?php echo $row['id_spj']; ?>">
													<?php  echo '('.$row['kode_rekening'].') '.$row['uraian_kegiatan']; ?>
											</option>
											<?php
												}
											?>  
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kegiatan</label>
										<div class="col-sm-8">
										<select name="id_target" id="kd_target" class="form-control" required="" onchange="getSpj2(this)">
											<option value="">-Pilih-</option>
											<?php
												// if($kseksi == 'DJ001'){
													$quer2=mysql_query("SELECT * FROM target_detail WHERE id_spj='$s[id_spj]' AND seksi='$s[kseksi]' AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi='1' AND id_kegiatan='$s[id_kegiatan]')");
												// } else {
												// 	$quer2=mysql_query("SELECT * FROM target_detail WHERE id_spj='$s[id_spj]' AND seksi='$s[kseksi]' AND id_target_detail NOT IN (SELECT id_target FROM bibs WHERE id_spj='$s[id_spj]') AND id_spj IN (SELECT id_spj FROM tb_valid_rok WHERE $fi='1' AND id_kegiatan='$s[id_kegiatan]')");
												// }
												while($row=mysql_fetch_assoc($quer2)){
											?>
											<option <?php if($s['id_target'] == $row['id_target_detail']) {echo 'selected'; } ?> value="<?php echo $row['id_target']; ?>">
													<?php  echo $row['uraian']; ?>
											</option>
											<?php
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
									
									<!-- <div class="form-group">
										<label class="col-sm-2 control-label">Hasil Foto</label>
										<div class="col-sm-4">
											<a href="pages\data\file_kegiatan\<?php echo $s['berkas']; ?>" target="_blank"><img height="250" width="250" src="pages\data\file_kegiatan\<?php echo $s['berkas']; ?>"></a>
											<input type="hidden" name="foto-old" value="<?php echo $s['berkas']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-8">
											<input type="file" name="file" class="form-control">
										</div>
									</div> -->
									<!--<div class="form-group">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
											<center><a href="?cat=data&page=registrasi-spj&act=ubah&del2&id=<?php echo $id ?>" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img src='img/hapus.png' title="Hapus" /></a></center>
										</div>
									</div>
									-->
									<div class="form-group">
										<label class="col-sm-2 control-label"></label>
										<div class="col-sm-8">
										<input type="hidden" name="sk" value="<?php echo $kseksi; ?>">
										<input type="hidden" name="bd" value="<?php echo $kbidang; ?>">
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
							
							// $b = date("w", strtotime($tgl_kegiatan));
							// if($b == '5'){
							// 	$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							// } elseif($b == '6'){
							// 	$batas = strtotime('+6 day', strtotime($_POST['tgl_kegiatan']));
							// } elseif($b == '0'){
							// 	$batas = strtotime('+5 day', strtotime($_POST['tgl_kegiatan']));
							// } else {
								$batas = strtotime('+7 day', strtotime($_POST['tgl_kegiatan']));
							// }

							$hari_ini = strtotime(date("Y-m-d"));
							
							$id_spj = $_POST['id_spj'];
							$nominal = $_POST['nominal'];
							$uraian = mysql_real_escape_string($_POST['uraian']);
							
							if ($_POST['sk'] == 'DJ001') {
								$l = mysql_fetch_assoc(mysql_query("SELECT kseksi, kbidang FROM spj WHERE id_spj='$id_spj'"));
								$sk = $l['kseksi'];
								$bd = $l['kbidang'];
							} else {
								$sk = $_POST['sk'];
								$bd = $_POST['bd'];
							}

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

							// if($hari_ini > $batas ){
								if($pagu == '0'){
									// echo "<script>alert('Data Gagal Disimpan. Tanggal Kegiatan SPJ Melewati batas 5 Hari Kerja.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';";
									echo "<script>alert('Data Gagal Disimpan. Total Realisasi Melebihi Pagu Anggaran.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
								} else {
									// if ($cek == '0' && $jml == '0') {
									// 	$hasil = mysql_query("UPDATE bibs SET tgl_kegiatan='$tgl_kegiatan', tgl_transfer='$tgl_transfer', id_spj='$id_spj', kseksi='$kseksi', kbidang='$kbidang', nominal='$nominal', uraian='$uraian' WHERE  kd_transaksi='$kd_transaksi'");
									// } else {
										$cek2 = cek();
										$jenis = $_POST['jenis_spj'];
										if ($jenis != "") {
											if ($cek2 == '1') {
												if (!empty($_FILES['file']['name'])) {
													$hapus = _delFoto($_POST['foto-old']);
													if ($hapus) {
														$cek3 = upload($_FILES['file'], $id_spj);
														if ($cek3) {
															$hasil = mysql_query("UPDATE bibs SET tgl_kegiatan='$tgl_kegiatan', tgl_transfer='$tgl_transfer', id_spj='$id_spj', kseksi='$sk', kbidang='$bd', nominal='$nominal', uraian='$uraian', jenis='$jenis', berkas='$cek3[msg]' WHERE  kd_transaksi='$kd_transaksi'");
															if ($hasil) {
																mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$kd_transaksi'");
																simpan($kd_transaksi);
																echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
															} else {
																echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
															}
														} else {
															echo "<script>alert('".$cek3['msg']."'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
														}
													} else {
														echo $hapus;
													}
												} else {
													$hasil = mysql_query("UPDATE bibs SET tgl_kegiatan='$tgl_kegiatan', tgl_transfer='$tgl_transfer', id_spj='$id_spj', kseksi='$sk', kbidang='$bd', nominal='$nominal', uraian='$uraian', jenis='$jenis' WHERE  kd_transaksi='$kd_transaksi'");
													if ($hasil) {
														mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$kd_transaksi'");
														simpan($kd_transaksi);
														echo "<script>alert('Data Berhasil Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=tampil';</script>";
													} else {
														echo "<script>alert('Data Gagal Disimpan.'); location.href='home.php?cat=data&page=registrasi-spj&act=ubah&id=$kd_transaksi';</script>";
													}
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
							// }
							
						}

						if(isset($_GET['del2'])){
							$id = isset($_GET['id']) ? $_GET['id'] : '';
							$cari=mysql_query("select * from bibs where kd_transaksi='{$id}'" );
							$dt=mysql_fetch_array($cari);
							$berkas=$dt['berkas'];
							$tmpfile = "file_kegiatan/$berkas";
						   
							$ff = mysql_query("update bibs set berkas='' WHERE kd_transaksi='{$id}'")or die(mysql_error());
							
							if($ff) { 
								unlink ($tmpfile);
								echo "<script>alert('Foto Berhasil Dihapus'); window.location='?cat=data&page=registrasi-spj&act=ubah&id=$id'</script>";
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
								$hasil = mysql_query("UPDATE bibs SET tolak='1', alasan='$alasan', tgl_tolak='$tgl' WHERE  kd_transaksi='$kd_transaksi'");
							} else {
								$hasil = mysql_query("UPDATE bibs SET tolak='0', alasan='$alasan', tgl_terima='$tgl', merah='' WHERE  kd_transaksi='$kd_transaksi'");
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
		<?php
			if($act == "tampil"){
		?>
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
										<?php
											if($kseksi == 'DJ001'){
										?>
										<div class="col-sm-1">
											<select name="guls" class="form-control">
												<option <?php if($guls == 'all'){ echo "selected"; } ?> value="all">GU dan LS</option>
												<option <?php if($guls == '0'){ echo "selected"; } ?> value="0">GU</option>
												<option <?php if($guls == '1'){ echo "selected"; } ?> value="1">LS</option>
											</select>
										</div>
										<div class="col-sm-1">
											<select name="sek" class="form-control">
												<option value="all">Semua</option>
												<?php
													$f = mysql_query("SELECT * FROM user WHERE level_user='2'");
													while($g = mysql_fetch_assoc($f)){
														echo "<option ";
														if (isset($_POST['sek']) && $_POST['sek'] == $g['kseksi']) {
															echo "selected";
														}
														echo " value='$g[kseksi]'>$g[nm_seksi]</option>";
													}
												?>
											</select>
										</div>
										<div class="col-sm-1">
											<select name="stat" class="form-control">
												<option <?php if($stat == 'all'){ echo "selected"; } ?> value="all">Semua</option>
												<option <?php if($stat == '0'){ echo "selected"; } ?> value="0">Belum Diterima</option>
												<option <?php if($stat == '1'){ echo "selected"; } ?> value="1">Direvisi</option>
												<option <?php if($stat == '2'){ echo "selected"; } ?> value="2">Proses</option>
												<option <?php if($stat == '3'){ echo "selected"; } ?> value="3">Divalidasi</option>
											</select>
										</div>
										<?php
											}
										?>
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
									<td style="text-align:center"><font color=""><b>Tanggal</b></font></td>
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
												if ($guls != 'all') {
													if ($sek != 'all') {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
														}
														
													} else {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.hapus='0' order by a.id ASC");
													}
												} else {
													if ($sek != 'all') {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
													} else {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.hapus='0' order by a.id ASC");
													}
												}
											} else {
												if ($guls != 'all') {
													if ($sek != 'all') {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
														}
														
													} else {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.jenis = '$guls' AND a.hapus='0' order by a.id ASC");
													}
												} else {
													if ($sek != 'all') {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.kseksi='$sek' AND a.hapus='0' order by a.id ASC");
													} else {
														if ($stat == '0') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='0' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif($stat == '1') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='1' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '2') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='0' AND a.validasi='0' AND a.hapus='0' order by a.id ASC");
														} elseif ($stat == '3') {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.terima='1' AND a.tolak='0' AND a.validasi='1' AND a.hapus='0' order by a.id ASC");
														} else {
															$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.hapus='0' order by a.id ASC");
														}
														// $query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.tgl_transfer = '0000-00-00' AND a.kseksi != '' AND a.hapus='0' order by a.id ASC");
													}
												}
											}
										} else {
											if($bulan != 'all'){
												$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND MONTH(a.tgl_kegiatan)='$bulan' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.kseksi='$kseksi' AND a.hapus='0' order by a.id ASC");
											} else {
												$query = mysql_query("SELECT * FROM bibs a, spj c, kegiatan d WHERE YEAR(a.tgl_kegiatan)='$tahun' AND a.id_spj=c.id_spj AND c.id_kegiatan=d.id_kegiatan AND a.kseksi='$kseksi' AND a.hapus='0' order by a.id ASC");
											}
										}
										$tt = 0;
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

											if ($hasil['tgl_tolak'] == '') {
												$tgl_tolak = "-";
											} else {
												$tgl_tolak = $hasil['tgl_tolak'];
											}
											
											if ($hasil['tgl_valid'] == '') {
												$tgl_valid = "-";
											} else {
												$tgl_valid = $hasil['tgl_valid'];
											}

											$tgl_kegiatan = $hasil['tgl_kegiatan'];
											$nominal = number_format($hasil['nominal'],0,',','.');
											$kode_rek = $hasil['kode_rekening'];
											$uraian = $hasil['uraian'];

											if ($hasil['jenis'] == "1") {
												$uraian = $uraian." - PENGAJUAN LS";
											}

											$kk = substr($hasil['nm_kegiatan'], 0, 20);
											$tt = $tt+$hasil['nominal'];

											$bg = '';
											if ($hasil['merah']) {
												$bg = 'yellow';
											}
											
									?>		

									<tr bgcolor="<?php echo $bg; ?>">	
										<!-- <td valign="top" align="center" bgcolor=""><?php echo $no++; ?></td> -->
										<td valign="top" align="center" bgcolor=""><?php echo sprintf("%05s", $id); ?></td>
										<td valign="top" align="left" bgcolor="">
											<!-- <a href="#" title="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>" data-toggle="popover" data-trigger="focus" data-content="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a> -->
											<a href="#" title="<?php echo $hasil['nm_kegiatan'].' / '.$hasil['uraian_kegiatan']; ?>"><?php echo $kode_rek ?></a>
										</td>
										<td valign="top" align="left" bgcolor=""><?php echo $kk; ?></td>
										<td valign="top" align="left" bgcolor="">
											<?php
												echo $uraian;
												// if($hasil['berkas'] != ''){
											?>
											<br><br> <a href="cetak_a21.php?id=<?php echo $hasil['kd_transaksi']; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-warning btn-sm">Cetak A2-1</a>
											<?php
												// }
											?>
										</td>
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
										<td valign="top" align="left" bgcolor="">
											<?php
												echo "Terima :<br>".$tgl_terima."<br>";
												echo "Tolak :<br>".$tgl_tolak."<br>";
												echo "Validasi :<br>".$tgl_valid."<br>";
											?>
										</td>
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
															echo "Direvisi";
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
											} elseif($hasil['tolak'] == '0' && $hasil['validasi'] == '0' && $hasil['terima'] == '0') {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
										<a href="home.php?cat=data&page=registrasi-spj&del&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img width="26px" src='img/remove.png' title="Hapus" /></a>
										<br><br>
										<?php
											} elseif($hasil['tolak'] == '1') {
										?>
										<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
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
														<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
														<a href="home.php?cat=data&page=registrasi-spj&del&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><img width="26px" src='img/remove.png' title="Hapus" /></a><br><br>
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
												<a href="home.php?cat=data&page=registrasi-spj&act=ubah&id=<?php echo $kd_transaksi ?>"><img src='img/edit.png' title="Edit" /></a>&nbsp;&nbsp;
												<a href="home.php?cat=data&page=registrasi-spj&act=buka&id=<?php echo $kd_transaksi ?> " onClick="return confirm('Apakah Anda Yakin Ingin Membuka Data Ini ??')"><img width="20px" src='img/cek.png' title="Buka" /></a>
											<?php
											}
										}
										?>
										</td>
									</tr>
								<?php }?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" class="text-right">TOTAL</th>
									<th><?php echo number_format($tt, 0, ',', '.'); ?></th>
									<th colspan="7"></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
			}
		?>
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
	function getTarget(item) {
		var value = item.value;
		var id_keg = $("#kd_kegiatan").val();
		// console.log(value);
		var drop = $('#kd_target');
		drop.empty();
		var data = {
			'id' : value,
			'id_keg' : id_keg
		}

		$.ajax({
			url: "getSpj4.php",
			// data: "id="+value,
			data: data,
			cache: false,
			success: function(msg){
				drop.html(msg);
			}
		});
	}

	function getSpj2(item) {
		var value = item.value;

		$.ajax({
			url: "getSpj5.php",
			data: "id="+value,
			cache: false,
			success: function(msg){
				var data = JSON.parse(msg);
				$("#nominal").val(data['nominal']);
				$("#uraian").val(data['uraian']);
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
	
	$a = mysql_fetch_assoc(mysql_query("SELECT berkas FROM bibs WHERE kd_transaksi='$id'"));
	$hapus = _delFoto($id);
	if ($hapus) {
		mysql_query("DELETE FROM bibs_detail WHERE kd_transaksi='$id'");
		mysql_query("DELETE FROM file_berkas WHERE kd_transaksi='$id'");
		mysql_query("DELETE from bibs WHERE kd_transaksi='{$id}'")or die(mysql_error());
		echo "<script>alert('Data Berhasil Dihapus'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
	}
}
if(isset($_GET['valid'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	$tgl = date('Y-m-d');
	
		mysql_query("UPDATE bibs SET validasi='1', tgl_valid='$tgl' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Divalidasi'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['batal'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
		mysql_query("UPDATE bibs SET validasi='0', tgl_valid='' WHERE kd_transaksi='$id'");
    echo "<script>alert('Data Berhasil Dibatalkan'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
if(isset($_GET['terima'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	$tgl = date('Y-m-d');
		mysql_query("UPDATE bibs SET terima='1', tgl_terima='$tgl', merah='' WHERE kd_transaksi='$id'");
    echo "<script>alert('SPJ Sudah Diterima'); window.location='home.php?cat=data&page=registrasi-spj&act=tampil'</script>";
}
?>
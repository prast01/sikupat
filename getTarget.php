<?php
	include "koneksi.php";
	session_start();
	$kseksi	= isset($_SESSION['kseksi']) ? $_SESSION['kseksi'] : '';
	$page = (isset($_POST['page'])) ? $_POST['page'] : '' ;
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
	switch ($page) {
		case 'realisasi':
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$title = $_POST['title'];
			?>
			<div class="modal-header">
				<div class="row">
					<div class="col-md-10 col-xs-10">
						<h5 class="modal-title"><?php echo ucfirst($_POST['jenis']); ?> Bulan <?php echo $bulan[$bln].' '.date('Y'); ?><br><?php echo $title; ?></h5>
					</div>
					<div class="col-md-2 col-xs-2">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="table-responsive">
							<h4>Daftar SPJ sudah ditransfer</h4>
							<table class="table table-bordered text-size-small">
								<thead>
									<tr>
										<th width='5%'>No</th>
										<th width='70%'>Uraian Kegiatan</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$no = 1;
										$q = mysql_query("SELECT * FROM bibs WHERE id_spj='$id_spj' AND kseksi='$kseksi' AND tgl_transfer != '0000-00-00' AND validasi='1' AND MONTH(tgl_transfer)='$bln'");
										$total = 0;
										while ($data = mysql_fetch_assoc($q)) {
											$total = $total+$data['nominal'];
									?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $data['uraian']; ?></td>
										<td class='text-right'><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
									</tr>
									<?php
											$no++;
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan='2' class='text-right'>Total</th>
										<th class='text-right'><?php echo number_format($total, 0, ',', '.'); ?></th>
									</tr>
								</tfoot>
							</table>
							<h4>Daftar SPJ belum ditransfer</h4>
							<table class="table table-bordered text-size-small">
								<thead>
									<tr>
										<th width='5%'>No</th>
										<th width='70%'>Uraian Kegiatan</th>
										<th>Nominal</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$no2 = 1;
										$q2 = mysql_query("SELECT * FROM bibs WHERE id_spj='$id_spj' AND kseksi='$kseksi' AND tgl_transfer = '0000-00-00' AND MONTH(tgl_kegiatan)='$bln'");
										$total2 = 0;
										while ($data2 = mysql_fetch_assoc($q2)) {
											$total2 = $total2+$data2['nominal'];
											if ($data2['tolak'] == '1' && $data2['terima'] == '1' && $data2['validasi'] == '0') {
												$status = 'Direvisi';
											} elseif ($data2['tolak'] == '0' && $data2['terima'] == '1' && $data2['validasi'] == '0') {
												$status = 'Proses';
											} elseif ($data2['tolak'] == '0' && $data2['terima'] == '1' && $data2['validasi'] == '1') {
												$status = 'Tervalidasi';
											} else {
												$status = "SPJ Belum Diterima";
											}
									?>
									<tr>
										<td><?php echo $no2; ?></td>
										<td><?php echo $data2['uraian']; ?></td>
										<td class='text-right'><?php echo number_format($data2['nominal'], 0, ',', '.'); ?></td>
										<td><?php echo $status; ?></td>
									</tr>
									<?php
											$no2++;
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan='2' class='text-right'>Total</th>
										<th class='text-right'><?php echo number_format($total2, 0, ',', '.'); ?></th>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php
			break;
		case 'target2':
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$title = $_POST['title'];
			$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
			if ($d['nominal'] == '') {
				$nom = 0;
			} else {
				$nom = $d['nominal'];
			}
			?>
			<div class="modal-header">
				<div class="row">
					<div class="col-md-10 col-xs-10">
						<h5 class="modal-title"><?php echo ucfirst($_POST['jenis']); ?> Bulan <?php echo $bulan[$bln].' '.date('Y'); ?><br><?php echo $title; ?></h5>
					</div>
					<div class="col-md-2 col-xs-2">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<?php
					if($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001'){
				?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<form class="form-horizontal" id="form-modal">
							<input type="hidden" id="seksi" value="<?php echo $_POST['s2']; ?>">
							<input type="hidden" id="keg" value="<?php echo $_POST['keg']; ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label">Uraian</label>
								<div class="col-sm-8">
									<textarea id="uraian" class="form-control" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Nominal</label>
								<div class="col-sm-8">
									<input type="number" id="nominal" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Waktu</label>
								<div class="col-sm-8">
									<select id="minggu" class="form-control">
										<option value="1">Minggu ke - 1</option>
										<option value="2">Minggu ke - 2</option>
										<option value="3">Minggu ke - 3</option>
										<option value="4">Minggu ke - 4</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									<input type="hidden" id="page" value="simpanTarget">
								</label>
								<div class="col-sm-8">
									<button type="button" onclick="simpanTarget('<?php echo $id_spj; ?>', '<?php echo $bln; ?>')" class="btn btn-success">Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php
					}
				?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="table-responsive">
							<table class="table table-bordered text-size-small">
								<thead>
									<tr>
										<!-- <th width='5%'>No</th> -->
										<th width='60%'>Uraian Kegiatan</th>
										<th>Waktu</th>
										<th>Nominal</th>
										<?php
											if($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001'){
										?>
										<th>Aksi</th>
										<?php
											}
										?>
									</tr>
								</thead>
								<tbody id="tbody">
									<?php
										$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
										if ($d['nominal'] == '') {
											$nom = 0;
										} else {
											$nom = $d['nominal'];
										}
										$q = mysql_query("SELECT * FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'");
										$total=0;
										while ($data = mysql_fetch_assoc($q)) {
											if ($data['minggu'] != '') {
												$waktu = "Minggu ke - ".$data['minggu'];
											} else {
												$waktu = '';
											}
											
									?>
									<tr id="<?php echo $data['id_target_detail']; ?>">
										<!-- <td><?php echo $no; ?></td> -->
										<td><?php echo $data['uraian']; ?></td>
										<td><?php echo $waktu; ?></td>
										<td class='text-right'><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
										<?php
											if($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001'){
										?>
										<td align="center">
											<span style="cursor:pointer" title="Hapus" onclick="del('<?php echo $data['id_target_detail']; ?>', '<?php echo $data['id_spj']; ?>', '<?php echo $data['bulan']; ?>', '<?php echo $data['uraian']; ?>', '<?php echo $data['nominal']; ?>', '<?php echo $data['seksi']; ?>', '<?php echo $data['id_keg']; ?>')">X</span>
										</td>
										<?php
											}
										?>
									</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan='2' class='text-right'>Total</th>
										<th class='text-right' id="nomi"><span><?php echo number_format($nom, 0, ',', '.'); ?></span></th>
										<?php
											if($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001'){
										?>
										<th></th>
										<?php
											}
										?>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="selesai()">Selesai</button> -->
				<button type="button" class="btn btn-secondary" onclick="selesai()">Selesai</button>
				<!-- <input type="button" id="add_simpan" class="finish btn btn-success sweet" value="Simpan"/> -->
			</div>
			<?php
			break;

		case 'target':
			$kseksi = $_POST['id_seksi'];
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$title = $_POST['title'];
			$keg = $_POST['keg'];
			$keg_pl = $_POST['keg_pl'];
			$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
			if ($d['nominal'] == '') {
				$nom = 0;
			} else {
				$nom = $d['nominal'];
			}

			$f = 'b'.$bln;
			$cek = mysql_fetch_assoc(mysql_query("SELECT $f FROM tb_valid_rok WHERE id_spj='$id_spj' AND kseksi='$kseksi'"));
			?>
			<div class="modal-header">
				<div class="row">
					<div class="col-md-10 col-xs-10">
						<h5 class="modal-title"><?php echo ucfirst($_POST['jenis']); ?> Bulan <?php echo $bulan[$bln].' '.date('Y'); ?><br><?php echo $title; ?></h5>
					</div>
					<div class="col-md-2 col-xs-2">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<?php
					if(($cek[$f] == '0' && ($_POST['s1'] == $_POST['s2']) || $_POST['s1'] == 'DJ001')){
						// if(($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001')){
				?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<form class="form-horizontal" id="form-modal">
							<input type="hidden" id="seksi" value="<?php echo $_POST['s2']; ?>">
							<input type="hidden" id="seksi_pl" value="<?php echo $kseksi; ?>">
							<input type="hidden" id="keg" value="<?php echo $_POST['keg']; ?>">
							<input type="hidden" id="keg_pl" value="<?php echo $keg_pl; ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label">Uraian</label>
								<div class="col-sm-8">
									<textarea id="uraian" class="form-control" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Nominal</label>
								<div class="col-sm-8">
									<input type="number" id="nominal" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Waktu</label>
								<div class="col-sm-8">
									<select id="minggu" class="form-control">
										<option value="1">Minggu ke - 1</option>
										<option value="2">Minggu ke - 2</option>
										<option value="3">Minggu ke - 3</option>
										<option value="4">Minggu ke - 4</option>
										<option value="5">Minggu ke - 5</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Penanggung Jawab</label>
								<div class="col-sm-8">
									<select id="pl" class="form-control">
										<option value="">Pilih</option>
										<?php
											$q = mysql_query("SELECT * FROM pegawai ORDER BY nama ASC");
											while ($a = mysql_fetch_assoc($q)) {
										?>
										<option value="<?php echo $a['kd_peg']; ?>"><?php echo $a['nama']; ?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Keterangan</label>
								<div class="col-sm-8">
									<textarea id="keterangan" class="form-control" rows="2"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									<input type="hidden" id="page" value="simpanTarget">
									<input type="hidden" id="id_target">
									<input type="hidden" id="tr">
								</label>
								<div class="col-sm-8">
									<button type="button" id="simpan" onclick="simpanTarget('<?php echo $id_spj; ?>', '<?php echo $bln; ?>')" class="btn btn-success">Simpan</button>
									<button type="button" id="ubah" onclick="ubahTarget('<?php echo $id_spj; ?>', '<?php echo $bln; ?>')" class="btn btn-warning">Ubah</button>
									<button type="reset" id="batal" onclick="bat()" class="btn btn-danger">Batal</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php
					}
				?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="table-responsive">
							<table class="table table-bordered text-size-small">
								<thead>
									<tr>
										<!-- <th width='5%'>No</th> -->
										<th width='50%'>Uraian Kegiatan</th>
										<th>PJ</th>
										<th>Waktu</th>
										<th>Nominal</th>
										<?php
											if(($cek[$f] == '0' && ($_POST['s1'] == $_POST['s2']) || $_POST['s1'] == 'DJ001')){
										?>
										<th colspan="2">Aksi</th>
										<?php
											}
										?>
									</tr>
								</thead>
								<tbody id="tbody">
									<?php
										//$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
										if($kseksi=="DJ002"){
											$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln' AND st='0'"));
										}else{
											if ($keg != '6') {
												$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln' and seksi='$kseksi' and id_keg_pl='$keg_pl' AND st='0'"));
											} else {
												$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln' and seksi_pl='$kseksi' and id_keg_pl='$keg_pl' AND st='0'"));
											}
											
										}
										if ($d['nominal'] == '') {
											$nom = 0;
										} else {
											$nom = $d['nominal'];
										}
										//$q = mysql_query("SELECT * FROM target_detail a WHERE a.id_spj='$id_spj' AND a.bulan='$bln'");
										if($kseksi=="DJ002"){
											$q = mysql_query("SELECT * FROM target_detail a WHERE a.id_spj='$id_spj' AND a.bulan='$bln' AND st='0'");
										}else{
											$q = mysql_query("SELECT * FROM target_detail a WHERE a.id_spj='$id_spj' AND a.bulan='$bln' and a.seksi_pl='$kseksi' and a.id_keg_pl='$keg_pl' AND st='0'");
										}
										$total=0;
										while ($data = mysql_fetch_assoc($q)) {
											if ($data['minggu'] != '') {
												$waktu = "Minggu ke - ".$data['minggu'];
											} else {
												$waktu = '';
											}
											$q4 = mysql_fetch_assoc(mysql_query("SELECT nama FROM pegawai WHERE kd_peg='$data[pl]'"));
									?>
									<tr id="<?php echo $data['id_target_detail']; ?>">
										<!-- <td><?php echo $no; ?></td> -->
										<td><?php echo $data['uraian']; ?><br>Keterangan :<br><?php echo $data['ket']; ?></td>
										<td><?php echo $q4['nama']; ?></td>
										<td><?php echo $waktu; ?></td>
										<td class='text-right'><?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
										<?php
											// if(($_POST['s1'] == $_POST['s2'] || $_POST['s1'] == 'DJ001') && $bln > date('m')){
												if(($cek[$f] == '0' && ($_POST['s1'] == $_POST['s2']) || $_POST['s1'] == 'DJ001')){
										?>
										<td align="center">
											<span style="cursor:pointer" title="Hapus" onclick="del('<?php echo $data['id_target_detail']; ?>', '<?php echo $data['id_spj']; ?>', '<?php echo $data['bulan']; ?>', '<?php echo $data['uraian']; ?>', '<?php echo $data['nominal']; ?>', '<?php echo $data['seksi']; ?>', '<?php echo $data['id_keg']; ?>')">X</span>
										</td>
										<td align="center">
											<span style="cursor:pointer" title="Ubah" class="fa fa-pencil" onclick="ubah('<?php echo $data['id_target_detail']; ?>','<?php echo $data['id_target_detail']; ?>', '<?php echo $data['uraian']; ?>', '<?php echo $data['nominal']; ?>', '<?php echo $data['minggu']; ?>', '<?php echo $data['pl']; ?>', '<?php echo $data['ket']; ?>')"></span>
										</td>
										<?php
											}
										?>
									</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan='3' class='text-right'>Total</th>
										<th class='text-right' id="nomi"><span><?php echo number_format($nom, 0, ',', '.'); ?></span></th>
										<?php
											if(($cek[$f] == '0' && ($_POST['s1'] == $_POST['s2']) || $_POST['s1'] == 'DJ001')){
										?>
										<th colspan="2"></th>
										<?php
											}
										?>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="selesai()">Selesai</button> -->
				<button type="button" class="btn btn-secondary" onclick="selesai()">Selesai</button>
				<!-- <input type="button" id="add_simpan" class="finish btn btn-success sweet" value="Simpan"/> -->
			</div>
			<?php
			break;
		case 'simpanTarget':
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$uraian = preg_replace( "/(\r|\n)/", " ", $_POST['uraian']);
			$nominal = $_POST['nominal'];
			$minggu = $_POST['minggu'];
			$seksi = $_POST['seksi'];
			$keg = $_POST['keg'];
			$keg_pl = $_POST['keg_pl'];
			$ket = preg_replace( "/(\r|\n)/", " ",  $_POST['keterangan']);
			$pl = (isset($_POST['pl'])) ? $_POST['pl'] : '' ;

			if ($keg == '6') {
				$q = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg, pl, seksi_pl, ket, id_keg_pl) VALUES('$id_spj', '$bln', '$uraian', '$nominal', '$minggu', '$seksi', '$keg', '$pl', '$kseksi', '$ket', '$keg_pl')");
			} else {
				$q = mysql_query("INSERT INTO target_detail(id_spj, bulan, uraian, nominal, minggu, seksi, id_keg, pl, seksi_pl, ket, id_keg_pl) VALUES('$id_spj', '$bln', '$uraian', '$nominal', '$minggu', '$seksi', '$keg', '$pl', '$seksi', '$ket', '$keg_pl')");
			}
			
			if ($q) {
				$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
				$nom = $d['nominal'];
				$field = 'b'.$bln;
				mysql_query("UPDATE target_spj SET $field='$nom' WHERE id_spj='$id_spj'");

				$q2 = mysql_fetch_assoc(mysql_query("SELECT id_kegiatan FROM spj WHERE id_spj='$id_spj'"));
				$id_keg = $q2['id_kegiatan'];
				
				$q3 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as jumlah FROM target_spj WHERE id_kegiatan='$id_keg'"));
				mysql_query("UPDATE `target` SET `$field`='$q3[jumlah]' WHERE id_kegiatan='$id_keg'");
				$q4 = mysql_fetch_assoc(mysql_query("SELECT nama FROM pegawai WHERE kd_peg='$pl'"));
				$msg = array("res"=> 1, 'nom'=> $nom, 'nomi'=> number_format($nom, 0, ',', '.'), 'nomi2'=> number_format($nominal, 0, ',', '.'), 'pl'=> $q4['nama']);
			} else {
				$msg = array("res"=> 0);
			}
			
			echo json_encode($msg);
			break;
		case 'ubahTarget':
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$id_target = $_POST['id_target'];
			$uraian = $_POST['uraian'];
			$nominal = $_POST['nominal'];
			$minggu = $_POST['minggu'];
			$ket = $_POST['keterangan'];
			$pl = (isset($_POST['pl'])) ? $_POST['pl'] : '' ;

			$q = mysql_query("UPDATE target_detail SET uraian='$uraian', nominal='$nominal', minggu='$minggu', pl='$pl', ket='$ket' WHERE id_target_detail='$id_target'");
			if ($q) {
				$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
				$nom = $d['nominal'];
				$field = 'b'.$bln;
				mysql_query("UPDATE target_spj SET $field='$nom' WHERE id_spj='$id_spj'");

				$q2 = mysql_fetch_assoc(mysql_query("SELECT id_kegiatan FROM spj WHERE id_spj='$id_spj'"));
				$id_keg = $q2['id_kegiatan'];
				
				$q3 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as jumlah FROM target_spj WHERE id_kegiatan='$id_keg'"));
				mysql_query("UPDATE `target` SET `$field`='$q3[jumlah]' WHERE id_kegiatan='$id_keg'");
				$q4 = mysql_fetch_assoc(mysql_query("SELECT nama FROM pegawai WHERE kd_peg='$pl'"));
				$msg = array("res"=> 1, 'nom'=> $nom, 'nomi'=> number_format($nom, 0, ',', '.'), 'nomi2'=> number_format($nominal, 0, ',', '.'), 'pl'=> $q4['nama']);
			} else {
				$msg = array("res"=> 0);
			}
			
			echo json_encode($msg);
			break;
		case 'del':
			$id_spj = $_POST['id_spj'];
			$bln = $_POST['bln'];
			$uraian = $_POST['uraian'];
			$nominal = $_POST['nominal'];
			$seksi = $_POST['seksi'];
			$keg = $_POST['keg'];

			$q = mysql_query("DELETE FROM target_detail WHERE id_spj='$id_spj' AND  bulan='$bln' AND  uraian='$uraian' AND  nominal='$nominal' AND  seksi='$seksi' AND  id_keg='$keg'");
			if ($q) {
				$d = mysql_fetch_assoc(mysql_query("SELECT SUM(nominal) as nominal FROM target_detail WHERE id_spj='$id_spj' AND bulan='$bln'"));
				if ($d['nominal'] == '') {
					$nom = 0;
				} else {
					$nom = $d['nominal'];
				}
				$field = 'b'.$bln;
				mysql_query("UPDATE target_spj SET $field='$nom' WHERE id_spj='$id_spj'");

				$q2 = mysql_fetch_assoc(mysql_query("SELECT id_kegiatan FROM spj WHERE id_spj='$id_spj'"));
				$id_keg = $q2['id_kegiatan'];
				
				$q3 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as jumlah FROM target_spj WHERE id_kegiatan='$id_keg'"));
				mysql_query("UPDATE `target` SET `$field`='$q3[jumlah]' WHERE id_kegiatan='$id_keg'");
				$msg = array("res"=> 1, 'nom'=> $nom, 'nomi'=> number_format($nom, 0, ',', '.'));
			} else {
				$msg = array("res"=> 0);
			}
			
			echo json_encode($msg);
			break;
		
		case 'dak':
			?>
			<script>
				$(document).ready(function() {
					$('.select3').select2();
				});	
			</script>
			<div class="modal-header">
				<div class="row">
					<div class="col-md-10 col-xs-10">
						<h5 class="modal-title">Tambah Kegiatan DAK</h5>
					</div>
					<div class="col-md-2 col-xs-2">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<form class="form-horizontal" action="" method="post">
							<input type="hidden" name="seksi" value="<?php echo $_POST['seksi']; ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label">DAK</label>
								<div class="col-sm-8">
									<select name="dak" class="form-control" onchange="getKegiatan2(this, 'kegiatan')">
										<option selected disabled>Pilih</option>
										<option value="DJ014">DAK Reguler</option>
										<option value="DJ015">DAK Non Fisik</option>
										<option value="DJ016">DAK Penugasan</option>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">DPA</label>
								<div class="col-sm-8">
									<select name="id_kegiatan" class="form-control select3" style="width: 100%" id="keg3" onchange="getKegiatan2(this, 'rek')">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-8">
									<select name="id_spj" class="form-control select3" style="width: 100%" id="rek" onchange="getKegiatan2(this, 'det')">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Kegiatan</label>
								<div class="col-sm-8">
									<select name="id_spj_detail[]" multiple class="form-control select3" style="width: 100%" id="det">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
								</label>
								<div class="col-sm-8">
									<button type="submit" class="btn btn-primary" name="simpan_dak">Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
			break;
		
		default:
			# code...
			break;
	}

?>
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
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
$act	= isset($_GET['act']) ? $_GET['act'] : '';
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';
$a = mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r = mysql_fetch_array($a);
$kseksi2 	= $r['kseksi'];
$level_user = $r['level_user'];
$lock = 0;

?>
<div class="tab-content">			
	<div class="panel-body" >
		<div class="panel panel-default" style="">
			<div class="panel-body">
				<div class="panel-heading">
					<h3 class="panel-title">Daftar Data Rekening</h3>
				</div>
				<div class="row">
					<div class="col-md-12">
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
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" style="font-size:10px">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>		
										<th width="2%" style="text-align:center">NO</th>
										<th width="15%" style="text-align:center">URAIAN KEGIATAN</th>
										<th width="" style="text-align:center"></th>
										<?php
											for ($i=1; $i <= 12 ; $i++) { 
										?>
										<th style="text-align:center">BULAN KE-<?php echo $i; ?></th>
										<?php
											}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
										$no = 0;
										$a = array();
										$b = array();
										for ($i=1; $i <= 12 ; $i++) {
											$target[$i] = 0;
											$realisasi[$i] = 0;
											$target_all[$i] = 0;
											$realisasi_all[$i] = 0;
										}
										if ($seksi != '') {
											$q = mysql_query("SELECT * FROM kegiatan WHERE kseksi='$seksi'");
										} else {
											$q = mysql_query("SELECT * FROM kegiatan");
										}
										
										$pagu_all = 0;
										$total_real = 0;
										while ($h = mysql_fetch_assoc($q)) {
											$no = $no + 1;
											$h1 = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) as total_realisasi, SUM(pagu_anggaran) as pagu FROM spj WHERE id_kegiatan='$h[id_kegiatan]' GROUP BY id_kegiatan"));
											$pagu = $h1['pagu'];
											$pagu_all = $pagu_all+$h1['pagu'];
											// $total_real = $h1['total_realisasi'];
											for ($i=1; $i <= 12 ; $i++) { 
												$a[$i] = 0;
												$b[$i] = 0;
												// realisasi
												$h2 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs, spj WHERE bibs.id_spj = spj.id_spj AND bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND MONTH(bibs.tgl_transfer) = '$i' AND spj.id_kegiatan='$h[id_kegiatan]' GROUP BY spj.id_kegiatan"));
												if ($h2['nominal'] != '') {
													if ($i == 3) {
														$b[$i] = $b[$i]+$h2['nominal']+$h1['total_realisasi'];
													} else {
														$b[$i] = $b[$i]+$h2['nominal'];
													}
												} else {
													if ($i == 3) {
														$b[$i] = $b[$i]+0+$h1['total_realisasi'];
													} else {
														$b[$i] = $b[$i]+0;
													}
												}

												// target
												$tabel = "b".$i;
												$h3 = mysql_fetch_assoc(mysql_query("SELECT * FROM target WHERE id_kegiatan='$h[id_kegiatan]'"));
												if ($h3[$tabel] != '') {
													$a[$i] = $a[$i]+$h3[$tabel];
												} else {
													$a[$i] = $a[$i]+0;
												}
											}
									?>
									<tr>
										<td rowspan="5" style="text-align:center"><?php echo $no; ?></td>
										<td rowspan="5" style="text-align:left">
											<?php echo $h['nm_kegiatan']; ?>
										</td>
									</tr>
									<tr>
										<td style="background-color:#0099FF">ROK (Rp) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i == 1) {
													$target[$i] = $a[$i];
												} elseif($i > 1 && $i <= 12) {
													$target[$i] = $target[$i-1] + $a[$i];
												}

												if ($i == date('m')) {
													$bg = '#ffb366';
												} else {
													$bg = '#0099FF';
												}
												
												// $target_all[$i] = $target_all[$i] + $target[$i];
										?>
										<td style="text-align:center; background-color:<?php echo $bg; ?>">
											<?php echo number_format($target[$i], 0, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>Realisasi(Rp) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i == 1) {
													$realisasi[$i] = $b[$i];
												} elseif($i > 1 && $i <= date('m')) {
													$realisasi[$i] = $realisasi[$i-1] + $b[$i];
												} else {
													$realisasi[$i] = 0;
												}
												// $realisasi_all[$i] = $realisasi_all[$i] + $realisasi[$i];

												if ($i == date('m')) {
													$bg = 'background-color: #ffb366';
												}
												
												if ($realisasi[$i] < $target[$i]) {
										?>
										<td style="text-align:center; color:#FF0000;">
										<?php
												} else {
										?>
										<td style="text-align:center" >
											<?php 
												}
												
												echo number_format($realisasi[$i], 0, ',', '.');
											?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>ROK (%) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												$p1[$i] = ($target[$i]/$pagu)*100;
										?>
										<td style="text-align:center">
											<?php echo number_format($p1[$i], 2, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>Realisasi (%) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i <= date('m')) {
													$p2[$i] = ($realisasi[$i]/$pagu)*100;
												} else {
													$p2[$i] = 0;
												}
												if ($p2[$i] < $p1[$i]) {
										?>
										<td style="text-align:center; color:#FF0000">
										<?php
												} else {
										?>
										<td style="text-align:center" >
											<?php 
												}
												echo number_format($p2[$i], 2, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
									
									<?php
										}

										$q4 = mysql_fetch_assoc(mysql_query("SELECT SUM(total_realisasi) as total_realisasi, SUM(pagu_anggaran) as pagu FROM spj"));
										for ($i=1; $i <=12 ; $i++) { 
											$field = 'b'.$i;
											// target
											$q2 = mysql_fetch_assoc(mysql_query("SELECT SUM(`$field`) as total FROM target"));
											// realisasi
											$q3 = mysql_fetch_assoc(mysql_query("SELECT Sum(bibs.nominal) AS nominal FROM bibs WHERE bibs.tgl_transfer != '0000-00-00' AND bibs.tolak = '0' AND bibs.tgl_transfer > '2019-03-31' AND MONTH(bibs.tgl_transfer) = '$i'"));

											$target_all[$i] = $target_all[$i-1]+$q2['total'];
											$realisasi_all[$i] = $realisasi_all[$i-1]+$q3['nominal'];
											if ($i == 3) {
												$realisasi_all[$i] = $realisasi_all[$i-1]+$q3['nominal']+$q4['total_realisasi'];
											} elseif ($i > date('m')) {
												$realisasi_all[$i] = 0;
											} else {
												$realisasi_all[$i] = $realisasi_all[$i-1]+$q3['nominal'];
											}
											
										}
									?>
									
									<tr>
										<td rowspan="5" style="text-align:center"></td>
										<td rowspan="5" style="text-align:left">
											Dinas Kesehatan Kabupaten Jepara
										</td>
									</tr>
									<tr>
										<td style="background-color:#0099FF">ROK (Rp) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i == date('m')) {
													$bg = '#ffb366';
												} else {
													$bg = '#0099FF';
												}
										?>
										<td style="text-align:center; background-color:<?php echo $bg; ?>">
											<?php echo number_format($target_all[$i], 0, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>Realisasi(Rp) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i == date('m')) {
													$bg = 'background-color: #ffb366';
												}
												
												if ($realisasi_all[$i] < $target_all[$i]) {
										?>
										<td style="text-align:center; color:#FF0000;">
										<?php
												} else {
										?>
										<td style="text-align:center" >
											<?php 
												}
												
												echo number_format($realisasi_all[$i], 0, ',', '.');
											?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>ROK (%) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												$p1[$i] = ($target_all[$i]/$q4['pagu'])*100;
										?>
										<td style="text-align:center">
											<?php echo number_format($p1[$i], 2, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
									<tr>
										<td>Realisasi (%) </td>
										<?php
											for ($i=1; $i <= 12 ; $i++) {
												if ($i <= date('m')) {
													$p2[$i] = ($realisasi_all[$i]/$q4['pagu'])*100;
												} else {
													$p2[$i] = 0;
												}
												if ($p2[$i] < $p1[$i]) {
										?>
										<td style="text-align:center; color:#FF0000">
										<?php
												} else {
										?>
										<td style="text-align:center" >
											<?php 
												}
												echo number_format($p2[$i], 2, ',', '.'); ?>
										</td>
										<?php
											}
										?>
									</tr>
								</tbody>
								<tfoot>
									<tr>		
										<th width="2%" style="text-align:center;" bgcolor="#e6e6e6">NO</th>
										<th width="15%" style="text-align:center" bgcolor="#e6e6e6">URAIAN KEGIATAN</th>
										<th width="" style="text-align:center" bgcolor="#e6e6e6"></th>
										<?php
											for ($i=1; $i <= 12 ; $i++) { 
										?>
										<th style="text-align:center" bgcolor="#e6e6e6">BULAN KE-<?php echo $i; ?></th>
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
			$("#ubah").hide();
			$("#batal").hide();
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
	var pl = $('#pl').val();

	var data = {'page':page,'id_spj': id_spj, 'bln': bln, 'uraian':uraian, 'nominal':nominal, 'minggu': minggu, 'seksi': seksi, 'keg': keg, 'pl': pl};
	$.ajax({
		type:'POST',
		url:'getTarget.php',
		data:data,
		success:function(data){
			var json=JSON.parse(data);
			if (json.res == 1) {
				var html = '<tr id="add-'+i+'">';
					html += '<td>'+uraian+'</td>';
					html += '<td>'+json.pl+'</td>';
					html += '<td>Minggu ke - '+minggu+'</td>';
					html += '<td align="right">'+json.nomi2+'</td>';
					html += '<td align="center"><span style="cursor:pointer" title="Hapus" onclick="del(\'add-'+i+'\', \''+id_spj+'\', \''+bln+'\', \''+uraian+'\', \''+nominal+'\')">X</span></td>';
					html += '<td align="center"><span style="cursor:pointer" class="fa fa-pencil" title="Ubah" onclick="ubah(\'add-'+i+'\', \'add-'+i+'\', \''+uraian+'\', \''+nominal+'\', \''+minggu+'\', \''+pl+'\')"></span></td>';
					html += '</tr>';
				$('#tbody').append(html);
				$('#uraian').val("");
				$('#nominal').val("");
				$('#minggu').val("1").change();
				$('#pl').val("").change();
				$('#id_target').val("");
				$('#tr').val("");
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

function ubah(id_target, id_tr, uraian, nominal, minggu, pl) {
	$("#simpan").hide();
	$("#ubah").show();
	$("#batal").show();
	$("#uraian").val(uraian);
	$("#nominal").val(nominal);
	$("#minggu").val(minggu).change();
	$("#pl").val(pl).change();
	$("#id_target").val(id_target);
	$("#tr").val(id_tr);
}

function bat() {
	$("#simpan").show();
	$("#ubah").hide();
	$("#batal").hide();
	$("#id_target").val("");
}

function ubahTarget(id_spj, bln) {
	var uraian = $('#uraian').val();
	var nominal = $('#nominal').val();
	var minggu = $('#minggu').val();
	var keg = $('#keg').val();
	var seksi = $('#seksi').val();
	var pl = $('#pl').val();
	var tr = $('#tr').val();
	var id_target = $("#id_target").val();

	var data = {'page':'ubahTarget','id_spj': id_spj, 'bln': bln, 'uraian':uraian, 'nominal':nominal, 'minggu': minggu, 'seksi': seksi, 'keg': keg, 'pl': pl, 'id_target': id_target};
	$.ajax({
		type:'POST',
		url:'getTarget.php',
		data:data,
		success:function(data){
			var json=JSON.parse(data);
			if (json.res == 1) {
				var html = '<tr id="add-'+i+'">';
					html += '<td>'+uraian+'</td>';
					html += '<td>'+json.pl+'</td>';
					html += '<td>Minggu ke - '+minggu+'</td>';
					html += '<td align="right">'+json.nomi2+'</td>';
					html += '<td align="center"><span style="cursor:pointer" title="Hapus" onclick="del(\'add-'+i+'\', \''+id_spj+'\', \''+bln+'\', \''+uraian+'\', \''+nominal+'\', \''+seksi+'\', \''+keg+'\')">X</span></td>';
					html += '<td align="center"><span style="cursor:pointer" class="fa fa-pencil" title="Ubah" onclick="ubah(\'add-'+i+'\', \'add-'+i+'\', \''+uraian+'\', \''+nominal+'\', \''+minggu+'\', \''+pl+'\')"></span></td>';
					html += '</tr>';
				$("#"+tr).remove();
				$('#tbody').append(html);
				$('#uraian').val("");
				$('#nominal').val("");
				$('#minggu').val("1").change();
				$('#pl').val("").change();
				$('#id_target').val("");
				$('#tr').val("");
				bat();
				$("#"+id_spj+bln).val(json.nom);
				$('#nomi span').text(json.nomi);
				i++;
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
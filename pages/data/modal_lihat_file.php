<!--
Author : Aguzrybudy
Created : Selasa, 19-April-2016
Title : Crud Menggunakan Modal Bootsrap
-->
<style>
.modal-content {
width:125%;
}
</style>
<?php
    $host="localhost";
    $user="root";
    $pass="";
    $database="realisasi_kegiatan";
    $koneksi=new mysqli($host,$user,$pass,$database);
    if (mysqli_connect_errno()) {
      trigger_error('Koneksi ke database gagal: '  . mysqli_connect_error(), E_USER_ERROR); 
    }
?>

<?php
    $id_spj = isset($_GET['id']) ? $_GET['id'] : '';
	$modal=mysqli_query($koneksi,"SELECT * FROM spj where id_spj='$id_spj'");
	$r=mysqli_fetch_array($modal);
	$id_kegiatan	= $r['id_kegiatan'];
	$kode_rekening	= $r['kode_rekening'];
	$uraian_kegiatan= $r['uraian_kegiatan'];
	$file	= $r['file'];
	$tgl_upload = substr($file,25,10);
	$blntahun_kegiatan = substr($file,36,7);
	
	
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun

	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}



?>

<div class="modal-dialog">
    <div class="modal-content">

    	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"><center>FILE KEGIATAN</center></h4>
        </div>

        <div class="modal-body">
        <div class="table-responsive">
				<div class="widget-content nopadding">
					<form id="form-wizard" class="form-horizontal" enctype="multipart/form-data" method="post" action="?cat=data&page=proses_edit&act=del">
					   <div id="form-wizard-1" class="step">
						 <div class="form-group">
							  <label  class="col-sm-3 control-label"><b>Tanggal Upload:</b></label>
							  <div class="col-sm-9">
							  	  <input type="hidden" class="form-control" name="id_spj" value="<?php echo $id_spj; ?>" size="" />
								  <?php echo tgl_indo($tgl_upload);  ?>
							  </div>
						 </div>
						 <div class="form-group">
							  <embed src="<?php echo $file ?>" type="application/pdf" width="100%" height="500"/></embed>
						 </div>
						 <div class="form-actions">
							<input type="submit" class="btn btn-success" name="delete" value="Delete" />
							<button type="reset" class="btn btn-danger"  data-dismiss="modal" aria-hidden="true">
							Cancel
						 </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
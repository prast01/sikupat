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

	$modal=mysqli_query($koneksi,"select * from kegiatan where id_kegiatan='$id_kegiatan'");
	$r2=mysqli_fetch_array($modal);
	$nm_kegiatan	= $r2['nm_kegiatan'];
	
	$thn = date("Y");
	$bln = date("m");
	$tgl = date('Y-m-d');
?>

<div class="modal-dialog">
    <div class="modal-content">

    	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"><center>UNGGAH FILE KEGIATAN</center></h4>
        </div>

        <div class="modal-body">
        <div class="table-responsive">
				<div class="widget-content nopadding">
					<form id="form-wizard" class="form-horizontal" enctype="multipart/form-data" method="post" action="?cat=data&page=proses_edit&act=tambah">
					   <div id="form-wizard-1" class="step">
						 <div class="form-group">
							  <label  class="col-sm-3 control-label"><b>Kode Rekening :</b></label>
							  <div class="col-sm-9">
							  	  <input type="hidden" class="form-control" name="id_spj" value="<?php echo $id_spj; ?>" size="" />
								  <?php echo $kode_rekening; ?>
							  </div>
						 </div>
						 <div class="form-group">
							  <label  class="col-sm-3 control-label"><b>Uraian Kegiatan :</b></label>
							  <div class="col-sm-9">
								  <?php echo $uraian_kegiatan; ?>
							  </div>
						 </div>
						 <div class="form-group">
							  <label  class="col-sm-3 control-label"><b>Nama Kegiatan :</b></label>
							  <div class="col-sm-9">
								  <?php echo $nm_kegiatan; ?>
							  </div>
						 </div>
						 <div class="form-group" style="display:none">
							  <label  class="col-sm-3 control-label"><b>Bulan :</b></label>
							  <div class="col-sm-2">
								<select name="bulan" id="bulan" class="form-control" required>
									  <option value="<?php echo $bln; ?>"><?php echo $bln; ?></option>
								</select>
							  </div>
							  <div class="col-sm-2">
							  	<select name="tahun" id="tahun" class="form-control">
							  		  <option value="<?php echo $thn; ?>"><?php echo $thn; ?></option>
							 	</select>
							  </div>
						 </div>
						 <div class="form-group">
							  <label  class="col-sm-3 control-label"><b>File (.pdf) :</b></label>
							  <div class="col-sm-4">
								  <input type="file" class="form-control" name="file" id="file" required>
							  </div>
						 </div>
						 <div class="form-actions">
							<input type="submit" class="btn btn-success" name="simpan" value="<?php if($file!=""){ ?>Update<?php }else{ ?>Simpan<?php } ?>" />
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
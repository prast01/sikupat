<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
?>
<div class="form-group" id="<?php echo $id ?>">
	<label class="col-sm-2 control-label"></label>
	<div class="col-sm-6">
		<select name="pegawai2[]" class="form-control" onchange="pihakLuar(this, <?php echo $id; ?>)">
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
		<input type="text" class="form-control" name="rupiah[]" placeholder="Nominal">
		<input type="text" name="ket[]" id="ket<?php echo $id; ?>" style="display:none; width:128px; margin-top:10px" placeholder="Keterangan">
	</div>
	<div class="col-sm-2">
		<a href="javascript:delt('<?php echo $id ?>')" class="btn btn-danger btn-sm">Del</a>
	</div>
</div>
<div id="space<?php echo $id ?>"></div>
<script>
	$(document).ready(function() {
			$('.select3').select2();
	});	
</script>
<?php
	}
?>
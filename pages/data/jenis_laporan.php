<?php
include ("pages/koneksi.php");
	
?>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
  //apabila terjadi event onchange terhadap object <select id=fakultas>
  $("#ckterimalaporan").change(function(){
	var ckterimalaporan = $("#ckterimalaporan").val();
	$.ajax({
		url: "ambillaporan.php",
		data: "ckterimalaporan="+ckterimalaporan,
		cache: false,
		success: function(msg){
			//jika data sukses diambil dari server kita tampilkan
			//di <select id=progdi>
			$("#cklaporan").html(msg);
		}
	});
  });
 });
</script>


<table width="100%" class="" id="">
	<tr>
		<th>Seksi</th>
		<th>
			<select name="ckterimalaporan" id="ckterimalaporan" class="">
			  <option value="">--Pilih Seksi--</option>
			  <?php 					
			  $query=mysql_query("select * from user where level_user='2' order by kseksi");
			  while($row=mysql_fetch_array($query)){
			  ?>
			  <option value="<?php  echo $row['kseksi']; ?>"><?php  echo $row['nm_seksi']; ?></option>
			  <?php 
			  }
			?>
			</select>											
		</th>	
		<th>Laporan</th>
		<th>
			<select name="cklaporan" id="cklaporan" class="form-control">
			 <option value=""></option>
			 <option value="">Pilih Seksi dulu</option>
			</select>
		</th>
	</tr>
</table>
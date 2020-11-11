<style>
	body{
		background:#FFFFFF;
	}

.table {
    width: 100%;
}
select.form-control {
    width: 200px;
    background-color: #fff;
    border: 1px solid #ccc;
}
</style>
<html>
<head>
    <title>Realisasi Kegiatan</title>
	<script src="pages/data/grafik/jquery.min.js"></script>
	<script src="pages/data/grafik/highcharts.js"></script>
	<script src="pages/data/grafik/exporting.js"></script>
	
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
	
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=fakultas>
	  $("#ckseksi").change(function(){
		var ckseksi = $("#ckseksi").val();
		$.ajax({
			url: "pages/data/ambil_kegiatan.php",
			data: "ckseksi="+ckseksi,
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				//di <select id=progdi>
				$("#ckeg").html(msg);
			}
		});
	  });
	 });
	
	</script>
	
	
</head>
<body>
	<div class="panel-body" >	
		<div class="widget-box">
			<div class="widget-content nopadding">
                <div id="form-wizard-1" class="step">
                    <div class="">
						<form id="form-wizard" class="form-horizontal" method="post" onSubmit="">
							<table class="table table-bordered ">
							</table>
						</form>
						
<?php
	  
  $update = mysql_query("update kegiatan set kirim='0' ")or die(mysql_error());
  if($update){
    echo "<script>window.location='?cat=data&page=grafik1a'</script>";
  }

?>


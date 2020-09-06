<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
	<link rel="icon" type="image/jpg" href="pages/images/logo.png" >
	<head>
		<title>Realisasi Kegiatan</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewreport" content="width=device-width, initial-scale=1">		
		<link rel="stylesheet" href="template/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="template/css/bootstrap-theme.min.css"/>
		<link rel="stylesheet" href="template/css/dataTables.bootstrap.css"/>
		<link rel="stylesheet" href="template/css/style1.css"/>
		
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
	
		<!-- page specific plugin styles -->
	
		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
	
		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
		<script src="template/js/jquery.min.js"></script>
		
	</head>
	<body>
		<!--untuk navigasi-->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<!--untuk layar responsive -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			<div>
		</nav>
		
			<?php
			$v_cat = (isset($_REQUEST['cat'])&& $_REQUEST['cat'] !=NULL)?$_REQUEST['cat']:'';
			$v_page = (isset($_REQUEST['page'])&& $_REQUEST['page'] !=NULL)?$_REQUEST['page']:'';
			if(file_exists("pages/".$v_cat."/".$v_page.".php"))
				{
						include("pages/".$v_cat."/".$v_page.".php");
				}else{
						include("pages/homepage.php");
				}
			 

			?>
		<div style="width:100%;height:50px"></div>
		<div class="navbar navbar-default navbar-fixed-bottom">
			<div class="container">
				<p class=""><center><font size="2" color="#FFFFFF">&#169; Copyright 2018 by DKK - Jepara</font></center>    
			</div>
		</div>
		<link rel="stylesheet" href="css-1/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="css-1/jquery.ui.timepicker.css?v=0.3.3" type="text/css" />
		<script type="text/javascript" src="js-1/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="js-1/jquery-ui.js"></script> 
		<script type="text/javascript" src="js-1/jquery.ui.timepicker.js?v=0.3.3"></script>
		<script type="text/javascript">
				$(document).ready(function() {
					$('#jam1').timepicker({
						showPeriodLabels: false
					});
				  });
		</script>
		<script type="text/javascript">
				$(document).ready(function() {
					$('#jam2').timepicker({
						showPeriodLabels: false
					});
				  });
		</script>
		
		<script src="pages/data/grafik/exporting.js"></script>
		<script src="template/js/bootstrap.min.js"></script>
		<script src="template/js/typeahead.min.js"></script>
		<script src="template/js/jquery.dataTables.min.js"></script>
		<script src="template/js/dataTables.bootstrap.js"></script>
		
		<script>			
			$(document).ready(function() {
				$('#tabel_spesial').dataTable();
			} );			
		 </script>
	</body>
</html>

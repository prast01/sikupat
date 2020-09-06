<?php 
session_start();
if(!isset($_SESSION['username']))
{
	echo "<script>window.location='index.php'</script>";
}
include 'koneksi.php';
$username	= isset($_SESSION['username']) ? $_SESSION['username'] : '';

$a 		= mysql_query("SELECT * FROM user where username='$username'")or die(mysql_error());
$r 		= mysql_fetch_array($a);
$username 	= $r['username'];
$level_user 	= $r['level_user'];
$kseksi 	= $r['kseksi'];
$password 	= $r['password'];

?>

<!DOCTYPE html>
<html>
	<head>
    	<title>Si Kupat - <?php echo '2020'; ?></title>
	<link rel="icon" type="image/jpg" href="pages/images/logo.png" >
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
		
		<!-- <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<link id="bs-css" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet"> -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<!-- <link id="bsdp-css" href="css/bootstrap-datepicker3.min.css" rel="stylesheet"> -->
		<!-- <script src="js/bootstrap-datepicker.min.js"></script> -->
		<script type="text/javascript" src="assets/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript">
			tinyMCE.init({
				mode : "exact",
				elements : "elm2",
				theme : "advanced",
				skin : "o2k7",
				skin_variant : "silver",
				plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
				
				theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",
				
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}
			});
		</script>
		
		<style>
			.container2 {
				position: relative;
				width: 100%;
				max-width: 400px;
			}

			.container2 img {
				width: 100%;
				height: auto;
			}

			.container2 .btn2 {
				position: absolute;
				top: 85%;
				left: 85%;
				transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				background-color: #555;
				color: white;
				font-size: 16px;
				padding: 12px 24px;
				border: none;
				cursor: pointer;
				border-radius: 5px;
				text-align: center;
			}

			.container2 .btn2:hover {
				background-color: black;
			}
		</style>
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
				<!--untuk navigasi -->
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="dropdown" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Sikupat<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<?php
									// for($i = 2018; $i < date('Y'); $i++){
								?>
								<!-- <li><a class="" target="_blank" href="http://dkk.sikdkkjepara.net/sikupat_<?php echo $i; ?>">Sikupat <?php echo $i; ?></a></li> -->
								<?php
									// }
								?>
								<li><a class="" target="_blank" href="http://dkk.sikdkkjepara.net/sikupat_2018">Sikupat 2018</a></li>
								<li><a class="" target="_blank" href="http://dkk.sikdkkjepara.net/sikupat">Sikupat 2019</a></li>
								<li><a class="" target="_blank" href="http://sikupat2020.sikdkkjepara.net">Sikupat 2020</a></li>
							</ul>
						</li>
						<li class="dropdown" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Input<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<!-- <li><a class="" href="?cat=data&page=kegiatan&act=tampil">Kegiatan</a></li> -->
								<?php if($_SESSION['kseksi'] =='DJ001'){ ?>
								<li><a class="" href="?cat=data&page=berkas-spj&act=tampil">Berkas Pendukung SPJ</a></li>
								<li><a class="" href="?cat=data&page=spj-transfer&act=tampil">SPJ Sudah Transfer</a></li>
								<?php }?>
								<li><a class="" href="?cat=data&page=rekap">Rekap Penerimaan SPJ</a></li>
								<li><a class="" href="?cat=data&page=target">ROK</a></li>
								<li><a class="" href="?cat=data&page=registrasi-spj&act=tampil">Pendaftaran SPJ</a></li>
								<li><a class="" href="?cat=data&page=spj-bidang&act=tampil">SPJ</a></li>
								<li><a class="" href="?cat=data&page=spj1&act=edit">SPJ Detail</a></li>
								<li><a class="" href="?cat=data&page=spj1&act=kegiatan">SPJ Kegiatan</a></li>
								<li><a class="" href="?cat=data&page=realisasi-kegiatan&act=edit">Realisasi Kegiatan</a></li>
							</ul>
						</li>
						<li class="dropdown" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Grafik<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a class="" href="?cat=data&page=grafik">Realisasi Penyerapan Anggaran</a></li>
								<li><a class="" href="?cat=data&page=grafik_kegiatan">Realisasi Penyerapan Anggaran Per-bulan</a></li>
								<li><a class="" href="?cat=data&page=grafik1">Detail Realisasi Per-Kegiatan</a></li>
							</ul>
						</li>
						
						<li class="dropdown" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<?php echo $username; ?>
							<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<?php if($_SESSION['kseksi'] =='DJ001'){ ?>
								<li>
									<a href="?cat=data&page=notif"><i class="ace-icon fa fa-lock"></i>Setting Kunci Sikupat</a>
								</li>
								<?php }?>
								<li>
									<a href="?cat=data&page=ubah-password"><i class="ace-icon fa fa-user"></i>Ubah Password Akun</a>
								</li>
								<li>
									<a href="?cat=data&page=logout"><i class="icon_key_alt"></i>Logout</a>
								</li>
							</ul>
						</li>
						
					</ul>
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
				<p class=""><center><font size="2" color="#FFFFFF">&#169; Copyright 2019 by DKK - Jepara</font></center>    
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
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<script>			
			$(document).ready(function() {
				$('#tabel_spesial').dataTable();
			} );
		$(document).ready(function() {
				$('.select2').select2();
		});	
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover();   
		});		
		 </script>
		 
	</body>
</html>

<!--
Author : Aguzrybudy
Created : Selasa, 19-April-2016
Title : Crud Menggunakan Modal Bootsrap
-->

<?php
    $host="localhost";
    $user="root";
    $pass="";
    $database="realisasi_kegiatan";
    $koneksi=new mysqli($host,$user,$pass,$database);
    if (mysqli_connect_errno()) {
      trigger_error('Koneksi ke database gagal: '  . mysqli_connect_error(), E_USER_ERROR); 
    }
	
$act = isset($_GET['act']) ? $_GET['act'] : '';

if($act=="del"){
	$delete = isset($_POST['delete']) ? $_POST['delete'] : '';
	if($delete){
		$id_spj = isset($_POST['id_spj']) ? $_POST['id_spj'] : '';
		
		$cari=mysql_query("select * from spj where id_spj='$id_spj'" );
		$dt=mysql_fetch_array($cari);
		$file=$dt['file'];
		$tmpfile = "$file";
		unlink("$tmpfile");
		$in = mysql_query("UPDATE spj SET file='' WHERE id_spj='$id_spj'") or die (mysql_error());
			if ($in){
			echo "<script>alert('File Berhasil Dihapus');window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";
			} else {
			echo "<script>alert('File Gagal Dihapus'); window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";
			}
	}
	
}elseif($act=="tambah"){
error_reporting(0);
$simpan = isset($_POST['simpan']) ? $_POST['simpan'] : '';

if($simpan){
	$id_spj = isset($_POST['id_spj']) ? $_POST['id_spj'] : '';
	$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
	$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';

	$allowed_ext = array('pdf');
	$file_name   = $_FILES['file']['name'];
	$file_ext    = strtolower(end(explode('.', $file_name)));
	$file_size   = $_FILES['file']['size'];
	$file_tmp    = $_FILES['file']['tmp_name'];
	$tgl = date('Y-m-d');
	
	if(in_array($file_ext, $allowed_ext) === true){
		if($file_size < 3211044070){
			$lokasi = 'pages/data/file_kegiatan/'.$tgl.'_'.$bulan.'-'.$tahun.'_'.$id_spj.'.'.$file_ext;
			move_uploaded_file($file_tmp, $lokasi);
			
 			$cari=mysql_query("select * from spj where id_spj='$id_spj'" );
			$dt=mysql_fetch_array($cari);
			$file=$dt['file'];
			$tmpfile = "$file";
			unlink("$tmpfile");
			$in = mysql_query("UPDATE spj SET file='$lokasi' WHERE id_spj='$id_spj'") or die (mysql_error());
				if ($in){
				echo "<script>window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";
				} else {
	    		echo "<script>alert('Gagal Upload File'); window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";
				}
		} else {
	    echo "<script>alert('Gagal Upload File'); window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";
		}
	} else {
    echo "<script>alert('Ekstensi file tidak diijinkan'); window.location='?cat=data&page=realisasi-kegiatan&act=edit'</script>";	
	}
}

}
?>
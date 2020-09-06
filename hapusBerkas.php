<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$src = "pages/data/file_kegiatan/".$id;

		$hapus = unlink($src);
		if ($hapus) {
			mysql_query("DELETE FROM file_berkas WHERE berkas='$id'");
			$data = array("res"=>1);
		} else {
			$data = array("res"=>0);
		}
		
		
		echo json_encode($data);
	}
?>
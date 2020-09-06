<?php
	include "koneksi.php";

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$q = mysql_fetch_assoc(mysql_query("SELECT * FROM target_detail WHERE id_target_detail='$id'"));

		$msg = array(
			'nominal' => $q['nominal'],
			'uraian' => $q['uraian']
		);

		echo json_encode($msg);
	}
?>
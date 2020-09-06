<?php
	if (isset($_GET['id'])) {
		include "koneksi.php";
		$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Lihat Foto</title>
</head>
<body>
	<table border="1" width="100%">
		<tr>
			<?php
				$q = mysql_query("SELECT * FROM file_berkas WHERE kd_transaksi='$id'");
				while ($d = mysql_fetch_assoc($q)) {
					$src = "pages/data/file_kegiatan/".$d['berkas'];
			?>
			<td align="center" width="25%">
				<img src="<?php echo $src; ?>" width="250px">
			</td>
			<?php
				}
			?>
		</tr>
	</table>
</body>
</html>
<?php
	}
?>
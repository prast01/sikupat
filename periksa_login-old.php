<?php
error_reporting(0);
include "koneksi.php";

$username = mysql_real_escape_string ($_POST["username"]);
$password = mysql_real_escape_string ($_POST["password"]);

// pastikan username dan password adalah berupa huruf atau angka.

$login=mysql_query("SELECT * FROM user WHERE username='$username' AND password='$password'") or die (mysql_error());
$ketemu=mysql_num_rows($login);
$r=mysql_fetch_array($login);

// Apabila username dan password ditemukan
if ($ketemu > 0){
  
  include 'getSpj2.php';
  session_start();
  $_SESSION['username'] = $r['username'];
  $_SESSION['password'] = $r['password'];
  $_SESSION['kseksi'] = $r['kseksi'];
  
  	echo "<script> window.location='home.php?cat=data&page=spj-bidang&act=tampil'</script>";
  }
else{
  ?>
	<script> alert('Login Gagal !!!'); 	
        document.location="index.php"; 	      
      </script>
	<?php
}
?>
<?php
error_reporting(0);
include "koneksi.php";

$username = mysql_real_escape_string ($_POST["username"]);
$password = mysql_real_escape_string ($_POST["password"]);

// pastikan username dan password adalah berupa huruf atau angka.

$login=mysql_query("SELECT * FROM user WHERE username='$username' AND password='$password'") or die (mysql_error());
$ketemu=mysql_num_rows($login);
$r=mysql_fetch_array($login);

function cek($seksi){
  $q = mysql_fetch_assoc(mysql_query("SELECT * FROM notif WHERE status='1'"));
  $id = $q['id_notif'];
  $q2 = mysql_query("SELECT * FROM notif_detail WHERE id_notif='$id' AND kseksi='$seksi'");
  $h = 1;
  if (mysql_num_rows($q2) > 0) {
    $h = 0;
  } else {
    $h = 1;
  }

  return $h;
}

// Apabila username dan password ditemukan
if ($ketemu > 0){

  $cek = cek($r['kseksi']);

  if($cek){
    // include 'getSpj2.php';
    session_start();
    $_SESSION['username'] = $r['username'];
    $_SESSION['password'] = $r['password'];
    $_SESSION['kseksi'] = $r['kseksi'];
    
    echo "<script> window.location='home.php?cat=data&page=spj-bidang&act=tampil'</script>";
  } else {
    $q = mysql_fetch_assoc(mysql_query("SELECT * FROM notif WHERE status='1'"));
    $notif = $q['notif'];
    echo "<script>alert('$notif'); window.location='index.php'; </script>";
  }
  
} else{
  ?>
	<script> alert('Login Gagal !!!'); 	
        document.location="index.php"; 	      
      </script>
	<?php
}
?>
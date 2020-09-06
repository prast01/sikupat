<?php
session_start();
include "koneksi.php";
                  
$username = isset($_POST['username']) ? $_POST['username'] : ''; 
$password = isset($_POST['password']) ? $_POST['password'] : '';
$user = mysql_query("select * from password where user='$username' and DES_DECRYPT(pasword)='$password'")or die(mysql_error());

//10 query untuk mencocokan, apakah data ada di dalam database atau tidak
$tot= mysql_num_rows($user);
$r= mysql_fetch_array($user);

if ($tot > 0) {//jika data ada maka akan diproses
 $_SESSION['user'] = $r['user'];
 $_SESSION['pasword'] = $r['pasword'];
 
	echo "<script>window.location='../home.php?cat=data&page=afri-agung'</script>";	
} else {	
	
     ?> <script> alert('Login Gagal !!!'); 	
         document.location="index5.php"; 	      
       </script> <?php
}

                  
?>
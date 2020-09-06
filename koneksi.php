<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "sikupat_2020";

mysql_connect($host, $user, $password) or die("Gagal koneksi...".mysql_error());
mysql_select_db($db) or die("Database tidak ditemukan...".mysql_error());
?>
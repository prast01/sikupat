<?php
    $host	 = "localhost";
    $user	 = "root";
    $pass	 = "";
    $dabname     = "qorib";

    $conn = mysql_connect( $host, $user, $pass) or die('Could not connect to mysql server.' );
    mysql_select_db($dabname, $conn) or die('Could not select database.');
	
?>
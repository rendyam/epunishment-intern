<?php

    $host="192.168.0.27";
    $dbuser="pkl";
    $dbpass="Pkl@9999";
    $dbname="db_efile";
    $dbport="3306";

    $host3306="192.168.0.27";
    $dbuser3306="pkl";
    $dbpass3306="Pkl@9999";
    $dbname3306="db_efile";
    $dbport3306="3306";
    
    $live_server="http://localhost:7777";
	
	$db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
?>

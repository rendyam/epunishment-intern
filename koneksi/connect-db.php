<?php

    $host="127.0.0.1";
    $dbuser="root";
    $dbpass="Rootkb5";
    $dbname="db_efile";
    $dbport="3308";
    
    $hostPocis="192.168.0.10";
    $dbuserPocis="pocis_supoprt";
    $dbpassPocis="@#PoC1sSuppOrt#!";
    $dbnamePocis="kbs_go_live";
    
    $host3306="127.0.0.1";
    $dbuser3306="root";
    $dbpass3306="Rootkb5";
    $dbname3306="db_efile";
    $dbport3306="3306";
    
    $hostVPACS="192.168.5.190";
    $dbuserVPACS="root";
    $dbpassVPACS="r";
    $dbnameVPACS="vpacs";
    
    $live_server="http://localhost:7777";
	
	$db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
?>

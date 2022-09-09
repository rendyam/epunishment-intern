<?php
    session_start();
    
    unset($_SESSION['sessunameetilang']);
    unset($_SESSION['sessnameetilang']);
    unset($_SESSION['sessidetilang']);
    
//    header('Location: '.$live_server.'/efile/logout');
    
    header('Location: index.php');
?>

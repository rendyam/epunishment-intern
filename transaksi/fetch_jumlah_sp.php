<?php
    session_start();
    include "../koneksi/connect-db.php";

    $modeDebug = 1;

    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        if($_POST['id_klasifikasi'] == 3){
            $query = "SELECT jumlah_sp_aktif, masa_berlaku FROM pelanggaran_rpt WHERE id_pelanggar = ".$_POST['id_pelanggar']." AND jenis_trx = ".$_POST['id_klasifikasi']." ORDER BY tgl_trx DESC LIMIT 1";
        } else {
            $query = "SELECT jumlah_sp_aktif, masa_berlaku FROM pelanggaran_rpt WHERE no_polisi like '%".$_POST['id_no_pol_pelanggar']."%' AND jenis_trx != 3 ORDER BY tgl_trx DESC LIMIT 1";
        }

        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                echo json_encode($row);
            }
        } else {
            $row = array();
            $row[0] = 0;
            echo json_encode($row);
        }
        
        $db = null;
    } catch (Exception $e) {
        if($modeDebug==0){
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        }else{
            $strMessage = $e->getMessage();
        }
    }

?>
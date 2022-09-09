<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $output = array();
        $statement = $db->prepare("
            SELECT * FROM gen_kendaraan_tab
            WHERE id_kendaraan = '". $_POST['id'] ."'
            LIMIT 1
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row){
            $output['id_kendaraan'] = $row['id_kendaraan'];
            $output['no_polisi'] = $row['no_polisi'];
            $output['no_stnk'] = $row['no_stnk'];
        }
        echo json_encode($output);
    }
?>
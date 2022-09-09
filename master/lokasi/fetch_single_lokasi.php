<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $output = array();
        $statement = $db->prepare("
            SELECT * FROM gen_lokasi_tab
            WHERE id_lokasi = '". $_POST['id'] ."'
            LIMIT 1
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row){
            $output['id_lokasi'] = $row['id_lokasi'];
            $output['nama_lokasi'] = $row['lokasi'];
        }
        echo json_encode($output);
    }
?>
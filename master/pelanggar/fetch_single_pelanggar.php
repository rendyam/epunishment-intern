<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $output = array();
        $statement = $db->prepare("
            SELECT * FROM gen_pelanggar_tab
            WHERE id_pelanggar = '". $_POST['id'] ."'
            LIMIT 1
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row){
            $output['id_pelanggar'] = $row['id_pelanggar'];
            $output['nama_pelanggar'] = $row['nama_pelanggar'];
            $output['no_ktp'] = $row['no_ktp'];
        }
        echo json_encode($output);
    }
?>
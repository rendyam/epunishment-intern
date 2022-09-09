<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $output = array();
        $statement = $db->prepare("
            SELECT * FROM gen_pelanggaran_tab
            WHERE id_pelanggaran = '". $_POST['id'] ."'
            LIMIT 1
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row){
            $output['id_jenis'] = $row['id_jenis'];
            $output['id_pelanggaran'] = $row['id_pelanggaran'];
            $output['nama_pelanggaran'] = $row['pelanggaran'];
        }
        echo json_encode($output);
    }
?>
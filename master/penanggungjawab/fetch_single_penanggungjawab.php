<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $output = array();
        $statement = $db->prepare("
            SELECT * FROM gen_penanggungjawab_tab
            WHERE id_pj = '". $_POST['id'] ."'
            LIMIT 1
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row){
            $output['id_pj'] = $row['id_pj'];
            $output['nama_penanggungjawab'] = $row['nama_pj'];
            $output['email_penanggungjawab'] = $row['email'];
        }
        echo json_encode($output);
    }
?>
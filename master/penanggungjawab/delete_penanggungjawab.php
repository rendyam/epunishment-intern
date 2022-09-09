<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $statement = $db->prepare("
                        DELETE FROM
                            gen_penanggungjawab_tab
                        WHERE id_pj = :id_pj
                    ");
        $statement->execute(
            array(
                ':id_pj' => $_POST['id']
            )
        );
        echo "Berhasil dihapus!";
    }
?>
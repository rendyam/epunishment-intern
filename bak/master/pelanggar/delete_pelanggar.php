<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $statement = $db->prepare("
                        DELETE FROM
                            gen_pelanggar_tab
                        WHERE id_pelanggar = :id_pelanggar
                    ");
        $statement->execute(
            array(
                ':id_pelanggar' => $_POST['id']
            )
        );
        echo "Berhasil dihapus!";
    }
?>
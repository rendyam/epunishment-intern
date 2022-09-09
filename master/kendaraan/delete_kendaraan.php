<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $statement = $db->prepare("
                        DELETE FROM
                            gen_kendaraan_tab
                        WHERE id_kendaraan = :id_kendaraan
                    ");
        $statement->execute(
            array(
                ':id_kendaraan' => $_POST['id']
            )
        );
        echo "Berhasil dihapus!";
    }
?>
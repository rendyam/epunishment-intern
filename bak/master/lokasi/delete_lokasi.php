<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $statement = $db->prepare("
                        DELETE FROM
                            gen_lokasi_tab
                        WHERE id_lokasi = :id_lokasi
                    ");
        $statement->execute(
            array(
                ':id_lokasi' => $_POST['id']
            )
        );
        echo "Berhasil dihapus!";
    }
?>
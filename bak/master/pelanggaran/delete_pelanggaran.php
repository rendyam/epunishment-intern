<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["id"])){
        $statement = $db->prepare("
                        DELETE FROM
                            gen_pelanggaran_tab
                        WHERE id_pelanggaran = :id_pelanggaran
                    ");
        $statement->execute(
            array(
                ':id_pelanggaran' => $_POST['id']
            )
        );
        echo "Berhasil dihapus!";
    }
?>
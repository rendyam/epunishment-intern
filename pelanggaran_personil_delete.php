<?php
    include "koneksi/connect-db.php";
    
    if(isset($_POST["id"])){
        $id = intval($_POST["id"]);
        $statement = $db->prepare("
                        DELETE FROM
                            pelanggaran_personil_tab
                        WHERE id = :id
                    ");
        $statement->execute(
            array(
                ':id' => $id
            )
        );
        echo "Berhasil dihapus!";
    }
?>
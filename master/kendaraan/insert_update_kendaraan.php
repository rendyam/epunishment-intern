<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["operations"])){
        if($_POST["operations"] == 'Tambah'){
            $no_polisi = $_POST["no_polisi"];
            $no_stnk = $_POST["no_stnk"];

            $db->beginTransaction();

            $sqlQuery = $db->prepare("
                insert into gen_kendaraan_tab(no_polisi, no_stnk) 
                values(:no_polisi, :no_stnk)
            ");

            $sqlQuery->bindParam(':no_polisi', $no_polisi, PDO::PARAM_STR);
            $sqlQuery->bindParam(':no_stnk', $no_stnk, PDO::PARAM_STR);

            $result = $sqlQuery->execute();

            $last_id = $db->lastInsertId();

            $db->commit();

            if(!empty($result)){
                $get_value = array(
                                "no_polisi" => $no_polisi
                            );
                echo json_encode($get_value);
            }
        }
        if($_POST["operations"] == 'Edit'){
            // echo json_encode($_POST['kendaraan_id']);
            
            $statement = $db->prepare("
                            UPDATE 
                                gen_kendaraan_tab
                            SET 
                                no_polisi = :no_polisi,
                                no_stnk = :no_stnk
                            WHERE 
                                id_kendaraan = :id_kendaraan
                        ");
            $statement->execute(
                array(
                    ':no_polisi' => $_POST["no_polisi"],
                    ':no_stnk' => $_POST["no_stnk"],
                    ':id_kendaraan' => $_POST["kendaraan_id"]
                )
            );
            echo "Berhasil diedit!";
        }
    }
?>
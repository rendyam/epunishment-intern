<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["operation"])){
        if($_POST["operation"] == 'Tambah'){
            $namaLokasi = $_POST["nama_lokasi"];

            $db->beginTransaction();
            
            $statusLokasi = 1;

            $sqlQuery = $db->prepare("
                insert into gen_lokasi_tab(lokasi, status_lokasi) 
                values(:lokasi, :status_lokasi)
            ");

            $sqlQuery->bindParam(':lokasi', $namaLokasi, PDO::PARAM_STR);
            $sqlQuery->bindParam(':status_lokasi', $statusLokasi, PDO::PARAM_STR);

            $result = $sqlQuery->execute();

            $last_id = $db->lastInsertId();

            $db->commit();

            if(!empty($result)){
                $get_value = array(
                                "id_lokasi" => $last_id,
                                "nama_lokasi" => $namaLokasi
                            );
                echo json_encode($get_value);
                // return "Berhasil ditambah!";
            }
        }
        if($_POST["operation"] == 'Edit'){
            $statement = $db->prepare("
                            UPDATE 
                                gen_lokasi_tab
                            SET 
                                lokasi = :lokasi
                            WHERE id_lokasi = :id_lokasi
                        ");
            $statement->execute(
                array(
                    ':lokasi' => $_POST['nama_lokasi'],
                    ':id_lokasi' => $_POST['lokasi_id']
                )
            );
            echo "Berhasil diedit!";
        }
    }
?>
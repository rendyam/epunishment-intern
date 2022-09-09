<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["operation"])){
        if($_POST["operation"] == 'Tambah'){
            $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

            $namaPelanggaran = $_POST["nama_pelanggaran"];
            $id_jenis = $_POST["jenis_pelanggaran"];

            $db->beginTransaction();
            
            $statusPelanggaran = 1;

            $sqlQuery = $db->prepare("
                insert into gen_pelanggaran_tab(pelanggaran, id_jenis, status_pelanggaran) 
                values(:pelanggaran, :id_jenis, :status_pelanggaran)
            ");

            $sqlQuery->bindParam(':pelanggaran', $namaPelanggaran, PDO::PARAM_STR);
            $sqlQuery->bindParam(':id_jenis', $id_jenis, PDO::PARAM_STR);
            $sqlQuery->bindParam(':status_pelanggaran', $statusPelanggaran, PDO::PARAM_STR);

            $result = $sqlQuery->execute();

            $db->commit();

            if(!empty($result)){
                echo "Berhasil ditambah!";
            }
        }
        if($_POST["operation"] == 'Edit'){
            $statement = $db->prepare("
                            UPDATE 
                                gen_pelanggaran_tab
                            SET 
                                pelanggaran = :pelanggaran,
                                id_jenis = :id_jenis
                            WHERE id_pelanggaran = :id_pelanggaran
                        ");
            $statement->execute(
                array(
                    ':pelanggaran' => $_POST['nama_pelanggaran'],
                    ':id_jenis' => $_POST['jenis_pelanggaran'],
                    ':id_pelanggaran' => $_POST['pelanggaran_id']
                )
            );
            echo "Berhasil diedit!";
        }
    }
?>
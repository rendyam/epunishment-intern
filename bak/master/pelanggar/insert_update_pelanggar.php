<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["operations"])){
        if($_POST["operations"] == 'Tambah'){
            
            $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

            $namaPelanggar = $_POST["nama_pelanggar"];
            $noKTP = $_POST["no_ktp"];

            $db->beginTransaction();
            
            $statusPelanggaran = 1;

            $sqlQuery = $db->prepare("
                insert into gen_pelanggar_tab(nama_pelanggar, no_ktp) 
                values(:nama_pelanggar, :no_ktp)
            ");

            $sqlQuery->bindParam(':nama_pelanggar', $namaPelanggar, PDO::PARAM_STR);
            $sqlQuery->bindParam(':no_ktp', $noKTP, PDO::PARAM_STR);

            $result = $sqlQuery->execute();

            $last_id = $db->lastInsertId();

            $db->commit();

            if(!empty($result)){
                $get_value = array(
                                "id_pelanggar" => $last_id,
                                "nama_pelanggar" => $namaPelanggar, 
                                "no_ktp" => $noKTP
                            );
                echo json_encode($get_value);
            }
        }
        if($_POST["operations"] == 'Edit'){
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
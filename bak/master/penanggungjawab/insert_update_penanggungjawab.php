<?php
    include "../../koneksi/connect-db.php";

    if(isset($_POST["operation"])){
        if($_POST["operation"] == 'Tambah'){
            $namaPenanggungJawab = $_POST["nama_penanggungjawab"];
            $emailPenanggungJawab = $_POST["email_penanggungjawab"];

            $db->beginTransaction();
            
            $statusPenanggungJawab = 1;

            $sqlQuery = $db->prepare("
                insert into gen_penanggungjawab_tab(nama_pj, status_pj, email) 
                values(:nama_pj, :status_pj, :email)
            ");

            $sqlQuery->bindParam(':nama_pj', $namaPenanggungJawab, PDO::PARAM_STR);
            $sqlQuery->bindParam(':status_pj', $statusPenanggungJawab, PDO::PARAM_STR);
            $sqlQuery->bindParam(':email', $emailPenanggungJawab, PDO::PARAM_STR);

            $result = $sqlQuery->execute();

            $last_id = $db->lastInsertId();

            $db->commit();

            if(!empty($result)){
                $get_value = array(
                                "id_penanggungjawab" => $last_id,
                                "nama_penanggungjawab" => $namaPenanggungJawab
                            );
                echo json_encode($get_value);
            }
        }
        if($_POST["operation"] == 'Edit'){
            $statement = $db->prepare("
                            UPDATE 
                                gen_penanggungjawab_tab
                            SET 
                                nama_pj = :nama_pj,
                                email = :email
                            WHERE id_pj = :id_pj
                        ");
            $statement->execute(
                array(
                    ':nama_pj' => $_POST['nama_penanggungjawab'],
                    ':id_pj' => $_POST['penanggungjawab_id'],
                    ':email' => $_POST['email_penanggungjawab'],
                )
            );
            echo "Berhasil diedit!";
        }
    }
?>
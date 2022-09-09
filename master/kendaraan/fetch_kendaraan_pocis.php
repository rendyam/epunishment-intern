<?php
    include "../../koneksi/connect-db.php";

    $conn = mysqli_connect($hostPocis, $dbuserPocis, $dbpassPocis, $dbnamePocis);
    $connMasterKendaraan = mysqli_connect($host3306, $dbuser3306, $dbpass3306, $dbname3306);

    if(isset($_GET['q'])){
        $q = $_GET['q'];
        $stmt = $conn->prepare("
                        SELECT 
                            a.police_number as police_number
                        FROM 
                            view_map_rfid_trucks a 
                        WHERE 
                            a.`status` = 'ACTIVE' 
                        AND
                            a.police_number LIKE ?
                        ORDER BY 
                            a.police_number
                    ");
        $param = "%$q%";
        $stmt->bind_param("s", $param);
        $data = array();
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    $police_number = $row['police_number'];
                    $data[] = array('police_number' => $police_number);
                }
                $stmt->close();
            } else {
                $q = $_GET['q'];
                $stmt = $connMasterKendaraan->prepare("
                            SELECT 
                                a.no_polisi 
                            FROM 
                                gen_kendaraan_tab a 
                            WHERE 
                                a.no_polisi LIKE ?
                            ORDER BY 
                                a.id_kendaraan
                        ");
                $param = "%$q%";
                $stmt->bind_param("s", $param);
                $data = array();
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    if($result->num_rows>0){
                        while($row = $result->fetch_assoc()){
                            $police_number = $row['no_polisi'];
                            $data[] = array('police_number' => $police_number);
                        }
                        $stmt->close();
                    } else {
                        $data[] = array("police_number" => "Nomor Polisi tidak ditemukan");
                    }
                }
            }
            echo json_encode($data);
        }
    }
    // if(isset($_POST["id"])){
    //     $id_klasifikasi = $_POST["id"];
    //     // get_kendaraan($id_klasifikasi);
    //     if($id_klasifikasi == 1){ //angkutan barang
    //         get_kendaraan_pocis();
    //     }
    //     else if ($id_klasifikasi == 2 || $id_klasifikasi == 4) { //non angkutan barang = 2, k3lh = 4
    //         get_kendaraan_vpacs();
    //     }
    // }

    // function get_kendaraan_pocis(){
    //     include "../../koneksi/connect-db.php";
        
    //     try {
    //         $db = new PDO("mysql:host=$hostPocis;dbname=$dbnamePocis", $dbuserPocis, $dbpassPocis, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            
    //         $query = "SELECT a.police_number from view_map_rfid_trucks a WHERE a.`status` = 'ACTIVE' order BY a.police_number";
    //         $result = $db->prepare($query);
    //         $result->execute();

    //         $num = $result->rowCount();
        
    //         $arr = array();
    //         if($num > 0) {
    //             while ($row = $result->fetch(PDO::FETCH_NUM)) {
    //                 $arr[] = $row[0];
    //             }
    //         }
            
    //         $dbMasterKendaraan = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
    //         $queryMasterKendaraan = "SELECT no_polisi from gen_kendaraan_tab a order BY a.id_kendaraan";
    //         $resultMasterKendaraan = $dbMasterKendaraan->prepare($queryMasterKendaraan);
    //         $resultMasterKendaraan->execute();

    //         $numMasterKendaraan = $resultMasterKendaraan->rowCount();

    //         $arrMasterKendaraan=array();
    //         if($numMasterKendaraan > 0) {
    //             while ($rowMasterKendaraan = $resultMasterKendaraan->fetch(PDO::FETCH_NUM)) {
    //                 $arrMasterKendaraan[] = $rowMasterKendaraan[0];
    //             }
    //         }

    //         $arr = array_merge($arr, $arrMasterKendaraan);
    //         echo json_encode($arr);

    //     } catch (Exception $e) {
    //         echo json_encode($e);            
    //     }
    // }

    // function get_kendaraan_vpacs(){
    //     include "../../koneksi/connect-db.php";
    //     $dbVPACS = new PDO("mysql:host=$hostVPACS;dbname=$dbnameVPACS", $dbuserVPACS, $dbpassVPACS, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
    //     $dbMasterKendaraan = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
    //     try {
    //         $output_vpacs = array();
    //         $kendaraan_vpacs = $dbVPACS->prepare("
    //             SELECT 
    //                 a.no_polisi as no_polisi
    //             FROM 
    //                 tags a 
    //             WHERE 
    //                 a.`status` = 'V' 
    //             AND 
    //                 a.no_polisi IS NOT NULL 
    //             ORDER BY 
    //                 a.no_polisi
    //         ");
    //         $kendaraan_vpacs->execute();
    //         $result_vpacs = $kendaraan_vpacs->fetchAll();
    //         foreach($result_vpacs as $row_vpacs){
    //             $output_vpacs[] = $row_vpacs[0];
    //         }

    //         $queryMasterKendaraan = "SELECT no_polisi from gen_kendaraan_tab a order BY a.id_kendaraan";
    //         $resultMasterKendaraan = $dbMasterKendaraan->prepare($queryMasterKendaraan);
    //         $resultMasterKendaraan->execute();
            
    //         $numMasterKendaraan = $resultMasterKendaraan->rowCount();

    //         $arrMasterKendaraan=array();
    //         if($numMasterKendaraan > 0) {
    //             while ($rowMasterKendaraan = $resultMasterKendaraan->fetch(PDO::FETCH_NUM)) {
    //                 $arrMasterKendaraan[] = $rowMasterKendaraan[0];
    //             }
    //         }
    //         $output_vpacs = array_merge($output_vpacs, $arrMasterKendaraan);

    //         $json = json_encode(mb_convert_encoding($output_vpacs, 'UTF-8', 'UTF-8'));
            

    //         if ($json) {
    //             echo $json;
    //         }
    //         else {
    //             echo json_last_error_msg();
    //         }
    //     } catch (Exception $e) {
    //         echo json_encode($e);            
    //     }
    // }


?>
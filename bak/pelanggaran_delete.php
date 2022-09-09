<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 0;
    $strMessage = "";
    
    $txtKode = base64_decode($_GET['id']); 
    
    if (isset($_GET['id']) and $_SERVER['REQUEST_METHOD'] == "GET") {
        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

            $db->beginTransaction();

            $sqlQuery = $db->prepare("delete from pelanggaran_tab where id_trx = :id");

            $sqlQuery->bindParam(':id', $txtKode, PDO::PARAM_STR);

            $sqlQuery->execute();

            $db->commit();

            if($sqlQuery->rowCount() > 0){
                header('location:pelanggaran_overview.php');
            }else{
                $strMessage = "<div class='alert alert-error'><strong>Data gagal disimpan</strong></div>";
            }
        }catch(PDOException $e){
            if($modeDebug==0){
                $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
            }else{
                $strMessage = $e->getMessage();
            }
        }
    }
?>
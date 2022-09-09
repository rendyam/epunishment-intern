<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 1;
            
    $strMessage = "";
    
    $strCmbNopol = "";
    
    try {
        $db = new PDO("mysql:host=$hostVPACS;dbname=$dbnameVPACS", $dbuserVPACS, $dbpassVPACS, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        $dbMasterKendaraan = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $query = "SELECT a.no_polisi from tags a WHERE a.`status` = 'V' AND a.no_polisi IS NOT null order BY a.no_polisi";
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        $arr = array();
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $arr[] = $row[0];
            }
        }

        $queryMasterKendaraan = "SELECT no_polisi from gen_kendaraan_tab a order BY a.id_kendaraan";
        $resultMasterKendaraan = $dbMasterKendaraan->prepare($queryMasterKendaraan);
        $resultMasterKendaraan->execute();

        $numMasterKendaraan = $resultMasterKendaraan->rowCount();

        $arrMasterKendaraan=array();
        if($numMasterKendaraan > 0) {
            while ($rowMasterKendaraan = $resultMasterKendaraan->fetch(PDO::FETCH_NUM)) {
                $arrMasterKendaraan[] = $rowMasterKendaraan[0];
            }
        }
        $arr = array_merge($arr, $arrMasterKendaraan);
        
        for($i=0; $i<count($arr); $i++){
            $strCmbNopol = $strCmbNopol."<option value='$arr[$i]'>$arr[$i]</option>";
        }
        
        $db = null;
    } catch (Exception $e) {
        if($modeDebug==0){
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        }else{
            $strMessage = $e->getMessage();
        }
    }
    
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <?PHP
        include "header.php";
    ?>
    
    <link rel="stylesheet" href="css/separate/vendor/bootstrap-daterangepicker.min.css">
</head>
<body class="with-side-menu">

	<header class="site-header">
	    <div class="container-fluid">
                
                <!logo startui-->
	        <!--<a href="#" class="site-logo">
	            <img class="hidden-md-down" src="img/logo-2.png" alt="">
	            <img class="hidden-lg-down" src="img/logo-2-mob.png" alt="">
	        </a>-->
                
                <!toggle show hide menu-->
	        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
	            <span>toggle menu</span>
	        </button>
	
	        <button class="hamburger hamburger--htla">
	            <span>toggle menu</span>
	        </button>
	        <?PHP include "menu_up.php";?>
	    </div><!--.container-fluid-->
	</header><!--.site-header-->

	<div class="mobile-menu-left-overlay"></div>
	<?PHP include "menu_left.php";?>

	<div class="page-content">
            <?PHP echo $strMessage;?>
            <div class="container-fluid">
                <div class="box-typical box-typical-padding">
                    <h2 class="with-border">Input Pelanggaran Kendaraan Non Angkutan Barang</h2>
                    <form id="frmLogin" class="sign-box" action="pelanggaran_nab_input.php" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <input type="text" class="form-control" name="txtPPJ" id="txtPPJ" value="<?PHP echo $txtKode;?>" hidden>
                            <label class="col-sm-2 form-control-label">No Polisi *</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                <select class="select2" name="cmbNopol" id="cmbNopol">
                                    <option value=""></option>
                                    <?PHP echo $strCmbNopol;?>
                                </select></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <button type="submit" class="btn" name="btnPilih" id="btnPilih">Pilih</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	<script src="js/lib/jquery/jquery-3.2.1.min.js"></script>
	<script src="js/lib/popper/popper.min.js"></script>
	<script src="js/lib/tether/tether.min.js"></script>
	<script src="js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="js/plugins.js"></script>
        <script src="js/lib/select2/select2.full.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
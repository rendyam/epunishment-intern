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
        $db = new PDO("mysql:host=$hostPocis;dbname=$dbnamePocis", $dbuserPocis, $dbpassPocis, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "SELECT a.police_number from view_map_rfid_trucks a WHERE a.`status` = 'ACTIVE' order BY a.police_number";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $strCmbNopol = $strCmbNopol."<option value='$row[0]'>$row[0]</option>";
            }
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
                    <h2 class="with-border">Input Pelanggaran Kendaraan Angkutan Barang</h2>
                    <form id="frmLogin" class="sign-box" action="pelanggaran_ab_input.php" method="post" enctype="multipart/form-data">
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
<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 1;
            
    $strMessage = "";
    
    $str = "";
    
?>

<!DOCTYPE html>
<html>
<?PHP include "header.php";?>
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
                    <div class="row">
                        <div class="col-sm-6">
                            <iframe width="475" height="225" src="https://www.youtube.com/embed/eluTDiGvNTI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div><!--.widget-simple-sm-fill-->
                        
                        <div class="col-sm-6">
                            <a href="pelanggaran_input.php">
                                <section class="widget widget-simple-sm-fill green">
                                    <br>
                                    <div class="widget-simple-sm-icon">
                                        <span class="font-icon glyphicon glyphicon-pencil"></span>
                                    </div>
                                    <div class="widget-simple-sm-fill-caption"> <br> <h2>Input Pelanggaran</h2> <br> </div>
                                </section>
                            </a>
                        </div><!--.widget-simple-sm-fill-->
                    </div><!--.row-->
                </div>
            </div>
        </div>
	<script src="js/lib/jquery/jquery-3.2.1.min.js"></script>
	<script src="js/lib/popper/popper.min.js"></script>
	<script src="js/lib/tether/tether.min.js"></script>
	<script src="js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="js/plugins.js"></script>
        <script src="js/lib/datatables-net/datatables.min.js"></script>
        <script>
		$(function() {
                        $('#example').DataTable({
                            "order": [[ 1, "asc" ]]
                        });
                        
                        $('#example2').DataTable({
                            "order": [[ 1, "asc" ]]
                        });
		});
	</script>
        
<script src="js/app.js"></script>
</body>
</html>
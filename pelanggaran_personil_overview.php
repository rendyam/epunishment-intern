<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 1;
            
    $strMessage = "";
    
    $str = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "SELECT * from pelanggaran_personil_rpt order by tanggal";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        $count = 0;
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;

                $str = $str."<tr>";
                $str = $str."<td valign='top'>".$count."</td>";
                $str = $str."<td valign='top'>".$row[1]."</td>";
                $str = $str."<td valign='top'>".$row[2]."</td>";
                $str = $str."<td valign='top'>".$row[3]."</td>";
                $str = $str."<td valign='top'>".$row[4]."</td>";
                $str = $str."<td valign='top'>".$row[5]."</td>";
                $str = $str."<td valign='top'>".$row[6]."</td>";
                $str = $str."<td valign='top'>".$row[7]."</td>";
                $str = $str."<td valign='top'>".$row[8]."</td>";
                
                $row[0] = base64_encode($row[0]);
                
                $str = $str."<td class='center' width='150px'>
                                <a class='btn btn-primary' href='pelanggaran_personil_edit.php?id=$row[0]'>
                                    <i class='icon-edit icon-white'></i>  
                                    Edit                                            
                                </a>
                            </td>";

                
                $str = $str."<td class='center' width='150px'>
                                <a class='btn btn-danger hapus' id='".$row[0]."'>
                                    <i class='icon-edit icon-white'></i>  
                                    Hapus                                            
                                </a>
                            </td>";
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
                    <h2 class="with-border">Daftar Pelanggaran</h2>
                    <div class="box-typical box-typical-padding">
                    <h2 class="with-border"><?PHP echo "<a href='pelanggaran_overview_export_excel.php'><i class='fa fa-file-excel-o'></i> Export Excel</a>";?></h2>
                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <!--<th>No</th>-->
                            <th>No</th>
                            <th>Tanggal<br>Pelanggaran</th>
                            <th>Waktu</th>
                            <th>Nama Pelanggar</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Pekerjaan</th>
                            <th>Perusahaan</th>
                            <th>Lokasi</th>
                            <th>PIC Tilang</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <!--<th>No</th>-->
                            <th>No</th>
                            <th>Tanggal<br>Pelanggaran</th>
                            <th>Waktu</th>
                            <th>Nama Pelanggar</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Pekerjaan</th>
                            <th>Perusahaan</th>
                            <th>Lokasi</th>
                            <th>PIC Tilang</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </tfoot>
                        <tbody>
                            <?PHP echo $str;?>
                        </tbody>
                    </table>
                </div>
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
                "order": [[ 0, "asc" ]]
            });

            $(document).on('click', '.hapus', function(event){
                var id = $(this).attr("id")
                decodedId = atob(id)
                if(confirm('Anda yakin akan menghapus?')){
                    $.ajax({
                        url:"pelanggaran_personil_delete.php",
                        method:"POST",
                        data:{id:decodedId},
                        success:function(data){
                            alert(data)
                            location.reload(true)
                        }
                    })
                }
            })
        });
    </script>
    <script src="js/app.js"></script>

</body>
</html>
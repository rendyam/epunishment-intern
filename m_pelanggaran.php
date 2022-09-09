<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 1;
            
    $strMessage = "";
    
    $str = "";
    $strJenisPelanggaran = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "SELECT a.id_pelanggaran, b.jenis, a.pelanggaran from gen_pelanggaran_tab a left join gen_jenis_pelanggaran_tab b on a. id_jenis=b.id_jenis";
        $queryJenisPelanggaran = "SELECT * FROM gen_jenis_pelanggaran_tab";

        $result = $db->prepare($query);
        $result->execute();

        $resultJenisPelanggaran = $db->prepare($queryJenisPelanggaran);
        $resultJenisPelanggaran->execute();

        $num = $result->rowCount();
        $numJenisPelanggaran = $resultJenisPelanggaran->rowCount();

        $count = 0;
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;

                $str = $str."<tr>";
                $str = $str."<td valign='top'>".$count."</td>";
                $str = $str."<td valign='top'>".$row[0]."</td>";
                $str = $str."<td valign='top'>".$row[1]."</td>";
                $str = $str."<td valign='top'>".$row[2]."</td>";
                
                $row[0] = base64_encode($row[0]);
                
                $str = $str."<td class='center' width='100px'>
                                <a class='btn btn-primary edit' id='".$row[0]."'>
                                    <i class='icon-edit icon-white'></i>  
                                    Edit                                            
                                </a>
                            </td>";
                
                $str = $str."<td class='center' width='100px'>
                                <a class='btn btn-danger hapus' id='".$row[0]."'>
                                    <i class='icon-edit icon-white'></i>  
                                    Hapus                                            
                                </a>
                            </td>";
            }
        }

        if($numJenisPelanggaran > 0) {
            while ($rowJenisPelanggaran = $resultJenisPelanggaran->fetch(PDO::FETCH_NUM)) {
                $strJenisPelanggaran = $strJenisPelanggaran."<option value=".$rowJenisPelanggaran[0]."> ". $rowJenisPelanggaran[1] ."</option>";
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
                    <h2 class="with-border">Master Pelanggaran</h2>
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="pull-right">
                                <button type="button" id="add_pelanggaran" data-toggle="modal" data-target="#pelanggaranModal" class='btn btn-success' href='#'>
                                    <i class='icon-edit icon-white'></i>  
                                    Tambah Data                                            
                                </button> 
                            </div>
                        </div>
                    </div>

                    <br>
                    
                    <div class="box-typical box-typical-padding">
                        <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>No</th>
                                <th>Kode</th>
                                <th>Jenis</th>
                                <th>Pelanggaran</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <!--<th>No</th>-->
                                <th>No</th>
                                <th>Kode</th>
                                <th>Jenis</th>
                                <th>Pelanggaran</th>
                                <th>Edit</th>
                                <th>Hapus</th>
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

        <div id="pelanggaranModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="pelanggaran_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Data Pelanggaran</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="nama_pelanggaran">Jenis Pelanggaran</label>
                                        <div class="col-lg-12 id_100">
                                            <select id="jenis_pelanggaran" class="form-control" name="jenis_pelanggaran">
                                                <option value="">Pilih jenis pelanggaran</option>
                                                <?php echo $strJenisPelanggaran; ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="nama_pelanggaran">Nama Pelanggaran</label>
                                        <input type="text" class="form-control" name="nama_pelanggaran" id="nama_pelanggaran" placeholder="Masukkan nama pelanggaran. Misal: Merokok">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="pelanggaran_id" id="pelanggaran_id" />
                            <input type="hidden" name="operation" id="operation" value="Tambah"/>
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Tambah" />
                        </div>
                    </div>
                </form>
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
                
                var dataTable = $('#example').DataTable({
                    "order": [[ 0, "asc" ]]
                });

                $(document).on('click', '#add_pelanggaran', function(event){
                    $('#pelanggaranModal').modal('show')
                    $('#jenis_pelanggaran').val("")
                    $('#nama_pelanggaran').val("")
                    $('.modal-title').text('Tambah Data Pelanggaran')
                    $('#action').val('Tambah')
                    $('#operation').val('Tambah')
                })

                $(document).on('submit', '#pelanggaran_form', function(event){
                    event.preventDefault();
                    var id_jenis = $('#jenis_pelanggaran').val()
                    var nama_pelanggaran = $('#nama_pelanggaran').val()

                    if(id_jenis != "" && nama_pelanggaran != "")
                    {
                        $.ajax({
                            url:"master/pelanggaran/insert_update_pelanggaran.php",
                            method:"POST",
                            data:new FormData(this),
                            contentType:false,
                            processData:false,
                            success:function(data){
                                alert(data)
                                $('#pelanggaran_form')[0].reset()
                                $('#pelanggaran_form').modal('hide')
                                location.reload(true)
                            }
                        })
                    }
                    else
                    {
                        alert("Form harus diisi!")
                    }
                })

                $(document).on('click', '.edit', function(event){
                    var id = $(this).attr("id")
                    decodedId = atob(id)
                    
                    $.ajax({
                        url:"master/pelanggaran/fetch_single_pelanggaran.php",
                        method:"POST",
                        data:{id:decodedId},
                        dataType:"json",
                        success:function(data){
                            $('#pelanggaranModal').modal('show')
                            $('#jenis_pelanggaran option[value="' + data.id_jenis + '"]').prop('selected', true)
                            $('#nama_pelanggaran').val(data.nama_pelanggaran)
                            $('.modal-title').text('Edit Jenis Pelanggaran')
                            $('#pelanggaran_id').val(decodedId)
                            $('#action').val('Edit')
                            $('#operation').val('Edit')
                        }
                    })
                })

                $(document).on('click', '.hapus', function(event){
                    var id = $(this).attr("id")
                    decodedId = atob(id)
                    if(confirm('Anda yakin akan menghapus?')){
                        $.ajax({
                            url:"master/pelanggaran/delete_pelanggaran.php",
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
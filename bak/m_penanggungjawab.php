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
        
        $query = "SELECT a.* from gen_penanggungjawab_tab a";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        $count = 0;
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;

                $str = $str."<tr>";
                $str = $str."<td valign='top'>".$count."</td>";
                $str = $str."<td valign='top'>".$row[0]."</td>";
                $str = $str."<td valign='top'>".$row[1]."</td>";
                
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
                    <h2 class="with-border">Master Penanggungjawab</h2>
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="pull-right">
                                <button type="button" id="add_penanggungjawab" data-toggle="modal" data-target="#penanggungJawabModal" class='btn btn-success' href='#'>
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
                                <th>Penanggungjawab</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <!--<th>No</th>-->
                                <th>No</th>
                                <th>Kode</th>
                                <th>Penanggungjawab</th>
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

        <div id="penanggungJawabModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="penanggungjawab_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Data Penanggung Jawab</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="nama_penanggungjawab">Nama Penanggung Jawab</label>
                                        <input type="text" class="form-control" name="nama_penanggungjawab" id="nama_penanggungjawab" placeholder="Masukkan nama penanggung jawab...">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="penanggungjawab_id" id="penanggungjawab_id" />
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
                $('#example').DataTable({
                    "order": [[ 0, "asc" ]]
                });

                $(document).on('click', '#add_penanggungjawab', function(event){
                    $('#penanggungJawabModal').modal('show')
                    $('#nama_penanggungjawab').val("")
                    $('.modal-title').text('Tambah Data Penanggung Jawab')
                    $('#action').val('Tambah')
                    $('#operation').val('Tambah')
                })

                $(document).on('submit', '#penanggungjawab_form', function(event){
                    event.preventDefault();
                    var nama_penanggungjawab = $('#nama_penanggungjawab').val()

                    if(nama_penanggungjawab != "") {
                        $.ajax({
                            url:"master/penanggungjawab/insert_update_penanggungjawab.php",
                            method:"POST",
                            data:new FormData(this),
                            contentType:false,
                            processData:false,
                            success:function(data){
                                alert(data)
                                $('#penanggungjawab_form')[0].reset()
                                $('#penanggungjawab_form').modal('hide')
                                location.reload(true)
                            }
                        })
                    } else {
                        alert("Form harus diisi!")
                    }
                })

                $(document).on('click', '.edit', function(event){
                    var id = $(this).attr("id")
                    decodedId = atob(id)
                    
                    $.ajax({
                        url:"master/penanggungjawab/fetch_single_penanggungjawab.php",
                        method:"POST",
                        data:{id:decodedId},
                        dataType:"json",
                        success:function(data){
                            $('#penanggungJawabModal').modal('show')
                            $('#nama_penanggungjawab').val(data.nama_penanggungjawab)
                            $('.modal-title').text('Edit Nama Penanggung Jawab')
                            $('#penanggungjawab_id').val(decodedId)
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
                            url:"master/penanggungjawab/delete_penanggungjawab.php",
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
</html><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


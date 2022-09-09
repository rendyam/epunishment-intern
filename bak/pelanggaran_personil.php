<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    
    $modeDebug = 1;
            
    $strMessage = "";

    $strCmbPJ = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_penanggungjawab_tab where status_pj = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $strCmbPJ = $strCmbPJ."<option value='$row[0]'>$row[1]</option>";
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
    
    $strCmbLokasi = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_lokasi_tab where status_lokasi = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $strCmbLokasi = $strCmbLokasi."<option value='$row[0]'>$row[1]</option>";
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

    $strPelanggar = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_pelanggar_tab";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $strPelanggar = $strPelanggar."<option value='$row[0]' data-noktp='$row[2]'>$row[1]</option>";
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
    
    $strCmbPelanggaran = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $query = "SELECT a.id_pelanggaran, b.jenis, a.pelanggaran FROM gen_pelanggaran_tab a LEFT JOIN gen_jenis_pelanggaran_tab b ON a.id_jenis=b.id_jenis WHERE a.status_pelanggaran = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $strCmbPelanggaran = $strCmbPelanggaran."<option value='$row[0]'>$row[1] - $row[2]</option>";
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
    if (isset($_POST['btnSimpan']) and $_SERVER['REQUEST_METHOD'] == "POST") {
        // var_dump($_POST);
        // exit;
        $date = str_replace('/', '-', $_POST['tanggal']);
        $date = date("Y-m-d H:i:s", strtotime($date));
        
        try {
            $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            
            $tanggal = $date;
            $waktu = $_POST['waktu'];
            $id_pelanggar = $_POST['nama_pelanggar_select'];
            $no_ktp = $_POST['no_ktp_front'];
            $pekerjaan = $_POST['pekerjaan'];
            $perusahaan = $_POST['cmbPerusahaan'];
            $lokasi = $_POST['cmbLokasi'];
            $pelanggaran = $_POST['cmbPelanggaran'];
            $picTilang = $_SESSION["sessidetilang"];

            if($waktu=="" || $id_pelanggar=="" || $no_ktp=="" || $pekerjaan=="" || $perusahaan=="" || $lokasi=="" || $pelanggaran==""){
                $strMessage = "<div class='alert alert-error'><strong>Kolom dengan tanda bintang (*) wajib diisi</strong></div>";
            }else{
                $db->beginTransaction();
                
                $sqlQuery = $db->prepare("insert into pelanggaran_personil_tab(tanggal, waktu, id_pelanggar, pekerjaan, perusahaan, lokasi, pelanggaran, pic) values(:tanggal, :waktu, :id_pelanggar, :pekerjaan, :perusahaan, :lokasi, :pelanggaran, :pic)");

                $sqlQuery->bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
                $sqlQuery->bindParam(':waktu', $waktu, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_pelanggar', $id_pelanggar, PDO::PARAM_STR);
                $sqlQuery->bindParam(':pekerjaan', $pekerjaan, PDO::PARAM_STR);
                $sqlQuery->bindParam(':perusahaan', $perusahaan, PDO::PARAM_STR);
                $sqlQuery->bindParam(':lokasi', $lokasi, PDO::PARAM_STR);
                $sqlQuery->bindParam(':pelanggaran', $pelanggaran, PDO::PARAM_STR);
                $sqlQuery->bindParam(':pic', $picTilang, PDO::PARAM_STR);

                $sqlQuery->execute();

                $db->commit();

                if($sqlQuery->rowCount() > 0){
                    // header('location:pelanggaran_ab_edit.php?id='.base64_encode($txtKode));

                    // header('location:pelanggaran_personil.php');
                    $strMessage = "<div class='alert alert-success'><strong>Data berhasil disimpan</strong></div>";
                }else{
                    $strMessage = "<div class='alert alert-error'><strong>Data gagal disimpan</strong></div>";
                }
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

<!DOCTYPE html>
<html>
<head lang="en">
    <?PHP
        include "header.php";
    ?>

    <link rel="stylesheet" href="css/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="css/separate/vendor/flatpickr.min.css">
    <link rel="stylesheet" href="css/separate/vendor/bootstrap-daterangepicker.min.css">
    <link rel="stylesheet" href="css/lib/clockpicker/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" href="css/separate/vendor/bootstrap-select/bootstrap-select.min.css">
    <style>
        .required {
            color: red;
        }
    </style>
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
	        <?php include "menu_up.php";?>
	    </div><!--.container-fluid-->
	</header><!--.site-header-->

	<div class="mobile-menu-left-overlay"></div>
	<?php include "menu_left.php";?>

	<div class="page-content">
            <?php echo $strMessage;?>
            <div class="container-fluid">
                <div class="box-typical box-typical-padding">
                    <h2 class="with-border">Input Pelanggaran Personil</h2>
                    <form id="form_pelanggaran_personil" class="sign-box" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Tanggal<span class="required">*</span></label>
                            <div class="col-sm-4">
                                <div class='input-group date'>
                                    <input id="daterange3" type="text" name="tanggal" value="<?php date("m/d/Y"); ?>" class="form-control">
                                    <span class="input-group-addon">
                                        <i class="font-icon font-icon-calend"></i>
                                    </span>
                                </div>
                            </div>
                            <label class="col-sm-2 form-control-label">Waktu<span class="required">*</span></label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control clockpicker" value="<?php date_default_timezone_set('Asia/Jakarta'); echo date("H:i"); ?>" id="waktu" name="waktu">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Nama Pelanggar<span class="required">*</span></label>
                            <div class="col-sm-10">                                
                                <select class="select2" name="nama_pelanggar_select" id="nama_pelanggar_select">
                                    <option value="" disabled selected>Pilih nama Pelanggar</option>
                                    <?php echo $strPelanggar;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label"></label>
                            <div class="col-sm-10">
                                <a id="add_nama_pelanggar" data-toggle="modal" data-target="#namaPelanggarModal" href='#'>Tambah nama</a>
                                <!-- <button type="button" id="add_nama_pelanggar" data-toggle="modal" data-target="#namaPelanggarModal" class='btn btn-success' href='#'>
                                    Tambah Nama                                            
                                </button> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">No. KTP<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" id="no_ktp_front" name="no_ktp_front" placeholder="Masukkan nomor KTP pelanggar"></p>
                            </div>
                        </div><div class="form-group row">
                            <label class="col-sm-2 form-control-label">Pekerjaan<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="Masukkan pekerjaan pelanggar"></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Perusahaan<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <select class="select2" name="cmbPerusahaan" id="cmbPerusahaan">
                                    <option value="" disabled selected>Pilih Perusahaan</option>
                                    <?PHP echo $strCmbPJ;?>
                                </select>                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Lokasi<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <select class="select2" name="cmbLokasi" id="cmbLokasi">
                                        <option value="" disabled selected>Pilih lokasi pelanggaran</option>
                                        <?PHP echo $strCmbLokasi;?>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Jenis Pelanggaran<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <select class="select2" name="cmbPelanggaran" id="cmbPelanggaran">
                                    <option value="" disabled selected>Pilih jenis pelanggaran</option>
                                    <?PHP echo $strCmbPelanggaran;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <input type="hidden" name="operation" id="operation" value="Tambah"/>
                                    <button type="submit" class="btn" name="btnSimpan" id="btnSimpan">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="namaPelanggarModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="nama_pelanggar_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Nama Pelanggar</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="Nama Pelanggar">Nama Pelanggar<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="nama_pelanggar" 
                                            id="nama_pelanggar" 
                                            placeholder="Masukkan nama Pelanggar">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="no_ktp">No. KTP<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="no_ktp" 
                                            id="no_ktp" 
                                            placeholder="Masukkan nomor KTP Pelanggar">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="lokasi_id" id="lokasi_id" />
                            <input type="hidden" name="operations" id="operations" value="Tambah"/>
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Tambah"/>
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
    
    <script src="js/lib/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="js/lib/moment/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="js/lib/flatpickr/flatpickr.min.js"></script>
	<script src="js/lib/clockpicker/bootstrap-clockpicker.min.js"></script>
	<script src="js/lib/clockpicker/bootstrap-clockpicker-init.js"></script>
	<script src="js/lib/daterangepicker/daterangepicker.js"></script>
	<script src="js/lib/bootstrap-select/bootstrap-select.min.js"></script>

    <script src="js/jquery-validation-1.19.2/dist/jquery.validate.min.js"></script>

    <script src="js/app.js"></script>

    <script>
        $('#daterange3').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD MMM YYYY'
            }
        });
        $(document).ready(function() {
            $(document).on('click', '#add_nama_pelanggar', function(event){
                $('#namaPelanggarModal').modal('show')
                $('#nama_pelanggar').val("")
                $('#no_ktp').val("")
            })
            
            $('#nama_pelanggar_form').validate({
                rules: {
                    nama_pelanggar: {
                        required: true,
                    },
                    no_ktp: {
                        required: true,
                        number: true
                        // minlength: 16
                    }
                },
                messages: {
                    nama_pelanggar: "Mohon isi nama Pelanggar",
                    no_ktp: {
                        required: "Mohon isi nomor KTP",
                        number: "Mohon isi dengan angka saja",
                        minlength: jQuery.validator.format("Nomor KTP harus diisi dengan {0} digit angka!")
                    }
                },
                submitHandler: function (form) {
                    var $form = $(form)

                    var $inputs = $form.find("input, select, button, textarea")

                    var serializedData = $form.serialize()

                    $inputs.prop("disabled", true)

                    request = $.ajax({
                        url:"master/pelanggar/insert_update_pelanggar.php",
                        type:"POST",
                        data:serializedData
                    })

                    request.done(function (response, textStatus, jqXHR) {
                        // log a message to the console
                        
                        // console.log("Hooray, it worked!");
                        parsed = JSON.parse(response)
                        // console.log(parsed);
                        nama_pelanggar = parsed.nama_pelanggar
                        no_ktp = parsed.no_ktp
                        id_pelanggar = parsed.id_pelanggar
                        alert("Berhasil ditambahkan!");
                        $('#add--response').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>Well done!</strong> You successfully read this important alert message.</div>');
                    });

                    // callback handler that will be called on failure
                    request.fail(function (jqXHR, textStatus, errorThrown) {
                        // log the error to the console
                        console.error("The following error occured: " + textStatus, errorThrown);
                    });

                    // callback handler that will be called regardless
                    // if the request failed or succeeded
                    request.always(function (response) {
                        // reenable the inputs
                        $inputs.prop("disabled", false);
                        $('#nama_pelanggar_form')[0].reset()
                        $('#namaPelanggarModal').modal('hide')

                        parsed = JSON.parse(response)

                        id_pelanggar = parsed.id_pelanggar

                        $("#no_ktp_front").val(no_ktp)

                        $.ajax({
                            url:"master/pelanggar/fetch_after_insert_pelanggar.php",
                            method:"POST",
                            data:{id_pelanggar:id_pelanggar},
                            dataType:"json",
                            success:function(data){
                                $('#nama_pelanggar_select').empty()
                                $("#nama_pelanggar_select").append("<option value=''>Pilih nama Pelanggar</option>");
                                data.forEach(function(type){
                                    $("#nama_pelanggar_select").append("<option value="+type.id_pelanggar+" "+ (type.id_pelanggar === id_pelanggar ? 'selected="selected"' : '') +" >"+type.nama_pelanggar+"</option>");
                                });
                            }
                        })
                    });
                }
            });

            $('#nama_pelanggar_select').on('change', function(){
                id_pelanggar = $('option:selected', this).attr('value')

                $.ajax({
                    url:"master/pelanggar/fetch_from_select.php",
                    method:"POST",
                    data:{id_pelanggar:id_pelanggar},
                    dataType:"json",
                    success:function(data){
                        $("#no_ktp_front").val(data[0].no_ktp)
                    }
                })
            })

            $('#form_pelanggaran_personil').validate({
                rules: {
                    waktu: {
                        required: true,
                    },
                    nama_pelanggar_select: {
                        required: true,
                    },
                    no_ktp_front: {
                        required: true,
                    },
                    pekerjaan:{
                        required: true,
                    },
                    cmbPerusahaan: {
                        required: true,
                    },
                    cmbLokasi: {
                        required: true,
                    },
                    cmbPelanggaran:{
                        required: true,
                    }
                },
                messages: {
                    waktu: "Mohon isi kolom waktu",
                    nama_pelanggar_select: "Mohon isi kolom nama Pelanggar",
                    no_ktp_front: "Mohon isi kolom No. KTP Pelanggar",
                    pekerjaan: "Mohon isi kolom Pekerjaan",
                    cmbPerusahaan: "Mohon isi kolom Perusahaan",
                    cmbLokasi: "Mohon isi kolom Lokasi",
                    cmbPelanggaran: "Mohon isi kolom Jenis Pelanggaran"
                }
            })
        })
    </script>
</body>
</html>
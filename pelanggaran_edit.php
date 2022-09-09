<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    include "f_setter_getter_serial.php";

    $modeDebug = 1;
    $strMessage = "";
    
    $select_klasifikasi = "";

    $klasifikasi = 0;

    $txtKode = base64_decode($_GET['id']);

    // date_default_timezone_set('Asia/Jakarta');
    // $date = new DateTime();
    // $date = $date->getTimestamp();
    // $date = date("Y-m-d H:i:s", $date);

    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from pelanggaran_rpt where id_trx = '$txtKode'";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                // $txtNopol = $row[3];
                $id_trx = $row[0];
            
                $getTglPelanggaran = $row[2];
                $getTglPelanggaran = explode('-', $getTglPelanggaran);
                $getTglPelanggaran = $getTglPelanggaran[1] . "/" . $getTglPelanggaran[2] . "/" . $getTglPelanggaran[0];
                
                $waktu = $row[3];
                if($waktu == NULL){
                    $waktu = "--:--";
                }

                $klasifikasi = $row[1];
                $id_pelanggar = $row[17];
                $no_polisi = $row[4];
                $id_pj = $row[6];
                $id_lokasi = $row[11];
                $id_pelanggaran = $row[9];
                $pekerjaan = $row[22];
                $keterangan = $row[14];
            }
        }

        $queryFoto = "select foto_path from pelanggaran_foto_tab where id_trx = '$txtKode'";

        $resultFoto = $db->prepare($queryFoto);
        $resultFoto->execute();

        $numFoto = $resultFoto->rowCount();
        if($numFoto > 0) {
            while ($rowFoto = $resultFoto->fetch(PDO::FETCH_NUM)) {
                $foto_tilang_path[] = $rowFoto[0];
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
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_klasifikasi_tab";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $select_klasifikasi = $select_klasifikasi."<option value='$row[0]' ". ($row[0] == $klasifikasi ?  'selected="selected"' : '') . ">". $row[1] . " </option>";
            }
        }
        $db = null;
    } catch (Exception $e) {
        if($modeDebug==0){
            $select_klasifikasi = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        }else{
            $select_klasifikasi = $e->getMessage();
        }
    }

    $nama_pelanggar_select = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_pelanggar_tab";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $nama_pelanggar_select = $nama_pelanggar_select."<option value='$row[0]' ". ($row[0] == $id_pelanggar ?  'selected="selected"' : '') . " data-noktp='$row[2]'>$row[1]</option>";
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

    $perusahaan_pj_select = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_penanggungjawab_tab where status_pj = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $perusahaan_pj_select = $perusahaan_pj_select."<option value='$row[0]' ".($row[0] == $id_pj ?  'selected="selected"' : '')." >$row[1]</option>";
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

    $lokasi_select = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_lokasi_tab where status_lokasi = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $lokasi_select = $lokasi_select."<option value='$row[0]' ". ($row[0] == $id_lokasi ?  'selected="selected"' : '') ." >$row[1]</option>";
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

    $jenis_pelanggaran_select = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "SELECT a.id_pelanggaran, b.jenis, a.pelanggaran FROM gen_pelanggaran_tab a LEFT JOIN gen_jenis_pelanggaran_tab b ON a.id_jenis=b.id_jenis WHERE a.status_pelanggaran = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $jenis_pelanggaran_select = $jenis_pelanggaran_select."<option value='$row[0]' ". ($row[0] == $id_pelanggaran ?  'selected="selected"' : '') ." >$row[1] - $row[2]</option>";
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

    if (isset($_POST['simpan_pelanggaran']) and $_SERVER['REQUEST_METHOD'] == "POST") {
        

        try {
            $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

            // echo json_encode($_FILES);
            date_default_timezone_set('Asia/Jakarta');
            $date = new DateTime();
            $date = $date->getTimestamp();
            $date = date("Y-m-d H:i:s", $date);

            $tglPelanggaran = $_POST['tanggal'];
            $tglPelanggaran = explode('/', $tglPelanggaran);
            $tglPelanggaran = $tglPelanggaran[2] . "-" . $tglPelanggaran[0] . "-" . $tglPelanggaran[1];
            $waktu = $_POST['waktu'];
            $jenisTrx = $_POST['klasifikasi_select'];
            $id_pelanggar = $_POST['nama_pelanggar_select'];
            if($_POST['klasifikasi_select'] != 3){
                $txtNopol = $_POST['nopol_select'];
            }
            $txtKTP = $_POST['no_ktp_front'];
            $pekerjaan = $_POST['pekerjaan'];
            $cmbPJ = $_POST['perusahaan_select'];
            $cmbLokasi = $_POST['lokasi_select'];
            $cmbPelanggaran = $_POST['pelanggaran_select'];
            $txtKeterangan = $_POST['keterangan'];
            $picTilang = $_SESSION["sessidetilang"];
            $status = 'Draft';
            
            $db->beginTransaction();

            $sqlQuery = $db->prepare("
                                UPDATE 
                                    pelanggaran_tab 
                                SET 
                                    jenis_trx = :jenis_trx, 
                                    tgl_trx = :tgl_trx, 
                                    waktu = :waktu, 
                                    no_polisi = :no_polisi, 
                                    id_pj = :id_pj, 
                                    no_ktp = :no_ktp, 
                                    id_pelanggaran = :id_pelanggaran, 
                                    id_lokasi = :id_lokasi, 
                                    keterangan = :keterangan,
                                    pekerjaan = :pekerjaan,
                                    id_pelanggar = :id_pelanggar,
                                    updated_at = :updated_at
                                WHERE 
                                    id_trx=:id_trx
                                ");

            $sqlQuery->bindParam(':id_trx', $txtKode, PDO::PARAM_STR);
            $sqlQuery->bindParam(':jenis_trx', $jenisTrx, PDO::PARAM_STR);
            $sqlQuery->bindParam(':tgl_trx', $tglPelanggaran, PDO::PARAM_STR);
            $sqlQuery->bindParam(':waktu', $waktu, PDO::PARAM_STR);
            $sqlQuery->bindParam(':no_polisi', $txtNopol, PDO::PARAM_STR);
            $sqlQuery->bindParam(':id_pj', $cmbPJ, PDO::PARAM_STR);
            $sqlQuery->bindParam(':no_ktp', $txtKTP, PDO::PARAM_STR);
            $sqlQuery->bindParam(':id_pelanggaran', $cmbPelanggaran, PDO::PARAM_STR);
            $sqlQuery->bindParam(':id_lokasi', $cmbLokasi, PDO::PARAM_STR);
            $sqlQuery->bindParam(':keterangan', $txtKeterangan, PDO::PARAM_STR);
            $sqlQuery->bindParam(':pekerjaan', $pekerjaan, PDO::PARAM_STR);
            $sqlQuery->bindParam(':id_pelanggar', $id_pelanggar, PDO::PARAM_STR);
            $sqlQuery->bindParam(':updated_at', $date, PDO::PARAM_STR);

            $sqlQuery->execute();

            if($sqlQuery->rowCount() > 0){
                if($_FILES['foto_tilang']['name'][0] != ""){
                    if (array_key_exists('delete_file', $_POST)) {
                        $filename = $_POST['delete_file'];
                        
                        for($i=0; $i<count($filename); $i++){
                            // var_dump($filename[$i][$i]);
                            // exit;
                            if(file_exists($filename[$i])){
                                unlink($filename[$i]);
                            }
                        }
                    }
                    
                    $sqlDeleteFotoTilang = $db->prepare("delete from pelanggaran_foto_tab where id_trx=:id_trx");
                    
                    $sqlDeleteFotoTilang->bindParam(':id_trx', $txtKode, PDO::PARAM_STR);

                    $sqlDeleteFotoTilang->execute();

                    for($i = 0; $i<count($_FILES['foto_tilang']['name']); $i++){
                        $filetmp = $_FILES['foto_tilang']['tmp_name'][$i];
                        $filename = $_FILES['foto_tilang']['name'][$i];
                        $filetype = $_FILES['foto_tilang']['type'][$i];
                        $filepath = "foto_tilang/".$filename;
        
                        move_uploaded_file($filetmp, $filepath);
        
                        $sqlFotoTilang = $db->prepare("insert into pelanggaran_foto_tab (id_trx, foto_path) VALUES (:id_trx, :foto_path)");
                        
                        $sqlFotoTilang->bindParam(':id_trx', $txtKode, PDO::PARAM_STR);
                        $sqlFotoTilang->bindParam(':foto_path', $filepath, PDO::PARAM_STR);

                        $sqlFotoTilang->execute();
                    }
                }

            $db->commit();

            // echo json_encode($_POST);

            // header('location:pelanggaran_ab_edit.php?id='.base64_encode($txtKode));
            
            //     //header('location:home.php');
                header('location:pelanggaran_edit.php?id='.base64_encode($txtKode));
                $strMessage = "<div class='alert alert-success'><strong>Data berhasil disimpan</strong></div>";
            } else {
                $strMessage = "<div class='alert alert-error'><strong>Data gagal disimpan</strong></div>";
                // echo json_encode($strMessage);
            }
            
        }catch(PDOException $e){
            if($modeDebug==0){
                $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
            }else{
                $strMessage = $e->getMessage();
            }
            // echo json_encode($strMessage);
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
        <link rel="stylesheet" href="css/separate/pages/others.min.css">
        <link href="plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
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
                <?PHP include "menu_up.php";?>
            </div><!--.container-fluid-->
        </header><!--.site-header-->

        <div class="mobile-menu-left-overlay"></div>

        <?PHP include "menu_left.php";?>

        <div class="page-content">
            <div class="container-fluid">
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Form Edit Pelanggaran - <?php echo $id_trx; ?></h3> <?php echo $strMessage; ?>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="box-typical box-typical-padding">
                    <form id="frmLogin" class="sign-box" action="" method="post" enctype="multipart/form-data">
                        <h5 class="with-border">1. Tanggal, Waktu & Klasifikasi Pelanggaran</h5>
                        <p>
                            Tentukan tanggal, waktu dan klasifikasi pelanggaran yang terjadi.
                        </p>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label semibold">Tanggal<span class="required">*</span></label>
                            <div class="col-sm-4">
                                <div class='input-group date'>
                                    <input id="daterange3" type="text" name="tanggal" value="<?php echo $getTglPelanggaran; ?>" class="form-control">
                                    <span class="input-group-addon">
                                        <i class="font-icon font-icon-calend"></i>
                                    </span>
                                </div>
                            </div>

                            <label class="col-sm-2 form-control-label semibold">Waktu<span class="required">*</span></label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control clockpicker" value="<?php echo $waktu; ?>" id="waktu" name="waktu">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label semibold">Klasifikasi Pelanggaran<span class="required">*</span></label>
                            <div class="col-sm-10">                                
                                <select class="select2" name="klasifikasi_select" id="klasifikasi_select">
                                    <option value="" disabled selected>Pilih klasifikasi pelanggaran</option>
                                    <?PHP echo $select_klasifikasi;?>
                                </select>
                            </div>
                        </div>
                        <!-- <div id="pesan">
                            <div class="add-customers-screen tbl">
                                <div class="add-customers-screen-in">
                                    <div class="add-customers-screen-user">
                                        <i class="font-icon font-icon-user"></i>
                                    </div>
                                    <h2>Form detail pelanggaran</h2>
                                    <p class="lead color-blue-grey-lighter">Pilih klasifikasi pelanggaran <br/>untuk membuka form detail pelanggaran</p>
                                </div>
                            </div>
                        </div> -->
                        <div id="detail">
                            <h5 class="with-border">2. Detail Pelanggaran</h5>
                            <p>
                                Isi detail pelanggaran berdasarkan kolom informasi yang tampil di bawah ini.
                            </p>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Nama Pelanggar<span class="required">*</span></label>
                                <div class="col-sm-10">                                
                                    <select class="select2" name="nama_pelanggar_select" id="nama_pelanggar_select">
                                        <option value="" disabled selected>Pilih nama pelanggar</option>
                                        <?php echo $nama_pelanggar_select; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-10">
                                    <label class="form-control-label">
                                        <small class="text-muted">Belum ada nama pelanggar yang Anda cari? <a id="add_nama_pelanggar" data-toggle="modal" data-target="#namaPelanggarModal" href='#'>Tambah nama pelanggar</a></small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row" id="no_pol_div">
                                <label class="col-sm-2 form-control-label semibold">No. Polisi Kendaraan<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <select class="select2 form-control selectjs" name="nopol_select" id="nopol_select">
                                        <option value="<?php echo $no_polisi; ?>"><?php echo $no_polisi; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="no_pol_div_tambah">
                                <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-10">
                                    <label class="form-control-label">
                                        <small class="text-muted">Belum ada nomor polisi yang Anda cari? <a id="add_nomor_polisi" data-toggle="modal" data-target="#nomorPolisiModal" href='#'>Tambah nomor polisi kendaraan</a></small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-10">
                                    <div id="status"></div>
                                    <div id="status_kendaraan"></div>
                                    <!-- <p><span class="label label-default">Belum ada Surat Peringatan (SP)</span></p>
                                    <p><small class="text-muted"><span class="label label-info">Surat Peringatan (SP) 1</span> - Masa berlaku SP: 20 Juli 2020 </small></p>
                                    <p><small class="text-muted"><span class="label label-warning">Surat Peringatan (SP) 2</span> - Masa berlaku SP: 20 Juli 2020 </small></p>
                                    <p><small class="text-muted"><span class="label label-danger">Surat Peringatan (SP) 3</span> - Masa berlaku SP: 20 Juli 2020 </small></p> -->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">No. Identitas (KTP/SIM/Lainnya)<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="no_ktp_front" name="no_ktp_front" placeholder="Masukkan nomor identitas pelanggar">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Pekerjaan<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="Masukkan pekerjaan pelanggar" value="<?php echo $pekerjaan; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Perusahaan / Penanggung Jawab<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <select class="select2" name="perusahaan_select" id="perusahaan_select">
                                        <option value="" disabled selected>Pilih Perusahaan / Penanggung Jawab</option>
                                        <?php echo $perusahaan_pj_select; ?>
                                    </select>                                
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-10">
                                    <label class="form-control-label">
                                        <small class="text-muted">Belum ada nama perusahaan/penanggung jawab yang Anda cari? <a id="add_perusahaan" data-toggle="modal" data-target="#perusahaanModal" href='#'>Tambah nama perusahaan/penanggung jawab</a> </small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Lokasi<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <select class="select2" name="lokasi_select" id="lokasi_select">
                                        <option value="" disabled selected>Pilih lokasi pelanggaran</option>
                                        <?php echo $lokasi_select; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-10">
                                    <label class="form-control-label">
                                        <small class="text-muted">Belum ada nama lokasi yang Anda cari? <a id="add_lokasi" data-toggle="modal" data-target="#lokasiModal" href='#'>Tambah nama lokasi</a> </small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Jenis Pelanggaran<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <select class="select2" name="pelanggaran_select" id="pelanggaran_select">
                                        <option value="" disabled selected>Pilih jenis pelanggaran</option>
                                        <?php echo $jenis_pelanggaran_select; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleSelect" class="col-sm-2 form-control-label semibold">Keterangan<span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <textarea rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="Tuliskan keterangan tambahan mengenai pelanggaran yang terjadi"><?php echo $keterangan; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label semibold">Foto</label>
                                <div class="col-sm-10">
                                    <?php 
                                        if(isset($foto_tilang_path)) {
                                            for($i=0; $i<count($foto_tilang_path); $i++) {
                                                echo "<img src = '$foto_tilang_path[$i]' width='40%'>"; 
                                                echo "<input type='hidden' value='".$foto_tilang_path[$i]."' name='delete_file[]' />";
                                            }
                                        }
                                    ?>
                                    <p class="form-control-static">Kosongkan jika tidak ada foto yang ingin di edit/ubah</p>
                                    <input type="file" id="foto_tilang" type="file" name="foto_tilang[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg. Size max 5 Mb. File max. 15">
                                    <p class="form-control-static">Perhatian: dengan memilih foto di form edit ini, maka foto sebelumnya akan terhapus</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 form-control-label">
                                    <p>Dengan menyimpan input edit pelanggaran ini, maka statusnya 'Draft'</p>
                                </label>
                            </div>
                            <div class="tbl">
                                <div class="tbl-row">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-success" name="simpan_pelanggaran" id="simpan_pelanggaran">Simpan Data Pelanggaran</button>
                                    </div>
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
                                        <label class="form-label " for="no_identitas">No. Identitas (KTP/SIM/Lainnya)<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="no_identitas" 
                                            id="no_identitas" 
                                            placeholder="Masukkan nomor identitas Pelanggar">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="operations" id="operations" value="Tambah"/>
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Tambah"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="nomorPolisiModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="nomor_polisi_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Nomor Polisi Kendaraan</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="Nomor Polisi Kendaraan">Nomor Polisi Kendaraan<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="no_polisi" 
                                            id="no_polisi" 
                                            placeholder="Masukkan nomor polisi kendaraan">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="no_ktp">Nomor STNK Kendaraan <small class="text-muted">Isi dengan strip ( - ), jika tidak ada nomor STNK</small></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="no_stnk" 
                                            id="no_stnk" 
                                            placeholder="Masukkan nomor STNK Kendaraan">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            
                            <input type="hidden" name="operations" id="operations" value="Tambah"/>
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Tambah"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="perusahaanModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="nama_perusahaan_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Nama Perusahaan / Penanggung Jawab</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label" for="Nama Perusahaan">Nama Perusahaan / Penanggung Jawab<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="nama_penanggungjawab" 
                                            id="nama_penanggungjawab" 
                                            placeholder="Masukkan nama perusahaan / penanggung jawab">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="no_ktp">Email Perusahaan / Penanggung Jawab<span class="required">*</span><small class="text-muted">Harus diisi, untuk keperluan pengiriman SP.</small></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="email_penanggungjawab" 
                                            id="email_penanggungjawab" 
                                            placeholder="Masukkan email perusahaan / penanggung jawab">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="operation" id="operation" value="Tambah"/>
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Tambah"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="lokasiModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="nama_lokasi_form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Nama Lokasi</h4>
                            <button type="button" class="close" data-dismiss="modal"> &times; </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <fieldset class="form-group">
                                        <label class="form-label " for="Nama Lokasi">Nama Lokasi<span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="nama_lokasi" 
                                            id="nama_lokasi" 
                                            placeholder="Masukkan nama lokasi">
                                    </fieldset>
                                </div>
                            </div><!--.row-->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="operation" id="operation" value="Tambah"/>
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
        <script src="plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

        <script src="js/jquery-validation-1.19.2/dist/jquery.validate.min.js"></script>

        <script src="js/app.js"></script>

        <script>
            $('#daterange3').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                // locale: {
                //     format: 'DD MMM YYYY'
                // }
            })

            $("#foto_tilang").fileinput({
                showUpload: false,
                showDelete: false,
                showCaption: true,
                theme: 'fa',
                allowedFileExtensions: ['jpg', 'jpeg'],
                maxFileSize:5000,
                maxFileCount: 15,
                slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_')
                },
                dropZoneEnabled: false,
            })

            var no_ktp = $('select[name=nama_pelanggar_select] option').filter(':selected').attr("data-noktp")
            $('#no_ktp_front').val(no_ktp)

            $(document).ready(function(){
                // $('#pesan').show()
                // $('#detail').hide()

                var id_klasifikasi = $('#klasifikasi_select').val()
                console.log(id_klasifikasi)
                if (id_klasifikasi == 3) {
                    $('#nopol_select').empty()
                    $('#no_pol_div').hide()
                    $('#no_pol_div_tambah').hide()
                }

                var changed_pelanggar = 0
                initialLoad(id_klasifikasi)

                $('#klasifikasi_select').change(function(){
                    id_klasifikasi = $(this).val()
                    // console.log(changed_pelanggar)

                    if(changed_pelanggar==1){
                        console.log(changed_pelanggar)
                        $('#nama_pelanggar_select').val('');
                        $('#nama_pelanggar_select').trigger('change');
                        $("#no_ktp_front").val('')
                        $('#status').empty()
                        $('#status_kendaraan').empty()
                    }
                    
                    $('#status').empty()
                    $('#status_kendaraan').empty()
                    // $('#pesan').hide()
                    // $('#detail').show()
                    // console.log(id_klasifikasi);

                    if(id_klasifikasi == 3){ 
                        // $('select[name="state"]').empty()
                        $('#nopol_select').empty()
                        $('#no_pol_div').hide()
                        $('#no_pol_div_tambah').hide()

                        id_pelanggar = $('option:selected', this).attr('value')

                        console.log(id_pelanggar)

                    } else {
                        $('#nopol_select').val("")
                        $('#no_pol_div').show()
                        $('#no_pol_div_tambah').show()

                        if(id_klasifikasi == 1){
                            $(".selectjs").select2({
                                placeholder: "Pilih nomor polisi kendaraan angkutan barang...",
                                minimumInputLength: 2,
                                ajax: {
                                    url: "master/kendaraan/fetch_kendaraan_pocis.php",
                                    type: "GET",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params){
                                        return{
                                            q: params.term,
                                            page: params.page
                                        }
                                    },
                                    processResults: function(data, params){
                                        params.page = params.page || 1;

                                        return{
                                            results: $.map(data, function(obj){
                                                return { id: obj.police_number, text: obj.police_number }
                                            }),
                                            pagination: {
                                                more: (params.page * 3) < data.total_count
                                            }
                                        }
                                    },
                                    cache: true
                                },
                            })
							$('#no_pol_div_tambah').hide()
                        } else if (id_klasifikasi == 2) {
                            $(".selectjs").select2({
                                placeholder: "Pilih nomor polisi kendaraan non-angkutan barang...",
                                minimumInputLength: 2,
                                ajax: {
                                    url: "master/kendaraan/fetch_kendaraan_vpacs.php",
                                    type: "GET",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params){
                                        return{
                                            q: params.term,
                                        }
                                    },
                                    processResults: function(data){
                                        return{
                                            results: $.map(data, function(obj){
                                                return { id: obj.police_number, text: obj.police_number }
                                            })
                                        }
                                    },
                                    cache: true
                                },
                            })
                        } else if (id_klasifikasi == 4) {
                            $(".selectjs").select2({
                                placeholder: "Pilih nomor polisi kendaraan...",
                                minimumInputLength: 2,
                                ajax: {
                                    url: "master/kendaraan/fetch_kendaraan_k3lh.php",
                                    type: "GET",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params){
                                        return{
                                            q: params.term,
                                        }
                                    },
                                    processResults: function(data){
                                        return{
                                            results: $.map(data, function(obj){
                                                return { id: obj.police_number, text: obj.police_number }
                                            })
                                        }
                                    },
                                    cache: true
                                },
                            })
                        }
                    }
                })

                $('#nama_pelanggar_select').on('change', function(){
                    changed_pelanggar = 1
                    $('#status').empty()
                    id_pelanggar = $('option:selected', this).attr('value')
                    if(id_pelanggar == ''){
                        console.log("true")
                        $('#status').empty()
                    }

                    $.ajax({
                        url:"master/pelanggar/fetch_from_select.php",
                        method:"POST",
                        data:{id_pelanggar:id_pelanggar},
                        dataType:"json",
                        success:function(data){
                            $("#no_ktp_front").val(data[0].no_ktp)
                        }
                    })
                    if(id_klasifikasi == 3){
                        // console.log(id_klasifikasi)
                        $.ajax({
                            url:"transaksi/fetch_jumlah_sp.php",
                            method:"POST",
                            data:{
                                id_pelanggar:id_pelanggar,
                                id_klasifikasi:id_klasifikasi
                            },
                            dataType:"json",
                            success:function(data){
                                // console.log(data)
                                if(data[0] == 0){
                                    $('#status').html('<p><span class="label label-default">Belum ada Surat Peringatan (SP)</span></p>');
                                } else if (data[0] == 1) {
                                    $('#status').html('<p><small class="text-muted"><span class="label label-info">Surat Peringatan (SP) 1</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                } else if (data[0] == 2) {
                                    $('#status').html('<p><small class="text-muted"><span class="label label-warning">Surat Peringatan (SP) 2</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                } else if (data[0] == 3) {
                                    $('#status').html('<p><small class="text-muted"><span class="label label-danger">Surat Peringatan (SP) 3</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                }
                            }
                        })
                    }
                })

                $("#nopol_select").on('change', function(){
                    if(id_klasifikasi != 3){
                        console.log("AJAX GET KENDARAAN")
                        id_no_pol_pelanggar = $('option:selected', this).attr('value')

                        $.ajax({
                            url:"transaksi/fetch_jumlah_sp.php",
                            method:"POST",
                            data:{
                                id_no_pol_pelanggar:id_no_pol_pelanggar,
                                id_klasifikasi:id_klasifikasi
                            },
                            dataType:"json",
                            success:function(data){
                                // console.log(data)
                                if(data[0] == 0){
                                    $('#status_kendaraan').html('<p><span class="label label-default">Belum ada Surat Peringatan (SP)</span></p>');
                                } else if (data[0] == 1) {
                                    $('#status_kendaraan').html('<p><small class="text-muted"><span class="label label-info">Surat Peringatan (SP) 1</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                } else if (data[0] == 2) {
                                    $('#status_kendaraan').html('<p><small class="text-muted"><span class="label label-warning">Surat Peringatan (SP) 2</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                } else if (data[0] == 3) {
                                    $('#status_kendaraan').html('<p><small class="text-muted"><span class="label label-danger">Surat Peringatan (SP) 3</span> - Masa berlaku SP: ' + data[1] + ' </small></p>');
                                }
                            }
                        })
                    }
                })

                $('#nama_pelanggar_form').validate({
                    rules: {
                        nama_pelanggar: {
                            required: true,
                        },
                        no_identitas: {
                            required: true,
                        }
                    },
                    messages: {
                        nama_pelanggar: "Mohon isi nama Pelanggar",
                        no_identitas: {
                            required: "Mohon isi nomor identitas pelanggar",
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
                                    $("#nama_pelanggar_select").append("<option value='0'>Pilih nama Pelanggar</option>");
                                    data.forEach(function(type){
                                        $("#nama_pelanggar_select").append("<option value="+type.id_pelanggar+" "+ (type.id_pelanggar === id_pelanggar ? 'selected="selected"' : '') +" >"+type.nama_pelanggar+"</option>");
                                    });
                                }
                            })
                        });
                    }
                });
                
                $('#nomor_polisi_form').validate({
                    rules: {
                        no_polisi: {
                            required: true,
                        },
                        no_stnk: {
                            required: true,
                        }
                    },
                    messages: {
                        no_polisi: "Mohon isi nomor polisi kendaraan",
                        no_stnk: {
                            required: "Mohon isi nomor STNK. Jika tidak memiliki STNK, isi dengan tanda strip (-)",
                        }
                    },
                    submitHandler: function (form) {
                        var $form = $(form)

                        var $inputs = $form.find("input, select, button, textarea")

                        var serializedData = $form.serialize()

                        $inputs.prop("disabled", true)

                        request = $.ajax({
                            url:"master/kendaraan/insert_update_kendaraan.php",
                            type:"POST",
                            data:serializedData
                        })

                        request.done(function (response, textStatus, jqXHR) {
                            // log a message to the console
                            
                            // console.log("Hooray, it worked!");
                            parsed = JSON.parse(response)
                            // console.log(parsed);
                            no_polisi = parsed.no_polisi
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
                            $('#nomor_polisi_form')[0].reset()
                            $('#nomorPolisiModal').modal('hide')

                            parsed = JSON.parse(response)

                            no_polisi = parsed.no_polisi

                            $("#nopol_select").append("<option value="+no_polisi+" "+ (no_polisi === no_polisi ? 'selected="selected"' : '') +" >"+no_polisi+"</option>");
                        });
                    }
                });

                $('#nama_perusahaan_form').validate({
                    rules: {
                        nama_penanggungjawab: {
                            required: true,
                        },
                        email_penanggungjawab: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        nama_penanggungjawab: "Mohon isi nama Perusahaan/Penanggung Jawab",
                        email_penanggungjawab: {
                            required: "Mohon isi email Perusahaan/Penanggung Jawab",
                            email: "Alamat e-mail tidak valid"
                        }
                    },
                    submitHandler: function (form) {
                        var $form = $(form)

                        var $inputs = $form.find("input, select, button, textarea")

                        var serializedData = $form.serialize()

                        $inputs.prop("disabled", true)

                        request = $.ajax({
                            url:"master/penanggungjawab/insert_update_penanggungjawab.php",
                            type:"POST",
                            data:serializedData
                        })

                        request.done(function (response, textStatus, jqXHR) {
                            // log a message to the console
                            
                            // console.log("Hooray, it worked!");
                            parsed = JSON.parse(response)
                            console.log(parsed);

                            id_penanggungjawab = parsed.id_penanggungjawab
                            nama_penanggungjawab = parsed.nama_penanggungjawab

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
                            $('#nama_perusahaan_form')[0].reset()
                            $('#perusahaanModal').modal('hide')

                            parsed = JSON.parse(response)

                            id_penanggungjawab = parsed.id_penanggungjawab

                            $.ajax({
                                url:"master/penanggungjawab/fetch_after_insert_penanggungjawab.php",
                                method:"POST",
                                data:{id_penanggungjawab:id_penanggungjawab},
                                dataType:"json",
                                success:function(data){
                                    console.log(data)
                                    $('#perusahaan_select').empty()
                                    $("#perusahaan_select").append("<option value=''>Pilih Perusahaan / Penanggung Jawab</option>");
                                    data.forEach(function(type){
                                        $("#perusahaan_select").append("<option value="+type.id_pj+" "+ (type.id_pj === id_penanggungjawab ? 'selected="selected"' : '') +" >"+type.nama_pj+"</option>");
                                    });
                                }
                            })
                        });
                    }
                });
                
                $('#nama_lokasi_form').validate({
                    rules: {
                        nama_lokasi: {
                            required: true,
                        }
                    },
                    messages: {
                        nama_lokasi: "Mohon isi nama lokasi"
                    },
                    submitHandler: function (form) {
                        var $form = $(form)

                        var $inputs = $form.find("input, select, button, textarea")

                        var serializedData = $form.serialize()

                        $inputs.prop("disabled", true)

                        request = $.ajax({
                            url:"master/lokasi/insert_update_lokasi.php",
                            type:"POST",
                            data:serializedData
                        })

                        request.done(function (response, textStatus, jqXHR) {
                            // log a message to the console
                            
                            // console.log("Hooray, it worked!");
                            parsed = JSON.parse(response)
                            console.log(parsed);

                            id_lokasi = parsed.id_lokasi
                            nama_lokasi = parsed.nama_lokasi

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
                            $inputs.prop("disabled", false)
                            $('#nama_lokasi_form')[0].reset()
                            $('#lokasiModal').modal('hide')

                            parsed = JSON.parse(response)

                            id_lokasi = parsed.id_lokasi

                            $.ajax({
                                url:"master/lokasi/fetch_after_insert_lokasi.php",
                                method:"POST",
                                data:{id_lokasi:id_lokasi},
                                dataType:"json",
                                success:function(data){
                                    console.log(data)
                                    $('#lokasi_select').empty()
                                    $("#lokasi_select").append("<option value=''>Pilih Perusahaan / Penanggung Jawab</option>");
                                    data.forEach(function(type){
                                        $("#lokasi_select").append("<option value="+type.id_lokasi+" "+ (type.id_lokasi === id_lokasi ? 'selected="selected"' : '') +" >"+type.lokasi+"</option>")
                                    })
                                }
                            })
                        })
                    }
                })
                $('#frmLogin').validate({
                    rules: {
                        daterange3: {
                            required: true,
                        },
                        waktu: {
                            required: true,
                        },
                        klasifikasi_select: {
                            required: true,
                        },
                        nama_pelanggar_select: {
                            required: true,
                        },
                        nopol_select: {
                            required: true,
                        },
                        no_ktp_front: {
                            required: true,
                        },
                        pekerjaan: {
                            required: true,
                        },
                        perusahaan_select: {
                            required: true,
                        },
                        lokasi_select: {
                            required: true,
                        },
                        pelanggaran_select: {
                            required: true,
                        },
                    },
                    messages: {
                        daterange3: "Mohon isi tanggal",
                        waktu: "Mohon isi waktu",
                        klasifikasi_select: "Mohon isi klasifikasi pelanggaran",
                        nama_pelanggar_select: "Mohon isi nama pelanggar",
                        nopol_select: "Mohon isi nomor polisi",
                        no_ktp_front: "Mohon isi nomor KTP",
                        pekerjaan: "Mohon isi pekerjaan",
                        perusahaan_select: "Mohon isi perusahaan / penanggung jawab",
                        lokasi_select: "Mohon isi lokasi pelanggaran",
                        pelanggaran_select: "Mohon isi jenis pelanggaran",
                    },
                })

                $("#keterangan").rules( "add", {
                    required: true,
                    messages: {
                        required: "Mohon isi keterangan",
                    }
                });

                function initialLoad(id){
                    var id_klasifikasi = id;
                    if(id_klasifikasi == 1){
                        $(".selectjs").select2({
                            placeholder: "Pilih nomor polisi kendaraan angkutan barang...",
                            minimumInputLength: 2,
                            ajax: {
                                url: "master/kendaraan/fetch_kendaraan_pocis.php",
                                type: "GET",
                                dataType: "json",
                                delay: 250,
                                data: function(params){
                                    return{
                                        q: params.term,
                                        page: params.page
                                    }
                                },
                                processResults: function(data, params){
                                    params.page = params.page || 1;

                                    return{
                                        results: $.map(data, function(obj){
                                            return { id: obj.police_number, text: obj.police_number }
                                        }),
                                        pagination: {
                                            more: (params.page * 3) < data.total_count
                                        }
                                    }
                                },
                                cache: true
                            },
                        })
						$('#no_pol_div_tambah').hide()
                    } else if (id_klasifikasi == 2) {
                        $(".selectjs").select2({
                            placeholder: "Pilih nomor polisi kendaraan non-angkutan barang...",
                            minimumInputLength: 2,
                            ajax: {
                                url: "master/kendaraan/fetch_kendaraan_vpacs.php",
                                type: "GET",
                                dataType: "json",
                                delay: 250,
                                data: function(params){
                                    return{
                                        q: params.term,
                                    }
                                },
                                processResults: function(data){
                                    return{
                                        results: $.map(data, function(obj){
                                            return { id: obj.police_number, text: obj.police_number }
                                        })
                                    }
                                },
                                cache: true
                            },
                        })
                    } else if (id_klasifikasi == 4) {
                        $(".selectjs").select2({
                            placeholder: "Pilih nomor polisi kendaraan...",
                            minimumInputLength: 2,
                            ajax: {
                                url: "master/kendaraan/fetch_kendaraan_k3lh.php",
                                type: "GET",
                                dataType: "json",
                                delay: 250,
                                data: function(params){
                                    return{
                                        q: params.term,
                                    }
                                },
                                processResults: function(data){
                                    return{
                                        results: $.map(data, function(obj){
                                            return { id: obj.police_number, text: obj.police_number }
                                        })
                                    }
                                },
                                cache: true
                            },
                        })
                    }
                }
            })

        </script>
    </body>
</html>
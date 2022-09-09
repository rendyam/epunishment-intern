<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    include "f_setter_getter_serial.php";
    
    $modeDebug = 1;
            
    $strMessage = "";
    $noPolisi = "";
    $noSTNK = "";
    $jmlSP = "";
    $masaBerlaku = "";
    $picTilang = "";
    
    if (isset($_POST['btnPilih']) and $_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            $noPolisi = $_POST['cmbNopol'];
            
            if($noPolisi==""){
                header('location:pelanggaran_ab.php');
            }else{
                try {
                    $db = new PDO("mysql:host=$hostPocis;dbname=$dbnamePocis", $dbuserPocis, $dbpassPocis, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

                    $query = "SELECT a.stnk_no from view_map_rfid_trucks a WHERE a.police_number = '$noPolisi' and a.status = 'ACTIVE'";

                    $result = $db->prepare($query);
                    $result->execute();

                    $num = $result->rowCount();

                    if($num > 0) {
                        while ($row = $result->fetch(PDO::FETCH_NUM)) {
                            $noSTNK = $row[0];
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
                    $db = new PDO("mysql:host=$host3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

                    $query = "SELECT a.jumlah_sp_aktif, a.masa_berlaku from pelanggaran_rpt a WHERE a.no_polisi = '$noPolisi'";

                    $result = $db->prepare($query);
                    $result->execute();

                    $num = $result->rowCount();

                    if($num > 0) {
                        while ($row = $result->fetch(PDO::FETCH_NUM)) {
                            $jmlSP = $row[0];
                            $masaBerlaku = $row[1];
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
            }
        }catch(PDOException $e){
            if($modeDebug==0){
                $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
            }else{
                $strMessage = $e->getMessage();
            }
        }
    }
    
    if (isset($_POST['btnSimpan']) and $_SERVER['REQUEST_METHOD'] == "POST") {
        date_default_timezone_set('Asia/Jakarta');
        $date = new DateTime();
        $date = $date->getTimestamp();
        $date = date("Y-m-d H:i:s", $date);

        try {
            $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            
            $txtNopol = $_POST['txtNopol'];
            
            $tglPelanggaran =$_POST['tglPelanggaran'];
            $tglPelanggaran = explode('/', $tglPelanggaran);
            $tglPelanggaran = $tglPelanggaran[2] . "-" . $tglPelanggaran[0] . "-" . $tglPelanggaran[1];
            
            $txtSTNK = $_POST['txtSTNK'];
            $cmbPJ = $_POST['cmbPJ'];
            $txtSopir = $_POST['txtSopir'];
            $txtKTP = $_POST['txtKTP'];
            $txtSIM = $_POST['txtSIM'];
            $cmbPelanggaran = $_POST['cmbPelanggaran'];
            $cmbLokasi = $_POST['cmbLokasi'];
            $txtKegiatan = $_POST['txtKegiatan'];
            $txtKeterangan = $_POST['txtKeterangan'];
            $picTilang = $_SESSION["sessidetilang"];

            if($txtNopol=="" || $tglPelanggaran=="" || $txtSTNK=="" || $cmbPJ=="" || $txtSopir=="" || $txtKTP=="" || $txtSIM=="" || $cmbPelanggaran=="" || $cmbLokasi=="" || $txtKegiatan=="" || $txtKeterangan==""){
                $strMessage = "<div class='alert alert-error'><strong>Kolom dengan tanda bintang (*) wajib diisi</strong></div>";
            }else{
                $db->beginTransaction();
                
                $jenisTrx = 1;
                $txtKode = getNomorBerikutnya("TLG");
                
                $sqlQuery = $db->prepare("insert into pelanggaran_tab(id_trx, jenis_trx, tgl_trx, no_polisi, no_stnk, id_pj, sopir, no_ktp, no_sim, id_pelanggaran, id_lokasi, kegiatan, keterangan, pic_tilang) values(:id_trx, :jenis_trx, :tgl_trx, :no_polisi, :no_stnk, :id_pj, :sopir, :no_ktp, :no_sim, :id_pelanggaran, :id_lokasi, :kegiatan, :keterangan, :pic_tilang)");

                $sqlQuery->bindParam(':id_trx', $txtKode, PDO::PARAM_STR);
                $sqlQuery->bindParam(':jenis_trx', $jenisTrx, PDO::PARAM_STR);
                $sqlQuery->bindParam(':tgl_trx', $tglPelanggaran, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_polisi', $txtNopol, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_stnk', $txtSTNK, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_pj', $cmbPJ, PDO::PARAM_STR);
                $sqlQuery->bindParam(':sopir', $txtSopir, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_ktp', $txtKTP, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_sim', $txtSIM, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_pelanggaran', $cmbPelanggaran, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_lokasi', $cmbLokasi, PDO::PARAM_STR);
                $sqlQuery->bindParam(':kegiatan', $txtKegiatan, PDO::PARAM_STR);
                $sqlQuery->bindParam(':keterangan', $txtKeterangan, PDO::PARAM_STR);
                $sqlQuery->bindParam(':pic_tilang', $picTilang, PDO::PARAM_STR);

                $sqlQuery->execute();

                $db->commit();

                if($sqlQuery->rowCount() > 0){
                    if($_FILES['foto_tilang']['name'][0] != ""){
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
                    //function blacklist
                    $queryBlacklist = "SELECT a.jumlah_sp_aktif, a.masa_berlaku from pelanggaran_rpt a WHERE a.no_polisi = '$txtNopol'";

                    $result = $db->prepare($queryBlacklist);
                    $result->execute();

                    $num = $result->rowCount();

                    if($num >= 3) {
                        $stsBlok = 1;

                        $sqlQueryBlacklist = $db->prepare("insert into blacklist_tab(no_polisi, tanggal_blok, status_blok) values(:no_polisi, :tanggal_blok, :status_blok)");

                        $sqlQueryBlacklist->bindParam(':no_polisi', $txtNopol, PDO::PARAM_STR);
                        $sqlQueryBlacklist->bindParam(':tanggal_blok', $date, PDO::PARAM_STR);
                        $sqlQueryBlacklist->bindParam(':status_blok', $stsBlok, PDO::PARAM_STR);

                        $sqlQueryBlacklist->execute();
                    }

                    setNomorBerikutnya("TLG");

                    header('location:pelanggaran_ab_edit.php?id='.base64_encode($txtKode));

                    //header('location:home.php');
                    //$strMessage = "<div class='alert alert-success'><strong>Data berhasil disimpan</strong></div>";
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

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <?PHP
        include "header.php";
    ?>
    
    <link rel="stylesheet" href="css/separate/vendor/bootstrap-daterangepicker.min.css">
    <link href="plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
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
                    <form id="frmLogin" class="sign-box" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <input type="text" class="form-control" name="txtPPJ" id="txtPPJ" value="<?PHP echo $txtKode;?>" hidden>
                            <label class="col-sm-2 form-control-label">Surat Peringatan (SP) Aktif</label>
                            <div class="col-sm-10">
                            <?php if($jmlSP == ''){ ?>
                                <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="Belum ada SP" hidden><input type="text" class="form-control" value="Belum ada SP" disabled></p>
                            <?php } else {?>
                                        <?php if($jmlSP == 2) {?>
                                        <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?php echo $jmlSP; ?>" hidden><input type="text" class="form-control" value="<?php echo $jmlSP; ?> (Perhatian: hampir mencapai batas maksimal SP 3)" disabled></p>
                                        <?php } else if($jmlSP == 3) { ?>
                                        <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?php echo $jmlSP; ?>" hidden><input type="text" class="form-control" value="<?php echo $jmlSP; ?> (Perhatian: Kendaraan sudah mencapai batas maksimal SP 3!)" disabled></p>
                                        <?php } else { ?>
                                        <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?php echo $jmlSP; ?>" hidden><input type="text" class="form-control" value="<?php echo $jmlSP; ?>" disabled></p>
                                        <?php } ?>
                            <?php } ?>
                            </div>
                            <label class="col-sm-2 form-control-label">Masa Berlaku SP</label>
                            <div class="col-sm-10">
                            <?php if($masaBerlaku == ''){ ?>
                                <p class="form-control-static"><input type="text" class="form-control" name="txtMasaBerlaku" id="txtMasaBerlaku" value="Belum ada SP" hidden><input type="text" class="form-control" value="Belum ada SP" disabled></p>
                            <?php } else {?>
                                <p class="form-control-static"><input type="text" class="form-control" name="txtMasaBerlaku" id="txtMasaBerlaku" value="<?php echo $masaBerlaku; ?>" hidden><input type="text" class="form-control" value="<?php echo $masaBerlaku; ?>" disabled></p>
                            <?php } ?>
                            </div>
                            <label class="col-sm-2 form-control-label">Pilih No Polisi</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?PHP echo $noPolisi;?>" hidden><input type="text" class="form-control" value="<?PHP echo $noPolisi;?>" disabled></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Tgl Pelanggaran *</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="input-group date">
                                        <input name="tglPelanggaran" id="tglPelanggaran" type="text" class="form-control">
                                        <div class="input-group-addon">
                                            <span class="font-icon font-icon-calend"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <label class="col-sm-2 form-control-label">No STNK *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSTNK" id="txtSTNK" value="<?PHP echo $noSTNK;?>"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Penanggungjawab *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><select class="select2" name="cmbPJ" id="cmbPJ">
                                    <option value=""></option>
                                    <?PHP echo $strCmbPJ;?>
                                </select></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Sopir *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSopir" id="txtSopir"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">No KTP *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtKTP" id="txtKTP"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">No SIM *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSIM" id="txtSIM"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Pelanggaran *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><select class="select2" name="cmbPelanggaran" id="cmbPelanggaran">
                                    <option value=""></option>
                                    <?PHP echo $strCmbPelanggaran;?>
                                </select></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Lokasi *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><select class="select2" name="cmbLokasi" id="cmbLokasi">
                                    <option value=""></option>
                                    <?PHP echo $strCmbLokasi;?>
                                </select></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Kegiatan *</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtKegiatan" id="txtKegiatan"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Keterangan *</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><textarea rows="2" class="form-control" name="txtKeterangan" id="txtKeterangan"></textarea></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Foto</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="file" id="foto_tilang" type="file" name="foto_tilang[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg. Size max 5 Mb. File max. 15"></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <button type="submit" class="btn" name="btnSimpan" id="btnSimpan">Simpan</button>
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
        
         <script type="text/javascript" src="js/lib/moment/moment-with-locales.min.js"></script>
    <script src="plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
	<script src="js/lib/daterangepicker/daterangepicker.js"></script>
        
        <script>
            $(function() {
                $('#tglPelanggaran').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true
                });
            });

            $("#foto_tilang").fileinput({
                showUpload: false,
                showDelete: false,
                showCaption: true,
                theme: 'fa',
                allowedFileExtensions: ['jpg', 'jpeg'],
                maxFileSize:5000,
                maxFileCount: 15,
                slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_');
                },
                dropZoneEnabled: false,
            });
	    </script>
<script src="js/app.js"></script>
</body>
</html>
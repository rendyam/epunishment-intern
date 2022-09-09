<?PHP
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }

    include "koneksi/connect-db.php";
    include "f_setter_getter_serial.php";
    
    $modeDebug = 1;
    
    
    $txtKode = base64_decode($_GET['id']);
    
    $strMessage = "";
    $noPolisi = "";
    $noSTNK = "";
    $jmlSP = "";
    $masaBerlaku = "";
    
    if (isset($_POST['btnPilih']) and $_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            $noPolisi = $_POST['cmbNopol'];
            
            if($noPolisi==""){
                header('location:pelanggaran_ab.php');
            }else{
                try {
                    $db = new PDO("mysql:host=$hostVPACS;dbname=$dbnameVPACS", $dbuserVPACS, $dbpassVPACS, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

                    $query = "SELECT a.stnk from tags a WHERE a.no_polisi = '$noPolisi' and a.status = 'V'";

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

            if($txtNopol=="" || $tglPelanggaran=="" || $txtSTNK=="" || $cmbPJ=="" || $txtSopir=="" || $txtKTP=="" || $txtSIM=="" || $cmbPelanggaran=="" || $cmbLokasi=="" || $txtKegiatan=="" || $txtKeterangan==""){
                $strMessage = "<div class='alert alert-error'><strong>Kolom dengan tanda bintang (*) wajib diisi</strong></div>";
            }else{
                $db->beginTransaction();
                
                $sqlQuery = $db->prepare("update pelanggaran_tab set tgl_trx=:tgl_trx, no_stnk=:no_stnk, id_pj=:id_pj, sopir=:sopir, no_ktp=:no_ktp, no_sim=:no_sim, id_pelanggaran=:id_pelanggaran, id_lokasi=:id_lokasi, kegiatan=:kegiatan, keterangan=:keterangan where id_trx=:id_trx");

                $sqlQuery->bindParam(':id_trx', $txtKode, PDO::PARAM_STR);
                $sqlQuery->bindParam(':tgl_trx', $tglPelanggaran, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_stnk', $txtSTNK, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_pj', $cmbPJ, PDO::PARAM_STR);
                $sqlQuery->bindParam(':sopir', $txtSopir, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_ktp', $txtKTP, PDO::PARAM_STR);
                $sqlQuery->bindParam(':no_sim', $txtSIM, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_pelanggaran', $cmbPelanggaran, PDO::PARAM_STR);
                $sqlQuery->bindParam(':id_lokasi', $cmbLokasi, PDO::PARAM_STR);
                $sqlQuery->bindParam(':kegiatan', $txtKegiatan, PDO::PARAM_STR);
                $sqlQuery->bindParam(':keterangan', $txtKeterangan, PDO::PARAM_STR);

                $sqlQuery->execute();

                $db->commit();

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
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from pelanggaran_tab where id_trx = '$txtKode'";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $txtNopol = $row[3];
            
                $tglPelanggaran = $row[2];
                $tglPelanggaran = explode('-', $tglPelanggaran);
                $tglPelanggaran = $tglPelanggaran[1] . "/" . $tglPelanggaran[2] . "/" . $tglPelanggaran[0];

                $txtSTNK = $row[4];
                $cmbPJ = $row[5];
                $txtSopir = $row[6];
                $txtKTP = $row[7];
                $txtSIM = $row[8];
                $cmbPelanggaran = $row[9];
                $cmbLokasi = $row[10];
                $txtKegiatan = $row[11];
                $txtKeterangan = $row[12];
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
    
    $strCmbPJ = "";
    
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "select * from gen_penanggungjawab_tab where status_pj = 1";
        
        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                if($row[0]==$cmbPJ){
                    $strCmbPJ = $strCmbPJ."<option value='$row[0]' selected>$row[1]</option>";
                }else{
                    $strCmbPJ = $strCmbPJ."<option value='$row[0]'>$row[1]</option>";
                }
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
                if($row[0]==$cmbPelanggaran){
                    $strCmbPelanggaran = $strCmbPelanggaran."<option value='$row[0]' selected>$row[1] - $row[2]</option>";
                }else{
                    $strCmbPelanggaran = $strCmbPelanggaran."<option value='$row[0]'>$row[1] - $row[2]</option>";
                }
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
                if($row[0]==$cmbLokasi){
                    $strCmbLokasi = $strCmbLokasi."<option value='$row[0]' selected>$row[1]</option>";
                }else{
                    $strCmbLokasi = $strCmbLokasi."<option value='$row[0]'>$row[1]</option>";
                }
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
                    <h2 class="with-border">Edit Pelanggaran Kendaraan Non Angkutan Barang</h2>
                    <h2 class="with-border">Nomor : <?PHP echo $txtKode;?></h2>
                    <form id="frmLogin" class="sign-box" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <input type="text" class="form-control" name="txtPPJ" id="txtPPJ" value="<?PHP echo $txtKode;?>" hidden>
                            <label class="col-sm-2 form-control-label">Surat Peringatan (SP) Aktif</label>
                            <div class="col-sm-10">
                            <?php if($jmlSP == ''){ ?>
                                <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="Belum ada SP" hidden><input type="text" class="form-control" value="Belum ada SP" disabled></p>
                            <?php } else {?>
                                <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?php echo $jmlSP; ?>" hidden><input type="text" class="form-control" value="<?php echo $jmlSP; ?>" disabled></p>
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
                                <p class="form-control-static"><input type="text" class="form-control" name="txtNopol" id="txtNopol" value="<?PHP echo $txtNopol;?>" hidden><input type="text" class="form-control" value="<?PHP echo $txtNopol;?>" disabled></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Tgl Pelanggaran *</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="input-group date">
                                        <input name="tglPelanggaran" id="tglPelanggaran" type="text" class="form-control" value="<?PHP echo $tglPelanggaran;?>">
                                        <div class="input-group-addon">
                                            <span class="font-icon font-icon-calend"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <label class="col-sm-2 form-control-label">No STNK *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSTNK" id="txtSTNK" value="<?PHP echo $txtSTNK;?>"></p>
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
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSopir" id="txtSopir" value="<?PHP echo $txtSopir;?>"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">No KTP *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtKTP" id="txtKTP" value="<?PHP echo $txtKTP;?>"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">No SIM *</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><input type="text" class="form-control" name="txtSIM" id="txtSIM" value="<?PHP echo $txtSIM;?>"></p>
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
                                <p class="form-control-static"><input type="text" class="form-control" name="txtKegiatan" id="txtKegiatan" value="<?PHP echo $txtKegiatan;?>"></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Keterangan *</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><textarea rows="2" class="form-control" name="txtKeterangan" id="txtKeterangan"><?PHP echo $txtKeterangan;?></textarea></p>
                            </div>
                            <label class="col-sm-2 form-control-label">Foto</label>
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
                                <p class="form-control-static"><input type="file" id="foto_tilang" type="file" name="foto_tilang[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg. Size max 5 Mb. File max. 15"></p>
                                <p class="form-control-static">Perhatian: dengan memilih foto di form edit ini, maka foto sebelumnya akan terhapus</p>
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
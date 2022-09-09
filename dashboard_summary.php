<?php
    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }
	
	function tgl_indonesia($tgl){

		$nama_bulan = array(1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		// $tanggal = substr($tgl,8,2);

		$bulan = $nama_bulan[(int)substr($tgl,5,2)];

		// $tahun = substr($tgl,0,4);

		
		// return $tanggal.' '.$bulan.' '.$tahun;		 
		return $bulan;	

	}	

    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query_perusahaan_pelanggar = "
                    SELECT 
                        right(left(replace(pr.tgl_trx,'-',''),6),2) as periode_bulan,
                        pr.nama_pj as nama_pj, 
                        count(id_pj) as id_pj,
                        MONTH(CURRENT_DATE() )
                    FROM 
                        pelanggaran_rpt pr 
                    WHERE
                        right(left(replace(pr.tgl_trx,'-',''),6),2) = MONTH(CURRENT_DATE())
                    GROUP BY 
                        pr.id_pj, 
                        right(left(replace(pr.tgl_trx,'-',''),6),2)
                    ORDER BY 
                        id_pj DESC
                ";
        
        $result = $db->prepare($query_perusahaan_pelanggar);
        $result->execute();
        $num = $result->rowCount();
        
        $count = 0;

        $str = "";
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;
                $str = $str."<tr>";
                $str = $str."<td valign='top'>".$count."</td>"; //No
                $str = $str."<td valign='top'>".$row[1]."</td>"; //Kode<br>Pelanggaran
                $str = $str."<td valign='top'>".$row[2]."</td>"; //Tanggal<br>Pelanggaran
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
        
        $query_jumlah_pelanggaran = "
                SELECT
                    right(left(replace(pr.tgl_trx,'-',''),6),2) as periode_bulan,
                    pr.pelanggaran as pelanggaran,
                    count(pr.id_pelanggaran) as id_pelanggaran,
                    MONTH(CURRENT_DATE())
                FROM 
                    pelanggaran_rpt pr
                WHERE
                    right(left(replace(pr.tgl_trx,'-',''),6),2) = MONTH(CURRENT_DATE())
                GROUP BY 
                    pr.id_pelanggaran, 
                    right(left(replace(pr.tgl_trx,'-',''),6),2)
                ORDER BY 
                    id_pelanggaran DESC
        ";
        
        $result = $db->prepare($query_jumlah_pelanggaran);
        $result->execute();
        $num = $result->rowCount();
        
        $count = 0;
        
        $str_jumlah_pelanggaran = "";
        
        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;
                $str_jumlah_pelanggaran = $str_jumlah_pelanggaran."<tr>";
                $str_jumlah_pelanggaran = $str_jumlah_pelanggaran."<td valign='top'>".$count."</td>"; //No
                $str_jumlah_pelanggaran = $str_jumlah_pelanggaran."<td valign='top'>".$row[1]."</td>"; //Kode<br>Pelanggaran
                $str_jumlah_pelanggaran = $str_jumlah_pelanggaran."<td valign='top'>".$row[2]."</td>"; //Tanggal<br>Pelanggaran
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
<div class="row">
    <div class="col-sm-6">
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h4 class="with-border">Daftar Perusahaan Pelanggar</h4>
                        <ol class="breadcrumb breadcrumb-simple">
                            <li>Di bawah ini adalah daftar perusahaan pelanggar di PT KBS dalam bulan <?= tgl_indonesia(date("Y/m/d"))?></li>
                        </ol>
                    </div>
                </div>

                <div class="tbl-row">
                    <div class="tbl-cell">
                        <div class="pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="card">
            <div class="card-block">
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Perusahaan/<br>Penanggung jawab</th>
                            <th>Jml <br> Pelanggaran</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Perusahaan/<br>Penanggung jawab</th>
                            <th>Jml <br> Pelanggaran</th>
                        </tr>
                        </tfoot>
                    <tbody>
                        <?php echo $str; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div><!--.widget-simple-sm-fill-->

    <div class="col-sm-6">
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h4 class="with-border">Daftar Jumlah Pelanggaran</h4>
                        <ol class="breadcrumb breadcrumb-simple">
                            <li>Di bawah ini adalah daftar jumlah pelanggaran yang terjadi pada bulan <?= tgl_indonesia(date("Y/m/d"))?> di PT KBS</li>
                        </ol>
                    </div>
                </div>

                <div class="tbl-row">
                    <div class="tbl-cell">
                        <div class="pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="card">
            <div class="card-block">
                <table id="example2" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis<br>Pelanggaran</th>
                            <th>Jml <br> Pelanggaran</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                        </tr>
                        </tfoot>
                    <tbody>
                        <?php  echo $str_jumlah_pelanggaran; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div><!--.widget-simple-sm-fill-->
</div><!--.widget-simple-sm-fill-->

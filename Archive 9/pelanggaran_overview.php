<?PHP
session_start();

if (!isset($_SESSION['sessunameetilang'])) {
    header('location:index.php');
}

include "koneksi/connect-db.php";

$modeDebug = 1;

$strMessage = "";

$str = "";

if (isset($_POST['filter_date']) and $_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
} else {
    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $query = "
                    SELECT 
                        * 
                    FROM 
                        pelanggaran_rpt 
                    WHERE
                        status IS NULL
                        OR status = 'Draft'
                    ORDER BY 
                        tgl_trx
                ";

        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        $count = 0;

        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $count = $count + 1;

                $str = $str . "<tr>";
                $str = $str . "<td valign='top'>" . $count . "</td>"; //No
                $str = $str . "<td valign='top'>" . $row[0] . "</td>"; //Kode<br>Pelanggaran
                $str = $str . "<td valign='top'>" . $row[2] . "</td>"; //Tanggal<br>Pelanggaran
                $str = $str . "<td valign='top'>" . $row[18] . "</td>"; //Klasifikasi<br>Pelanggaran
                $str = $str . "<td valign='top'>" . $row[16] . "</td>"; //Nama Pelanggar
                $str = $str . "<td valign='top'>" . $row[4] . "</td>"; //No Polisi
                $str = $str . "<td valign='top'>" . $row[7] . "</td>"; // Penanggungjawab
                $str = $str . "<td valign='top'>" . $row[10] . "</td>"; // Pelanggaran
                $str = $str . "<td valign='top'>" . $row[12] . "</td>"; //Lokasi
                $str = $str . "<td valign='top'>" . $row[14] . "</td>"; //Keterangan
                $str = $str . "<td valign='top'>" . $row[20] . "</td>"; //Masa berlaku
                $str = $str . "<td valign='top'>" . $row[21] . "</td>"; //surat peringatan aktif
                $str = $str . "<td valign='top'>" . $row[15] . "</td>"; //pic tilang

                $row[0] = base64_encode($row[0]);

                // if($row[1]==1){
                $str = $str . "<td class='center' width='150px'>
                                    <a class='btn btn-primary' href='pelanggaran_edit.php?id=$row[0]'>
                                            <i class='icon-edit icon-white'></i>  
                                            Edit                                            
                                    </a>
                                </td>";
                // }elseif($row[1]==2){
                //     $str = $str."<td class='center' width='150px'>
                //                     <a class='btn btn-primary' href='pelanggaran_nab_edit.php?id=$row[0]'>
                //                             <i class='icon-edit icon-white'></i>  
                //                             Edit                                            
                //                     </a>
                //                 </td>";
                // }
                $str = $str . "<td class='center' width='150px'>
                                <a class='btn btn-danger' onclick='return konfirmasi()' href='pelanggaran_delete.php?id=$row[0]'>
                                    <i class='icon-edit icon-white'></i>  
                                    Cancel                                            
                                </a>
                            </td>";
            }
        }

        $db = null;
    } catch (Exception $e) {
        if ($modeDebug == 0) {
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        } else {
            $strMessage = $e->getMessage();
        }
    }

    try {
        $db = new PDO("mysql:host=$host3306;port=$dbport3306;dbname=$dbname3306", $dbuser3306, $dbpass3306, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $queryCancel = "SELECT * FROM pelanggaran_rpt WHERE status = 'Cancel'";

        $result = $db->prepare($queryCancel);
        $result->execute();

        $numCancel = $result->rowCount();

        $db = null;
    } catch (Exception $e) {
        if ($modeDebug == 0) {
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        } else {
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

    <link rel="stylesheet" href="css/separate/vendor/bootstrap-daterangepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                    <?PHP include "menu_up.php"; ?>
        </div>
        <!--.container-fluid-->
    </header>
    <!--.site-header-->

    <div class="mobile-menu-left-overlay"></div>
    <?PHP include "menu_left.php"; ?>


    <header class="page-content">
        <?PHP echo $strMessage; ?>

        <header class="container-fluid">
            <header class="box-typical box-typical-padding">
                <header class="section-header">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h2 class="with-border">Daftar Pelanggaran</h2>
                                <ol class="breadcrumb breadcrumb-simple">
                                    <li>Di bawah ini adalah daftar pelanggaran di PT KBS</li>
                                </ol>
                            </div>
                        </div>

                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <div class="pull-right">
                                    <?php echo "<a href='pelanggaran_overview_export_excel.php' type='button' class='btn btn-inline'><i class='fa fa-file-excel-o'></i> Export Excel</a>"; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#">Semua Daftar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='pelanggaran_overview_cancel.php'>Di-cancel (<?php echo $numCancel; ?>) </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class='input-group date'>
                                        <input id="daterange" type="text" name="range_tanggal" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="font-icon font-icon-calend"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--.row-->
                    </div>
                </header>

                <div class="box-typical box-typical-padding">
                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>No</th>
                                <th>Kode<br>Pelanggaran</th>
                                <th>Tanggal<br>Pelanggaran</th>
                                <th>Klasifikasi<br>Pelanggaran</th>
                                <th>Nama Pelanggar</th>
                                <th>No Polisi</th>
                                <th>Perusahaan / <br> Penanggungjawab</th>
                                <th>Pelanggaran</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                                <th>Masa Berlaku</th>
                                <th>Surat<br>Peringatan<br>Aktif</th>
                                <th>PIC Tilang</th>
                                <th>Edit</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <!--<th>No</th>-->
                                <th>No</th>
                                <th>Kode<br>Pelanggaran</th>
                                <th>Tanggal<br>Pelanggaran</th>
                                <th>Klasifikasi<br>Pelanggaran</th>
                                <th>Nama Pelanggar</th>
                                <th>No Polisi</th>
                                <th>Perusahaan / <br> Penanggungjawab</th>
                                <th>Pelanggaran</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                                <th>Masa Berlaku</th>
                                <th>Surat<br>Peringatan<br>Aktif</th>
                                <th>PIC Tilang</th>
                                <th>Edit</th>
                                <th>Cancel</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?PHP echo $str; ?>
                        </tbody>
                    </table>
                </div>
            </header>
        </header>
    </header>
    <script src="js/lib/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/lib/popper/popper.min.js"></script>
    <script src="js/lib/tether/tether.min.js"></script>
    <script src="js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script type="text/javascript" src="js/lib/moment/moment-with-locales.min.js"></script>
    <script src="js/lib/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script src="js/lib/datatables-net/datatables.min.js"></script>
    <script>
        $(function() {
            $('#example').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        });


        function konfirmasi() {
            if (confirm('Anda yakin akan meng-cancel?')) {
                alert('Data pelanggaran berhasil di-cancel')
            } else {
                return false
            }
        }

        $(function() {
            $('input[name="range_tanggal"]').daterangepicker({
                // timePicker: true,
                // timePicker24Hour: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                // locale: {
                //     format: 'DD/MM/YYYY H:mm '
                // }
            })
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                filter_date = 1
                startDate = picker.startDate.format('YYYY-MM-DD')
                endDate = picker.endDate.format('YYYY-MM-DD')
                // console.log(picker.startDate.format('YYYY-MM-DD'));
                // console.log(picker.endDate.format('YYYY-MM-DD'));

                // $.post('pelanggaran_overview.php', {
                //     filter_date: 'filter_date',
                //     startDate: 'startDate',
                //     endDate: 'endDate'
                // }, function(result) {
                //     console.log(result)
                // });

                $.ajax({
                    type: "POST",
                    // url: "pelanggaran_overview.php?filter_date=" + filter_date + "?startDate=" + startDate + "?endDate=" + endDate,
                    url: "pelanggaran_overview_search_date.php",
                    data: {
                        filter_date: filter_date,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        // location.reload();
                        $("#example").html(data)
                        // console.log(data)
                    }
                });
            });
        })
    </script>
    <script src="js/app.js"></script>
</body>

</html>
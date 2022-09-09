<?php
include "koneksi/connect-db.php";

if (isset($_POST["filter_date"])) {
    // $output = array();
    $connect = mysqli_connect($host3306, $dbuser3306, $dbpass3306, $dbname3306);
    $output = '';
    $query = "SELECT * FROM pelanggaran_rpt WHERE tgl_trx BETWEEN '" . $_POST['startDate'] . "' AND '" . $_POST['endDate'] . "' AND (status IS NULL OR status = 'Draft') ORDER BY tgl_trx";
    $result = mysqli_query($connect, $query);
    $count = 0;
    $output .= "
                <table id='example' class='display table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>
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
                ";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $count = $count + 1;
            $output .= '
                    <tr>
                        <td> ' . $count . ' </td>
                        <td> ' . $row["id_trx"] . ' </td>
                        <td> ' . $row["tgl_trx"] . ' </td>
                        <td> ' . $row["klasifikasi_pelanggaran"] . ' </td>
                        <td> ' . $row["nama_pelanggar"] . ' </td>
                        <td> ' . $row["no_polisi"] . ' </td>
                        <td> ' . $row["nama_pj"] . ' </td>
                        <td> ' . $row["pelanggaran"] . ' </td>
                        <td> ' . $row["lokasi"] . ' </td>
                        <td> ' . $row["keterangan"] . ' </td>
                        <td> ' . $row["masa_berlaku"] . ' </td>
                        <td> ' . $row["jumlah_sp_aktif"] . ' </td>
                        <td> ' . $row["pic_tilang"] . ' </td>
                        <td class="center" width="150px">
                            <a class="btn btn-primary" href="pelanggaran_edit.php?id=' . base64_encode($row["id_trx"]) . '">
                                    <i class="icon-edit icon-white"></i>  
                                    Edit                                            
                            </a>
                        </td>
                        <td class="center" width="150px">
                            <a class="btn btn-danger" onclick="return konfirmasi()" href="pelanggaran_delete.php?id=' . base64_encode($row["id_trx"]) . '">
                                    <i class="icon-edit icon-white"></i>  
                                    Cancel                                            
                            </a>
                        </td>
                    </tr>
                </tbody>
            ';
        }
    } else {
        $output .= '
                <tbody>
                    <tr>
                        <td> Not Found </td>
                    </tr>
                </tbody>
            ';
    }
    echo $output;
}

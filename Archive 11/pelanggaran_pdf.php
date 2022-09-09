<?php
session_start();

if (!isset($_SESSION['sessunameetilang'])) {
    header('location:index.php');
}

include "koneksi/connect-db.php";


$modeDebug = 0;
$strMessage = "";

$txtKode = base64_decode($_GET['id']);

date_default_timezone_set('Asia/Jakarta');
$date = new DateTime();
$date = $date->getTimestamp();
$date = date("Y-m-d H-i-s", $date);


if (isset($_GET['id']) and $_SERVER['REQUEST_METHOD'] == "GET") {
    try {
        require('PHPPDF/fpdf.php');

        $pdf = new FPDF('l', 'mm', 'A5');

        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(190, 7, 'e-PUNISHMENT - PT KRAKATAU BANDAR SAMUDERA', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(10, 7, '', 0, 1);

        $pdf->SetFont('Arial', '', 10);

        $db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $connect = mysqli_connect($host, $dbuser, $dbpass, $dbname);

        $data = mysqli_query($connect, "select * from pelanggaran_rpt where id_trx = '$txtKode'");

        // print_r($pathFoto);
        // return;

        while ($row = mysqli_fetch_array($data)) {
            $id_trx = $row['id_trx'];
            $pdf->Cell(45, 6, "ID Tilang", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(27, 6, $row['id_trx'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Jenis Pelanggaran", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(50, 6, $row['pelanggaran'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Tanggal Pelanggaran", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(27, 6, $row['tgl_trx'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Surat Peringatan (SP)", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(27, 6, $row['jumlah_sp_aktif'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Masa Berlaku SP", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(27, 6, $row['masa_berlaku'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Waktu", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['waktu'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Nama Pelanggar", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['nama_pelanggar'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Pekerjaan", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['pekerjaan'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Nama Penanggung Jawab", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['nama_pj'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Lokasi Pelanggaran", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['lokasi'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "PIC Tilang", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['pic_tilang'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Keterangan", 0);
            $pdf->Cell(3, 6, ":", 0);
            $pdf->Cell(25, 6, $row['keterangan'], 0);
            $pdf->Ln();
            $pdf->Cell(45, 6, "Foto", 0);
            $pdf->Cell(3, 6, ":", 0);
            $dataFoto = mysqli_query($connect, "select * from pelanggaran_foto_tab where id_trx = '$txtKode'");
            while ($rowFoto = mysqli_fetch_array($dataFoto)) {
                $pathFoto[] = $rowFoto['foto_path'];
            }
            // print_r($pathFoto[0]);
            // return;

            for ($i = 0; $i < count($pathFoto); $i++) {
                if (file_exists($pathFoto[$i])) {
                    $pdf->Image($pathFoto[$i], null, null, '100');
                }
            }
            // $pdf->Cell(25, 6, $dataFoto, 0);
            // $pdf->Write(5, $row['id_trx']);
            // $pdf->Write(5, $row['jenis_trx']);
            // $pdf->Write(5, $row['tgl_trx']);
            // $pdf->Write(5, $row['waktu']);
            // $pdf->Cell(27, 6, $row['id_trx'], 1, 0);
            // $pdf->Cell(20, 6, $row['jenis_trx'], 1, 0);
            // $pdf->Cell(27, 6, $row['tgl_trx'], 1, 0);
            // $pdf->Cell(25, 6, $row['waktu'], 1, 1);
        }


        // $img = "foto_tilang/WhatsApp Image 2020-04-07 at 17.23.41.jpeg";
        // $pdf->Image($img, '10', '10', '30', '30');
        // $pdf->Cell(40, 40, $pdf->Image($img, $pdf->GetX(), $pdf->GetY(), 33.78), 0, 0, 'L', false);
        $pdf->Output('D', 'pelanggaran_' . $id_trx . '_' . $date . '.pdf');
    } catch (PDOException $e) {
        if ($modeDebug == 0) {
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        } else {
            $strMessage = $e->getMessage();
        }
    }
}

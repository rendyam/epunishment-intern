<?php
include('../koneksi/connect-db.php');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
try{
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Kode Pelanggaran');
$sheet->setCellValue('C1', 'Tanggal Pelanggaran');
$sheet->setCellValue('D1', 'Klasifikasi Pelanggaran');
$sheet->setCellValue('E1', 'Nama Pelanggar');
$sheet->setCellValue('F1', 'No Polisi');
$sheet->setCellValue('G1', 'Perusahaan/Penanggungjawab');
$sheet->setCellValue('H1', 'Pelanggaran');
$sheet->setCellValue('I1', 'Lokasi');
$sheet->setCellValue('J1', 'Keterangan');
$sheet->setCellValue('K1', 'Masa Berlaku');
$sheet->setCellValue('L1', 'Surat Peringatan Aktif');
$sheet->setCellValue('M1', 'PIC Tilang');
$sheet->setCellValue('N1', 'Status');

$host="192.168.0.27";
$dbuser="pkl";
$dbpass="Pkl@9999";
$dbname="db_efile";
$dbport="3306";

$db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
 
$query = "SELECT * from pelanggaran_rpt order by tgl_trx";
$result = $db->prepare($query);
$result->execute();     

$num = $result->rowCount();

$no = 0;

$i = 2;
$no = 1;
while($row = $result->fetch(PDO::FETCH_NUM))
{
//     echo "<pre>";
//     var_dump($row);
// exit;
// echo "</pre>";

	$sheet->setCellValue('A'.$i, $no++);
	$sheet->setCellValue('B'.$i, $row[0]);
	$sheet->setCellValue('C'.$i, $row[2]);
	$sheet->setCellValue('D'.$i, $row[18]);	
	$sheet->setCellValue('E'.$i, $row[16]);	
	$sheet->setCellValue('F'.$i, $row[4]);	
	$sheet->setCellValue('G'.$i, $row[7]);	
	$sheet->setCellValue('H'.$i, $row[10]);	
	$sheet->setCellValue('I'.$i, $row[12]);	
	$sheet->setCellValue('J'.$i, $row[14]);	
	$sheet->setCellValue('K'.$i, $row[20]);	
	$sheet->setCellValue('L'.$i, $row[21]);	
	$sheet->setCellValue('M'.$i, $row[15]);	
	$sheet->setCellValue('N'.$i, $row[23]);	

	$i++;
}
 
$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
$i = $i - 1;
$sheet->getStyle('A1:D'.$i)->applyFromArray($styleArray);
 
 
$writer = new Xlsx($spreadsheet);
$writer->save('Data Pelanggaran PT KBS.xlsx');

} catch (Exception $e) {
    echo $e->getMessage();
}
?>
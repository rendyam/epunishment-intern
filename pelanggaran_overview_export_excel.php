<?php
    session_start();

    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }
    
    error_reporting(E_ALL);
    require_once 'PHPExcel/Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    $rowNya = 1;
    
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$rowNya, "No")
            ->setCellValue('B'.$rowNya, "Kode Pelanggaran")
            ->setCellValue('C'.$rowNya, "Tanggal Pelanggaran")
            ->setCellValue('D'.$rowNya, "Klasifikasi Pelanggaran")
            ->setCellValue('E'.$rowNya, "Nama Pelanggar")
            ->setCellValue('F'.$rowNya, "No Polisi")
            ->setCellValue('G'.$rowNya, "Perusahaan/Penanggungjawab")
            ->setCellValue('H'.$rowNya, "Pelanggaran")
            ->setCellValue('I'.$rowNya, "Lokasi")
            ->setCellValue('J'.$rowNya, "Keterangan")
            ->setCellValue('K'.$rowNya, "Masa Berlaku")
            ->setCellValue('L'.$rowNya, "Surat Peringatan Aktif")
            ->setCellValue('M'.$rowNya, "PIC Tilang")
            ->setCellValue('N'.$rowNya, "Status")
        ;
    
    $rowNya = $rowNya + 1;
    
    try {
        include "koneksi/connect-db.php";
        
        $db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
        
        $query = "SELECT * from pelanggaran_rpt order by tgl_trx";
        
        $result = $db->prepare($query);
        $result->execute();     

        $num = $result->rowCount();

        $no = 0;
        
        if($num > 0){
            while($row = $result->fetch(PDO::FETCH_NUM)){
                $no = $no + 1;

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("A$rowNya", $no)
                        ->setCellValue("B$rowNya", $row[0])
                        ->setCellValue("C$rowNya", $row[2])
                        ->setCellValue("D$rowNya", $row[18])
                        ->setCellValue("E$rowNya", $row[16])
                        ->setCellValue("F$rowNya", $row[4])
                        ->setCellValue("G$rowNya", $row[7])
                        ->setCellValue("H$rowNya", $row[10])
                        ->setCellValue("I$rowNya", $row[12])
                        ->setCellValue("J$rowNya", $row[14])
                        ->setCellValue("K$rowNya", $row[20])
                        ->setCellValue("L$rowNya", $row[21])
                        ->setCellValue("M$rowNya", $row[15])
                        ->setCellValue("N$rowNya", $row[23])
                ;
                
                $rowNya = $rowNya + 1;
            }
        }
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar Pelanggaran');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $query = "SELECT SYSDATE() as dt";

        $result = $db->prepare($query);
        $result->execute();

        $num = $result->rowCount();

        if($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tgl = "daftar_pelanggaran_".$row[0];
            }
        }

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$tgl.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

?>
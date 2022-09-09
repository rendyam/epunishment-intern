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
            ->setCellValue('D'.$rowNya, "No Polisi")
            ->setCellValue('E'.$rowNya, "Penanggungjawab")
            ->setCellValue('F'.$rowNya, "Pelanggaran")
            ->setCellValue('G'.$rowNya, "Lokasi")
            ->setCellValue('H'.$rowNya, "Kegiatan")
            ->setCellValue('I'.$rowNya, "Masa Berlaku")
            ->setCellValue('J'.$rowNya, "Surat Peringatan Aktif")
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
                        ->setCellValue("D$rowNya", $row[3])
                        ->setCellValue("E$rowNya", $row[6])
                        ->setCellValue("F$rowNya", $row[11])
                        ->setCellValue("G$rowNya", $row[13])
                        ->setCellValue("H$rowNya", $row[14])
                        ->setCellValue("I$rowNya", $row[16])
                        ->setCellValue("J$rowNya", $row[17])
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

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
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
$date = date("Y-m-d H:i:s", $date);

if (isset($_GET['id']) and $_SERVER['REQUEST_METHOD'] == "GET") {
    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

        $db->beginTransaction();

        $status = "Approve";
        $id_user_approve = $_SESSION['sessidetilang'];

        $sqlQuery = $db->prepare("
                    UPDATE 
                        pelanggaran_tab
                    SET 
                        status = :status,
                        approved_at = :approved_at,
                        approved_by = :approved_by
                    WHERE
                        id_trx = :id
            ");

        $sqlQuery->bindParam(':id', $txtKode, PDO::PARAM_STR);
        $sqlQuery->bindParam(':status', $status, PDO::PARAM_STR);
        $sqlQuery->bindParam(':approved_by', $id_user_approve, PDO::PARAM_STR);
        $sqlQuery->bindParam(':approved_at', $date, PDO::PARAM_STR);

        $sqlQuery->execute();

        $db->commit();

        require 'PHPMailer/PHPMailerAutoload.php';
        require 'credential.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = EMAIL;                 // SMTP username
        $mail->Password = PASS;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom(EMAIL, 'e-Punishment PT Krakatau Bandar Samudera');
        $mail->addAddress($_GET['email']);     // Add a recipient

        $mail->addReplyTo(EMAIL);

        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Laporan Pelanggaran - PT Krakatau Bandar Samudera';
        $mail->Body    = '
                            Dengan hormat, <br>
                            Bersama email ini, kami dari PT Krakatau Bandar Samudera menginformasikan sebagai berikut: <br>
                            Nama : ' . $_GET['name'] . ' <br>
                            Bekerja di Perusahaan : ' . $_GET['pj'] . ' <br>
                            <br>
                            Telah melakukan pelanggaran di lingkungan PT Krakatau Bandar Samudera dengan informasi sebagai berikut: <br>
                            Kode Pelanggaran : ' . $txtKode . ' <br>
                            Tanggal Pelanggaran : ' . $_GET['tgl'] . ' <br>
                            Surat Peringatan (SP) : ' . $_GET['sp'] . ' <br>
                            Pelanggaran : ' . $_GET['pelanggaran'] . ' <br>
                            <br>
                            SURAT PERINGATAN berlaku selama 6 bulan sejak tanggal surat diterbitkan ' . $_GET['masa_berlaku'] . '.
                            <br>
                            Adapun jenis SP yang berlaku di PT Krakatau Bandar Samudera adalah sebagai berikut: <br>
                            PERINGATAN I, Sanksi: Diberikan teguran dan dicatat kedalam sistem e-punishment data kendaraan dan/atau data diri pelanggar dan/atau data perusahaan. <br>
                            PERINGATAN II, Sanksi: Diberikan teguran dan dicatat kedalam sistem e-punishment sebagai PERINGATAN KERAS atas adanya pelanggaran kedua. <br>
                            PERINGATAN III, Sanksi: Diberikan teguran dan dicatat kedalam sistem e-punishment sebagai peringatan terakhir dengan tindakan pencabutan kartu gate access  untuk pelanggar sehingga pelanggar (perorangan dan/atau kendaraan) DILARANG memasuki kawasan PT KBS untuk jangka waktu 3 (tiga) bulan sejak diberikan Surat Peringatan III. <br>
                            <br>
                            
                            Mohon untuk melakukan tindakan dari internal perusahaan, agar tidak terjadi pelanggaran di kemudian hari.
                            <br><br>
                            Terima kasih atas kerjamanya
                            <br><br>
                            PT Krakatau Bandar Samudera
                            ';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }



        if ($sqlQuery->rowCount() > 0) {
            header('location:pelanggaran_overview.php');
        } else {
            $strMessage = "<div class='alert alert-error'><strong>Data gagal disimpan</strong></div>";
        }
    } catch (PDOException $e) {
        if ($modeDebug == 0) {
            $strMessage = "<div class='alert alert-danger alert-fill alert-close alert-dismissible fade show' role='alert'>Oops, there is something wrong.....</div>";
        } else {
            $strMessage = $e->getMessage();
        }
    }
}

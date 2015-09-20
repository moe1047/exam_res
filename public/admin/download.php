<?php
require_once('../../includes/functions.php');
if(isset($_REQUEST['download_sample'])){
    $file="../uploads/upload_Std_sample.xlsx";
    if (file_exists("../uploads/upload_Std_sample.xlsx")) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;

    }
    redirect_to('upload_student.php');
}

if(isset($_REQUEST['download_sample_res'])){
    $file="../uploads/upload_res_sample.xlsx";
    if (file_exists("../uploads/upload_Std_sample.xlsx")) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;

    }
    redirect_to('add_std_grade.php');
}
if(isset($_REQUEST['download_sms_reg_sample'])){
    $file="../uploads/upload_sms_reg_sample.xlsx";
    if (file_exists("../uploads/upload_Std_sample.xlsx")) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;

    }
    redirect_to('semester_reg.php');
}
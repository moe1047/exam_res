<?php session_start();
require_once('../../includes/functions.php');

if(isset($_REQUEST['logout'])) {

    session_destroy();
    $_SESSION = array();
    redirect_to("index.php");
}

    ?>
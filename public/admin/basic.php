<?php session_start();
//require_once('../../includes/functions.php');

$class_methods=get_class_propeties('mysqli');
foreach($class_methods as $method){
    echo $method."<br>";
}
get_class_
?>
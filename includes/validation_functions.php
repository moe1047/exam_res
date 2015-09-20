<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function in_min_and_max($value,$min,$max){
    return ($value >= $min) && ($value <= $max);
}
function has_existence($value){
    return isset($value) and $value != "";
}//true
function invalid_char($value){
    $junk = array('.' , ',' , '/' , ";" , '[' ,  "]" , '-','_', '*', '&', '^','%','$', '#', '@', '!', '~', '+', '(', ')', '|', '{', '}', '<', '>', '?', ':', '"', '=');
    return in_array($value,$junk);
}//false
function str_length($value,$min,$max){
    return strlen($value) > $min and strlen($value)< $max;
}//true
function valid_date($value){
    $data=$value;
    $format='/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
    return preg_match($format,$data);
}//true
function is_number($value){
    return is_integer($value);
}//true
function error_alert($errors=array()){
    $output="<div class='alert alert-danger'>";
   $output.="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>";
   $output.="×</button>";
   $output.="<span class='glyphicon glyphicon-hand-right'></span> <strong>Error!</strong>";
    $output.= "<hr class='message-inner-separator'>";
    $output.="<ul>";
    foreach($errors as $error){
        $output.="<li>$error</li>";
    }
    $output.="</ul>";
     //$output.="<p>";
       //$output.="Change a few things up and try submitting again.</p>";
        $output.="</div>";
        return $output;
}
//
function registered_std_alert($errors=array()){
    $output="<div class='alert alert-danger'>";
    $output.="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>";
    $output.="×</button>";
    $output.="<span class='glyphicon glyphicon-hand-right'></span> <strong>the following IDs are already registered:-</strong>";
    $output.= "<hr class='message-inner-separator'>";
    $output.="<ul>";
    foreach($errors as $error){
        $output.="<li>$error</li>";
    }
    $output.="</ul>";
    $output.="</div>";
    return $output;
}
function not_registered_std_alert($errors=array()){
    $output="<div class='alert alert-danger'>";
    $output.="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>";
    $output.="×</button>";
    $output.="<span class='glyphicon glyphicon-hand-right'></span> <strong>the following IDs are not registered:-</strong>";
    $output.="<ul>";
    foreach($errors as $error){
        $output.="<li>$error</li>";
    }
    $output.="</ul>";
    $output.="</div>";
    return $output;
}
function success_alert($success){
    $output="<div class='alert alert-success'>";
    $output.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>";
    $output.=" ×</button>";
    $output.= " <span class='glyphicon glyphicon-ok'></span> <strong>Success</strong>";
    $output.= "<hr class='message-inner-separator'>";
    $output.= "<p>";
    $output.="$success</p>";
    $output.= "</div>";


    /*$output="<div class='alert alert-success'>";
    $output.="$success";
    $output.="</div>";*/
    return $output;
}
function warning_alert($value){
    $output="<div class='alert alert-warning'>";
    $output.="$value";
    $output.="</div>";
    return $output;

}
function registered_std_sms_success_alert($errors=array()){
    $output="<div class='alert alert-success'>";
    $output.="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>";
    $output.="×</button>";
    $output.="<span class='glyphicon glyphicon-hand-right'></span> <strong>The following IDs are registered successfully!</strong>";
    $output.= "<hr class='message-inner-separator'>";
    $output.="<ul>";
    foreach($errors as $error){
        $output.="<li>$error</li>";
    }
    $output.="</ul>";
    $output.="</div>";
    return $output;
}
function admin_error_alert($error){
    $output="<div id='message'>";
    $output.= "<strong>Error</strong>";
    $output.= "<hr class='message-inner-separator'>";
    $output.= "<p>";
    $output.="$error</p>";
    $output.= "</div>";


    /*$output="<div class='alert alert-success'>";
    $output.="$success";
    $output.="</div>";*/
    return $output;
}
function del_success_sweet_alert($message){
    $output= "<script>";
    $output.= " swal('Successfully Deleted!', '".$message."', 'success')";
    $output.= "</script>";
    return $output;
}

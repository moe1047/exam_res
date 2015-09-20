<?php session_start();
require_once('../includes/layout/std_header.php');
require_once('../includes/functions.php');
require_once('../includes/validation_functions.php');

?>
<?php
$error=array();
if(isset($_REQUEST['txt_std_id']) &&!empty($_REQUEST['txt_std_id']) && isset($_REQUEST['txt_std_pwd']) && !empty($_REQUEST['txt_std_pwd'])){
    $student_id=test_input($_REQUEST['txt_std_id']);
    $password=test_input($_REQUEST['txt_std_pwd']);

    if(empty($error)){
        $student=attemp_std_login($student_id,$password);
        if($student){
            if(!$student['active']){
                $error[]="this account has been deactivated";
            }


        }else{$error[]="username/password is not valid";}

    }

    if(empty($error)){
        $all_semesters=get_all_semesters();
        while($semester=$all_semesters->fetch_assoc()){
            if(!find_if_std_sms_registered($student_id,$semester['semester_id'])){
                $error['semester']="sorry, you aren't registered for ".$semester['semester_name']."(".$semester['ac_year_from']."-".$semester['ac_year_to'].")";
                break;

            }
        }
    }

    if(empty($error)){
        $student=attemp_std_login($student_id,$password);

            $_SESSION['student_id']=$student['student_id'];
            $_SESSION['first_name']=$student['first_name'];
            $_SESSION['middle_name']=$student['middle_name'];
            $_SESSION['last_name']=$student['last_name'];
            $_SESSION['class_name']=$student['class_name'];
            $_SESSION['shift']=$student['shift'];
            $_SESSION['batch']=$student['batch'];
            $_SESSION['section']=$student['section'];
            redirect_to('student_report.php');

    }

}
?>
<body>
<div class="container">
    <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">

            <div class="panel panel-default">
                <?php //if(isset($no_error)){echo success_alert($no_error);}?>
                <?php if(isset($error) &&!empty($error)){echo error_alert($error);}?>
                <div class="panel-heading">
                    <div class="row-fluid user-row">
                        <img src="img/Admas--College.png" class="img-responsive" alt="Conxole Admin"/>
                    </div>
                </div>
                <div class="panel-body">

                    <form accept-charset="UTF-8" role="form" class="form-signin" method="post">
                        <fieldset>
                            <label class="panel-login">
                                <div class="login_result"></div>
                            </label>
                            <input class="form-control" placeholder="Username" id="username" type="text" name="txt_std_id">
                            <input class="form-control" placeholder="Password" id="password" type="password" name="txt_std_pwd">
                            <br>
                            <input class="btn btn-lg btn-default btn-block" type="submit" id="login" value="Login">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</div>


<?php require_once('../includes/layout/std_footer.php')?>


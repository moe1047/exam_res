<?php require_once('../includes/functions.php');
session_start();
?>
<?php require_once('../includes/validation_functions.php');?>
<?php
if(isset($_GET['student_class'])){
    $all_class_student=get_class_students($_GET['student_class']);
    echo "<option value='' selected>Select Student</option>";
    while($row = $all_class_student->fetch_assoc()){
        echo "<option value=".$row['student_id'].">".$row['student_id']." | ".$row['first_name']." ".$row['middle_name']." ".$row['last_name']."</option>";}
        }


if(isset($_GET['student_sms'])){

    $all_student_sms=get_std_sms($_GET['student_sms']);//id is being passed to this
    echo "<option value='' selected>Select semester</option>";
    while($sms_row = $all_student_sms->fetch_assoc()){
        echo "<option value=".$sms_row['semester_id'].">".$sms_row['semester_name']." ".$sms_row['ac_year_from']." - ".$sms_row['ac_year_to']."</option>";
    }
}
if(isset($_GET['sms_course'])&& isset($_GET['class_id'])){

    $all_sms_course=semester_class_courses($_GET['class_id'],$_GET['sms_course']);//id is being passed to this
    echo "<option value='' selected>Select Course</option>";
    while($row = $all_sms_course->fetch_assoc()){
        echo "<option value=".$row['course_id'].">".$row['course_code']." | ".$row['course_name']."</option>";
    }
}
if(isset($_REQUEST['clscrs_cls_id'])){//search table(class_course)
    $semester_id="";
    if(isset($_REQUEST['sms_id'])&&!empty($_REQUEST['sms_id'])){$semester_id=$_REQUEST['sms_id'];}
    $search_cls_result=search_clscrs_by_class($_REQUEST['clscrs_cls_id'],$semester_id);
    //if (isset($search_result['false'])){$search_error=$search_result['false'];}
    //if(isset($search_result['rows'])){$search_rows=$search_result['rows'];$search_rows2=$search_result['rows'];}//search_rows2 is for testing





    $cls_id=$ac_year_from=$ac_year_to=0;$semester_name="";
    //$class_courses=get_all_class_courses();
    if(isset($search_cls_result) && is_object($search_cls_result)){
        while($cls_crs_row=$search_cls_result->fetch_assoc()){
            //--------------------------------------------------------------

            $cls_id_count=0;
            $count_class=search_clscrs_by_class($_REQUEST['clscrs_cls_id'],$semester_id);
            while($cls=$count_class->fetch_assoc()){
                if($cls['class_id']==$cls_crs_row['class_id']){
                    $cls_id_count+=1;
                }
            }

            //--------------------------------------------------------------
            $std_sms_count=0;
            $count_sms=search_clscrs_by_class($_REQUEST['clscrs_cls_id'],$semester_id);
            while($sms=$count_sms->fetch_assoc()){
                if($sms['ac_year_from']==$cls_crs_row['ac_year_from']&&$sms['ac_year_to']==$cls_crs_row['ac_year_to']
                    &&$sms['class_id']==$cls_crs_row['class_id']){
                    $std_sms_count+=1;
                }
            }

            //--------------------------------------------------------------
            $std_smsn_count=0;
            $count_smsn=search_clscrs_by_class($_REQUEST['clscrs_cls_id'],$semester_id);
            while($sms=$count_smsn->fetch_assoc()){
                if($sms['ac_year_from']==$cls_crs_row['ac_year_from']&&$sms['ac_year_to']==$cls_crs_row['ac_year_to']&&
                    $sms['class_id']==$cls_crs_row['class_id']&&$sms['semester_name']==$cls_crs_row['semester_name']){
                    $std_smsn_count+=1;
                }
            }

            //--------------------------------------------------------------





            $table_row="<tr>";
            if($cls_id!==$cls_crs_row['class_id']){
                $table_row.="<td rowspan=".$cls_id_count.">".$cls_crs_row['class_name']." ".$cls_crs_row['shift']." ".$cls_crs_row['batch']." (".$cls_crs_row['section'].")</td>";
            }
            if($ac_year_from!==$cls_crs_row['ac_year_from'] && $ac_year_to!==$cls_crs_row['ac_year_to']  ){
                $table_row.="<td rowspan='".$std_sms_count."'>"."".$cls_crs_row['ac_year_from']." - ".$cls_crs_row['ac_year_to']."</td>";
            }

            if($ac_year_from!==$cls_crs_row['ac_year_from'] && $ac_year_to!==$cls_crs_row['ac_year_to'] || $semester_name!=$cls_crs_row['semester_name']){
                $table_row.="<td rowspan='".$std_smsn_count."'> ".$cls_crs_row['semester_name']."</td>";
            }


            $table_row.="<td>".$cls_crs_row['course_code']." | ".$cls_crs_row['course_name']."</td>";
            $table_row.="<td>".$cls_crs_row['cr_hours']."</td>";
            $table_row.="<td><input type='radio' value='".$cls_crs_row['class_course_id']."' name='radio_edit_clscr'></td>";
            $table_row.="<td><input type='checkbox' name='chk_delete_clscr[]' value='".$cls_crs_row['class_course_id']."'></td>";
            $table_row.="</tr>";
            $cls_id=$cls_crs_row['class_id'];
            $ac_year_from=$cls_crs_row['ac_year_from'];$ac_year_to=$cls_crs_row['ac_year_to'];$semester_name=$cls_crs_row['semester_name'];
            echo $table_row;
        }
    }






}

if(isset($_REQUEST['search_sms_reg'])){
    $std_id=$std_name=$cls_id='';
    if(isset($_REQUEST['sms_reg_std_id'])&&!empty($_REQUEST['sms_reg_std_id'])){$std_id=$_REQUEST['sms_reg_std_id'];}
    if(isset($_REQUEST['sms_reg_name'])&&!empty($_REQUEST['sms_reg_name'])){$std_name=$_REQUEST['sms_reg_name'];}
    if(isset($_REQUEST['sms_reg_cls_id'])&&!empty($_REQUEST['sms_reg_cls_id'])){$cls_id=$_REQUEST['sms_reg_cls_id'];}

    $search_result=search_reg_student($std_id,$std_name,$cls_id);

    if(isset($search_result['rows'])&& is_object($search_result['rows'])){
        $student_id=0;
        $ac_year_from=0;$ac_year_to=0;$sms_reg_cls_id=0;

        while($student_row=$search_result['rows']->fetch_assoc()){


            //--------------------------------------------------------------reason for this is because while inside a while wont work
            $std_id_count=0;
            $count_std_id=search_reg_student($std_id,$std_name,$cls_id);
            while($std=$count_std_id['rows']->fetch_assoc()){
                if($std['student_id']==$student_row['student_id']){
                    $std_id_count+=1;
                }
            }

            //--------------------------------------------------------------
            $std_year_count=0;
            $count_std_year=search_reg_student($std_id,$std_name,$cls_id);
            while($year=$count_std_year['rows']->fetch_assoc()){
                if($year['ac_year_from']==$student_row['ac_year_from']&&$year['ac_year_to']==$student_row['ac_year_to']&&$year['student_id']==$student_row['student_id']){
                    $std_year_count+=1;
                }
            }

            //--------------------------------------------------------------
            $std_cls_count=0;
            $count_cls=search_reg_student($std_id,$std_name,$cls_id);
            while($clsyear=$count_cls['rows']->fetch_assoc()){
                if($clsyear['class_id']==$student_row['class_id']){
                    $std_cls_count+=1;
                }
            }

            //--------------------------------------------------------------
            $table_row="<tr>";
            //$table_row.="<td>".$No."</td>";
            //--------------------------------------------------------------

            if($student_id!==$student_row['student_id']){
                $table_row.="<td rowspan='".$std_id_count."'>".$student_row['student_id']."</td>";
                $table_row.="<td rowspan='".$std_id_count."'>".$student_row['first_name']." ".$student_row['middle_name']." ".$student_row['last_name']."</td>";
            }
            if($sms_reg_cls_id!==$student_row['class_id']){
                $table_row.="<td rowspan=".$std_cls_count.">".$student_row['class_name']." ".$student_row['shift']." ".$student_row['batch']." (".$student_row['section'].")</td>";
            }

            if($ac_year_from!==$student_row['ac_year_from']||$ac_year_to!==$student_row['ac_year_to'] || $student_id!==$student_row['student_id']){
                $table_row.="<td rowspan='".$std_year_count."'>".$student_row['ac_year_from']." - ".$student_row['ac_year_to']."</td>";
            }

            $table_row.="<td>".$student_row['semester_name']."</td>";
            $table_row.="<td>".$student_row['remark']."</td>";
            $table_row.="<td><input type='radio' name='radio_edit_sms_reg' value='".$student_row['sms_reg_id']."' ></td>";
            $table_row.="<td><input type='checkbox' name='chk_del_sms_reg[]' value='".$student_row['sms_reg_id']."'></td>";
            $table_row.="</tr>";
            echo $table_row;
            $student_id=$student_row['student_id'];
            $ac_year_from=$student_row['ac_year_from'];$ac_year_to=$student_row['ac_year_to'];$sms_reg_cls_id=$student_row['class_id'];
        }
    }


}
if(isset($_REQUEST['add_reg_std_cls'])){
    $all_class_student=get_class_students($_REQUEST['add_reg_std_cls']);
    echo "<option value='' selected>Select Student</option>";
    while($row = $all_class_student->fetch_assoc()){
        echo "<option value=".$row['student_id'].">".$row['student_id']." | ".$row['first_name']." ".$row['middle_name']." ".$row['last_name']."</option>";}
}
if(isset($_REQUEST['mark_id'])){
    $mark_results=get_all_marks_by_markg($_REQUEST['mark_id']);
    if(is_object($mark_results)){
        while($mark_row=$mark_results->fetch_assoc()){
            $table_row="<tr>";
            $table_row.="<td>".$mark_row['mark']."</td>";
            $table_row.="<td>".$mark_row['fromm']."</td>";
            $table_row.="<td>".$mark_row['too']."</td>";
            $table_row.="<td>".$mark_row['points']."</td>";
            $table_row.="<td>".$mark_row['mark_group_name']."</td>";
            $table_row.="<td><input type='radio' value='".$mark_row['mark_id']."' name='radio_edit_mrk'></td>";
            $table_row.="<td><input type='checkbox' name='chk_delete_mrk[]' value='".$mark_row['mark_id']."'></td>";
            $table_row.="<tr>";
            echo $table_row;
        }
    }else{echo "sql problem";}
}
if(isset($_POST['curr_pwd'])&&isset($_POST['new_pwd'])&&isset($_POST['conf_pwd'])){
    $error="";
    if(empty($_POST['curr_pwd'])||empty($_POST['new_pwd'])||empty($_POST['conf_pwd'])){
        $error="all fields must be filled";
    }
    if(!attemp_std_login($_SESSION['student_id'],test_input($_POST['curr_pwd']))){
        $error="[ERROR] current password";

    }
    if(empty($error)){
        if(test_input($_POST['new_pwd'])!==test_input($_POST['conf_pwd'])){
            $error="the two new password doesn't match";
        }

    }
    if(empty($error)){
        $password=password_hash(test_input($_POST['new_pwd']),PASSWORD_DEFAULT,['cost'=>12]);
     $message=update_std_pwd($_SESSION['student_id'],$password);
        if(isset($message['true'])){
            echo "Password successfully changed";
        }elseif(isset($message['false'])){
            echo $message['false']['message'];
        }
    }
    if(!empty($error)){
        echo $error;
    }
}
?>



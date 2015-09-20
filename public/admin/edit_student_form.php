<?php require_once('../../includes/layout/header.php');?>
<?php
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->
<?php $page="edit_student_form";
require_once('../../includes/layout/nav.php');

?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['student_id'])){
    $result=get_students_by_id($_REQUEST['student_id']);
    while($std=$result->fetch_assoc()){
        $student_id=$std['student_id'];
        $first_name=$std['first_name'];
        $middle_name=$std['middle_name'];
        $last_name=$std['last_name'];
        $school=$std['school'];
        $roll_No=$std['roll_No'];
        $graduated_year=$std['graduated_year'];
        $graduation_grade=$std['graduation_grade'];
        $phone_number=$std['phone_number'];
        $class_id=$std['class_id'];
        $pwd=$std['pwd'];
        $remark=$std['remark'];
        $active=$std['active'];

    }
}//request from edit class
if(isset($_REQUEST['submit_edit_std'])){
    $edited_pass=password_hash(test_input($_REQUEST['txt_stdedit_pwd']),PASSWORD_DEFAULT,['cost'=>12]);
    $message_returned=update_student($_REQUEST['txt_stdedit_id'],$_REQUEST['txt_stdedit_fname'],$_REQUEST['txt_stdedit_mname'],
        $_REQUEST['txt_stdedit_lname'],$_REQUEST['txt_stdedit_school'],$_REQUEST['txt_stdedit_rno'],$_REQUEST['txt_stdedit_gr_year'],
        $_REQUEST['txt_stdedit_gr_grade'],$_REQUEST['txt_stdedit_phno'],$_REQUEST['ddl_stdedit_cls'],$edited_pass,
        $_REQUEST['txt_stdedit_remark'],$_REQUEST['txt_stdedit_active']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to("search_student.php?edited_id=".$_REQUEST['txt_stdedit_id']);}
    if (isset($message_returned['false'])){$edit_error=$message_returned['false'];}
}
?>
<div id="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Student</h3></div>
                <div class="panel-body">
                    <?php if(isset($edit_error) ){echo error_alert($edit_error);} ?>
                    <?php if(isset($edit_success)){echo success_alert($edit_success);} ?>

                    <div class="col-md-6 ">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="add_std_form">
                                    <div class="form-group col-md-6 disabled">
                                        <label for="txt_std_id ">Student ID:</label>
                                        <input type="text" class="form-control " id="txt_std_id" value="<?php if(isset($student_id)){echo $student_id;} ?>" placeholder="0000" name="txt_stdedit_id"  >
                                    </div>
                                    <div class="form-group col-md-6" >
                                        <label for="txt_std_fullname" >first Name:</label>
                                        <input type="text" class="form-control" id="txt_std_fullname" VALUE="<?php if(isset($first_name)){echo $first_name;} ?>" name="txt_stdedit_fname" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="txt_std_fullname">Middle Name:</label>
                                        <input type="text" class="form-control" id="txt_std_fullname" value="<?php if(isset($middle_name)){echo $middle_name;} ?>" name="txt_stdedit_mname" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="txt_std_fullname">Last Name:</label>
                                        <input type="text" class="form-control" id="txt_std_fullname" value="<?php if(isset($last_name)){echo $last_name;} ?>" name="txt_stdedit_lname" >
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_std_ph.no">ph.number:</label>
                                        <input type="text" class="form-control" id="txt_std_no" value="<?php if(isset($phone_number)){echo $phone_number;} ?>" placeholder="+(000)00000000" name="txt_stdedit_phno" >
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_std_class">Class:</label>
                                        <select  class="form-control" name="ddl_stdedit_cls" >
                                            <option value="" selected>Select Class</option>
                                            <?php $all_classes=get_all_classes();
                                            while($classs = $all_classes->fetch_assoc()){
                                                echo "<option value='".$classs['class_id']."'";
                                                if(isset($class_id) && $class_id==$classs['class_id']){echo " selected ";}
                                                echo ">";
                                                echo $classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                            }?>
                                        </select>
                                    </div>

                            </div><!--/panel body-->
                        </div><!--/panel-->
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body">


                                <div class="form-group col-md-6">
                                    <label for="txt_std_DOB " >School:</label>
                                    <input type="text" class="form-control" value="<?php if(isset($school)){echo $school;} ?>" id="txt_std_DOB"  name="txt_stdedit_school" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txt_std_DOB">Roll Number:</label>
                                    <input type="text" class="form-control" value="<?php if(isset($roll_No)){echo $roll_No;} ?>" id="txt_std_DOB"  name="txt_stdedit_rno" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txt_std_address">Graduation year:</label>
                                    <input type="text" class="form-control" id="txt_std_address"  value="<?php if(isset($graduated_year)){echo $graduated_year;} ?>" name="txt_stdedit_gr_year" placeholder="year format: YYYY.">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txt_std_address">Graduated Grade:</label>
                                    <input type="text" class="form-control" id="txt_std_address" value="<?php if(isset($graduation_grade)){echo $graduation_grade;} ?>" name="txt_stdedit_gr_grade" >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txt_std_pwd ">Password:</label>
                                    <input type="password" class="form-control" id="txt_std_pwd"  placeholder="*****" name="txt_stdedit_pwd" >

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txt_std_pwd">Active:</label>
                                    <select  class="form-control" name="txt_stdedit_active"  >
                                        <option value="1" <?php if(isset($active) && $active==true){echo " selected ";} ?>>Yes</option>
                                        <option value="0" <?php if(isset($active) && $active==false){echo " selected ";} ?>>No</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="txt_stdedit_remark">Remark:</label>
                                    <textarea class="form-control" rows="2"  id="txt_stdedit_remark" name="txt_stdedit_remark" ><?php if(isset($remark)){echo $remark;} ?></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" name="submit_edit_std" class="btn btn-default btn-lg" >Update</button>
                                </div>
                                </form>


                            </div><!--/panel body-->
                        </div><!--/panel-->

                    </div><!--/col-md-6-->
                </div>
            </div>
            <!--<div class="col-md-1"></div>-->
        </div><!--/row-->
    </div><!-- /#page-wrapper -->
</div><!--container-->
<?php require_once('../../includes/layout/footer.php');?>



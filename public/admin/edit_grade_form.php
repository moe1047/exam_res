<?php require_once('../../includes/layout/header.php');?>
<?php
session_start();$_SESSION['user']='moe';
require_once('../../includes/functions.php');?>
<?php require_once('../../includes/validation_functions.php');
admin_confirm_logged_in();?>

<!--/header-->

<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['grade_id'])){
    $result=get_grade_by_id($_REQUEST['grade_id']);
    while($grade=$result->fetch_assoc()){
          $std_class=$grade['class_id'];
         $std_id=$grade['student_id'];
          $std_course=$grade['course_id'];
         $semester=$grade['semester_id'];
         $total_result=$grade['result'];
        $grade_remark=$grade['remark'];
    }
}//request from search grades
if(isset($_REQUEST['submit_edit'])){
    $message_returned=update_grade($_REQUEST['edit_grade_result'],$_REQUEST['edit_grade_std'],$_REQUEST['edit_grade_crs'],$_REQUEST['edit_grade_sms']
        ,$_REQUEST['edit_grade_remark'],$_REQUEST['edit_grade_id']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('edit_grades.php');}
    if (isset($message_returned['false'])){$edit_error=$message_returned['false'];}
}
?>
<div class="container">
    <div id="page-wrapper">

        <div class="row">


            <div class="col-md-2"></div>
            <div class="col-md-7"><!--add section-->
                <div class="alert alert-info">
                    <p>1) The result should be between 0-100</p>
                </div>
                <?php if(isset($edit_error) ){echo error_alert($edit_error);} ?>
                <?php if(isset($edit_success)){echo success_alert($edit_success);} ?>

                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Edit Grades</h3></div>
                    <div class="panel-body">

                        <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <input type="hidden" name="edit_grade_id" value='<?php if(isset($_REQUEST['grade_id'])){echo $_REQUEST['grade_id'];} ?>'>
                            <div class="form-group">
                                <label for="edit_grade_cls">Class:</label>
                                <select id="edit_grade_cls" class="form-control" name="edit_grade_cls" onchange="get_courses_m(this.value);get_students(this.value)"  required >
                                    <option value="" >Select Class</option>
                                    <?php $all_classes=get_all_classes();
                                    while($classs = $all_classes->fetch_assoc()){
                                        $class_option= "<option value='".$classs['class_id']."'";
                                        if(isset($std_class)){if($std_class==$classs['class_id']){$class_option.=" selected ";}}
                                        $class_option.=">";
                                        $class_option.= $classs['class_name']." ".$classs['shift']."</option>";
                                        echo $class_option;
                                    }?>
                                </select>

                            </div>
                            <div class="form-group ">
                                <label for="edit_grade_std">Student:</label>
                                <select id="edit_grade_std" name="edit_grade_std" class="form-control" required >
                                    <?php if(isset($std_class)){
                                        echo "<option value=''>Select Students</option>";
                                        $all_students=get_class_students($std_class);
                                        while($student_row=$all_students->fetch_assoc()){
                                            $std_option= "<option value='".$student_row['student_id']."'";
                                            if(isset($std_id)){if($std_id==$student_row['student_id']){$std_option.=" selected ";}}
                                            $std_option.=">";
                                            $std_option.= $student_row['student_id']."</option>";
                                            echo $std_option;
                                        }
                                    }?>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_grade_crs">Course:</label>
                                <select id="edit_grade_crs" name="edit_grade_crs" class="form-control" required >
                                    <?php if(isset($std_class)){
                                        echo "<option value=''>Select course</option>";
                                        $all_courses=get_class_courses($std_class);
                                        while($course_row=$all_courses->fetch_assoc()){
                                            $crs_option= "<option value='".$course_row['course_id']."'";
                                            if(isset($std_course)){if($std_course==$course_row['course_id']){$crs_option.=" selected ";}}
                                            $crs_option.=">";
                                            $crs_option.= $course_row['course_name']."</option>";
                                            echo $crs_option;
                                        }
                                    }?>

                                </select>

                            </div>
                            <div class="form-group">
                                <label for="edit_grade_sms">Semester:</label>
                                <select id="edit_grade_sms" class="form-control" name="edit_grade_sms" required >
                                    <?php $all_semesters=get_all_semesters();
                                    while($semester_row=$all_semesters->fetch_assoc()){
                                        $sms_option= "<option value=".$semester['semester_id'];
                                        if(isset($semester)){if($semester==$semester_row['semester_id']){$sms_option.=" selected ";}}
                                        $sms_option.= ">" ;
                                        $sms_option.=$semester_row['semester_name']." ".$semester_row['ac_year_from']." / ".$semester_row['ac_year_to']."</option>";
                                    echo $sms_option;
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="txt_grade_result">Result:</label>
                                <input type="text" class="form-control" id="txt_grade_result" name="edit_grade_result"
                                       placeholder="100 > input >0" name="edit_grde_result" value="<?php if(isset($total_result)){echo $total_result;} ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_grade_remark">remark:</label>
                                <input type="text" class="form-control" id="edit_grade_remark" name="edit_grade_remark"
                                       placeholder="Add / make up /Regular " name="txt_grde_result" value="<?php if(isset($grade_remark)){echo $grade_remark;} ?>">
                            </div>
                            <button type="submit" name="submit_edit" class="btn btn-default btn-lg">Submit</button>
                        </form>

                    </div><!--/panel body-->
                </div><!--/panel-->




            </div><!--/col-md-6-->
            <!--<div class="col-md-1"></div>-->
        </div><!--/row-->


    </div><!-- /#page-wrapper -->
</div><!--container-->
<?php require_once('../../includes/layout/footer.php');?>

<script type="text/javascript">//to get the courses for ddl_grade_class
    function get_courses(classs)
    {
        $.ajax({
            url: 'process_courses.php?class=' + classs,
            success: function(data) {
                $("#ddl_grade_crs").html(data);
            }
        })
    }

    function get_courses_m(classs)//to get the courses for ddl_grade_class_m
    {
        $.ajax({
            url: 'process_courses.php?class=' + classs,
            success: function(data) {
                $("#ddl_grade_crs_m").html(data);
            }
        })
    }

    function get_students(student_class)//to get the student for ddl_grade_class_m
    {
        $.ajax({
            url: 'process_students.php?student_class=' + student_class,
            success: function(data) {
                $("#ddl_grade_std_m").html(data);
            }
        })
    }
</script>

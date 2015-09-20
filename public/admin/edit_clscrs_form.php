<?php require_once('../../includes/layout/header.php');
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php $page="edit_clscrs_form";
?>
<!--/header-->

<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['clscrs_id'])){
    $result=get_clscrs_by_id($_REQUEST['clscrs_id']);
    while($clscrs=$result->fetch_assoc()){
        $clscrs_id=$clscrs['class_course_id'];
        $class_id=$clscrs['class_id'];
        $sms_id=$clscrs['semester_id'];
        $course_id=$clscrs['course_id'];
        $cr_hours=$clscrs['cr_hours'];
    }
}//request from edit class
if(isset($_REQUEST['submit_edit_clscrs'])){
    $message_returned=update_clscrs($_REQUEST['txt_edit_clscrs_id'],$_REQUEST['txt_edit_cls_id'],$_REQUEST['ddl_edit_sms_id'],$_REQUEST['ddl_edit_crs_id'],$_REQUEST['txt_edit_cr_h']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('class_course.php');}
    if (isset($message_returned['false'])){$edit_error=$message_returned['false'];}
}
?>
<div class="container">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-7"><!--add section-->

                <?php if(isset($edit_error) ){echo error_alert($edit_error);} ?>
                <?php if(isset($edit_success)){echo success_alert($edit_success);} ?>

                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title"></h3></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Edit Class Course</h3></div>
                            <div class="panel-body">
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php if(isset($clscrs_id)){echo $clscrs_id;} ?>" name="txt_edit_clscrs_id">
                                    <input type="hidden" value="<?php if(isset($class_id)){echo $class_id;} ?>" name="txt_edit_cls_id">
                                    <!--<div class="form-group">
                                        <label for="txt_clscrs">Class:</label>
                                        <select id="txt_clscrs" class="form-control" name="txt_edit_cls_id" required >
                                            <option value="" selected>Select Class</option>
                                            <?php /*$all_classes=get_all_classes();
                                            while($classs = $all_classes->fetch_assoc()){
                                                echo "<option value=".$classs['class_id'];
                                                if(isset($class_id)){
                                                    if($classs['class_id']==$class_id){echo " selected ";}
                                                }
                                                echo ">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                            }*/?>
                                        </select>

                                    </div>-->
                                    <div class="form-group">
                                        <label for="ddl_grade_sms">Semester:</label>
                                        <select id="ddl_grade_sms" class="form-control" name="ddl_edit_sms_id"  size="4" required>
                                            <option value="" selected>select semester</option>
                                            <?php $ac_year=get_ac_year();
                                            while($ac_year_row=$ac_year->fetch_assoc()){
                                                //echo $ac_year_row['ac_year_from']."<br>";
                                                //echo $ac_year_row['ac_year_to'];
                                                $semester=get_all_semestersby_ac($ac_year_row['ac_year_from'],$ac_year_row['ac_year_to']);
                                                echo "<optgroup label='".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']."'>";
                                                while($semester_row=$semester->fetch_assoc()){
                                                    echo "<option value=".$semester_row['semester_id'];
                                                    if(isset($sms_id)){
                                                        if($semester_row['semester_id']==$sms_id){echo " selected ";}
                                                    }
                                                    echo ">";
                                                    echo $ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']." ".$semester_row['semester_name']."</option>";
                                                }
                                                echo "</optgroup>";

                                            }
                                            ?>
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="txt_crscls">Course:</label>
                                        <select id="txt_crscls" name="ddl_edit_crs_id" class="form-control" required >
                                            <?php $all_courses=get_all_courses();
                                            while($course = $all_courses->fetch_assoc()){
                                                echo "<option value=".$course['course_id'];
                                                if(isset($course_id)){
                                                    if($course['course_id']==$course_id){echo " selected ";}
                                                }
                                                echo ">".$course['course_code']."- ".$course['course_name']."</option>";
                                            }?>
                                        </select>
                                        <span class="help-block">hold <kbd>CTRL</kbd> to select multiple choices</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_cr_hours">Credit hours:</label>
                                        <input type="text" class="form-control" id="txt_class_name" placeholder="3 / 4" value="<?php if(isset($cr_hours)){echo $cr_hours;} ?>" name="txt_edit_cr_h" required>
                                    </div>
                                    <button type="submit" name='submit_edit_clscrs' class="btn btn-default btn-lg">Submit</button>
                                </form>

                            </div><!--/panel body-->
                        </div><!--/panel-->

                    </div><!--/panel body-->
                </div><!--/panel-->

            </div><!--/col-md-6-->
            <!--<div class="col-md-1"></div>-->
        </div><!--/row-->
    </div><!-- /#page-wrapper -->
</div><!--container-->
<?php require_once('../../includes/layout/footer.php');?>



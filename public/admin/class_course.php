<?php require_once('../../includes/layout/header.php');
$page="class_course";?>
<!--/header-->
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
/*if(isset($_REQUEST['cls_id'])){
    $semester_id="";
    if(isset($_REQUEST['sms_id'])){$semester_id=$_REQUEST['sms_id'];}
    $search_cls_result=search_clscrs_by_class($_REQUEST['cls_id'],$semester_id);
    //if (isset($search_result['false'])){$search_error=$search_result['false'];}
    //if(isset($search_result['rows'])){$search_rows=$search_result['rows'];$search_rows2=$search_result['rows'];}//search_rows2 is for testing
}*/
if(isset($_REQUEST['submit_clcr'])) {
    $course_assigned = 0;
    if (!isset($_REQUEST['ddl_clcr_cls']) || empty($_REQUEST['ddl_clcr_cls'])) {
        $message['class'] = "class must be selected";
    }
    if (!isset($_REQUEST['ddl_clcr_crs'])|| empty($_REQUEST['ddl_clcr_crs'])) {
        $message['course'] = "select course";
    }
    if (!isset($_REQUEST['ddl_clcr_sms'])|| empty($_REQUEST['ddl_clcr_sms'])) {
        $message['sms'] = "select a semester";
    }
    if (!isset($_REQUEST['txt_clcr_cr_h'])|| empty($_REQUEST['txt_clcr_cr_h'])) {
        $message['cr_hours'] = "the credit hours wasn't provided";
    }
    if (!isset($message)) {
        $result=get_class_by_id($_REQUEST['ddl_clcr_cls']);
        $class_course=$result->fetch_assoc();

        foreach ($_REQUEST['ddl_clcr_crs'] as $course) {
            $msg_returned = insert_into_class_course($_REQUEST['ddl_clcr_cls'], $course,$_REQUEST['ddl_clcr_sms'],$_REQUEST['txt_clcr_cr_h']);
            if (isset($msg_returned['false'])) {
                $message[] = $msg_returned['false']['message'];
            }
            if (isset($msg_returned['true'])) {
                $course_assigned += 1;
            }
        }
    }


}
if(isset($_REQUEST['btn_delete_clscr'])){
    if(isset($_REQUEST['chk_delete_clscr'])){
        $delete_error=array();
        $success_message=0;
        foreach($_REQUEST['chk_delete_clscr'] as $class_course_id){
            $delete_message=delete_class_course($class_course_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_del_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['check_del_error']="No row is selected to delete";}
}
if(isset($_REQUEST['btn_edit_clscr'])){
    if(isset($_REQUEST['radio_edit_clscr'])){
        redirect_to('edit_clscrs_form.php?clscrs_id='.$_REQUEST['radio_edit_clscr']);
    }else{$edit_message['not_selected']="No row is selected to edit";}
}

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-4">
            <?php if(isset($message)){echo error_alert($message);} ?>
            <?php //if(isset($message['course'])){echo error_alert($message);} ?>
            <?php if(isset($course_assigned) && $course_assigned !=0){
                echo success_alert($course_assigned." courses are assigned to (".$class_course['class_name']." ".$class_course['shift']." ".$class_course['section']." ".$class_course['batch'].")");
            } ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Assign Courses For Classes</h3></div>
                <div class="panel-body">

                    <form role="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="txt_clscrs">Class:</label>
                            <select id="txt_clscrs" class="form-control" name="ddl_clcr_cls" required >
                                <option value="" selected>Select Class</option>
                                <?php $all_classes=get_all_classes();
                                while($classs = $all_classes->fetch_assoc()){
                                    echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                }?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="ddl_grade_sms">Semester:</label>
                            <select id="ddl_grade_sms" class="form-control" name="ddl_clcr_sms"  size="4" required>
                                <option value="" selected>select semester</option>
                                <?php $ac_year=get_ac_year();
                                while($ac_year_row=$ac_year->fetch_assoc()){
                                    //echo $ac_year_row['ac_year_from']."<br>";
                                    //echo $ac_year_row['ac_year_to'];
                                    $semester=get_all_semestersby_ac($ac_year_row['ac_year_from'],$ac_year_row['ac_year_to']);
                                    echo "<optgroup label='".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']."'>";
                                    while($semester_row=$semester->fetch_assoc()){
                                        echo "<option value=".$semester_row['semester_id'].">".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']." ".$semester_row['semester_name']."</option>";
                                    }
                                    echo "</optgroup>";

                                }
                                ?>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="txt_crscls">Course:</label>
                            <select multiple id="txt_crscls" name="ddl_clcr_crs[]" class="form-control" required >
                                <?php $all_courses=get_all_courses();
                                while($course = $all_courses->fetch_assoc()){
                                    echo "<option value=".$course['course_id'].">".$course['course_code']."- ".$course['course_name']."</option>";
                                }?>
                            </select>
                            <span class="help-block">hold <kbd>CTRL</kbd> to select multiple choices</span>
                        </div>
                        <div class="form-group">
                            <label for="txt_cr_hours">Credit hours:</label>
                            <input type="text" class="form-control" id="txt_class_name" placeholder="3 / 4" name="txt_clcr_cr_h" required>
                        </div>
                        <button type="submit" name='submit_clcr' value="submit_clcr"  class="btn btn-default btn-lg">Submit</button>
                    </form>
                </div><!--/panel body-->
            </div><!--/panel-->
        </div><!--/mod-->
        <div class="col-md-8">
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
            <?php if(isset($edit_message) && !empty($edit_message)){echo error_alert($edit_message);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." course/s is deleted");} ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <!--<form method="post" action="class_course.php">-->
                        <div class="form-group col-md-6">
                            <label for="txt_std_class">Class:</label>
                            <select  class="form-control" name="ddl_class_search" id="ddl_class_search" required>
                                <option value="" selected>Select Class</option>
                                <?php $all_classes=get_all_classes();
                                while($classs = $all_classes->fetch_assoc()){
                                    echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                }?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ddl_grade_sms">Semester:</label>
                            <select  class="form-control" name="ddl_sms_search" id="ddl_sms_search" required>
                                <option value="" selected>All semester</option>
                                <?php $ac_year=get_ac_year();
                                while($ac_year_row=$ac_year->fetch_assoc()){
                                    //echo $ac_year_row['ac_year_from']."<br>";
                                    //echo $ac_year_row['ac_year_to'];
                                    $semester=get_all_semestersby_ac($ac_year_row['ac_year_from'],$ac_year_row['ac_year_to']);
                                    echo "<optgroup label='".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']."'>";
                                    while($semester_row=$semester->fetch_assoc()){
                                        echo "<option value=".$semester_row['semester_id'].">".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']." ".$semester_row['semester_name']."</option>";
                                    }
                                    echo "</optgroup>";

                                }
                                ?>
                            </select>

                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-default btn-md" onclick="search_class_courses()" name="search_cls_crs"><span class="glyphicon glyphicon-search"></span> Search</button>
                        </div>




                    <!--</form>-->

                </div><!--panel body-->
            </div><!--panel default-->
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="post">
                        <table class="table table-bordered" id="tbl_results">
                            <thead>
                            <tr>
                                <td><b>Class</b> </td>
                                <td><b>ac year</b></td>
                                <td><b>semester</b></td>
                                <td><b>Course</b></td>
                                <td><b>Credit hours</b></td>
                                <td><b>Edit</b></td>
                                <td><b>Delete</b></td>

                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                        <button type="submit" class="btn btn-default btn-md" name="btn_edit_clscr"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_clscr" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                </div><!--panel body-->
            </div><!--panel-->

        </div><!--the end of the row-->

        <!-- /#page-wrapper -->


        <?php require_once('../../includes/layout/footer.php');?>

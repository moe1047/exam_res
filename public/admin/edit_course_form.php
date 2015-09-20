<?php require_once('../../includes/layout/header.php');?>
<?php

require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->

<?php $page="edit_course_form";
require_once('../../includes/layout/nav.php');

?>

<!-- /Navigation -->
<?php
if (isset($_REQUEST['course_id'])){
    $result=get_all_courses_by_id($_REQUEST['course_id']);
    while($course=$result->fetch_assoc()){
        $course_id=$course['course_id'];
        $course_name=$course['course_name'];
        $course_code=$course['course_code'];
    }
}//request from edit class
if(isset($_REQUEST['edit_crs'])){
    $message_returned=update_course($_REQUEST['txt_edit__crsid'],$_REQUEST['txt_edit__crsname'],$_REQUEST['txt_edit__crscode']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('add_course.php');}
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
                            <div class="panel-heading"><h3 class="panel-title">Edit class</h3></div>
                            <div class="panel-body">

                                <form role="form" method="post">
                                    <input type="hidden" name="txt_edit__crsid" value='<?php if(isset($_REQUEST['course_id'])){echo $course_id;} ?>'>
                                    <div class="form-group">
                                        <label for="txt_crs_name">Course Name:</label>
                                        <input type="text" class="form-control" id="txt_crs_name"  value="<?php if(isset($course_name)){echo $course_name;} ?>" name="txt_edit__crsname" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_course_code">Course Code:</label>
                                        <input type="text" class="form-control" id="txt_course_code"  value="<?php if(isset($course_code)){echo $course_code;} ?>" name="txt_edit__crscode" required>
                                    </div>
                                    <button type="submit" name="edit_crs" class="btn btn-default btn-lg">Update</button>
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



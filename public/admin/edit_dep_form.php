<?php require_once('../../includes/layout/header.php');?>
<?php
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->
<?php $page="edit_course_form";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['dep_id'])){
    $result=get_all_dep_by_id($_REQUEST['dep_id']);
    while($dep=$result->fetch_assoc()){
        $dep_id=$dep['dep_id'];
        $dep_name=$dep['dep_name'];
        $location=$dep['location'];

    }
}//request from edit class
if(isset($_REQUEST['submit_edit_dep'])){
    $message_returned=update_department($_REQUEST['txt_editdep_id'],$_REQUEST['txt_editdep_name'],$_REQUEST['txt_editdep_location']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('add_department.php');}
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
                            <div class="panel-heading"><h3 class="panel-title">Edit Department</h3></div>
                            <div class="panel-body">

                                <form role="form" method="post">
                                    <input type="hidden" value="<?php if(isset($dep_id)){echo $dep_id;} ?>" name="txt_editdep_id">
                                    <div class="form-group">
                                        <label for="txt_dprtmnt_name">Department Name:</label>
                                        <input type="text" class="form-control" id="txt_dprtmnt_name" value="<?php if(isset($dep_name)){echo $dep_name;} ?>" name="txt_editdep_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_dprtmnt_location">Department Location:</label>
                                        <input type="text" class="form-control" id="txt_dprtmnt_location"  value="<?php if(isset($location)){echo $location;} ?>" name="txt_editdep_location" required>
                                    </div>
                                    <button type="submit" name="submit_edit_dep" class="btn btn-default btn-lg">Update</button>
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



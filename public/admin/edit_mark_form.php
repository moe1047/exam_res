<?php require_once('../../includes/layout/header.php');
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php $page="edit_mark_form";
?>
<!--/header-->

<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['mark_id'])){
    $result=get_all_marks_by_id($_REQUEST['mark_id']);
    while($mark=$result->fetch_assoc()){
        $mark_id=$mark['mark_id'];
        $mark_name=$mark['mark'];
        $fromm=$mark['fromm'];
        $too=$mark['too'];
        $points=$mark['points'];
    }
}//request from edit class
if(isset($_REQUEST['submit_edit_mrk'])){
    $message_returned=update_mark($_REQUEST['txt_edit_mrk_id'],$_REQUEST['txt_edit_mrk'],$_REQUEST['txt_edit_from'],$_REQUEST['txt_edit_to'],$_REQUEST['txt_edit_points']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('add_marks.php');}
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
                                <form role="form">
                                    <input type="hidden" value="<?php if(isset($mark_id)){echo $mark_id;} ?>" name="txt_edit_mrk_id">
                                    <div class="form-group">
                                        <label for="txt_class_name">Mark Symbol:</label>
                                        <input type="text" class="form-control" id="txt_class_name" value="<?php if(isset($mark_name)){echo $mark_name;} ?>" placeholder="A+ / B / C" name="txt_edit_mrk" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_range_from">Range From:</label>
                                        <input type="text" class="form-control" id="txt_range_from" value="<?php if(isset($fromm)){echo $fromm;} ?>" placeholder="90 / 80 / 70" name="txt_edit_from" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_range_to">Range To:</label>
                                        <input type="text" class="form-control" id="txt_range_to" value="<?php if(isset($too)){echo $too;} ?>" placeholder="90 / 80 / 7" name="txt_edit_to" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_mrk_points">points:</label>
                                        <input type="text" class="form-control" id="txt_mrk_points" value="<?php if(isset($points)){echo $points;} ?>" placeholder="4 / 4.5 / 3.5" name="txt_edit_points" required>
                                    </div>



                                    <button type="submit" name="submit_edit_mrk" class="btn btn-default btn-lg">Update</button>
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



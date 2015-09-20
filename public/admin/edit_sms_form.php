<?php require_once('../../includes/layout/header.php');?>
<?php
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->
<?php $page="edit_sms_form";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['semester_id'])){
    $result=get_all_sms_by_id($_REQUEST['semester_id']);
    while($semester=$result->fetch_assoc()){
        $sms_id=$semester['semester_id'];
        $sms_name=$semester['semester_name'];
        $sms_ac_from=$semester['ac_year_from'];
        $sms_ac_to=$semester['ac_year_to'];
    }
}//request from edit class
if(isset($_REQUEST['submit_edit_sms'])){
    $message_returned=update_semester($_REQUEST['txt_edit_sms_id'],$_REQUEST['ddl_edit_smsname'],$_REQUEST['ddl_edit_ac_from'],$_REQUEST['ddl_edit_ac_to']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('add_semester.php');}
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
                    <div class="panel-heading"><h3 class="panel-title"></h3></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Edit class</h3></div>
                            <div class="panel-body">
                                <form role="form">
                                    <input type="hidden" value="<?php if(isset($sms_id)){echo $sms_id;} ?>" name="txt_edit_sms_id" >
                                    <div class="form-group">
                                        <label for="ddl_sms_name">Semester Name:</label>
                                        <select  id="ddl_sms_name" class="form-control" name="ddl_edit_smsname" required>
                                            <option <?php if(isset($sms_name)){if($sms_name=='semester1'){echo "selected";}} ?>>semester1</option>
                                            <option <?php if(isset($sms_name)){if($sms_name=='semester2'){echo "selected";}} ?>>semester2</option>
                                            <option <?php if(isset($sms_name)){if($sms_name=='semester3'){echo "selected";}} ?>>semester3</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="txt_ac_from">Academic year from:</label>
                                        <input type="text" class="form-control" id="txt_ac_from" placeholder="YYYY" name="ddl_edit_ac_from" value="<?php if(isset($sms_ac_from)){echo $sms_ac_from;} ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_ac_to">Academic year to:</label>
                                        <input type="text" class="form-control" id="txt_ac_to" placeholder="YYYY" name="ddl_edit_ac_to" value="<?php if(isset($sms_ac_to)){echo $sms_ac_to;} ?>">
                                    </div>


                                    <button type="submit" name="submit_edit_sms" class="btn btn-default btn-lg">Update</button>
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



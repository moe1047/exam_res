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
if (isset($_REQUEST['sms_reg_id'])){
    $result=get_all_sms_reg_by_id($_REQUEST['sms_reg_id']);
    while($sms_reg=$result->fetch_assoc()){
        $sms_reg_id=$sms_reg['sms_reg_id'];
        $sms_id=$sms_reg['semester_id'];
        $remark=$sms_reg['remark'];
        $std_id=$sms_reg['student_id'];
    }
}//request from edit class
if(isset($_REQUEST['sumbit_std_reg_mn'])){
    if(!isset($_REQUEST['ddl_edit_sms_reg_sms']) || empty($_REQUEST['ddl_edit_sms_reg_sms'])){
        $edit_error[]="semester is required";
    }
    if(empty($edit_error)){
        if(find_if_std_sms_registered($_REQUEST['txt_edit_sms_std_id'],$_REQUEST['ddl_edit_sms_reg_sms'])){
            $edit_error[]="this student is already registered for this semester";
            time_redirect_to('all_registration.php');
        }
    }
    if(empty($edit_error)){
        $message_returned=update_sms_reg($_REQUEST['txt_edit_sms_reg_id'],$_REQUEST['ddl_edit_sms_reg_sms'],$_REQUEST['txt_edit_sms_reg_remark']);
        if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('all_registration.php');}
        if (isset($message_returned['false'])){$edit_error[]=$message_returned['false']['message'];}
    }


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
                            <div class="panel-heading"><h3 class="panel-title">Edit Semester Registration</h3></div>
                            <div class="panel-body">

                                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="upload_std_form">
                                    <input type="hidden" value="<?php if(isset($sms_reg_id)){echo $sms_reg_id;} ?>" name="txt_edit_sms_reg_id">
                                    <input type="hidden" value="<?php if(isset($std_id)){echo $std_id;} ?>" name="txt_edit_sms_std_id">

                                    <div class="form-group col-md-8 col-md-offset-2">
                                        <label for="search_grade_sms"> Semester:</label>
                                        <select id="search_grade_sms" name="ddl_edit_sms_reg_sms" class="form-control" >
                                            <option selected value="">all semesters</option>
                                            <?php $ac_year=get_ac_year();

                                            while($ac_year_row=$ac_year->fetch_assoc()){
                                                //echo $ac_year_row['ac_year_from']."<br>";
                                                //echo $ac_year_row['ac_year_to'];
                                                $semester=get_all_semestersby_ac($ac_year_row['ac_year_from'],$ac_year_row['ac_year_to']);
                                                echo "<optgroup label='".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']."'>";
                                                while($semester_row=$semester->fetch_assoc()){
                                                    echo "<option value=".$semester_row['semester_id'];
                                                    if(isset($sms_id)){if($semester_row['semester_id']==$sms_id){echo " selected ";}}
                                                    echo ">";
                                                    echo $ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']." ".$semester_row['semester_name']."</option>";
                                                }
                                                echo "</optgroup>";

                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8 col-md-offset-2">
                                        <label for="std_upload_pwd">Remark:</label>
                                        <textarea class="form-control" rows="3" name="txt_edit_sms_reg_remark"><?php if(isset($remark)){echo $remark;} ?></textarea>

                                    </div>
                                    <div class="col-md-3 col-md-offset-5">
                                        <button type="submit" class="btn btn-default btn-lg" name="sumbit_std_reg_mn"><span class='glyphicon glyphicon-edit'></span> Update</button>
                                    </div>
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



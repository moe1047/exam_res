<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->
<!-- Navigation -->
<?php $page="all_registration";
require_once('../../includes/layout/nav.php');?>
<?php
if(isset($_REQUEST['search_sms_reg'])){
    /*$search_result=search_reg_student($_REQUEST['txt_search_reg_std_id']);
    if (isset($search_result['false'])){$search_error=$search_result['false'];}
    if(isset($search_result['rows'])){$search_rows=$search_result['rows'];$search_rows2=$search_result['rows'];}//search_rows2 is for testing*/
}
if(isset($_REQUEST['btn_del_sms_reg'])){
    if(isset($_REQUEST['chk_del_sms_reg']) && !empty($_REQUEST['chk_del_sms_reg'])){
        $delete_error=array();
        $success_message=0;
        foreach($_REQUEST['chk_del_sms_reg'] as $reg_id){
            $delete_message=delete_sms_reg($reg_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_del_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['check_del_error']="No row is selected to delete";}
}
if(isset($_REQUEST['btn_edit_sms_reg'])){
    if(isset($_REQUEST['radio_edit_sms_reg'])){
        redirect_to('edit_sms_reg_form.php?sms_reg_id='.$_REQUEST['radio_edit_sms_reg']);
    }else{$edit_message['not_selected']="No row is selected to edit";}
}
if(isset($_REQUEST['edited_id'])){
    $search_rows=get_edited_student($_REQUEST['edited_id']);
}
?>

<div id="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if(isset($search_error ) && !empty($search_error['message']) ){echo error_alert($search_error);} ?>
                <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
                <?php if(isset($edit_message) && !empty($edit_message)){echo error_alert($edit_message);} ?>
                <?php if(isset($success_message) && $success_message!=0){echo success_alert($success_message." student registration/s is deleted");} ?>
                <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Semester Registrations</h3></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <!--<form method="post" action="all_registration.php">-->
                                    <div class="form-group col-md-4">
                                        <label for="txt_std_id">Student ID:</label>
                                        <input type="text" class="form-control" id="txt_search_reg_std_id" placeholder="0000" name="txt_search_reg_std_id"  >
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label for="txt_std_fullname" >Name:</label>
                                        <input type="text" class="form-control" id="txt_searchstd_name"  placeholder="firstname" name="txt_searchstd_name" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="txt_std_class">Class:</label>
                                        <select  class="form-control" name="ddl_searchstd_class" id="ddl_searchstd_class">
                                            <option value="" selected>Select Class</option>
                                            <?php $all_classes=get_all_classes();
                                            while($classs = $all_classes->fetch_assoc()){
                                                echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-default btn-lg" onclick="search_sms_registrations()" id="search_sms_reg" value="search_sms_reg" name="search_sms_reg"><span class="glyphicon glyphicon-search"></span> Search</button>
                                    </div>
                                <!--</form>-->

                            </div><!--panel body-->
                        </div><!--panel default-->

                    </div><!--panel body-->
                </div><!--panel default-->

            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form role="form">
                            <table class="table table-bordered" id="tbl_std_reg_result">
                                <thead>
                                <tr>

                                    <!--<td><b>No.</b> </td>-->
                                    <td><b>ID No.</b> </td>
                                    <td><b>FullName </b></td>
                                    <td><b>Class </b></td>
                                    <td><b>Academic Year </b></td>
                                    <td><b>Semester</b></td>
                                    <td><b>Remark</b></td>
                                    <td><b>Edit</b></td>
                                    <td><b>delete</b></td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                            <button type="submit" class="btn btn-default btn-md" name="btn_edit_sms_reg"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                            <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_del_sms_reg" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        </form>
                    </div><!--panel body-->
                </div><!--panel-->
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->


<?php require_once('../../includes/layout/footer.php');?>


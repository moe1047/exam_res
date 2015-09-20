<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->
<!-- Navigation -->
<?php $page='add_department';
require_once('../../includes/layout/nav.php');?>
<?php

if(isset($_POST['submit_dep'])){
    $error=array();

    if(!has_existence($_POST['txt_adddep_name'])){
        $error['txt_adddep_name']="department name is required";
    }elseif(invalid_char($_POST['txt_adddep_name'])){
        $error['txt_adddep_name']="there's an invalid characters in the department name";
    }else{
        $dep_name=test_input($_POST['txt_adddep_name']);
    }

    if(!has_existence($_POST['txt_adddep_location'])){
        $error['txt_adddep_location']="location is required";
    }elseif(invalid_char($_POST['txt_adddep_location'])){
        $error['txt_adddep_location']="there's an invalid characters in the location";
    }else{
        $dep_location=test_input($_POST['txt_adddep_location']);
    }

    if(empty($error)){
        $add_dep_message=insert_into_department($dep_name,$dep_location);
        if(isset($add_dep_message['true'])){$message_dep_no_error="added successfully";}
        if(isset($add_dep_message['false'])){$error['DB']=$add_dep_message['false']['message'];}
    }

}
if(isset($_GET['btn_delete_dep'])){
    $delete_error=array();
    if(isset($_GET['chk_delete_dep'])){
        $success_message=0;
        foreach($_GET['chk_delete_dep'] as $dep_id){
            $delete_message=delete_department($dep_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_error']=$delete_message['false'];}
        }
    }else{$delete_error['del_error']="No row is selected to delete";}
}
if(isset($_GET['btn_edit_dep'])){
    if(isset($_GET['radio_edit_dep'])){
        redirect_to('edit_dep_form.php?dep_id='.$_GET['radio_edit_dep']);
    }else{$edit_message['edit_error']="No row is selected to edit";}
}



?>

<div id="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <?php  if(isset($message_dep_no_error)){echo success_alert($message_dep_no_error);} ?>
                <?php if(isset($error) && !empty($error)){echo error_alert($error);} ?>

                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Add Department</h3></div>
                    <div class="panel-body">

                        <form role="form" method="post" name="add_dep_form">
                            <div class="form-group">
                                <label for="txt_dprtmnt_name">Department Name:</label>
                                <input type="text" class="form-control" id="txt_dprtmnt_name" placeholder="management / ICT /DS" name="txt_adddep_name" required>
                            </div>
                            <div class="form-group">
                                <label for="txt_dprtmnt_name">Department Location:</label>
                                <input type="text" class="form-control" id="txt_dprtmnt_name"  name="txt_adddep_location" required>
                            </div>


                            <button type="submit" name="submit_dep" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-floppy-save"></span> Submit</button>
                        </form>

                    </div><!--/panel body-->
                </div><!--/panel-->
            </div>
            <div class="col-md-6">
                <!--message returned from executing the edit function-->
                <?php $warning_message="there may be classes that is assigned to this department ";
                if(isset($delete_error['db_error'])){echo warning_alert($warning_message);} ?>
                <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error['db_error']);} ?>
                <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." Department/s is deleted");} ?>
                <?php if(isset($edit_message['edit_error'])){echo error_alert($edit_message);} ?><!--message returned from executing the edit function-->
                <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>

                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Edit / delete Department</h3></div>
                    <div class="panel-body">
                        <form role="form" method="get">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td><b>Department Name</b> </td>
                                    <td><b>Department Location</b> </td>
                                    <td><b>Edit</b></td>
                                    <td><b>delete</b></td>
                                </tr>
                                </thead>
                                <?php
                                $dep_results=get_all_departments();
                                while($dep_row=$dep_results->fetch_assoc()){
                                    $table_row="<tr>";
                                    $table_row.="<td>".$dep_row['dep_name']."</td>";
                                    $table_row.="<td>".$dep_row['location']."</td>";
                                    $table_row.="<td><input type='radio' value='".$dep_row['dep_id']."' name='radio_edit_dep'></td>";
                                    $table_row.="<td><input type='checkbox' name='chk_delete_dep[]' value='".$dep_row['dep_id']."'></td>";
                                    $table_row.="<tr>";
                                    echo $table_row;
                                }

                                ?>

                            </table>
                            <button type="submit" class="btn btn-default btn-md" name="btn_edit_dep"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                            <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_dep" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        </form>
                    </div><!--/panel body-->
                </div><!--/panel-->





            </div><!--col-4-->
        </div>
    </div>
</div>
<!-- /#page-wrapper -->


<?php require_once('../../includes/layout/footer.php');?>
<script type="text/javascript">
    var frmvalidator =new Validator("add_dep_form");
frmvalidator.addValidation("txt_adddep_name","req","department name is required");
frmvalidator.addValidation("txt_adddep_name","alphabetic","department name should Only be alphabetic characters");

frmvalidator.addValidation("txt_adddep_location","req","department location is required");
frmvalidator.addValidation("txt_adddep_location","alphanumeric","location should be alphanumeric");
//first name
    function ConfirmDelete()
    {
        var x = confirm("Are you sure you want to delete?");
        if (x)
            return true;
        else
            return false;
    }
</script>

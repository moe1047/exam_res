<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');
$page="add_semester";?>


<!--/header-->
<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
/*
 *
 *
 */
if(isset($_REQUEST['btn_delete_sms'])){
    $delete_error=array();
    if(isset($_REQUEST['chk_delete_sms'])){
        $success_message=0;
        foreach($_REQUEST['chk_delete_sms'] as $sms_id){
            $delete_message=delete_semester($sms_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['del_error']="No row is selected to delete";}
}
if(isset($_REQUEST['btn_edit_sms'])){
    if(isset($_REQUEST['radio_edit_sms'])){
        redirect_to('edit_sms_form.php?semester_id='.$_REQUEST['radio_edit_sms']);
    }else{$edit_message['not_selected']="No row is selected to edit";}
}
if(isset($_POST['submit_sms'])){
    if(has_existence($_REQUEST['ddl_sms_name'])){
        $sms_name=$_REQUEST['ddl_sms_name'];
    }else $message['ddl_sms_name']="semester name is required";

    if(has_existence($_REQUEST['txt_ac_from'])){
        if(is_numeric($_REQUEST['txt_ac_from'])){
            $ac_from=$_REQUEST['txt_ac_from'];
        }else{$message['txt_ac_from']="ac year from should be a number";}
    }else $message['txt_ac_from']="ac year from cannot be empty";

    if(has_existence($_REQUEST['txt_ac_to'])){
        if(is_numeric($_REQUEST['txt_ac_to'])){
            $ac_to=$_REQUEST['txt_ac_to'];
        }else{$message['txt_ac_to']="ac year to should be a number";}
    }else $message['txt_ac_to']="ac year to cannot be empty";




    if (empty($message)){
        $message_returned=insert_into_semester($sms_name,$ac_from,$ac_to);
        if(isset($message_returned['true'])){$message_success_submit="added successfully";}
        if(isset($message_returned['false'])){$message['error_message']=$message_returned['false']['message'];}
    }
}
?>
<div id="page-wrapper">

    <div class="row">
        <div class="col-md-6">
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
            <?php if(isset($edit_message) && !empty($edit_message)){echo error_alert($edit_message);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." Semester/s is deleted");} ?>


            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Edit / delete Mark</h3></div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td><b>semester name</b> </td>
                                <td><b>Academic year from</b> </td>
                                <td><b>Academic year to</b></td>
                                <td><b>Edit</b></td>
                                <td><b>Delete</b></td>
                            </tr>
                            </thead>
                            <?php
                            $sms_results=get_all_semesters();
                            while($sms_row=$sms_results->fetch_assoc()){
                                $table_row="<tr>";
                                $table_row.="<td>".$sms_row['semester_name']."</td>";
                                $table_row.="<td>".$sms_row['ac_year_from']."</td>";
                                $table_row.="<td>".$sms_row['ac_year_to']."</td>";
                                $table_row.="<td><input type='radio' value='".$sms_row['semester_id']."' name='radio_edit_sms'></td>";
                                $table_row.="<td><input type='checkbox' name='chk_delete_sms[]' value='".$sms_row['semester_id']."'></td>";
                                $table_row.="<tr>";
                                echo $table_row;
                            }
                            ?>
                        </table>
                        <button type="submit" class="btn btn-default btn-md" name="btn_edit_sms"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_sms" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                </div><!--/panel body-->
            </div><!--/panel-->
        </div><!--mod-6-->
        <div class="col-md-4">
            <?php if(isset($message) && !empty($message)){echo error_alert($message);} ?>
            <?php if(isset($message_success_submit)){echo success_alert($message_success_submit);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Semester</h3></div>
                <div class="panel-body">

                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="ddl_sms_name">Semester Name:</label>
                            <select  id="ddl_sms_name" class="form-control" name="ddl_sms_name" required>
                                <option>semester1</option>
                                <option>semester2</option>
                                <option>semester3</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="txt_ac_from">Academic year from:</label>
                            <input type="text" class="form-control" id="txt_ac_from" placeholder="YYYY" name="txt_ac_from" required >
                        </div>
                        <div class="form-group">
                            <label for="txt_ac_to">Academic year to:</label>
                            <input type="text" class="form-control" id="txt_ac_to" placeholder="YYYY" name="txt_ac_to" required>
                        </div>


                        <button type="submit" name="submit_sms" class="btn btn-default btn-lg">Submit</button>
                    </form>

                </div><!--/panel body-->
            </div><!--/panel-->



</div><!--/col-md-6-->
        </div><!--/row-->
        <!-- /#page-wrapper -->


        <?php require_once('../../includes/layout/footer.php');?>
    <script>
        function ConfirmDelete()
        {
            var x = confirm("Are you sure you want to delete?");
            if (x)
                return true;
            else
                return false;
        }
    </script>


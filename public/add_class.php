<?php require_once('../includes/functions.php');?>
<?php require_once('../includes/validation_functions.php');?>
<?php require_once('../includes/layout/header.php');?>
<!--/header-->
<!-- Navigation -->
<?php $page='add_class';
require_once('../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if(isset($_GET['btn_delete_cls'])){
    $delete_error=array();
    if(isset($_GET['chk_delete_cls'])){
        $success_message=0;
        foreach($_GET['chk_delete_cls'] as $class_id){
            $delete_message=delete_class($class_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_error']=$delete_message['false'];}
        }
    }else{$delete_error['del_error']="No row is selected to delete";}
}
if(isset($_GET['btn_edit_cls'])){
    if(isset($_GET['radio_edit_cls'])){
        redirect_to('edit_class_form.php?class_id='.$_GET['radio_edit_cls']);
    }else{$edit_message['edit_error']="No row is selected to edit";}
}
if(isset($_POST['submit_cls'])){
    if(isset($_POST['ddl_addcls_shift'])){
        $message=array();
            $message_returned=insert_into_class($_REQUEST['txt_addcls_name'],$_REQUEST['ddl_addcls_Dep'],$_REQUEST['txt_addcls_Gyear'],
                $_REQUEST['ddl_addcls_section'],$_REQUEST['ddl_addcls_shift']);
            if(isset($message_returned['true'])){$message['no error']="added successfully";}
            if(isset($message_returned['false'])){$message['error_message']=$message_returned['false']['message']/*array*/;}

    }else{$message['shift']="select shift";}
}
?>
<div id="page-wrapper">

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
            <?php if(isset($message['error_message'])){echo error_alert($message);} ?>
            <?php //if(isset($message['shift'])){echo error_alert($message);} ?>
            <?php if(isset($message['no error'])){echo success_alert($message['no error']);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add class</h3></div>
                <div class="panel-body">

                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="txt_class_name">Class Name:</label>
                            <input type="text" class="form-control" id="txt_class_name" placeholder="management / ICT /DS" name="txt_addcls_name" required>
                        </div>
                        <div class="form-group">
                            <label for="txt_class_Department">Department:</label>
                            <select id="txt_class_Department" class="form-control" name ="ddl_addcls_Dep"  >
                                <option value="" selected>Select Department</option>
                                <?php $all_deps=get_all_departments();
                                while($department = $all_deps->fetch_assoc()){
                                    echo "<option value=".$department['dep_id'].">".$department['dep_name']." ".$department['location']."</option>";
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txt_class_Gyear">Graduation Year:</label>
                            <input type="text" class="form-control" id="txt_class_Gyear" placeholder="YYYY / MM /DD " name="txt_addcls_Gyear" required>
                        </div>

                        <div class="form-group">
                            <label for="txt_class_shift">Class Shift:</label>
                            <select id="txt_class_shift" class="form-control" name ="ddl_addcls_shift"  >
                                <option value="afternoon" >afternoon</option>
                                <option value="morning" >morning</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txt_class_section">Section:</label>
                            <select id="txt_class_section" class="form-control" name ="ddl_addcls_section"  >
                                <option value="A" >A</option>
                                <option value="B" >B</option>
                                <option value="C" >C</option>
                                <option value="D" >D</option>
                                <option value="E" >E</option>
                            </select>
                        </div>
                        <button type="submit" name="submit_cls" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-floppy-save"></span> Submit</button>
                    </form>

                </div><!--/panel body-->
            </div><!--/panel-->
        </div>
        <div class="col-md-6">
            <?php $warning_message="there might be students that's registered to this class <br> OR there's courses assigned to this class";
            if(isset($delete_error['db_error'])){echo warning_alert($warning_message);} ?>
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error['db_error']);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." Class/es is deleted");} ?>
            <?php //if(isset($edit_message['edit_error'])){echo error_alert($edit_message);} ?><!--message returned from executing the edit function-->
            <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Edit / delete Class</h3></div>
                <div class="panel-body">
                    <form role="form" method="get" name="add_class_form">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td><b>Class Name</b> </td>
                                <td><b>Class Shift</b> </td>
                                <td><b>graduation_year</b> </td>
                                <td><b>section</b> </td>
                                <td><b>Edit</b></td>
                                <td><b>delete</b></td>
                            </tr>
                            </thead>
                            <?php
                            $class_results=get_all_classes();
                                while($class_row=$class_results->fetch_assoc()){
                                    $table_row="<tr>";
                                    $table_row.="<td>".$class_row['class_name']."</td>";
                                    $table_row.="<td>".$class_row['shift']."</td>";
                                    $table_row.="<td>".$class_row['graduation_year']."</td>";
                                    $table_row.="<td>".$class_row['section']."</td>";
                                    $table_row.="<td><input type='radio' value='".$class_row['class_id']."' name='radio_edit_cls'></td>";
                                    $table_row.="<td><input type='checkbox' name='chk_delete_cls[]' value='".$class_row['class_id']."'></td>";
                                    $table_row.="<tr>";
                                    echo $table_row;
                                }

                            ?>
                        </table>
                        <button type="submit" class="btn btn-default btn-md" name="btn_edit_cls"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_cls" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                </div><!--/panel body-->
            </div><!--/panel-->





            </div><!--col-4-->
        </div><!--/row-->
        <!-- /#page-wrapper -->


        <?php require_once('../includes/layout/footer.php');?>
    <script>
    function ConfirmDelete()
    {
    var x = confirm("Are you sure you want to delete?");
    if (x)
    return true;
    else
    return false;
    }


    var frmvalidator  = new Validator("add_class_form");
    frmvalidator.addValidation("txt_std_id","req","ID is required");
    frmvalidator.addValidation("txt_std_id","num","ID is should be a number");
    //first name
    frmvalidator.addValidation("txt_std_Fname","req","first Name is required");
    frmvalidator.addValidation("txt_std_Fname","alphabetic","Only alphabetic characters");

    frmvalidator.addValidation("txt_std_Mname","req","Middle Name is required");
    frmvalidator.addValidation("txt_std_Mname","alphabetic","Middle Name is should be an alphanumeric ");
    </script>

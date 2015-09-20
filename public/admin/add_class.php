<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php require_once('../../includes/pagination.php');?>
<!--/header-->
<!-- Navigation -->
<?php $page='add_class';
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if(isset($_GET['btn_delete_cls'])){
    $delete_error=array();
    if(isset($_GET['chk_delete_cls'])){
        $success_message=0;
        foreach($_GET['chk_delete_cls'] as $class_id){
            $delete_message=delete_class($class_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_error']=$delete_message['false']['message'];}
        }
    }
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
                $_REQUEST['ddl_addcls_section'],$_REQUEST['ddl_addcls_shift'],$_REQUEST['txt_addcls_batch']);
            if(isset($message_returned['true'])){$message['no error']="added successfully";}
            if(isset($message_returned['false'])){$message['error_message']=$message_returned['false']['message']/*array*/;}

    }else{$message['shift']="select shift";}
}
?>
<div id="page-wrapper">
    <div class="row">

        <div class="col-md-4 col-md-offset-1">
            <?php if(isset($message['error_message'])){echo error_alert($message);} ?>
            <?php //if(isset($message['shift'])){echo error_alert($message);} ?>
            <?php if(isset($message['no error'])){echo success_alert($message['no error']);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add class</h3></div>
                <div class="panel-body">

                    <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <div class="form-group">
                            <label for="txt_class_name">Class Name:</label>
                            <input type="text" class="form-control" id="txt_class_name" placeholder="management / ICT /DS" name="txt_addcls_name" required>
                        </div>
                        <div class="form-group">
                            <label for="txt_class_Department">Department:</label>
                            <select id="txt_class_Department" class="form-control" name ="ddl_addcls_Dep" required >
                                <option value="" selected>Select Department</option>
                                <?php $all_deps=get_all_departments();
                                while($department = $all_deps->fetch_assoc()){
                                    echo "<option value=".$department['dep_id'].">".$department['dep_name']." ".$department['location']."</option>";
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txt_class_Gyear">Batch:</label>
                            <input type="number" class="form-control" id="txt_class_Gyear" placeholder="number" name="txt_addcls_batch" required>
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
        <!--<div class="col-md-1">

        </div>-->
        <div class="col-md-6">
            <div class="panel panel-default"><!--pagination-->
                <div class="panel-body">
                    <?php
                    $class_curr_page=!empty($_GET['class_page'])?(int)$_GET['class_page']:1;
                    $per_page=8;
                    $total_class=get_total_class();//returns a digit
                    $class_page=new Pagination($class_curr_page,$per_page,$total_class);

                    /*echo "<ul class='pagination'>";
                    for($i=1;$i<=$class_page->total_pages();$i++){
                        echo "<li><a class='text-center' href='add_class.php?class_page=".$i."'>".$i."</a><li><br>";
                    }
                    echo "</ul>";*/

                    ?>
                    <div class="col-md-7 col-md-offset-3">
                    <ul class="pagination pagination-sm center-block ">
                        <li <?php if(!$class_page->has_previous_page()){echo "class='disabled';";} ?>>
                            <a href="add_class.php?class_page=<?php if($class_page->has_previous_page()){echo $class_page->previous_page();}else{echo $class_curr_page; } ?>" aria-label="Previous">
                                <span aria-hidden="true"><< Previous</span>
                            </a>
                        </li>
                        <?php
                        for($i=1;$i<=$class_page->total_pages();$i++){
                            echo "<li><a class='text-center' href='add_class.php?class_page=".$i."'>".$i."</a><li>";
                        }
                        ?>
                        <li <?php if(!$class_page->has_next_page()){echo "class='disabled';";} ?>>
                            <a href="add_class.php?class_page=<?php if($class_page->has_next_page()){echo $class_page->next_page();}else{echo $class_curr_page;}?>"
                               aria-label="Next">
                                <span aria-hidden="true">Next >></span>
                            </a>
                        </li>
                    </ul>
                    </div>

                </div>
            </div><!--/pagination-->

            <?php $warning_message="there might be students that's registered to this class <br> OR there's courses assigned to this class";
            if(isset($delete_error['db_error'])){echo warning_alert($warning_message);} ?>
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){
                echo del_success_sweet_alert($success_message." Class/es has been deleted");
            }?>

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
                                <td><b>Graduation_year</b> </td>
                                <td><b>Section</b> </td>
                                <td><b>Batch</b> </td>
                                <td><b>Edit</b></td>
                                <td><b>delete</b></td>
                            </tr>
                            </thead>
                            <?php


                            $class_results=get_all_classes_limit($per_page,$class_page->offset());
                                while($class_row=$class_results->fetch_assoc()){
                                    $table_row="<tr>";
                                    $table_row.="<td>".$class_row['class_name']."</td>";
                                    $table_row.="<td>".$class_row['shift']."</td>";
                                    $table_row.="<td>".$class_row['graduation_year']."</td>";
                                    $table_row.="<td>".$class_row['section']."</td>";
                                    $table_row.="<td>".$class_row['batch']."</td>";
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


        <?php require_once('../../includes/layout/footer.php');?>

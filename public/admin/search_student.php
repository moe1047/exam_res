<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php require_once('../../includes/pagination.php');?>

<!--/header-->
<!-- Navigation -->
<?php $page="search_student";
require_once('../../includes/layout/nav.php');?>
<?php
if(isset($_REQUEST['search_std'])){
    $search_result=search_student($_REQUEST['txt_searchstd_id'],$_REQUEST['txt_searchstd_name'],$_REQUEST['ddl_searchstd_class']);
    if (isset($search_result['false'])){$search_error=$search_result['false'];}
    if(isset($search_result['rows'])){$search_rows=$search_result['rows'];}
}
if(isset($_REQUEST['btn_delete_std'])){
    if(isset($_REQUEST['chk_delete_std']) && !empty($_REQUEST['chk_delete_std'])){
        $delete_error=array();
        $success_message=0;
        foreach($_REQUEST['chk_delete_std'] as $std_id){
            $delete_message=delete_student($std_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_del_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['check_del_error']="No row is selected to delete";}
}
if(isset($_REQUEST['btn_edit_std'])){
    if(isset($_REQUEST['radio_edit_std'])){
        redirect_to('edit_student_form.php?student_id='.$_REQUEST['radio_edit_std']);
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
                <?php if(isset($success_message) && $success_message!=0){echo success_alert($success_message." student/s is deleted");} ?>
                <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Search student</h3></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form method="post" action="search_student.php">
                                <div class="form-group col-md-4">
                                    <label for="txt_std_id">Student ID:</label>
                                    <input type="text" class="form-control" id="txt_std_id" placeholder="0000" name="txt_searchstd_id"  >
                                </div>
                                <div class="form-group col-md-4" >
                                    <label for="txt_std_fullname" >Name:</label>
                                    <input type="text" class="form-control" id="txt_std_fullname"  placeholder="firstname" name="txt_searchstd_name" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="txt_std_class">Class:</label>
                                    <select  class="form-control" name="ddl_searchstd_class" >
                                        <option value="" selected>Select Class</option>
                                        <?php $all_classes=get_all_classes();
                                        while($classs = $all_classes->fetch_assoc()){
                                            echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-default btn-lg col-md-offset-5" name="search_std"><span class="glyphicon glyphicon-search"></span> Search</button>
                                </div>
                                </form>
                                </div><!--panel body-->
                            </div><!--panel default-->

                    </div><!--panel body-->
                    </div><!--panel default-->

            </div>



            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                <form role="form">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td><b>ID No.</b></td>
                            <td><b>Fullname</b></td>
                            <td><b>Class </b></td>
                            <td><b>number</b></td>
                            <td><b>remark</b></td>
                            <td><b>active</b></td>
                            <td><b>Edit</b></td>
                            <td><b>delete</b></td>
                        </tr>
                        </thead>
                        <?php
                        if(isset($search_rows) && is_object($search_rows)){
                            while($student_row=$search_rows->fetch_assoc()){
                                if($student_row['active']==true){$active="Yes";}else{$active="No";}
                                $table_row="<tr>";
                                $table_row.="<td>".$student_row['student_id']."</td>";
                                $table_row.="<td>".$student_row['first_name']." ".$student_row['middle_name']." ".$student_row['last_name']."</td>";
                                $table_row.="<td>".$student_row['class_name']." ".$student_row['shift']." ".$student_row['section']." ".$student_row['batch']."</td>";
                                $table_row.="<td>".$student_row['phone_number']."</td>";
                                $table_row.="<td>".$student_row['remark']."</td>";
                                $table_row.="<td>".$active."</td>";
                                $table_row.="<td><input type='radio' value='".$student_row['student_id']."' name='radio_edit_std'></td>";
                                $table_row.="<td><input type='checkbox' name='chk_delete_std[]' value='".$student_row['student_id']."'></td>";
                                $table_row.="<tr>";
                                echo $table_row;
                            }
                        }
                        ?>
                    </table>
                    <button type="submit" class="btn btn-default btn-md" name="btn_edit_std"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                    <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_std" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                </form>
                        <!--<div>

                            <?php
                            /*$std_curr_page=!empty($_GET['class_page'])?(int)$_GET['class_page']:1;
                            $per_page=13;
                            $total_class=get_total_class();//returns a digit
                            $std_page=new Pagination($std_curr_page,$per_page,$total_class);

                            /*echo "<ul class='pagination'>";
                            for($i=1;$i<=$class_page->total_pages();$i++){
                                echo "<li><a class='text-center' href='add_class.php?class_page=".$i."'>".$i."</a><li><br>";
                            }
                            echo "</ul>";*/

                            ?>
                            <div class="col-md-7 col-md-offset-4">
                                <ul class="pagination pagination-sm center-block ">
                                    <li <?php //if(!$std_page->has_previous_page()){echo "class='disabled';";} ?>>
                                        <a href="add_class.php?class_page=<?php //if($std_page->has_previous_page()){echo $std_page->previous_page();}else{echo $class_curr_page; } ?>" aria-label="Previous">
                                            <span aria-hidden="true"><< Previous</span>
                                        </a>
                                    </li>
                                    <?php
                                    //for($i=1;$i<=$std_page->total_pages();$i++){
                                        //echo "<li><a class='text-center' href='add_class.php?class_page=".$i."'>".$i."</a><li>";
                                    //}
                                    ?>
                                    <li <?php //if(!$std_page->has_next_page()){echo "class='disabled';";} ?>>
                                        <a href="add_class.php?class_page=<?php //if($std_page->has_next_page()){echo $std_page->next_page();}else{echo $class_curr_page;}?>"
                                           aria-label="Next">
                                            <span aria-hidden="true">Next >></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>-->

            </div><!--panel body-->
                    </div><!--panel-->
                </div>



        </div>
    </div>
</div>
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

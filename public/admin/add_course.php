<?php require_once('../../includes/layout/header.php');
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php require_once('../../includes/pagination.php');?>

<?php
$page="add_course";
?>
<!--/header-->
<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if(isset($_GET['btn_delete_crs'])){
    $delete_error=array();
    if(isset($_GET['chk_delete_crs'])){
        $success_message=0;
        foreach($_GET['chk_delete_crs'] as $course_id){
            $delete_message=delete_course($course_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['del_error']="No row is selected to delete";}
}
if(isset($_GET['btn_edit_crs'])){
    if(isset($_GET['radio_edit_crs'])){
        redirect_to('edit_course_form.php?course_id='.$_GET['radio_edit_crs']);
    }else{$edit_message['edit_error']="No row is selected to edit";}
}
if(isset($_POST['submit_crs'])){
    $message=array();
    if(has_existence($_REQUEST['txt_addcrs_name'])){
        $course_name=$_REQUEST['txt_addcrs_name'];
    }else $message['course_name']="course name is not set";

    if(has_existence($_REQUEST['txt_addcrs_code'])){
        $course_code=$_REQUEST['txt_addcrs_code'];
    }else $message['course_code']="course code is not set";
    if (!isset($message['course_code']) && !isset($message['course_name'])){
        $message_returned=insert_into_course($_REQUEST['txt_addcrs_name'],$_REQUEST['txt_addcrs_code']);
        if(isset($message_returned['true'])){$message_success="added successfully";}
        if(isset($message_returned['false'])){$message['error_message']=$message_returned['false']['message'];}
    }
    }
?>

<div id="page-wrapper">

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default"><!--pagination-->
                <div class="panel-body">
                    <?php
                    $course_curr_page=!empty($_GET['course_page'])?(int)$_GET['course_page']:1;
                    $per_page=8;//no of courses per page
                    $total_course=get_total_courses();//returns a digit
                    $course_page=new Pagination($course_curr_page,$per_page,$total_course);

                    ?>
                    <div class="col-md-7 col-md-offset-3">
                        <ul class="pagination pagination-sm center-block">
                            <li <?php if(!$course_page->has_previous_page()){echo "class='disabled';";} ?>>
                                <a href="add_course.php?course_page=<?php if($course_page->has_previous_page()){echo $course_page->previous_page();}else{echo $course_curr_page; } ?>" aria-label="Previous">
                                    <span aria-hidden="true"><< Previous</span>
                                </a>
                            </li>
                            <?php
                            for($i=1;$i<=$course_page->total_pages();$i++){
                                echo "<li><a class='text-center' href='add_course.php?course_page=".$i."'>".$i."</a><li>";
                            }
                            ?>
                            <li <?php if(!$course_page->has_next_page()){echo "class='disabled';";} ?>>
                                <a href="add_course.php?course_page=<?php if($course_page->has_next_page()){echo $course_page->next_page();}else{echo $course_curr_page;}?>"
                                   aria-label="Next">
                                    <span aria-hidden="true">Next >></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div><!--/pagination-->
            <?php $warning_message="the course or the courses you've selected to delete is being used in other places";
            if(isset($delete_error['db_error'])){echo warning_alert($warning_message);} ?>
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." course/es is deleted");} ?>
            <?php if(isset($edit_message['edit_error'])){echo error_alert($edit_message);} ?><!--message returned from executing the edit function-->
            <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Edit / delete Course</h3></div>
                <div class="panel-body">
                    <form role="form" method="get">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td><b>Course Name</b> </td>
                                <td><b>Course Code</b> </td>
                                <td><b>Edit</b></td>
                                <td><b>delete</b></td>
                            </tr>
                            </thead>
                            <?php
                            $course_results=get_all_courses_limit($per_page,$course_page->offset());
                            while($course_row=$course_results->fetch_assoc()){
                                $table_row="<tr>";
                                $table_row.="<td>".$course_row['course_name']."</td>";
                                $table_row.="<td>".$course_row['course_code']."</td>";
                                $table_row.="<td><input type='radio' value='".$course_row['course_id']."' name='radio_edit_crs'></td>";
                                $table_row.="<td><input type='checkbox' name='chk_delete_crs[]' value='".$course_row['course_id']."'></td>";
                                $table_row.="<tr>";
                                echo $table_row;
                            }
                            ?>
                        </table>
                        <button type="submit" class="btn btn-default btn-md" name="btn_edit_crs"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_crs" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                </div><!--/panel body-->
            </div><!--/panel-->
        </div><!--col-4-->
        <div class="col-md-4">
            <?php if(isset($message) && !empty($message)){echo error_alert($message);} ?>
            <?php //if(isset($message['shift'])){echo error_alert($message);} ?>
            <?php if(isset($message_success)){echo success_alert($message_success);} ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add course</h3></div>
                <div class="panel-body">

                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="txt_crs_name">Course Name:</label>
                            <input type="text" class="form-control" id="txt_crs_name" placeholder="management / into to ICT" name="txt_addcrs_name" >
                        </div>
                        <div class="form-group">
                            <label for="txt_crs_name">Course Code:</label>
                            <input type="text" class="form-control" id="txt_crs_name" placeholder="ict 202 / mgt 201" name="txt_addcrs_code" >
                        </div>
                        <button type="submit" name="submit_crs" class="btn btn-default btn-lg">Submit</button>
                    </form>

                </div><!--/panel body-->
            </div><!--/panel-->




        </div><!--/row-->
        <!-- /#page-wrapper -->


        <?php require_once('../../includes/layout/footer.php');?>



<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php $page="edit_grades";
require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>


<!-- /Navigation -->
<?php if(isset($_POST['search_grade'])) {
    $search_result=search_result($_POST['search_grade_id'],$_POST['search_grade_cls'],$_POST['search_grade_sms'],$_POST['search_grade_date'],$_POST['search_grade_crs']);
    if (isset($search_result['false'])){$search_error=$search_result['false'];}
    if(isset($search_result['rows'])){$search_rows=$search_result['rows'];}
}
if(isset($_GET['btn_edit'])){
    if(isset($_GET['radio_edit'])){
        $_SESSION['edit_grade_id']=$_GET['radio_edit'];
        redirect_to('edit_grade_form.php?grade_id='.$_GET['radio_edit']);

    }else{$edit_error['edit_error']="No row is selected to edit";}
}
if(isset($_GET['btn_delete'])){
    $delete_error=array();

    if(isset($_GET['chk_delete'])){
        $success_message=0;
        foreach($_GET['chk_delete'] as $grade_id){
             $delete_message=delete_grade($grade_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error=$delete_message['false'];}

        }



    }else{$delete_error['del_error']="No row is selected to delete";}

}
?>

<div class="container" >
    <div id="page-wrapper">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-9 "><!--upload section-->
                        <?php if(isset($search_error ) && !empty($search_error['message']) ){echo error_alert($search_error);} ?>
                        <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
                        <?php if(isset($success_message) && $success_message!=0){echo success_alert($success_message." grade/s is deleted");} ?>
                        <?php if(isset($edit_error)){echo error_alert($edit_error);} ?>
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Search student Grade</h3></div>

                            <div class="panel-body">
                                <form role="form" method="post">
                                    <div class="form-group col-md-3">
                                        <label for="search_grade_id">student id:</label>
                                        <input type="number" class="form-control input-sm" name="search_grade_id" id="search_grade_id" placeholder="0000">

                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="search_grade_cls">Class:</label>
                                        <select  id="search_grade_cls" class="form-control input-sm" name="search_grade_cls" onchange="get_courses_search(this.value)">
                                            <option value="" selected>all Classes</option>
                                            <?php $all_classes=get_all_classes();
                                            while($classs = $all_classes->fetch_assoc()){
                                                echo "<option value=".$classs['class_id'].">".$classs['class_name']." ".$classs['shift']."</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="search_grade_crs">Course:</label>
                                        <select  id="search_grade_crs" class="form-control input-sm" name="search_grade_crs">
                                            <option value="" selected>all course</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="search_grade_sms">Semester:</label>
                                        <select id="search_grade_sms" name="search_grade_sms" class="form-control input-sm" >
                                            <option selected value="">all semesters</option>
                                            <?php $ac_year=get_ac_year();

                                            while($ac_year_row=$ac_year->fetch_assoc()){
                                                //echo $ac_year_row['ac_year_from']."<br>";
                                                //echo $ac_year_row['ac_year_to'];
                                                $semester=get_all_semestersby_ac($ac_year_row['ac_year_from'],$ac_year_row['ac_year_to']);
                                                echo "<optgroup label='".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']."'>";
                                                while($semester_row=$semester->fetch_assoc()){
                                                    echo "<option value=".$semester_row['semester_id'].">".$ac_year_row['ac_year_from']."-".$ac_year_row['ac_year_to']." ".$semester_row['semester_name']."</option>";
                                                }
                                                echo "</optgroup>";

                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="search_grade_date">submitted date:</label>
                                        <input id="search_grade_date" name="search_grade_date" type="date" class="form-control input-sm">
                                        <div class="help-block">[Optional]</div>
                                    </div><div class="col-md-12"></div>
                                    <button type="submit" class="btn btn-default btn-md col-md-3" name="search_grade"><span class="glyphicon glyphicon-search"></span> Search</button>
                                </form>
                            </div><!--/ body panel -->
                        </div><!--/  panel -->
                    </div><!--/  col-md-12 -->
                </div><!--/  rows -->
            </div><!--container-->


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Edit / delete grade</h3></div>
                    <div class="panel-body">

                    </div><!--/panel bod<form role="form">
                            <table class="table table-hover">
                            <thead>
                            <tr>
                                <td><b>ID No.</b> </td>
                                <td><b>Fullname</b> </td>
                                <td><b>Class </b></td>
                                <td><b>Course </b></td>
                                <td><b>Course Code </b></td>
                                <td><b>result</b></td>
                                <td><b>sub.date</b></td>
                                <td><b>Edit</b></td>
                                <td><b>delete</b></td>
                            </tr>
                            </thead>
                            <?php
                    if(isset($search_rows)){
                        while($grade_row=$search_rows->fetch_assoc()){
                            $table_row="<tr>";
                            $table_row.="<td>".$grade_row['student_id']."</td>";
                            $table_row.="<td>".$grade_row['student_name']."</td>";
                            $table_row.="<td>".$grade_row['class_name']." ".$grade_row['shift']."</td>";
                            $table_row.="<td>".$grade_row['course_name']."</td>";
                            $table_row.="<td>".$grade_row['course_code']."</td>";
                            $table_row.="<td>".$grade_row['result']."</td>";
                            $table_row.="<td>".date('Y-m-d',strtotime($grade_row['submitted_date']))."</td>";
                            $table_row.="<td><input type='radio' value='".$grade_row['grade_id']."' name='radio_edit'></td>";
                            $table_row.="<td><input type='checkbox' name='chk_delete[]' value='".$grade_row['grade_id']."'></td>";
                            $table_row.="<tr>";
                            echo $table_row;
                        }
                    }
                    ?>
                        </table>
                        <button type="submit" class="btn btn-default btn-md" name="btn_edit"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        </form>y-->
                </div><!--/panel-->



            </div><!--/col-md-6-->
        </div><!--/row-->
    </div><!-- /#page-wrapper -->
    </div><!--/container-->
<script>
    document.querySelector('.sweet-14').onclick = function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "error",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Danger!'
        });
    };
</script>

    <?php require_once('../../includes/layout/footer.php');?>

<script>
function get_courses_search(student_class)//to get the student for ddl_grade_class_m
{
    $.ajax({
    url: 'process_courses_search.php?class=' + student_class,
    success: function(data) {
    $("#search_grade_crs").html(data);
    }
    })
}

function ConfirmDelete()
{
    var x = confirm("Are you sure you want to delete?");
    if (x)
        return true;
    else
        return false;
}

</script>
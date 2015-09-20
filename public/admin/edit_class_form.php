<?php require_once('../../includes/layout/header.php');?>
<?php
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->

<!-- Navigation -->
<?php $page="edit_class_form";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_REQUEST['class_id'])){
    $result=get_class_by_id($_REQUEST['class_id']);
    while($class=$result->fetch_assoc()){//class_id	class_name	department_id	graduation_year	section	shift
        $class_id=$class['class_id'];
        $class_name=$class['class_name'];
        $shift=$class['shift'];
        $dep_id=$class['department_id'];
        $gra_year=$class['graduation_year'];
        $section=$class['section'];
        $batch=$class['batch'];
    }
}//request from edit class
if(isset($_REQUEST['submit_edit_cls'])){
    $message_returned=update_class($_REQUEST['txt_editcls_id'],$_REQUEST['txt_editcls_name'],$_REQUEST['ddl_editcls_dep'],$_REQUEST['txt_editcls_Gyear'],
        $_REQUEST['ddl_editcls_section'],$_REQUEST['ddl_editcls_shift'],$_REQUEST['txt_editcls_batch']);
    if (isset($message_returned['true'])){$edit_success="edited successfully";time_redirect_to('add_class.php');}
    if (isset($message_returned['false'])){$edit_error=$message_returned['false'];}
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
                            <div class="panel-heading"><h3 class="panel-title">Edit class</h3></div>
                            <div class="panel-body">

                                <form role="form" method="post">
                                    <input type="hidden" value="<?php if(isset($class_id)){echo $class_id;} ?>" name="txt_editcls_id">

                                    <div class="form-group">
                                        <label for="txt_class_name">Class Name:</label>
                                        <input type="text" class="form-control" id="txt_editcls_name" value="<?php if(isset($class_name)){echo $class_name;} ?>" placeholder="management / ICT /DS" name="txt_editcls_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_class_Department">Department:</label>
                                        <select id="txt_class_Department" class="form-control" name ="ddl_editcls_dep"  >
                                            <option value="" selected>Select Department</option>
                                            <?php $all_deps=get_all_departments();
                                            while($department = $all_deps->fetch_assoc()){
                                                echo "<option value=".$department['dep_id'];
                                                if(isset($dep_id)){if($dep_id==$department['dep_id']){echo " selected ";}}
                                                echo ">";
                                                echo $department['dep_name']." ".$department['location']."</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_class_Gyear">Graduation Year:</label>
                                        <input type="text" class="form-control" id="txt_class_Gyear" value="<?php if(isset($gra_year)){echo $gra_year;} ?>" placeholder="YYYY / MM /DD " name="txt_editcls_Gyear" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_class_Gyear">Batch:</label>
                                        <input type="number" class="form-control" id="txt_class_Gyear" value="<?php if(isset($batch)){echo $batch;} ?>"  name="txt_editcls_batch" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="txt_class_shift">Class Shift:</label>
                                        <select id="txt_class_shift" class="form-control" name ="ddl_editcls_shift"  >
                                            <option value="afternoon" <?php if(isset($shift) && $shift=="afternoon"){echo " selected ";} ?>>afternoon</option>
                                            <option value="morning" <?php if(isset($shift) && $shift=="morning"){echo " selected ";} ?>>morning</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_class_section">Section:</label>
                                        <select id="txt_class_section" class="form-control" name ="ddl_editcls_section"  >
                                            <option value="A" <?php if(isset($section) && $section=="A"){echo " selected ";}?>>A</option>
                                            <option value="B" <?php if(isset($section) && $section=="B"){echo " selected ";}?>>B</option>
                                            <option value="C" <?php if(isset($section) && $section=="C"){echo " selected ";}?>>C</option>
                                            <option value="D" <?php if(isset($section) && $section=="D"){echo " selected ";}?>>D</option>
                                            <option value="E" <?php if(isset($section) && $section=="E"){echo " selected ";}?>>E</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit_edit_cls" class="btn btn-default btn-lg">Update</button>
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



<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');?>
<?php require_once('../../includes/validation_functions.php');
admin_confirm_logged_in();?>

<!--/header-->
<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->

<div id="page-wrapper">

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add User</h3></div>
                <div class="panel-body">

                    <form role="form">
                        <div class="form-group">
                            <label for="txt_std_id">User ID:</label>
                            <input type="text" class="form-control" id="txt_student_id" placeholder="ABC123" name="txt_student_id" required>
                        </div>
                        <div class="form-group">
                            <label for="txt_usr_fullname">User full Name:</label>
                            <input type="text" class="form-control" id="txt_full_name" placeholder="first_name Second_name last_name" name="txt_student_id" required>
                        </div>
                        <div class="form-group">
                            <label for="txt_usr_pwd">password:</label>
                            <input type="password" class="form-control" id="txt_usr_pwd" placeholder="****" name="txt_usr_pwd" >
                        </div>
                        <div class="form-group">
                            <label for="txt_usr_re_pwd">re-Type password:</label>
                            <input type="password" class="form-control" id="txt_usr_re_pwd" placeholder="****" name="txt_usr_re_pwd">
                        </div>

                        <div class="form-group">
                            <label for="txt_usr_acc_group">Account Group:</label>
                            <select  class="form-control" required>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>


                        <button type="submit" class="btn btn-default btn-lg">Submit</button>
                    </form>

                </div><!--/panel body-->
            </div><!--/panel-->
            </div>
        <div class="col-md-6">
            <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
            <?php if(isset($edit_message) && !empty($edit_message)){echo error_alert($edit_message);} ?>
            <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." Mark/s is deleted");} ?>


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





        </div>
        <!--/row-->

        <!-- /#page-wrapper -->


        <?php require_once('../../includes/layout/footer.php');?>

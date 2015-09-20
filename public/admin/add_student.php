<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->
<!-- Navigation -->
<?php
$page="add_student";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
$error=array();
if(isset($_POST['submit_std'])){
    if(!is_numeric ($_POST['txt_std_id']) ){
        $error['id']="the ID should be a number";
    }elseif(!has_existence($_POST['txt_std_id'])){
        $error['id']="ID is required";
    }elseif(find_unregistered_id($_POST['txt_std_id'])){
        $error['id']="{$_POST['txt_std_id']}"." is already registered";
    }else{
        $add_id=test_input($_POST['txt_std_id']);
    }

    if(!has_existence($_POST['txt_std_Fname'])){
        $error['txt_std_Fname']="firstname is required";
    }elseif(invalid_char($_POST['txt_std_Fname'])){
        $error['txt_std_Fname']="there's an invalid characters in the firstname";
    }else{
        $add_fname=test_input($_POST['txt_std_Fname']);
    }

    if(!has_existence($_POST['txt_std_Mname'])){
        $error['txt_std_Mname']="Midname is required";
    }elseif(invalid_char($_POST['txt_std_Mname'])){
        $error['txt_std_Mname']="there's an invalid characters in the Midname";
    }else{
        $add_mname=test_input($_POST['txt_std_Mname']);
    }

    if(!has_existence($_POST['txt_std_Lname'])){
        $error['txt_std_Lname']="Last_name is required";
    }elseif(invalid_char($_POST['txt_std_Lname'])){
        $error['txt_std_Lname']="there's an invalid characters in the Last_name";
    }else{
        $add_lname=test_input($_POST['txt_std_Lname']);
    }


    if(isset($_POST['txt_std_phno'])&& !empty($_POST['txt_std_phno'])){
         $add_phno=test_input($_POST['txt_std_phno']);
    }else{$error['txt_std_phno']="ph.number is required";}


    if(!has_existence($_POST['ddl_std_class'])){
        $error['ddl_std_class']="class is required";
    }else{
        $add_class=test_input($_POST['ddl_std_class']);
    }


    if(!has_existence($_POST['txt_std_school'])){
        $error['txt_std_school']="school is required";
    }elseif(invalid_char($_POST['txt_std_school'])){
        $error['txt_std_school']="there's an invalid characters in the School";
    }else{
        $add_school=test_input($_POST['txt_std_school']);
    }

    if(!has_existence($_POST['txt_std_rno'])){
        $error['txt_std_rno']="Roll is required";
    }elseif(invalid_char($_POST['txt_std_school'])){
        $error['txt_std_rno']="there's an invalid characters in the RollNo";
    }else{
        $add_rno=test_input($_POST['txt_std_rno']);
    }


    if(!has_existence($_POST['txt_std_gr_year'])){
        $error['ddl_std_gr_year']="grade year is required";
    }elseif(invalid_char($_POST['txt_std_gr_year'])){
        $error['ddl_std_gr_year']="there's an invalid characters in the graduated_year";
    }else{
        $add_gr_year=test_input($_POST['txt_std_gr_year']);
    }

    if(!has_existence($_POST['txt_std_gr_grade'])){
        $error['ddl_std_gr_grade']="graduation grade is required";
    }elseif(invalid_char($_POST['txt_std_gr_grade'])){
        $error['ddl_std_gr_grade']="there's an invalid characters in the graduated_grade";
    }else{
        $add_gr_grade=test_input($_POST['txt_std_gr_grade']);
    }

    if(!has_existence($_POST['txt_std_pwd'])){
        $error['txt_std_pwd']="password is required";
    }else{
        $add_pwd=password_hash(test_input($_POST['txt_std_pwd']),PASSWORD_DEFAULT,['cost'=>12]);
    }

    $add_active=$_REQUEST['ddl_std_active'];


    if(empty($error)){
        $message=insert_into_student($add_id,$add_fname,$add_mname,$add_lname,$add_phno,$add_class,$add_school,$add_rno,$add_gr_year,$add_gr_grade,$add_pwd,$add_active,$_REQUEST['txt_std_remark']);
        if(isset($message['true'])){$message_no_error="added successfully";}
        if(isset($message['false'])){$error['DB']=$message['false']['message'];}
    }

}//for manually adding
if (isset($_FILES['upload_stds']['name'])){//uploading section
    $pwd="null";
    if(isset($_POST['std_upload_pwd']) && !empty($_POST['std_upload_pwd'])){$pwd=$_POST['std_upload_pwd'];}//if password was provided
    if(filesize($_FILES['upload_stds']['tmp_name'])>0) {
        if (PATHINFO(basename($_FILES['upload_stds']['name']), PATHINFO_EXTENSION) === 'csv') {
            if (isset($_POST['std_upload_cls']) && !empty($_POST['std_upload_cls'])) {
                $upload_class=$_POST['std_upload_cls'];
                $ids = find_unregistered_ids($_FILES['upload_stds']['tmp_name']);//returns ['founded'] & ['not_founded'](multi-dim-array)
            if (isset($ids['founded'])) {$id_founded = $ids['founded'];}
            if (isset($ids['not_founded'])) {$id_nfounded = $ids['not_founded'];}
            if (!isset($ids['founded'])) {//if the student is not founded(registered) do the registration
                $upload_returned_msg=upload_into_student($_FILES['upload_stds']['tmp_name'], $upload_class,$pwd);
                if(isset($upload_returned_msg['false'])){$upload_error['uploading_process_error']=$upload_returned_msg['false']['message'];}
            }
        }else{$upload_error['class']="class is not set";}
        }else{$upload_error['file_type']="file is not a CSV";}
    }else{$upload_error['file_not_selected']="no file is selected";}
}
?>
<div id="page-wrapper">
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Add Student</h3></div>
            <div class="panel-body">
        <div class="col-md-6 ">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php if(isset($message_no_error)){echo success_alert($message_no_error);} ?>
                    <?php if(count($error)>0){echo error_alert($error);} ?>
                    <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="add_std_form">
                        <div class="form-group col-md-6">
                            <label for="txt_std_id">Student ID:</label>
                            <input type="text" class="form-control" id="txt_std_id" placeholder="0000" name="txt_std_id"  >
                        </div>
                        <div class="form-group col-md-6" >
                            <label for="txt_std_fullname" >first Name:</label>
                            <input type="text" class="form-control" id="txt_std_fullname"  name="txt_std_Fname" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_std_fullname">Middle Name:</label>
                            <input type="text" class="form-control" id="txt_std_fullname"  name="txt_std_Mname" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txt_std_fullname">Last Name:</label>
                            <input type="text" class="form-control" id="txt_std_fullname"  name="txt_std_Lname" >
                        </div>
                        <div class="form-group">
                            <label for="txt_std_ph.no">ph.number:</label>
                            <input type="text" class="form-control" id="txt_std_no" placeholder="+(000)00000000" name="txt_std_phno" >
                        </div>
                        <div class="form-group">
                            <label for="txt_std_class">Class:</label>
                            <select  class="form-control" name="ddl_std_class" >
                                <option value="" selected>Select Class</option>
                                <?php $all_classes=get_all_classes();
                                while($classs = $all_classes->fetch_assoc()){
                                    echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                }?>
                            </select>
                        </div>

            </div><!--/panel body-->
                </div><!--/panel-->
            </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">


            <div class="form-group col-md-6">
                <label for="txt_std_DOB " >School:</label>
                <input type="text" class="form-control" id="txt_std_DOB"  name="txt_std_school" >
            </div>
            <div class="form-group col-md-6">
                <label for="txt_std_DOB">Roll Number:</label>
                <input type="text" class="form-control" id="txt_std_DOB"  name="txt_std_rno" >
            </div>
            <div class="form-group col-md-6">
                <label for="txt_std_address">Graduation year:</label>
                <input type="text" class="form-control" id="txt_std_address"  name="txt_std_gr_year" placeholder="year format: YYYY.">

            </div>
            <div class="form-group col-md-6">
                <label for="txt_std_address">Graduated Grade:</label>
                <input type="text" class="form-control" id="txt_std_address"  name="txt_std_gr_grade" >
            </div>

            <div class="form-group col-md-6">
                <label for="txt_std_pwd ">Password:</label>
                <input type="password" class="form-control" id="txt_std_pwd" placeholder="*****" name="txt_std_pwd" >

            </div>
            <div class="form-group col-md-6">
                <label for="txt_std_pwd">Active:</label>
                <select  class="form-control" name="ddl_std_active"  >
                    <option value="1" selected>Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>

            <div class="form-group col-md-8">
                <label for="txt_std_address">Remark:</label>
                <textarea class="form-control" rows="3" name="txt_std_remark" ></textarea>
            </div>


            <div class="col-md-6"><button type="submit" name="submit_std" class="btn btn-default btn-lg" ><span class="glyphicon glyphicon-floppy-save"></span> Submit</button></div>
            </form>


                </div><!--/panel body-->
            </div><!--/panel-->

        </div><!--/col-md-6-->
            </div>
            </div>
        </div><!--/row-->
</div><!-- /#page-wrapper -->
<!--/</div>container-->

<?php require_once('../../includes/layout/footer.php');?>

<script type="text/javascript">
    var frmvalidator  = new Validator("add_std_form");
    frmvalidator.addValidation("txt_std_id","req","ID is required");
    frmvalidator.addValidation("txt_std_id","num","ID should be a number");
    //first name
    frmvalidator.addValidation("txt_std_Fname","req","first Name is required");
    frmvalidator.addValidation("txt_std_Fname","alphabetic","Only alphabetic characters");

    frmvalidator.addValidation("txt_std_Mname","req","Middle Name is required");
    frmvalidator.addValidation("txt_std_Mname","alphabetic","Middle Name is should be an alphanumeric ");

    frmvalidator.addValidation("txt_std_Lname","req","Last name required");
    frmvalidator.addValidation("txt_std_Lname","alphabetic","Last name is should be an alphanumeric ");

    frmvalidator.addValidation("txt_std_phno","req","Phone is required");
    frmvalidator.addValidation("txt_std_phno","num","Phone  should be a number");

    frmvalidator.addValidation("ddl_std_class","req","Class is required");
    frmvalidator.addValidation("txt_std_school","req","school required");

    frmvalidator.addValidation("txt_std_rno","req","Roll number required");
    frmvalidator.addValidation("txt_std_rno","num","ROLL is should be a number");

    frmvalidator.addValidation("txt_std_gr_year","req","graduated year required");

    frmvalidator.addValidation("txt_std_gr_grade","req","graduated grade is required");
    //pass
    frmvalidator.addValidation("txt_std_pwd","req","password is required");
    frmvalidator.addValidation("txt_std_pwd","minlen=6","password characters should be 6 at minimum");
    //active
    frmvalidator.addValidation("ddl_std_active","req","provide if it's active required");

    </script>


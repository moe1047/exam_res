<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<!--/header-->
<!-- Navigation -->
<?php
$page="upload_student";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->

<?php

if (isset($_FILES['upload_stds']['name'])){//uploading section
    $pwd="null";
    if(isset($_POST['std_upload_pwd']) && !empty($_POST['std_upload_pwd'])){$pwd=password_hash($_POST['std_upload_pwd'],PASSWORD_BCRYPT,['cost'=>10]);}//if password was provided
    if(filesize($_FILES['upload_stds']['tmp_name'])>0) {
        if (PATHINFO(basename($_FILES['upload_stds']['name']), PATHINFO_EXTENSION) === 'csv') {
            if (isset($_POST['std_upload_cls']) && !empty($_POST['std_upload_cls'])) {
                $upload_class=$_POST['std_upload_cls'];
                $ids = find_unregistered_ids($_FILES['upload_stds']['tmp_name']);//returns ['founded'] & ['not_founded'](multi-dim-array)
                if (isset($ids['founded'])) {$id_founded = $ids['founded'];}
                if (isset($ids['not_founded'])) {$id_nfounded = $ids['not_founded'];}
                if (!isset($ids['founded'])) {//if the student is not founded(registered) do the registration
                    $upload_returned_msg=upload_into_student($_FILES['upload_stds']['tmp_name'], $upload_class,$pwd,$_REQUEST['std_upload_active']);
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
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <p>File Order Content = [student_id] [first_name] [middle_name] [last_name]	[school] [roll_No] [graduated_year] [graduation_grade] [phone_number] [remark] </p>
                    </div>
                    <?php if(isset($upload_returned_msg['true'])){echo success_alert("registered:- ".count($id_nfounded));}?><!--success-->
                    <?php if(isset($id_founded)){echo registered_std_alert($ids['founded']);}//error
                    //if(isset($upload_returned_msg['false'])){echo error_alert($upload_returned_msg['false']);}
                    if(isset($upload_error)){echo error_alert($upload_error);}?><!--errors-->
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Upload Students</h3></div>
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="upload_std_form">
                                        <div class="form-group col-md-3">
                                            <label for="std_upload_cls">(STEP 1) Class:</label>
                                            <select  id="std_upload_cls" class="form-control" name="std_upload_cls" >
                                                <option value="" selected>Select Class</option>
                                                <?php $all_classes=get_all_classes();
                                                while($classs = $all_classes->fetch_assoc()){
                                                    echo "<option value=".$classs['class_id'].">".$classs['class_name']."/".$classs['shift']." / ".$classs['section']." / ".$classs['graduation_year']."</option>";
                                                }?>
                                            </select>

                                        </div>


                                        <div class="form-group col-md-3">
                                            <label for="std_upload_pwd">(STEP 2) Password:</label>
                                            <input type="password" class="form-control" name="std_upload_pwd" id="std_upload_pwd" placeholder="password">
                                            <p class="help-block">Minimum lengh of the password is 6 </p>

                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="std_upload_cls">(STEP 3) Active?:</label>
                                            <select  id="std_upload_cls" class="form-control" name="std_upload_active" >
                                                <option value="1" selected>True</option>
                                                <option value="0" >False</option>

                                            </select>

                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="upload_stds">(STEP 4) Upload students:</label>
                                            <input type="file" id="upload_stds" name="upload_stds">
                                            <p class="help-block">only a spreadsheet with an extension of csv</p>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-default btn-lg" name="sumbit"><span class='glyphicon glyphicon-upload'></span> Upload</button>
                                            </div>


                                    </form>
                                    <form role="form" action="download.php" method="post"  name="download_sample">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-default btn-lg" name="download_sample"><span class='glyphicon glyphicon-file'></span> Sample</button>
                                    </form>
                                </div>
                                    <!--Pbody-->
                            </div><!--Panel-->
                                </div><!--/ body panel -->
                        </div>


                    </div><!--/  panel -->
                </div><!--/  col-md-6 -->
                </div>
        </div>



    <!-- /#page-wrapper -->





<?php require_once('../../includes/layout/footer.php');?>
<script type="text/javascript">
    var frmvalidator  = new Validator("upload_std_form");
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("std_upload_cls","req","Class is required");

    frmvalidator.addValidation("std_upload_pwd","req","password is required");
    frmvalidator.addValidation("std_upload_pwd","minlen=6","password characters should be 6 at minimum");
    //uploading
    frmvalidator.addValidation("upload_stds","req_file","File upload is required");
    frmvalidator.addValidation("upload_stds","file_extn=csv","Allowed file extension: .csv");


</script>
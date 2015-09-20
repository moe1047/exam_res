<?php require_once('../../includes/layout/header.php');?>
<?php require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>

<script src="../js/jquery.js"></script>
<script>
    $(document).ready(function(){
        $("div#upload_reg").hide();
    })

</script>
<!--/header-->
<!-- Navigation -->
<?php
$page="semester_reg";
require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->

<?php
if(isset($_REQUEST['sumbit_upload_std_reg'])){ ?>
    <script>
        $(document).ready(function(){
            var div_add_reg=$("div#add_reg");
            var div_up_reg=$("div#upload_reg");
            div_add_reg.hide();
            div_up_reg.show();
            $("li#upload_reg").addClass("active");
            $("li#add_reg").removeClass("active");
        });

    </script>
    <?php
    if (isset($_FILES['upload_sms_stds']['name'])){//uploading section
        $remark="NULL";
        if(isset($_POST['txt_reg_remark']) && !empty($_POST['txt_reg_remark'])){$remark=$_POST['txt_reg_remark'];}//if password was provided
        if(filesize($_FILES['upload_sms_stds']['tmp_name'])>0) {
            if (PATHINFO(basename($_FILES['upload_sms_stds']['name']), PATHINFO_EXTENSION) === 'csv') {
                if (isset($_POST['ddl_reg_sms']) && !empty($_POST['ddl_reg_sms'])) {//if semester is selected
                    $reg_semester=$_POST['ddl_reg_sms'];

                    $ids = find_unregistered_ids($_FILES['upload_sms_stds']['tmp_name']);//returns ['founded'] & ['not_founded'](multi-dim-array)
                    if (isset($ids['founded'])) {$id_founded = $ids['founded'];}
                    if (isset($ids['not_founded'])) {$id_nfounded = $ids['not_founded'];}

                    if (!isset($ids['not_founded'])) {//if the student is not founded(registered) do the registration
                        $upload_returned_msg=upload_into_sms_registration($_FILES['upload_sms_stds']['tmp_name'], $reg_semester,$remark);
                        if(isset($upload_returned_msg['false'])){$upload_error['uploading_process_error']=$upload_returned_msg['false']['message'];}
                    }
                }else{$upload_error['semester']="semester is not selected";}
            }else{$upload_error['file_type']="file is not a CSV";}
        }else{$upload_error['file_not_selected']="no file is selected";}
    }
}

if(isset($_REQUEST['sumbit_std_reg_mn'])){?>
    <script>
        $(document).ready(function(){
            $("div#upload_reg").hide();
        })

    </script><!--to hide the upload div-->
    <?php

    $add_error=array();

    if(!isset($_REQUEST['ddl_add_reg_std_sms']) || empty($_REQUEST['ddl_add_reg_std_sms'])){
        $add_error[]="semester must be selected";
    }
    if(!isset($_REQUEST['ddl_add_reg_std_id']) || empty($_REQUEST['ddl_add_reg_std_id'])){
        $add_error[]="student must be selected";
    }
    if(!isset($_REQUEST['txt_add_reg_std_rmrk']) || empty($_REQUEST['txt_add_reg_std_rmrk'])){
        $add_remark="";
    }else{$add_remark=$_REQUEST['txt_add_reg_std_rmrk'];}




    if(empty($add_error)){
    $already_registered=array();
    foreach($_REQUEST['ddl_add_reg_std_id'] as $std_id){
        if(!find_if_std_sms_registered($std_id,$_REQUEST['ddl_add_reg_std_sms'])){
            $add_message_returned=insert_into_std_reg($std_id,$_REQUEST['ddl_add_reg_std_sms'],$add_remark);
            if(isset($add_message_returned['true'])){$registered[]=$std_id;}
        }else{$already_registered[]=$std_id;}
    }

}
}
?>

<div id="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li role="presentation" id="add_reg" class="active"><a href="#">Add Semester Registration List</a></li>
                            <li role="presentation" id="upload_reg" ><a href="#">Upload Semester Registration List</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div id="add_reg">
                    <?php if(isset($already_registered) && !empty($already_registered)){echo registered_std_alert($already_registered);}?><!--success-->
                    <?php if(isset($registered) && !empty($registered)){echo registered_std_sms_success_alert($registered);}

                    //if(isset($upload_returned_msg['false'])){echo error_alert($upload_returned_msg['false']);}

                    if(isset($add_error) && !empty($add_error)){echo error_alert($add_error);}?><!--errors-->
                    <div class="panel panel-default">

                        <div class="panel-heading"><h3 class="panel-title">Register for semester</h3></div>

                        <div class="panel-body">
                            <div class="panel panel-default ">

                                <div class="panel-body">
                                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="upload_std_form">

                                        <div class="form-group col-md-8 col-md-offset-2  ">
                                            <label for="txt_std_class">Class:</label>
                                            <select  class="form-control" name="ddl_add_reg_std_class" onchange="get_std_add_reg(this.value)">
                                                <option value="" selected>Select Class</option>
                                                <?php $all_classes=get_all_classes();
                                                while($classs = $all_classes->fetch_assoc()){
                                                    echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['graduation_year']."</option>";
                                                }?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-8 col-md-offset-2">
                                            <label for="ddl_grade_std_m">Student:</label>
                                            <select multiple id="ddl_add_reg_std_id" name="ddl_add_reg_std_id[]" class="form-control" size="5" onchange="get_std_semesters(this.value)"   >

                                            </select>
                                            <span class="help-block">hold <kbd>CTRL</kbd> to select multiple students</span>
                                        </div>
                                        <div class="form-group col-md-8 col-md-offset-2">
                                            <label for="search_grade_sms"> Semester:</label>
                                            <select id="search_grade_sms" name="ddl_add_reg_std_sms" class="form-control" >
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
                                        <div class="form-group col-md-8 col-md-offset-2">
                                            <label for="std_upload_pwd">Remark:</label>
                                            <textarea class="form-control" rows="3" name="txt_add_reg_std_rmrk"></textarea>
                                            <p class="help-block">remark text field is optional</p>

                                        </div>
                                        <div class="col-md-3 col-md-offset-5">
                                            <button type="submit" class="btn btn-default btn-lg" name="sumbit_std_reg_mn"><span class='glyphicon glyphicon-floppy-disk'></span> Submit</button>
                                        </div>
                                    </form>
                                </div><!--Pbody-->
                            </div><!--Panel-->
                        </div><!--/ body panel -->
                    </div>
                </div><!--/  panel -->
                <div id="upload_reg">
                    <?php if(isset($upload_returned_msg['true'])){echo success_alert("registered:- ".count($id_founded));}?><!--success-->
                    <?php if(isset($id_nfounded)){echo not_registered_std_alert($id_nfounded);}//error

                    //if(isset($upload_returned_msg['false'])){echo error_alert($upload_returned_msg['false']);}

                    if(isset($upload_error)){echo error_alert($upload_error);}?><!--errors-->
                    <div class="panel panel-default">

                        <div class="panel-heading"><h3 class="panel-title">Upload Students</h3></div>

                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="alert alert-info">
                                    <p>File content order = [student_id] </p>
                                </div>
                                <div class="panel-body">
                                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="upload_std_form">


                                        <div class="form-group col-md-4">
                                            <label for="search_grade_sms">(Step 1) Semester:</label>
                                            <select id="search_grade_sms" name="ddl_reg_sms" class="form-control " >
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
                                        <div class="form-group col-md-4">
                                            <label for="std_upload_pwd">(STEP 2) Remark:</label>
                                            <input type="text" class="form-control" name="txt_reg_remark" id="std_upload_pwd" placeholder="remark">
                                            <p class="help-block">remark text field is optional</p>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="upload_stds">(STEP 3) Upload students:</label>
                                            <input type="file" id="upload_sms_stds" name="upload_sms_stds">
                                            <p class="help-block">only a spreadsheet with an extension of csv</p>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-default btn-lg" name="sumbit_upload_std_reg"><span class='glyphicon glyphicon-upload'></span> Upload</button>
                                        </div>

                                    </form>
                                    <form role="form" action="download.php" method="post"  name="download_sample">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-default btn-lg" name="download_sms_reg_sample"><span class='glyphicon glyphicon-file'></span> Sample</button>
                                    </form>
                                </div>
                                </div><!--Pbody-->
                            </div><!--Panel-->
                        </div><!--/ body panel -->
                    </div>


                </div><!--/  panel -->

            </div><!--/  col-md-6 -->
    </div>
</div>
    <!-- /#page-wrapper -->
    <script src="../js/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $("ul.nav").children("li#upload_reg").on("click",function(){
                $("div#add_reg").hide(800);
                $("div#upload_reg").fadeIn();

                var add_reg=$("li#add_reg");
                add_reg.removeClass("active");
                $("li#upload_reg").addClass("active");
            });

            $("ul.nav").children("li#add_reg").on("click",function(){
                var upload_reg=$("div#add_reg");
                upload_reg.fadeIn(800);
                $("div#upload_reg").hide();

                var add_reg=$("li#add_reg");
                add_reg.addClass("active");
                $("li#upload_reg").removeClass("active");

            });

        })
    </script>



<?php require_once('../../includes/layout/footer.php');?>
<script type="text/javascript">
    var frmvalidator  = new Validator("upload_std_form");
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("ddl_reg_sms","req","semester is required");


    //uploading
    frmvalidator.addValidation("upload_sms_stds","req_file","File upload is required");
    frmvalidator.addValidation("upload_sms_stds","file_extn=csv","Allowed file extension: .csv");


</script>
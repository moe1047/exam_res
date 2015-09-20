<?php require_once('../../includes/layout/header.php');
require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php require_once('../../includes/validation_functions.php');?>
<?php
$page="add_std_grade";
?>
<script src="../js/jquery.js"></script>
<script>
    $(document).ready(function(){
        $("div#upload_res").hide();
    })

</script>
<!--/header-->
<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');
;?>
<!-- /Navigation -->
<?php
$message=array();
if(isset($_POST['submit_m'])){?>
    <script>
        $(document).ready(function(){
            $("ul.nav").children("li#add_res").on("click",function(){
                $("div#upload_res").hide(800);
                $("div#add_res").fadeIn();


                $("li#upload_res").removeClass("active");
                $("li#add_res").addClass("active");

            });
    </script>
    <?php
    $error_message=array();
    if(!isset($_REQUEST['ddl_grade_cls']) || empty($_REQUEST['ddl_grade_cls'])){
        $error_message[]="class must be selected";

    }
    if(!isset($_REQUEST['ddl_grade_sms_m']) || empty($_REQUEST['ddl_grade_sms_m'])){
        $error_message[]="semester must be selected";

    }
    if(!isset($_REQUEST['ddl_grade_std_m']) || empty($_REQUEST['ddl_grade_std_m'])){
        $error_message[]="student must be selected";
    }
    if(!isset($_REQUEST['ddl_grade_mark_group']) || empty($_REQUEST['ddl_grade_mark_group'])){
        $error_message[]="mark group must be selected";
    }

    if(!isset($_REQUEST['ddl_grade_crs_m']) || empty($_REQUEST['ddl_grade_crs_m'])){
        $error_message[]="course must be selected";
    }

    if(isset($_REQUEST['txt_grade_result']) || !empty($_REQUEST['txt_grade_result'])){
        $result=test_input($_POST['txt_grade_result']);
        if(!in_min_and_max($result,0,100)){
            $error_message[]="result must be in the range of (0-100)";
        }
    }else{$error_message[]="result cannot be empty";}






    if(empty($error_message)){
        $clscrs_msg=get_clscrs_id($_REQUEST['ddl_grade_cls'],$_REQUEST['ddl_grade_crs_m'],$_REQUEST['ddl_grade_sms_m']);
        if(!is_object($clscrs_msg)){
            $error_message[]="this course is not registered for this class on this semester";
        }else{
            while ($clscrs_id=$clscrs_msg->fetch_assoc()){
                $class_course_id=$clscrs_id['class_course_id'];
            }
        }
    }//find class_course_id

    if(empty($error_message)) {
        $std_id=$_REQUEST['ddl_grade_std_m'];

        if (find_if_std_crs_result($std_id, $class_course_id)) {
            $error_message[] = "{$std_id} already took the exam ";
        }
    }//check is the student has taken the exam



    if(empty($error_message) ){
        $message_insert_returned=insert_into_results($result,$_POST['ddl_grade_std_m'],$class_course_id,
           $_SESSION['user_seq'],$_POST['txt_grade_remark'],$_REQUEST['ddl_grade_mark_group']);
        if(isset($message_insert_returned['true'])){
            $resultt=get_students_by_id($_POST['ddl_grade_std_m']);//get student to get his no
            $studentt=$resultt->fetch_assoc();
            $student_ph_no=$studentt['phone_number'];
            send_sms(array($student_ph_no),"new results has been submitted");
            $insert_message_success="added successfully";
        }
        if(isset($message_insert_returned['false'])){$error_message[]=$message_insert_returned['false']['message'];}
    }


}//manuel adding section
if(isset($_REQUEST['upload_res'])){?>
    <script>
        $(document).ready(function(){
            $("div#upload_res").show();
            $("div#add_res").hide();

            $("li#add_res").removeClass("active");
            $("li#upload_res").addClass("active");

            });


    </script>
<?php

    $upload_error=array();
    if(!isset($_REQUEST['ddl_up_result_cls']) || empty($_REQUEST['ddl_up_result_cls'])){
        $upload_error[]="class must be selected";
    }
    if(!isset($_REQUEST['ddl_up_result_sms']) || empty($_REQUEST['ddl_up_result_sms'])){
        $upload_error[]="semester must be selected";
    }
    if(!isset($_REQUEST['ddl_up_result_crs']) || empty($_REQUEST['ddl_up_result_crs'])){
        $upload_error[]="course must be selected";
    }

    if(filesize($_FILES['result_file']['tmp_name'])<=0){
        $upload_error[]="file must be set";

    }elseif(PATHINFO(basename($_FILES['result_file']['name']),PATHINFO_EXTENSION)!=='csv'){
        $upload_error[]="file must be CSV";
    }

    if(!isset($_REQUEST['ddl_up_result_mark_group']) || empty($_REQUEST['ddl_up_result_mark_group'])){
        $upload_error[]="Mark Group must be selected";
    }

    if(empty($upload_error)){
        $file=fopen($_FILES['result_file']['tmp_name'],'r');
        $line=0;
        while($data=fgetcsv($file,1000,',')){
            $line++;
            if($data[0]=="" || $data[1]=="" ){
                $upload_error[]="result or id is empty on line $line (UPLOADED FILE)";
                break;
            }

        }
        fclose($file);
    }//check if one of the result/id field exist or nah
    if(empty($upload_error)){
        $file=fopen($_FILES['result_file']['tmp_name'],'r');
        $line=0;
        while($data=fgetcsv($file,1000,',')){
            $line++;
            if(!check_if_std_in_cls($data[0],$_REQUEST['ddl_up_result_cls'])){
                $upload_error[]="$data[0] doesn't belong on the specified class - line $line (UPLOADED FILE)";
                break;
            }

        }
        fclose($file);
    }//check if the stds belong to the specified class

    if(empty($upload_error)){
                $file=fopen($_FILES['result_file']['tmp_name'],'r');
                $line=0;
                while($data=fgetcsv($file,1000,',')){
                    $line++;
                    if(!find_if_std_sms_registered($data[0],$_REQUEST['ddl_up_result_sms'])){
                        $upload_error[]="$data[0] is not registered on the provided semester - line $line (UPLOADED FILE)";
                        break;
                    }

                }
                fclose($file);

    }//check if the student is registered of nah

    if(empty($upload_error)){
        $file=fopen($_FILES['result_file']['tmp_name'],'r');
        $line=0;
        while($data=fgetcsv($file,1000,',')){
            $line++;
            if($data[1]<0){
                $upload_error[]="result should be greater then 0 - line $line (UPLOADED FILE)";
                break;
            }elseif($data[1]>100){
                $upload_error[]="result should not be greater then 100 - line $line (UPLOADED FILE)";
                break;
            }

        }
        fclose($file);

    }//check is the result on the required limit (0-100)

    if(empty($upload_error)){
        $clscrs_msg=get_clscrs_id($_REQUEST['ddl_up_result_cls'],$_REQUEST['ddl_up_result_crs'],$_REQUEST['ddl_up_result_sms']);
        if(!is_object($clscrs_msg)){
            $error_message[]="this course is not registered for this class on this semester";
        }else{
            while ($clscrs_id=$clscrs_msg->fetch_assoc()){
                $class_course_id=$clscrs_id['class_course_id'];
            }
        }
    }//get $class_course_id


    if(empty($upload_error)){
        $file=fopen($_FILES['result_file']['tmp_name'],'r');
        $line=0;
        while($data=fgetcsv($file,1000,',')){
            $line++;
            if(find_if_std_crs_result($data[0],$class_course_id)){
                $upload_error[]="$data[0] already took the exam - line $line (UPLOADED FILE)";
                break;
            }

        }
        fclose($file);

    }//check if the std already took the exam

    if(empty($upload_error)){
        $phone_number_up=array();//up for upload
        $file=fopen($_FILES['result_file']['tmp_name'],'r');
        $upload_success=0;
        while($data=fgetcsv($file,1000,',')){
            if(!isset($data[2]) || empty($data[2])){$remark="";}else{$remark=$data[2];}
            $upload_msg=insert_into_results($data[1],$data[0],$class_course_id,$_SESSION['user_seq'],$remark,$_REQUEST['ddl_up_result_mark_group']);
            if(isset($upload_msg['false'])){$upload_error[]=$upload_msg['false']['message'];}
            if(isset($upload_msg['true'])){
                $resultt=get_students_by_id($data[0]);//get student to get his no
                $studentt=$resultt->fetch_assoc();
                $student_ph_no=$studentt['phone_number'];
                if(!empty($student_ph_no)){$phone_number_up[]=$student_ph_no;}
                $upload_success+=1;
            }


        }
        fclose($file);
        if(!empty($phone_number_up)){send_sms($phone_number_up,"new results has been submitted");}//if there is no numbers , dont send sms


    }









}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation" id="add_res" class="active"><a href="#">Add Results </a></li>
                        <li role="presentation" id="upload_res" ><a href="#">Upload Result</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 ">
            <div id="upload_res">
                <?php if(isset($upload_error) && !empty($upload_error)){echo error_alert($upload_error);} ?>
                <?php if(isset($upload_success) && $upload_success!=0){echo success_alert($upload_success." results has been submitted");} ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Upload Grades</h3></div>
                    <div class="panel-body">
                        <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="ddl_grade_cls">Class:</label>
                                <select id="ddl_up_result_cls" class="form-control" name="ddl_up_result_cls"  onchange="get_courses_up()" required >
                                    <option value="" selected>Select Class</option>
                                    <?php $all_classes=get_all_classes();
                                    while($classs = $all_classes->fetch_assoc()){
                                        echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                    }?>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="ddl_up_result_sms">Semester:</label>
                                <select  class="form-control" name="ddl_up_result_sms" id="ddl_up_result_sms" onchange="get_courses_up()" required>
                                    <option value="" selected>All semester</option>
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







                            <div class="form-group ">
                                <label for="ddl_grade_std_m">Course:</label>
                                <select id="ddl_up_result_crs" name="ddl_up_result_crs" class="form-control" onchange="get_std_semesters(this.value)"  required >

                                </select>
                            </div>



                            <div class="form-group ">
                                <label for="ddl_up_result_mark_group">Mark Group:</label>
                                <select  class="form-control" name="ddl_up_result_mark_group" ">
                                <?php $all_mark_groups=get_all_mark_group();
                                while($mark_group = $all_mark_groups->fetch_assoc()){
                                    echo "<option value=".$mark_group['mark_group_id'].">".$mark_group['mark_group_name']."</option>";
                                }?>
                                </select>
                            </div>



                            <div class="form-group">
                                <label for="upload_grades">Upload Grades:</label>
                                <input type="file" id="upload_grades" name="result_file">
                                <p class="help-block">only a spreadsheet with the extension of .csv  </p>
                            </div>

                            <button type="submit" class="btn btn-default btn-lg col-md-6" name="upload_res"><span class='glyphicon glyphicon-upload'></span> Upload</button>
                        </form>
                        <form role="form" action="download.php" method="post"  name="download_sample">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-default btn-lg" name="download_sample_res"><span class='glyphicon glyphicon-file'></span> Sample</button>
                            </div>
                        </form>

                    </div><!--/ body panel -->


                </div><!--/  panel -->

            </div>
            <div id="add_res">
                <?php if(isset($error_message) && !empty($error_message)){echo error_alert($error_message);} ?>
                <?php if(isset($insert_message_success)){echo success_alert($insert_message_success);} ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Add Result</h3></div>
                    <div class="panel-body">

                        <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

                            <div class="form-group"><!--last "_m" in names and ids is for Manuel-->
                                <label for="ddl_grade_cls_m">Class:</label>
                                <select id="ddl_grade_cls_m" class="form-control" name="ddl_grade_cls" onchange="get_students_add_std(this.value)"  required >
                                    echo "<option value='' selected>Select Class</option>";
                                    <?php $all_classes=get_all_classes();
                                    while($classs = $all_classes->fetch_assoc()){
                                        echo "<option value=".$classs['class_id'].">".$classs['class_name']." / ".$classs['shift']." / ".$classs['section']." / ".$classs['batch']."</option>";
                                    }?>
                                </select>

                            </div>
                            <div class="form-group ">
                                <label for="ddl_grade_std_m">Student:</label>
                                <select id="ddl_grade_std_m" name="ddl_grade_std_m" class="form-control" onchange="get_std_semesters(this.value)"  required >

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ddl_grade_sms_m">Semester:</label>
                                <select id="ddl_grade_sms_m" class="form-control" name="ddl_grade_sms_m"   onchange="get_sms_courses(this.value)" required>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ddl_grade_crs_m">Course:</label>
                                <select id="ddl_grade_crs_m" name="ddl_grade_crs_m" class="form-control" required >

                                </select>

                            </div>
                            <div class="form-group">
                                <label for="txt_grade_result">Result:</label>
                                <input type="text" class="form-control" id="txt_grade_result" name="txt_grade_result" placeholder="100 > input >0" name="txt_grde_result" required>
                            </div>
                            <div class="form-group ">
                                <label for="txt_std_class">Mark Group:</label>
                                <select  class="form-control" name="ddl_grade_mark_group" ">
                                <?php $all_mark_groups=get_all_mark_group();
                                while($mark_group = $all_mark_groups->fetch_assoc()){
                                    echo "<option value=".$mark_group['mark_group_id'].">".$mark_group['mark_group_name']."</option>";
                                }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="txt_grade_remark">remark:</label>
                                <input type="text" class="form-control" id="txt_grade_remark" name="txt_grade_remark" placeholder="Add / make up /Regular" name="txt_grde_result" >
                            </div>
                            <button type="submit" name="submit_m" class="btn btn-default btn-lg col-md-offset-5">Submit</button>
                        </form>
                    </div><!--/panel body-->
                </div><!--/panel-->
            </div>

        </div><!-- col-md-->
    </div><!--/row-->
</div><!-- /#page-wrapper -->
<script>
    $(document).ready(function(){
        $("ul.nav").children("li#add_res").on("click",function(){
            $("div#upload_res").hide(800);
            $("div#add_res").fadeIn();


            $("li#upload_res").removeClass("active");
            $("li#add_res").addClass("active");

        });

        $("ul.nav").children("li#upload_res").on("click",function(){

            $("div#upload_res").fadeIn();
            $("div#add_res").hide(800);

            $("li#add_res").removeClass("active");
            $("li#upload_res").addClass("active");


        });

    })
</script>

<?php require_once('../../includes/layout/footer.php');?>

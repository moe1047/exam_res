<?php
require_once('../../includes/functions.php');?>
<?php require_once('../../includes/validation_functions.php');?>
<?php require_once('../../includes/layout/header.php');?>
<!--/header-->

<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
if (isset($_FILES['grade_file']['name'])){
    if(filesize($_FILES['grade_file']['tmp_name'])>0){
        if(PATHINFO(basename($_FILES['grade_file']['name']),PATHINFO_EXTENSION)==='csv'){
            $ids=find_unregistered_ids($_FILES['grade_file']['tmp_name']);//returns ['founded'] & ['not_founded'](multi-dim-array)
            if(isset($ids['founded'])){$id_founded=$ids['founded'];}
            if(isset($ids['not_founded'])){$not_founded=$ids['not_founded'];}
            if(!isset($ids['not_founded'])){
                $message_returned=upload_into_grades($_FILES['grade_file']['tmp_name'],$_POST['ddl_grade_crs'],$_POST['ddl_grade_sms']);
                if(isset($message_returned['true'])){
                    $message_success="successfully uploaded";
                }else{
                    $message_error=$message_returned['false']['message'];
                }
            }
        }else{$message_error="not a csv file";}

    }else{
        $message_error="file not set";
    }
}//file uploading section
if(isset($_POST['submit_m'])){
    $result=test_input($_POST['txt_grade_result']);
    if(in_min_and_max($result,0,100)){
        $message_insert_returned=insert_into_results($result,$_POST['ddl_grade_std_m'],$_POST['ddl_grade_crs_m'],$_POST['ddl_grade_sms_m'],$_SESSION['user'],$_POST['txt_grade_remark']);
            if(isset($message_insert_returned['true'])){$insert_message_success="added successfully";}
            if(isset($message_insert_returned['false'])){$insert_message_error=$message_insert_returned['false']['message'];}
    }else{
        $insert_message_error='result must be in the range of (0-100)';
    }


}//manuel adding section
 ?>
<div class="container">
    <div id="page-wrapper">

        <div class="row">

            <div class="col-md-5 col-md-offset-1"><!--upload section-->
                <div class="alert alert-info">
                    <p>1) the file should be .CSV, 2) FORMAT[id,result,remark],<br> 3) all students that have results must be registered</p>
                </div>
                <div class="alert alert-success">
                    <?php if(isset($id_founded)){
                        echo "added :- ".count($id_founded)." grades<br>";
                    }
                    if(isset($message_success)){
                        echo $message_success;
                    }

                    ?>
                </div>
                <div class="alert alert-danger">
                    <?php
                    if (isset($not_founded)){
                        foreach($not_founded as $founded_ids){
                            echo "not registered:- ".$founded_ids."<br>";
                        }
                    }
                    if (isset($message_error)){
                            echo $message_error."<br>";
                    }
                   ?>
                </div>

            </div><!--/  col-md-6 -->

            <div class="col-md-5"><!--add section-->
                <div class="alert alert-info">
                    <p>1) The result should be between 0-100<br>2)be carefull to add the result of one student twice</p>
                </div>
                <div class="alert alert-success">
                    <?php if(isset($insert_message_success)){
                        echo $insert_message_success;
                    }?>
                </div>
                <div class="alert alert-danger">
                    <?php if(isset($insert_message_error)){echo $insert_message_error;}?>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Add Grades</h3></div>
                    <div class="panel-body">

                        <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div class="form-group"><!--last "_m" in names and ids is for Manuel-->
                                <label for="ddl_grade_cls_m">Class:</label>
                                <select id="ddl_grade_cls_m" class="form-control" name="ddl_grade_cls" onchange="get_students(this.value);get_courses_by_class_m(this.value)"  size="3" required >
                                    <option value="" selected>Select Class</option>
                                    <?php $all_classes=get_all_classes();
                                    while($classs = $all_classes->fetch_assoc()){
                                        echo "<option value=".$classs['class_id'].">".$classs['class_name']." ".$classs['shift']."</option>";
                                    }?>
                                </select>

                            </div>
                            <div class="form-group ">
                                <label for="ddl_grade_std_m">Student:</label>
                                <select id="ddl_grade_std_m" name="ddl_grade_std_m" class="form-control" required >

                                </select>
                            </div>
                            <div class="form-group">
                                    <label for="ddl_grade_sms_m">Semester:</label>
                                    <select id="ddl_grade_sms_m" class="form-control" name="ddl_grade_sms_m" required  onchange="get_courses_m(this.value)" size="4">
                                        <option value="" selected>select semester</option>
                                        <?php $all_semesters=get_all_semesters();
                                        while($semester=$all_semesters->fetch_assoc()){
                                            echo "<option value=".$semester['semester_id'].">".$semester['semester_name']." ".$semester['ac_year_from']." / ".$semester['ac_year_to']."</option>";
                                        }
                                        ?>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="ddl_grade_crs_m">Course:</label>
                                <select id="ddl_grade_crs_m" name="ddl_grade_crs_m" class="form-control" required size="4">

                                </select>

                            </div>
                            <div class="form-group">
                                <label for="txt_grade_result">Result:</label>
                                <input type="text" class="form-control" id="txt_grade_result" name="txt_grade_result" placeholder="100 > input >0" name="txt_grde_result" required>
                            </div>
                            <div class="form-group">
                                <label for="txt_grade_remark">remark:</label>
                                <input type="text" class="form-control" id="txt_grade_remark" name="txt_grade_remark" placeholder="Add / make up /Regular " name="txt_grde_result" >
                            </div>
                            <button type="submit" name="submit_m" class="btn btn-default btn-lg">Submit</button>
                        </form>

                    </div><!--/panel body-->
                </div><!--/panel-->




            </div><!--/col-md-6-->
            <!--<div class="col-md-1"></div>-->
        </div><!--/row-->


    </div><!-- /#page-wrapper -->
</div><!--container-->
    <?php require_once('../../includes/layout/footer.php');?>

<script type="text/javascript">//to get the courses for ddl_grade_class

    function get_courses_u(semester)//to get the courses for ddl_grade_crs_m
    {
        var class_id=$('#ddl_grade_cls').val();
        var semester=semester;
        $.ajax({
            url: 'process_courses.php?class=' + class_id+'&semester='+semester,
            success: function(data) {
                $("#ddl_grade_crs").html(data);
            }
        })
    }


    function get_courses_m(semester)//to get the courses for ddl_grade_crs_m
    {
        var class_id=$('#ddl_grade_cls_m').val();
        var semester=semester;
        $.ajax({
            url: 'process_courses.php?class=' + class_id+'&semester='+semester,
            success: function(data) {
                $("#ddl_grade_crs_m").html(data);
            }
        })
    }

    function get_students(student_class)//to get the student for ddl_grade_class_m
    {
        $.ajax({
            url: 'process_students.php?student_class=' + student_class,
            success: function(data) {
                $("#ddl_grade_std_m").html(data);
            }
        })
    }
    function get_courses_by_class_m(classs)//to get the courses for ddl_grade_crs_m
    {
        var semester=$('#ddl_grade_sms_m').val();
        var class_id=classs;
        $.ajax({
            url: 'process_courses.php?class=' + class_id+'&semester='+semester,
            success: function(data) {
                $("#ddl_grade_crs_m").html(data);
            }
        })
    }
    function get_courses_by_semester(classs)//to get the courses for ddl_grade_crs_m
    {
        var semester=$('#ddl_grade_cls_m').val();
        var class_id=classs;
        $.ajax({
            url: 'process_courses.php?class=' + class_id+'&semester='+semester,
            success: function(data) {
                $("#ddl_grade_crs").html(data);
            }
        })
    }

</script>

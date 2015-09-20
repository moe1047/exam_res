<?php session_start();require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php if(isset($_GET['class']) and isset($_GET['semester'])){
    $all_semester_course=semester_class_courses($_GET['class'],$_GET['semester']);
    while($row = $all_semester_course->fetch_assoc()){
        echo "<option value=".$row['course_id'].">".$row['course_name']."</option>";}
}
?>
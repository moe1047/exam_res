<?php session_start(); require_once('../../includes/functions.php');
admin_confirm_logged_in();?>
<?php if(isset($_GET['class'])){
    $all_class_course=get_class_courses($_GET['class']);
    echo "<option value=''>all courses</option>";
    while($row = $all_class_course->fetch_assoc()){
        echo "<option value=".$row['course_id'].">".$row['course_name']."</option>";}
}
?>
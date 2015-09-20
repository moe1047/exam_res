<?php require_once('../includes/functions.php');

$result=get_students_by_id(4697);
$student=$result->fetch_assoc();
echo $student['phone_number'];


?>




<form method="post" action="test.php" enctype="multipart/form-data">
    from: <input type="text" name="from"><br>
    to: <input type="text" name="to"><br>
    message: <textarea  name="msg"></textarea><br>
    <input type="submit" name="submit" value="submit">
    <h1></h1>


</form>
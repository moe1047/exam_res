<?php
ob_start();
$conn = new mysqli('localhost','root','','exam_res');
if ($conn->connect_error){
    die("<b>there's an error </b>".$conn->connect_error);
}
function redirect_to($page){
   return header("location:{$page}");
}
function time_redirect_to($page){
    return header("refresh:1;url={$page}");
}
function get_all_classes_limit($limit,$offset){
    global $conn;
    $result=$conn->query("select * from class ORDER BY class_name LIMIT $limit OFFSET $offset");
    return $result;

}
function get_all_classes(){
    global $conn;
    $result=$conn->query("select * from class ORDER BY class_name ");
    return $result;

}
function get_total_class(){
    global $conn;
    $result=$conn->query("select count(*) from class ");
    return array_shift($result->fetch_assoc());
}
function get_class_courses($class){
    global $conn;
    $sql="select class_course.course_id,course.course_name";
    $sql.=" from class_course inner join course on course.course_id=class_course.course_id where class_id=".$class;


    $result=$conn->query($sql);
    return $result;

}
function get_all_semesters(){
    global $conn;
    $result=$conn->query("select * from semester ORDER by ac_year_from");
    return $result;
}
function get_ac_year(){
    global $conn;
    $result=$conn->query("select ac_year_from,ac_year_to from semester group by ac_year_from,ac_year_from");
    return $result;
}
function find_if_student_id($std_id){
    global $conn;
    $result=$conn->query("select student_id from student where student_id={$std_id}");
    if ($result->num_rows>0){
        return true;
    }else{
        return false;
    }

}
function find_unregistered_ids($handle){
    $file=fopen($handle,'r');
    $ids=array();
    while($data=fgetcsv($file,1000,',')){
        if(find_if_student_id($data[0])){
            //$cleared+=1;
            $ids['founded'][]=$data[0];
        }else{
            $ids['not_founded'][]=$data[0];
        }

    }
        fclose($file);
    return $ids;
}//returns muliti-dimention array
function find_unregistered_id($id){
   global $conn;
    $result=$conn->query("select * from student where student_id={$id}");
    if($result->num_rows>0){
        return true;
    }else{
        return false;
    }
}//returns boolean
function upload_into_grades($handle,$course_id,$semester_id){
    global $conn;
    $file=fopen($handle,'r');
    while($data=fgetcsv($file,1000,',')){
        $sql="insert into grades(student_id,course_id,semester_id,result,submitted_by,remark)";
        if(isset($data[2])){$remark=$data[2];}else{$remark='null';}
        $sql.=" VALUES({$data[0]},{$course_id},{$semester_id},{$data[1]},'{$_SESSION['user']}','$remark')";
        if ($conn->query($sql) === TRUE) {
            $message['true']=true;
        } else {
            $message['false']['message']= "Error: " . $conn->error;
            break;
        }


    }
    fclose($file);
    return $message;
} //returns multi-dim $message['false'],$message['true']
function get_class_students($class){
    global $conn;
    $sql="select * from student where class_id={$class} order by student_id";
    $result=$conn->query($sql);
    return $result;
}
function insert_into_results($result,$student_id,$class_course_id,$user,$remark,$markg){
    global $conn;
    $sql="insert into results(student_id,class_course_id,result,submitted_by,remark,mark_group_id)";
    $sql.="values({$student_id},{$class_course_id},{$result},'{$user}','$remark',$markg)";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
return $message;
}
function insert_into_student($id,$fname,$mname,$lname,$number="NULL",
                             $class="NULL",$school="NULL",$roll_no="NULL",$gra_year="NULL",
                             $gra_grade="NULL",$pwd="NULL",$active="NULL",$remark="NULL"){
    global $conn;
    $sql="insert into student(student_id,first_name,middle_name,last_name,school,roll_No,graduated_year,graduation_grade,phone_number,class_id,pwd,remark,active) ";
    $sql.=" values($id,'$fname','$mname','$lname','$school','$roll_no','$gra_year','$gra_grade','$number','$class','$pwd','$remark',$active)";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}//returns multi-dim $message['false'],$message['true']
function upload_into_student($handle,$class_id,$pwd='null',$active){
    global $conn;
    $file=fopen($handle,'r');
    while($data=fgetcsv($file,1000,',')){
        //data[9]=remark
        if(isset($data[9])){if($data[9]==null || $data[9]==""){$data[9]="null";}else{$remark=$data[9];}}else{$remark='NULL';}
        $sql="insert into student(student_id,first_name,middle_name,last_name,school,roll_No,graduated_year,graduation_grade,phone_number,class_id,pwd,remark,active)";
        $sql.=" VALUES({$data[0]},'{$data[1]}','{$data[2]}','{$data[3]}','{$data[4]}','{$data[5]}','{$data[6]}','{$data[7]}',{$data[8]},$class_id,'$pwd','$remark',$active)";
        if ($conn->query($sql) === TRUE) {
            $message['true']=true;
        } else {
            $message['false']['message']= "Error: " . $conn->error;
            break;
        }
    }
    fclose($file);
    return $message;
}
function search_result($id,$class,$semester,$submitted_date,$course_id){
    global $conn;
    $sql="select grade_id,results.student_id,student.student_name,student.class_id,class.class_name,class.shift,";
    $sql.="course.course_name,course.course_code,results.result,results.semester_id,submitted_date from results inner join student on grades.student_id=student.student_id ";
    $sql.="inner join class on student.class_id = class.class_id inner join course on grades.course_id=course.course_id ";
    $sql.="where grades.student_id like '{$id}%' and student.class_id like'{$class}%' and grades.semester_id like '{$semester}%'";
    $sql.="and submitted_date like '{$submitted_date}%' and grades.course_id like '{$course_id}%'";
    if($result['rows']=$conn->query($sql)){
        return $result;
    }else {
        $result['false']['message']=$conn->error;
        return $result;
    }


//$result['rows']->num_rows>0

}
function delete_grade($grade_id){
    global $conn;
    $sql="delete from grades where grade_id={$grade_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function get_grade_by_id($grade_id){
    global $conn;
    $sql="select grade_id,grades.student_id,course_id,semester_id,result,remark,student.class_id ";
    $sql.="from grades inner join student on grades.student_id=student.student_id where grade_id={$grade_id}";
    $result=$conn->query($sql);
    return $result;
}
function get_student_by_class($class_id){
    global $conn;
    $sql="select student_id from student where class_id=$class_id";
    $result=$conn->query($sql);
    return $result;
}
function update_grade($result,$student_id,$course_id,$semester_id,$remark,$grade_id){
    global $conn;
    $sql="update grades set student_id=$student_id,";
    $sql.="course_id=$course_id,semester_id=$semester_id,";
    $sql.="result=$result,remark='$remark' where grade_id=$grade_id;";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function semester_class_courses($class,$semester){
    global $conn;
    $sql="select class_course.course_id,course.course_name,course.course_code";
    $sql.=" from class_course inner join course on class_course.course_id=course.course_id where class_id=".$class;
    $sql.=" and semester_id=$semester order by course.course_name";
    $result=$conn->query($sql);
    return $result;
}
function get_all_courses(){
    global $conn;
    $result=$conn->query("select * from course ORDER BY course_name");
    return $result;

}
function get_all_courses_limit($limit,$offset){
    global $conn;
    $result=$conn->query("select * from course ORDER BY course_name LIMIT $limit OFFSET $offset ");
    return $result;

}
function get_total_courses(){
    global $conn;
    $result=$conn->query("select count(*) from course ");
    return array_shift($result->fetch_assoc());
}
function insert_into_class_course($class,$course,$semester,$cr_hours){
    global $conn;
    $sql="insert into class_course(class_id,course_id,semester_id,cr_hours) ";
    $sql.=" values(".$class.",".$course.",".$semester.",".$cr_hours.")";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function get_all_semestersby_ac($ac_year_from,$ac_year_to){
    global $conn;
    $result=$conn->query("select * from semester where ac_year_from='$ac_year_from' and ac_year_to='$ac_year_to';");
    return $result;
}
function delete_class($class_id){
    global $conn;
    $sql="delete from class where class_id={$class_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function delete_course($course_id){
    global $conn;
    $sql="delete from course where course_id={$course_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function delete_mark($mark_id){
    global $conn;
    $sql="delete from mark where mark_id={$mark_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function insert_into_class($class_name,$dep_id,$gr_year,$section,$class_shift,$batch){
    global $conn;
    $sql="insert into class(class_name,department_id,graduation_year,section,shift,batch)";
    $sql.="values('$class_name','$dep_id','$gr_year','$section','$class_shift',$batch)";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function get_class_by_id($id){
    global $conn;
    $result=$conn->query("select * from class WHERE class_id = $id ORDER BY class_name");
    return $result;

}
function get_all_courses_by_id($id){
    global $conn;
    $result=$conn->query("select * from course WHERE course_id = $id ORDER BY course_name");
    return $result;

}
function update_class($class_id,$class_name,$department_id,$graduation_year,$section,$shift,$batch){
    global $conn;
    $sql="update class set class_name='$class_name',";
    $sql.="shift='$shift' ,department_id='$department_id',graduation_year='$graduation_year',section='$section',batch='$batch' ";
    $sql.="where class_id=$class_id;";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function update_course($course_id,$course_name,$course_code){
    global $conn;
    $sql="update course set course_name='$course_name',";
    $sql.="course_code='$course_code'";
    $sql.="where course_id=$course_id;";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function insert_into_course($course_name,$course_code){
    global $conn;
    $sql="insert into course(course_name,course_code)";
    $sql.="values('$course_name','$course_code')";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function get_all_marks(){
    global $conn;
    $sql="select mark,fromm,too,points,mark_id,mark_group.mark_group_name from mark inner JOIN mark_group on mark.mark_group_id=mark_group.mark_group_id ORDER by fromm DESC ";
    $result=$conn->query($sql);
    return $result;

}
function get_all_marks_by_id($id){
    global $conn;
    $result=$conn->query("select * from mark WHERE mark_id = $id ORDER BY mark");
    return $result;

}
function update_mark($mark_id,$mark,$from,$too,$points){
    global $conn;
    $sql="update mark set mark='$mark',";
    $sql.="fromm=$from,too=$too ,points=$points ";
    $sql.="where mark_id=$mark_id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function insert_into_mark($mark,$from,$too,$points,$mark_group){
    global $conn;
    $sql="insert into mark(mark,fromm,too,points,mark_group_id)";
    $sql.="values('$mark','$from','$too','$points',$mark_group)";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function delete_semester($semester_id){
    global $conn;
    $sql="delete from semester where semester_id={$semester_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function insert_into_semester($sms_name,$ac_from,$ac_to){
    global $conn;
    $sql="insert into semester(semester_name,ac_year_from,ac_year_to)";
    $sql.="values('$sms_name','$ac_from','$ac_to')";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;


}
function get_all_sms_by_id($id){
    global $conn;
    $result=$conn->query("select * from semester WHERE semester_id = $id ORDER BY ac_year_from ");
    return $result;
}
function update_semester($sms_id,$sms_name,$sms_ac_from,$sms_ac_to){
    global $conn;
    $sql="update semester set semester_name='$sms_name',";
    $sql.="ac_year_from=$sms_ac_from,ac_year_to=$sms_ac_to ";
    $sql.="where semester_id=$sms_id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function get_all_users(){
    global $conn;
    $result=$conn->query("select * from user ORDER BY class_name");
    return $result;

}
function get_all_departments(){
    global $conn;
    $result=$conn->query("select * from department ORDER by dep_name");
    return $result;
}
function get_all_dep_by_id($id){
    global $conn;
    $result=$conn->query("select * from department WHERE dep_id = $id ORDER BY dep_name ");
    return $result;

}
function update_department($dep_id,$dep_name,$location){
    global $conn;
    $sql="update department set dep_name='$dep_name',";
    $sql.="location='$location' ";
    $sql.="where dep_id=$dep_id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function delete_department($dep_id){
    global $conn;
    $sql="delete from department where dep_id={$dep_id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;


}
function insert_into_department($dep_name,$dep_location){
    global $conn;
    $sql="insert into department(dep_name,location) ";
    $sql.="values('$dep_name','$dep_location')";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;


}
function search_student($student_id,$first_name,$class_id){
    global $conn;
    $sql="select student_id,first_name,middle_name,last_name,phone_number,remark,active,class.class_name,class.batch,class.section,class.shift";
    $sql.=" from student inner join class on student.class_id=class.class_id ";
    $sql.="where student.student_id like '$student_id%' and student.class_id like '$class_id%' and first_name like '$first_name%'";
    if($result['rows']=$conn->query($sql)){
        return $result;
    }else {
        $result['false']['message']=$conn->error;
        return $result;
    }

}
function delete_student($id){
    global $conn;
    $sql="delete from student where student_id={$id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function get_students_by_id($id){
    global $conn;
    $result=$conn->query("select * from student where student_id=$id ORDER by student_id");
    if(is_object($result)&& $result->num_rows>0){
        return $result;
    }else{
        return false;
    }
}
function update_student($student_id,$first_name,$middle_name,$last_name,$school,$roll_No,
                        $graduated_year,$graduation_grade,$phone_number,$class_id,$pwd,$remark,$active){
    global $conn;
    $sql="update student set ";
    $sql.="first_name='$first_name' ,middle_name='$middle_name' ,last_name='$last_name',school='$school',roll_No='$roll_No',";
    $sql.="graduated_year='$graduated_year',graduation_grade='$graduation_grade',";
    $sql.="phone_number='$phone_number',class_id='$class_id',pwd='$pwd',remark='$remark',active='$active'";
    $sql.=" where student_id='$student_id'";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;


}
function get_edited_student($id){
    global $conn;
    $sql="select student_id,first_name,middle_name,last_name,phone_number,remark,active,class.class_name,class.batch,class.section,class.shift";
    $sql.=" from student inner join class on student.class_id=class.class_id ";
    $sql.="where student.student_id =$id";
    $result=$conn->query($sql);
    return $result;

}
function upload_into_sms_registration($handle,$semester_id,$remark){
    global $conn;
    $file=fopen($handle,'r');
    while($data=fgetcsv($file,1000,',')){
        $sql="insert into semester_registration(student_id,semester_id,remark)";
        $sql.=" VALUES({$data[0]},{$semester_id},'{$remark}')";
        if ($conn->query($sql) === TRUE) {
            $message['true']=true;
        } else {
            $message['false']['message']= "Error: " . $conn->error;
            break;
        }


    }
    fclose($file);
    return $message;
}
function search_reg_student($id,$name,$class){
    global $conn;
    $sql="select semester_registration.semester_id,semester_registration.student_id,student.student_id,student.class_id,class.class_name,class.shift,class.batch,class.section,";
    $sql.="semester.semester_name,semester_registration.remark,semester.ac_year_from,semester.ac_year_to";
    $sql.=",sms_reg_id,student.first_name,student.middle_name,student.last_name";
    $sql.=" from semester_registration inner join semester on semester_registration.semester_id=semester.semester_id inner join student ";
    $sql.="on semester_registration.student_id=student.student_id inner join class on student.class_id=class.class_id where semester_registration.student_id like '$id%' and student.first_name like '%$name%' and student.class_id like '%$class'";
    $sql.=" order by student.class_id,semester_registration.student_id,ac_year_from,semester.semester_name" ;
    if($result['rows']=$conn->query($sql)){
        return $result;
    }else {
        $result['false']['message']=$conn->error;
        return $result;
    }
}
function get_std_sms($id){
    global $conn;
    $sql="select semester_registration.semester_id,semester.semester_name,";
    $sql.="student_id,semester.ac_year_from,semester.ac_year_to ";
    $sql.="from semester_registration inner join semester on semester_registration.semester_id=semester.semester_id ";
    $sql.="where student_id=$id order by semester.ac_year_from,semester.semester_name";
    $result=$conn->query($sql);
    return $result;
}
function get_all_class_courses(){
    global $conn;
    $sql="select class_course_id,class_course.class_id,class.class_name,class.shift,class.batch,class.section,semester.semester_name,";
    $sql.="semester.ac_year_from,semester.ac_year_to,";
    $sql.="class_course.course_id,course.course_name,course.course_code,cr_hours from class_course ";
    $sql.="inner join course on class_course.course_id=course.course_id ";
    $sql.="inner join class on class_course.class_id=class.class_id ";
    $sql.="inner join semester on class_course.semester_id=semester.semester_id order by semester.ac_year_from;";
    $result=$conn->query($sql);
    return $result;
}
function search_clscrs_by_class($class_id,$sms){
    global $conn;
    $sql="select class_course_id,class_course.class_id,class.class_name,class.shift,class.batch,class.section,semester.semester_name,";
    $sql.="semester.semester_id,semester.ac_year_from,semester.ac_year_to,";
    $sql.="class_course.course_id,course.course_name,course.course_code,cr_hours from class_course ";
    $sql.="inner join course on class_course.course_id=course.course_id ";
    $sql.="inner join class on class_course.class_id=class.class_id ";
    $sql.="inner join semester on class_course.semester_id=semester.semester_id ";
    $sql.="where class_course.class_id = $class_id and semester.semester_id like '%$sms' order by ac_year_from,semester_name;";
    $result=$conn->query($sql);
    return $result;//group by class_id,course_name order by ac_year_from
}
function delete_class_course($id){
    global $conn;
    $sql="delete from class_course where class_course_id={$id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function get_clscrs_by_id($id){
    global $conn;
    $result=$conn->query("select * from class_course WHERE class_course_id = $id ");
    return $result;
}
function update_clscrs($class_course_id,$class_id,$semester_id,$course_id,$cr_hours){
    global $conn;
    $sql="update class_course set class_id=$class_id";
    $sql.=",semester_id=$semester_id ";
    $sql.=",cr_hours=$cr_hours ";
    $sql.=",course_id=$course_id ";
    $sql.="where class_course_id=$class_course_id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function insert_into_std_reg($std,$sms,$remark){
    global $conn;
    $sql="insert into semester_registration(semester_id,student_id,remark) ";
    $sql.="values($sms,$std,'$remark')";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function find_if_std_sms_registered($std,$sms){
    global $conn;
    $result=$conn->query("select * from semester_registration where student_id={$std} and semester_id={$sms}");
    if (is_object($result) && $result->num_rows>0){
        return true;
    }else{
        return false;
    }
}
function insert_into_mark_group($markg_name,$remark){
    global $conn;
    $sql="insert into mark_group(mark_group_name,remark) ";
    $sql.="values('$markg_name','$remark')";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;


}
function get_all_mark_group(){
    global $conn;
    $sql="select * from mark_group";
    $result=$conn->query($sql);
    return $result;
}
function delete_mark_group($id){
    global $conn;
    $sql="delete from mark_group where mark_group_id={$id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;

}
function get_all_marks_by_markg($markg){
    global $conn;
    $sql="select mark,fromm,too,points,mark_id,mark_group.mark_group_name from mark ";
    $sql.="inner JOIN mark_group on mark.mark_group_id=mark_group.mark_group_id where mark.mark_group_id={$markg} ORDER by fromm DESC ";
    $result=$conn->query($sql);
    return $result;
}
function find_if_std_crs_result($std,$class_course){
    global $conn;
    $result=$conn->query("select * from results where student_id={$std} and class_course_id={$class_course}");
    if (is_object($result) && $result->num_rows>0){
        return true;
    }else{
        return false;
    }
}
function get_all_sms_reg_by_id($id){
    global $conn;
    $result=$conn->query("select * from semester_registration WHERE sms_reg_id = $id");
    return $result;
}
function update_sms_reg($id,$sms,$remark){
    global $conn;
    $sql="update semester_registration set semester_id=$sms";
    $sql.=",remark='$remark' ";
    $sql.="where sms_reg_id=$id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function delete_sms_reg($id){
    global $conn;
    $sql="delete from semester_registration where sms_reg_id={$id}";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function get_clscrs_id($cls,$crs,$sms){
    global $conn;
    $result=$conn->query("select * from class_course WHERE semester_id=$sms and course_id=$crs and class_id=$cls");
    return $result;

}
function check_if_std_in_cls($std,$cls){
    global $conn;
    $result=$conn->query("select * from student where student_id={$std} and class_id={$cls}");
    if (is_object($result) && $result->num_rows>0){
        return true;
    }else{
        return false;
    }
}
function get_std_results($id){
    global $conn;
    $sql="select results.student_id,student.first_name,student.middle_name,student.last_name,
Class_course.class_id,class.class_name,class.shift,class.section,class.batch,
Class_course.semester_id,semester.semester_name,semester.ac_year_from,semester.ac_year_to ,
Class_course.course_id,course.course_name,course_code ,class_course.cr_hours,
result,
mark_group_id from results
inner join class_course on results.class_course_id=class_course.class_course_id
inner join class on Class_course.class_id =class.class_id
inner join student on results.student_id=student.student_id
inner join semester on class_course.semester_id=semester.semester_id
inner join course on class_course.course_id=course.course_id where results.student_id=$id order by ac_year_from,semester_name";
    $result=$conn->query($sql);
    return $result;
}
function get_mark_by_mark_group($mark_group_id){
    global $conn;
    $result=$conn->query("select * from mark WHERE mark_group_id=$mark_group_id");
    return $result;
}
function get_std_username_by_id($id){
    global $conn;
    $sql="select student_id,first_name,middle_name,last_name,pwd,phone_number,class.class_name,class.shift,
          class.section,class.batch,class.graduation_year,active
          from student
          inner join class on student.class_id=class.class_id where student.student_id=$id";
    $result=$conn->query($sql);
    if(is_object($result)&& $result->num_rows>0){
        return $result;
    }else{
        return false;
    }
}
function attemp_std_login($id,$password){
    $student=get_std_username_by_id($id);
    if($student){
        $student=$student->fetch_assoc();
        $saved_std_pwd=$student['pwd'];
        if(password_verify($password,$saved_std_pwd)){
            return $student;

        }else{return false;}


    }else{return false;}

}
function logged_in(){
    return isset($_SESSION['student_id']);
}
function confirm_logged_in(){
    if(!logged_in()){
        redirect_to('index.php');
    }
}

function admin_logged_in(){
    return isset($_SESSION['user_seq']);
}
function admin_confirm_logged_in(){
    if(!admin_logged_in()){
        redirect_to('index.php');
    }
}

function get_admin_username_by_id($id){
    global $conn;
    $sql="select user_seq,user_id,pwd,acc_group,created_date,full_name from user where user_id='$id'";
    $result=$conn->query($sql);
    if(is_object($result)&& $result->num_rows>0){
        return $result;
    }else{
        return false;
    }
}
function attemp_admin_login($id,$password){
    $admin=get_admin_username_by_id($id);
    if($admin){
        $admin=$admin->fetch_assoc();
        $saved_admin_pwd=$admin['pwd'];
        if(password_verify($password,$saved_admin_pwd)){
            return $admin;

        }else{return false;}


    }else{return false;}

}
function update_std_pwd($id,$pwd){
    global $conn;
    $sql="update student set pwd='$pwd' where student_id=$id";
    if ($conn->query($sql) === TRUE) {
        $message['true']=true;
    } else {
        $message['false']['message']= "Error: " . $conn->error;
    }
    return $message;
}
function send_sms(array $to,$message){
    // Textlocal account details
    $username = 'moe1047@gmail.com';
    $hash = '98c1c0f2925cf3355b92f33cafac31342cdab047';

    // Message details
    $numbers = $to;
    $sender = urlencode('ADMAS UNIVERSITY');
    $message = rawurlencode($message);

    $numbers = implode(',', $numbers);

    // Prepare data for POST request
    $data = array('username' => $username, 'hash' => $hash, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

    // Send the POST request with cURL
    $ch = curl_init('http://api.txtlocal.com/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Process your response here
    //echo $response;


}
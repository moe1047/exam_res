<head>
    <title>
        Student Academic Report
    </title>
</head>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<script src="js/custom_js.js"></script>
<?php
session_start();
require_once('../includes/functions.php');
confirm_logged_in();
$student_result=get_std_results($_SESSION['student_id']);
if(isset($_REQUEST['log_out'])){
    $_SESSION=array();
    redirect_to("index.php");
}
?>

<div class="container">
    <div class="row">
        <div class="panel panel-default ">
            <div class="panel-body">
                <h1 class="text-center">
                    ADMAS UNIVERSITY
                    <h3 class="text-center">Student's Academic Record</h3>
                    <hr class='message-inner-separator'>
                </h1>

                <?php require_once('../includes/layout/std_nav.php');?>


                <div class="panel panel-default ">
                    <div class="panel-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td><b>Course Code</b></td>
                                <td><b>Course Title</b></td>
                                <td><b>Result</b></td>
                                <td><b>Credit Hours</b></td>
                                <td><b>Grade</b></td>
                                <td><b>points</b></td>
                                <td><b>Total Grade points</b></td>
                            </tr>
                            </thead>
                            <tbody>


                <?php
                if(isset($student_result) && is_object($student_result)){
                $points=0;

                $total_credit_hours=0;$total_points=0;$sum_total_grade_points=0;$semester_id=0;
                $ac_year_from=$ac_year_to=0;
                $comulative_points=0;$comulative_cr_hours=$comulative_total_grade=0;

                while($result=$student_result->fetch_assoc()){
                if($semester_id!==$result['semester_id'] && $semester_id!==0){
                echo"<td><b></b></td>";
                echo"<td><b></b></td>";
                echo"<td><b></b></td>";
                echo"<td><b>$total_credit_hours</b></td>";
                echo"<td><b></b></td>";
                echo"<td><b>$total_points</b></td>";
                echo"<td><b>$sum_total_grade_points</b></td>";

                    ?>

                            </tbody>
                        </table>
                        <h4 class="text-center">
                            <span class="label label-default">
                                <span class="glyphicon glyphicon-hand-right"></span> Semester GPA= <?php if(isset($total_points)&& isset($total_credit_hours)){
                                    echo round($sum_total_grade_points/$total_credit_hours,2);}
                                ?>
                            </span>
                        </h4>
                        <h4 class="text-left">


                        </h4>
                        <?php $total_credit_hours=0;$total_points=0;$sum_total_grade_points=0;?>
                    </div>
                </div>
                    <div class="panel panel-default ">
                        <div class="panel-body table-responsive">


                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td><b>Course Code</b></td>
                                    <td><b>Course Title</b></td>
                                    <td><b>Result</b></td>
                                    <td><b>Credit Hours</b></td>
                                    <td><b>Grade</b></td>
                                    <td><b>points</b></td>
                                    <td><b>Total Grade points</b></td>
                                </tr>
                                </thead>
                                <tbody>

                    <?php

                            }



                                    $table_row="<tr>";
                                    $table_row.="<td>".$result['course_code']."</td>";
                                    $table_row.="<td>".$result['course_name']."</td>";
                                    $table_row.="<td>".$result['result']."</td>";
                                    $table_row.="<td>".$result['cr_hours']."</td>";
                                    $table_row.="<td>";
                                    if(isset($result['mark_group_id'])){
                                        $marks=get_mark_by_mark_group($result['mark_group_id']);
                                        while($mark_row=$marks->fetch_assoc()){
                                            if($result['result']>=$mark_row['fromm'] && $result['result']<=$mark_row['too']){
                                                $table_row.=$mark_row['mark'];//grade ex: A+,A,B
                                                $points=$mark_row['points'];
                                            }

                                        }
                                    }


                                    $table_row.="</td>";

                                    $table_row.="<td>";
                                    if(isset($points)){
                                        $table_row.=$points;
                                    }
                                    $table_row.="</td>";
                                    $table_row.="<td>".$result['cr_hours']*$points."</td>";
                                    $table_row.="</tr>";
                                    echo $table_row;
                                    



                                    $total_credit_hours+=$result['cr_hours'];
                                    $total_points+=$points;
                                    $sum_total_grade_points+=$result['cr_hours']*$points;

                                    $semester_id=$result['semester_id'];
                                    $ac_year_from=$result['ac_year_from'];$ac_year_to=$result['ac_year_to'];
                    $comulative_points+=$points;
                    $comulative_cr_hours+=$result['cr_hours'];
                    $comulative_total_grade+=$points*$result['cr_hours'];



                                }

                                echo"<td><b></b></td>";
                                echo"<td><b></b></td>" ;
                                echo"<td><b></b></td>";
                                echo"<td><b>$total_credit_hours</b></td>";
                                echo"<td></td>";
                                echo"<td><b>$total_points</b></td>";
                                echo"<td><b>$sum_total_grade_points</b></td>";
                            }


                            ?>
                            </tbody>
                            </table>

                        <h4 class="text-center">
                            <span class="label label-default">
                                <span class="glyphicon glyphicon-hand-right"></span>
                                Semester GPA= <?php if(isset($total_points)&& isset($total_credit_hours)){
                                    echo round($sum_total_grade_points/$total_credit_hours,2);}
                                ?>
                            </span>
                        </h4>

                        </div>


                    </div>
                <div class="panel panel-default ">
                    <div class="panel-body">
                        <h3 class="text-center">
                            <span class="label label-default"><span class="glyphicon glyphicon-hand-right"></span> Comulative GPA= <?php if(isset($comulative_total_grade) && isset($comulative_cr_hours)){
                                echo round($comulative_total_grade/$comulative_cr_hours,2);}
                            ?>
                            </span>

                        </h3>

                    </div>
                </div>


    </div>
    <div class="row">
        <p class="bg-success" style="padding:10px;margin-top:20px"><small> Admas University</small></p>
    </div>
</div>
        </div>
    </div>


<div class="modal fade" id="ch_pass_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Change Password</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" role="form" id="change_std_pwd">
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Current password:</label>
                        <input type="password" class="form-control" id="recipient-name" placeholder="your old password" name="curr_pwd">
                    </div>

                    <div class="form-group" id="pass">
                        <label for="recipient-name" class="control-label">New Password:</label>
                        <input  type="password" id="new_pass" class="form-control"  placeholder="your new password" onkeyup="get_contents()" name="new_pwd">
                    </div>
                    <div class="form-group" id="pass_conf">
                        <label for="recipient-name" class="control-label">Confirm New Password:</label>
                        <input type="password" id="new_pass_conf" class="form-control"  placeholder="type the new password again" onkeyup="get_contents()" name="conf_pwd">
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
                <button type="button" class="btn btn-default" onclick="change_std_pwd();"><span class="glyphicon glyphicon-floppy-save" ></span> Save Password</button>
            </div>

        </div>
    </div>
</div>


<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

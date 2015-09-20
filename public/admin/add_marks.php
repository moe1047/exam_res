<?php require_once('../../includes/layout/header.php');
require_once('../../includes/functions.php');?>
<?php require_once('../../includes/validation_functions.php');
admin_confirm_logged_in();?>
<?php $page="add_marks";
?>
<script src="../js/jquery.js"></script>
<script>
    $(document).ready(function(){
        $("div#mark_group").hide();
    })

</script>
<!--/header-->
<!-- Navigation -->
<?php require_once('../../includes/layout/nav.php');?>
<!-- /Navigation -->
<?php
$message=array();
if(isset($_REQUEST['btn_delete_mrkg'])){?>
    <script>
        $(document).ready(function() {
            var mark_group=$("div#mark_group");
            mark_group.fadeIn();
            $("div#mark").hide(800);

            var mark=$("li#mark");
            $("li#mark_group").addClass("active");
            mark.removeClass("active");
        });


    </script>
    <?php
    $delete_markg_error=array();
    if(isset($_REQUEST['chk_delete_mrkg'])){
        $success_markg_message=0;
        foreach($_REQUEST['chk_delete_mrkg'] as $markg_id){
            $delete_markg_message=delete_mark_group($markg_id);
            if(isset($delete_markg_message['true'])){$success_markg_message+=1;}
            if(isset($delete_markg_message['false'])){$delete_markg_error['db_del_error']=$delete_markg_message['false']['message'];}
        }
    }else{$delete_markg_error['check_del_error']="No row is selected to delete";}

}
if(isset($_REQUEST['submit_mrkg'])){?>
    <script>

        $(document).ready(function() {
            var mark_group=$("div#mark_group");
            mark_group.fadeIn();
            $("div#mark").hide(800);

            var mark=$("li#mark");
            $("li#mark_group").addClass("active");
            mark.removeClass("active");

        });

    </script>
    <?php
    $add_markg_error=array();
    if(!isset($_REQUEST['txt_markg_name']) || empty($_REQUEST['txt_markg_name'])){
        $add_markg_error[]="Mark group name must be provided";

    }
    if(!isset($_REQUEST['txt_markg_remark']) || empty($_REQUEST['txt_markg_remark'])){
        $markg_remark="";

    }else{$markg_remark=$_REQUEST['txt_markg_remark'];}

    if(empty($add_markg_error)){
        $markg_msg_returned=insert_into_mark_group($_REQUEST['txt_markg_name'],$_REQUEST['txt_markg_remark']);
        if(isset($markg_msg_returned['true'])){$add_markg_success="added successfully";}
        if(isset($markg_msg_returned['false'])){$add_markg_error[]=$markg_msg_returned['false']['message'];}

    }

}

if(isset($_REQUEST['btn_delete_mrk'])){?>
    <script>
        $(document).ready(function() {

                $("div#mark_group").hide(800);
                $("div#mark").fadeIn();

                var mark_group = $("li#mark_group");
                mark_group.removeClass("active");
                $("li#mark").addClass("active");

        });
    </script>
    <?php
    if(isset($_REQUEST['chk_delete_mrk'])){
        $delete_error=array();
        $success_message=0;
        foreach($_REQUEST['chk_delete_mrk'] as $mark_id){
            $delete_message=delete_mark($mark_id);
            if(isset($delete_message['true'])){$success_message+=1;}
            if(isset($delete_message['false'])){$delete_error['db_del_error']=$delete_message['false']['message'];}
        }
    }else{$delete_error['check_del_error']="No row is selected to delete";}
}
if(isset($_REQUEST['btn_edit_mrk'])){?>
    <script>
        $(document).ready(function() {

                $("div#mark_group").hide(800);
                $("div#mark").fadeIn();

                var mark_group = $("li#mark_group");
                mark_group.removeClass("active");
                $("li#mark").addClass("active");


        });
    </script>
    <?php
    if(isset($_REQUEST['radio_edit_mrk'])){
        redirect_to('edit_mark_form.php?mark_id='.$_REQUEST['radio_edit_mrk']);
    }else{$edit_message['not_selected']="No row is selected to edit";}
}
if(isset($_POST['submit_mrk'])){?>
    <script>
        $(document).ready(function() {
            $("div#mark_group").hide(800);
                $("div#mark").fadeIn();

                var mark_group = $("li#mark_group");
                mark_group.removeClass("active");
                $("li#mark").addClass("active");

        });
    </script>
    <?php
    if(has_existence($_REQUEST['txt_mrk_from'])){
        if(is_numeric($_REQUEST['txt_mrk_from'])){
            $from=$_REQUEST['txt_mrk_from'];
        }else{$message['from_numeric']="range 'from' should be a number";}
    }else $message['from']="from range cannot be empty";

    if(has_existence($_REQUEST['txt_mrk_to'])){
        if(is_numeric($_REQUEST['txt_mrk_to'])){
            $to=$_REQUEST['txt_mrk_to'];
        }else{$message['txt_mrk_to']="range 'to' should be a number";}
    }else $message['txt_mrk_to']="'To' range cannot be empty";

    if(has_existence($_REQUEST['txt_mrk_points'])){
        if(is_numeric($_REQUEST['txt_mrk_points'])){
            $points=$_REQUEST['txt_mrk_points'];
        }else{$message['txt_mrk_points']="points should be a number";}
    }else $message['txt_mrk_points']="Points cannot be empty";

    if(has_existence($_REQUEST['txt_mrk_name'])){
            $mrk_name=$_REQUEST['txt_mrk_name'];
    }else $message['txt_mrk_name']="Mark symbol cannot be empty";

    if(has_existence($_REQUEST['ddl_mrk_mark_group'])){
        $mark_group=$_REQUEST['ddl_mrk_mark_group'];
    }else $message['ddl_mrk_mark_group']="Mark group should be chosen";


    if (empty($message)){
        $message_returned=insert_into_mark($mrk_name,$from,$to,$points,$mark_group);
        if(isset($message_returned['true'])){$message_success_submit="added successfully";}
        if(isset($message_returned['false'])){$message['error_message']=$message_returned['false']['message'];}
    }
}

?>
<div id="page-wrapper">

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation" id="mark" class="active"><a href="#">Mark</a></li>
                        <li role="presentation" id="mark_group" ><a href="#">Mark Group</a></li>
                    </ul>
                </div>
            </div>

        </div><!-- col-md-->
        <div class="col-md-9">
            <div id="mark">
                <div class="col-md-4" id="add_mark_form ">
                    <?php if(isset($message) && !empty($message)){echo error_alert($message);} ?>
                    <?php if(isset($message_success_submit)){echo success_alert($message_success_submit);} ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Add Mark</h3></div>
                        <div class="panel-body">

                            <form role="form" method="post">
                                <div class="form-group">
                                    <label for="txt_class_name">Mark Symbol:</label>
                                    <input type="text" class="form-control" id="txt_class_name" placeholder="A+ / B / C" name="txt_mrk_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="txt_range_from">Range From:</label>
                                    <input type="text" class="form-control" id="txt_range_from" placeholder="90 / 80 / 70" name="txt_mrk_from" required>
                                </div>
                                <div class="form-group">
                                    <label for="txt_range_to">Range To:</label>
                                    <input type="text" class="form-control" id="txt_range_to" placeholder="90 / 80 / 7" name="txt_mrk_to" required>
                                </div>
                                <div class="form-group">
                                    <label for="txt_mrk_points">points:</label>
                                    <input type="text" class="form-control" id="txt_mrk_points" placeholder="4 / 4.5 / 3.5" name="txt_mrk_points" required>
                                </div>
                                <div class="form-group ">
                                    <label for="txt_std_class">Mark Group:</label>
                                    <select  class="form-control" name="ddl_mrk_mark_group" ">
                                        <?php $all_mark_groups=get_all_mark_group();
                                        while($mark_group = $all_mark_groups->fetch_assoc()){
                                            echo "<option value=".$mark_group['mark_group_id'].">".$mark_group['mark_group_name']."</option>";
                                        }?>
                                    </select>
                                </div>



                                <button type="submit" name="submit_mrk" class="btn btn-default btn-lg col-md-offset-4">Submit</button>
                            </form>
                        </div><!--/panel body-->
                    </div><!--/panel-->

                </div>
                <div class="col-md-8" id="add_mark_table">
                    <?php if(isset($delete_error) && !empty($delete_error)){echo error_alert($delete_error);} ?>
                    <?php if(isset($edit_message) && !empty($edit_message)){echo error_alert($edit_message);} ?>
                    <?php if(isset($success_message) && $success_message!=0 ){echo success_alert($success_message." Mark/s is deleted");} ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group col-md-7">
                                <label for="txt_std_class">Search By Mark Group:</label>
                                <select  class="form-control" id="ddl_search_mark_group" >
                                <?php $all_mark_groups=get_all_mark_group();
                                while($mark_group = $all_mark_groups->fetch_assoc()){
                                    echo "<option value=".$mark_group['mark_group_id'].">".$mark_group['mark_group_name']."</option>";
                                }?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <button type="submit" class=" btn btn-default btn-md" onclick="search_mark_by_markg()" name="search_cls_crs"><span class="glyphicon glyphicon-search"></span> Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Edit / delete Mark</h3></div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <table class="table table-hover" id="tbl_mark_results">
                                    <thead>
                                    <tr>
                                        <td><b>Grade</b> </td>
                                        <td><b>from</b> </td>
                                        <td><b>To</b></td>
                                        <td><b>Points</b></td>
                                        <td><b>Mark Group</b></td>
                                        <td><b>Edit</b></td>
                                        <td><b>Delete</b></td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                                <button type="submit" class="btn btn-default btn-md" name="btn_edit_mrk"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                                <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_mrk" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                            </form>
                        </div><!--/panel body-->
                    </div><!--/panel-->
                </div>
            </div>
            <div id="mark_group">
                <div class="col-md-4" id="add_mark_group_form">
                    <?php if(isset($add_markg_error) && !empty($add_markg_error)){echo error_alert($add_markg_error);} ?>
                    <?php if(isset($add_markg_success)){echo success_alert($add_markg_success);} ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Add Mark Group</h3></div>
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form role="form" method="POST" action="add_marks.php">
                                        <div class="form-group">
                                            <label for="txt_class_name">Mark Group Name:</label>
                                            <input type="text" class="form-control" id="txt_markg_name"  name="txt_markg_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt_class_name">Remark:</label>
                                            <textarea  class="form-control" id="txt_markg_remark"  name="txt_markg_remark"></textarea>
                                        </div>

                                        <button type="submit" name="submit_mrkg" class="btn btn-default btn-lg col-md-offset-3">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div><!--/panel body-->
                    </div><!--/panel-->
                </div>
                <div class="col-md-8" id="add_mark_group_table">
                    <?php if(isset($delete_markg_error) && !empty($delete_markg_error)){echo error_alert($delete_markg_error);} ?>
                    <?php if(isset($delete_markg_message['false'])){echo warning_alert("There might be marks that is assigned to this group");} ?>
                    <?php if(isset($success_markg_message) && $success_markg_message!=0 ){echo success_alert($success_markg_message." Mark group has been deleted");} ?>


                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Edit / delete Mark Group</h3></div>
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form role="form" method="post">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <td><b>Mark Group name</b> </td>
                                                <td><b>Remark</b> </td>
                                                <td><b>Delete</b></td>
                                            </tr>
                                            </thead>
                                            <?php
                                            $mark_results=get_all_mark_group();
                                            while($markg_row=$mark_results->fetch_assoc()){
                                                $table_row="<tr>";
                                                $table_row.="<td>".$markg_row['mark_group_name']."</td>";
                                                $table_row.="<td>".$markg_row['remark']."</td>";
                                                $table_row.="<td><input type='checkbox' name='chk_delete_mrkg[]' value='".$markg_row['mark_group_id']."'></td>";
                                                $table_row.="<tr>";
                                                echo $table_row;
                                            }
                                            ?>

                                        </table>
                                        <button type="submit" id="btn_del" class="btn btn-default btn-md" name="btn_delete_mrkg" onclick="return ConfirmDelete()"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                    </form>
                                </div>
                            </div>

                        </div><!--/panel body-->
                    </div><!--/panel-->
                </div>
            </div>

        </div>
        </div><!--/row-->
        </div><!-- /#page-wrapper -->


<script>
    $(document).ready(function(){
        $("ul.nav").children("li#mark").on("click",function(){
            $("div#mark_group").hide(800);
            $("div#mark").fadeIn();

            var mark_group=$("li#mark_group");
            mark_group.removeClass("active");
            $("li#mark").addClass("active");

        });



        $("ul.nav").children("li#mark_group").on("click",function(){
            var mark_group=$("div#mark_group");
            mark_group.fadeIn();
            $("div#mark").hide(800);

            var mark=$("li#mark");
            $("li#mark_group").addClass("active");
            mark.removeClass("active");

        });

    })
</script>
<?php require_once('../../includes/layout/footer.php');?>

function get_students_add_std(student_class)//to get the student for ddl_grade_class_m
{
    $.ajax({
        url: 'process_ajax.php?student_class=' + student_class,
        success: function(data) {
            $("#ddl_grade_std_m").html(data);
        }
    });
    get_std_semesters($("#ddl_grade_std_m").val());
    get_sms_courses($('#ddl_grade_sms_m').val());
}

function get_std_semesters(student)//to get the student for ddl_grade_class_m
{
    $.ajax({
        url: 'process_ajax.php?student_sms=' + student,
        success: function(data) {
            $("#ddl_grade_sms_m").html(data);
        }
    });
    get_sms_courses($('#ddl_grade_sms_m').val());
}

function get_sms_courses(semester)//to get the courses for ddl_grade_crs_m
{
    var class_id=$('#ddl_grade_cls_m').val();

    $.ajax({
        url: 'process_ajax.php?sms_course=' + semester+'&class_id='+class_id,
        success: function(data) {
            $("#ddl_grade_crs_m").html(data);
        }
    });
}
function ConfirmDelete()
{
var x = confirm("Are you sure you want to delete?");
    if (x)
        return true;
    else
        return false;
}
function search_class_courses()//to get the courses for ddl_grade_crs_m
{
    var class_id=$('#ddl_class_search').val();
    var sms_id=$('#ddl_sms_search').val();

    $.ajax({
        url: 'process_ajax.php?clscrs_cls_id=' + class_id+'&sms_id=' + sms_id,
        success: function(data) {
            $('#tbl_results tbody').html(data);
        }
    });
}

function search_sms_registrations()//to get the courses for ddl_grade_crs_m
{
    var std_id=$('#txt_search_reg_std_id').val();
    var name_id=$('#txt_searchstd_name').val();
    var cls_id=$('#ddl_searchstd_class').val();
    var submit=$('#search_sms_reg').val();

    $.ajax({
        url: 'process_ajax.php?sms_reg_std_id=' + std_id+'&sms_reg_name=' + name_id+'&sms_reg_cls_id=' + cls_id+'&search_sms_reg=' + submit,
        success: function(data) {
            $('#tbl_std_reg_result tbody').html(data);
        }
    });
}

function get_std_add_reg(student_class)//to get the student for ddl_grade_class_m
{
    $.ajax({
        url: 'process_ajax.php?add_reg_std_cls=' + student_class,
        success: function(data) {
            $("#ddl_add_reg_std_id").html(data);
        }
    });

}
function search_mark_by_markg(){
    var mark_group_id=$('#ddl_search_mark_group').val();
    $.ajax({
        url: 'process_ajax.php?mark_id=' + mark_group_id,
        success: function(data) {
            $("#tbl_mark_results tbody").html(data);
        }
    });

}
function get_courses_up(){
    var class_id=$('#ddl_up_result_cls').val();
    var semester=$('#ddl_up_result_sms').val();
    $.ajax({
        url: 'process_ajax.php?sms_course=' + semester+'&class_id='+class_id,
        success: function(data) {
            $("#ddl_up_result_crs").html(data);
        }
    });
}


function change_std_pwd(){

    $.ajax({
        type:"POST",
        url:"process_ajax.php",
        data:$("#change_std_pwd").serialize(),
        success:function(data){
            alert(data);
        }

    });


}



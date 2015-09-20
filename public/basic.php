<link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<script src="js/custom_js.js"></script>
<!--has-success has-feedback-->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Click Me</button>


<div class="modal fade" id="ch_pass_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Change Password</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Current password:</label>
                        <input type="text" class="form-control" id="recipient-name" placeholder="your old password">
                    </div>

                    <div class="form-group" id="pass">
                        <label for="recipient-name" class="control-label">New Password:</label>
                        <input  type="text" id="new_pass" class="form-control"  placeholder="your new password" onkeyup="get_contents()">
                    </div>
                    <div class="form-group" id="pass_conf">
                        <label for="recipient-name" class="control-label">Confirm New Password:</label>
                        <input type="text" id="new_pass_conf" class="form-control"  placeholder="type the new password again" onkeyup="get_contents()">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-floppy-save"></span> Save Password</button>
            </div>
        </div>
    </div>
</div>



<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script>
    function get_contents(){
        //$('#inputSuccess23').val(value);
        var input_pass=$('#new_pass').val();
        var input_conf_pass=$('#new_pass_conf').val();
        var div_pass=$('#pass');
        var div_conf_pass=$('#pass_conf');
        if(input_pass==input_conf_pass && input_pass!=="" && input_conf_pass!=="" ){
            div_pass.removeClass('has-error');
            div_conf_pass.removeClass('has-error');

            div_pass.addClass('has-success has-feedback');
            div_conf_pass.addClass('has-success has-feedback');


        }else if(input_pass!==input_conf_pass && input_pass!=="" && input_conf_pass!==""){
            div_pass.removeClass('has-success');
            div_conf_pass.removeClass('has-success');

            div_pass.addClass('has-error');
            div_conf_pass.addClass('has-error');
        }else{
            div_pass.removeClass('has-success has-error');
            div_conf_pass.removeClass('has-success has-error');
        }
    }

</script>
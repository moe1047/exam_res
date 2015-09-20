<?php session_start();
//require_once('../../includes/layout/std_header.php');
require_once('../../includes/functions.php');
require_once('../../includes/validation_functions.php');

if(isset($_REQUEST['txt_username']) &&!empty($_REQUEST['txt_username']) && isset($_REQUEST['txt_pwd']) &&!empty($_REQUEST['txt_pwd'])){
    $admin_id=test_input($_REQUEST['txt_username']);
    $pwd=test_input($_REQUEST['txt_pwd']);

    $admin=attemp_admin_login($admin_id,$pwd);
    if($admin){
        $_SESSION['user_seq']=$admin['user_seq'];
        $_SESSION['user_id']=$admin['user_id'];
        $_SESSION['acc_group']=$admin['acc_group'];
        $_SESSION['full_name']=$admin['full_name'];
        redirect_to('dashboard.php');
    }else{$error="Username/Password is not Valid";}
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Admin Login Form</title>

    <link rel="stylesheet" href="../css/admin_style.css">


    <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<div id="login_wrapper">
    <?php  if(isset($error)){ echo admin_error_alert($error);}?>
    <div class="login">

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
            <p>
                <label for="login">Username:</label>
                <input type="text" name="txt_username" id="login" placeholder="username" >
            </p>

            <p>
                <label for="password">Password:</label>
                <input type="password" name="txt_pwd" id="password" placeholder="password">
            </p>

            <p class="login-submit">
                <button type="submit" class="login-button">Login</button>
            </p>

            <p class="forgot-password"><a href="#">Forgot your password?</a></p>
        </form>
    </div>
</div>



<!--<section class="about">

    <p class="about-author">
        &copy; 2014&ndash;2015 <a href="http://admashmc.com" target="_blank">Admas University <br> Hargeisa Main campus</a>

</section>-->
</body>
</html>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
</div>
<!-- Top Menu Items on the right-->
<ul class="nav navbar-right top-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php  if(isset($_SESSION['full_name'])){echo $_SESSION['full_name'];} ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <a href="#"><i class="glyphicon glyphicon-pencil"></i> Add user</a>
            </li>
            <li>
                <a href="#"><i class="glyphicon glyphicon-cog"></i> Edit user</a>
            </li>

            <li class="divider"></li>
            <li>
                <a href="admin_logout.php?logout=1"><i class="glyphicon glyphicon-off"></i> Log Out</a>
            </li>
        </ul>
    </li>
</ul>
<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li <?php if(isset($page) && $page=='dashboard'){ echo "class='active'";} ?>>
            <a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <!--the start of the drop down list-->



        <li <?php if(isset($page) && $page=='add_class' or $page=='add_department' or $page=='add_class' or $page=='add_course' or $page=='add_semester'
            or $page=='add_marks'  or $page=='add_student' or $page=='upload_student'
        ){echo " class='active' "; }?>>
            <a href="#" data-toggle="collapse" data-target="#class"><i></i><i class="fa fa-plus-square"></i> Add New <i class="fa fa-fw fa-caret-down"></i></a>
            <!--the start of the drop down list items-->
            <ul id="class" class="collapse">
                <li >
                    <a href="add_class.php" ><span class='glyphicon glyphicon-home'></span> Add Class </a>
                </li>
                <li >
                    <a href="add_course.php" ><i class="fa fa-book"></i> Add Course </a>
                </li>
                <li >
                    <a href="add_department.php"><i class="fa fa-university"></i> Add Department</a>
                </li>
                <li >
                    <a href="add_semester.php"><i class="fa fa-suitcase"></i> Add Semester</a>
                </li>
                <li >
                    <a href="add_marks.php"><span class='glyphicon glyphicon-pencil'></span> Add Mark</a>
                </li>
                <li >
                    <a href="add_student.php"><i class="fa fa-user"></i> Add Student</a>
                </li>
                <li >
                    <a href="upload_student.php"><i class="fa fa-upload"></i> Upload Students</a>
                </li>

            </ul>
        </li>
        <li <?php if(isset($page) && $page=='add_std_grade'){echo " class='active' ";}?>>
            <a href="add_std_grade.php"><span class='glyphicon glyphicon-book'></span> Exam Results</a>
        </li>
        <li <?php if(isset($page) && $page=='search_student'){echo " class='active' ";}?>>
            <a href="search_student.php"><i class="glyphicon glyphicon-search"></i> Search Student</a>
        </li>
        <li <?php if(isset($page) && $page=='all_registration' or $page=='semester_reg' ){echo " class='active' "; }?>>
            <a href="#" data-toggle="collapse" data-target="#sms"><i></i><span class="glyphicon glyphicon-user"></span> Semester Registration <i class="fa fa-fw fa-caret-down"></i></a>
            <!--the start of the drop down list items-->
            <ul id="sms" class="collapse">
                <li >
                    <a href="semester_reg.php" ><span class='glyphicon glyphicon-upload'></span> Add registration </a>
                </li>
                <li >
                    <a href="all_registration.php" ><span class='glyphicon glyphicon-search'></span> Search registration </a>
                </li>
            </ul>
        </li>
        <li <?php if(isset($page) && $page=='class_course'){echo " class='active' ";}?>>
            <a href="class_course.php"><i class="fa fa-fw fa-file"></i> Class Course Registration</a>
        </li>


    </ul>
</div>
    <!-- /.navbar-collapse -->
</nav>

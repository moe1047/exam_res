<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--<a class="navbar-brand" href="#">M</a><br>-->
            <p class="navbar-text"><b>Name:</b> <?php echo ucfirst($_SESSION['first_name'])." ".ucfirst($_SESSION['middle_name'])." ".ucfirst($_SESSION['last_name'])?> . <br><b>  ID No :</b>  <?php echo $_SESSION['student_id']?></p>
            <p class="navbar-text"><b>  Class :</b> <?php echo strtoupper($_SESSION['class_name']); ?> <br> <b> Shift :</b> <?php echo ucfirst($_SESSION['shift']); ?></p>
            <p class="navbar-text"><b>Section :</b>  <?php echo ucfirst($_SESSION['section']); ?> <br> <b>  Batch :</b>  <?php echo ucfirst($_SESSION['batch']); ?></p>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!--<ul class="nav navbar-nav">
                <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>-->

            <ul class="nav navbar-nav navbar-right">
                <!--<li><a href="#">Link</a></li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> Settings  <span class="caret"></span> </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#" data-toggle="modal" data-target="#ch_pass_modal">Change Password</a></li>
                        <!--<li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>-->
                    </ul>
                </li>
                <li>
                    <form role="form" action="student_report.php">
                        <button type="submit" class="btn btn-default navbar-btn" name="log_out"><span class="glyphicon glyphicon-log-out"></span> Sign Out</button>
                    </form>

                </li>
                <!--<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>-->
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
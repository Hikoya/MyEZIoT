<?php 

	require_once("../include/membersite_config.php");

	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("login");
		exit;
	}
	
	$username = $_SESSION['username_of_user'];
	
	if (isset($_POST['logout'])) {

		$fgmembersite->LogOut();
	}
	
	if(isset($_POST['submittedprofile']))
	{
		if($fgmembersite->UpdateProfile($username))
		{
			$fgmembersite->RedirectToURL("profile");
			exit;
		}
	
	}
	
	if(isset($_POST['submittedheader']))
	{
		if($fgmembersite->UpdateHeader($username))
		{
			$fgmembersite->RedirectToURL("index");
		}
	
	}
	
	if(isset($_POST['submittedsms']))
	{
		if($fgmembersite->UpdateSMS($username))
		{
			$fgmembersite->RedirectToURL("profile");
		}
	
	}
	
	$config = parse_ini_file('../private/config.ini'); 	 
	$sql = "SELECT * FROM ".$config['tablename']." where username = '".$username."'  ";
	
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$query = mysqli_query($link,$sql);
	if(mysqli_num_rows($query) > 0)
	{
		if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			$name = $result['name'];
			$email = $result['email'];
			$address = $result['address'];
			$postalcode = $result['postalcode'];
			$mobileno = $result['mobileno'];
			$callingcode = $result['callingcode'];
			
			if(!empty($result['column1']))
				$column1 = $result['column1'];
			else
				$column1 = "";
			
			if(!empty($result['column2']))
				$column2 = $result['column2'];
			else
				$column2 = "";
			
			if(!empty($result['column3']))
				$column3 = $result['column3'];
			else
				$column3 = "";
			
			if(!empty($result['column4']))
				$column4 = $result['column4'];
			else
				$column4 = "";
			
			if(!empty($result['column5']))
				$column5 = $result['column5'];
			else
				$column5 = "";
			
			if(!empty($result['column6']))
				$column6 = $result['column6'];
			else
				$column6 = "";
			
			$smsmobileno = $result['smsmobileno'];
			$smsemail = $result['smsemail'];
			
			$level = (int)$result['level'];
			if(!empty($result['cameraurl']))
				$cameraurl = $result['cameraurl'];
			else
				$cameraurl = '';
			
		}
	}
	
	$sitehead = $config['sitehead'];
	$sitebot = $config['sitebot'];
	$sitesmall = $config['sitesmall'];
	
?>




<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $sitehead?> | Edit Profile</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  
    <script src="https://use.fontawesome.com/cd59f17108.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

	<header class="main-header">

    <!-- Logo -->
    <a href="index" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $sitesmall ?></span>
      <!-- logo for regular state and mobile devices -->
      <p class="logo-lg" style="font-size:15px"><?php echo $sitehead ?> <span style="font-size:9px"><?php echo $sitebot ?></p>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle Navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		<li class="dropdown user user-menu">
            <div  >
              <img src="../dist/img/sp.png" alt="SP Logo">
            </div>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $fgmembersite->UserFullUserName(); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?= $fgmembersite->UserFullUserName(); ?>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="logout" id='logout' class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $fgmembersite->UserFullUserName(); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
    
      <!-- sidebar menu: : style can be found in sidebar.less -->
	   <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        
		<?php
			if($level == 1)
			{		
		?>
			<li>
			  <a href="index">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			  </a>
			</li>
			<li><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
		
		<?php
				
			}
			else if($level == 2)
			{
		?>
			<li>
			  <a href="index">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			  </a>
			</li>
			<li><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
			<li class="treeview">
			  <a href="#">
				<i class="fa fa-plus-square"></i> <span>Manage</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li ><a href="registernode"> <i class="fa fa-desktop"></i> <span>Node</span></a></li>
				<li ><a href="registerswitch"> <i class="fa fa-hand-pointer-o"></i> <span>Switch</span></a></li>
			  </ul>
			</li>
			<li><a href="devices"><i class="fa fa-plug"></i> <span>Devices</span></a></li>
			
			<?php
			if(!empty($cameraurl))
			{
			?>
			<li ><a href="camera"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
			<?php
			}
			?>
			
			<li ><a href="threshold"> <i class="fa fa-tachometer"></i> <span>Threshold</span></a></li>
			<li><a href="profile"><i class="fa fa-user"></i> <span>Profile</span></a></li>
			
			
		<?php
			}
			else if($level == 3)
			{
		?>
			<li>
			  <a href="index">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			  </a>
			</li>
			<li><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
			<li class="treeview">
			  <a href="#">
				<i class="fa fa-plus-square"></i> <span>Manage</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li ><a href="registernode"> <i class="fa fa-desktop"></i> <span>Node</span></a></li>
				<li ><a href="registerswitch"> <i class="fa fa-hand-pointer-o"></i> <span>Switch</span></a></li>
			  </ul>
			</li>
			<li><a href="devices"><i class="fa fa-plug"></i> <span>Devices</span></a></li>
			
			<?php
			if(!empty($cameraurl))
			{
			?>
			<li ><a href="camera"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
			<?php
			}
			?>
			
			<li ><a href="threshold"> <i class="fa fa-tachometer"></i> <span>Threshold</span></a></li>
			<li><a href="profile"><i class="fa fa-user"></i> <span>Profile</span></a></li>
			
			
		<?php
			}
		?>
		
		
		<?php
		if($username == $config['adminname'])
		{	
		?>
				
			<li class="header">ADMIN</li>
			<li ><a href="approval"> <i class="fa fa-check-circle"></i> <span>Approval</span></a></li>
			<li ><a href="utilities"> <i class="fa fa-gears"></i> <span>Utilities</span></a></li>
		<?php
		}
		?>
		
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Welcome, <?= $fgmembersite->UserFullUserName(); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    
	<div class = "row">
	<section>
		<div class="col-lg-7 col-xs-12">
		<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Information</h4>
				For Alert section, place a ';' between each number or email to send alerts to multiple numbers or emails <br>
				Country code is not required. <br>
				Example: 91234567;9234567;93456789 <br>
				Example: myeziot@myeziot.com;myeziott@myeziot.com
              </div>
		</div>
	</section>
	</div>
	
	<div class = "row">
	<section class="col-lg-7 connectedSortable">
	
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs pull-right">
            <li class="active"><a data-toggle="tab"><?= $fgmembersite->UserFullUserName(); ?></a></li>
            <li class="pull-left header"><i class="fa fa-pencil"></i> Edit Profile</li>
        </ul>
		
		  <div style="padding-top:50px"> </div>
		  <form id='update' class="form-horizontal" onsubmit="return validateForm()" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
		  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Full Name:<span class="req">*</span></label>
            <div class="col-lg-8">
              <input class="form-control" name="fullname" type="text" value="<?php echo $name ?>">
			  <span id='update_name_errorloc' class='error'></span>
            </div>
          </div> 
 
          <div class="form-group">
            <label class="col-lg-3 control-label">Email:<span class="req">*</span></label>
            <div class="col-lg-8">
              <input class="form-control" name="email" type="text" value="<?php echo $email?>">
			  <span id='update_email_errorloc' class='error'></span>
            </div>
          </div>
         <div class="form-group">
            <label class="col-md-3 control-label">Address:<span class="req">*</span></label>
            <div class="col-md-8">
              <input class="form-control" name="address" type="text" value="<?php echo $address?>">
			  <span id='update_address_errorloc' class='error'></span>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-md-3 control-label">Postal Code:<span class="req">*</span></label>
            <div class="col-md-8">
              <input class="form-control" name="postalcode" type="text" value="<?php echo $postalcode?>">
			  <span id='update_postalcode_errorloc' class='error'></span>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Calling Code<span class="req">*</span></label>
            <div class="col-md-8">
              <input class="form-control" name="callingcode" type="text" value="<?php echo $callingcode?>">
			  <span id='update_mobileno_errorloc' class='error'></span>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Mobile Number:<span class="req">*</span></label>
            <div class="col-md-8">
              <input class="form-control" name="mobileno" type="text" value="<?php echo $mobileno?>">
			  <span id='update_mobileno_errorloc' class='error'></span>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <button type="submit" class="btn btn-primary" style="width:120px" name="submittedprofile" id="submittedprofile">Update Profile</button>
              <span></span>
              <input type="reset" style="margin-left:100px" class="btn btn-default" value="Cancel">
            </div>
          </div>
        </form>
		
		<div style="padding-top:50px"> </div>
		
	</div>
	
	</section>
	
	<section class="col-lg-5 connectedSortable">
	
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs pull-right">
            <li class="active"><a data-toggle="tab"><?= $fgmembersite->UserFullUserName(); ?></a></li>
            <li class="pull-left header"><i class="fa fa-pencil"></i> Edit Field Name</li>
        </ul>
		
		  <div style="padding-top:50px"> </div>
		  <form id='update2' class="form-horizontal" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
		  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
          
		 
          <div class="form-group">
            <label class="col-lg-3 control-label">Field 1:</label>
            <div class="col-lg-8">
              <input class="form-control" name="column1" type="text" value="<?php echo $column1?>">
			  <span id='update_email_errorloc' class='error'></span>
            </div>
          </div>
		  
         <div class="form-group">
            <label class="col-md-3 control-label">Field 2:</label>
            <div class="col-md-8">
              <input class="form-control" name="column2" type="text" value="<?php echo $column2?>">
			  <span id='update_address_errorloc' class='error'></span>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Field 3:</label>
            <div class="col-md-8">
              <input class="form-control" name="column3" type="text" value="<?php echo $column3?>">
			  <span id='update_postalcode_errorloc' class='error'></span>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Field 4:</label>
            <div class="col-md-8">
              <input class="form-control" name="column4" type="text" value="<?php echo $column4?>">
			  <span id='update_mobileno_errorloc' class='error'></span>
            </div>
          </div>
		  
		    <div class="form-group">
            <label class="col-md-3 control-label">Field 5:</label>
            <div class="col-md-8">
              <input class="form-control" name="column5" type="text" value="<?php echo $column5?>">
			  <span id='update_mobileno_errorloc' class='error'></span>
            </div>
          </div>
		  
		    <div class="form-group">
            <label class="col-md-3 control-label">Field 6:</label>
            <div class="col-md-8">
              <input class="form-control" name="column6" type="text" value="<?php echo $column6?>">
			  <span id='update_mobileno_errorloc' class='error'></span>
            </div>
          </div>
		  
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <button type="submit" class="btn btn-primary" style="width:120px" name="submittedheader" id="submittedheader">Update Field</button>
              <span></span>
              <input type="reset" style="margin-left:60px" class="btn btn-default" value="Cancel">
            </div>
          </div>
        </form>
		
		<div style="padding-top:50px"> </div>
		
	</div>

	</section>
	
	<section class="col-lg-5 connectedSortable">
	
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs pull-right">
            <li class="active"><a data-toggle="tab"><?= $fgmembersite->UserFullUserName(); ?></a></li>
            <li class="pull-left header"><i class="fa fa-pencil"></i> Edit Alert Details</li>
        </ul>
		
		  <div style="padding-top:50px"> </div>
		  <form id='update2' class="form-horizontal" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
		  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
          
		 
          <div class="form-group">
            <label class="col-lg-3 control-label">SMS Email:</label>
            <div class="col-lg-8">
              <input class="form-control" name="smsemail" type="text" value="<?php echo $smsemail ?>">
			  <span id='update_email_errorloc' class='error'></span>
            </div>
          </div>
		  
         <div class="form-group">
            <label class="col-md-3 control-label">SMS Mobile Number:</label>
            <div class="col-md-8">
              <input class="form-control" name="smsmobileno" type="text" value="<?php echo $smsmobileno ?>">
			  <span id='update_address_errorloc' class='error'></span>
            </div>
          </div>
		  		  
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <button type="submit" class="btn btn-primary" style="width:120px" name="submittedsms" id="submittedsms">Update Details</button>
              <span></span>
              <input type="reset" style="margin-left:60px" class="btn btn-default" value="Cancel">
            </div>
          </div>
        </form>
		
		<div style="padding-top:50px"> </div>
		
	</div>
	
	
	</section>
	</div>  
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div>
<!-- ./wrapper -->

<script src="../scss/js/index.js"></script>
<script type='text/javascript'>

function validateForm() {
    var x = document.forms["update"]["fullname"].value;
    if (x == "") {
        alert("Name must be filled out");
        return false;
    }
	
	var x = document.forms["update"]["email"].value;
    if (x == "") {
        alert("Email must be filled out");
        return false;
    }

	var x = document.forms["update"]["callingcode"].value;
    if (x == "") {
        alert("Mobile number must be filled out");
        return false;
    }
	var x = document.forms["update"]["mobileno"].value;
    if (x == "") {
        alert("Mobile number must be filled out");
        return false;
    }
	var x = document.forms["update"]["password"].value;
    if (x == "") {
        alert("Password must be filled out");
        return false;
    }
}


</script>

<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>



</body>
</html>

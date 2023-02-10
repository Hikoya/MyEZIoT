<?PHP
require_once("../include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login");
    exit;
}

$sitename = '';
$sitename = $config['sitename'];

$username = '';
$username = $_SESSION['username_of_user'];

$reg = false;
$delete = false;
$edit = false;
						
if(isset($_POST['submitted']))
{
   if($fgmembersite->RegisterSwitch())
   {
	   $reg = true;
   }
}

if(isset($_POST['submitted2']))
{
   if($fgmembersite->DeleteSwitch($username))
   {
	   $delete = true;
   }
}

if(isset($_POST['submitted3']))
{
   if($fgmembersite->EditSwitch($username))
   {
	   $edit = true;
   }
}

$config = parse_ini_file("../private/config.ini");

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];
$sitesmall = $config['sitesmall'];


if (isset($_POST['logout'])) {

	$fgmembersite->LogOut();
}

$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
					// Attempt select query execution
					
$sql = "SELECT * FROM ".$config['tablename']." where username = '$username' ";
if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		while($row = $result->fetch_array(MYSQLI_BOTH))
		{
			$level = (int)$row['level'];
			if(!empty($row['cameraurl']))
				$cameraurl = $row['cameraurl'];
			else
				$cameraurl = '';
			
			$level = $row['level'];
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $sitehead ?> | Switch</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  
  <script src="https://use.fontawesome.com/cd59f17108.js"></script>
  
  
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
   <style>
  .select-style {
    border: 1px solid #ccc;
    border-radius: 3px;
    overflow: hidden;

}

.select-style select {
    padding: 5px 8px;
    width: 100%;
    border: none;
    box-shadow: none;
    background: transparent;
    background-image: none;
    -webkit-appearance: none;
}

.select-style select:focus {
    outline: none;
}
 </style>

</head>


<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="index" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $sitesmall ?></span>
      <!-- logo for regular state and mobile devices -->
      <p class="logo-lg" style="font-size:15px"><?php echo $sitehead?> <span style="font-size:9px"><?php echo $sitebot ?></p>
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
				<li class="active"><a href="registerswitch"> <i class="fa fa-hand-pointer-o"></i> <span>Switch</span></a></li>
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
				<li class="active"><a href="registerswitch"> <i class="fa fa-hand-pointer-o"></i> <span>Switch</span></a></li>
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
        <small>Switch</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Switch</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	    
		<div class="row">
		
		<?php
		if($reg){
			?>
			<section>
			<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-info"></i> Information</h4>
					You have successfully registered your switch!
				  </div>
			</div>
			</section>
			<?php
		}
		?>
		
		<?php
		if($delete){
			?>
			<section>
			<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-info"></i> Information</h4>
					You have successfully deleted your switch!
				  </div>
			</div>
			</section>
			<?php
		}
		?>
		
		<?php
		if($edit){
			?>
			<section>
			<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-info"></i> Information</h4>
					You have successfully edited your switch!
				  </div>
			</div>
			</section>
			<?php
		}
		?>
		
		<section>
		<div class="col-lg-12">
		<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Information</h4>
                Switch: Special boxes that contains a smart relay for ease of control of devices from outside.
              </div>
		</div>
		</section>
		
		<section>
		<div class="col-xs-12 col-md-12">
			<div class="box">
				<div class="box-header">
				<h3 class="box-title">Switches</h3>
				</div>
				
				<div class="box-body table-responsive no-padding">
				<div style="padding:10px">
						<?php
						
						
						if(!empty($username))
						{
						
						
						?>
						
						<table id="example2" class="table table-hover">
						<thead>
						<tr>
						  <th>Switch ID</th>
						  <th>Username</th>
						  <th>Description</th>
						</tr>
						</thead>
						<tbody>
						
						<?php
						
						$is_present = 0;
						$config = parse_ini_file('../private/config.ini'); 	   
						$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
						// Attempt select query execution
						
						$sql = "SELECT * FROM ".$config['tablenameswitch']." where username = '$username' ";

						if($result = mysqli_query($link, $sql)){
							if(mysqli_num_rows($result) > 0){
								while($row = $result->fetch_array(MYSQLI_BOTH))
								{
									
									$is_present = 1;
									
									$gatewayno = $row['gatewayno'];
									$switches[] = $gatewayno;
									
									echo "<tr>";
									echo "<td>" . $row['gatewayno'] . "</td>";
									echo "<td>" . $row['username'] . "</td>";
									echo "<td>" . $row['description'] . "</td>";
									
									echo "</tr>";
								}
								// Free result set
								mysqli_free_result($result);
								
							} 
						} 
						
						?>
					
						</tbody>
						</table>
					
						<?php 
					
						}
						else
						{
						
							$tab_content = '
							<div class="row">
							<div class="col-md-12">
								<p style="padding:50px"> Session expired. Please login again. </p>
							</div>
							</div>
							';
							
							echo $tab_content;			
						}							   
						?>			
				</div>
			</div>
		</div>
		
		  
	
	</section>
	</div>
	
	<div class="row">
	<section>
        
		<div class="col-lg-5 col-xs-12">
      
          <div class="box box-primary">
            <div class="box-header with-border">
				<div style="padding-top:50px"> </div>
				<form id='update3' style="margin-left:10px" class="form-horizontal" onsubmit="return validateForm3()" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
			
			<legend>Edit Switch</legend>
				
			<div class="form-group">
			  <label class="col-lg-3 control-label">Serial Number:<span class="req">*</span></label>
			  <div class="col-lg-7">
				<div class="select-style">
					<select id="serialno3" name="serialno3" onChange="populate();">
					
						<?php 
						if($is_present == 1)
						{
							$switch_content = '<option value=""></option>';
							foreach($switches as $key => $value)
							{
								$switch_content .= 
								'<option value="'.$value.'">'.$value.'</option>';
							}
							
						}
						else
							$switch_content = '<option value=""></option>';
						
						echo $switch_content;
						
						?>
						
					 </select>
					</div>
			  </div>
			</div> 
			
			<div class="form-group">
            <label class="col-lg-3 control-label">Description:<span class="req">*</span></label>
            <div class="col-lg-7">
              <input class="form-control" name="description3" id="description3" type="text">

            </div>
          </div>
                  
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary" style="width:100px" name="submitted3" id="submitted3">Edit Switch</button>
              <span></span>
            </div>
			<div class="col-md-3">
			  <button type="submit" class="btn btn-primary" style="width:100px" name="submitted2" id="submitted2">Delete Switch</button>
			</div>
          </div>
        </form>
			</div>
          </div>
        </div>
		
		
		<div class="col-lg-7 col-xs-12">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              
			
		   <div style="padding-top:50px"> </div>
		   <form id='update' class="form-horizontal" onsubmit="return validateForm()" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
		  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
		  
		  <legend>Register Switch</legend>
		  
          <div class="form-group">
            <label class="col-lg-3 control-label">Description:<span class="req">*</span></label>
            <div class="col-lg-7">
              <input class="form-control" name="description" type="text">

            </div>
          </div>
       
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-7">
              <button type="submit" class="btn btn-primary" style="width:120px" name="submitted" id="submitted">Register Switch</button>
              <span></span>
              <input type="reset" style="margin-left:100px" class="btn btn-default" value="Cancel">
            </div>
          </div>
        </form>
		
		<!--<div style="padding-top:50px"> </div>-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->  
        </div>
		
    

    </section>
    <!-- /.content -->
		
	</section>
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->


<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

<!--<script type='text/javascript'>
// <![CDATA[
   
   // var frmvalidator  = new Validator("register");
    //frmvalidator.EnableOnPageErrorDisplay();
   // frmvalidator.EnableMsgsTogether();
   // frmvalidator.addValidation("serialno","req","Please provide your serial number");
// ]]>
</script>-->

<script type='text/javascript'>

function validateForm() {
   	
	var x = document.forms["update"]["description"].value;
    if (x == "") {
        alert("Description must be filled out");
        return false;
    }
	
}


</script>

<script type='text/javascript'>

function validateForm2() {
    var x = document.forms["update2"]["serialno2"].value;
    if (x == "") {
        alert("Serial number must be filled out");
        return false;
    }
}

function validateForm3() {
    var x = document.forms["update3"]["serialno3"].value;
    if (x == "") {
        alert("Serial number must be filled out");
        return false;
    }
	
	var x = document.forms["update3"]["description3"].value;
    if (x == "") {
        alert("Description must be filled out");
        return false;
    }
	
}



</script>

<script>
  $(function () {
    $('#example2').DataTable();
  });
</script>


<script>

function populate(){
	
	var serialno3 = document.getElementById('serialno3').value;
	
	$.ajax({
      url: 'populate',
	  type: 'POST',
	  data: {switches: serialno3},
      success: function(data) {
		 var response_array = JSON.parse(data);
		 $('#description3').val(response_array['description']);  
	  }
    }); // end ajax call
	
}

</script>

</body>
</html>
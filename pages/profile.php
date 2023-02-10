<?php 

	require_once("../include/membersite_config.php");
	
	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("login");
		exit;
	}
	
	$username = $_SESSION['username_of_user'];
	
	if(isset($_REQUEST['generate']))
	{
		if($fgmembersite->CreateAPIKey($username))
		{
			$fgmembersite->RedirectToURL("profile");
		}
	}

	if (isset($_POST['logout'])) {

		$fgmembersite->LogOut();
	}
	
	
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
				// Attempt select query execution
				
	$sql = "SELECT * FROM ".$config['tablename']." where username = '$username' ";
			
	$writekey = '';
	$readkey = '';
	
	$content = '';
	$key_content = '';
	
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
				
				$content .= '
				<tr>
				<td> '. $row['name'] . '</td>
				<td> '. $row['username'] . '</td>
				<td> '. $row['email'] . '</td>
				<td> '. $row['address'] . '</td>
				<td> '. $row['postalcode'] . '</td>
			    <td> '. $row['callingcode'] . '</td>
				<td> '. $row['mobileno'] . '</td>
				</tr>';
							
				$writekey = $row['writekey'];
				$readkey = $row['readkey'];
			}
			// Free result set
			mysqli_free_result($result);
						
		} else{
				echo "No gateway IDs registered in this account. ";
			}
	} else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link) ;
		}
		
	if(!empty($writekey) && !empty($readkey))
	{
		$key_content .= '
		<div class = "col-md-5 col-xs-12">
		  
		  <div class="alert alert-info alert-dismissible">
			<h4><i class="icon fa fa-key"></i> API Keys</h4>
			  <div class = "row">
                <div class = "col-md-8 col-xs-8">
					<p style="font-size:20px" > Write Key :  <span id = "writekey" >'.$writekey.'</span></p>
				</div>
				<div class = "col-md-2 col-xs-2">
				  <button type="button" class="btn btn-group" data-clipboard-target="#writekey" ><i class="fa fa-clipboard"></i></button>
				</div>
			  </div>
			  
			  <div class="row">
                <div class="col-md-8 col-xs-8">
					<p style="font-size:20px" > Read Key :  <span id = "readkey" >'.$readkey.'</span></p>
				</div>
				<div class="col-md-1 col-xs-2">
				  <button type="button" class="btn btn-group" data-clipboard-target="#readkey" ><i class="fa fa-clipboard"></i></button>
				</div>
			  </div>
			 </div>
		   </div>
		';
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
  <title><?php echo $sitehead ?> | Users</title>
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
			<li class="active"><a href="profile"><i class="fa fa-user"></i> <span>Profile</span></a></li>
			
			
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
			<li class="active"><a href="profile"><i class="fa fa-user"></i> <span>Profile</span></a></li>
			
			
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
        <li class="active">Users</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
	  <section>
      <div class="row">
	  
	    <section>
		<div class="col-lg-7 col-xs-12">
		<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Information</h4>
				Write Key: API Key to send sensor data using the REST API <br>
				api.myeziot.com/api/writeapi?key=&ltapi_key&gt&ltdevice ID&gt&column1=data1&column2=data2 <br><br>
				Read Key: API Key to read information using the REST API <br>
				api.myeziot.com/api/readapi?key=&ltapi_key&gt&ltdevice ID&gt <br><br>
				
				Example: <br>
				Key : a12345678 <br>
				Device ID: aaaaa <br>
				<br>
				api.myeziot.com/api/writeapi?key=a12345678aaaaa&column1=25&column2=57 <br>
				api.myeziot.com/api/readapi?key=a12345678aaaaa<br>
				
              </div>
		</div>
		
		</section>
		
		<section>
		<?php
		if(!empty($key_content))
		{
			echo $key_content;
		}
		?>
		</section>
		
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
			  <div style="padding:10px">
              <table id="example1" class="table table-hover">
                <thead>
                <tr>
                  <th>Full Name</th>
				  <th>Username</th>
                  <th>Email</th>
				  <th>Address</th>
				  <th>Postal Code</th>
				  <th>Calling Code</th>
				  <th>Mobile Number</th>
                </tr>
                </thead>
                <tbody>
               
			    <?php
					echo $content;
				?>		   

              </tbody>
              </table>
			  </div>
			 
			</div>
		
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
	  </section>
	  
	  <section>
		
		<div class ="row">
			<div class="col-xs-3 col-lg-1" style="margin-right:20px">
			  <div id="changepwd"><a href="change-pwd">
				    <button type="button" id="changepwd" class="btn btn-app" ><i class="fa fa-pencil"></i>
					<p>Change Password</p>
					</button>
			  </a></div>
			</div>
			<div class="col-xs-3 col-lg-1" style="margin-left:-10px">
			  <div id="editprofile"><a href="editprofile">
				    <button type="button" id="editprofile" class="btn btn-app" ><i class="fa fa-pencil"></i>
					<p>Edit Profile   </p>  
					</button>
			  </a></div>
			 </div>
			 <div class="col-xs-3 col-lg-1" style="margin-left:-10px">
			  <div id="generate">
					<button type="button" id="editprofile2" class="btn btn-app" data-toggle="modal" data-target="#modal-generate" ><i class="fa fa-key"></i>
					<p>Generate new API Keys</p>
					</button>
			  </div>
			 </div>
		</div>
		
	   </section>
	   
	   <div class="modal fade" id="modal-generate">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Key Generation</h4>
              </div>
              <div class="modal-body">
                <p>Do you wish to generate a new set of API keys?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<form>
				    <input type="submit" name='generate' id='generate' value='Confirm' class="btn btn-primary">
				</form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>

<script>
$( document ).ready(function() {
  var clipboard = new Clipboard('.btn-group');
});
</script>

<script>
  $(function () {
    $("#example1").DataTable({
		 responsive: true
	});
  });
</script>



</body>
</html>

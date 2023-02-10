<?php 

	require_once("../include/membersite_config.php");

	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("login");
		exit;
	}

	if (isset($_POST['logout'])) {

		$fgmembersite->LogOut();
	}
	
$config = parse_ini_file("../private/config.ini");
$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];
$sitesmall = $config['sitesmall'];
$username = '';
$username = $_SESSION['username_of_user'];


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
  <title><?php echo $sitehead ?> | Devices</title>
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
			<li class="active"><a href="devices"><i class="fa fa-plug"></i> <span>Devices</span></a></li>
			
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
			<li class="active"><a href="devices"><i class="fa fa-plug"></i> <span>Devices</span></a></li>
			
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
        <li class="active">Devices</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
	  
		<section>
		<div class="col-lg-12">
		<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Information</h4>
                Nodes: Sensors that capture any data and are categorised into seperate columns for data analytics. <br><br>
				Switch: Special boxes that contains a smart relay for ease of control of devices from outside.
              </div>
		</div>
		</section>
		
		<section>
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Nodes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
				<div style="padding:10px">
			    <?php
				
				
				
				if(!empty($username))
				{
					
					
					?>
					
					<table id="example1" class="table table-hover">
					<thead>
					<tr>
					  <th>Node ID</th>
					  <th>Description</th>
					  <th>Location</th>
					  <th>Column 1</th>
					  <th>Column 2</th>
					  <th>Column 3</th>
					  <th>Column 4</th>
					</tr>
					</thead>
					<tbody>
					
					<?php
					
					$config = parse_ini_file('../private/config.ini'); 	   
					$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
					// Attempt select query execution
					
					$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '$username' ";
					
					if($result = mysqli_query($link, $sql)){
						if(mysqli_num_rows($result) > 0){
							while($row = $result->fetch_array(MYSQLI_BOTH))
							{
								echo "<tr>";
								echo "<td>" . $row['gatewayno'] . "</td>";
								echo "<td>" . $row['description'] . "</td>";
								echo "<td>" . $row['location'] . "</td>";
								
								if(!empty($row['column1']))
									echo "<td>" . ucfirst($row['column1']) . "</td>";
								else
									echo "<td>" . "N.A" . "</td>";
								
								if(!empty($row['column2']))
									echo "<td>" . ucfirst($row['column2']) . "</td>";
								else
									echo "<td>" . "N.A" . "</td>";
								
								if(!empty($row['column3']))
									echo "<td>" . ucfirst($row['column3']) . "</td>";
								else
									echo "<td>" . "N.A" . "</td>";
								
								if(!empty($row['column4']))
									echo "<td>" . ucfirst($row['column4']) . "</td>";
								else
									echo "<td>" . "N.A" . "</td>";
	
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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        
		<div class="col-xs-12 col-md-8">
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
						
						$config = parse_ini_file('../private/config.ini'); 	   
						$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
						// Attempt select query execution
						
						$sql = "SELECT * FROM ".$config['tablenameswitch']." where username = '$username' ";
					
						if($result = mysqli_query($link, $sql)){
							if(mysqli_num_rows($result) > 0){
								while($row = $result->fetch_array(MYSQLI_BOTH))
								{
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
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable();
  });
</script>

</body>
</html>

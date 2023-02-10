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
	
	//echo $username;
	
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
				// Attempt select query execution
	$admin = $config['adminname'];
	$is_present = 0;

	$sql = "SELECT * FROM ".$config['tablenamenode']." ";
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			
			$is_present = 1;
			while($row = $result->fetch_array(MYSQLI_BOTH))
			{
				$gatewayno[] = $row['gatewayno'];
			}
		}
	}
	
	
	$sql2 = "SELECT * FROM ".$config['tablename']." where username = '$username' ";
	if($result2 = mysqli_query($link, $sql2)){
		if(mysqli_num_rows($result2) > 0){
			while($row2 = $result2->fetch_array(MYSQLI_BOTH))
			{
				$level = (int)$row2['level'];
				if(!empty($row2['cameraurl']))
					$cameraurl = $row2['cameraurl'];
				else
					$cameraurl = '';
			}
		}
	}
	
	$content = '';
	
	foreach($gatewayno as $key => $value)
	{
		$sql3 = "SELECT * FROM ".$value." ";
		if($result3 = mysqli_query($link, $sql3)){
			
			$sql4 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' ";
			if($result4 = mysqli_query($link, $sql4)){
				if(mysqli_num_rows($result4) > 0){
					while($row4 = $result4->fetch_array(MYSQLI_BOTH))
					{
						
						$content .= '
						<tr id="row'.$row4['id'].'">
						<td id="user'.$row4['id'].'"> '. $row4['username'] . '</td>
						<td> '. $value . '</td>
						<td> '. mysqli_num_rows($result3) . '</td>
						<td> $'. mysqli_num_rows($result3) * $config['cost'] . '</td>
						';
						
					}
				}
			}
			
		}
		
	}
	
	$sitehead = $config['sitehead'];
	$sitebot = $config['sitebot'];
	$sitesmall = $config['sitesmall'];
	
?>

<?php

if($username == $admin)
	
{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $sitehead ?> | Utilities</title>
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
			<li><a href="approval"> <i class="fa fa-check-circle"></i> <span>Approval</span></a></li>
			<li class="active"><a href="utilities"> <i class="fa fa-gears"></i> <span>Utilities</span></a></li>
			
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
        <li class="active">Utilities</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
	  <section>
      <div class="row">
		
		<?php 
		
			if($is_present == 1)
			{
		?>
		
		  <div class="col-xs-12">
			  <div class="box">
				<div class="box-header">
				  <h3 class="box-title">Utilities</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
				  <div style="padding:10px">
				  <table id="example1" class="table table-hover">
					<thead>
					<tr>
					  <th>Username</th>
					  <th>Serial Number</th>
					  <th>Number of Rows</th>
					  <th>Estimated Cost</th>
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
			
			
			<div class="col-lg-5 col-xs-12">
      
          <div class="box box-primary">
            <div class="box-header with-border">
			
				<form id='column1' method="post" class="form-horizontal" role="form">
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
			
			<legend>Delete Records</legend>
			
			<span>This will delete all records before the specified date</span><br><br>
			
			<div class="form-group">
			  <label class="col-lg-3 control-label">Serial Number:<span class="req">*</span></label>
			  <div class="col-lg-7">
				<div class = "select-style" >
					<select id="serialno" name="serialno">
					
						<?php 
						if($is_present == 1)
						{
							$node_content = '<option value=""></option>';
							foreach($gatewayno as $key => $value)
							{
								$node_content .= 
								'<option value="'.$value.'">'.$value.'</option>';
							}	
						}
						else
							$node_content = '<option value=""></option>';
						
						echo $node_content;
						
						?>
						
					 </select>
				</div>
			  </div>
			</div> 
			
			<div class="form-group">
			  <label class="col-lg-3 control-label">Date:<span class="req">*</span></label>
			  <div class="col-lg-7">
				<div class = "select-style" >
					<select id="date" name="date">
					
						<?php 
						if($is_present == 1)
						{
							date_default_timezone_set('Asia/Singapore');
							$date_content = '<option value=""></option>';
							for( $i = 0; $i < 100; $i++ ) {
								
								$yesterday = date("Y-m-d", time() - (60 * 60 * 24 * $i));
								$date_content .= 
								'<option value="'.$yesterday.'">'.$yesterday.'</option>';
							}
										
						}
						else
							$date_content = '<option value=""></option>';
						
						echo $date_content;
						
						?>
						
					 </select>
				</div>
			  </div>
			</div> 
		 
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary" name="submitted" id="submitted" onClick="delete_data()">Delete Records</button>
              <span></span>
            </div>
			
          </div>
        </form>
			</div>
          </div>
        </div>
		
		
		<div class="col-lg-5 col-xs-12">
      
          <div class="box box-primary">
            <div class="box-header with-border">
			
				<form id='column1' method="post" class="form-horizontal" role="form">
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
			
			<legend>Delete Records</legend>
			
			<span>This will delete a range of rows in ascending order</span><br><br>
			
			<div class="form-group">
			  <label class="col-lg-3 control-label">Serial Number:<span class="req">*</span></label>
			  <div class="col-lg-7">
				<div class = "select-style" >
					<select id="serialno2" name="serialno2">
					
						<?php 
						if($is_present == 1)
						{
							$node_content = '<option value=""></option>';
							foreach($gatewayno as $key => $value)
							{
								$node_content .= 
								'<option value="'.$value.'">'.$value.'</option>';
							}	
						}
						else
							$node_content = '<option value=""></option>';
						
						echo $node_content;
						
						?>
						
					 </select>
				</div>
			  </div>
			</div> 
			
			<div class="form-group">
			  <label class="col-lg-3 control-label">Number of Rows:<span class="req">*</span></label>
			  <div class="col-lg-7">
				<div class = "select-style" >
					<select id="range" name="range">
					
						<?php 
						if($is_present == 1)
						{
							$range_content = '<option value=""></option>';
							for( $i = 1; $i < 50; $i++ ) {
								
								$range = 100 * $i;
								$range_content .= 
								'<option value="'.$range.'">'.$range.'</option>';
							}
										
						}
						else
							$range_content = '<option value=""></option>';
						
						echo $range_content;
						
						?>
						
					 </select>
				</div>
			  </div>
			</div> 
		 
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary" name="submitted" id="submitted" onClick="delete_data2()">Delete Records</button>
              <span></span>
            </div>
			
          </div>
        </form>
			</div>
          </div>
        </div>
			
			
			
		<?php 
		
			}
		?>
		
		
      </div>
      <!-- /.row -->
	  </section>
	  
	
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

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable();
  });
</script>


<script>

function delete_data()
{
	 var serialno = document.getElementById("serialno").value;
	 var date = document.getElementById("date").value;
	 
	 //alert(serialno);
		
	 $.ajax
	 ({
	  type:'post',
	  url:'utilities-ajax',
	  data:{
	   serialno:serialno,
	   date:date
	  },
	  success:function(response) {
		  
	   //console.log(response);
	   if(response=="success")
	   {
		location.reload();
	   }
	  }
	 });
}

</script>

<script>

function delete_data2()
{
	 var serialno = document.getElementById("serialno2").value;
	 var range = document.getElementById("range").value;
	 
	 //alert(serialno);
		
	 $.ajax
	 ({
	  type:'post',
	  url:'utilities-ajax',
	  data:{
	   serialno:serialno,
	   range:range
	  },
	  success:function(response) {
		  
	   //console.log(response);
	   if(response=="success")
	   {
		location.reload();
	   }
	  }
	 });
}

</script>

</body>
</html>

<?php

}

?>
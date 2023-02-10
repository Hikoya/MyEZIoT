<?php 

	require_once("../include/membersite_config.php");
	
	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("login");
		exit;
	}
	
	$username = $_SESSION['username_of_user'];
	
	$success = false;
	
	if(isset($_REQUEST['delete']))
	{
		if($fgmembersite->DeleteMonthRecord($username))
		{
			$success = true;
		}
	}

	if (isset($_POST['logout'])) {

		$fgmembersite->LogOut();
	}
	
	
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
				// Attempt select query execution
	$admin = $config['adminname'];
	
	$sql = "SELECT * FROM ".$config['tablename']." WHERE NOT username = '".$admin."' ";	
	$content = '';

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = $result->fetch_array(MYSQLI_BOTH))
			{
			
				$content .= '
				<tr id="row'.$row['id_user'].'">
				<td > '. $row['name'] . '</td>
				<td id="user'.$row['id_user'].'"> '. $row['username'] . '</td>
				<td> '. $row['email'] . '</td>
				<td> '. $row['mobileno'] . '</td>
				';
				
				if($row['approval'] == "approve")
				    $content .= '<td id="approve'.$row['id_user'].'"> Approved </td>' ;
				else
					$content .= '<td id="approve'.$row['id_user'].'"> Rejected </td>' ;
				
				$level = (int)$row['level'];
				if($level == 1)
				{
					$content .=
					'<td>
					<div class="select-style">
					<select id="level'.$row['id_user'].'" onChange="change_level('.$row['id_user'].');">
					<option selected value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					</select>
					</div>
					</td>';
				}
				else if($level == 2)
				{
					$content .=
					'<td>
					<div class="select-style">
					<select id="level'.$row['id_user'].'" onChange="change_level('.$row['id_user'].');">
					<option value="1">1</option>
					<option selected value="2">2</option>
					<option value="3">3</option>
					</select>
					</div>
					</td>';
				}
				else if($level == 3)
				{
					$content .=
					'<td>
					<div class="select-style">
					<select id="level'.$row['id_user'].'" onChange="change_level('.$row['id_user'].');">
					<option value="1">1</option>
					<option value="2">2</option>
					<option selected value="3">3</option>
					</select>
					</div>
					</td>';
				}
				
				$content .= '<td>
				<input type="button" class="edit_button" id="approval_button'.$row['id_user'].'" value="Approve" onclick="approval_row('.$row['id_user'].')">
				</td>
				<td>
				<input type="button" class="edit_button" id="disapproval_button'.$row['id_user'].'" value="Reject" onclick="disapproval_row('.$row['id_user'].')">
				</td>
				<td>
				<input type="button" class="edit_button" id="delete_button'.$row['id_user'].'" value="Delete" onclick="get_row('.$row['id_user'].')">
				</td>
				<td>
				';
				
				$content .= '
				</td>
				</tr>
				';

			}
			// Free result set
			mysqli_free_result($result);
						
		} else{
				echo "No gateway IDs registered in this account. ";
			}
	} else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link) ;
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
  <title><?php echo $sitehead ?> | Approval</title>
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
			<li class="active"><a href="approval"> <i class="fa fa-check-circle"></i> <span>Approval</span></a></li>
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
        <li class="active">Approval</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
	  <section>
      <div class="row">
	  
		<?php
		if($success){
			?>
			<section>
			<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-info"></i> Information</h4>
					SQL Records that are more than a month has been deleted.
				  </div>
			</div>
			</section>
			<?php
		}
		?>
	  
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Approval</h3>
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
				  <th>Mobile Number</th>
				  <th>Status</th>
				  <th>Level</th>
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
		
	
		<div class="modal fade" id="modal-column">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete User</h4>
              </div>
              <div class="modal-body">
                <p>Do you wish to delete this user?</p>
				<span>User: <span id="savecolumn"> </span> </span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id= "buttoncolumn2" class="btn btn-primary" data-dismiss="modal" onClick="delete_row()" >Save changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		
		
      </div>
      <!-- /.row -->
	  </section>
	  
	  <section>
	  <div class = "row">
	  <div class="col-lg-5 col-xs-12">
	  <div class="box box-primary">
        <div class="box-header with-border">
		<legend>Delete Last Month Records</legend>
		<div style="padding-top:10px"> </div>
			<form id='delete' class="form-horizontal" action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' role="form">
			 <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
			 <div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-3">
				  <button type="submit" class="btn btn-primary" style="width:200px" name="delete" id="delete">Delete SQL Records</button>
				  <span></span>
				</div>
			  </div>
			</form>
			</div>
          </div>
        </div>
	  </div>
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

function approval_row(id)
{
	 var name = document.getElementById("user"+id).innerText;

	 $.ajax
	 ({
	  type:'post',
	  url:'approval-ajax',
	  data:{
	   username:name,
	   approval:"approve"
	  },
	  success:function(response) {
	   if(response=="success")
	   {
		document.getElementById("approve"+id).innerHTML="Approved";
	   }
	  }
	 });
}

</script>

<script>

function disapproval_row(id)
{
 var name = document.getElementById("user"+id).innerText;
	
 $.ajax
 ({
  type:'post',
  url:'approval-ajax',
  data:{
   username:name,
   approval:"reject"
  },
  success:function(response) {
   if(response=="success")
   {
    document.getElementById("approve"+id).innerHTML = "Rejected";
   }
  }
 });
}

</script>

<script>

function change_level(id)
{
 var name = document.getElementById("user"+id).innerText;
 
 var e = document.getElementById("level"+id);
 var level = e.options[e.selectedIndex].value;
 
 $.ajax
 ({
  type:'post',
  url:'level-ajax',
  data:{
   username:name,
   level:level
  },
  success:function(response) {
   if(response=="success")
   {
    document.getElementById("level"+id).value = level;
   }
  }
 });
}

</script>

<script>
function get_row(id){
	
var name = document.getElementById("user"+id).innerText;

$('#modal-column').modal('show');
	
document.getElementById("savecolumn").innerHTML= name;
}

</script>

<script>

function delete_row()
{
 var name = document.getElementById("savecolumn").innerText;
	
 $.ajax
 ({
  type:'post',
  url:'delete-ajax',
  data:{
   username:name
  },
  success:function(response) {
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
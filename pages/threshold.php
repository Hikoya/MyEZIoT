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
	
	$username = $_SESSION['username_of_user'];
	$config = parse_ini_file('../private/config.ini'); 	   
	

	$sitehead = $config['sitehead'];
	$sitebot = $config['sitebot'];
	$sitesmall = $config['sitesmall'];
	
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
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title><?php echo $sitehead ?> | Threshold</title>
	
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/css/bootstrap-slider.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		
		<style>
  .select-style {
    border: 1px solid #ccc;
    width: 150px;
    border-radius: 3px;
    overflow: hidden;
    background: #fafafa url("data:image/png;base64,R0lGODlhDwAUAIABAAAAAP///yH5BAEAAAEALAAAAAAPABQAAAIXjI+py+0Po5wH2HsXzmw//lHiSJZmUAAAOw==") no-repeat 90% 50%;
}

.select-style select {
    padding: 5px 8px;
    width: 130%;
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
			
			<li class="active"><a href="threshold"> <i class="fa fa-tachometer"></i> <span>Threshold</span></a></li>
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
			
			<li class="active"><a href="threshold"> <i class="fa fa-tachometer"></i> <span>Threshold</span></a></li>
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
       <h1>Welcome, <?= $fgmembersite->UserFullUserName(); ?> </h1>
	  <h1 align="center"><small>Threshold Setter</small></h1>
	  
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Threshold</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Threshold</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding" >
			  <div style = "padding:10px">
              <table id="example1" class="table table-hover">
                <thead>
                <tr>
                  <th>Device ID</th>    
				  <th>Description</th>
				  <th>Column 1</th>
				  <th>Column 2</th>
				  <th>Column 3</th>
				  <th>Column 4</th>
				  <th>Column 1 Threshold</th>
				  <th>Column 2 Threshold</th>
				  <th>Column 3 Threshold</th>
				  <th>Column 4 Threshold</th>
                </tr>
                </thead>
                <tbody>
               
			   <?php
			    $is_present = 0;
			    $username = $_SESSION['username_of_user'];
				$config = parse_ini_file('../private/config.ini'); 	   
				$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
				// Attempt select query execution
				
				$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '$username' ";
				
	
				if($result = mysqli_query($link, $sql)){
					if(mysqli_num_rows($result) > 0){
						while($row = $result->fetch_array(MYSQLI_BOTH))
						{
							$is_present = 1;
							
							$gatewayno = $row['gatewayno'];
							$nodes[] = $gatewayno;
							
							echo "<tr>";
							echo "<td>" . $row['gatewayno'] . "</td>";
							echo "<td>" . $row['description'] . "</td>";
							
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
							
							if(empty($row['threshold1']))
								echo "<td>" . "No Threshold" . "</td>";
							else
								echo "<td>" . $row['threshold1'] . "</td>";
							
							if(empty($row['threshold2']))
								echo "<td>" . "No Threshold" . "</td>";
							else
								echo "<td>" . $row['threshold2'] . "</td>";
							
							if(empty($row['threshold3']))
								echo "<td>" . "No Threshold" . "</td>";
							else
								echo "<td>" . $row['threshold3'] . "</td>";
							
							if(empty($row['threshold4']))
								echo "<td>" . "No Threshold" . "</td>";
							else
								echo "<td>" . $row['threshold4'] . "</td>";
							
							echo "</tr>";
						}
						// Free result set
						mysqli_free_result($result);
					} 
				}	 		   
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

      <div class="row">
        <div id="column1div" class="col-xs-12 col-md-5">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Threshold</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row margin">
                <div class="col-sm-6">
				
					<form id='column1'  class="form-horizontal" role="form">
					<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
						
						
					<div class="form-group">
					  <label class="col-lg-6 control-label" style="text-align: left;">Device ID:<span class="req">*</span></label>
					  <div class="col-lg-8">
						<div class="select-style" >
							<select id="serialno" name="serialno" onChange="populate();">
							
								<?php 
								if($is_present == 1)
								{
									$node_content = '<option value=""></option>';
									foreach($nodes as $key => $value)
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
						<label class="col-lg-6 control-label" style="text-align: left;">Column:</label>
						<div class="col-lg-8">
						<div class="select-style" >
							<select id="column" name="column" onChange="populate();">
							
								<?php 
								
								$node_content1 .= '<option value=""></option>';
								$node_content1 .= '<option value="column1">Column 1</option>';
								$node_content1 .= '<option value="column2">Column 2</option>';
								$node_content1 .= '<option value="column3">Column 3</option>';
								$node_content1 .= '<option value="column4">Column 4</option>';
								
				
								echo $node_content1;
								
								?>
								
							 </select>
						</div>
						</div>
					</div> 
					
						<div class="form-group">
						<label class="col-lg-6 control-label" style="text-align: left;">Threshold:</label>
						<div class="col-lg-11">
						  <input class="form-control" name="threshold" id="threshold" type="text">
						</div>
						</div>
						
					</form>

					<div style="padding-top:20px;">
				    <button type="button" id="column1" name="column1" class="btn btn-app"  onClick="getValue1()"><i class="fa fa-save"></i>
					Save
					</button>
					</div>
  
                </div>
				
               
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		
      </div>
	   <!-- /.row -->
	  
	  
	  <div class="modal fade" id="modal-column1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Column Threshold</h4>
              </div>
              <div class="modal-body">
                <p>Do you wish to save your new threshold?</p>
				<span>New threshold: <span id="savecolumn1"> </span></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="buttoncolumn1" class="btn btn-primary" data-dismiss="modal" onClick="column1()" >Save changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		
		
    </section>
    <!-- /.content -->
  </div>
 
</div>
<!-- ./wrapper -->

<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Bootstrap slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/bootstrap-slider.js"></script>

<script>
function populate(){
	
	var serialno = document.getElementById('serialno').value;
	var column = document.getElementById('column').value;
	
	if((serialno != "") && (column != ""))
	{
		$.ajax({
		  url: 'populate',
		  type: 'POST',
		  data: {serialno : serialno , column : column},
		  success: function(data) {
			 var response_array = JSON.parse(data);
			 $('#threshold').val(response_array['threshold']);  
		  }
		}); // end ajax call
	}
	
	
}
</script>

<script>
function getValue1(){
	
var x = document.getElementById("serialno").value;
var y = document.getElementById("threshold").value;

if (x == "") 
{
	alert("Serial number must be filled out");
	$('#modal-column1').modal('hide');
}
else
{
	
	$('#modal-column1').modal('show');
	
}

document.getElementById("savecolumn1").innerHTML= y;
}

</script>



<script>
function column1(){
	
var threshold = document.getElementById("threshold").value;
var serialno = document.getElementById("serialno").value;
var column = document.getElementById("column").value;

if (serialno == "") 
{
    alert("Serial number must be filled out");  
}
else if(column == "")
{
	alert("column must be filled out");  
}
else
{
	$.ajax({type: "POST",
            url: "update-threshold-ajax",
            data: { column: column , id : serialno , threshold : threshold},
            success:function(result){
			  location.reload();
            },
           error: function(xhr, status, error) {
			  alert(xhr.responseText);
			}
       });
}
	
}
</script>

<script>
  $(function () {
    $("#example1").DataTable();
  });
</script>


</body>
</html>

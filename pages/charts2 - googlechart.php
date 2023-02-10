<?php 

	require_once("../include/membersite_config.php");

	if(!$fgmembersite->CheckLogin())
	{
		$fgmembersite->RedirectToURL("login.php");
		exit;
	}

	if (isset($_POST['logout'])) {

		$fgmembersite->LogOut();
	}
	
	$username = $_SESSION['username_of_user'];
	
	$config = parse_ini_file('../private/config.ini'); 	   
	$connect = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
	if($username == $config['adminname'])
		$tab_query = "SELECT * FROM ".$config['tablenamenode']." ORDER BY id ASC";
	else
		$tab_query = "SELECT * FROM ".$config['tablenamenode']." WHERE username = '".$username."' ORDER BY id ASC";

	$tab_result = mysqli_query($connect, $tab_query);
	$tab_menu = '';
	$tab_content = '';
	
	$is_present = 0;
	
	if(mysqli_num_rows($tab_result) > 0)
	{
		
		$i = 0;
		$is_present = 1;
		while($row = mysqli_fetch_array($tab_result))
		{
			if($i == 0)
			{
				$tab_menu .= '
				<li class="active"><a href="#'.$row["gatewayno"].'" data-toggle="tab">'.$row["description"].'</a></li>
				';
				
				$tab_content .= '
				<div role="tabpanel" id="'.$row["gatewayno"].'" class="tab-pane fade in active">
				<div class="row">
					<div class="col-md-12">
						<div id="'.$row['gatewayno'].'" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
					</div>
				</div>
				</div>
				';
				
				$gatewayno[] = $row["gatewayno"];	
	  
			}
			else
			{
				$tab_menu .= '
				<li><a href="#'.$row['gatewayno'].'" data-toggle="tab">'.$row["description"].'</a></li>
				';
					
				$tab_content .= '<div role="tabpanel" class="tab-pane" id="'.$row['gatewayno'].'">
				<div class="row">
					<div class="col-md-12">
						<div id="'.$row['gatewayno'].'" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
					</div>
				</div>
				</div>
				';
				
				$gatewayno[] = $row["gatewayno"];	
	 
			}
			
			$i++;
		}
	}
	else
	{
			$tab_menu .= '
				<li><a href="#default" data-toggle="tab">No devices found</a></li>
				';
					
			$tab_content .= '
				<div role="tabpanel" id="default" class="tab-pane fade in active">
				<div class="row">
					<div class="col-md-12">
						<p style="padding:50px"> No devices registered in this account </p>
					</div>
				</div>
				</div>
				';
	}
	
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SP IoT Gateway | Charts</title>
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
  
  <link rel="stylesheet" type="text/css" href="https://www.highcharts.com/media/com_demo/css/highslide.css" />
  

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
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>L</b>F</span>
      <!-- logo for regular state and mobile devices -->
      <p class="logo-lg" style="font-size:15px">SP IoT Gateway <span style="font-size:9px">by LoRa and Friends</p>
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
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
             <!--   <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li> !-->
              <li class="footer"><a href="#">View all</a></li>
            </ul>
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
                  <a href="logout.php" id='logout' class="btn btn-default btn-flat">Sign out</a>
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
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
       <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
          <a href="index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="active"><a href="charts.php"><i class="fa fa-pie-chart"></i> <span>Charts</span></a></li>
		 
		<li ><a href= "devices.php" > <i class="fa fa-plug"></i> <span>Devices</span></a></li>
		<li ><a href="users.php"> <i class="fa fa-user"></i> <span>Users</span></a></li>
		<li ><a href="threshold.php"> <i class="fa fa-tachometer"></i> <span>Threshold</span></a></li>
		<li ><a href="registernode.php"> <i class="fa fa-desktop"></i> <span>Register Device</span></a></li>
		<li ><a href="camera.php"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
		 
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
  
        <!--<span>Welcome, <?= $fgmembersite->UserFullUserName(); ?> <span style="padding-left:330px"><small>Environment</small></span></span>-->
	  <h1>Welcome, <?= $fgmembersite->UserFullUserName(); ?> </h1>
	  <h1 align="center"><small>Environment</small></h1>
 
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Charts</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- AREA CHART -->
		  
		  <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul id="tabs" class="nav nav-tabs pull-right">
              <!--<li class="active"><a href="#morris-area-chart" data-toggle="tab">Donut</a></li>-->
              <li class="pull-left header"><i class="fa fa-inbox"></i> Sensor Reading</li>
			  
			  <?php
				echo $tab_menu;
			  ?>
				
            </ul>
			
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales --> 
			  <!--<div class="chart tab-pane active" id="morris-area-chart" style="position: relative; height: 300px; padding: 30px;" ></div> -->
			  
			  <?php
				echo $tab_content;
			   ?>
			  
            </div>
			
          </div>
		
            <!-- /.box-body -->
        </div>
          <!-- /.box -->  
        </div>
     
        <!-- /.col (RIGHT) -->
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
<!-- ChartJS 1.0.1 -->
<script src="../plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<!-- Additional files for the Highslide popup effect -->
<script src="https://www.highcharts.com/media/com_demo/js/highslide-full.min.js"></script>
<script src="https://www.highcharts.com/media/com_demo/js/highslide.config.js" charset="utf-8"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script>
function monitoring(){
 
$.ajax({
      url: 'monitoring.php',
      success: function(data) {
     
	  }
    }); // end ajax call
	
}

</script>


<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
                    monitoring();
                    // Set interval to call the drawChart again
                    setInterval(monitoring, 5000);
                    });
</script>

<?php

if($is_present == 1)
{
	
	foreach($gatewayno as $key => $value)
	{
		
		
?> 
	<script>
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(<?php echo $value ?>);
	
	function <?php echo $value?>() {
	
		var jsonData = $.ajax({
				url: 'charts-ajax2.php',
				type: 'POST',
				async: true,
				dataType: "json",
				data: {id:'<?php echo $value?>'},
				success: function(jsonData)
					{
					
						 // Draw a column chart
						var wrapper = new google.visualization.ChartWrapper({
						options : {tooltip: {isHtml: true}  },
						containerId: '<?php echo $value ?>'
						});
						
						var data = new google.visualization.DataTable(jsonData);
						
						wrapper.setChartType('AreaChart');
						wrapper.setDataTable(data);
					
						wrapper.draw();
						
					}	
				}).responseText;
	}
	
	</script>
	
	<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
                    <?php echo $value?>();
                    // Set interval to call the drawChart again
                    setInterval(<?php echo $value?>, 5000);
                    });
	</script>

	
	<?php
		

	}
}
	?>



</body>
</html>

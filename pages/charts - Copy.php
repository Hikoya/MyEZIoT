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
	$connect = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
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
					<div class="col-md-12 col-xs-12">
						<div id="'.$row['gatewayno'].'" ></div>
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
					<div class="col-md-12 col-xs-12">
						<div id="'.$row['gatewayno'].'" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
	

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];
$sitesmall = $config['sitesmall'];

$sql = "SELECT * FROM ".$config['tablename']." where username = '$username' ";
if($result = mysqli_query($connect, $sql)){
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
  <title><?php echo $sitehead?> | Charts</title>
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
 
 <style>
  .select-style {
    border: 1px solid #ccc;
    width: 120px;
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
			<li class="active"><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
			
			<?php
			if(!empty($cameraurl))
			{
			?>
			<li ><a href="camera"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
			<?php
			}
			?>
		
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
			<li class="active"><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
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
			<li class="active"><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
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
  
        <!--<span>Welcome, <?= $fgmembersite->UserFullUserName(); ?> <span style="padding-left:330px"><small>Environment</small></span></span>-->
	  <h1>Welcome, <?= $fgmembersite->UserFullUserName(); ?> </h1>
	  <h1 align="center"><small>Environment</small></h1>
 
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Analytics</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
		<div class="col-md-2 col-xs-6" style="margin-bottom:10px">
			<div class="select-style">
			<select id="chartType" onChange="changeType()"  >
				<option value="area">Area Chart</option>
				<option value="column">Column Chart</option>
				<option value="line">Line Chart</option>
				<option value="bar">Bar Chart</option>	
				<option value="waterfall">Waterfall Chart</option>
				<option value="bubble">Bubble Chart</option>
				<option value="scatter">Scatter Chart</option>
			</select>
			</div>
		</div>  
		
		<div class="col-md-2 col-xs-6" style="margin-bottom:10px">	
			<div class="select-style">
			<select id="intervalType" onChange="changeType()" >
				<option value="10">10</option>	
				<option value="50">50</option>	
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="300">300</option>
				<option value="400">400</option>
				<option value="500">500</option>
				<option value="600">600</option>
				<option value="700">700</option>
				<option value="800">800</option>
				<option value="900">900</option>
				<option value="1000">1000</option>
				<option value="1100">1100</option>
				<option value="1200">1200</option>
				<option value="1300">1300</option>
				<option value="1400">1400</option>
				<option value="1500">1500</option>
				<option value="1600">1600</option>
				<option value="1700">1700</option>
				<option value="1800">1800</option>
				<option value="1900">1900</option>
				<option value="2000">2000</option>
				<option value="3000">3000</option>
				<option value="4000">4000</option>
				<option value="5000">5000</option>
				<option value="6000">6000</option>
				<option value="7000">7000</option>
				<option value="8000">8000</option>
				<option value="9000">9000</option>
				<option value="10000">10000</option>
				<option value="20000">20000</option>
				<option value="30000">30000</option>
				<option value="40000">40000</option>			
			</select>
				</div>
		</div>  
	  </div>
		
		<div class="row">
		<div class="col-md-12 col-xs-12">
          <!-- AREA CHART -->
		  <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul id="tabs" class="nav nav-tabs pull-right">
              <!--<li class="active"><a href="#morris-area-chart" data-toggle="tab">Donut</a></li>-->
              <li class="pull-left header"><i class="fa fa-inbox"></i>Analytics</li>
			  
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
<script src="https://code.highcharts.com/highcharts-more.js"></script>

<!-- Additional files for the Highslide popup effect -->
<script src="https://www.highcharts.com/media/com_demo/js/highslide-full.min.js"></script>
<script src="https://www.highcharts.com/media/com_demo/js/highslide.config.js" charset="utf-8"></script>


<?php

if($is_present == 1)
{
	
	
	?>
	<script>
	
	function changeType(){
		
		<?php 
		foreach($gatewayno as $key => $value)
		{
			echo nl2br($value.'()'.';');
		}
		?>
		
	}
	
	</script>
	
	<?php
	foreach($gatewayno as $key => $value)
	{
		
?> 

	<script type="text/javascript">

	function <?php echo $value ?>() {
	
	var chartType = document.getElementById('chartType').value;
	var intervalType = document.getElementById('intervalType').value;
	
	var options =  {
		chart: {
			renderTo: '<?php echo $value?>',
			type: chartType
		},
		title: {
            text: []
        },
		xAxis: {
			allowDecimals: false,
			reversed: true,
			labels: {
				formatter: function () {
					return this.value; // clean, unformatted number for year
				}
			}
		},
		yAxis: {
			title: {
				text: 'Environment'
			}
		},
		series: [{
					name: [],
					data: []
				},{
					name: [],
					data: []
				},{
					name: [],
					data: []
				},{
					name: [],
					data: []
				}]
		}
		
	 $.ajax({
		url: 'charts-ajax',
		type: 'POST',
		async: true,
		dataType: "json",
		data: {id:'<?php echo $value?>' , interval:intervalType},
		success: function (data) {
			
			if(data != "")
			{
				var div = document.getElementById('<?php echo $value?>');
				div.style.minWidth="310px"; 
				div.style.height="400px"; 
				div.style.margin="0 auto";
				
				options.xAxis.categories = data.timestamp;
				options.series[0].data = data.column1;
				options.series[1].data = data.column2;
				options.series[2].data = data.column3;
				options.series[3].data = data.column4;
				options.series[0].name = data.column1name;
				options.series[1].name = data.column2name;
				options.series[2].name = data.column3name;
				options.series[3].name = data.column4name;
				options.series[0].color = '#396ab1';
				options.series[1].color = '#ab6857';
				options.title.text = data.location;
				chart = new Highcharts.Chart(options);
				chart.reflow();
			}
			else
			{
				var div = document.getElementById('<?php echo $value?>');
				div.style.minWidth="310px";
				div.style.height="60px";
				div.style.textAlign="center";
				div.innerText = "No data recorded";
			}
			
		}
	  });
	  
	  //data = [{name: "humidity", data:[65.0,65.0,65.0,65.0,65.0,65.0]},{name:"temperature", data:[51.0,51.0,51.0,51.0,51.0,51.0]}];
	  //visitorData(data);
	 }


	</script>
	
	<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
                    <?php echo $value?>();
                    // Set interval to call the drawChart again
                    setInterval(<?php echo $value?>, 60000);
                    });
	</script>

	<?php
		

	}
}
	?>


</body>
</html>

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
	
	$user_ip = getenv('REMOTE_ADDR');
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
	//$country = $geo["geoplugin_countryName"];
	//$countrycode = $geo["geoplugin_countryCode"];
		
	$country = "Singapore";
	$countrycode = "SG";
	
	$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
	
	$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$country.', '.$countrycode.'")';
	$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query). "&format=json";

	$session = curl_init($yql_query_url);
	curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	$json = curl_exec($session);
	$phpObj = json_decode($json);
	
	$temperature = '';
	$temp = '';
	$weather = '';
	$humidity = '';
	
	$temperature = $phpObj->query->results->channel->item->condition->temp;
	$temp = round((5/9 *($temperature - 32)),2);
	
	$weather = $phpObj->query->results->channel->item->condition->text;
	
	$humidity = $phpObj->query->results->channel->atmosphere->humidity;
	
	$config = parse_ini_file("../private/config.ini");
    $sitehead = $config['sitehead'];
    $sitebot = $config['sitebot'];
	$sitesmall = $config['sitesmall'];
	
	$is_present = 0; 
	$is_present2 = 0; 
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$sql = "SELECT * FROM ".$config['tablenameswitch']." WHERE username = '".$username."' ";
	$query = mysqli_query($link,$sql);
						
	$tab_content = '';
	if(mysqli_num_rows($query) > 0)
	{	
		$is_present = 1;
		while($result = mysqli_fetch_array($query))
		{
			$gatewayno = $result['gatewayno'];
			$switch[] = $gatewayno;
								
			$sql2 = "SELECT * FROM ".$config['tablenameswitch']." WHERE gatewayno = '".$gatewayno."' ";
			$query2 = mysqli_query($link, $sql2);
			if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
			{
				$description = $result2['description'];
				$threshold = $result2['threshold'];
			}
			
			if(!empty($threshold))
			{
				$tab_content .=  '
				<div class="row" style="margin-left:20px">
				<div class="col-xs-5">
				<i class="fa fa-bolt " style="margin-right: 5px"></i>'.$description.
				'<br><span style="font-size:11px">Threshold: '.$threshold.'</span></div>
				<div class="col-xs-3">
				<div href="" class="cube-switch" id="'.$gatewayno.'" onclick="'.$gatewayno.'()">
				<span class="switch">
				<span class="switch-state off">Off</span>
				<span class="switch-state on">On</span>
				</span>
				</div>
				</div>
				</div>';
			}
			else
			{
				$tab_content .=  '
				<div class="row" style="margin-left:20px">
				<div class="col-xs-5">
				<i class="fa fa-bolt "  style="margin-right: 5px"></i>'.$description.
				'</div>
				<div class="col-xs-3">
				<div href="" class="cube-switch" id="'.$gatewayno.'" onclick="'.$gatewayno.'()">
				<span class="switch">
				<span class="switch-state off">Off</span>
				<span class="switch-state on">On</span>
				</span>
				</div>
				</div>
				</div>';
			}
								
		}
	}
	
	$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE username = '".$username."' ";
	$query2 = mysqli_query($link,$sql2);
	if(mysqli_num_rows($query2) > 0)
	{	
		$is_present2 = 1;
		while($result2 = mysqli_fetch_array($query2))
		{
			$gatewayno = $result2['gatewayno'];
			$switch2[] = $gatewayno;									
		}
	}
	
	$is_column1 = 0;
	$is_column2 = 0;
	$is_column3 = 0;
	$is_column4 = 0;
	$is_gps = 0;
	$is_youtube = 0;
	
	$sql3 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column1 IS NOT NULL AND username = '".$username."' ";
	$query3 = mysqli_query($link,$sql3);
	if(mysqli_num_rows($query3) > 0)
	{
		$is_column1 = 1;
	}
	
	$sql4 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column2 IS NOT NULL AND username = '".$username."' ";
	$query4 = mysqli_query($link,$sql4);
	if(mysqli_num_rows($query4) > 0)
	{
		$is_column2 = 1;
	}
	
	$sql5 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column3 IS NOT NULL AND username = '".$username."' ";
	$query5 = mysqli_query($link,$sql5);
	if(mysqli_num_rows($query5) > 0)
	{
		$is_column3 = 1;
	}
	
	$sql6 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column4 IS NOT NULL AND username = '".$username."' ";
	$query6 = mysqli_query($link,$sql6);
	if(mysqli_num_rows($query6) > 0)
	{
		$is_column4 = 1;
	}
	
	
	$sql8 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column5 IS NOT NULL AND username = '".$username."' ";
	$query8 = mysqli_query($link,$sql8);
	if(mysqli_num_rows($query8) > 0)
	{
		$is_gps = 1;
	}
	

	$sql9 = "SELECT * FROM ".$config['tablenamenode']. " WHERE column6 IS NOT NULL AND username = '".$username."' ";
	$query9 = mysqli_query($link,$sql9);
	if(mysqli_num_rows($query9) > 0)
	{
		$is_youtube = 1;
		
		while($result9 = mysqli_fetch_array($query9))
		{
			$gatewayno = $result9['gatewayno'];
			$y_link = $result9['column6'];
			parse_str(parse_url($y_link, PHP_URL_QUERY), $variables);
			$y_link = $variables['v'];
			
			$switch2[] = $gatewayno;		
			$youtube_link[] = $y_link;
		}
	}
	
	
	$column1 = '';
	$column2 = '';
	$column3 = '';
	$column4 = '';
	
	$column5 = '';
	$column6 = '';
	
	$sql7 = "SELECT * FROM ".$config['tablename']. " WHERE username = '".$username."' ";
	$query7 = mysqli_query($link,$sql7);
	if(mysqli_num_rows($query7) > 0)
	{
		if($result7 = mysqli_fetch_array($query7))
		{
			$level = (int)$result7['level'];
			if(!empty($result7['cameraurl']))
				$cameraurl = $result7['cameraurl'];
			else
				$cameraurl = '';
			
			$level = $result7['level'];
			
			if(!empty($result7['column1']))
				$column1 = $result7['column1'];	
			else
				$column1 = "Sensor Reading 1";
			
			if(!empty($result7['column2']))
				$column2 = $result7['column2'];
			else
				$column2 = "Sensor Reading 2";
		
			if(!empty($result7['column3']))
				$column3 = $result7['column3'];
			else
				$column3 = "Sensor Reading 3";
		
			if(!empty($result7['column4']))
				$column4 = $result7['column4'];	
			else
				$column4 = "Sensor Reading 4";		

			if(!empty($result7['column5']))
				$column5 = $result7['column5'];	
			else
				$column5 = "GPS Coordinates";		
			
			if(!empty($result7['column6']))
				$column6 = $result7['column6'];	
			else
				$column6 = "Youtube";		
		}
	}
	
						
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $sitehead?> | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  
  <script src="https://use.fontawesome.com/cd59f17108.js"></script>
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
  
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  
 <style>

	.chat_box {
	position:fixed;
	right:10px;
	bottom:0px;
	width:370px;
	}
	
	.chat_body{
	background:white;
	height:400px;
	}

	.chat_head,.msg_head{
		background:#3a96dd;
		color:white;
		font-weight:bold;
		cursor:pointer;
	}
	
	table.dataTable .thead td{
		solid: #3a96dd;
	}
</style>

<style>


/* SWITCH */
.cube-switch {
    border-radius:10px;
    border:1px solid rgba(0,0,0,0.4);
    box-shadow: 0 0 8px rgba(0,0,0,0.6), inset 0 100px 50px rgba(255,255,255,0.1);
    /* Prevents clics on the back */
    cursor:default;    
    display: none;
    height: 75px;
    position: relative;
    overflow:hidden;
    /* Prevents clics on the back */
    pointer-events:none;
    width: 75px;
    white-space: nowrap;
    background:#333;
	margin: 15px;
}

/* The switch */
.cube-switch .switch {
    border:1px solid rgba(0,0,0,0.6);
    border-radius:0.7em;
    box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 -7px 0 rgba(0,0,0,0.2),
    inset 0 50px 10px rgba(0,0,0,0.2),
    0 1px 0 rgba(255,255,255,0.2);
    display:block;
    width: 60px;
    height: 60px;
    margin-left:-30px;
    margin-top:-30px;
    position:absolute;
    top: 50%;
    left: 50%;
    width: 60px;
 
    background:#666;
    transition: all 0.2s ease-out;

    /* Allows click */
    cursor:pointer;
    pointer-events:auto;
}

/* SWITCH Active State */
.cube-switch.active {
    /*background:#222;
    box-shadow:
    0 0 5px rgba(0,0,0,0.5),
    inset 0 50px 50px rgba(55,55,55,0.1);*/
}

.cube-switch.active .switch {
    background:#333;
    box-shadow:
    inset 0 6px 0 rgba(255,255,255,0.1),
    inset 0 7px 0 rgba(0,0,0,0.2),
    inset 0 -50px 10px rgba(0,0,0,0.1),
    0 1px 0 rgba(205,205,205,0.1);
}

.cube-switch.active:after,
.cube-switch.active:before {
    background:#333; 
    box-shadow:
    0 1px 0 rgba(255,255,255,0.1),
    inset 1px 2px 1px rgba(0,0,0,0.5),
    inset 3px 6px 2px rgba(200,200,200,0.1),
    inset -1px -2px 1px rgba(0,0,0,0.3);
}

.cube-switch.active .switch:after,
.cube-switch.active .switch:before {
    background:#222;
    border:none;
    margin-top:0;
    height:1px;
}

.cube-switch .switch-state {
    display: block;
    position: absolute;
    left: 40%;
    color: #FFF;

    font-size: .5em;
    text-align: center;
}

/* SWITCH On State */
.cube-switch .switch-state.on {
    bottom: 15%;
}

/* SWITCH Off State */
.cube-switch .switch-state.off {
    top: 15%;
}

</style>

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
			<li class="active">
			  <a href="index">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			  </a>
			</li>
			<li><a href="charts"><i class="fa fa-pie-chart"></i> <span>Analytics</span></a></li>
			
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
			<li class="active">
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
			<li class="active">
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
	  <h1 align="center"><small>Control Panel</small></h1>
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      
	  <div class="row">
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $temp; ?><sup style="font-size: 20px"> °C</sup></h3>

              <p>Current Temperature (<?php echo $country ?>) <br> Yahoo API</p>
            </div>
            <div class="icon">
              <i class="fa fa-thermometer-empty"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $humidity; ?><sup style="font-size: 20px">%</sup></h3>

              <p>Current Humidity   (<?php echo $country ?>) <br> Yahoo API</p>
            </div>
            <div class="icon">
              <i class="fa fa-tint"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
		
		<div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 style="font-size: 20px"><?php echo $weather; ?></h3>

              <p style="padding-top:18px">Current Weather  (<?php echo $country ?>) <br> Yahoo API</p>
            </div>
            <div class="icon">
              <i class="fa fa-sun-o"></i>
            </div>
          </div>
        </div>
		
		</div>
	  
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 col-xs-12 connectedSortable">
          
		  <?php
			if($is_column1 == 1)
			{
		  ?>

          <div class="box box-default">
            <div class="box-header">
			  <h3 class="box-title"><?php echo $column1 ?></h3>
              <div class="box-tools pull-right">

                 <button type="button" class="btn btn-box-tool" data-widget="collapse"  ><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            
              </div>
            </div>
            <div class="box-body">
			
			 <div class = "row">
		  
		  <div class="col-md-4 col-xs-6">
			<div class="select-style" >
			<select id="chartType" onChange="column_chart();"  >
			 <option value="ColumnChart">Column Chart</option>
			 <option value="BarChart">Bar Chart</option>
			 <option value="AreaChart">Area Chart</option>
			 <option value="Table">Table Chart</option>
			 <option value="Gauge">Gauge</option>
			 </select>
			</div>
		  </div> 
		  
		  <div class="col-md-4 col-xs-6">
			<div class="select-style" >
			<select id="numType" onChange="column_chart();" >
			 <option value="1">1</option>
			 <option value="2">2</option>
			 <option selected="selected" value="5">5</option>
			 <option value="10">10</option>
			 <option value="15">15</option>
			 </select>
			 </div>
		  </div>  
		  
		  </div>
		  
		  <div style="margin-top:10px"></div>
		  
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div id="columnchart_values"></div>       
                </div>
              </div>
            </div>
          </div>
        
		<?php
		
		}
		
		?>
		
		<?php
			if($is_column3 == 1)
			{
			?>
			
		
			
			<div class="box box-default">
            <div class="box-header">
			  <h3 class="box-title"><?php echo $column3 ?></h3>
              <div class="box-tools pull-right">

                 <button type="button" class="btn btn-box-tool" data-widget="collapse"  ><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            
              </div>
            </div>
            <div class="box-body">
			
				<div class = "row">
		
			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="chartType3" onChange="column_chart3();"  >
				 <option value="ColumnChart">Column Chart</option>
				 <option value="BarChart">Bar Chart</option>
				 <option value="AreaChart">Area Chart</option>
				 <option value="Table">Table Chart</option>
				 <option value="Gauge">Gauge</option>
				 </select>
				</div>
			</div> 

			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="numType3" onChange="column_chart3();" >
				 <option value="1">1</option>
				 <option value="2">2</option>
				 <option selected="selected" value="5">5</option>
				 <option value="10">10</option>
				 <option value="15">15</option>
				 </select>
				 </div>
			</div>  
				
			</div>
			
			<div style="margin-top:10px"></div>
			
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div id="columnchart_values3"></div>       
                </div>
              </div>
            </div>
            </div>
			
			<?php
		
			}
			
			?>
		
		
		</section>
      
        <section class="col-lg-6 col-xs-12 connectedSortable">
			
			<?php
			if($is_column2 == 1)
			{
			?>
		
		
			
		    <div class="box box-default">
            <div class="box-header">
			  <h3 class="box-title"><?php echo $column2 ?></h3>
              <div class="box-tools pull-right">

                 <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            
              </div>
            </div>
            <div class="box-body">
			
				<div class = "row">
			
			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="chartType2" onChange="column_chart2();"  >
				 <option value="ColumnChart">Column Chart</option>
				 <option value="BarChart">Bar Chart</option>
				 <option value="AreaChart">Area Chart</option>
				 <option value="Table">Table Chart</option>
				 <option value="Gauge">Gauge</option>
				 </select>
				</div>
			</div> 

			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="numType2" onChange="column_chart2();" >
				 <option value="1">1</option>
				 <option value="2">2</option>
				 <option selected="selected" value="5">5</option>
				 <option value="10">10</option>
				 <option value="15">15</option>
				 </select>
				 </div>
			</div>  
				
			</div>
			
			<div style="margin-top:10px"></div>
			
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div id="columnchart_values2"></div>       
                </div>
              </div>
            </div>
            </div>
      
			<?php
		
			}
			
			?>
			
			
			<?php
			if($is_column4 == 1)
			{
			?>
			
			
			
		    <div class="box box-default">
            <div class="box-header">
			  <h3 class="box-title"><?php echo $column4 ?></h3>
              <div class="box-tools pull-right">

                 <button type="button" class="btn btn-box-tool" data-widget="collapse"  ><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            
              </div>
            </div>
            <div class="box-body">
			
			<div class = "row">
		
			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="chartType4" onChange="column_chart4();"  >
				 <option value="ColumnChart">Column Chart</option>
				 <option value="BarChart">Bar Chart</option>
				 <option value="AreaChart">Area Chart</option>
				 <option value="Table">Table Chart</option>
				 <option value="Gauge">Gauge</option>
				 </select>
				</div>
			</div> 

			<div class="col-md-4 col-xs-6">
				<div class="select-style" >
				<select id="numType4" onChange="column_chart4();" >
				 <option value="1">1</option>
				 <option value="2">2</option>
				 <option selected="selected" value="5">5</option>
				 <option value="10">10</option>
				 <option value="15">15</option>
				 </select>
				 </div>
			</div>  
				
			</div>
			
			<div style="margin-top:10px"></div>
			
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div id="columnchart_values4"></div>       
                </div>
              </div>
            </div>
            </div>
			
			<?php
		
			}
			
			?>
			
        </section>
      </div>
      <!-- /.row (main row) -->
	  
	  
	  <div class="row">
		<?php
		if($is_gps == 1)
		{
		?>
         
	  
        <!-- Left col -->
        <section class="col-lg-6 col-xs-12 connectedSortable">
          
		  <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $column5 ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div id="columnchart_values5"></div>       
                </div>
              </div>
            </div>
          </div>

		  
        </section>
		
		<?php
		
		}
		
		?>
		
		<?php
		if($is_youtube == 1)
		{
		?>
       
        <section class="col-lg-6 col-xs-12 connectedSortable">
	   
	   <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $column6 ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12 col-xs-12">
				
				<?php
					
					$num = count($youtube_link);
					$height = 400/$num;
					
					foreach($youtube_link as $key => $value)
					{
						$url = "https://www.youtube.com/embed/" . $value;
						
				?>
						<iframe src=<?php echo $url ?>
						width="560" height="<?php echo $height?>" frameborder="0" allowfullscreen></iframe>
				<?php
					}
				?>
            
				</div>
              </div>
            </div>
        </div>
		
        </section>
		
		<?php
		
		}
		
		?>
		
    </div>
	
	
	 <div class = "row">
	  
	  	<section class="col-lg-7 col-xs-12 connectedSortable">
		 
			<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-snowflake-o"></i> Nodes Registered</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <div class="row">
            	 <div style="padding:20px">
				 <table id="dynatable" class="table table-hover">
				 <thead>
					<tr>
					<th>Serial Number</th>
					<th>Description</th>
					<th>Location</th>
					<th>Column 1 </th>
					<th>Column 2 </th>
					<th>Column 3 </th>
					<th>Column 4 </th>
					</tr>
				</thead>
				</table>	
                </div>
              </div>
            </div>
			</div>
			
			<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-signal"></i> Switches Registered</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <div class="row">
                <div class="col-md-12 col-xs-12">
				<div style="padding:20px">
				 <table id="dynatable2" class="table table-hover">
				 <thead>
					<tr>
					<th>Serial Number</th>
					<th>Description</th>
					</tr>
				</thead>
				</table>	
				</div>   
				</div>
                </div>
              </div>
            </div>
   
		</section>
		
		<?php
		
		if($is_present == 1)
		{
		?>
		<section class="col-lg-5 col-xs-12 connectedSortable">
		
		<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-cog"></i> Switch Threshold</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-4 col-xs-4" >
				    Switches
					<div class="select-style" style="width:90px" >
					<select id="switchType">
					
						<?php 
						if($is_present == 1)
						{
							$switch_content = '<option value=""></option>';
							foreach($switch as $key => $value)
							{
								$switch_content .= 
								'<option value="'.$value.'">'.$value.'</option>';
							}
							
							echo $switch_content;
						}
						?>
						
					 </select>
					</div>
                </div>
				
				<div class="col-md-4 col-xs-4" >
				    Sensor Nodes
					<div class="select-style" style="width:90px" >
						<select id="nodeType">
							
							<?php 
							if($is_present2 == 1)
							{
								$node_content = '<option value=""></option>';
								$node_content = '<option value="delete">Delete</option>';
								foreach($switch2 as $key => $value)
								{
									$node_content .= 
									'<option value="'.$value.'">'.$value.'</option>';
								}
								
								echo $node_content;
							}
							?>
							
						 </select>
					</div>
				 </div>
				 
				 <div class="col-md-4 col-xs-4">
				    Command
					<div class="select-style" style="width:90px" >
						<select id="commandType">
							
							<?php 
							if($is_present2 == 1)
							{
								$command_content = '<option value=""></option>';
								$command_content .='<option value="ON">ON</option>';
								$command_content .='<option value="OFF">OFF</option>';
							
								echo $command_content;
							}
							?>
							
						 </select>
					</div>
				 </div>
				 	
              </div>
			  
			  <div class = "row" >
			  <div class="col-md-3 col-xs-1" style="margin-top:10px">
					<button type="button" class="btn btn-primary" style="width:105px" onClick="setThreshold()" id="threshold">Set Threshold</button>
			  </div>
			  </div>
            </div>
        </div>
		
		<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bar-chart"></i> Appliances</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-circle-o"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div style="font-size:20px;vertical-align:left;" class="tab-content no-padding">
					
					<?php echo $tab_content ?>
					
				</div>
                </div>
              </div>
            </div>
        </div>
	 
		</section>
		
		<?php
		}
		?>
     
	 </div>
	 
	 

	 <div class="modal fade" id="modal-threshold">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Switch Threshold</h4>
              </div>
              <div class="modal-body">
                <p>Do you wish to save your new threshold?</p>
				<span>Switch: <span id="saveColumn1"> </span></span>
				<span>Node: <span id="saveColumn2"> </span></span>
				<span>Command: <span id="saveColumn3"> </span></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="buttoncolumn1" class="btn btn-primary" data-dismiss="modal" onClick="saveThreshold()" >Save changes</button>
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
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>


<script src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.netpie.io/microgear.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQs_RyLtC82fNUP08MJO90ykPZJMaK_Wk&callback=initMap"></script>



<script>
function setThreshold(){
	
var x = document.getElementById("switchType").value;
var y = document.getElementById("nodeType").value;
var z = document.getElementById("commandType").value;

if (x == "") 
{
	alert("A switch must be chosen!");
	$('#modal-threshold').modal('hide');
}
else
{
	document.getElementById("saveColumn1").innerHTML= x;
	
	if (y == "delete" && z == "")
	{
		document.getElementById("saveColumn2").innerHTML= "Delete Threshold";
		document.getElementById("saveColumn3").innerHTML= "Delete Threshold";
		$('#modal-threshold').modal('show');
	}
	else if(y != "delete" && z != "")
	{
		document.getElementById("saveColumn2").innerHTML= y;
		document.getElementById("saveColumn3").innerHTML= z;
		$('#modal-threshold').modal('show');
	}
	
}
}

</script>

<script>
function saveThreshold(){
	
var switches = document.getElementById("switchType").value;
var node = document.getElementById("nodeType").value;
var command = document.getElementById("commandType").value;

if (switches == "") 
{
    alert("A switch must be chosen"); 
	
}
else 
{
	$.ajax({type: "POST",
            url: "update-switch-threshold-ajax",
            data: { switches: switches , node : node , command : command},
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
  $.widget.bridge('uibutton', $.ui.button);
  
   $(document).ready(function(){
	  
	$('.chat_body').hide();
	  
	$('.chat_head').click(function(){
		$('.chat_body').slideToggle('slow');
	});
	
	$('.msg_head').click(function(){
		$('.msg_wrap').slideToggle('slow');
	});
	
});
</script>



<?php
if($is_column1 == 1)
{
?>

<script type="text/javascript">
    
    function column_chart() {
		
		var numType = document.getElementById('numType').value;
		
		var jsonData = $.ajax({
			url: 'column1-ajax',
			type: 'POST',
    		dataType:"json",
			data: {numType: numType},
    		async: false,
			success: function(jsonData)
				{
					
					 // Draw a column chart
					var wrapper = new google.visualization.ChartWrapper({
					options : {tooltip: {isHtml: true}},
					containerId: 'columnchart_values'
					});

					var chartType = document.getElementById('chartType').value;
					
					var data = new google.visualization.DataTable(jsonData);
					
					wrapper.setChartType(chartType);
					wrapper.setDataTable(data);
        		
					wrapper.draw();
					
				}	
			}).responseText;
  }
  
</script>

<?php
}
?>

<?php
if($is_column2 == 1)
{
?>

<script type="text/javascript">

    function column_chart2() {
		
		var numType = document.getElementById('numType2').value;
		
		var jsonData = $.ajax({
			url: 'column2-ajax',
			type: 'POST',
			data: {numType: numType},
    		dataType:"json",
    		async: false,
			success: function(jsonData)
				{
					 // Draw a column chart
					var wrapper = new google.visualization.ChartWrapper({
					options : {tooltip: {isHtml: true}},
					containerId: 'columnchart_values2'
					});

					var chartType = document.getElementById('chartType2').value;
								
					var data = new google.visualization.DataTable(jsonData);
								
					wrapper.setChartType(chartType);
					wrapper.setDataTable(data);
							
					wrapper.draw();	
				}	
			}).responseText;
			
		
  }
     
</script>
<?php
}
?>

<?php
if($is_column3 == 1)
{
?>

<script type="text/javascript">
    
    function column_chart3() {
		
		var numType = document.getElementById('numType3').value;
		
		var jsonData = $.ajax({
			url: 'column3-ajax',
			type: 'POST',
    		dataType:"json",
			data: {numType: numType},
    		async: false,
			success: function(jsonData)
				{
					
					 // Draw a column chart
					var wrapper = new google.visualization.ChartWrapper({
					options : {tooltip: {isHtml: true}},
					containerId: 'columnchart_values3'
					});

					var chartType = document.getElementById('chartType3').value;
					
					var data = new google.visualization.DataTable(jsonData);
					
					wrapper.setChartType(chartType);
					wrapper.setDataTable(data);
        		
					wrapper.draw();
					
				}	
			}).responseText;
  }
  
</script>

<?php
}
?>

<?php
if($is_column4 == 1)
{
?>
<script type="text/javascript">
    
    function column_chart4() {
		
		var numType = document.getElementById('numType4').value;
		
		var jsonData = $.ajax({
			url: 'column4-ajax',
			type: 'POST',
    		dataType:"json",
			data: {numType: numType},
    		async: false,
			success: function(jsonData)
				{
					
					 // Draw a column chart
					var wrapper = new google.visualization.ChartWrapper({
					options : {tooltip: {isHtml: true}},
					containerId: 'columnchart_values4'
					});

					var chartType = document.getElementById('chartType4').value;
					
					var data = new google.visualization.DataTable(jsonData);
					
					wrapper.setChartType(chartType);
					wrapper.setDataTable(data);
        		
					wrapper.draw();
					
				}	
			}).responseText;
  }
  
</script>
<?php
}
?>

<?php
if($is_gps == 1)
{
?>
<script>
    
	function initMap(){
	
	var jsonData = $.ajax({
			url: 'column5-ajax',
			type: 'POST',
    		dataType:"json",
    		async: false,
			success: function(jsonData)
				{
					if(jsonData != "")
					{
						var div = document.getElementById('columnchart_values5'); 
						div.style.width = "100%";
						div.style.height = "400px";
						
						var map = new google.maps.Map(document.getElementById('columnchart_values5'), {
						  zoom: 12,
						  center: new google.maps.LatLng(-33.92, 151.25),
						  mapTypeId: google.maps.MapTypeId.ROADMAP
						});

						var infowindow = new google.maps.InfoWindow();
						
						var marker, i;

						for (i = 0; i < jsonData.length; i++) {  
						  marker = new google.maps.Marker({
							position: new google.maps.LatLng(parseFloat(jsonData[i][1]), parseFloat(jsonData[i][2])),
							map: map
						  });
						  
						   var center = new google.maps.LatLng(parseFloat(jsonData[i][1]), parseFloat(jsonData[i][2]));
						   map.panTo(center);
						   
						   google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
							  infowindow.setContent(jsonData[i][0]);
							  infowindow.open(map, marker);
							}
						  })(marker, i));
						  
						}
					}
					else
					{
						var div = document.getElementById('columnchart_values5'); 
						div.style.width = "100%";
						div.style.height = "20px";
						
						div.innerHTML = "No GPS Coordinates Found";
					}
				}	
			}).responseText;
	}
	
</script>
<?php
}
?>


<?php
if($is_youtube == 1)
{
?>
<script type="text/javascript">
    
    function column_chart6() {
		
		var jsonData = $.ajax({
			url: 'column6-ajax',
			type: 'POST',
    		dataType:"json",
    		async: false,
			success: function(jsonData)
				{
					
					
				}	
			}).responseText;
	}
  
</script>
<?php
}
?>


<script>

	var table = $('#dynatable').DataTable( {
    ajax: "table-ajax",
	scrollCollapse: true,
	scrollY:  200
} );
 
setInterval( function () {
    table.ajax.reload();
}, 10000 );

</script>

<script>

	var table2 = $('#dynatable2').DataTable( {
    ajax: "table2-ajax",
	scrollCollapse: true,
	scrollY:  200
} );
 
setInterval( function () {
    table2.ajax.reload();
}, 10000 );

</script>

<?php 
if($is_column2 == 1)
{
?>
<script type="text/javascript">
		$(document).ready(function(){
			
					google.charts.load("visualization", "1", {packages:["corechart"]});
      
					
                    // First load the chart once 
                    column_chart2();
					
					google.charts.setOnLoadCallback(column_chart2);
                    // Set interval to call the drawChart again
                    setInterval(column_chart2, 10000);
                    });
</script>
<?php
}
?>
<?php 
if($is_column1 == 1)
{
?>
<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
					
					// Load the Visualization API and the piechart package.
					google.charts.load("visualization", "1", {packages:["corechart"]});
					  
					
	
                    column_chart();
					
					google.charts.setOnLoadCallback(column_chart);
                    // Set interval to call the drawChart again
                    setInterval(column_chart, 10000);
                    });
</script>
<?php
}
?>

<?php 
if($is_column3 == 1)
{
?>
<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
					
					// Load the Visualization API and the piechart package.
					google.charts.load("visualization", "1", {packages:["corechart"]});
					  
					
	
                    column_chart3();
					
					google.charts.setOnLoadCallback(column_chart3);
                    // Set interval to call the drawChart again
                    setInterval(column_chart3, 10000);
                    });
</script>
<?php
}
?>
<?php 
if($is_column4 == 1)
{
?>
<script type="text/javascript">
		$(document).ready(function(){
                    // First load the chart once 
					
					// Load the Visualization API and the piechart package.
					google.charts.load("visualization", "1", {packages:["corechart"]});
					  
					
	
                    column_chart4();
					
					google.charts.setOnLoadCallback(column_chart4);
                    // Set interval to call the drawChart again
                    setInterval(column_chart4, 10000);
                    });
</script>
<?php
}
?>


<?php 
if($is_gps == 1)
{
?>
<script type="text/javascript">
		$(document).ready(function(){
                   
	
                    initMap();
					
                    setInterval(initMap, 20000);
                    });
</script>
<?php
}
?>


<?php 
if($is_present == 1)
{
	
	?>
	
	<script>
		
		const APPKEY = "ssMBRNl27QOP02o";
		const APPSECRET = "NhEsGIu3wqsP6gRIijoVc98Ce";
		const APPID = "LoRaESP8266";

		var microgear = Microgear.create({
			gearkey: APPKEY,
			gearsecret: APPSECRET
		});
		
	<?php
	foreach($switch as $key => $value)
	{	
?>
		
		function <?php echo $value?>(){
			if(document.getElementById("<?php echo $value?>").className == "cube-switch active"){
				microgear.publish("/gearname/<?php echo $value?>","OFF", true);
			}else if(document.getElementById("<?php echo $value?>").className == "cube-switch"){
				microgear.publish("/gearname/<?php echo $value?>","ON" , true);
			}
		}
		
	<?php 

	}
	
?>

		microgear.on('message', function(topic,data) {
			
			var parts = data.split('/', 2);

			// After calling split(), 'parts' is an array with two elements:
			// parts[0] is 'sometext'
			// parts[1] is '20202'

			var topic = parts[0];
			var msg   = parts[1];
			
		<?php if($is_present == 1)
		{
			foreach($switch as $key => $value)
			{
		
		?>
			if(topic == "<?php echo $value?>" && msg == "ON" ){
				document.getElementById("<?php echo $value?>").className = "cube-switch active";
			}else if(topic == "<?php echo $value?>" && msg == "OFF"){
				document.getElementById("<?php echo $value?>").className = "cube-switch";
			}
		
		<?php }
		}
		?>
			
		});

		microgear.on('connected', function() {
			microgear.setname('controllerplug');
	<?php	
		if($is_present == 1)
		{
			foreach($switch as $key => $value)
			{	?>	
			document.getElementById("<?php echo $value?>").style.display = "block";	
	<?php 	} 
		}	?>
		});
		
		microgear.resettoken();
		microgear.connect(APPID);
		
		</script>
	<?php
	
	}
	
	?>

	
</body>
</html>

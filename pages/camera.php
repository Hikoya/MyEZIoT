<?PHP
require_once("../include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login");
    exit;
}

if (isset($_POST['logout'])) {

	$fgmembersite->LogOut();
}

$username = $fgmembersite->UserFullUserName();
$config = parse_ini_file("../private/config.ini");
$admin = $config['adminname'];

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

<?php
if(!empty($cameraurl))
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="//www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $sitehead?> | Camera</title>
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
          <p id = "user"><?= $fgmembersite->UserFullUserName(); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
     
      <!-- /.search form -->
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
			<li class="active"><a href="camera"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
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
			<li class="active"><a href="camera"> <i class="fa fa-camera"></i> <span>Camera</span></a></li>
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
        <small>Camera</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Camera</li>
      </ol>
    </section>
	
	
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<h3 class="box-title">Camera</h3>
			</div>
			<div class="box-body">
			
			<div id="imageDiv"></div>
			<button id="send">Take a Photo</button>
			
			</div>
        </div>
       
        <!-- /.col -->
      </div>
	  
	  </div>
	  
	  <div class="row">
	   <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<h3 class="box-title">Pictures</h3>
			</div>
			<div class="box-body">
			
			<div id="images"></div>
			<div id="filenames"></div>
			
			</div>
        </div>
       </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	

  </div>
  <!-- /.content-wrapper -->

</div>

<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Slimscroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="../plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core-min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/hmac-min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/sha256-min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>


<?php
	if(!empty($cameraurl))
	{
?>
<script type="text/javascript">
    
    document.getElementById('send').addEventListener('click', function (e) {
      send('');
    });
	
    function SigV4Utils(){}
    SigV4Utils.sign = function(key, msg) {
      var hash = CryptoJS.HmacSHA256(msg, key);
      return hash.toString(CryptoJS.enc.Hex);
    };
	
    SigV4Utils.sha256 = function(msg) {
      var hash = CryptoJS.SHA256(msg);
      return hash.toString(CryptoJS.enc.Hex);
    };
	
    SigV4Utils.getSignatureKey = function(key, dateStamp, regionName, serviceName) {
      var kDate = CryptoJS.HmacSHA256(dateStamp, 'AWS4' + key);
      var kRegion = CryptoJS.HmacSHA256(regionName, kDate);
      var kService = CryptoJS.HmacSHA256(serviceName, kRegion);
      var kSigning = CryptoJS.HmacSHA256('aws4_request', kService);
      return kSigning;
    };
	
    function createEndpoint(regionName, awsIotEndpoint, accessKey, secretKey) {
      var time = moment.utc();
      var dateStamp = time.format('YYYYMMDD');
      var amzdate = dateStamp + 'T' + time.format('HHmmss') + 'Z';
      var service = 'iotdevicegateway';
      var region = regionName;
      var secretKey = secretKey;
      var accessKey = accessKey;
      var algorithm = 'AWS4-HMAC-SHA256';
      var method = 'GET';
      var canonicalUri = '/mqtt';
      var host = awsIotEndpoint;
      var credentialScope = dateStamp + '/' + region + '/' + service + '/' + 'aws4_request';
      var canonicalQuerystring = 'X-Amz-Algorithm=AWS4-HMAC-SHA256';
      canonicalQuerystring += '&X-Amz-Credential=' + encodeURIComponent(accessKey + '/' + credentialScope);
      canonicalQuerystring += '&X-Amz-Date=' + amzdate;
      canonicalQuerystring += '&X-Amz-SignedHeaders=host';
      var canonicalHeaders = 'host:' + host + '\n';
      var payloadHash = SigV4Utils.sha256('');
      var canonicalRequest = method + '\n' + canonicalUri + '\n' + canonicalQuerystring + '\n' + canonicalHeaders + '\nhost\n' + payloadHash;
      var stringToSign = algorithm + '\n' +  amzdate + '\n' +  credentialScope + '\n' +  SigV4Utils.sha256(canonicalRequest);
      var signingKey = SigV4Utils.getSignatureKey(secretKey, dateStamp, region, service);
      var signature = SigV4Utils.sign(signingKey, stringToSign);
      canonicalQuerystring += '&X-Amz-Signature=' + signature;
      return 'wss://' + host + canonicalUri + '?' + canonicalQuerystring;
    }
	
    var endpoint = createEndpoint(
        'ap-southeast-1', // Your Region
        '<?php echo $cameraurl?>', // Require 'lowercamelcase'!!
        'AKIAJWAP2QH4TNT3TL2Q',
        '9QV/zA9pP50vNxgioK1y54K9lSBZBzHYrW6GXvWh');
		
    var clientId = Math.random().toString(36).substring(7);
    var client = new Paho.MQTT.Client(endpoint, clientId);
    
	var connectOptions = {
      useSSL: true,
      timeout: 3,
      mqttVersion: 4,
      onSuccess: subscribe
    };
	
    client.connect(connectOptions);
    client.onMessageArrived = onMessage;
    client.onConnectionLost = function(e) { console.log(e) };
	
    function subscribe() {
      client.subscribe("<?php echo $username ?>/MQTT2");
      console.log("subscribed");
    }
	
    function send(content) {
	
      var message = new Paho.MQTT.Message(content);
      message.destinationName = "<?php echo $username?>/MQTT";
      client.send(message);
      console.log("sent");
	  
    }
	
    function onMessage(message) {
	  
	  console.log("Received img");
	  //console.log(message.payloadString);
	  
	  var payload = JSON.parse(message.payloadString);
	  var data = payload.message;
	  
	 // var image = new Image();
	 // image.src = 'data:image/png;base64,' + data;
	 // image.width = 200;
	 // image.height = 200;
	  
	 // console.log(image);
	  
	  //document.getElementById("imageDiv").src = image.src;
	  //document.body.appendChild(image);
	  
	  //var item = document.getElementById("imageDiv").childNodes[0];
	  //item.replaceChild(image, item.childNodes[0]);
		

	  $('#imageDiv').empty();
      $('#imageDiv').prepend('<img src="data:image/jpeg;base64, '+data+'" height="200" width = "200">');	  
	  
	  
    }
	
</script>

<script>

function displayImg(){
	
	var name = document.getElementById("user").innerText;
	$('#images').empty();
	$('#filenames').empty();
	
	$.ajax({
            url: "getcamera",
            dataType: "json",
			type:"post",
			data:{
			name: name	
			},
            success: function (data) {
		
                $.each(data, function(i,filename) {
					 var title = filename.substr(0,19);
                     $('#images').prepend('<img src="'+ '/pages/mqtt/' + name + '/'+ filename +'" title="' + title + '" height="200" width = "200" style="padding-right:20px">');	 
					 
                });
            }
        });
}

</script>
<script>
	$(document).ready(function(){
                    // First load the chart once
                    displayImg();
                    // Set interval to call the drawChart again
                    setInterval(displayImg, 10000);
                    });
</script>  
<?php
	}

?>


</body>
</html>
<?php
}
?>

<?php 

require_once("../include/membersite_config.php");

if(isset($_POST['submittedlog']))
{
   if($fgmembersite->LoginToken())
   {
        $fgmembersite->RedirectToURL("thank-you-token.php");
   }

}

$config = parse_ini_file("../private/config.ini");

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];

?>


<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo $sitehead ?>| Login </title>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="../css/style1.css">

</head>

<body>

 <div class="login-wrap">
	<div class="login-html">
	
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead?> </p>
		
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
				<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' >
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" type="text" class="input" name="usernametoken" value='<?php echo $fgmembersite->SafeDisplay('username') ?>' >
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass" type="password" class="input" name="passwordtoken" data-type="password">
				</div>
		
				<div class="group">
					<input type="submit" class="button" value="Sign In" name="submittedlog" id="submittedlog">
				</div>
				<div class="hr"></div>
				
				</form>
				
			</div>
			<div class="sign-up-htm">
			
			</div>
		</div>
	</div>
</div>
  
<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="../scss/js/index.js"></script>
  
</body>
</html>



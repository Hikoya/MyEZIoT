<?PHP
require_once("../include/membersite_config.php");

$success = false;
if($fgmembersite->ResetPassword())
{
    $success=true;
}

$config = parse_ini_file("../private/config.ini");

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];

?>
<!DOCTYPE html>
<html >
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
  <title><?php echo $sitehead ?> | Reset Password</title>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="../css/style1.css">
  <link rel="STYLESHEET" type="text/css" href="../style/fg_membersite.css" />


</head>
<body>

<div class="login-wrap">
	<div class="login-html">
	
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead ?> </p>
		
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Reset</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
			<?php
			if($success){
			?>
			<h2>Password is resetted successfully</h2>
			Your new password is sent to your email address.
			<?php
			}else{
			?>
			<h2>Error</h2>
			<span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span>
			<?php
			}
			?>
			
			<div class="hr"></div>
				<div class="foot-lnk">
					<a href="login.php">Home</a>
				</div>
				
			</div>
			<div class="sign-up-htm">	
				
			</div>
		</div>
	</div>
</div>


</body>
</html>
<?PHP
require_once("../include/membersite_config.php");

$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetPasswordLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent");
        exit;
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
  <title><?php echo $sitehead ?> | Forget Password </title>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="../css/style1.css">

</head>


<body>

<div class="login-wrap">
	<div class="login-html">
	
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead ?> </p>
		
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Reset</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
				<form id='resetpwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' >
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				<div class="group">
					<label for="user" class="label">Email Address</label>
					<input id="user" type="text" class="input" name="email" value='<?php echo $fgmembersite->SafeDisplay('email') ?>' >
				</div>
				<div class="group">
					<input type="submit" class="button" value="Reset Password" name="submitted" id="submitted">
				</div>
				<div class="hr"></div>
				<div class="foot-lnk">
					<a href="login.php">Home</a>
				</div>
				</form>
				
			</div>
			<div class="sign-up-htm">	
				
			</div>
		</div>
	</div>
</div>



<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
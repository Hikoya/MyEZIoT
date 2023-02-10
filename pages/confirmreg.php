<?PHP
require_once("../include/membersite_config.php");

if(isset($_GET['code']))
{
   if($fgmembersite->ConfirmUser())
   {
        $fgmembersite->RedirectToURL("thank-you-regd");
   }
}

$config = parse_ini_file("../private/config.ini");

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];

?>


<!DOCTYPE html>
<html >
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
  <title><?php echo $sitehead ?> | Confirm Registration</title>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="../css/style1.css">
  <link rel="STYLESHEET" type="text/css" href="../style/fg_membersite.css" />
  <script type='text/javascript' src='../scripts/gen_validatorv31.js'></script>

</head>
<body>

<div class="login-wrap">
	<div class="login-html">
	
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead ?> </p>
		
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Confirm</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
				<p>Please enter the confirmation code in the box below</p>
			
				<form id='confirm' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='get' accept-charset='UTF-8' >
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				<div class="group">
					<label for="user" class="label">Confirmation Code</label>
					<input id="user" type="text" class="input" name="code" >
				</div>
				<div class="group">
					<input type="submit" class="button" value="Submit" name="submittedreg" id="submittedreg">
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


  <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="../scss/js/index.js"></script>

</body>
</html>
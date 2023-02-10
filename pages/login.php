<?PHP
require_once("../include/membersite_config.php");

if(isset($_POST['submittedlog']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("index");
   }

}

else if(isset($_POST['submittedreg']))
{
   if($fgmembersite->RegisterUser())
   {
        $fgmembersite->RedirectToURL("thank-you");
   }
}

if(!empty($_GET['userID']))
{
	$userID = $_GET['userID'];
}
else $userID = '';

if(!empty($_GET['pwd']))
{
	$password = $_GET['pwd'];
}
else $password = '';

if(!empty($_GET['key']))
{
	$key = $_GET['key'];
}
else $key = '';

if(!empty($userID) && !empty($password))
{
	if(!isset($_SESSION)){ session_start(); }
	if(!$fgmembersite->CheckLoginInDB($userID,$password))
    {
        return false;
    }
	else
	{
		$_SESSION[$fgmembersite->GetLoginSessionVar()] = $userID;
		$fgmembersite->RedirectToURL("index");
	}
		
}

if(!empty($key))
{
	if(!isset($_SESSION)){ session_start(); }
	if(!$fgmembersite->CheckLoginInDBKey($key))
    {
        return false;
    }
	else
	{
		$_SESSION[$fgmembersite->GetLoginSessionKey()] = $key;
		$fgmembersite->RedirectToURL("public");
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
  <title><?php echo $sitehead ?> | Login/Signup </title>
  
  
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
<link rel="stylesheet" href="../css/style1.css">


<style>
.column {
    float: left;
    width: 50%;
}

.row:after {
    content: "";
    display: table;
    clear: both;
}

</style>
</head>

<body>
  <div class="login-wrap">
	<div class="login-html">
	    <br>
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead ?> </p>
		<br>
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
		<div class="login-form">
			<div class="sign-in-htm">
			
				<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8' >
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" type="text" class="input" name="usernamelogin" value='<?php echo $fgmembersite->SafeDisplay('username') ?>' >
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass" type="password" class="input" name="password" data-type="password">
				</div>
		
				<div class="group">
					<input type="submit" class="button" value="Sign In" name="submittedlog" id="submittedlog">
				</div>
				<div class="hr"></div>
				
				<div class="foot-lnk">
					<a href="reset-pwd-req">Forgot Password?</a>
				</div>
				
				<br>
				<br>
				
				<div class="foot-lnk">
					<a href="public">Demo Mode</a>
				</div>
				</form>
				
				<br>
				<br>
				<br>
				
				<TABLE BORDER="0">
				<TR>
				
				<TD>
				 <a href='https://play.google.com/store/apps/details?id=com.sp.eziot&hl=en&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' width="180" height="70" src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'/></a>
				</TD>
				<TD> 
				 <a href='https://itunes.apple.com/us/app/eziot/id1294664424?ls=1&mt=8'><img width="200" height="45" src='../css/appstore.svg'/></a>
				</TD>
				
				</TR>

				</TABLE>
				
			</div>
			<div class="sign-up-htm">
			
				<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				
				<div class="group">
					<label for="user" class="label">Full Name</label>
					<input id="user" type="text" class="input" name="name">
				</div>
				<div class="group">
					<label for="pass" class="label">Email Address</label>
					<input id="pass" type="text" class="input" name="email">
				</div>
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" type="text" class="input" name="username">
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass" type="password" class="input" data-type="password" name="password">
				</div>
				<div class="group">
					<label for="user" class="label">Postal Address</label>
					<input id="user" type="text" class="input" name="address">
				</div>
				<div class="group">
					<label for="user" class="label">Postal Code</label>
					<input id="user" type="text" class="input" name="postalcode">
				</div>
				<div class="group">
					<label for="user" class="label">International Country Code</label>
					<input id="user" type="text" class="input" name="callingcode">
				</div>
				<div class="group">
					<label for="user" class="label">Mobile Number</label>
					<input id="user" type="text" class="input" name="mobileno">
				</div>
				
				<div class="group">
					<input type="submit" class="button" value="Sign Up" name="submittedreg" id="submittedreg">
				</div>
				
				<div class="hr"></div>
				<div class="foot-lnk">
					<label for="tab-1">Already Member?</a>
				</div>
				</form>
				
				<br>
				<br>

				<TABLE BORDER="0">
				<TR>
				
				<TD>
				 <a href='https://play.google.com/store/apps/details?id=com.sp.eziot&hl=en&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' width="180" height="70" src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'/></a>
				</TD>
				<TD> 
				 <a href='https://itunes.apple.com/us/app/eziot/id1294664424?ls=1&mt=8'><img width="200" height="45" src='../css/appstore.svg'/></a>
				</TD>
				
				</TR>

				</TABLE>
			
			</div>
			
		</div>
	</div>
   </div>


<script src="../scripts/gen_validatorv31.js" type="text/javascript"></script>
<script type='text/javascript'>
// <![CDATA[
   
    var frmvalidator  = new Validator("register");
    frmvalidator.addValidation("name","req","Please provide your full name");
	frmvalidator.addValidation("email","req","Please provide your email address");
	frmvalidator.addValidation("username","req","Please provide your username");
	frmvalidator.addValidation("password","req","Please provide your password");
	frmvalidator.addValidation("callingcode","req","Please provide your calling code");
	frmvalidator.addValidation("mobileno","req","Please provide your mobile number");
	
// ]]>
</script>

<script type='text/javascript'>
// <![CDATA[
   
    var frmvalidator  = new Validator("login");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
	frmvalidator.addValidation("usernamelogin","req","Please provide your username");
	frmvalidator.addValidation("password","req","Please provide your password");
	
// ]]>
</script>
  
</body>
</html>

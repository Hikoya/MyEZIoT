<?php

$config = parse_ini_file("../private/config.ini");

$sitehead = $config['sitehead'];
$sitebot = $config['sitebot'];
?>

<!DOCTYPE html>
<html >
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
  <title><?php echo $sitehead ?> | Login</title>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="../css/style1.css">

</head>
<body>

<div class="login-wrap">
	<div class="login-html">
	
		<p style="font-size: 20px; padding-bottom: 10px"> <?php echo $sitehead ?> </p>
		
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab"></label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
			<h2> You have successfully logged in </h2>
			<br>
			An SMS containing the login token will be sent to your mobile phone shortly.
			
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
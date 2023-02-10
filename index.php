<?php
	
	/*if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/pages/login.php');
	exit;*/
?>



<!DOCTYPE html>
<html >

<body>
   <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="scss/js/index.js"></script>
  <script type="text/javascript">
	var timer = 0; //seconds
	 website = "https://myeziot.com/pages/index.php"
	function delayer() {
	 window.location = website;
	}
	setTimeout('delayer()', 1000 * timer); 
</script>
 
</body>
</html>

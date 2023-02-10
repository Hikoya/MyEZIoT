<?PHP
	
	require_once("../include/membersite_config.php");

	if (empty($_POST['key'])) {
		$key = '';
	} else {
		$key = $_POST['key'];
	}
	
	if(!empty($key))
	{
		if($fgmembersite->LoginKey())
		{
			echo "success";
		}
		else
		{
			//echo $fgmembersite->GetErrorMessage();
			echo "failed";
		}
	}

?>



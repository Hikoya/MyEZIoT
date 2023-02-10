<?PHP
	
	require_once("../include/membersite_config.php");

	if (empty($_POST['userID'])) {
		$userID = '';
	} else {
		$userID = $_POST['userID'];
	}
	
	if (empty($_POST['password'])) {
		$password = '';
	} else {
		$password = $_POST['password'];
	}
	
	if(!empty($userID) && !empty($password))
	{
		if($fgmembersite->LoginUnity())
		{
			echo "success".";".$userID;
		}
		else
		{
			//echo $fgmembersite->GetErrorMessage();
			echo "failed";
		}
	}

?>



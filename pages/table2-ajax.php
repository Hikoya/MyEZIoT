<?php	
					
	require_once("../include/membersite_config.php");

	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	else if($fgmembersite->CheckKeyLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	
	else
	{
		$username = "";
	}
		
	if(!empty($username))
	{
		$config = parse_ini_file('../private/config.ini'); 	
	
		$sql = "SELECT * FROM ".$config['tablenameswitch']." where username = '".$username."'  ";
		
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		$query3 = mysqli_query($link,$sql);
		if(mysqli_num_rows($query3) > 0)
		{
			while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
			{
				$gatewayno[] = $result3['gatewayno'];	
			}
			
			foreach($gatewayno as $key => $value)
			{
				$sql6 = "SELECT * FROM ".$config['tablenameswitch']." where gatewayno = '$value' ";
				$query6 = mysqli_query($link,$sql6);
											
				if(mysqli_num_rows($query6) > 0)
				{
					if($result7 = mysqli_fetch_array($query6,MYSQLI_ASSOC))
					{
						$description = $result7['description'];
					}
					
				}	
				else
				{
					$description = "No data available";	
				}
					
								
				$data[] = array($value,$description);
		
			}
		
				
		}
		else
		{
			$data[] = array("No data available in table","");
		}
		
		$data2 = array('data' => $data);
		echo json_encode($data2);
	
	}
	else
	{
		$data[] = array("Session timed out","","","","","","");
		$data2 = array('data' => $data);
		echo json_encode($data2);
	}
	
	
	
	
?>
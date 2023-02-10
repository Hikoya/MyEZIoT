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
	
		$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '".$username."'  ";
		
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
				$sql6 = "SELECT * FROM ".$config['tablenamenode']." where gatewayno = '$value' ";
				$query6 = mysqli_query($link,$sql6);
											
				if(mysqli_num_rows($query6) > 0)
				{
			
					if($result7 = mysqli_fetch_array($query6,MYSQLI_ASSOC))
					{
						$description = $result7['description'];
						$location = $result7['location'];
						
						if(!empty($result7['column1']))
							$column1 = $result7['column1'];
						else
							$column1 = "N.A";
						
						if(!empty($result7['column2']))
							$column2 = $result7['column2'];
						else
							$column2 = "N.A";
						
						if(!empty($result7['column3']))
							$column3 = $result7['column3'];
						else
							$column3 = "N.A";
						
						if(!empty($result7['column4']))
							$column4 = $result7['column4'];
						else
							$column4 = "N.A";
						
						$column1 = ucfirst($column1);
						$column2 = ucfirst($column2);
						$column3 = ucfirst($column3);
						$column4 = ucfirst($column4);
					}
					
				}	
				else
				{
					$description = "No";
					$location = "data available";
					$column1 = "Please check";
					$column2 = "your details";		
					$column3 = "";	
					$column4 = "";	
				}
					
									
				
				$data[] = array($value,$description,$location,$column1,$column2,$column3,$column4);
		
			}
		
				
		}
		else
		{
			$data[] = array("No data available in table","","","","","","");
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
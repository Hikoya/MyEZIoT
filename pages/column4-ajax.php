<?php
	require_once("../include/membersite_config.php");
	
	if (empty($_POST['numType'])) {
		$numType = '';
	} else {
		$numType = $_POST['numType'];
	}
	
	$username = '';
	
	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	else if($fgmembersite->CheckKeyLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	
	
	if(!empty($username) && !empty($numType))
	{	
		$config = parse_ini_file('../private/config.ini'); 

		$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '".$username."' AND column4 IS NOT NULL order by timestamp asc limit ".$numType." ";
		
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		$query3 = mysqli_query($link,$sql);
		
		if(mysqli_num_rows($query3) > 0)
		{
			$table['cols'] = array
			(
			array('label' => 'Gateway Number', 'type' => 'string'),
			array('label' => 'Values', 'type' => 'number'),
			array('label' => 'tooltip', 'type' => 'string', 'role' => 'tooltip')
			);	
			
			while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
			{
				$gatewaynoarray[] = $result3['gatewayno'];	
			}
			
			foreach($gatewaynoarray as $key => $value)
			{
					$column4 = 2;
					
					$sql4 = "SELECT * FROM " . $value . " WHERE column4 is NOT NULL order by timestamp desc limit 1 ";
					$query4 = mysqli_query($link, $sql4);
					
					if(mysqli_num_rows($query4) > 0)
					{
				
						if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
						{
							$column4= (float)$result4['column4'];  
							$timestamp = $result4['timestamp'];
						}	
					
						$sql5 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' ";
						$query5 = mysqli_query($link, $sql5);
						if($result5 = mysqli_fetch_array($query5,MYSQLI_ASSOC))
						{
							$description = $result5['description'];
							$usernamee = $result5['username'];
							$address = $result5['location'];
							$address = ucfirst($address);
							$columnname = $result5['column4'];
							$columnname = ucfirst($columnname);
						}
					
						$addressarray = array("$columnname : $column4  ", " Location: $address " , " Last Updated: $timestamp ");
					
						$temp = array();
					
						
						$temp[] = array('v' => $description);
						
						

						$temp[] = array('v' => $column4);
						$temp[] = array('v' => $addressarray);
						$rows[] = array('c' => $temp);
						
					}
					else
					{
						$sql5 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' ";
						$query5 = mysqli_query($link, $sql5);
						if($result5 = mysqli_fetch_array($query5,MYSQLI_ASSOC))
						{
							$description = $result5['description'];
						}
						
						
						$addressarray = array("No data recorded.");
						$temp = array();
						$temp[] = array('v' => $description);
						$temp[] = array('v' => $column4);
						$temp[] = array('v' => $addressarray);
						$rows[] = array('c' => $temp);
						
					}				
			}
				
			$table['rows'] = $rows;
			
		}
		else
		{
			$table['cols'] = array
			(
			array('label' => 'Gateway Number', 'type' => 'string'),
			array('label' => 'Values', 'type' => 'number'),
			array('label' => 'tooltip', 'type' => 'string', 'role' => 'tooltip')
			);	
			
			$temp = array();
			$temp[] = array('v' => "");	
			$temp[] = array('v' => "");
			$temp[] = array('v' => "");
			$rows[] = array('c' => $temp);
			
			$table['rows'] = $rows;
			
		}
		
	}
	else
	{
		$table = '';
	}
	
	
	echo json_encode($table);
	
	
?>
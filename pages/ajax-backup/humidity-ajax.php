<?php
	require_once("../include/membersite_config.php");

	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
		
	$data[] = array('gatewayno','Humidity');
	$config = parse_ini_file('../private/config.ini'); 	 

	if($username == $config['adminname'])
	{
		$sql = "SELECT * FROM ".$config['tablenamenode']."   ";
	}
	else
	{
		$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '".$username."'  ";
	}
	
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$query3 = mysqli_query($link,$sql);
	while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
	{
		$gatewaynoarray[] = $result3['gatewayno'];	
	}
	
	foreach($gatewaynoarray as $key => $value)
	{
			$temperature = 0;
			$humidity = 0;
			
			$sql4 = "SELECT * FROM " . $value . " order by timestamp desc limit 1 ";
			$query4 = mysqli_query($link, $sql4);
		
			if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
			{
			   $humidity = (float)$result4['humidity'];  
			   $timestamp = $result4['timestamp'];
			}	
			
			$sql5 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' ";
			$query5 = mysqli_query($link, $sql5);
			if($result5 = mysqli_fetch_array($query5,MYSQLI_ASSOC))
			{
				$description = $result5['description'];
			}
			
			if($username != $config['adminname'])
			{
				$data[] = array($description,$humidity);
			}
			else
			{
				$data[] = array($value,$humidity);
			}		
			
			
			
	}
	
	echo json_encode($data);
	
	
?>
<?php
	require_once("../include/membersite_config.php");

	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
		
	$config = parse_ini_file('../private/config.ini'); 	 

	$sql = "SELECT * FROM ".$config['tablename']." where username = '".$username."'  ";
	
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$query3 = mysqli_query($link,$sql);
	while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
	{
		$humidity = $result3['humidity'];	
		$temperature = $result3['temperature'];
	}
	
	$data = array($temperature,$humidity);
	

	echo json_encode($data);
	
	
?>
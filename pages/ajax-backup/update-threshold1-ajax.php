<?php

	require_once("../include/membersite_config.php");

	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
		
	$humidity = $_POST['humidity'];
	
	$config = parse_ini_file('../private/config.ini'); 	 

	$sql = "UPDATE ".$config['tablename']." SET humidity = '$humidity'  WHERE username = '$username' ";
	
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$query3 = mysqli_query($link,$sql);
	
	
?>
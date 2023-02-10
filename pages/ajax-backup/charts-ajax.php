<?php
				
	//$tabSelected = $_POST['id'];
	
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$rows = '';
	$query = "SELECT * FROM node1 ORDER BY timestamp DESC LIMIT 0, 24";
	$result = mysqli_query($link,$query);
	$total_rows =  $result->num_rows;
	if($result) 
	{
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	
	echo json_encode($rows);

?>
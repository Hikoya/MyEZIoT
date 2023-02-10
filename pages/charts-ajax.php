<?php
				
	if(!empty($_POST['id']))
	{
		$tabSelected = $_POST['id'];
	}
	else
	{
		$tabSelected = '';
	}
	
	if(!empty($_POST['interval']))
	{
		$interval = $_POST['interval'];
	}
	else
	{
		$interval = '';
	}
	
	
	if(!empty($tabSelected) && !empty($interval))
	{
		$config = parse_ini_file('../private/config.ini'); 	   
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		$sql = "SELECT * FROM ".$tabSelected." ORDER BY timestamp DESC LIMIT ".$interval." ";
		$query = mysqli_query($link,$sql);
		

		if(mysqli_num_rows($query) > 0)
		{
			while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
			{
				$column1 = (float)$result['column1'];
				$column2 = (float)$result['column2'];
				$column3 = (float)$result['column3'];
				$column4 = (float)$result['column4'];
				$timestamp = $result['timestamp'];
				
				$timearr[] = $timestamp;
				$column1arr[] = $column1;
				$column2arr[] = $column2;
				$column3arr[] = $column3;
				$column4arr[] = $column4;
			}
			
			$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$tabSelected' ";
			$query2 = mysqli_query($link,$sql2);
			
			while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
			{
				if(!empty($result2['column1']))
					$column1name = $result2['column1'];
				else
					$column1name = "Column 1";
				
				if(!empty($result2['column2']))
					$column2name = $result2['column2'];
				else
					$column2name = "Column 2";
			
				if(!empty($result2['column3']))
					$column3name = $result2['column3'];
				else
					$column3name = "Column 3";
			
				if(!empty($result2['column4']))
					$column4name = $result2['column4'];
				else
					$column4name = "Column 4";
				
				$location = $result2['location'];
				
				$column1name = ucfirst($column1name);
				$column2name = ucfirst($column2name);
				$column3name = ucfirst($column3name);
				$column4name = ucfirst($column4name);
				$location = ucfirst($location);
				
			}
			
			echo json_encode(array('column1' => $column1arr, 'column2' => $column2arr,'column3' => $column3arr, 'column4' => $column4arr,'timestamp' => $timearr , 
		'column1name' => $column1name , 'column2name' => $column2name , 'column3name' => $column3name , 'column4name' => $column4name, 'location' => $location));

		}
		else
		{
			echo json_encode("");
		}
	
	}
	else
		header('HTTP/1.1 500 Internal Server Error');
		
	
	
?>
<?php
				
	if(!empty($_POST['id']))
	{
		$tabSelected = $_POST['id'];
	}
	else
	{
		$tabSelected = '';
	}
	
	if(!empty($tabSelected))
	{
		$config = parse_ini_file('../private/config.ini'); 	   
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$tabSelected' " ;
		$query2 = mysqli_query($link,$sql2);
		if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
		{
			$column1name = $result2['column1'];
			$column2name = $result2['column2'];
		}
			$column1name = ucfirst($column1name);
			$column2name = ucfirst($column2name);
		
		$table['cols'] = array
		(
			array('label' => 'Timestamp', 'type' => 'string'),
			array('label' => $column1name, 'type' => 'number'),
			array('label' => $column2name, 'type' => 'number'),
		);	

		$sql = "SELECT * FROM ".$tabSelected." ORDER BY timestamp DESC LIMIT 10";
		$query = mysqli_query($link,$sql);
		while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			//$column1 = (float)$result['column1'];
			//$column2 = (float)$result['column2'];		
			$timestamp[] = $result['timestamp'];
			
		}
		
		foreach($timestamp as $key => $value)
		{
			$sql4 = "SELECT * FROM " . $tabSelected . " WHERE timestamp = '$value' ";
			$query4 = mysqli_query($link, $sql4);
			
			if(mysqli_num_rows($query4) > 0)
			{
				if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
				{
					$column1= (float)$result4['column1'];  
					$column2 = (float)$result4['column2'];
					$timestamp = $result4['timestamp'];
				}	
				
				$temp = array();
				$temp[] = array('v' => $timestamp);
				$temp[] = array('v' => $column1);
				$temp[] = array('v' => $column2);
				$rows[] = array('c' => $temp);
			}	
		}
		
		/*$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$tabSelected' ";
		$query2 = mysqli_query($link,$sql2);
		
		while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
		{
			$column1name = $result2['column1'];
			$column2name = $result2['column2'];
			
			$column1name = ucfirst($column1name);
			$column2name = ucfirst($column2name);
		}*/
		
		
		$table['rows'] = $rows;
		
		echo json_encode($table);
		
	}
	
	
?>
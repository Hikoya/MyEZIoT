<?php
	require_once("../include/membersite_config.php");
	
	$username = '';
	
	if($fgmembersite->CheckLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	else if($fgmembersite->CheckKeyLogin())
	{
		$username = $_SESSION['username_of_user'];
	}
	
	
	if(!empty($username))
	{	
		$config = parse_ini_file('../private/config.ini'); 

		$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '".$username."' order by timestamp asc ";
		
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		$query3 = mysqli_query($link,$sql);
		
		if(mysqli_num_rows($query3) > 0)
		{
			
			while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
			{
				$gatewaynoarray[] = $result3['gatewayno'];	
			}
			
			foreach($gatewaynoarray as $key => $value)
			{
				//echo $value;
				
				$sql4 = " SELECT * FROM " . $value . " WHERE column5 is NOT NULL ORDER BY TIMESTAMP DESC LIMIT 1 ";
				$query4 = mysqli_query($link, $sql4);
						
				if(mysqli_num_rows($query4) > 0)
				{
					//echo $value;
					
					$desc = "";
					$lat = "";
					$long = "";
					
					if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
					{
						$column4 = $result4['column5'];  
					
						$columnarray = explode(';',$column4);
						
						$lat = $columnarray[0];
						$long= $columnarray[1];
						
					}	
					
					$sql5 = "SELECT * FROM " .$config['tablenamenode']." WHERE gatewayno = '" . $value. "' ";
					$query5 = mysqli_query($link , $sql5);
					
					if(mysqli_num_rows($query5) > 0)
					{
						if($result5 = mysqli_fetch_array($query5,MYSQLI_ASSOC))
						{
							$desc = $result5['description'];  
						}	
					}
					
					$final_arr = array($desc,$lat,$long);
					
				}
				else
				{
					$sql6 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' AND position is NOT NULL";
					$query6 = mysqli_query($link , $sql6);
					
					if(mysqli_num_rows($query6) > 0)
					{
						if($result6 = mysqli_fetch_array($query6,MYSQLI_ASSOC))
						{
							$column6 = $result6['position'];  
							$desc = $result6['description'];
							
							$columnarray = explode(';',$column6);
							
							$lat = $columnarray[0];
							$long= $columnarray[1];
							
							$final_arr = array($desc,$lat,$long);
					
						}	
					}
					else
					{
						$final_arr = "";
					}
				}
				
				if(!empty($final_arr))
				{
					$final[] = $final_arr;
				}
					
			}
			
			
			$table = $final;
			echo json_encode($table);
			
		}
	
	}
	else
	{
		$table = '';
		echo json_encode($table);
	}
	
	
	
	
	
?>
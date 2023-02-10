<?php
	
	if(!empty($_GET['key']))
	{
		$str = $_GET['key'];
	}
	else
	{
		$str = '';
	}

	if(!empty($str))
	{
		
		$apikey = substr($str,0,15);
		$deviceID = substr($str,15);
		
		//$strarray = explode(';',$str);
						
		//$apikey = $strarray[0];
		//$deviceID = $strarray[1];
						
		$apikey = Sanitize($apikey);
		$deviceID = Sanitize($deviceID);
		
		$config = parse_ini_file('../private/config.ini'); 	   
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$sql = "SELECT * FROM ".$config['tablename']." WHERE readkey = '$apikey' ";
		$query = mysqli_query($link,$sql);
		if(mysqli_num_rows($query) > 0)
		{
			if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
			{
				$user = $result['username'];
			
			}
			
			$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$deviceID."' ";
			$query2 = mysqli_query($link,$sql2);
		
			if(mysqli_num_rows($query2) > 0)
			{
				if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC) )
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
					
					if(!empty($result2['position']))
					{
						$position = $result2['position'];
						$columnarray = explode(';',$position);
						
						$lat = $columnarray[0];
						$long= $columnarray[1];
					}
					else
					{
						$position = "";
						$lat = "";
						$long = "";
					}
						
					
					$user2 = $result2['username'];
					
				}
				
				if(strcasecmp($user, $user2) == 0)
				{
					$sql3 = "SELECT * FROM ".$deviceID." ORDER BY TIMESTAMP DESC LIMIT 1";
					$query3 = mysqli_query($link,$sql3);
					
					if(mysqli_num_rows($query3) > 0 )
					{
						if($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
						{
							if(!empty($result3['column1']))
								$column1 = $result3['column1'];
							else
								$column1 = "";
							
							if(!empty($result3['column2']))
								$column2 = $result3['column2'];
							else
								$column2 = "";
							
							if(!empty($result3['column3']))
								$column3 = $result3['column3'];
							else
								$column3 = "";
							
							if(!empty($result3['column4']))
								$column4 = $result3['column4'];
							else
								$column4 = "";
							
							if(!empty($result3['column5']))
							{
								$column5 = $result3['column5'];
								
								$columnarray = explode(';',$column5);
						
								$lat = $columnarray[0];
								$long= $columnarray[1];
								
							}
							else
							{
								$column5 = "";
							}
								
							
							$timestamp = $result3['timestamp'];
							$type = "device";
						}
					}
					else
					{
						$column1 = "";
						$column2 = "";
						$column3 = "";
						$column4 = "";
						$column5 = "";
						$timestamp = "";
						$type = "device";
						
					}
					
					$body = array("column1"=>$column1,"column2"=>$column2,"column3"=>$column3,"column4"=>$column4,"latitude"=>$lat,"longitude"=>$long,"timestamp"=>$timestamp,"column1name"=>$column1name,
						"column2name"=>$column2name,"column3name"=>$column3name,"column4name"=>$column4name,"type"=>$type);
							
					echo json_encode($body);
				}
				else
				{
					header('HTTP/1.1 500 Internal Server Error');
					
					echo "1";
				}
			}
			else
			{
				$sql2 = "SELECT * FROM ".$config['tablenameswitch']." WHERE gatewayno = '".$deviceID."' ";
				$query2 = mysqli_query($link,$sql2);
				
				if(mysqli_num_rows($query2) > 0)
				{
					if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
					{
						$user2 = $result2['username']; 
					}
					
					if(strcasecmp($user, $user2) == 0)
					{
						$type = "switch";
						$column1 = "";
						$column2 = "";
						$column3 = "";
						$column4 = "";
						$column5 = "";
						$lat = "";
						$long = "";
						$timestamp = "";
						$column1name = "";
						$column2name = "";
						$column3name = "";
						$column4name = "";	
									
						
						$body = array("column1"=>$column1,"column2"=>$column2,"column3"=>$column3,"column4"=>$column4,"latitude"=>$lat,"longitude"=>$long,"timestamp"=>$timestamp,"column1name"=>$column1name,
						"column2name"=>$column2name,"column3name"=>$column3name,"column4name"=>$column4name,"type"=>$type);
							
						echo json_encode($body);
					}
					else
					{
						header('HTTP/1.1 500 Internal Server Error');
						
						echo "2";
					}
				}
				else
				{
					header('HTTP/1.1 500 Internal Server Error');
					
					echo "3";
				}
			}
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			
			echo "4";
		}		
	}
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		
		echo "5";
	}
	
	function Sanitize($str,$remove_nl=true)
    {
        $str = stripslashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
			$str = str_replace(" ","",$str);
        }

        return $str;
    } 
	
?>
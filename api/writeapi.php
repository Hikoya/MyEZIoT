<?php

	if(!empty($_GET['key']))
	{
		$str = $_GET['key'];
	}
	else
	{
		$str = '';
	}
	
	if(!empty($_GET['column1']))
	{
		$column1 = $_GET['column1'];
	}
	else
	{
		$column1 = '';
	}
	
	if(!empty($_GET['column2']))
	{
		$column2 = $_GET['column2'];
	}
	else
	{
		$column2 = '';
	}
	
	if(!empty($_GET['column3']))
	{
		$column3 = $_GET['column3'];
	}
	else
	{
		$column3 = '';
	}
	
	if(!empty($_GET['column4']))
	{
		$column4 = $_GET['column4'];
	}
	else
	{
		$column4 = '';
	}
	
	if(!empty($_GET['column5']))
	{
		$column5 = $_GET['column5'];
	}
	else
	{
		$column5 = '';
	}
	
	$user = '';
	$user2 = 's';
	
	$combo = 0000;
	$precursor = 0;
	
	if(empty($column1) && empty($column2) && empty($column3) && empty($column4))
		$combo = 0000;
	if(!empty($column1) && empty($column2) && empty($column3) && empty($column4))
		$combo = 1000;
	if(!empty($column1) && empty($column2) && empty($column3) && !empty($column4))
		$combo = 1001;
	if(!empty($column1) && empty($column2) && !empty($column3) && empty($column4))
		$combo = 1010;
	if(!empty($column1) && empty($column2) && !empty($column3) && !empty($column4))
		$combo = 1011;
	if(!empty($column1) && !empty($column2) && empty($column3) && empty($column4))
		$combo = 1100;
	if(!empty($column1) && !empty($column2) && empty($column3) && !empty($column4))
		$combo = 1101;
	if(!empty($column1) && !empty($column2) && !empty($column3) && empty($column4))
		$combo = 1110;
	if(!empty($column1) && !empty($column2) && !empty($column3) && !empty($column4))
		$combo = 1111;
	if(empty($column1) && empty($column2) && empty($column3) && !empty($column4))
		$combo = 0001;
	if(empty($column1) && empty($column2) && !empty($column3) && empty($column4))
		$combo = 0010;
	if(empty($column1) && empty($column2) && !empty($column3) && !empty($column4))
		$combo = 0011;
	if(empty($column1) && !empty($column2) && empty($column3) && empty($column4))
		$combo = 0100;
	if(empty($column1) && !empty($column2) && empty($column3) && !empty($column4))
		$combo = 0101;
	if(empty($column1) && !empty($column2) && !empty($column3) && empty($column4))
		$combo = 0110;
	if(empty($column1) && !empty($column2) && !empty($column3) && !empty($column4))
		$combo = 0111;
	
	if(!empty($column5))
		$precursor = 1;
	
	if(!empty($str) )
	{
		
		//$strarray = explode(';',$str);
						
		//$apikey = $strarray[0];
		//$deviceID = $strarray[1];
		
		$apikey = substr($str,0,15);
		$deviceID = substr($str,15);
					
		$apikey = Sanitize($apikey);
		$deviceID = Sanitize($deviceID);
		
		
		$column1 = Sanitize($column1);
		$column2 = Sanitize($column2);
		$column3 = Sanitize($column3);
		$column4 = Sanitize($column4);
		
		$config = parse_ini_file('../private/config.ini'); 	   	
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$sql = "SELECT * FROM ".$config['tablename']." WHERE writekey = '$apikey' ";
		$query = mysqli_query($link,$sql);
		if(mysqli_num_rows($query) > 0)
		{
			if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
			{
				$user = $result['username'];
			}
			
			$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$deviceID' ";
			$query2 = mysqli_query($link,$sql2);
			if(mysqli_num_rows($query2) > 0)
			{
				if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
				{
					$user2 = $result2['username'];
				}
				
				if($user2 === $user)
				{
					if($precursor == 1)
					{
						switch($combo){
							case 0000:
									$sql3 = "INSERT INTO ".$deviceID." (column5) VALUES ('".$column5."') ";
									break;
							case 0001:
									$sql3 = "INSERT INTO ".$deviceID." (column4 , column5) VALUES ('".$column4."','".$column5."') ";
									break;
							case 0010:
									$sql3 = "INSERT INTO ".$deviceID." (column3 , column5) VALUES ('".$column3."','".$column5."') ";
									break;
							case 0011:
									$sql3 = "INSERT INTO ".$deviceID." (column3,column4, column5) VALUES ('".$column3."','".$column4."','".$column5."') ";
									break;
							case 0100:
									$sql3 = "INSERT INTO ".$deviceID." (column2, column5) VALUES ('".$column2."','".$column5."') ";
									break;
							case 0101:
									$sql3 = "INSERT INTO ".$deviceID." (column2,column4, column5) VALUES ('".$column2."','".$column4."','".$column5."') ";
									break;
							case 0110:
									$sql3 = "INSERT INTO ".$deviceID." (column2,column3, column5) VALUES ('".$column2."','".$column3."','".$column5."') ";
									break;
							case 0111:
									$sql3 = "INSERT INTO ".$deviceID." (column2,column3,column4, column5) VALUES ('".$column2."','".$column3."','".$column4."','".$column5."') ";
									break;
							case 1000:
									$sql3 = "INSERT INTO ".$deviceID." (column1, column5) VALUES ('".$column1."','".$column5."') ";
									break;
							case 1001:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column4, column5) VALUES ('".$column1."','".$column4."','".$column5."') ";
									break;
							case 1010:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column3, column5) VALUES ('".$column1."','".$column3."','".$column5."') ";
									break;
							case 1011:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column3,column4, column5) VALUES ('".$column1."','".$column3."','".$column4."','".$column5."') ";
									break;
							case 1100:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column2, column5) VALUES ('".$column1."','".$column2."','".$column5."') ";
									break;
							case 1101:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column4, column5) VALUES ('".$column1."','".$column2."','".$column4."','".$column5."') ";
									break;
							case 1110:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column3, column5) VALUES ('".$column1."','".$column2."','".$column3."','".$column5."') ";
									break;
							case 1111:
									$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column3,column4, column5) VALUES ('".$column1."','".$column2."','".$column3."','".$column4."','".$column5."') ";
									break;	
						}
					}
					else
					{
						switch($combo){
						case 0001:
								$sql3 = "INSERT INTO ".$deviceID." (column4) VALUES ('".$column4."') ";
								break;
						case 0010:
								$sql3 = "INSERT INTO ".$deviceID." (column3) VALUES ('".$column3."') ";
								break;
						case 0011:
								$sql3 = "INSERT INTO ".$deviceID." (column3,column4) VALUES ('".$column3."','".$column4."') ";
								break;
						case 0100:
								$sql3 = "INSERT INTO ".$deviceID." (column2) VALUES ('".$column2."') ";
								break;
						case 0101:
								$sql3 = "INSERT INTO ".$deviceID." (column2,column4) VALUES ('".$column2."','".$column4."') ";
								break;
						case 0110:
								$sql3 = "INSERT INTO ".$deviceID." (column2,column3) VALUES ('".$column2."','".$column3."') ";
								break;
						case 0111:
								$sql3 = "INSERT INTO ".$deviceID." (column2,column3,column4) VALUES ('".$column2."','".$column3."','".$column4."') ";
								break;
						case 1000:
								$sql3 = "INSERT INTO ".$deviceID." (column1) VALUES ('".$column1."') ";
								break;
						case 1001:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column4) VALUES ('".$column1."','".$column4."') ";
								break;
						case 1010:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column3) VALUES ('".$column1."','".$column3."') ";
								break;
						case 1011:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column3,column4) VALUES ('".$column1."','".$column3."','".$column4."') ";
								break;
						case 1100:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column2) VALUES ('".$column1."','".$column2."') ";
								break;
						case 1101:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column4) VALUES ('".$column1."','".$column2."','".$column4."') ";
								break;
						case 1110:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column3) VALUES ('".$column1."','".$column2."','".$column3."') ";
								break;
						case 1111:
								$sql3 = "INSERT INTO ".$deviceID." (column1,column2,column3,column4) VALUES ('".$column1."','".$column2."','".$column3."','".$column4."') ";
								break;	
						default:
								header('HTTP/1.1 500 Internal Server Error');
								break;
						}
					}
					
			
					$query3 = mysqli_query($link,$sql3);
					
				}
				else
				{
					header('HTTP/1.1 500 Internal Server Error');
				}
			}
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
		}
	}
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
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
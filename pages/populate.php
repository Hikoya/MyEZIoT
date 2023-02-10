<?php
	
	if(!empty($_POST['serialno']))
		$serialno = $_POST['serialno'];
	else
		$serialno = '';
	
	if(!empty($_POST['switches']))
		$switch = $_POST['switches'];
	else
		$switch = '';
	
	if(!empty($_POST['column']))
		$column = $_POST['column'];
	else
		$column = '';
	
	if(!empty($serialno) && empty($column)) //set col name
	{
		$username = '';
		$serialno = Sanitize($serialno);
		
		require_once("../include/membersite_config.php");
		if(!$fgmembersite->CheckLogin())
		{
			exit;
		}
		else
			$username = $_SESSION['username_of_user'];
		
		if(!empty($username))
		{			
			$config = parse_ini_file("../private/config.ini");
			$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
			
			$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$serialno."' ";
			$query = mysqli_query($link,$sql);
			if(mysqli_num_rows($query) > 0 )
			{
				if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$user = $result['username'];
					
					if($user === $username)
					{
						$description = $result['description'];
						$location = $result['location'];
						
						if(!empty($result['column1']))
							$column1 = $result['column1'];
						else
							$column1 = "";
						
						if(!empty($result['column2']))
							$column2 = $result['column2'];
						else
							$column2 = "";
						
						if(!empty($result['column3']))
							$column3 = $result['column3'];
						else
							$column3 = "";
						
						if(!empty($result['column4']))
							$column4 = $result['column4'];
						else
							$column4 = "";
						
						if(!empty($result['column5']))
							$column5 = $result['column5'];
						else
							$column5 = "";
						
						if(!empty($result['column6']))
							$column6 = $result['column6'];
						else
							$column6 = "";
						
						if(!empty($result['position']))
						{
							$position = $result['position'];
							
							$columnarray = explode(';',$position);
							
							$lat = $columnarray[0];
							$long= $columnarray[1];
						}
							
						else
						{
							$lat = "";
							$long = "";
						}

						$json_result = array("description" => $description , "location" => $location , "column1" => $column1 , "column2" => $column2 , "column3" => $column3 , "column4" => $column4 , "column5" => $column5 , "column6" => $column6 , "latitude" => $lat , "longitude" => $long);
						
						echo json_encode($json_result);
					}
					
				}
			}
		}
	}
	else if(!empty($serialno) && !empty($column)) //set threshold
	{
		$username = '';
		$serialno = Sanitize($serialno);
		$column = Sanitize($column);
		
		require_once("../include/membersite_config.php");
		if(!$fgmembersite->CheckLogin())
		{
			exit;
		}
		else
			$username = $_SESSION['username_of_user'];
		
		if(!empty($username))
		{			
			$config = parse_ini_file("../private/config.ini");
			$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
			
			$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$serialno."' ";
			$query = mysqli_query($link,$sql);
			if(mysqli_num_rows($query) > 0 )
			{
				if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$user = $result['username'];
					
					if($user === $username)
					{
						if($column == "column1")
						{
							if(!empty($result['threshold1']))
								$threshold = $result['threshold1'];
							else
								$threshold = "";
						}
						else if($column == "column2")
						{
							if(!empty($result['threshold2']))
								$threshold = $result['threshold2'];
							else
								$threshold = "";
						}
						else if($column == "column3")
						{
							if(!empty($result['threshold3']))
								$threshold = $result['threshold3'];
							else
								$threshold = "";
						}
						else if($column == "column4")
						{
							if(!empty($result['threshold4']))
								$threshold = $result['threshold4'];
							else
								$threshold = "";
						}
						
						
					
						$json_result = array("threshold" => $threshold);
						
						echo json_encode($json_result);
					}
					
				}
			}
		}
	}
	else if(!empty($switch)) //set switch
	{
		$username = '';
		$switch = Sanitize($switch);
		
		require_once("../include/membersite_config.php");
		if(!$fgmembersite->CheckLogin())
		{
			exit;
		}
		else
			$username = $_SESSION['username_of_user'];
		
		if(!empty($username))
		{			
			$config = parse_ini_file("../private/config.ini");
			$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
			
			$sql = "SELECT * FROM ".$config['tablenameswitch']." WHERE gatewayno = '".$switch."' ";
			$query = mysqli_query($link,$sql);
			if(mysqli_num_rows($query) > 0 )
			{
				if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$user = $result['username'];
					
					if($user === $username)
					{
							
						if(!empty($result['description']))
							$description = $result['description'];
						else
							$description = "NULL";
						
						$json_result = array("description" => $description );
						
						echo json_encode($json_result);
					}
					
				}
			}
		}
	}
	else
		header('HTTP/1.1 500 Internal Server Error');
	
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
        }

        return $str;
    }   
	
	
?>
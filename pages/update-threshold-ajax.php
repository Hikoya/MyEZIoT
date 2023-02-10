<?php

	if(!empty($_POST['column']))
	{
		$column = $_POST['column'];	
	}
	else
		$column = '';
	
	if(!empty($_POST['id']))
	{
		$serialno = $_POST['id'];
	}
	else
		$serialno = '';
	
	$threshold = '';
	$threshold = $_POST['threshold'];
	
	
	if(!empty($column) && !empty($serialno))
	{
		require_once("../include/membersite_config.php");

		$username = '';
		
		if($fgmembersite->CheckLogin())
		{
			$username = $_SESSION['username_of_user'];
		}
		
		if(!empty($username))
		{
			$config = parse_ini_file('../private/config.ini'); 	 
			$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
			
			$serialno = mysqli_real_escape_string($link , $serialno);
			$column = mysqli_real_escape_string($link , $column);
			$threshold = mysqli_real_escape_string($link , $threshold);
			
			$serialno = Sanitize($serialno);
			$column = Sanitize($column);
			$threshold = Sanitize($threshold);
			
			$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$serialno' ";
			$query = mysqli_query($link,$sql);
			if(mysqli_num_rows($query) > 0)
			{
				if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$user = $result['username'];
				}
				
				if($user === $username)
				{		
					if($column == "column1")
					{
						if(!empty($threshold))
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold1 = '$threshold'  WHERE gatewayno = '$serialno' ";
						else
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold1 = NULL  WHERE gatewayno = '$serialno' ";
					}
						
					else if($column == "column2")
					{
						if(!empty($threshold))
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold2 = '$threshold'  WHERE gatewayno = '$serialno' ";
						else
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold2 = NULL  WHERE gatewayno = '$serialno' ";
					}
					else if($column == "column3")
					{
						if(!empty($threshold))
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold3 = '$threshold'  WHERE gatewayno = '$serialno' ";
						else
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold3 = NULL  WHERE gatewayno = '$serialno' ";
					}
					else if($column == "column4")
					{
						if(!empty($threshold))
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold4 = '$threshold'  WHERE gatewayno = '$serialno' ";
						else
							$sql2 = "UPDATE ".$config['tablenamenode']." SET threshold4 = NULL  WHERE gatewayno = '$serialno' ";
					}
					
					$query2 = mysqli_query($link,$sql2);	
				}
				else
				{
					header('HTTP/1.1 500 Internal Server Error');
					print "Device does not belong to user";
				}

			}
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
				print "No such device";
			}
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			print "Session timed out, please login again.";
		}
		
		
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
        }

        return $str;
    }    
	
  
	
?>
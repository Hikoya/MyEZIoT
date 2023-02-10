<?php
	
	if(!empty($_POST['switches']))
	{
		$switches = $_POST['switches'];
	}
	else
		$switches = '';
	
	if(!empty($_POST['node']))
	{
		$node = $_POST['node'];
	}
	else
		$node = '';
	
	if(!empty($_POST['command']))
	{
		$command = $_POST['command'];
	}
	else
		$command = '';
	

	if(!empty($switches) && !empty($node))
	{
		require_once("../include/membersite_config.php");

		$username = '';
		$user = '';
		$user2 = '';
		
		$node_exist = 0;
		$node = Sanitize($node);
		$switches = Sanitize($switches);
		$command = Sanitize($command);
		
		if($fgmembersite->CheckLogin())
		{
			$username = $_SESSION['username_of_user'];
		}
		
		$config = parse_ini_file('../private/config.ini'); 
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		if($node != "delete")
		{
			$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$node."' ";
			$query = mysqli_query($link,$sql);
			if(mysqli_num_rows($query) > 0)
			{
				if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$user = $result['username'];
				}
				
				$node_exist = 1;
			}
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
			}
		}
		
		$sql2 = "SELECT * FROM ".$config['tablenameswitch']." WHERE gatewayno = '".$switches."' ";
		$query2 = mysqli_query($link,$sql2);
		if(mysqli_num_rows($query2) > 0)
		{
			if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
			{
				$user2 = $result2['username'];
			}
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
		}
		
		if($username == $user2)
		{
			if($node_exist == 1 && $username == $user && ($command == "ON" || $command == "OFF"))
			{
				$sql3 = "UPDATE ".$config['tablenameswitch']." SET threshold = '$node' , command = '$command' WHERE gatewayno = '$switches' ";
			}	
			else	
				$sql3 = "UPDATE ".$config['tablenameswitch']." SET threshold = NULL , command = NULL WHERE gatewayno = '$switches' ";
			
			$query3 = mysqli_query($link,$sql3);
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
        }

        return $str;
    }   
	
?>
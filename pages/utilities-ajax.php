<?php

    require_once("../include/membersite_config.php");
	
	if($fgmembersite->CheckLogin())
	{
		$user = $_SESSION['username_of_user'];
	}
	else
		$user = "";
	
	if(!empty($_POST['serialno']))
	{
		$serialno = $_POST['serialno'];	
	}
	else
		$serialno = '';
	
	if(!empty($_POST['date']))
	{
		$date = $_POST['date'];	
	}
	else
		$date = '';
	
	if(!empty($_POST['range']))
	{
		$range = $_POST['range'];	
	}
	else
		$range = '';
		
	if(!empty($serialno) && !empty($date) && empty($range))
	{
		$config = parse_ini_file('../private/config.ini'); 	 
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$admin = $config['adminname'];
			
		$serialno = mysqli_real_escape_string($link , $serialno);
		$date = mysqli_real_escape_string($link , $date);
			
		$serialno = Sanitize($serialno);
		$date = Sanitize($date);
		
		
		if((strcasecmp($admin, $user) == 0))
		{
			$sql = "DELETE FROM ".$serialno." WHERE timestamp < '".$date." 00:00:00' ";
			$query = mysqli_query($link,$sql);
			echo "success";
			exit;
		}
		else
		{
			echo "bloody hell";
			exit;
		}
		
			
	}
	
	if(!empty($serialno) && empty($date) && !empty($range))
	{
		$config = parse_ini_file('../private/config.ini'); 	 
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$admin = $config['adminname'];
			
		$serialno = mysqli_real_escape_string($link , $serialno);
		$range = mysqli_real_escape_string($link , $range);
			
		$serialno = Sanitize($serialno);
		$range = Sanitize($range);
		
		if((strcasecmp($admin, $user) == 0))
		{
			$sql = "DELETE FROM ".$serialno." ORDER BY TIMESTAMP ASC LIMIT ".$range." ";
			$query = mysqli_query($link,$sql);
			echo "success";
			exit;
		}
		else
		{
			echo "bloody hell";
			exit;
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
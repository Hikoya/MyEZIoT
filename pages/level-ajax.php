<?php

    require_once("../include/membersite_config.php");
	
	if(!empty($_POST['username']))
	{
		$username = $_POST['username'];	
	}
	else
		$username = '';
	
	if(!empty($_POST['level']))
	{
		$level = $_POST['level'];	
	}
	else
		$level = '';
	
	$user = '';
	
	if($fgmembersite->CheckLogin())
	{
		$user = $_SESSION['username_of_user'];
	}
	
	
	if(!empty($username) && !empty($level))
	{
		$config = parse_ini_file('../private/config.ini'); 	 
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$admin = $config['adminname'];
			
		$username = mysqli_real_escape_string($link , $username);
		$level = mysqli_real_escape_string($link , $level);
			
		$username = Sanitize($username);
		$level = Sanitize($level);
		$level = (int)$level;
		
		
		if(strcasecmp($admin, $user) == 0)
		{
			$sql = "UPDATE ".$config['tablename']." SET level = '".$level."' WHERE username = '".$username."' ";
			$query = mysqli_query($link,$sql);
			echo "success";
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
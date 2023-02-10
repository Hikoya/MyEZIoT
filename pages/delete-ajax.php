<?php

    require_once("../include/membersite_config.php");
	
	if(!empty($_POST['username']))
	{
		$username = $_POST['username'];	
	}
	else
		$username = '';
	
	
	$user = '';
	
	if($fgmembersite->CheckLogin())
	{
		$user = $_SESSION['username_of_user'];
	}
	
	
	if(!empty($username))
	{
		$config = parse_ini_file('../private/config.ini'); 	 
		$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		
		$admin = $config['adminname'];
			
		$username = mysqli_real_escape_string($link , $username);			
		$username = Sanitize($username);
		
		if(strcasecmp($admin, $user) == 0)
		{
			$sql = "DELETE FROM ".$config['tablename']." WHERE username = '".$username."' ";
			$query = mysqli_query($link,$sql);
			
			$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE username = '".$username."' ";
			$query2 = mysqli_query($link,$sql2);
			
			if(mysqli_num_rows($query2) > 0)
			{
				while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
				{
					$gatewaynoarray[] = $result2['gatewayno'];	
				}
				
				foreach($gatewaynoarray as $key => $value)
				{
					$sql3 = "DROP TABLE ".$value." ";
					$query3 = mysqli_query($link,$sql3);
				}
			}
			
			$sql4 = "DELETE FROM ".$config['tablenamenode']." WHERE username = '".$username."' ";
			$query4 = mysqli_query($link,$sql4);
			
			$sql5 = "DELETE FROM ".$config['tablenameswitch']." WHERE username = '".$username."' ";
			$query5 = mysqli_query($link,$sql5);
			
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
<?php
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
	if(!empty($_GET['userID']))
	{
		$userID = $_GET['userID'];
	}
	else
		$userID = '';
	
	if(!empty($userID))
	{
		$userID = Sanitize($userID);
		$sql = "SELECT * FROM ".$config['tablenamenode']." where username = '$userID' ";
	}
	else
		$sql = "SELECT * FROM ".$config['tablenamenode']." ";
	
	$query = mysqli_query($link,$sql);
	
	$deviceID2 = '';	
	if(mysqli_num_rows($query) > 0)
	{
		while($result = mysqli_fetch_array($query,MYSQLI_ASSOC) )
		{
			$deviceID2 = $deviceID2 . $result['gatewayno'] . ';' ;
		}
	}
	
	if(!empty($userID))
		$sql2 = "SELECT * FROM ".$config['tablenameswitch']." where username = '$userID' ";
	else
		$sql2 = "SELECT * FROM ".$config['tablenameswitch']."  ";
	
	$query2 = mysqli_query($link,$sql2);
	
	if(mysqli_num_rows($query2) > 0)
	{
		while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC) )
		{
			$deviceID2 = $deviceID2 . $result2['gatewayno'] . ';';
		}
	}
	$deviceID2 = rtrim($deviceID2,';');
	$deviceID2 = str_replace(" ","",$deviceID2);
	echo $deviceID2;

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
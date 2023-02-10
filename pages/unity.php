
<?php

header('Content-type: text/plain; charset=utf-8');

if(!empty($_GET['deviceID']))
{
	$deviceID = $_GET['deviceID'];
}
else
{
	$deviceID = '';
}

if(!empty($_GET['userID']))
{
	$userID = $_GET['userID'];
}
else
{
	$userID = '';
}

if(!empty($_GET['key']))
{
	$key = $_GET['key'];
}
else
{
	$key = '';
}


if(!empty($deviceID) && empty($userID) && empty($key))
{
	$config = parse_ini_file('../private/config.ini'); 	   
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$deviceID."' ";
	$query = mysqli_query($link,$sql);
	
	if(mysqli_num_rows($query) > 0)
	{
		if($result = mysqli_fetch_array($query,MYSQLI_ASSOC) )
		{
			$column1name = $result['column1'];
			$column2name = $result['column2'];
		}
		
		
		$sql2 = "SELECT * FROM ".$deviceID." ORDER BY TIMESTAMP DESC LIMIT 1";
		$query2 = mysqli_query($link,$sql2);
		
		if(mysqli_num_rows($query2) > 0 )
		{
			if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
			{
				$column1 = $result2['column1'];
				$column2 = $result2['column2'];
				$timestamp = $result2['timestamp'];
				$type = "device";
			}
		}
		else
		{
			$column1 = "NULL";
			$column2 = "NULL";
			$timestamp = "NULL";
			$type = "device";
			
		}
	}
	else
	{
		$sql2 = "SELECT * FROM ".$config['tablenameswitch']." WHERE gatewayno = '".$deviceID."' ";
		$query2 = mysqli_query($link,$sql2);
		
		if(mysqli_num_rows($query2) > 0)
		{
			$type = "switch";
			$column1 = "NULL";
			$column2 = "NULL";
			$timestamp = "NULL";
			$column1name = "NULL";
			$column2name = "NULL";
		}
		else
		{
			$type = "NULL";
			$column1 = "NULL";
			$column2 = "NULL";
			$timestamp = "NULL";
			$column1name = "NULL";
			$column2name = "NULL";		
		}
	
	}
	

	$reply = "Column1:$column2,Column2:$column1,Timestamp:$timestamp,Column1name:$column1name,Column2name:$column2name,Type:$type;";
	
	echo $reply;
}
else if( !empty($deviceID) && !empty($userID) && empty($key) )
{	
	
	$config = parse_ini_file("../private/config.ini");
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
	$sql3 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$deviceID' ";
	$query3 = mysqli_query($link,$sql3);
	if(mysqli_num_rows($query3) > 0)
	{
		$sql = "SELECT * FROM ".$deviceID." ORDER BY TIMESTAMP DESC LIMIT 1";
		$query = mysqli_query($link,$sql);
	
		if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			$data1 = $result['column1'];
			$data2 = $result['column2'];
			$timestamp = $result['timestamp'];
			$type = "device";
		}
		
		$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$deviceID' ";
		$query2 = mysqli_query($link,$sql2);
		if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
		{
			$column1 = $result2['column1'];
			$column2 = $result2['column2'];
			
			$username = $result2['username'];
		}
		
		if(strcasecmp($userID, $username) == 0)
		{		
			
			$reply = "Column1:$data1,Column2:$data2,Timestamp:$timestamp,Column1name:$column1,Column2name:$column2,Type:$type;";
			echo $reply;
		}
		else
		{
			$reply = "fail";
			echo $reply;
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
				$username = $result2['username'];
				
				
			}
		
			if(strcasecmp($username , $userID) == 0)
			{
				
				$type = "switch";
				$column1 = "NULL";
				$column2 = "NULL";
				$timestamp = "NULL";
				$column1name = "NULL";
				$column2name = "NULL";
				
				$reply = "Column1:$column2,Column2:$column1,Timestamp:$timestamp,Column1name:$column1name,Column2name:$column2name,Type:$type;";
				echo $reply;
			}
			else
			{
				$reply = "fail";
				echo $reply;
			}
		}
		else
		{
			$type = "NULL";
			$column1 = "NULL";
			$column2 = "NULL";
			$timestamp = "NULL";
			$column1name = "NULL";
			$column2name = "NULL";		
			
			$reply = "fail";
			echo $reply;
		}
		
		
	}
}
else if( !empty($deviceID) && !empty($key) && empty($userID) )
{	
	
	$config = parse_ini_file("../private/config.ini");
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
	$keyuserID = "";
	$username = "a";
	
	$sql3 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$deviceID' ";
	$query3 = mysqli_query($link,$sql3);
	if(mysqli_num_rows($query3) > 0)
	{
		$sql = "SELECT * FROM ".$deviceID." ORDER BY TIMESTAMP DESC LIMIT 1";
		$query = mysqli_query($link,$sql);
	
		if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			$data1 = $result['column1'];
			$data2 = $result['column2'];
			$timestamp = $result['timestamp'];
			$type = "device";
		}
		
		$sql2 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '$deviceID' ";
		$query2 = mysqli_query($link,$sql2);
		if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
		{
			$column1 = $result2['column1'];
			$column2 = $result2['column2'];
			
			$username = $result2['username'];
		}
		
		$sql4 = "SELECT * FROM ".$config['tablename']." WHERE readkey = '$key' ";
		$query4 = mysqli_query($link,$sql4);
		if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
		{
			
			$keyuserID = $result4['username'];
		}
		
		
		if(strcasecmp($keyuserID, $username) == 0)
		{		
			$reply = "Column1:$data1,Column2:$data2,Timestamp:$timestamp,Column1name:$column1,Column2name:$column2,Type:$type;";
			echo $reply;
		}
		else
		{
			$reply = "fail";
			echo $reply;
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
				$username = $result2['username'];
				
				
			}
		
			$sql4 = "SELECT * FROM ".$config['tablename']." WHERE readkey = '$key' ";
			$query4 = mysqli_query($link,$sql4);
			if($result4 = mysqli_fetch_array($query4,MYSQLI_ASSOC))
			{
				
				$keyuserID = $result4['username'];
			}
			
			if(strcasecmp($username , $keyuserID) == 0)
			{
				
				$type = "switch";
				$column1 = "NULL";
				$column2 = "NULL";
				$timestamp = "NULL";
				$column1name = "NULL";
				$column2name = "NULL";
				
				$reply = "Column1:$column2,Column2:$column1,Timestamp:$timestamp,Column1name:$column1name,Column2name:$column2name,Type:$type;";
				echo $reply;
			}
			else
			{
				$reply = "fail";
				echo $reply;
			}
		}
		else
		{
			$type = "NULL";
			$column1 = "NULL";
			$column2 = "NULL";
			$timestamp = "NULL";
			$column1name = "NULL";
			$column2name = "NULL";		
			
			$reply = "fail";
			echo $reply;
		}
		
	
	}
	
	
	
}
else
	echo "error";





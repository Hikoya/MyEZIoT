<?php

date_default_timezone_set("Asia/Singapore");

$config = parse_ini_file('../private/config.ini'); 	 
$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']); //connect to db

$threshold = (int)$config['threshold']; //num of times it will repeat before determined to be over threshold
$limit = (int)$config['limit']; //monitor last x of values rows

$smsinterval = (int)$config['smsinterval']; //check if last sms sent
$interval = (int)$config['interval']; //for not double-sending sms

$sql = "SELECT * FROM ".$config['tablenamenode']." ";

$query = mysqli_query($link,$sql); 
if(mysqli_num_rows($query) > 0) //check if any node exist
{
	while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$gatewayarray[] = $result['gatewayno'];	
	}
	
	foreach($gatewayarray as $key => $value)
	{
		$currtimestamp = strtotime(date("Y-m-d H:i:s"));
		
		$average1 = 0;
		$count1 = 0;
		
		$average2 = 0;
		$count2 = 0;
		
		$average3 = 0;
		$count3 = 0;
		
		$average4 = 0;
		$count4 = 0;
		
		$lastsms = 0;
		
		$column1threshold = '';
		$column2threshold = '';
		$column3threshold = '';
		$column4threshold = '';
		
		$is_present1 = 0;
		$is_present2 = 0;
		$is_present3 = 0;
		$is_present4 = 0;
		
		//Get username and individual threshold of the node registered
		
		$sql1 = "SELECT * FROM ".$config['tablenamenode']." where gatewayno = '".$value."' ";
		$query1 = mysqli_query($link,$sql1);
		if($result1 = mysqli_fetch_array($query1,MYSQLI_ASSOC))
		{
			if(!empty($result1['threshold1']))
				$column1threshold = (float)$result1['threshold1'];
			else
				$column1threshold = '';
			
			if(!empty($result1['threshold2']))
				$column2threshold = (float)$result1['threshold2'];
			else
				$column2threshold = '';
			
			if(!empty($result1['threshold3']))
				$column3threshold = (float)$result1['threshold3'];
			else
				$column3threshold = '';
			
			if(!empty($result1['threshold4']))
				$column4threshold = (float)$result1['threshold4'];
			else
				$column4threshold = '';
			
			$lastsms = (int)$result1['smstime'];
		}
		
		if(!empty($column1threshold))
		{
			$sql2 = "SELECT * FROM " . $value . " WHERE column1 IS NOT NULL ORDER BY timestamp DESC LIMIT ".$limit."";
			$query2 = mysqli_query($link, $sql2);
			if(mysqli_num_rows($query2) > 0)
			{
				$is_present1 = 1;
				
				while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
				{
					$column1 = (float)$result2['column1'];
					$average1 = $average1 + $column1;
					$timestamp1 = $result2['timestamp'];
					
					if($column1 >= $column1threshold)
					{
						$count1  =  $count1 + 1;
					}
				}
			}
		}
		
		if(!empty($column2threshold))
		{
			$sql3 = "SELECT * FROM " . $value . " WHERE column2 IS NOT NULL ORDER BY timestamp DESC LIMIT ".$limit."";
			$query3 = mysqli_query($link, $sql3);
			if(mysqli_num_rows($query3) > 0)
			{
				$is_present2 = 1;
				
				while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
				{
					$column2 = (float)$result3['column2'];
					$average2 = $average2 + $column2;
					$timestamp2 = $result3['timestamp'];
					
					if($column2 >= $column2threshold)
					{
						$count2 = $count2 + 1;
					}
				}
			}
		}
		
		if(!empty($column3threshold))
		{
			$sql3 = "SELECT * FROM " . $value . " WHERE column3 IS NOT NULL ORDER BY timestamp DESC LIMIT ".$limit."";
			$query3 = mysqli_query($link, $sql3);
			if(mysqli_num_rows($query3) > 0)
			{
				$is_present3 = 1;
				
				while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
				{
					$column3 = (float)$result3['column3'];
					$average3 = $average3 + $column3;
					$timestamp3 = $result3['timestamp'];
					
					if($column3 >= $column3threshold)
					{
						$count3 = $count3 + 1;
					}
				}
			}
		}
		
		if(!empty($column4threshold))
		{
			$sql3 = "SELECT * FROM " . $value . " WHERE column4 IS NOT NULL ORDER BY timestamp DESC LIMIT ".$limit."";
			$query3 = mysqli_query($link, $sql3);
			if(mysqli_num_rows($query3) > 0)
			{
				$is_present4 = 1;
				
				while($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
				{
					$column4 = (float)$result3['column4'];
					$average4 = $average4 + $column4;
					$timestamp4 = $result3['timestamp'];
					
					if($column4 >= $column4threshold)
					{
						$count4 = $count4 + 1;
					}
				}
			}
		}
		
		//echo nl2br("Gateway : $value"."\n");
		
		//echo nl2br("Count 1 : $count1"."\n");
		//echo nl2br("Count 2 : $count2"."\n");
		//echo nl2br("Count 3 : $count3"."\n");
		//echo nl2br("Count 4 : $count4"."\n");
		
		//echo nl2br("Column 1: $column1threshold"."\n");
		//echo nl2br("Column 2: $column2threshold"."\n");
		//echo nl2br("Column 3: $column3threshold"."\n");
		//echo nl2br("Column 4: $column4threshold"."\n");
		
		$column = 0;
		
		if(!empty($column1threshold) && ($is_present1 == 1))
		{
			$average1 = $average1 / $limit;
			$finaltimestamp1 = strtotime($timestamp1);
			$difftimestamp1 = $currtimestamp - $finaltimestamp1;
			$smstimestamp1 = $currtimestamp - $lastsms;
			
			//echo nl2br("DiffTimestamp1 : $difftimestamp1"."\n");
			//echo nl2br("SMSTimestamp1 : $smstimestamp1"."\n");
			
			if(($count1 >= $threshold) && ($difftimestamp1 <= $interval) && ($smstimestamp1 >= $smsinterval))
			{
				$column = 1;
				getInfo($value,$average1,$column,$timestamp1);
				
				//echo "SMS1";
			}
			
		}
		
		if(!empty($column2threshold) && ($is_present2 == 1))
		{
			$average2 = $average2 / $limit;
			$finaltimestamp2 = strtotime($timestamp2);
			$difftimestamp2 = $currtimestamp - $finaltimestamp2;
			$smstimestamp2 = $currtimestamp - $lastsms;
			
			//echo nl2br("DiffTimestamp2 : $difftimestamp2"."\n");
			//echo nl2br("SMSTimestamp2 : $smstimestamp2"."\n");
			
			if(($count2 >= $threshold) && ($difftimestamp2 <= $interval) && ($smstimestamp2 >= $smsinterval))
			{
				$column = 2;
				getInfo($value,$average2,$column,$timestamp2);
				
				//echo "SMS2";
			}
		
		}
		
		if(!empty($column3threshold) && ($is_present3 == 1))
		{
			$average3 = $average3 / $limit;
			$finaltimestamp3 = strtotime($timestamp3);
			$difftimestamp3 = $currtimestamp - $finaltimestamp3;
			$smstimestamp3 = $currtimestamp - $lastsms;
			
			//echo nl2br("DiffTimestamp3 : $difftimestamp3"."\n");
			//echo nl2br("SMSTimestamp3 : $smstimestamp3"."\n");
			
			if(($count3 >= $threshold) && ($difftimestamp3 <= $interval) && ($smstimestamp3 >= $smsinterval))
			{
				$column = 3;
				getInfo($value,$average2,$column,$timestamp3);
				
				//echo "SMS3";
			}
		
		}
		
		if(!empty($column4threshold) && ($is_present4 == 1))
		{
			$average4 = $average4 / $limit;
			$finaltimestamp4 = strtotime($timestamp4);
			$difftimestamp4 = $currtimestamp - $finaltimestamp4;
			$smstimestamp4 = $currtimestamp - $lastsms;
			
			//echo nl2br("DiffTimestamp4 : $difftimestamp4"."\n");
			//echo nl2br("SMSTimestamp4 : $smstimestamp4"."\n");
			
			if(($count4 >= $threshold) && ($difftimestamp4 <= $interval) && ($smstimestamp4 >= $smsinterval))
			{
				$column = 4;
				getInfo($value,$average2,$column,$timestamp4);
				
				//echo "SMS4";
			}
		}
			
	}
}
	

function sendSMS($code,$table,$mobileno,$avgsum,$location,$column,$timestamp)
{
	$current = strtotime(date("Y-m-d H:i:s"));
	
	$config = parse_ini_file('../private/config.ini'); 	 
	$topic = $config['smstopic'];
	$sitename = $config['sitename'];
	$message = "[Notice] $column over threshold , value $avgsum at $location in $sitename. Last updated $timestamp";
	
	$mobileno = rtrim($mobileno, ";");
	$array = explode(';',$mobileno);
		
	foreach ($array as $key => $value)
	{
		$mobile = $code.$value;
	
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://myeziot.com/sms");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
					"topic=".$topic."&mobileno=".$mobile."&msg=".$message."");

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		echo $result;
		curl_close ($ch);
	}

	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']); //connect to db
	$sql = "UPDATE ".$config['tablenamenode']." SET smstime = '".$current."' WHERE gatewayno = '".$table."' ";
	$query = mysqli_query($link,$sql);
	
	//echo "SMS Sent";

}

function sendEMAIL($email,$username,$table,$avgsum,$location,$column,$timestamp)
{
	require_once("../include/membersite_config.php");
	$message = "$column over threshold for sensor $table, value $avgsum at $location. Last updated $timestamp";
	
	$email = rtrim($email, ";");
	$email_array = explode(';',$email);
	
	foreach ($email_array as $key => $value)
	{
		$fgmembersite->SendUserAlertEmail($value,$username,$message);
	}
}

function getInfo($v,$c,$t,$timestamp)
{
	$table = $v;
	$avgsum = $c;
	$type = $t;
	
	$config = parse_ini_file('../private/config.ini'); 	
	$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
	
	$sql = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$table."' ";
	$query = mysqli_query($link, $sql);
	
	if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$username_target = $result['username'];
		$location = $result['location'];
		
		if($type == 1)
			$column = $result['column1'];
		
		if($type == 2)
			$column = $result['column2'];
		
		if($type == 3)
			$column = $result['column3'];
		
		if($type == 4)
			$column = $result['column4'];
	}
	
	$sql2 = "SELECT * FROM ".$config['tablename']." WHERE username = '".$username_target."' ";
	$query2 = mysqli_query($link, $sql2);
	if($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
	{
		$email = $result2['smsemail'];
		$mobileno = $result2['smsmobileno'];
		$code = $result2['callingcode'];
	}
	
	sendEMAIL($email,$username_target,$table,$avgsum,$location,$column,$timestamp);
	sendSMS($code,$table,$mobileno,$avgsum,$location,$column,$timestamp);
}



?>
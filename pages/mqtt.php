<?php

if(!empty($_GET['topic']))
{
	$topic = trim($_GET['topic']);
}
else
{
	$topic = '';
}

if(!empty($_GET['msg']))
{
	$msg = trim($_GET['msg']); 
}
else
{
	$msg = '';
}

if(!empty($topic) && !empty($msg) )
{
	require_once('../include/mqtt-lib.php');

	$config = [
		'aws_iot_host'          	=> "A1QNCE7GB8FLEF.iot.ap-southeast-1.amazonaws.com",
		'aws_iot_port'				=> 	8883,
		'aws_iot_cafile'        	=> "../private/rootca.pem",
		'aws_iot_crtfile'       	=> "../private/cert.crt",
		'aws_iot_private_keyfile'	=> "../private/priv.key",
	];

	// Init MQTT Library
	$mqtt = new libmqtt\client($config['aws_iot_host'], $config['aws_iot_port'], uniqid() );
	$mqtt->setClientCert($config['aws_iot_crtfile'], $config['aws_iot_private_keyfile']);
	$mqtt->setCAFile($config['aws_iot_cafile']);
	$mqtt->setCryptoProtocol('ssl');
	$mqtt->setVerbose(1);
	
	// Try to connect
	if (!$mqtt->connect())
	{
		die('0'.'');
	}

	//$reply = $mqtt->publish($topic, $msg, 0);
	
	$topics['NodeRed/Testing2'] = array("qos" => 0, "function" => "procmsg");
	$data = $mqtt->subscribe($topics);

	// Close the connection
	//$mqtt->close();
	
	echo $data;
	
}


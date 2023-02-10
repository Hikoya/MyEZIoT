<?php

if(!empty($_POST['fullname']))
{
	$fullname = $_POST['fullname'];
}
else
	$fullname = '';

if(!empty($_POST['emailaddress']))
{
	$email = $_POST['emailaddress'];
}
else
	$email = '';

if(!empty($_POST['username']))
{
	$user = $_POST['username'];
}
else
	$user = '';

if(!empty($_POST['password']))
{
	$password = $_POST['password'];
}
else
	$password = '';


if(!empty($_POST['postaladdress']))
{
	$postaladdress = $_POST['postaladdress'];
}
else
	$postaladdress = '';

if(!empty($_POST['postalcode']))
{
	$postalcode = $_POST['postalcode'];
}
else
	$postalcode = '';

if(!empty($_POST['internationalcountrycode']))
{
	$internationalcountrycode = $_POST['internationalcountrycode'];
}
else
	$internationalcountrycode = '';

if(!empty($_POST['mobilenumber']))
{
	$mobilenumber = $_POST['mobilenumber'];
}
else
	$mobilenumber = '';

if(!empty($fullname) && !empty($email) && !empty($user) && !empty($password) && !empty($internationalcountrycode) && !empty($mobilenumber) )
{
	require_once("../include/membersite_config.php");
	if(!$fgmembersite->RegisterUserUnity($fullname,$email,$user,$password,$postaladdress,$postalcode,$internationalcountrycode,$mobilenumber))
	{
		echo "fail";
	}
	else
	{
		echo "success";
	}
}

?>
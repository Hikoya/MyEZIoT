<?php

if (empty($_POST['topic'])) {
		$name = '';
} else {
		$name = $_POST['topic'];
}

if (empty($_POST['msg'])) {
		$msg = '';
} else {
		$msg = $_POST['msg'];
}

if (empty($_POST['mobileno'])) {
		$target = '';
} else {
		$target = $_POST['mobileno'];
}

require __DIR__ . '/vendor/autoload.php';

$config = parse_ini_file('../private/config.ini'); 	 
$key = $config['smskey'];
$secret = $config['smssecret'];

$params = array(
    'credentials' => array(
        'key' => $key,
        'secret' => $secret,
    ),
    'region' => 'ap-northeast-1', 
    'version' => 'latest'
);
$sns = new \Aws\Sns\SnsClient($params);

$args = array(
    'MessageAttributes' => [
        'AWS.SNS.SMS.SenderID' => [
               'DataType' => 'String',
               'StringValue' => $name
        ]
     ],
    "PhoneNumber" => $target,
    "Message" => $msg
);

$result = $sns->publish($args);

echo "SMS";
echo $_POST['mobileno'];

?>
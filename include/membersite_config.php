<?PHP
require_once("fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('loragateway.com');


//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time

$config = parse_ini_file('../private/config.ini'); 

$hostname = $config['servername'];
$username = $config['username'];
$password = $config['password'];
$dbname = $config['dbname'];
$tablename = $config['tablename'];
$tablenamenode = $config['tablenamenode'];
$tablenameswitch = $config['tablenameswitch'];
$tablenametoken = $config['tablenametoken'];
$smstopic = $config['smstopicpass'];
$sitename = $config['sitename'];

$emailaddress = $config['emailaddress'];
$emailpwd = $config['emailpassword'];
$adminname = $config['emailadminname'];

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail($emailaddress);


$fgmembersite->InitDB($hostname,$username,$password,$dbname,$tablename,$tablenamenode,$tablenameswitch,$tablenametoken,$smstopic,$sitename);
					  				  
$fgmembersite->InitEmail($emailaddress,$emailpwd,$adminname);

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('BOLKqN9r7ejwV1g');

?>
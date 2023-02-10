<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */
 
class DB_Functions {
	
 
	 private $conn;
 
    // constructor
    function __construct() {
        
		$config = parse_ini_file('../private/config.ini'); 

		$hostname = $config['servername'];
		$username = $config['username'];
		$password = $config['password'];
		$dbname   = $config['dbname'];
	
		$this->conn = new mysqli($hostname, $username, $password, $dbname);
    }

         
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $username, $password) {
		
		$config = parse_ini_file('../private/config.ini'); 
		
		$tablename= $config['tablename'];
		
		$rand_key = $config['rand_key'];
		$randno1 = rand();
        $randno2 = rand();
		
		$encrypted_password = md5($password);
		$confirmcode = md5($email.$rand_key.$randno1.''.$randno2);
		
        $stmt = $this->conn->prepare("INSERT INTO" .$tablename."(name, email, username, password, confirmcode) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $username, $encrypted_password, $confirmcode);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM ".$tablename." WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($username, $password) {
		
		$config = parse_ini_file('../private/config.ini'); 
		
		$tablename=$config['tablename'];
		
        $stmt = $this->conn->prepare("SELECT * FROM ".$tablename." WHERE username = ? and confirmcode = 'confirmed' ");
 
        $stmt->bind_param("s", $username);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            $encrypted_password = $user['password'];
            $comparedpassword = md5($password);
            // check for password equality
            if ($encrypted_password == $comparedpassword)
			{
                // user authentication details are correct
                return $user;
            }
			
        } else {
            return NULL;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
		
	
		
		$config = parse_ini_file('../private/config.ini'); 
		
		$tablename = $config['tablename'];
		
        $stmt = $this->conn->prepare("SELECT email from ".$tablename." WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	
	public function SendUserConfirmationEmail($name,$email)
    {
		require_once("PHPMailerAutoload.php");
		require_once("class.phpmailer.php");
		require_once("class.smtp.php");
		require_once("fg_membersite.php");
		
		
		$config = parse_ini_file('./private/config.ini'); 
		
		$emailadmin = $config['emailaddress'];
		$pwdadmin = $config['emailpassword'];
		$adminname = $config['adminname'];
		$sitename = $config['sitename'];
		
		$fg = new FGMembersite();
		$url = $fg->GetAbsoluteUrlFolder();
		
        
		$rand_key = $config['rand_key'];
		$randno1 = rand();
        $randno2 = rand();
		$confirmcode = md5($email.$rand_key.$randno1.''.$randno2);
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($email,$name);

        $mailer->Subject = "Your registration with ".$sitename;

        $mailer->From = $emailadmin;
		$mailer->FromName = $adminname;		
       
        $confirm_url = $url.'/confirmreg.php?code='.$confirmcode; 
        
        $mailer->Body ="Hello ".$name."\r\n\r\n".
        "Thanks for your registration with ".$sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url \r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
    }
	
	
	public function storeGateway($gatewayno, $username ,$description) {
		
		$config = parse_ini_file('/private/config.ini'); 
		
		$tablename= $config['tablenamenode'];
			
		$qry = 'INSERT INTO '.$tablename.' (username,gatewayno,description) VALUES ("' .$username.'", "' .$gatewayno.'" , " '.$description.'")';
        
		$result = mysqli_query($this->conn,$qry);
		 // check for successful store
        if ($result) {
			
			$stmt = $this->conn->prepare("SELECT * FROM ".$tablename." WHERE gatewayno = ?");
            $stmt->bind_param("s", $gatewayno);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
          
        } else {
            return false;
        }
		
    }
	
	public function isGatewayExisted($gatewayno) {
		
		$config = parse_ini_file('/private/config.ini'); 
		
		$tablename = $config['tablenamenode'];
		
        $stmt = $this->conn->prepare("SELECT gatewayno from ".$tablename." WHERE gatewayno = ?");
        $stmt->bind_param("s", $gatewayno);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	

 
}
 
?>
<?PHP

require_once("formvalidator.php");
require_once("PHPMailerAutoload.php");
require_once("class.phpmailer.php");
require_once("class.smtp.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;
    
    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;
	
	var $emailuser;
	var $emailpwd;
	var $emailadminname;
    
    var $error_message;
    
    //-----Initialization -------
    function FGMembersite()
    {
        $this->rand_key = 'BOLKqN9r7ejwV1g';
		$this->rand_key2 = 'ika92Sda7FGcaKvm';
    }
    
    function InitDB($host,$uname,$pwd,$database,$tablename,$tablenamenode,$tablenameswitch,$tablenametoken,$smstopic,$sitename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;
		$this->tablenamenode =$tablenamenode;
		$this->tablenametoken = $tablenametoken;
		$this->tablenameswitch = $tablenameswitch;
		$this->SMSTopicPass = $smstopic;
		$this->sitename = $sitename;

    }
	
	
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }
    
    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
	
	function InitEmail($emailuser,$emailpwd,$emailadminname)
	{
		$this->emailuser = $emailuser;
		$this->emailpwd  = $emailpwd;
		$this->emailadminname = $emailadminname;
	}
    
    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submittedreg']))
        {
           return false;
        }
		      
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }
		
        $this->SendAdminIntimationEmail($formvars);
		
        return true;
    }
	
	function RegisterUserUnity($f,$e,$u,$p,$pa,$pc,$ic,$mn)
    {
        
        $formvars = array();
        
        $formvars['name'] = $f;
        $formvars['email'] = $e;
        $formvars['username'] = $u;
        $formvars['password'] = $p;
		$formvars['address'] = $pa;
		$formvars['postalcode'] = $pc;
		$formvars['mobileno'] = $mn;
		$formvars['callingcode'] = $ic;
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }
		
        $this->SendAdminIntimationEmail($formvars);
		
        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Please provide the confirm code");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }
        
        $this->SendUserWelcomeEmail($user_rec);
        
        $this->SendAdminIntimationOnRegComplete($user_rec);
        
        return true;
    }    
    
    function Login()
    {
        if(empty($_POST['usernamelogin']))
        {
            $this->HandleError("UserName is empty!");
			$_POST['usernamelogin']='';
			$_POST['password']='';
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
			$_POST['usernamelogin']='';
			$_POST['password']='';
			return false;
        }
        
        $username = trim($_POST['usernamelogin']);
        $password = trim($_POST['password']);
		   	 
        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDBReg($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDBApprove($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }
		
        $_SESSION[$this->GetLoginSessionVar()] = $username;
        
        return true;
    }
	
	function LoginToken()
    {
        if(empty($_POST['usernametoken']))
        {
            $this->HandleError("UserName is empty!");
			$_POST['usernametoken']='';
			$_POST['passwordtoken']='';
            return false;
        }
        
        if(empty($_POST['passwordtoken']))
        {
            $this->HandleError("Password is empty!");
			$_POST['usernametoken']='';
			$_POST['passwordtoken']='';
			return false;
        }
        
        $username = trim($_POST['usernametoken']);
        $password = trim($_POST['passwordtoken']);
		
		if(!isset($_SESSION)){ session_start(); }
		
        if(!$this->CheckLoginInDBReg($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDBApprove($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }
		
		if(!$this->DeleteToken($username))
		{
			return false;
		}
		
		if(!$this->GenerateToken($username))
		{
			return false;
		}
		
		$_SESSION[$this->GetLoginSessionVar()] = $username;
		
        return true;
    }
	
	function LoginUnity()
    {
        if(empty($_POST['userID']))
        {
            $this->HandleError("UserName is empty!");
			$_POST['userID']='';
			$_POST['password']='';
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
			$_POST['userID']='';
			$_POST['password']='';
			return false;
        }
        
        $username = trim($_POST['userID']);
        $password = trim($_POST['password']);
		
		if(!isset($_SESSION)){ session_start(); }
        	
        if(!$this->CheckLoginInDBReg($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDBApprove($username))
        {
            return false;
        }
		
		if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }
		
		$_SESSION[$this->GetLoginSessionVar()] = $username;
		
        return true;
    }
	
	function LoginKey()
    {
        if(empty($_POST['key']))
		{
			$this->HandleError("Key is empty!");
			$_POST['key']='';
			return false;
		}
		
		$key = trim($_POST['key']);
		
		if(!isset($_SESSION)){ session_start(); }
		
		if(!$this->CheckLoginInDBKey($key))
        {
            return false;
        }
		
        $_SESSION[$this->GetLoginSessionKey()] = $key;
        
        return true;
    }
	
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
		 
         return true;
    }
	
	function CheckKeyLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionKey();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
		 
         return true;
    }
    
    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }
	
	function UserFullUserName()
    {
        return isset($_SESSION['username_of_user'])?$_SESSION['username_of_user']:'';
    }
    
    
    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }
    
    function LogOut()
    {
        session_start();
        
        $sessionvar = $this->GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;
        
        unset($_SESSION[$sessionvar]);
    }
    
    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code is empty!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
        
		if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }
        
        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }
        
        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
			
		if(!$this->SendUserUpdateSMS($user_rec))
		{
			return false;
		}
		
        return true;
    }
    
    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }
        
        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Old password is empty!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("New password is empty!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
        
        $pwd = trim($_POST['oldpwd']);
        
        if(!$this->VerifyPasswordInDB($user_rec,$pwd))
        {
            $this->HandleError("The old password does not match!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);
        
        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
		
		if(!$this->SendUserUpdateConfirmationEmail($user_rec))
        {
            return false;
        }
		
		if(!$this->SendUserUpdateSMS($user_rec))
		{
			return false;
		}
		
		
        return true;
    }
	
	function RegisterNode()
	{
		if(!isset($_POST['submitted']))
        {
           return false;
        }
		
		if(!empty($_POST['latitude']) && empty($_POST['longitude']))
		{
			$this->HandleError("Longitude must be filled out with latitude");
			return false;
		}
		
		if(empty($_POST['latitude']) && !empty($_POST['longitude']))
		{
			$this->HandleError("Latitude must be filled out with longitude");
			return false;
		}
		
        $formvars = array();  
		
		$this->GenerateSerialNo($formvars);
	
        $this->CollectRegistrationSubmissionNode($formvars);
		
		if(!$this->SaveToDatabaseNode($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function RegisterSwitch()
	{
		if(!isset($_POST['submitted']))
        {
           return false;
        }
		
		if(empty($_POST['description']))
        {
            $this->HandleError("Description is empty!");
            return false;
        }
		
        $formvars = array(); 

		$this->GenerateSerialNo($formvars);
	
        $this->CollectRegistrationSubmissionSwitch($formvars);
		
		if(!$this->SaveToDatabaseSwitch($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function DeleteNode($username)
	{
		if(!isset($_POST['submitted2']))
        {
           return false;
        }
		
		if(empty($_POST['serialno3']))
        {
            $this->HandleError("Serial number is empty!");
            return false;
        }
		
        $formvars = array();  
		$formvars['username'] = $username;
	
        $this->CollectDeleteNode($formvars);
		
		if(!$this->SaveToDatabaseDeleteNode($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function DeleteSwitch($username)
	{
		if(!isset($_POST['submitted2']))
        {
           return false;
        }
		
		 if(empty($_POST['serialno3']))
        {
            $this->HandleError("Serial number is empty!");
            return false;
        }
		
        $formvars = array(); 
		$formvars['username'] = $username;
	
        $this->CollectDeleteSwitch($formvars);
		
		if(!$this->SaveToDatabaseDeleteSwitch($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function EditNode($username)
	{
		if(!isset($_POST['submitted3']))
        {
           return false;
        }
		
		if(empty($_POST['serialno3']))
        {
            $this->HandleError("Serial number is empty!");
            return false;
        }
		
		if(empty($_POST['latitude3']) && !empty($_POST['longitude3']) )
        {
            $this->HandleError("Latitude must be filled out with longitude!");
            return false;
        }
		
		if(!empty($_POST['latitude3']) && empty($_POST['longitude3']) )
        {
            $this->HandleError("Longitude must be filled out with latitude!");
            return false;
        }
		
        $formvars = array();  
	
        $this->CollectEditNode($formvars);
		
		$formvars['username'] = $username;
		
		if(!$this->SaveToDatabaseEditNode($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function EditSwitch($username)
	{
		if(!isset($_POST['submitted3']))
        {
           return false;
        }
		
		if(empty($_POST['serialno3']))
        {
            $this->HandleError("Serial number is empty!");
            return false;
        }
		
        $formvars = array();  
		$formvars['username'] = $username;
	
        $this->CollectEditSwitch($formvars);
		
		if(!$this->SaveToDatabaseEditSwitch($formvars))
		{
			return false;
		}
        
        return true;
	}
	
	function UpdateProfile($username)
    {
		if(!isset($_SESSION)){ session_start(); }
		 
        if(!isset($_POST['submittedprofile']))
        {
           return false;
        }
       
        $formvars = array();
        $this->CollectRegistrationSubmissionUpdate($formvars);
		
		if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
        
		if($user_rec['email'] != $formvars['email'])
        {
            if(!$this->SendUserUpdateProfileEmail($formvars))
			{
				return false;
			}
			
			if(!$this->SendUserUpdateProfileOldEmail($formvars,$user_rec['email']))
			{
				return false;
			}
			
			$_SESSION['email_of_user'] = $formvars['email'];
		
        }
		
		$formvars['username'] = $username;
		
        if(!$this->UpdateToDatabase($formvars))
        {
            return false;
        }
		 
        return true;
    }
	
	function UpdateHeader($username)
    {
        if(!isset($_POST['submittedheader']))
        {
           return false;
        }
       
        $formvars = array();
        $this->CollectHeaderUpdate($formvars);
	
		$formvars['username'] = $username;
		
        if(!$this->UpdateToDatabaseHeader($formvars))
        {
            return false;
        }
		 
        return true;
    }
	
	function UpdateSMS($username)
    {
        if(!isset($_POST['submittedsms']))
        {
           return false;
        }
       
        $formvars = array();
        $this->CollectSMSUpdate($formvars);
	
		$formvars['username'] = $username;
		
        if(!$this->UpdateToDatabaseSMS($formvars))
        {
            return false;
        }
		 
        return true;
    }
    
    //-------Public Helper functions -------------
    
    
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    
    function RedirectToURL($url)
    {
        $config = parse_ini_file("../private/config.ini");
		$sitename = $config['sitename'];
		header("Location: https://".$sitename."/".$url."");
        exit;
    }
    
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }
    
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysqli_error($this->connection));
    }
    
    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
	
	 function GetLoginSessionKey()
    {
        $retvar = md5($this->rand_key2);
        $retvar = 'key_'.substr($retvar,0,10);
        return $retvar;
    }
    
	function CheckLoginInDBKey($key)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
		
        $key = $this->SanitizeForSQL($key);
        $qry = "Select username from $this->tablename where readkey ='$key' ";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The user has not been authenticated OR no such user exist. ");
            return false;
        }
		
        $row = mysqli_fetch_assoc($result);
		
		$_SESSION['username_of_user'] = $row['username'];
 
        return true;
    }
	
	function CheckLoginInDBReg($username)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
		
        $username = $this->SanitizeForSQL($username);
        $qry = "Select username from $this->tablename where username='$username' and confirmcode = 'confirmed' ";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The user has not been authenticated OR no such user exist. ");
            return false;
        }
		
        $row = mysqli_fetch_assoc($result);
 
        return true;
    }
	
	function CheckLoginInDBApprove($username)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
		
        $username = $this->SanitizeForSQL($username);
        $qry = "Select username from $this->tablename where username='$username' and approval = 'approve' ";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The user has not been approved by the admin. ");
            return false;
        }
		
        $row = mysqli_fetch_assoc($result);
 
        return true;
    }
	
    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
		
        $username = $this->SanitizeForSQL($username);
		
		$pwd_qry = "select password from $this->tablename where username = '$username'";
		$pwd_result = mysqli_query($this->connection,$pwd_qry);
		$pwd_row = mysqli_fetch_assoc($pwd_result);
		$pwd_hash = $pwd_row['password'];
		
		if(password_verify($password,$pwd_hash)){
			$qry = "Select name, email, username from $this->tablename where username='$username'";
			$result = mysqli_query($this->connection,$qry);
			
			$row = mysqli_fetch_assoc($result);
        
			$_SESSION['username_of_user'] = $row['username'];
			$_SESSION['email_of_user'] = $row['email'];
			$_SESSION['name_of_user'] = $row['name'];
        
			return true;
		}
		else{
			
			$this->HandleError("Error logging in. The username or password does not match");
            return false;
		}
		
	}
	
	
    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $confirmcode = $this->SanitizeForSQL($_GET['code']);
        
        $result = mysqli_query($this->connection, "Select name, email from $this->tablename where confirmcode='$confirmcode'");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Wrong confirm code.");
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];
        
        $qry = "Update $this->tablename Set confirmcode='confirmed' Where  confirmcode='$confirmcode'";
        
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }      
        return true;
    }
    
    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,20);
        
        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }
	
	function VerifyPasswordInDB($user_rec, $oldpwd)
	{
		
		$qry = "SELECT password FROM ".$this->tablename." WHERE id_user=".$user_rec['id_user']."";
		$pwd_result = mysqli_query($this->connection,$qry);
		$pwd_row = mysqli_fetch_assoc($pwd_result);
		$pwd_hash = $pwd_row['password'];
		if(password_verify($oldpwd,$pwd_hash)){
			return true;
		}
		else
			return false;
		
	}
    
    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);
		$newpwd = password_hash($newpwd,PASSWORD_DEFAULT);
        
        $qry = "Update $this->tablename Set password='".$newpwd."' Where  id_user=".$user_rec['id_user']."";
        
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }     
        return true;
    }
	
    
    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $email = $this->SanitizeForSQL($email);
        
        $result = mysqli_query($this->connection,"Select * from $this->tablename where email='$email'");  

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }
        $user_rec = mysqli_fetch_assoc($result);

        
        return true;
    }
    
    function SendUserWelcomeEmail(&$user_rec)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer             = new PHPMailer(true);
		  
		$mailer->IsSMTP(); // telling the class to use SMTP
		$mailer->Debugoutput = 'html';

		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin;  		// GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
		

        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($user_rec['email'],$user_rec['name']);
        
        $mailer->Subject = "Welcome to ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Welcome! Your registration  with ".$this->sitename." is completed.\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
		
		

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending user welcome email.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
       
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;

		if(empty($this->admin_email))
        {
            return false;
        }
        $mailer             = new PHPMailer(true);
		  
		$mailer->IsSMTP(); // telling the class to use SMTP
		$mailer->Debugoutput = 'html';

		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin;  // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Registration Completed: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();       
		$mailer->FromName = $emailadminname;
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$user_rec['name']."\r\n".
        "Email address: ".$user_rec['email']."\r\n";
   
		
		if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function GetResetPasswordCode($email)
    {
       return substr(md5($email.$this->sitename.$this->rand_key),0,20);
    }
    
    function SendResetPasswordLink($user_rec)
    {
        
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer             = new PHPMailer(true);
		  
		$mailer->IsSMTP(); // telling the class to use SMTP
		$mailer->Debugoutput = 'html';

		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin;  // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
	   
		$email = $user_rec['email'];
        
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;
        
        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email));

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
		
	
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];
		
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
        
		$mailer             = new PHPMailer(true);
		  
		$mailer->IsSMTP(); // telling the class to use SMTP
		$mailer->Debugoutput = 'html';

		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin;  // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your new password for ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
		
	
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
        $validator->addValidation("name","req","Please fill in Name");
        $validator->addValidation("email","email","The input for Email should be a valid email value");
        $validator->addValidation("email","req","Please fill in Email");
        $validator->addValidation("username","req","Please fill in UserName");
        $validator->addValidation("password","req","Please fill in Password");
		$validator->addValidation("mobileno","req","Please fill in Mobile Number");

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
	
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
		
		if(!empty($_POST['address']))
			$formvars['address'] = $this->Sanitize($_POST['address']);
		
		if(!empty($_POST['postalcode']))
			$formvars['postalcode'] = $this->Sanitize($_POST['postalcode']);
		
		$formvars['mobileno'] = $this->Sanitize($_POST['mobileno']);
		$formvars['callingcode'] = $this->Sanitize($_POST['callingcode']);
    }
    
    function CollectRegistrationSubmissionNode(&$formvars)
    {
		$formvars['description'] = $this->Sanitize($_POST['description']);
		$formvars['location'] = $this->Sanitize($_POST['location']);
		
		if(!empty($_POST['column1']))
			$formvars['column1'] = $this->Sanitize($_POST['column1']);
		if(!empty($_POST['column2']))
			$formvars['column2'] = $this->Sanitize($_POST['column2']);
		if(!empty($_POST['column3']))
			$formvars['column3'] = $this->Sanitize($_POST['column3']);
		if(!empty($_POST['column4']))
			$formvars['column4'] = $this->Sanitize($_POST['column4']);
		if(!empty($_POST['column6']))
			$formvars['column6'] = $this->Sanitize($_POST['column6']);
		
		if(!empty($_POST['latitude']))
			$formvars['latitude'] = $this->Sanitize($_POST['latitude']);
		if(!empty($_POST['longitude']))
			$formvars['longitude'] = $this->Sanitize($_POST['longitude']);
		
		if(isset($_POST['column5']))
			$formvars['column5'] = "GPS";
    }
	
	function CollectRegistrationSubmissionSwitch(&$formvars)
    {
		$formvars['description'] = $this->Sanitize($_POST['description']);
    }
	
	function CollectEditNode(&$formvars)
    {
		$formvars['serialno'] = $this->Sanitize($_POST['serialno3']);
		
		if(!empty($_POST['description3']))
			$formvars['description'] = $this->Sanitize($_POST['description3']);
		
		if(!empty($_POST['location3']))
			$formvars['location'] = $this->Sanitize($_POST['location3']);
		
		if(!empty($_POST['column13']))
			$formvars['column1'] = $this->Sanitize($_POST['column13']);
		else
			$formvars['column1'] = '';
	
		if(!empty($_POST['column23']))
			$formvars['column2'] = $this->Sanitize($_POST['column23']);
		else
			$formvars['column2'] = '';
		
		if(!empty($_POST['column33']))
			$formvars['column3'] = $this->Sanitize($_POST['column33']);
		else
			$formvars['column3'] = '';
		
		if(!empty($_POST['column43']))
			$formvars['column4'] = $this->Sanitize($_POST['column43']);
		else
			$formvars['column4'] = '';
		
		if(!empty($_POST['column63']))
			$formvars['column6'] = $this->Sanitize($_POST['column63']);
		else
			$formvars['column6'] = '';
		
		if(!empty($_POST['latitude3']))
			$formvars['latitude'] = $this->Sanitize($_POST['latitude3']);
		else
			$formvars['latitude'] = '';
		
		if(!empty($_POST['longitude3']))
			$formvars['longitude'] = $this->Sanitize($_POST['longitude3']);
		else
			$formvars['longitude'] = '';
		
		if(isset($_POST['column53']))
			$formvars['column5'] = 'GPS';
		else
			$formvars['column5'] = '';
    }
	
	function CollectEditSwitch(&$formvars)
    {
		$formvars['serialno'] = $this->Sanitize($_POST['serialno3']);
		$formvars['description'] = $this->Sanitize($_POST['description3']);
    }
	
	function CollectDeleteNode(&$formvars)
    {
		$formvars['serialno2'] = $this->Sanitize($_POST['serialno3']);
    }
	
	function CollectDeleteSwitch(&$formvars)
    {
		$formvars['serialno2'] = $this->Sanitize($_POST['serialno3']);
    }
	
	function CollectRegistrationSubmissionUpdate(&$formvars)
    {
		$formvars['name'] = $this->Sanitize($_POST['fullname']);
		$formvars['email'] = $this->Sanitize($_POST['email']);
		
		if(!empty($_POST['address']))
			$formvars['address'] = $this->Sanitize($_POST['address']);
		
		if(!empty($_POST['postalcode']))
			$formvars['postalcode'] = $this->Sanitize($_POST['postalcode']);
		
		$formvars['mobileno'] = $this->Sanitize($_POST['mobileno']);
		$formvars['callingcode'] = $this->Sanitize($_POST['callingcode']);
    }
	
	function CollectHeaderUpdate(&$formvars)
    {
		if(!empty($_POST['column1']))
			$formvars['column1'] = $this->Sanitize($_POST['column1']);
	
		if(!empty($_POST['column2']))
			$formvars['column2'] = $this->Sanitize($_POST['column2']);
		
		if(!empty($_POST['column3']))
			$formvars['column3'] = $this->Sanitize($_POST['column3']);
		
		if(!empty($_POST['column4']))
			$formvars['column4'] = $this->Sanitize($_POST['column4']);
			
		if(!empty($_POST['column5']))
			$formvars['column5'] = $this->Sanitize($_POST['column5']);
			
		if(!empty($_POST['column6']))
			$formvars['column6'] = $this->Sanitize($_POST['column6']);
    }
	
	function CollectSMSUpdate(&$formvars)
    {
		if(!empty($_POST['smsemail']))
			$formvars['smsemail'] = $this->Sanitize($_POST['smsemail']);
	
		if(!empty($_POST['smsmobileno']))
			$formvars['smsmobileno'] = $this->Sanitize($_POST['smsmobileno']);
	
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);

        $mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;		
        
        $confirmcode = $formvars['confirmcode'];
        
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode; 
        
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url \r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
    }
	
	function SendUserUpdateConfirmationEmail(&$formvars)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);

        $mailer->Subject = "Your password has recently been changed in ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;		
          
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Your password has recently been changed in ".$this->sitename."\r\n".
        "Please ensure that you are able to log in with your new password and that it is really you who changed it.\r\n".
        "\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
    }
	
	function SendUserUpdateProfileEmail(&$formvars)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);

        $mailer->Subject = "[Success] Email address change in ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;		
          
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Your email has successfully been changed in ".$this->sitename."\r\n".
        "Please use this email to reset your password when needed.\r\n".
        "\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
		
		exit;
    }
	
	function SendUserUpdateProfileOldEmail(&$formvars,$email)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($email,$formvars['name']);

        $mailer->Subject = "[Notice] Email address change in ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;		
          
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Your email has recently been changed in ".$this->sitename."\r\n".
        "Please contact support if this is an unauthorised operation and that it is not you who initiated this action.\r\n".
        "\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
		exit;
    }
	
	function SendUserAlertEmail($email,$username,$message)
    {
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
		$mailer->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mailer->IsSMTP(); // telling the class to use SMTP
                                         
		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin; 			 // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($email,$username);

        $mailer->Subject = "[Notice] Sensor data exceeded threshold in ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
		$mailer->FromName = $emailadminname;		
          
        $mailer->Body ="Hello ".$username."\r\n\r\n".
        "Sensor data exceeded threshold set by user in ".$this->sitename."\r\n".
		$message.
        "\r\n".
        "\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
	

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
        return true;
    }
	
    function GetAbsoluteURLFolder()
    {
        //$scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$scriptFolder = 'https://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
		$scriptFolder =  rtrim($scriptFolder, "\.");
        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
		
		$emailadmin = $this->emailuser;
		$pwdadmin = $this->emailpwd;
		$emailadminname = $this->emailadminname;
		
        $mailer             = new PHPMailer(true);
		  
		$mailer->IsSMTP(); // telling the class to use SMTP
		$mailer->Debugoutput = 'html';

		$mailer->SMTPAuth   = true;                  // enable SMTP authentication
		$mailer->SMTPSecure = 'tls';                 // sets the prefix to the servier
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mailer->Username   = $emailadmin;  // GMAIL username
		$mailer->Password   = $pwdadmin;           // GMAIL password
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "New registration: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();   
		$mailer->FromName = $emailadminname;		
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$formvars['name']."\r\n".
        "Email address: ".$formvars['email']."\r\n".
        "UserName: ".$formvars['username'];
		
	
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }
        
        if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("This UserName is already used. Please try another username");
            return false;
        }        
		
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }
	
	 function SaveToDatabaseNode(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->IsFieldUniqueNode($formvars,'serialno'))
        {
			$this->GenerateSerialNo($formvars);
        } 

		if(!$this->IsFieldUniqueSwitch($formvars,'serialno'))
        {
            $this->GenerateSerialNo($formvars);
        }     
		
		if(!empty($formvars['column1']) || !empty($formvars['column2']) || !empty($formvars['column3']) || !empty($formvars['column4']) || !empty($formvars['column5']) || !empty($formvars['column6']) || !empty($formvars['latitude']) || !empty($formvars['longitude']) )
		{
			
			if(!$this->CreateTableNode($formvars))
			{
				$this->HandleError("Creating database failed!");
				return false;
			}
			
			 if(!$this->InsertIntoDBNode($formvars))
			{
				$this->HandleError("Inserting to Database failed!");
				return false;
			}
			
			return true;
		}
		
	    
    }
	
	 function SaveToDatabaseSwitch(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
		
		if(!$this->IsFieldUniqueNode($formvars,'serialno'))
        {
            $this->HandleError("This Serial Number is already used.");
            $this->GenerateSerialNo($formvars);
        }  
		
        if(!$this->IsFieldUniqueSwitch($formvars,'serialno'))
        {
            $this->HandleError("This Serial Number is already used.");
            $this->GenerateSerialNo($formvars);
        }        
	
		if(!$this->InsertIntoDBSwitch($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
		
        return true;
    }
	
	function SaveToDatabaseDeleteNode(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->DoesNodeExist($formvars,'serialno2'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno2']." does not exist in database.");
            return false;
        }        
		
        if(!$this->DoesNodeBelong($formvars,'serialno2','username'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno2']." does not belong to you.");
            return false;
        }  
		
		if(!$this->DeleteTableNode($formvars))
		{
			$this->HandleError("Deleting database failed!");
			return false;
		}
		
		if(!$this->DeleteIntoDBNode($formvars))
        {
           // $this->HandleError("Delete from Database failed!");
            return false;
        }
		
        return true;
    }
	
	function SaveToDatabaseDeleteSwitch(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->DoesSwitchExist($formvars,'serialno2'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno2']." does not exist in database.");
            return false;
        }        
		
		if(!$this->DoesSwitchBelong($formvars,'serialno2','username'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno2']." does not belong to ".$formvars['username'].".");
            return false;
        } 
		
		if(!$this->DeleteIntoDBSwitch($formvars))
        {
           // $this->HandleError("Delete from Database failed!");
            return false;
        }
		
        return true;
    }
	
	function SaveToDatabaseEditNode(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->DoesNodeExist($formvars,'serialno'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno']." does not exist in database.");
            return false;
        } 

		if(!$this->DoesNodeBelong($formvars,'serialno','username'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno']." does not belong to you.");
            return false;
        }  
       
		if(!$this->EditIntoDBNode($formvars))
        {
            //$this->HandleError("Delete from Database failed!");
            return false;
        }
		
        return true;
    }
	
	function SaveToDatabaseEditSwitch(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
       
        if(!$this->DoesSwitchExist($formvars,'serialno'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno']." does not exist in database.");
            return false;
        }   

		if(!$this->DoesSwitchBelong($formvars,'serialno','username'))
        {
            $this->HandleError("This Serial Number ".$formvars['serialno']." does not belong to you.");
            return false;
        } 
       
		if(!$this->EditIntoDBSwitch($formvars))
        {
            $this->HandleError("Delete from Database failed!");
            return false;
        }
		
        return true;
    }
	
	function UpdateToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
      
        if(!$this->UpdateIntoDB($formvars))
        {
            $this->HandleError("Updating to Database failed!");
            return false;
        }
        return true;
    }
	
	function UpdateToDatabaseHeader(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
      
        if(!$this->UpdateIntoDBHeader($formvars))
        {
            $this->HandleError("Updating to Database failed!");
            return false;
        }
        return true;
    }
	
	function UpdateToDatabaseSMS(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
      
        if(!$this->UpdateIntoDBSMS($formvars))
        {
            $this->HandleError("Updating to Database failed!");
            return false;
        }
        return true;
    }
	
	function UpdateToDatabaseKey(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
      
        if(!$this->UpdateIntoDBKey($formvars))
        {
            $this->HandleError("Updating to Database failed!");
            return false;
        }
        return true;
    }
    
    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename where $fieldname='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
	
	function IsFieldUniqueNode($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablenamenode where gatewayno='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
	
	function IsFieldUniqueSwitch($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablenameswitch where gatewayno='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
	
	function DoesNodeExist(&$formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablenamenode where gatewayno ='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
			return true;
        }
        return false;
    }
	
	function DoesNodeBelong(&$formvars,$fieldname,$fieldname2)
    {
		$user = $formvars[$fieldname2];
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select * from $this->tablenamenode where gatewayno ='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
			if($results = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$username = $results['username'];
				if(strcasecmp($user, $username) == 0)
				{
					return true;
				}
				else
					return false;
			}
        }
        return false;
    }
	
	function DoesSwitchExist(&$formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablenameswitch where gatewayno ='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
			return true;
        }
        return false;
    }
	
	function DoesSwitchBelong(&$formvars,$fieldname,$fieldname2)
    {
		$user = $formvars[$fieldname2];
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select * from $this->tablenameswitch where gatewayno ='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
			if($results = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$username = $results['username'];
				if(strcasecmp($user, $username) == 0)
				{
					return true;
				}
				else
					return false;
			}
        }
        return false;
    }
    
    function DBLogin()
    {

        $this->connection = mysqli_connect($this->db_host,$this->username,$this->pwd,$this->database);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysqli_select_db($this->connection, $this->database))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysqli_query($this->connection,"SET NAMES 'UTF8'"))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }    
	
    
    function Ensuretable()
    {
        $result = mysqli_query("SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }
    
    /*function CreateTable()
    {
        $qry = "Create Table $this->tablename (".
                "id_user INT NOT NULL AUTO_INCREMENT ,".
                "name VARCHAR( 128 ) NOT NULL ,".
                "email VARCHAR( 64 ) NOT NULL ,".
                "phone_number VARCHAR( 16 ) NOT NULL ,".
                "username VARCHAR( 16 ) NOT NULL ,".
                "password VARCHAR( 32 ) NOT NULL ,".
                "confirmcode VARCHAR(32) ,".
                "PRIMARY KEY ( id_user )".
                ")";
                
        if(!mysql_query($qry,$this->connection))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }*/
	
	function CreateTableNode(&$formvars)
    {
		
		$serialno = $formvars['serialno'];
			
		$qry =  "CREATE TABLE ".$serialno." (".
				" id INT NOT NULL AUTO_INCREMENT,".
				" column1 DOUBLE(10,3) NULL, ".
				" column2 DOUBLE(10,3) NULL, ".
				" column3 DOUBLE(10,3) NULL, ".
				" column4 DOUBLE(10,3) NULL, ".
				" column5 VARCHAR(50) NULL, ".
				" column6 VARCHAR(50) NULL, ".
				" timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,".
				" PRIMARY KEY ( id )".
				")";

        if(!mysqli_query($this->connection,$qry))
        {
            //$this->HandleDBError("This product is already registered");
            return false;
        }
        return true;
    }
	
	function DeleteTableNode(&$formvars)
    {
		
		$serialno = $formvars['serialno2'];
		$current = $_SESSION['username_of_user'];
			
		$qry =  "SELECT username from ".$this->tablenamenode." where gatewayno = '".$serialno."' ";

        $query = mysqli_query($this->connection,$qry);
		if($result3 = mysqli_fetch_array($query,MYSQLI_ASSOC))
        {
            $tableusername = $result3['username'];
        }
		
		if($tableusername != $current)
		{
			$this->HandleDBError("You are not authorised to perform this operation");
			return false;
		}
		else
		{
			$qry2 = "DROP TABLE ".$serialno." ";
			if(!mysqli_query($this->connection,$qry2))
			{
            $this->HandleDBError("Error deleting the table \nquery was\n $qry");
            return false;
			}
		}
        return true;
    }
	
    
    function UpdateIntoDB(&$formvars)
    {
		$content = '';
		
		if(!empty($formvars['address']) && !empty($formvars['postalcode'])) //11
		{
			$content .= 'address  = "'.$this->SanitizeForSQL($formvars['address']) .'",';
			$content .= 'postalcode= "'.$this->SanitizeForSQL($formvars['postalcode']) .'",';
		}
		
		if(!empty($formvars['address']) && empty($formvars['postalcode'])) //10
		{
			$content .= 'address  = "'.$this->SanitizeForSQL($formvars['address']) .'",';
		}
		
		if(empty($formvars['address']) && !empty($formvars['postalcode'])) //01
		{
			$content .= 'postalcode= "'.$this->SanitizeForSQL($formvars['postalcode']) .'",';
		}
		
        $update_query = 'UPDATE '.$this->tablename.' SET
                name = "'.$this->SanitizeForSQL($formvars['name'])    .'",
                email = "'.$this->SanitizeForSQL($formvars['email'])      .'",
				'.$content.'
				callingcode= "'.$this->SanitizeForSQL($formvars['callingcode']) .'",
				mobileno  = "'.$this->SanitizeForSQL($formvars['mobileno']) .'"
                WHERE username = "'.$formvars['username'].'" ';   
				
        if(!mysqli_query($this->connection,$update_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$update_query");
            return false;
        }        
		
        return true;			
    }
	
	function UpdateIntoDBKey(&$formvars)
    {
		
        $update_query = 'UPDATE '.$this->tablename.' SET
                writekey = "'.$this->SanitizeForSQL($formvars['writekey'])    .'",
                readkey = "'.$this->SanitizeForSQL($formvars['readkey'])      .'"
                WHERE username = "'.$formvars['username'].'" ';   
				
        if(!mysqli_query($this->connection,$update_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$update_query");
            return false;
        }        
		
        return true;			
    }
	
	function UpdateIntoDBHeader(&$formvars)
    {
		$precursor = 0;
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) &&!empty($formvars['column4']) ) //1111
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';		

			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1110
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';	
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';	
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1101
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1100
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //1011
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1010
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ';	
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1001
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1000
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0111
		{
		
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';		
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';	
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';	

			$precursor = 1;			
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0110
		{
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0101
		{
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //0100
		{
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ';	

			$precursor = 1;			
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0011
		{
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';	
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0010
		{
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0001
		{
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column5']) && !empty($formvars['column6'])) //01
		{
			if($precursor == 1)
			{
				$content .= ',column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}
			else
			{
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}
		}
		
		if(!empty($formvars['column5']) && empty($formvars['column6'])) //10
		{
			if($precursor == 1)
			{
				$content .= ',column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ';
			}
			else
			{
				$content .= 'column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ';
			}
		}
		
		if(!empty($formvars['column5']) && !empty($formvars['column6'])) //11
		{
			if($precursor == 1)
			{
				$content .= ',column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}
			else
			{
				$content .= 'column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}
		}
		
		
		
		if(!empty($formvars['column1']) || !empty($formvars['column2']) || !empty($formvars['column3']) || !empty($formvars['column4']) || !empty($formvars['column5']) || !empty($formvars['column6'])) 
		{
			$update_query = 'UPDATE '.$this->tablename.' SET
                '.$content.'
                WHERE username = "'.$formvars['username'].'" ';   
				
			if(!mysqli_query($this->connection,$update_query))
			{
				$this->HandleDBError("Error inserting data to the table\nquery:$update_query");
				return false;
			}        
			
			return true;
		}
		else
			return false;
			
    }
	
	function UpdateIntoDBSMS(&$formvars)
    {
		
		if(!empty($formvars['smsemail']) && !empty($formvars['smsmobileno']) ) //11
		{
			$content .= 'smsemail = "'.$this->SanitizeForSQL($formvars['smsemail']).'" ,';
			$content .= 'smsmobileno = "'.$this->SanitizeForSQL($formvars['smsmobileno']).'" ';				   
		}
		
		if(!empty($formvars['smsemail']) && empty($formvars['smsmobileno']) ) //10
		{
			$content .= 'smsemail = "'.$this->SanitizeForSQL($formvars['smsemail']).'" ';
		}
		
		if(empty($formvars['smsemail']) && !empty($formvars['smsmobileno']) ) //01
		{
			$content .= 'smsmobileno = "'.$this->SanitizeForSQL($formvars['smsmobileno']).'" ';
		}
		
		if(!empty($formvars['smsemail']) || !empty($formvars['smsmobileno']) ) 
		{
			$update_query = 'UPDATE '.$this->tablename.' SET
                '.$content.'
                WHERE username = "'.$formvars['username'].'" ';   
				
			if(!mysqli_query($this->connection,$update_query))
			{
				$this->HandleDBError("Error inserting data to the table\nquery:$update_query");
				return false;
			}        
			
			return true;
		}
		else
			return false;
			
    }
	
	function DeleteIntoDBNode(&$formvars)
    {
	
		$delete_query = "DELETE FROM ".$this->tablenamenode." WHERE ".
						"gatewayno = '" . $this->SanitizeForSQL($formvars['serialno2']) . "' ";    
				
        if(!mysqli_query($this->connection,$delete_query))
        {
            $this->HandleDBError("Error deleting data to the table\nquery:$delete_query");
            return false;
        }        
        return true;
		
    }
	
	function DeleteIntoDBSwitch(&$formvars)
    {
	
		$delete_query = "DELETE FROM ".$this->tablenameswitch." WHERE ".
						"gatewayno = '" . $this->SanitizeForSQL($formvars['serialno2']) . "' ";    
				
        if(!mysqli_query($this->connection,$delete_query))
        {
            $this->HandleDBError("Error deleting data to the table\nquery:$delete_query");
            return false;
        }        
        return true;
		
    }
	
	function EditIntoDBNode(&$formvars)
    {
		$content = '';
		$des_content = '';
		
		$precursor = 0;
		$precursor2 = 0;
		
		
		if(!empty($formvars['description']) && empty($formvars['location']))
		{
			$des_content .= 'description = "'.$this->SanitizeForSQL($formvars['description']).'" ,';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['description']) && !empty($formvars['location']))
		{
			$des_content .= 'description = "'.$this->SanitizeForSQL($formvars['description']).'" ,';
			$des_content .= 'location = "'.$this->SanitizeForSQL($formvars['location']).'" ,';
			
			$precursor = 1;
		}
			
		if(empty($formvars['description']) && !empty($formvars['location']))
		{
			$des_content .= 'location = "'.$this->SanitizeForSQL($formvars['location']).'" ,';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) &&!empty($formvars['column4']) ) //1111
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';	
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';		
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1110
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';	
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1101
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';	
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1100
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //1011
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1010
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';	
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1001
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1000
		{
			$content .= 'column1 = "'.$this->SanitizeForSQL($formvars['column1']).'" ';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0111
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';	
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';	

			$precursor = 1;			
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0110
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0101
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //0100
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = "'.$this->SanitizeForSQL($formvars['column2']).'" ';		
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0011
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0010
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = "'.$this->SanitizeForSQL($formvars['column3']).'" ,';
			$content .= 'column4 = NULL ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0001
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column4 = "'.$this->SanitizeForSQL($formvars['column4']).'" ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //0000
		{
			$content .= 'column1 = NULL ,';
			$content .= 'column2 = NULL ,';
			$content .= 'column3 = NULL ,';
			$content .= 'column3 = NULL ';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column5']) && empty($formvars['column6'])) //00
		{
			if($precursor == 1)
			{
				$content .= ', column5 = NULL ,';
				$content .= 'column6 = NULL ';

			}
			else
			{
				$content .= 'column5 = NULL ,';
				$content .= 'column6 = NULL ';

			}
			
			$precursor2 = 1;
		}
		
		if(empty($formvars['column5']) && !empty($formvars['column6'])) //01
		{
			if($precursor == 1)
			{
				$content .= ', column5 = NULL ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';

			}
			else
			{
				$content .= 'column5 = NULL ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
				
			}

			$precursor2 = 1;
		}
		
		if(!empty($formvars['column5']) && empty($formvars['column6'])) //10
		{
			if($precursor == 1)
			{
				$content .= ', column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = NULL';
				
			}
			else
			{
				$content .= 'column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = NULL';
			}

			$precursor2 = 1;
		}
		
		if(!empty($formvars['column5']) && !empty($formvars['column6'])) //11
		{
			if($precursor == 1)
			{
				$content .= ', column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}
			else
			{
				$content .= 'column5 = "'.$this->SanitizeForSQL($formvars['column5']).'" ,';
				$content .= 'column6 = "'.$this->SanitizeForSQL($formvars['column6']).'" ';
			}

			$precursor2 = 1;
		}
		
		if(empty($formvars['latitude']) && empty($formvars['longitude'])) 
		{
			
			if($precursor == 1 || $precursor2 == 1)
			{
				$content .= ', position = NULL ';
			}
			else
			{
				$content .= ' position = NULL ';
			}	
		}
		
		if(!empty($formvars['latitude']) && !empty($formvars['longitude'])) 
		{
			$location = $formvars['latitude'].";".$formvars['longitude'];
			
			if($precursor == 1 || $precursor2 == 1)
			{
				$content .= ', position = "'.$this->SanitizeForSQL($location).'" ';
			}
			else
			{
				$content .= 'position = "'.$this->SanitizeForSQL($location).'" ';
			}	
		}
			
		if(!empty($formvars['column1']) || !empty($formvars['column2']) || !empty($formvars['column3']) || !empty($formvars['column4']) || !empty($formvars['description']) || 
		!empty($formvars['location']) || !empty($formvars['latitude']) || !empty($formvars['longitude']))
		{
			
			$edit_query = 'UPDATE '.$this->tablenamenode.' SET
					'.$des_content.'
					'.$content.'
					WHERE gatewayno = "'.$formvars['serialno'].'" '; 
					
			if(!mysqli_query($this->connection,$edit_query))
			{
				$this->HandleDBError("".$formvars['column1']."".$formvars['column2']."".$formvars['column3']."".$formvars['column4']."");
				return false;
			}
			
			return true;
		}
		else		
			return false;		
    }
	
	function EditIntoDBSwitch(&$formvars)
    {
	
		$edit_query = 'UPDATE '.$this->tablenameswitch.' SET
                description = "'.$this->SanitizeForSQL($formvars['description'])    .'"   
                WHERE gatewayno = "'.$formvars['serialno'].'" '; 
				
        if(!mysqli_query($this->connection,$edit_query))
        {
            $this->HandleDBError("Error editing data to the table\nquery:$edit_query");
            return false;
        }        
        return true;
		
    }
	
	function InsertIntoDBNode(&$formvars)
    {
		
		$content = '';
		$content2 = '';
		
		$precursor = 0;
		$precursor2 = 0;
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) &&!empty($formvars['column4']) ) //1111
		{
			$content = 'column1,column2,column3,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
						
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1110
		{
			$content = 'column1,column2,column3';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '"';
						
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1101
		{
			$content = 'column1,column2,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1100
		{
			$content = 'column1,column2';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column2']) . '"';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //1011
		{
			$content = 'column1,column3,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
			
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //1010
		{
			$content = 'column1,column3';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '"';
						
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //1001
		{
			$content = 'column1,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
						
			$precursor = 1;
		}
		
		if(!empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //1000
		{
			$content = 'column1';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column1']) . '"';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0111
		{
			$content = 'column2,column3,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0110
		{
			$content = 'column2,column3';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column3']) . '"';
						
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0101
		{
			$content = 'column2,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column2']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
						
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && !empty($formvars['column2']) && empty($formvars['column3']) && empty($formvars['column4']) ) //0100
		{
			$content = 'column2';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column2']) . '"';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && !empty($formvars['column4']) ) //0011
		{
			$content = 'column3,column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column3']) . '",
						"' . $this->SanitizeForSQL($formvars['column4']) . '"';
						
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && !empty($formvars['column3']) && empty($formvars['column4']) ) //0010
		{
			$content = 'column3';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column3']) . '"';
			
			$precursor = 1;
		}
		
		if(empty($formvars['column1']) && empty($formvars['column2']) && empty($formvars['column3']) && !empty($formvars['column4']) ) //0001
		{
			$content = 'column4';
			$content2 =  '"' . $this->SanitizeForSQL($formvars['column4']) . '"';
			
			$precursor = 1;
		}
			
		if(!empty($formvars['column5']) && empty($formvars['column6']) ) //10
		{
			if($precursor == 1) //got something before
			{
				$content = $content . ',column5';
				$content2 = $content . ', "'. $formvars['column5'] .'"';
			}
			else //dont have something before
			{
				$content .= 'column5';
				$content2 =  ' "'. $formvars['column5'] .'" ';
			}
			
			$precursor2 = 1;
		}
		
		if(empty($formvars['column5']) && !empty($formvars['column6']) ) //01
		{
			if($precursor == 1) //got something before
			{
				$content =  $content . ',column6';
				$content2 = $content2 . ', "'. $formvars['column6'] .'"';
			}
			else //dont have something before
			{
				$content = $content . 'column6';
				$content2 =  $content2 . ' "'. $formvars['column6'] .'" ';
			}
			
			$precursor2 = 1;
		}
		
		if(!empty($formvars['column5']) && !empty($formvars['column6']) ) //11
		{
			if($precursor == 1) //got something before
			{
				$content = $content . ',column5,column6';
				$content2 = $content2 .  ', "' . $formvars['column5'] . '",
						"' . $formvars['column6'] . '"';
			}
			else //dont have something before
			{
				$content = $content . 'column6';
				$content2 = $content2 .  ' "' . $formvars['column5'] . '",
						"' . $formvars['column6'] . '"';
			}
			
			$precursor2 = 1;
		}
		
		if(!empty($formvars['latitude']) && !empty($formvars['longitude']))
		{
			$location = $formvars['latitude'].";".$formvars['longitude'];
			
			if($precursor == 1 || $precursor2 == 1){
				$content = $content . ',position';
				$content2 = $content2 . ', "'. $location . '"';
			}
			else{
				$content = $content . 'position';
				$content2 = $content2 . ' "' . $location . '"';
			}
		}
		
	
		if(!empty($formvars['column1']) || !empty($formvars['column2']) || !empty($formvars['column3']) || !empty($formvars['column4']) || !empty($formvars['column5']) || !empty($formvars['column6']) || !empty($formvars['latitude']) || !empty($formvars['longitude']) ) 
		{
			$insert_query = 'insert into '.$this->tablenamenode.'(
                username,
                gatewayno,
				description,
				location,
				'.$content.'
                )
                values
                (
                "' . $this->SanitizeForSQL($_SESSION['username_of_user']) . '",
				"' . $this->SanitizeForSQL($formvars['serialno']) . '",
				"' . $this->SanitizeForSQL($formvars['description']) . '",
				"' . $this->SanitizeForSQL($formvars['location']) . '",
				'.$content2.'
                )';  
				
			if(!mysqli_query($this->connection,$insert_query))
			{
				//$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
				return false;
			}
			else
				return true;
		}
		else
			return false;
		
        
		
    }
	
	function InsertIntoDBSwitch(&$formvars)
    {
		
		$insert_query = 'insert into '.$this->tablenameswitch.'(
                username,
                gatewayno,
				description
                )
                values
                (
                "' . $this->SanitizeForSQL($_SESSION['username_of_user']) . '",
				"' . $this->SanitizeForSQL($formvars['serialno']) . '",
				"' . $this->SanitizeForSQL($formvars['description']) . '"
                )';      
        if(!mysqli_query($this->connection,$insert_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
		
    }
	
	function InsertIntoDB(&$formvars)
    {
		
        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);
	
		if (strpos($formvars['callingcode'] , '+') === false) 
			$callingcode = '+'.$formvars['callingcode'];
		else
			$callingcode = $formvars['callingcode'];
		
		date_default_timezone_set('Asia/Singapore');
		$date = date("Y-m-d H:i:s"); 
		
		$uniq = uniqid($formvars['username']);
		
		$writekey = substr(md5($uniq.$date),0,15);
		$readkey = substr(md5($date.$uniq),0,15);
		
        $formvars['confirmcode'] = $confirmcode;  
		
		$content = '';
		$i_content = '';
		
		if(!empty($formvars['address']) && !empty($formvars['postalcode'])) //11
		{
			$content = 'address, postalcode,';
			$i_content = '"' . $this->SanitizeForSQL($formvars['address']) . '",
				"' . $this->SanitizeForSQL($formvars['postalcode']) . '",';
		}
		
		if(!empty($formvars['address']) && empty($formvars['postalcode'])) //10
		{
			$content = 'address,';
			$i_content = '"' . $this->SanitizeForSQL($formvars['address']) . '",';
		}
		
		if(!empty($formvars['address']) && empty($formvars['postalcode'])) //01
		{
			$content = 'postalcode,';
			$i_content = '"' . $this->SanitizeForSQL($formvars['postalcode']) . '",';
		}
		
		if(empty($formvars['address']) && empty($formvars['postalcode'])) //00
		{
			$content = '';
			$i_content = '';
		}
		
        $insert_query = 'insert into '.$this->tablename.'(
                name,
                email,
                username,
                password,
                confirmcode,
				'.$content.'
				mobileno,
				callingcode,
				writekey,
				readkey,
				smsemail,
				smsmobileno
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['name']) . '",
                "' . $this->SanitizeForSQL($formvars['email']) . '",
                "' . $this->SanitizeForSQL($formvars['username']) . '",
                "' . password_hash($formvars['password'] , PASSWORD_DEFAULT) . '",
                "' . $confirmcode . '",
				'  . $i_content. '
				"' . $this->SanitizeForSQL($formvars['mobileno']) . '",
				"' . $callingcode . '",
				"' . $writekey . '",
				"' . $readkey . '",
				"' . $this->SanitizeForSQL($formvars['email']) . '",
				"' . $this->SanitizeForSQL($formvars['mobileno']) . '"
                )';      
				
        if(!mysqli_query($this->connection,$insert_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
    }
	
	function CreateAPIKey($username)
	{	
		date_default_timezone_set('Asia/Singapore');
		$date = date("Y-m-d H:i:s"); 
		
		$uniq = uniqid($username);
		
		$writekey = substr(md5($uniq.$date),0,15);
		$readkey = substr(md5($date.$uniq),0,15);
		
		$formvars = array();
		$formvars['username'] = $username;
		$formvars['writekey'] = $writekey;
		$formvars['readkey'] = $readkey;
		
		if(!$this->UpdateToDatabaseKey($formvars))
        {
            return false;
        }
		
		return true;
		
	}
	
	function GenerateSerialNo(&$formvars)
	{
		$str = "";
		$characters = array_merge(range('a','z'));
		$max = count($characters) - 1;
		for ($i = 0; $i < 6; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		
		$formvars['serialno'] = $str;
	}
	
	function GenerateToken($username)
	{
		if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
		
		date_default_timezone_set('Asia/Singapore');
		$date = date("Y-m-d H:i:s"); 
		$token = substr(md5($username.$date),0,10);
		
		$insert_query = 'insert into '.$this->tablenametoken.'(
                username,
                token
                )
                values
                (
                "' . $this->SanitizeForSQL($username) . '",
				"' . $token . '"
                )';      
				
				
        if(!mysqli_query($this->connection,$insert_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
		
		if(!$this->SendUserTokenSMS($username,$token))
		{
			return false;
		}
		
		return true;
		
	}
	
	function DeleteToken($username)
	{
		if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
		
		$delete_query = "DELETE FROM ".$this->tablenametoken." WHERE ".
						"username = '" . $this->SanitizeForSQL($username) . "' ";    
				
				
        if(!mysqli_query($this->connection,$delete_query))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$delete_query");
            return false;
        }        
		
		return true;
		
	}
	
	function DeleteMonthRecord($username)
	{
		if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
		
		$config = parse_ini_file("../private/config.ini");
		$admin = $config['adminname'];
		
		if(strcasecmp($admin, $username) == 0)
		{
			$select_qry = "SELECT * FROM ".$this->tablenamenode." ";
			$query = mysqli_query($this->connection,$select_qry);
			if(mysqli_num_rows($query) > 0)
			{
				while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					$gatewayno[] = $result['gatewayno'];	
				}
				
				foreach($gatewayno as $key => $value)
				{
					$delete_qry = "DELETE FROM ".$value." WHERE timestamp < DATE_SUB(NOW(), INTERVAL 1 MONTH) ";
					if(!mysqli_query($this->connection,$delete_qry))
					{
						$this->HandleDBError("$delete_qry");
						return false;
					}  
				}
			}
			
			return true;
			
		}
				
	}
	
	function SendUserTokenSMS($username,$token)
	{
		$message = " Dear ".$username." , your access code is : ";
		$message .= $token;
		
		if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }    
		
		$num_query = "SELECT * FROM ".$this->tablename." WHERE ".
					 "username = '" . $this->SanitizeForSQL($username) . "' "; 
					 
		$query = mysqli_query($this->connection,$num_query);
		if($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
		{
			$num = $result['mobileno'];
			$code = $result['callingcode'];
		}
		
		$topic = $this->SMSTopicPass;
		$num = $code.$num;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"myeziot.com/sms");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
					"topic=".$topic."&mobileno=".$num."&msg=".$message."");
					
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		curl_close ($ch);
					
		return true;
	}
	
	function SendUserUpdateSMS($user_rec)
	{
		$message = "[Notice] Dear ".$user_rec['username']." , your password has been changed recently in ".$this->sitename.".";
		$message .= " Please ensure that you are the one who initiated this operation. ";
		
		$topic = $this->SMSTopicPass;
		$num = $user_rec['mobileno'];
		$code = $user_rec['callingcode'];
		$num = $code.$num;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"myeziot.com/sms");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
					"topic=".$topic."&mobileno=".$num."&msg=".$message."");
					
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		curl_close ($ch);
					
		return true;
	}
	
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
	
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysqli_real_escape_string" ) )
        {
              $ret_str = mysqli_real_escape_string( $this->connection , $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

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
	
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }    
}
?>
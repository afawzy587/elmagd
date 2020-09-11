<?php if(!defined("inside")) exit;
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

class loginClass
{
	 var $name;
	 var $email;
	 var $password;
	 var $remember;
	 var $id;
	 var $tableName 	= "settings_users";
	 function doLogin($email,$pass)
	 {
		if($email !=""  || $pass != "")
		{
			if($this->isLogged() == false)
			{
				global $db;
				$query = $db->query("SELECT * FROM `".$this->tableName."` WHERE `users_email`='".$email."' AND `users_status` !='0'   LIMIT 1");
				$queryTotal = $db->resultcount();
				if($queryTotal == 1)
				{
					$userData = $db->fetchitem($query);
					if (password_verify($pass, $userData['users_password']))
					{
						// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
						session_start();
						session_regenerate_id();
						$_SESSION['loggedin'] = true;
						$_SESSION['name']     = $userData['users_name'];
						$_SESSION['photo']    = $userData['users_photo'];
						$_SESSION['email']    = $userData['users_email'];
						$_SESSION['id']       = $userData['users_sn'];
						$query = $db->query("UPDATE `".$this->tableName."` SET `users_last_login`= NOW() WHERE `users_sn`='".$userData['users_sn']."'");
						return 1; // login success
					} else {
						return 2; // Incorrect password!
					}
				}else{
					return 3; // user not found
				}
			}else{
				return 4; // login before
			}
		}else{
			return false;  // empty data
		}
	 }
	 function doLogout()
	 {
		if($this->isLogged() == true)
		{
			// query and get data from db
			$this->doDestroy();
			return  true;
		}else{return false;}
	 }
	 function doDestroy()
	 {
		session_start();
		session_unset();
		session_destroy();
		session_write_close();
	 }
	 function doCheck()
	 {
		if($this->isLogged() == true)
		{
			global $db;
			$email = $_SESSION['email'];
			$id    = $_SESSION['id'];
			$query = $db->query("SELECT * FROM `".$this->tableName."` WHERE `users_email`='".$_SESSION['email']."' AND `users_sn`='".$_SESSION['id']."' ");
			$queryTotal = $db->resultcount();
			if($queryTotal == 1)
			{
				return true;
			}else{
				$this->doDestroy();
				return false;
			}
		}else{
			$this->doDestroy();
			return false;
		}
	 }
	 function getUserInformation()
	 {
		if($this->isLogged() == true)
		{
			global $db;
			$email = $_SESSION['email'];
			$id    = $_SESSION['id'];
			$query = $db->query("SELECT * FROM `".$this->tableName."` WHERE `users_email`='".$email."' AND `users_sn`='".$id."' LIMIT 1 ");
			$queryTotal = $db->resultcount();
			if($queryTotal == 1)
			{
				$userInformation = $db->fetchitem($query);
				return array(
					"users_name"			    	        => 		$userInformation['users_name'],
					"users_birthday"			    	    => 		$userInformation['users_birthday'],
					"users_managment_id"    		        => 		$userInformation['users_managment_id'],
					"users_job_id"		                    => 		$userInformation['users_job_id'],
					"users_qualification"      		        => 		$userInformation['users_qualification'],
					"users_graduation_year"		            => 		$userInformation['users_graduation_year'],
					"users_phone"		                    => 		$userInformation['users_phone'],
					"users_email"		                    => 		$userInformation['users_email'],
					"users_photo"		                    => 		$userInformation['users_photo'],
					"users_address"		                    => 		$userInformation['users_address'],
					"users_password"		                => 		$userInformation['users_password'],
					"users_group"		                    => 		$userInformation['users_group'],
					"users_salary"		                    => 		$userInformation['users_salary'],
					"users_last_login"		                => 		$userInformation['users_last_login'],
					"users_status"		                    => 		$userInformation['users_status'],
				);
			}else{$this->doDestroy();return false;}
		}else{$this->doDestroy();return false;}
	 }
	 function isLogged()
	 {
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){return true;}else{return false;}
		 
	 }
	 function checkmailisfound($email)
	 {
		global $db;
		$query = $db->query("SELECT * FROM `".$this->tableName."` WHERE `users_email`='".$email."' AND `users_status` !='1'   LIMIT 1");
		$queryTotal = $db->resultcount();
		if($queryTotal == 1)
		{
			$userData = $db->fetchitem($query);
			if($userData['users_recovery_code'] != 0 && (strtotime($userData['users_recovery_expired']) > time()))
			{
				return 'send';
			}else{
				return $userData['users_sn'];
			}
		}else{
			return 0;
		}

	 }

}



?>

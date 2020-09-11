<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_users
{
	var $tableName 	= "settings_users";

	function getsiteSettings_users($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `users_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_status` != '0' ".$search." ORDER BY `users_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_users($addon = "")
	{
		if($q != "")
		{
			$search = "AND `users_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `users_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_usersInformation($users_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_sn` = '".$users_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "users_sn"			                   => 		 $sitegroup['users_sn'],
                "users_name"			               => 		 $sitegroup['users_name'],
                "users_birthday"                       =>         $sitegroup['users_birthday'],
                "users_department_id"                  =>         $sitegroup['users_department_id'],
                "users_job_id"                         =>         $sitegroup['users_job_id'],
                "users_graduation_year"                =>         $sitegroup['users_graduation_year'],
                "users_qualification"                  =>         $sitegroup['users_qualification'],
                "users_phone"                          =>         $sitegroup['users_phone'],
                "users_email"                          =>         $sitegroup['users_email'],
                "users_address"                        =>         $sitegroup['users_address'],
                "users_group"                          =>         $sitegroup['users_group'],
                "users_salary"                         =>         $sitegroup['users_salary'],
                "users_photo"                          =>         $sitegroup['users_photo'],
            	"users_status"                         =>          $sitegroup['users_status']
            );
        }else{return null;}
	}

	function setSettings_usersInformation($Settings_users)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_name` LIKE '".$Settings_users['users_name']."' AND `users_sn` !='".$Settings_users['users_sn']."' AND users_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_phone` LIKE '".$Settings_users['users_phone']."' AND `users_sn` !='".$Settings_users['users_sn']."' AND users_status != 0  LIMIT 1 ");
			$quTotal = $GLOBALS['db']->resultcount();
			if($quTotal == 0)
			{
				if($Settings_users['users_photo'] != "")
				{
					$users_photo = "`users_photo`='".$Settings_users['users_photo']."',";
				}else
				{
					$users_photo = "";
				}
				
				if($Settings_users['users_password'] != "")
				{
					$users_password = "`users_password`='".$Settings_users['users_password']."',";
				}else
				{
					$users_password = "";
				}
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
						`users_name`                  =       '".$Settings_users['users_name']."',".$users_photo."
						`users_birthday`              =       '".$Settings_users['users_birthday']."',".$users_password."
						`users_department_id`         =       '".$Settings_users['users_department_id']."',
						`users_job_id`                =       '".$Settings_users['users_job_id']."',
						`users_graduation_year`       =       '".$Settings_users['users_graduation_year']."',
						`users_qualification`         =       '".$Settings_users['users_qualification']."',
						`users_phone`                 =       '".$Settings_users['users_phone']."',
						`users_email`                 =       '".$Settings_users['users_email']."',
						`users_address`               =       '".$Settings_users['users_address']."',
						`users_group`                 =       '".$Settings_users['users_group']."',
						`users_salary`                =       '".$Settings_users['users_salary']."'
				  WHERE `users_sn`    	            = 	    '".$Settings_users['users_sn']."' LIMIT 1 ");
				return 1;
			}else{
				return 3;
			}

		}else{
			return 2;

		}
	}
	
	function addSettings_users($Settings_users)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_name` LIKE '".$Settings_users['users_name']."' AND users_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `users_phone` LIKE '".$Settings_users['users_phone']."' AND users_status != 0  LIMIT 1 ");
			$quTotal = $GLOBALS['db']->resultcount();
			if($quTotal == 0)
			{
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
				(`users_sn`, `users_name`, `users_birthday`, `users_department_id`, `users_job_id`, `users_qualification`, `users_graduation_year`, `users_phone`, `users_email`,
				`users_photo`, `users_address`, `users_password`, `users_group`, `users_salary`,`users_status`) 
				VALUES ( NULL ,'".$Settings_users['users_name']."','".$Settings_users['users_birthday']."','".$Settings_users['users_department_id']."','".$Settings_users['users_job_id']."','".$Settings_users['users_qualification']."','".$Settings_users['users_graduation_year']."','".$Settings_users['users_phone']."'
				,'".$Settings_users['users_email']."','".$Settings_users['users_photo']."','".$Settings_users['users_address']."','".$Settings_users['users_password']."','".$Settings_users['users_group']."','".$Settings_users['users_salary']."',1)");
				return 1;
			}else{
				return 3;
			}

		}else{
			return 2;

		}
	}



}
?>

<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemReminders
{
	var $tableName 	= "Reminders";

	function getsiteReminders($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `title` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `reminders_status` != '0' ".$search." ORDER BY `reminders_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalReminders($addon = "")
	{
		if($q != "")
		{
			$search = "AND `title` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `reminders_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getRemindersInformation($reminders_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `reminders_sn` = '".$reminders_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "reminders_sn"			                   => 		 $sitegroup['reminders_sn'],
                "title"			                           => 		 $sitegroup['title'],
                "reminders_date"                    =>          $sitegroup['reminders_date'],
            	"reminders_status"                         =>          $sitegroup['reminders_status']
            );
        }else{return null;}
	}

	function setRemindersInformation($Reminders)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `title` LIKE '".$Reminders['title']."' AND `reminders_sn` !='".$Reminders['reminders_sn']."' AND reminders_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`title`                        =       '".$Reminders['title']."',
					`reminders_date`        =       '".$Reminders['reminders_date']."'
			  WHERE `reminders_sn`    	           = 	    '".$Reminders['reminders_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addReminders($Reminders)
	{
		$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
		(`reminders_sn`, `reminders_type`,`title`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_read`, `reminders_status`) 
		VALUES 
		( NULL ,'defult','".$Reminders['title']."','".$Reminders['reminders_date']."','day','1','".$Reminders['reminders_date']."','".$Reminders['reminders_date']."',0,1)");
		return 1;
	}



}
?>

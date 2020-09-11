<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_user_group
{
	var $tableName 	= "settings_user_group";

	function getsiteSettings_user_group($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `group_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `group_status` != '0' ".$search." ORDER BY `group_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_user_group($addon = "")
	{
		if($q != "")
		{
			$search = "AND `group_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `group_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_user_groupInformation($group_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `group_sn` = '".$group_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "group_sn"			                 => 		 $sitegroup['group_sn'],
                "group_name"			                 => 		 $sitegroup['group_name'],
                "group_description"                    =>          $sitegroup['group_description'],
            	"group_status"                         =>          $sitegroup['group_status']
            );
        }else{return null;}
	}

	function setSettings_user_groupInformation($Settings_user_group)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `group_name` LIKE '%".$Settings_user_group['group_name']."%' AND `group_sn` !='".$Settings_user_group['group_sn']."' AND group_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`group_name`                  =       '".$Settings_user_group['group_name']."',
					`group_description`           =       '".$Settings_user_group['group_description']."'
			  WHERE `group_sn`    	            = 	    '".$Settings_user_group['group_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addSettings_user_group($Settings_user_group)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `group_name` LIKE '%".$Settings_user_group['group_name']."%' AND group_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`group_sn`, `group_name`, `group_description`,`group_status`) 
			VALUES ( NULL ,'".$Settings_user_group['group_name']."','".$Settings_user_group['group_description']."',1)");
			return 1;

		}else{
			return 2;

		}
	}



}
?>

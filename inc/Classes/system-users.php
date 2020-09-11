<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_departments
{
	var $tableName 	= "settings_departments";

	function getsiteSettings_departments($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `departments_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `departments_status` != '0' ".$search." ORDER BY `departments_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_departments($addon = "")
	{
		if($q != "")
		{
			$search = "AND `departments_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `departments_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_departmentsInformation($departments_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `departments_sn` = '".$departments_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "departments_sn"			                 => 		 $sitegroup['departments_sn'],
                "departments_name"			                 => 		 $sitegroup['departments_name'],
                "departments_description"                    =>          $sitegroup['departments_description'],
            	"departments_status"                         =>          $sitegroup['departments_status']
            );
        }else{return null;}
	}

	function setSettings_departmentsInformation($Settings_departments)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `departments_name` LIKE '%".$Settings_departments['departments_name']."%' AND `departments_sn` !='".$Settings_departments['departments_sn']."' AND departments_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`departments_name`                  =       '".$Settings_departments['departments_name']."',
					`departments_description`           =       '".$Settings_departments['departments_description']."'
			  WHERE `departments_sn`    	            = 	    '".$Settings_departments['departments_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addSettings_departments($Settings_departments)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `departments_name` LIKE '%".$Settings_departments['departments_name']."%' AND departments_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`departments_sn`, `departments_name`, `departments_description`,`departments_status`) 
			VALUES ( NULL ,'".$Settings_departments['departments_name']."','".$Settings_departments['departments_description']."',1)");
			return 1;

		}else{
			return 2;

		}
	}



}
?>

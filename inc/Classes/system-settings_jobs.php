<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_jobs
{
	var $tableName 	= "settings_jobs";

	function getsiteSettings_jobs($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND j.`jobs_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` j INNER JOIN `settings_departments` d ON j.`jobs_department` = d.departments_sn WHERE j.`jobs_status` != '0' ".$search." ORDER BY j.`jobs_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_jobs($addon = "")
	{
		if($q != "")
		{
			$search = "AND `jobs_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `jobs_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_jobsInformation($jobs_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` j INNER JOIN `settings_departments` d ON j.`jobs_department` = d.departments_sn WHERE `jobs_sn` = '".$jobs_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "jobs_sn"			                 => 		 $sitegroup['jobs_sn'],
                "jobs_name"			                 => 		 $sitegroup['jobs_name'],
                "department_name"                    =>          $sitegroup['department_name'],
                "jobs_department"                    =>          $sitegroup['jobs_department'],
            	"jobs_status"                        =>          $sitegroup['jobs_status']
            );
        }else{return null;}
	}

	function setSettings_jobsInformation($Settings_jobs)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `jobs_name` LIKE '%".$Settings_jobs['jobs_name']."' AND `jobs_sn` !='".$Settings_jobs['jobs_sn']."' AND jobs_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`jobs_name`                  =       '".$Settings_jobs['jobs_name']."',
					`jobs_department`           =       '".$Settings_jobs['jobs_department']."'
			  WHERE `jobs_sn`    	            = 	    '".$Settings_jobs['jobs_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addSettings_jobs($Settings_jobs)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `jobs_name` LIKE '%".$Settings_jobs['jobs_name']."' AND jobs_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`jobs_sn`, `jobs_name`, `jobs_department`,`jobs_status`) 
			VALUES ( NULL ,'".$Settings_jobs['jobs_name']."','".$Settings_jobs['jobs_department']."',1)");
			return 1;

		}else{
			return 2;

		}
	}



}
?>

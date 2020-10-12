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
                "group_name"			             => 		 $sitegroup['group_name'],
                "group_description"                  =>          $sitegroup['group_description'],
				"setting_company"                    =>          $sitegroup['setting_company'],
				"settings_department"                =>          $sitegroup['settings_department'],
				"setting_jobs"                       =>          $sitegroup['setting_jobs'],
				"setting_stocks"                     =>          $sitegroup['setting_stocks'],
				"settings_products"                  =>          $sitegroup['settings_products'],
				"settings_users"                     =>          $sitegroup['settings_users'],
				"settings_clients"                   =>          $sitegroup['settings_clients'],
				"settings_suppliers"                 =>          $sitegroup['settings_suppliers'],
				"settings_banks"                     =>          $sitegroup['settings_banks'],
				"clients_pricing"                    =>          $sitegroup['clients_pricing'],
				"clients_finance"                    =>          $sitegroup['clients_finance'],
				"clients_old_pricing"                =>          $sitegroup['clients_old_pricing'],
				"clients_payments"                   =>          $sitegroup['clients_payments'],
				"operations"                         =>          $sitegroup['operations'],
				"expense"                            =>          $sitegroup['expense'],
				"deposit_check"                      =>          $sitegroup['deposit_check'],
				"bank_transfer"                      =>          $sitegroup['bank_transfer'],
				"client_payment"                     =>          $sitegroup['client_payment'],
				"supplier_payment"                   =>          $sitegroup['supplier_payment'],
				"settings_user_group"                =>          $sitegroup['settings_user_group'],
				"reminders"                          =>          $sitegroup['reminders'],
				"delete_deposits"                    =>          $sitegroup['delete_deposits'],
            	"group_status"                       =>          $sitegroup['group_status']
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
					`group_description`           =       '".$Settings_user_group['group_description']."',
					`setting_company`             =       '".$Settings_user_group['setting_company']."',
					`settings_department`         =       '".$Settings_user_group['settings_department']."',
					`setting_jobs`                =       '".$Settings_user_group['setting_jobs']."',
					`setting_stocks`              =       '".$Settings_user_group['setting_stocks']."',
					`settings_products`           =       '".$Settings_user_group['settings_products']."',
					`settings_users`              =       '".$Settings_user_group['settings_users']."',
					`settings_clients`            =       '".$Settings_user_group['settings_clients']."',
					`settings_suppliers`          =       '".$Settings_user_group['settings_suppliers']."',
					`settings_banks`              =       '".$Settings_user_group['settings_banks']."',
					`clients_pricing`             =       '".$Settings_user_group['clients_pricing']."',
					`clients_finance`             =       '".$Settings_user_group['clients_finance']."',
					`clients_old_pricing`         =       '".$Settings_user_group['clients_old_pricing']."',
					`clients_payments`            =       '".$Settings_user_group['clients_payments']."',
					`operations`                  =       '".$Settings_user_group['operations']."',
					`expense`                     =       '".$Settings_user_group['expense']."',
					`deposit_check`               =       '".$Settings_user_group['deposit_check']."',
					`bank_transfer`               =       '".$Settings_user_group['bank_transfer']."',
					`client_payment`              =       '".$Settings_user_group['client_payment']."',
					`supplier_payment`            =       '".$Settings_user_group['supplier_payment']."',
					`reminders`                   =       '".$Settings_user_group['reminders']."',
					`delete_deposits`             =       '".$Settings_user_group['delete_deposits']."',
					`settings_user_group`         =       '".$Settings_user_group['settings_user_group']."'
			  WHERE `group_sn`    	              = 	  '".$Settings_user_group['group_sn']."' LIMIT 1 ");
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

<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_banks
{
	var $tableName 	= "settings_banks";

	function getsiteSettings_banks($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `banks_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `banks_status` != '0' ".$search." ORDER BY `banks_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}
	
	
	function getaccountsSettings_banks($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `banks_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `banks_status` != '0' ".$search." ORDER BY `banks_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $banks = $GLOBALS['db']->fetchlist();
			foreach($banks as $k => $v)
			{
				$banks_creditquery = $GLOBALS['db']->query("SELECT * ,'credit' as type FROM `settings_banks_credit` WHERE `banks_credit_bank_id` = '".$v['banks_sn']."' AND `banks_credit_status` !='0'   ");
				$banks_creditqueryTotal = $GLOBALS['db']->resultcount();
				if($banks_creditqueryTotal > 0)
				{
					$banks_credit = $GLOBALS['db']->fetchlist();
				}
				$banks[$k]['banks_accounts'] = $banks_credit;
				
				
				$banks_currentquery = $GLOBALS['db']->query("SELECT * , 'current' as type FROM `settings_banks_current` WHERE `banks_current_bank_id` = '".$v['banks_sn']."'  LIMIT 1 ");
				$banks_currentqueryTotal = $GLOBALS['db']->resultcount();
				if($banks_currentqueryTotal > 0)
				{
					$banks_current = $GLOBALS['db']->fetchitem($banks_currentquery);
					array_push($banks[$k]['banks_accounts'],$banks_current);

				}
				


				$banks_savingquery = $GLOBALS['db']->query("SELECT * , 'save' as type FROM `settings_banks_saving` WHERE `banks_saving_bank_id` = '".$v['banks_sn']."'  LIMIT 1 ");
				$banks_savingqueryTotal = $GLOBALS['db']->resultcount();
				if($banks_savingqueryTotal > 0)
				{
					$banks_saving = $GLOBALS['db']->fetchitem($banks_savingquery);
					array_push($banks[$k]['banks_accounts'],$banks_saving);

				}

				


			}
			
			return($banks);
        }else{return null;}
	}

	function getTotalSettings_banks($addon = "")
	{
		if($q != "")
		{
			$search = "AND `banks_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `banks_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_banksInformation($banks_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `banks_sn` = '".$banks_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
			
			$banks_creditquery = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE `banks_credit_bank_id` = '".$sitegroup['banks_sn']."' AND `banks_credit_status` != '0'   ");
			$banks_creditqueryTotal = $GLOBALS['db']->resultcount();
			if($banks_creditqueryTotal > 0)
			{
				$banks_credit = $GLOBALS['db']->fetchlist();
			}
			$banks_currentquery = $GLOBALS['db']->query("SELECT * FROM `settings_banks_current` WHERE `banks_current_bank_id` = '".$sitegroup['banks_sn']."'  LIMIT 1 ");
			$banks_currentqueryTotal = $GLOBALS['db']->resultcount();
			if($banks_currentqueryTotal > 0)
			{
				$banks_current = $GLOBALS['db']->fetchitem($banks_currentquery);
			}
			
			$banks_savingquery = $GLOBALS['db']->query("SELECT * FROM `settings_banks_saving` WHERE `banks_saving_bank_id` = '".$sitegroup['banks_sn']."'  LIMIT 1 ");
			$banks_savingqueryTotal = $GLOBALS['db']->resultcount();
			if($banks_savingqueryTotal > 0)
			{
				$banks_saving = $GLOBALS['db']->fetchitem($banks_savingquery);
			}
            return array(
                "banks_sn"			                   => 		   $sitegroup['banks_sn'],
                "banks_name"			               => 		   $sitegroup['banks_name'],
                "banks_account_number"                 =>          $sitegroup['banks_account_number'],
                "banks_credit"                         =>          $banks_credit,
            	"banks_status"                         =>          $sitegroup['banks_status'],
            	"banks_current_sn"                     =>          $banks_current['banks_current_sn'],
            	"banks_current_account_number"         =>          $banks_current['banks_current_account_number'],
            	"banks_current_opening_balance"        =>          $banks_current['banks_current_opening_balance'],
            	"banks_saving_sn"                      =>          $banks_saving['banks_saving_sn'],
            	"banks_saving_account_number"          =>          $banks_saving['banks_saving_account_number'],
            	"banks_saving_open_balance"            =>          $banks_saving['banks_saving_open_balance'],
            	"banks_saving_interest_rate"           =>          $banks_saving['banks_saving_interest_rate'],
            	"banks_saving_duration_of_interest"    =>          $banks_saving['banks_saving_duration_of_interest'],
            );
        }else{return null;}
	}

	function setSettings_banksInformation($Settings_banks)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `banks_name` LIKE '".$Settings_banks['banks_name']."' AND `banks_sn` !='".$Settings_banks['banks_sn']."' AND banks_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`banks_name`                  =       '".$Settings_banks['banks_name']."',
					`banks_account_number`        =       '".$Settings_banks['banks_account_number']."'
			  WHERE `banks_sn`    	              = 	    '".$Settings_banks['banks_sn']."' LIMIT 1 ");
			
			foreach($Settings_banks['banks_credit_sn'] as $k => $v)
			{
				if($v > 0 )
				{
					$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_banks_credit` SET
					`banks_credit_name`                   =       '".$Settings_banks['banks_credit_name'][$k]."',
					`banks_credit_code`                   =       '".$Settings_banks['banks_credit_code'][$k]."',
					`banks_credit_open_balance`           =       '".$Settings_banks['banks_credit_open_balance'][$k]."',
					`banks_credit_repayment_period`       =       '".$Settings_banks['banks_credit_repayment_period'][$k]."',
					`banks_credit_interest_rate`          =       '".$Settings_banks['banks_credit_interest_rate']."',
					`banks_credit_duration_of_interest`   =       '".$Settings_banks['banks_credit_duration_of_interest'][$k]."',
					`banks_credit_limit_value`            =       '".$Settings_banks['banks_credit_limit_value'][$k]."',
					`banks_credit_cutting_ratio`          =       '".$Settings_banks['banks_credit_cutting_ratio'][$k]."',
					`banks_credit_repayment_type`         =       '".$Settings_banks['banks_credit_repayment_type'][$k]."',
					`banks_credit_client`                 =       '".$Settings_banks['banks_credit_client'][$k]."',
					`banks_credit_product`                =       '".$Settings_banks['banks_credit_product'][$k]."'
					WHERE `banks_credit_sn`    	          = 	    '".$v."' LIMIT 1 ");
					
					$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
					`banks_finance_open_balance`          =       '".$Settings_banks['banks_credit_open_balance'][$k]."',
					WHERE `banks_finance_account_type` = 'credit' AND `banks_finance_bank_id`   =  '".$Settings_banks['banks_sn']."' AND `banks_finance_account_id` = '".$v."' LIMIT 1 ");
				}else{
					if($Settings_banks['banks_credit_name'][$k] !== "")
					{
						$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_credit`
						(`banks_credit_sn`, `banks_credit_bank_id`, `banks_credit_name`, 
						`banks_credit_code`, `banks_credit_open_balance`, `banks_credit_repayment_period`, `banks_credit_repayment_type`, 
						`banks_credit_interest_rate`, `banks_credit_duration_of_interest`, `banks_credit_limit_value`,
						`banks_credit_cutting_ratio`, `banks_credit_client`, `banks_credit_product`) 
						VALUES ( NULL ,'".$Settings_banks['banks_sn']."','".$Settings_banks['banks_credit_name'][$k]."'
						,'".$Settings_banks['banks_credit_code'][$k]."','".$Settings_banks['banks_credit_open_balance'][$k]."','".$Settings_banks['banks_credit_repayment_period'][$k]."','".$Settings_banks['banks_credit_repayment_type'][$k]."'
						,'".$Settings_banks['banks_credit_interest_rate'][$k]."','".$Settings_banks['banks_credit_duration_of_interest'][$k]."','".$Settings_banks['banks_credit_limit_value'][$k]."'
						,'".$Settings_banks['banks_credit_cutting_ratio'][$k]."','".$Settings_banks['banks_credit_client'][$k]."','".$Settings_banks['banks_credit_product'][$k]."')");
					}
				}
				
			}
			if($Settings_banks['banks_saving_sn'] != 0)
			{
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_banks_saving` SET
					`banks_saving_account_number`         =       '".$Settings_banks['banks_saving_account_number']."',
					`banks_saving_open_balance`           =       '".$Settings_banks['banks_saving_open_balance']."',
					`banks_saving_duration_of_interest`   =       '".$Settings_banks['banks_saving_duration_of_interest']."',
					`banks_saving_interest_rate`          =       '".$Settings_banks['banks_saving_interest_rate']."'
				WHERE `banks_saving_sn`    	              = 	    '".$Settings_banks['banks_saving_sn']."' LIMIT 1 ");
				
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
						`banks_finance_open_balance`          =       '".$Settings_banks['banks_saving_open_balance']."',
				WHERE `banks_finance_account_type` = 'saving' AND `banks_finance_bank_id`   =  '".$Settings_banks['banks_sn']."' AND `banks_finance_account_id` = '".$Settings_banks['banks_saving_sn']."' LIMIT 1 ");
				
			}else{
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_saving`
				(`banks_saving_sn`, `banks_saving_bank_id`, `banks_saving_account_number`, `banks_saving_open_balance`, `banks_saving_interest_rate`, `banks_saving_duration_of_interest`) 
				VALUES ( NULL ,'".$bank_id."','".$Settings_banks['banks_saving_account_number']."','".$Settings_banks['banks_saving_open_balance']."','".$Settings_banks['banks_saving_interest_rate']."','".$Settings_banks['banks_saving_duration_of_interest']."')");
				
				$save_id = $GLOBALS['db']->fetchLastInsertId();
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `setiings_banks_finance`
				(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_status`)
				VALUES ( NULL ,'".$bank_id."','saving','".$save_id."','".$Settings_banks['banks_saving_open_balance']."',1)");
				
			}
			if($Settings_banks['banks_saving_sn'] != 0)
			{
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_banks_current` SET
					`banks_current_account_number`         =       '".$Settings_banks['banks_current_account_number']."',
					`banks_current_opening_balance`        =       '".$Settings_banks['banks_current_opening_balance']."'
				WHERE `banks_current_sn`    	               = 	    '".$Settings_banks['banks_current_sn']."' LIMIT 1 ");
				
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
					`banks_finance_open_balance`          =       '".$Settings_banks['banks_current_opening_balance']."',
				WHERE `banks_finance_account_type` = 'current' AND `banks_finance_bank_id`   =  '".$Settings_banks['banks_sn']."' AND `banks_finance_account_id` = '".$Settings_banks['banks_current_sn']."' LIMIT 1 ");
				
			}else{
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_current`
				(`banks_current_sn`, `banks_current_bank_id`, `banks_current_account_number`, `banks_current_opening_balance`) 
				VALUES ( NULL ,'".$bank_id."','".$Settings_banks['banks_current_account_number']."','".$Settings_banks['banks_current_opening_balance']."')");
				$current_id = $GLOBALS['db']->fetchLastInsertId();
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `setiings_banks_finance`
				(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_status`)
				VALUES ( NULL ,'".$bank_id."','current','".$current_id."','".$Settings_banks['banks_current_opening_balance']."',1)");

			}
			
			
			
			return 1;

		}else{
			return 2;

		}
	}
	
	function addSettings_banks($Settings_banks)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `banks_name` LIKE '".$Settings_banks['banks_name']."' AND banks_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`banks_sn`, `banks_name`, `banks_account_number`,`banks_status`) 
			VALUES ( NULL ,'".$Settings_banks['banks_name']."','".$Settings_banks['banks_account_number']."',1)");
			$bank_id = $GLOBALS['db']->fetchLastInsertId();
			foreach($Settings_banks['banks_credit_name'] as $cId => $c)
			{
				if($c !== "")
				{
					$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_credit`
					(`banks_credit_sn`, `banks_credit_bank_id`, `banks_credit_name`, 
					`banks_credit_code`, `banks_credit_open_balance`, `banks_credit_repayment_period`, `banks_credit_repayment_type`, 
					`banks_credit_interest_rate`, `banks_credit_duration_of_interest`, `banks_credit_limit_value`,
					`banks_credit_cutting_ratio`, `banks_credit_client`, `banks_credit_product`) 
					VALUES ( NULL ,'".$bank_id."','".$c."'
					,'".$Settings_banks['banks_credit_code'][$cId]."','".$Settings_banks['banks_credit_open_balance'][$cId]."','".$Settings_banks['banks_credit_repayment_period'][$cId]."','".$Settings_banks['banks_credit_repayment_type'][$cId]."'
					,'".$Settings_banks['banks_credit_interest_rate'][$cId]."','".$Settings_banks['banks_credit_duration_of_interest'][$cId]."','".$Settings_banks['banks_credit_limit_value'][$cId]."'
					,'".$Settings_banks['banks_credit_cutting_ratio'][$cId]."','".$Settings_banks['banks_credit_client'][$cId]."','".$Settings_banks['banks_credit_product'][$cId]."')");
				
					$account_id = $GLOBALS['db']->fetchLastInsertId();
				
					$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `setiings_banks_finance`
					(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_status`)
					VALUES ( NULL ,'".$bank_id."','credit','".$account_id."','".$Settings_banks['banks_credit_open_balance'][$cId]."',1)");
				}
			}
			if($Settings_banks['banks_current_account_number'] != "")
			{
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_current`
				(`banks_current_sn`, `banks_current_bank_id`, `banks_current_account_number`, `banks_current_opening_balance`) 
				VALUES ( NULL ,'".$bank_id."','".$Settings_banks['banks_current_account_number']."','".$Settings_banks['banks_current_opening_balance']."')");
				
				$current_id = $GLOBALS['db']->fetchLastInsertId();
				
				
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `setiings_banks_finance`
				(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_status`)
				VALUES ( NULL ,'".$bank_id."','current','".$current_id."','".$Settings_banks['banks_current_opening_balance']."',1)");
			}
			if($Settings_banks['banks_saving_account_number'] != "")
			{
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_banks_saving`
				(`banks_saving_sn`, `banks_saving_bank_id`, `banks_saving_account_number`, `banks_saving_open_balance`, `banks_saving_interest_rate`, `banks_saving_duration_of_interest`) 
				VALUES ( NULL ,'".$bank_id."','".$Settings_banks['banks_saving_account_number']."','".$Settings_banks['banks_saving_open_balance']."','".$Settings_banks['banks_saving_interest_rate']."','".$Settings_banks['banks_saving_duration_of_interest']."')");
				
				$save_id = $GLOBALS['db']->fetchLastInsertId();
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `setiings_banks_finance`
				(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_status`)
				VALUES ( NULL ,'".$bank_id."','saving','".$save_id."','".$Settings_banks['banks_saving_open_balance']."',1)");
				
			}
			
			return 1;

		}else{
			return 2;

		}
	}
	
	function get_banks_finance()
	{
		$query = $GLOBALS['db']->query("
		SELECT b.banks_name,(SUM(`banks_finance_open_balance`) + SUM(`banks_finance_credit`)) AS credit  FROM `settings_banks` b 
		INNER JOIN `setiings_banks_finance` bf ON b.`banks_sn` = bf.`banks_finance_bank_id`
		WHERE  bf.`banks_finance_status` != 0 ORDER BY b.`banks_sn`  DESC ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}	
	}
	
	function get_bank_account($id)
	{
		$banks_creditquery = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE `banks_credit_bank_id` = '".$id."' AND `banks_credit_status` != '0'   ");
		$banks_creditqueryTotal = $GLOBALS['db']->resultcount();
		if($banks_creditqueryTotal > 0)
		{
			return($GLOBALS['db']->fetchlist());
		
		}else{
			return null;
		}
	}


	function Get_Account_data($id)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE  `banks_credit_sn` = '".$id."' AND `banks_credit_status` != '0' LIMIT 1");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
			$sitegroup = $GLOBALS['db']->fetchitem($query);
			$data = array(
                "banks_credit_repayment_period"			                   => 		   $sitegroup['banks_credit_repayment_period'],
                "banks_credit_repayment_type"			                   => 		   $sitegroup['banks_credit_repayment_type'],
				"banks_credit_interest_rate"                               =>          $sitegroup['banks_credit_interest_rate'],
				"banks_credit_duration_of_interest"                        =>          $sitegroup['banks_credit_duration_of_interest'],
				"banks_credit_limit_value"                                 =>          $sitegroup['banks_credit_limit_value'],
				"banks_credit_cutting_ratio"                               =>          $sitegroup['banks_credit_cutting_ratio'],
				"banks_credit_client"                                      =>          $sitegroup['banks_credit_client'],
				"banks_credit_product"                                     =>          $sitegroup['banks_credit_product'],
			);
			return($data);
		}else{
			return null;
		}
	}



}
?>

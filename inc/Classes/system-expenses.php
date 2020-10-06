<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemExpenses
{
	var $tableName 	= "expenses";

	function getsiteExpenses($addon = "",$search="")
	{
		if($search['q'] != "")
		{
			$s = "AND `expenses_cheque_sn` LIKE '%".$search['q']."%' ";
		}else{
			$s = "";
		}
        if($search['id'] > 0){
				$id = " AND `expenses_sn` = '".$search['id']."'";
			}else{
				$id = "" ;
			}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `expenses_status` != '0' ".$s.$id." ORDER BY `expenses_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalExpenses($addon = "", $q = "")
	{
		if($q != "")
		{
			$search = "AND `expenses_cheque_sn` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `expenses_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getExpensesInformation($expenses_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `expenses_sn` = '".$expenses_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "expenses_sn"			                  => 		 $sitegroup['expenses_sn'],
                "expenses_date"			                  => 		 $sitegroup['expenses_date'],
                "expenses_type"                           =>          $sitegroup['expenses_type'],
                "expenses_amount"                         =>          $sitegroup['expenses_amount'],
                "expenses_cheque_date"                    =>          $sitegroup['expenses_cheque_date'],
                "expenses_cheque_sn"                      =>          $sitegroup['expenses_cheque_sn'],
                "expenses_in"                             =>          $sitegroup['expenses_in'],
                "expenses_bank_id"                        =>          $sitegroup['expenses_bank_id'],
                "expenses_bank_account_type"              =>          $sitegroup['expenses_bank_account_type'],
                "expenses_bank_account_id"                =>          $sitegroup['expenses_bank_account_id'],
                "expenses_title"                          =>          $sitegroup['expenses_title'],
            	"expenses_status"                         =>          $sitegroup['expenses_status']
            );
        }else{return null;}
	}

	function setExpensesInformation($Expenses)
	{

         if($Expenses['expenses_bank_id']== 'safe')
        {
            $Expenses['expenses_in']  = 'safe';
            $Expenses['expenses_bank_id']    = 0 ;
        }else{
            $Expenses['expenses_in']  = 'bank';
        }
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `expenses_sn` = '".$Expenses['expenses_sn']."' AND expenses_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 1)
        {
			$sitegroup = $GLOBALS['db']->fetchitem($query);
			if($sitegroup['expenses_in'] == 'bank')
            {
                if($sitegroup['expenses_bank_account_id'] != 0)
                {
                    $q= "AND`banks_finance_account_id` = '".$sitegroup['expenses_bank_account_id']."' " ;
                }else{
                    $q= "";
                }
                $bankfinance = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance`
                WHERE `banks_finance_bank_id` ='".$sitegroup['expenses_bank_id']."'  AND `banks_finance_account_type` = '".$sitegroup['expenses_bank_account_type']."' ".$q." AND `banks_finance_status` != 0 LIMIT 1 ");
                $bankfinanceTotal = $GLOBALS['db']->resultcount();
                if($bankfinanceTotal == 1)
                {
                    $sitebank = $GLOBALS['db']->fetchitem($query);
                    $new = $sitebank['banks_finance_credit'] + $sitegroup['expenses_amount'];
                    $banks_total_with_benefits = $sitebank['banks_total_with_benefits'] + $sitegroup['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
                    `banks_finance_credit`		     =	'".$new."',
                    `banks_total_with_benefits`		 =	'".$banks_total_with_benefits."'
                    WHERE `banks_finance_sn`         = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
                }
            }elseif($sitegroup['expenses_in'] == 'safe'){
                $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
                $sitecompany = $GLOBALS['db']->fetchitem($company_query);
                if($sitegroup['expenses_type']== 'cash'){
                    $cash = $sitecompany['companyinfo_opening_balance_safe'] + $Expenses['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_safe`   =  '".$cash."'
                     WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

                }else{
                    $cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $Expenses['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_cheques`= '".$cheque."'
                     WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
                }
            }


			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`expenses_date`                 =       '".$Expenses['expenses_date']."',
					`expenses_type`                 =       '".$Expenses['expenses_type']."',
					`expenses_amount`               =       '".$Expenses['expenses_amount']."',
					`expenses_cheque_date`          =       '".$Expenses['expenses_cheque_date']."',
					`expenses_cheque_sn`            =       '".$Expenses['expenses_cheque_sn']."',
					`expenses_in`                   =       '".$Expenses['expenses_in']."',
					`expenses_bank_id`              =       '".$Expenses['expenses_bank_id']."',
					`expenses_bank_account_type`    =       '".$Expenses['expenses_bank_account_type']."',
					`expenses_bank_account_id`      =       '".$Expenses['expenses_bank_account_id']."',
					`expenses_title`                =       '".$Expenses['expenses_title']."'
			  WHERE `expenses_sn`    	            = 	    '".$Expenses['expenses_sn']."' LIMIT 1 ");
			if($Expenses['expenses_in'] == 'bank')
            {
                if($Expenses['expenses_account_id'] != 0)
                {
                    $q= "AND`banks_finance_account_id` = '".$Expenses['expenses_account_idexpenses_account_id']."' " ;
                }else{
                    $q= "";
                }
                $bankfinance = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance`
                WHERE `banks_finance_bank_id` ='".$Expenses['expenses_bank_id']."'  AND `banks_finance_account_type` = '".$Expenses['expenses_bank_account_type']."' ".$q." AND `banks_finance_status` != 0 LIMIT 1 ");
                $bankfinanceTotal = $GLOBALS['db']->resultcount();

                if($bankfinanceTotal == 1)
                {
                    $sitebank = $GLOBALS['db']->fetchitem($query);
                    $new                       = $sitebank['banks_finance_credit'] - $Expenses['expenses_amount'];
                    $banks_total_with_benefits = $sitebank['banks_total_with_benefits'] - $Expenses['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
                    `banks_finance_credit`		 =	'".$new."',
                    `banks_total_with_benefits`		 =	'".$banks_total_with_benefits."'
                    WHERE `banks_finance_sn`     = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
                }else
                {

                    if($Expenses['expenses_bank_account_type'] == 'saving')
                    {
                        $bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_saving` WHERE `banks_saving_bank_id` = '".$Expenses['expenses_bank_id']."' LIMIT 1 ");
                        $Total = $GLOBALS['db']->resultcount();
                        if($Total > 0)
                        {
                            $account = $GLOBALS['db']->fetchitem($query);
                            $id      = $account['banks_saving_sn'];
                            $open    = $account['banks_saving_open_balance'];
                        }

                    }elseif($Expenses['expenses_bank_account_type'] == 'current'){
                        $bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_current` WHERE `banks_current_bank_id` = '".$Expenses['expenses_bank_id']."' LIMIT 1 ");
                        $Total = $GLOBALS['db']->resultcount();
                        if($Total > 0)
                        {
                            $account = $GLOBALS['db']->fetchitem($query);
                            $id = $account['banks_current_sn'];
                            $open = $account['banks_current_opening_balance'];
                        }
                    }

                    $Expenses_new = -$Expenses['expenses_amount'];
                    $GLOBALS['db']->query("INSERT INTO `setiings_banks_finance`
                    (`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`,`banks_finance_open_balance`,`banks_finance_credit`,`banks_total_with_benefits`, `banks_finance_status`)
                    VALUES
                    (NULL , '".$Expenses['expenses_bank_id']."' , '".$Expenses['expenses_bank_account_type']."', '".$id."', '".$open."', '".$Expenses_new."','".$Expenses_new."',1)
                    ");
                }
            }elseif($Expenses['expenses_in'] == 'safe'){
                $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
                $sitecompany = $GLOBALS['db']->fetchitem($company_query);
                if($Expenses['expenses_type']== 'cash'){
                    $cash = $sitecompany['companyinfo_opening_balance_safe'] - $Expenses['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_safe`   =  '".$cash."'
                     WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

                }else{
                    $cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $Expenses['expenses_amount'];
                    $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_cheques`= '".$cheque."'
                     WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
                }
            }
		
		
			
			
			if($Expenses['expenses_cheque_date'] || $sitegroup['expenses_cheque_date'] != $Expenses['expenses_cheque_date'])
			{
				$GLOBALS['db']->query("DELETE FROM `reminders` WHERE `reminders_type` = 'expenses' AND `reminders_type_id`= '".$Expenses['expenses_sn']."' LIMIT 1");
				$reminders_remember_date  = date('Y-m-d',strtotime('-7days',strtotime($Expenses['expenses_cheque_date'])));
				$GLOBALS['db']->query("INSERT INTO `reminders`
				(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
				VALUES
				(NULL ,'expenses','".$Expenses['expenses_sn']."','".$Expenses['expenses_cheque_date']."','day','7','".$reminders_remember_date."','".$reminders_remember_date."',1)");
			}
			return 1;

		}else{
			return 2;

		}
	}
	
	function addExpenses($Expenses)
	{
        if($Expenses['expenses_bank_id']== 'safe')
        {
            $Expenses['expenses_in']  = 'safe';
            $Expenses['expenses_bank_id']    = 0 ;
        }else{
            $Expenses['expenses_in']  = 'bank';
        }
		$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
		(`expenses_sn`, `expenses_date`, `expenses_type`, `expenses_amount`, `expenses_cheque_date`, `expenses_cheque_sn`,`expenses_in`, `expenses_bank_id`, `expenses_bank_account_type`, `expenses_bank_account_id`, `expenses_title`, `expenses_status`)
		VALUES ( NULL ,'".$Expenses['expenses_date']."','".$Expenses['expenses_type']."','".$Expenses['expenses_amount']."','".$Expenses['expenses_cheque_date']."','".$Expenses['expenses_cheque_sn']."','".$Expenses['expenses_in']."','".$Expenses['expenses_bank_id']."','".$Expenses['expenses_bank_account_type']."','".$Expenses['expenses_bank_account_id']."','".$Expenses['expenses_title']."',1)");
	    $expence_id =$GLOBALS['db']->fetchLastInsertId();
		
		if($Expenses['expenses_account_id'] != 0)
		{
			$q= "AND`banks_finance_account_id` = '".$Expenses['expenses_account_idexpenses_account_id']."' " ;
		}else{
			$q= "";
		}
		if($Expenses['expenses_in'] == 'bank')
        {
            $bankfinance = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance`
            WHERE `banks_finance_bank_id` ='".$Expenses['expenses_bank_id']."'  AND `banks_finance_account_type` = '".$Expenses['expenses_bank_account_type']."' ".$q." AND `banks_finance_status` != 0 LIMIT 1 ");
            $bankfinanceTotal = $GLOBALS['db']->resultcount();
            if($bankfinanceTotal == 1)
            {
                $sitebank = $GLOBALS['db']->fetchitem($bankfinance);
                $new = $sitebank['banks_finance_credit'] - $Expenses['expenses_amount'];
                $banks_total_with_benefits = $sitebank['banks_total_with_benefits'] - $Expenses['expenses_amount'];
                $GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
                `banks_finance_credit`		     =	'".$new."',
                `banks_total_with_benefits`		 =	'".$banks_total_with_benefits."'
                WHERE `banks_finance_sn`         = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
            }else
            {

                if ($Expenses['expenses_bank_account_type'] == 'saving') {
                    $bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_saving` WHERE `banks_saving_bank_id` = '".$Expenses['expenses_bank_id']."' LIMIT 1 ");
                    $Total = $GLOBALS['db']->resultcount();
                    if($Total > 0)
                    {
                        $account = $GLOBALS['db']->fetchitem($bankfinance);
                        $id      = $account['banks_saving_sn'];
                        $open    = $account['banks_saving_open_balance'];
                    }

                }elseif($Expenses['expenses_bank_account_type'] == 'current'){
                    $bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_current` WHERE `banks_current_bank_id` = '".$Expenses['expenses_bank_id']."' LIMIT 1 ");
                    $Total = $GLOBALS['db']->resultcount();
                    if($Total > 0)
                    {
                        $account = $GLOBALS['db']->fetchitem($bankfinance);
                        $id = $account['banks_current_sn'];
                        $open = $account['banks_current_opening_balance'];
                    }
                }

                $Expenses_new = -$Expenses['expenses_amount'];
                $GLOBALS['db']->query("INSERT INTO `setiings_banks_finance`
                (`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`,`banks_finance_open_balance`,`banks_finance_credit`,`banks_total_with_benefits`, `banks_finance_status`)
                VALUES
                (NULL , '".$Expenses['expenses_bank_id']."' , '".$Expenses['expenses_bank_account_type']."', '".$id."', '".$open."', '".$Expenses_new."','".$Expenses_new."',1)
                ");
            }
        }elseif($Expenses['expenses_in'] == 'safe'){
            $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if($Expenses['expenses_type']== 'cash'){
                $cash = $sitecompany['companyinfo_opening_balance_safe'] - $Expenses['expenses_amount'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_safe`   =  '".$cash."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

            }else{
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $Expenses['expenses_amount'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_cheques`= '".$cheque."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
            }
        }
		
		
		if($Expenses['expenses_cheque_date'])
		{
			
			$reminders_remember_date  = date('Y-m-d',strtotime('-7days',strtotime($Expenses['expenses_cheque_date'])));
			$GLOBALS['db']->query("INSERT INTO `reminders`
			(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
			VALUES
			(NULL ,'expenses','".$expence_id."','".$Expenses['expenses_cheque_date']."','day','7','".$reminders_remember_date."','".$reminders_remember_date."',1)");
		}
		
		
		
		return 1;

		
	}
	
	
	function deleteExpence($id)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `expenses_sn` = '".$id."' AND expenses_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 1)
        {
			$sitegroup = $GLOBALS['db']->fetchitem($query);
			if($sitegroup['expenses_account_id'] != 0)
			{
				$q= "AND`banks_finance_account_id` = '".$sitegroup['expenses_account_idexpenses_account_id']."' " ;
			}else{
				$q= "";
			}

			$bankfinance = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance`
			WHERE `banks_finance_bank_id` ='".$sitegroup['expenses_bank_id']."'  AND `banks_finance_account_type` = '".$sitegroup['expenses_bank_account_type']."' ".$q." AND `banks_finance_status` != 0 LIMIT 1 ");
			$bankfinanceTotal = $GLOBALS['db']->resultcount();
			if($bankfinanceTotal == 1)
			{
				$sitebank = $GLOBALS['db']->fetchitem($query);
				$new = $sitebank['banks_finance_credit'] + $sitegroup['expenses_amount'];

				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
				`banks_finance_credit`		 =	'".$new."'
				WHERE `banks_finance_sn`     = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
			}
			$GLOBALS['db']->query("DELETE FROM `reminders` WHERE `reminders_type` ='expenses' AND `reminders_type_id` = '".$id."' LIMIT 1");
		}
		
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`expenses_status`               =       '" . $sitegroup['expenses_status'] . "'
			  WHERE `expenses_sn`    	            = 	    '".$id."' LIMIT 1 ");
		return 1;
	}



}
?>

<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemCollect_returns
{
    var $tableName 	= "collect_returns";
	function Add_Collect_returns($Collect_returns)
	{
        if($Collect_returns['collect_returns_bank_id']== 'safe')
        {
            $Collect_returns['collect_returns_insert_in']  = 'safe';
            $Collect_returns['collect_returns_bank_id']    = 0 ;
        }else{
            $Collect_returns['collect_returns_insert_in']  = 'bank';
        }
        $GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
		(`collect_returns_sn`, `collect_returns_date`, `collect_returns_person`,`collect_returns_type`,`collect_returns_cheque_date`,`collect_returns_cheque_number`, `collect_id`, `collect_returns_value`, 
		`collect_returns_insert_in`, `collect_returns_bank_id`, `collect_returns_account_type`, `collect_returns_account_id`, `collect_returns_status`)
        VALUES ( NULL ,'".$Collect_returns['collect_returns_date']."','".$Collect_returns['collect_returns_person']."','".$Collect_returns['collect_returns_type']."','".$Collect_returns['collect_returns_cheque_date']."','".$Collect_returns['collect_returns_cheque_number']."','".$Collect_returns['collect_id']."','".$Collect_returns['collect_returns_value']."',
		'".$Collect_returns['collect_returns_insert_in']."','".$Collect_returns['collect_returns_bank_id']."','".$Collect_returns['collect_returns_account_type']."','".$Collect_returns['collect_returns_account_id']."',1)");
        
		$Collect_returns_id=$GLOBALS['db']->fetchLastInsertId();
		
       if($Collect_returns['collect_returns_bank_id']== 'safe')
       {
			$company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
			$sitecompany = $GLOBALS['db']->fetchitem($company_query);

		   if($Collect_returns['collect_returns_type']== 'cash'){
			    if($Collect_returns['collect_returns_person'] == 'supplier')
				{	
					$cash = $sitecompany['companyinfo_opening_balance_safe'] + $Collect_returns['collect_returns_value'];
				}else{
					$cash = $sitecompany['companyinfo_opening_balance_safe'] - $Collect_returns['collect_returns_value'];
				}
				$GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
				 `companyinfo_opening_balance_safe`   =  '".$cash."'
				 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
	  	   }else{
			   if($Collect_returns['collect_returns_person'] == 'supplier')
				{
					$cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $Collect_returns['collect_returns_value'];
			   }else{
					$cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $Collect_returns['collect_returns_value'];
			   }
			  $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
				 `companyinfo_opening_balance_cheques`= '".$cheque."'
			   WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
		  }
       }else
	   {
            if($Collect_returns['collect_returns_account_type']=='current' || $Collect_returns['collect_returns_account_type']=='saving'){

			  $account_query=$GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '".$Collect_returns['collect_returns_bank_id']."' AND `banks_finance_account_type` = '".$Collect_returns['collect_returns_account_type']."' ");

			  $siteaccount = $GLOBALS['db']->fetchitem($account_query);
				
			  if($Collect_returns['collect_returns_person'] == 'supplier')
			  {	
				$banks_finance_credit      =  $siteaccount['banks_finance_credit'] + $Collect_returns['collect_returns_value'];
				$banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $Collect_returns['collect_returns_value'];
			  }else{
				$banks_finance_credit      =  $siteaccount['banks_finance_credit'] - $Collect_returns['collect_returns_value'];
				$banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] - $Collect_returns['collect_returns_value'];
			  }
			 $account_query=$GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
				`banks_finance_credit`         = '".$banks_finance_credit."',
				`banks_total_with_benefits`    = '".$banks_total_with_benefits."'
			 WHERE `banks_finance_sn` = '".$siteaccount['banks_finance_sn']."'");

            }elseif($Collect_returns['collect_returns_account_type']=='credit'){
				$account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Collect_returns['collect_returns_bank_id'] . "' AND `banks_finance_account_type` = '" . $Collect_returns['collect_returns_account_type'] . "' AND `banks_finance_account_id` = '" . $Collect_returns['collect_returns_account_id'] . "'");
				$sitebank = $GLOBALS['db']->fetchitem($account_query);
				if($Collect_returns['collect_returns_type'] == 'supplier')
				{	
				  $banks_finance_credit =  $sitebank['banks_finance_credit'] + $Collect_returns['collect_returns_value'];
				  $banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] + $Collect_returns['collect_returns_value'];
				}else{
				  $banks_finance_credit =  $sitebank['banks_finance_credit'] - $Collect_returns['collect_returns_value'];
				  $banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] - $Collect_returns['collect_returns_value'];
				}
				$account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
				`banks_finance_credit`    = '" . $banks_finance_credit . "',
				`banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
				WHERE `banks_finance_sn` = '" . $sitebank['banks_finance_sn'] . "'");
            }
        }
		
	   if($Collect_returns['collect_returns_person'] == 'supplier')
	   {
			$suppliers_collectible = $GLOBALS['db']->query(" SELECT * FROM `suppliers_collectible` WHERE `collectible_sn` =  '".$Collect_returns['collect_id']."' AND `collectible_status` != '0'  LIMIT 1");
			$suppliers_collectibleCount = $GLOBALS['db']->resultcount();
			if($suppliers_collectibleCount == 1)
			{
				$supplier = $GLOBALS['db']->fetchitem($suppliers_collectible);
				$finance = $GLOBALS['db']->query("SELECT * FROM `suppliers_finance` WHERE `suppliers_finance_supplier_id` = '".$supplier['collectible_supplier_id']."'  LIMIT 1 ");
				$financeTotal = $GLOBALS['db']->resultcount();

				$sitefinance = $GLOBALS['db']->fetchitem($finance);
				$new = $sitefinance['suppliers_finance_credit'] + $Collect_returns['collect_returns_value'];
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `suppliers_finance` SET
				`suppliers_finance_credit`		     =	'".$new."'
				WHERE `suppliers_finance_sn` 		 = 	'".$sitefinance['suppliers_finance_sn']."' LIMIT 1 ");
			}
		    
		    /********************* insert value in operation *****************************/
			$GLOBALS['db']->query("SELECT *  FROM `supplier_collectible_operations` WHERE `collectible_id` = '".$Collect_returns['collect_id']."'");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal > 0)
			{
				$operations = $GLOBALS['db']->fetchlist();
				foreach($operations as $k => $operation)
				{
					$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE `operations_sn` = '".$operation['operations_id']."' LIMIT 1");
					$queryTotal = $GLOBALS['db']->resultcount();
					if($queryTotal == 1)
					{
						$siteoperation = $GLOBALS['db']->fetchitem($company_query);
						$paid   = $siteoperation['operations_supplier_paid'] - $operation['value'];
						$remain = $siteoperation['operations_supplier_remain'] + $operation['value'];
						$GLOBALS['db']->query("UPDATE `operations`
						SET
						`operations_supplier_paid`='".$paid."',
						`operations_supplier_remain`='".$remain."'
						WHERE `operations_sn` = '".$siteoperation['operations_sn']."'");
					}
				}
			}
		
			$update_table_collect = 'suppliers_collectible';
		}else{
		   
		   	$clients_collectible = $GLOBALS['db']->query(" SELECT * FROM `clients_collectible` WHERE `collectible_sn` =  '".$Collect_returns['collect_id']."' AND `collectible_status` != '0'  LIMIT 1");
			$clients_collectibleCount = $GLOBALS['db']->resultcount();
			if($clients_collectibleCount == 1)
			{
				$client = $GLOBALS['db']->fetchitem($clients_collectible);
				$finance = $GLOBALS['db']->query("SELECT * FROM `clients_finance` WHERE `clients_finance_client_id` = '".$client['collectible_client_id']."'  LIMIT 1 ");
				$financeTotal = $GLOBALS['db']->resultcount();

				$sitefinance = $GLOBALS['db']->fetchitem($finance);
				$new = $sitefinance['clients_finance_credit'] + $Collect_returns['collect_returns_value'];
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `clients_finance` SET
				`clients_finance_credit`		     =	'".$new."'
				WHERE `clients_finance_sn` 		 = 	'".$sitefinance['clients_finance_sn']."' LIMIT 1 ");
			}
		    
		    /********************* insert value in operation *****************************/
			$GLOBALS['db']->query("SELECT *  FROM `clients_collectible_operations` WHERE `collectible_id` = '".$Collect_returns['collect_id']."'");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal > 0)
			{
				$operations = $GLOBALS['db']->fetchlist();
				foreach($operations as $k => $operation)
				{
					$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE `operations_sn` = '".$operation['operations_id']."' LIMIT 1");
					$queryTotal = $GLOBALS['db']->resultcount();
					if($queryTotal == 1)
					{
						$siteoperation = $GLOBALS['db']->fetchitem($company_query);
						$paid   = $siteoperation['operations_customer_paid'] - $operation['value'];
						$remain = $siteoperation['operations_customer_remain'] + $operation['value'];
						$GLOBALS['db']->query("UPDATE `operations`
						SET
						`operations_customer_paid`='".$paid."',
						`operations_customer_remain`='".$remain."'
						WHERE `operations_sn` = '".$siteoperation['operations_sn']."'");
					}
				}
			}
		   
			$update_table_collect = 'clients_collectible';
		}
		$GLOBALS['db']->query("UPDATE `".$update_table_collect."` SET  `collectible_status`= '0' WHERE `collectible_sn` = '".$Collect_returns['collect_id']."' ");
      
      return 1;

    }
}
?>

<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemClients_collectible
{
	var $tableName 	= "clients_collectible";

	public function GetClientFinance(){
		$GLOBALS['db']->query("SELECT DISTINCT c.`clients_name`,f.*  FROM `settings_clients` c
			LEFT JOIN  `clients_finance` f ON c.`clients_sn` = f.`clients_finance_client_id` WHERE c.`clients_status` !='0'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}
	
	public function GetClientFinanceByid($id){
		$query=$GLOBALS['db']->query("SELECT DISTINCT c.`clients_name`,f.*  FROM `settings_clients` c
			LEFT JOIN  `clients_finance` f ON c.`clients_sn` = f.`clients_finance_client_id` WHERE c.`clients_status` !='0' AND c.`clients_sn` ='".$id."' LIMIT 1");
		 $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
			$finance =$GLOBALS['db']->fetchitem($query);
			$client = array(
				"name"    => $finance['clients_name'],
				"credit"  => $finance['clients_finance_credit']
			);
            return($client);
        }else{return null;}
	}
	
	public function GetSearchResult($search)
	{
		$client_id    = intval($search['client']);
		$supplier_id  = intval($search['supplier']);
		$product_id   = intval($search['product']);
		$start_date   = format_data_base($search['start_date']);
		$end_date     = format_data_base($search['end_date']);
		$serial_from  = sanitize($search['serial_from']);
		$serial_to    = sanitize($search['serial_to']);
		$invoice_from = sanitize($search['invoice_from']);
		$invoice_to   = sanitize($search['invoice_to']);
		
		$supplier     = $supplier_id > 0 ? " AND `operations_supplier` = '".$supplier_id."'" : "";
		$product      = $product_id > 0 ? " AND `operations_product` = '".$product_id."'" : "";
		$start_date   = $search['start_date'] ? " AND `operations_date` >=  '".$start_date."'" : "";
		$end_date     = $search['end_date'] ? " AND `operations_date` <=  '".$end_date."'" : "";
		$serial_from  = $serial_from ? " AND `operations_receipt` >= '".$serial_from."'" : "";
		$serial_to    = $serial_to ? " AND `operations_receipt`<= '".$serial_to."'" : "";
		$invoice_from = $invoice_from ? " AND `operations_code` >=  '".$invoice_from."'" : "";
		$invoice_to   = $invoice_to ? " AND `operations_code` <=  '".$invoice_to."'" : "";
		
		$GLOBALS['db']->query("SELECT `operations_supplier`,`operations_product`,min(`operations_date`) AS start_date , MAX(`operations_date`) AS end_date ,min(`operations_receipt`) AS start , MAX(`operations_receipt`) AS end   ,SUM(`operations_customer_price`) AS total,SUM(`operations_customer_paid`) AS paid, SUM(`operations_customer_remain`) AS remain  FROM `operations` WHERE 
		`operations_customer` = '".$client_id."'".$supplier.$product.$start_date.$end_date.$serial_from.$serial_to.$invoice_from.$invoice_to." AND `operations_status` != '0'  
		GROUP BY `operations_product` ");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}



	}
	
	public function Getcolection_details($search)
	{
		$product_id   = intval($search['product']);
		$serial_from  = sanitize($search['serial_from']);
		$serial_to    = sanitize($search['serial_to']);
		
		$product      = $product_id > 0 ? " `operations_product` = '".$product_id."'" : "";
		$serial_from  = $serial_from ? " AND `operations_receipt` >= '".$serial_from."'" : "";
		$serial_to    = $serial_to ? " AND `operations_receipt`<= '".$serial_to."'" : "";
		
		$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE 
		".$product.$serial_from.$serial_to." AND `operations_status` != '0'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}



	}
	
	public function add_clients_collectible($_collectible,$search)
	{
		// ******************** paid in operation *******************// 
		$client_id    = intval($search['client']);
		$supplier_id  = intval($search['supplier']);
		$product_id   = intval($search['product']);
		$start_date   = format_data_base($search['start_date']);
		$end_date     = format_data_base($search['end_date']);
		$serial_from  = sanitize($search['serial_from']);
		$serial_to    = sanitize($search['serial_to']);
		$invoice_from = sanitize($search['invoice_from']);
		$invoice_to   = sanitize($search['invoice_to']);
		
		$supplier     = $supplier_id > 0 ? " AND `operations_supplier` = '".$supplier_id."'" : "";
		$product      = $product_id > 0 ? " AND `operations_product` = '".$product_id."'" : "";
		$start_date   = $search['start_date'] ? " AND `operations_date` >=  '".$start_date."'" : "";
		$end_date     = $search['end_date'] ? " AND `operations_date` <=  '".$end_date."'" : "";
		$serial_from  = $serial_from ? " AND `operations_receipt` >= '".$serial_from."'" : "";
		$serial_to    = $serial_to ? " AND `operations_receipt`<= '".$serial_to."'" : "";
		$invoice_from = $invoice_from ? " AND `operations_code` >=  '".$invoice_from."'" : "";
		$invoice_to   = $invoice_to ? " AND `operations_code` <=  '".$invoice_to."'" : "";
		
		
		if($_collectible['collectible_bank_id']== 'safe')
        {
            $_collectible['collectible_insert_in']  = 'safe';
            $_collectible['collectible_bank_id']    = 0 ;
        }else{
            $_collectible['collectible_insert_in']  = 'bank';
        }
		$GLOBALS['db']->query("INSERT INTO `".$this->tableName."`
		(`collectible_sn`, `collectible_client_id`, `collectible_date`, `collectible_type`, `collectible_value`, `collectible_cheque_date`, `collectible_cheque_number`,`collectible_insert_in`, `collectible_bank_id`, `collectible_account_type`, `collectible_account_id`, `collectible_status`) 
		VALUES
		(NULL,'".$_collectible['collectible_client_id']."','".$_collectible['collectible_date']."','".$_collectible['collectible_type']."','".$_collectible['collectible_value']."','".$_collectible['collectible_cheque_date']."','".$_collectible['collectible_cheque_number']."','".$_collectible['collectible_insert_in']."','".$_collectible['collectible_bank_id']."','".$_collectible['collectible_account_type']."','".$_collectible['collectible_account_id']."',1)");
		$collect_id =$GLOBALS['db']->fetchLastInsertId();
		/********************************** insert payed in operation ***********************/
		$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE `operations_customer` = '".$client_id."'".$supplier.$product.$start_date.$end_date.$serial_from.$serial_to.$invoice_from.$invoice_to." AND `operations_customer_remain` != '0' AND `operations_status` != '0'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $operations = $GLOBALS['db']->fetchlist();
			$collectible_value = $_collectible['collectible_value'] ;
			$invoices = [];
			foreach($operations as $k => $operation)
			{
				if($collectible_value >= $operation['operations_customer_remain'])
				{
					$paid   = $operation['operations_customer_paid'] + $operation['operations_customer_remain'];
					$remain = 0;
				 	$collectible_value = $collectible_value - $operation['operations_customer_remain'];
					$GLOBALS['db']->query("UPDATE `operations` SET 
					`operations_customer_paid`  ='".$paid."',
					`operations_customer_remain`='".$remain."' 
					WHERE `operations_sn` ='".$operation['operations_sn']."'");
				}elseif($collectible_value != 0 && $collectible_value < $operation['operations_customer_remain']){
					$paid   = $operation['operations_customer_paid'] + $collectible_value ;
					$remain = $operation['operations_customer_remain'] - $collectible_value;
				    $collectible_value = 0;
					$GLOBALS['db']->query("UPDATE `operations` SET 
					`operations_customer_paid`  ='".$paid."',
					`operations_customer_remain`='".$remain."' 
					WHERE `operations_sn` ='".$operation['operations_sn']."'");
				}
				$GLOBALS['db']->query("INSERT INTO `clients_collectible_operations`
				(`id`, `collectible_id`, `operations_id`, `value`)
				VALUES (NULL,'".$collect_id."','".$operation['operations_sn']."','".$paid."')");
			}
        }
		
		
		
		$finance = $GLOBALS['db']->query("SELECT * FROM `clients_finance` WHERE `clients_finance_client_id` = '".$_collectible['collectible_client_id']."'  LIMIT 1 ");
        $financeTotal = $GLOBALS['db']->resultcount();
        if($financeTotal > 0)
        {
			
            $sitefinance = $GLOBALS['db']->fetchitem($query);
			$new = $sitefinance['clients_finance_credit'] - $_collectible['collectible_value'];
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `clients_finance` SET
			`clients_finance_credit`		 =	'".$new."'
			WHERE `clients_finance_sn` 		 = 	'".$sitefinance['clients_finance_sn']."' LIMIT 1 ");
		}else
		{
			$GLOBALS['db']->query("INSERT INTO `clients_finance`
			(`clients_finance_sn`, `clients_finance_client_id`, `clients_finance_credit`, `clients_status`)
			VALUES
			(NULL , '".$_collectible['collectible_client_id']."' , '".-$_collectible['collectible_value']."',1)
			");
		}
		if($_collectible['collectible_bank_id']== 'safe')
        {
            $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if($_collectible['collectible_type']== 'cash'){
                $cash = $sitecompany['companyinfo_opening_balance_safe'] + $_collectible['collectible_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_safe`   =  '".$cash."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

            }else{
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $_collectible['collectible_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_cheques`= '".$cheque."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
				if($_collectible['collectible_cheque_date'])
				{
					$reminders_remember_date  = date('Y-m-d',strtotime('-7days',strtotime($_collectible['collectible_cheque_date'])));
					$GLOBALS['db']->query("INSERT INTO `reminders`
					(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
					VALUES
					(NULL ,'safe','".$collect_id."','".$_collectible['collectible_cheque_date']."','day','7','".$reminders_remember_date."','".$reminders_remember_date."',1)");
				}
            }
            
        }else{
			if($_collectible['collectible_account_id'] != 0)
			{
				$q= "AND`banks_finance_account_id` = '".$_collectible['collectible_account_id']."' " ;
			}else{
				$q= "";
			}
			$bankfinance = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance`
			WHERE `banks_finance_bank_id` ='".$_collectible['collectible_bank_id']."'  AND `banks_finance_account_type` = '".$_collectible['collectible_account_type']."' ".$q." AND `banks_finance_status` != 0 LIMIT 1 ");
			$bankfinanceTotal = $GLOBALS['db']->resultcount();
			if($bankfinanceTotal > 0)
			{
//				if($_collectible['collectible_account_type'] == 'saving' || $_collectible['collectible_account_type'] == 'saving' )
//				{
					if($_collectible['collectible_type']== 'cash')
					{
						$sitebank = $GLOBALS['db']->fetchitem($query);
						$new = $sitebank['banks_finance_credit'] + $_collectible['collectible_value'];
						$banks_total_with_benefits = $sitebank['banks_total_with_benefits'] + $_collectible['collectible_value'];
						$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
						`banks_finance_credit`		 =	'".$new."',
						`banks_total_with_benefits`		 =	'".$banks_total_with_benefits."'
						WHERE `banks_finance_sn` 		 = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
					}elseif($_collectible['collectible_type']== 'cheque')
					{
						if($_collectible['collectible_account_type'] != 'credit')
						{
							$deposits_cut_precent = 0;
							$deposits_cut_value   = 0;
							$deposits_days        = 0;
							$deposit_date_pay     = 0;

						}else{
							$account_query = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE `banks_credit_sn` = '".$_collectible['collectible_account_id']."'");
							$siteaccount = $GLOBALS['db']->fetchitem($account_query);

							$deposits_cut_precent = $siteaccount['banks_credit_cutting_ratio'];
							$deposits_cut_value   = ($siteaccount['banks_credit_cutting_ratio']/100) * $_collectible['collectible_value'] ;
							if($siteaccount['banks_credit_repayment_type'] == 'day')
							{
								$deposits_days        = $siteaccount['banks_credit_repayment_period'];
								$deposit_date_pay     = $reminders_remember_date  = date('Y-m-d',strtotime('+'.$deposits_days.'days',strtotime($_collectible['collectible_date'])));;
							}else{
								$now = time();
								$your_date = strtotime($_collectible['collectible_cheque_date']);
								$datediff = $now - $your_date;
								$deposits_days            = round($datediff / (60 * 60 * 24));
							    $deposit_date_pay         = $_collectible['collectible_cheque_date'];
							}

						}
						$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `deposits`
						(`deposits_sn`, `deposits_date`, `deposits_type`, `deposits_value`, `deposits_cheque_date`,
						`deposits_cheque_number`, `deposits_insert_in`, `deposits_bank_id`, `deposits_account_type`,
						`deposits_account_id`,`deposits_cut_precent`, `deposits_cut_value`, `deposits_days`,
						`deposit_date_pay`,`deposits_status`)
						VALUES ( NULL ,'".$_collectible['collectible_date']."','".$_collectible['collectible_type']."','".$_collectible['collectible_value']."','".$_collectible['collectible_cheque_date']."',
						'".$_collectible['collectible_cheque_number']."','".$_collectible['collectible_insert_in']."','".$_collectible['collectible_bank_id']."','".$_collectible['collectible_account_type']."',
						'".$_collectible['collectible_account_id']."','".$deposits_cut_precent."','".$deposits_cut_value."','".$deposits_days."','".$deposit_date_pay."',1)");
						$deposits_id=$GLOBALS['db']->fetchLastInsertId();

						$reminders_remember_date  = date('Y-m-d', strtotime('-7days', strtotime($_collectible['collectible_cheque_date'])));
						$GLOBALS['db']->query("INSERT INTO `reminders`
						(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
						VALUES
						(NULL ,'deposits','" . $deposits_id . "','" . $_collectible['collectible_cheque_date'] . "','day','7','" . $reminders_remember_date . "','" . $reminders_remember_date . "',1)");

								}
//				}
			}
		}

		
		
		
		
		return 1;
	
	}



}
?>

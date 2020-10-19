<?php if(!defined("inside")) exit;
if(!isset($_SESSION))
    {
        session_start();
    }
class systemSuppliers_collectible
{
	var $tableName 	= "suppliers_collectible";

	public function GetClientFinance(){
		$GLOBALS['db']->query("SELECT DISTINCT c.`suppliers_name`,f.*  FROM `settings_Suppliers` c
			LEFT JOIN  `suppliers_finance` f ON c.`suppliers_sn` = f.`suppliers_finance_client_id` WHERE c.`suppliers_status` !='0'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
    }else{return null;}
	}

	public function GetSupplierFinanceByid($id){
		$query=$GLOBALS['db']->query("SELECT DISTINCT c.`suppliers_name`,f.*  FROM `settings_suppliers` c
			LEFT JOIN  `suppliers_finance` f ON c.`suppliers_sn` = f.`suppliers_finance_supplier_id` WHERE c.`suppliers_status` !='0' AND c.`suppliers_sn` ='".$id."' LIMIT 1");
		 $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
			$finance =$GLOBALS['db']->fetchitem($query);
			$client = array(
				"name"    => $finance['suppliers_name'],
				"credit"  => $finance['suppliers_finance_credit']
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
		$code         = sanitize($search['code']);

		$code         = $search['code']  ? " AND `operations_code` = '".$code."'" : "";
		$client      = $client > 0 ? " AND `operations_customer` = '".$client."'" : "";
		$product      = $product_id > 0 ? " AND `operations_product` = '".$product_id."'" : "";
		$start_date   = $search['start_date'] ? " AND `operations_date` >=  '".$start_date."'" : "";
		$end_date     = $search['end_date'] ? " AND `operations_date` <=  '".$end_date."'" : "";
		$serial_from  = $serial_from ? " AND `operations_receipt` >= '".$serial_from."'" : "";
		$serial_to    = $serial_to ? " AND `operations_receipt`<= '".$serial_to."'" : "";
		$invoice_from = $invoice_from ? " AND `operations_code` >=  '".$invoice_from."'" : "";
		$invoice_to   = $invoice_to ? " AND `operations_code` <=  '".$invoice_to."'" : "";

		$GLOBALS['db']->query("SELECT `operations_customer`,`operations_product`,min(`operations_date`) AS start_date , MAX(`operations_date`) AS end_date ,min(`operations_receipt`) AS start , MAX(`operations_receipt`) AS end   ,SUM(`operations_supplier_price`) AS total,SUM(`operations_supplier_paid`) AS paid, SUM(`operations_supplier_remain`) AS remain  FROM `operations` WHERE
		`operations_supplier` = '".$supplier_id."'".$client.$code.$product.$start_date.$end_date.$serial_from.$serial_to.$invoice_from.$invoice_to." AND `operations_status` != '0'
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
		$serial_from  = sanitize($search['start']);
		$serial_to    = sanitize($search['end']);

		$product      = $product_id > 0 ? " `operations_product` = '".$product_id."'" : "";
		$serial_from  = $serial_from ? " AND `operations_receipt` >= '".$serial_from."'" : "";
		$serial_to    = $serial_to ? " AND `operations_receipt`<= '".$serial_to."'" : "";
        echo "SELECT *  FROM `operations` WHERE
		".$product.$serial_from.$serial_to." AND `operations_status` != '0'";
		$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE
		".$product.$serial_from.$serial_to." AND `operations_status` != '0'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}



	}

	public function add_suppliers_collectible($_collectible,$search)
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
		$code   = sanitize($search['code']);

		$client       = $client_id > 0 ? " AND `operations_customer` = '".$client_id."'" : "";
		$code         = $search['code']  ? " AND `operations_code` = '".$code."'" : "";
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
		(`collectible_sn`, `collectible_supplier_id`, `collectible_date`, `collectible_type`, `collectible_value`, 
        `collectible_cheque_date`, `collectible_cheque_number`,`collectible_insert_in`, `collectible_bank_id`,
        `collectible_account_type`, `collectible_account_id`,`collectible_operations`,`collectible_payment_case`,`collectible_recipient`, `collectible_status`)
		VALUES
		(NULL,'".$_collectible['collectible_supplier_id']."','".$_collectible['collectible_date']."','".$_collectible['collectible_type']."','".$_collectible['collectible_value']."',
        '".$_collectible['collectible_cheque_date']."','".$_collectible['collectible_cheque_number']."','".$_collectible['collectible_insert_in']."','".$_collectible['collectible_bank_id']."',
        '".$_collectible['collectible_account_type']."','".$_collectible['collectible_account_id']."','".$operations_paid."','".$_collectible['collectible_payment_case']."','".$_collectible['collectible_recipient']."',1)");
	
        $collect_id =$GLOBALS['db']->fetchLastInsertId();
		
		if($_collectible['collectible_payment_case'] != 'later')
		{

			/********************* insert value in operation *****************************/
			$GLOBALS['db']->query("SELECT *  FROM `operations` WHERE `operations_supplier` = '".$supplier_id."'".$code.$client.$product.$start_date.$end_date.$serial_from.$serial_to.$invoice_from.$invoice_to." AND `operations_supplier_remain` != '0' AND `operations_status` != '0'");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal > 0)
			{
				$operations = $GLOBALS['db']->fetchlist();
				$collectible_value = $_collectible['collectible_value'] ;
				$invoices = [];
				foreach($operations as $k => $operation)
				{
					if($collectible_value >= $operation['operations_supplier_remain'])
					{
						$paid   = $operation['operations_supplier_paid'] + $operation['operations_supplier_remain'];
						$remain = 0;
						$collectible_value = $collectible_value - $operation['operations_supplier_remain'];
						$GLOBALS['db']->query("UPDATE `operations` SET
						`operations_supplier_paid`  ='".$paid."',
						`operations_supplier_remain`='".$remain."'
						WHERE `operations_sn` ='".$operation['operations_sn']."'");
					}elseif($collectible_value != 0 && $collectible_value < $operation['operations_supplier_remain']){
						$paid   = $operation['operations_supplier_paid'] + $collectible_value ;
						$remain = $operation['operations_supplier_remain'] - $collectible_value;
						$collectible_value = 0;
						$GLOBALS['db']->query("UPDATE `operations` SET
						`operations_supplier_paid`  ='".$paid."',
						`operations_supplier_remain`='".$remain."'
						WHERE `operations_sn` ='".$operation['operations_sn']."'");
					}
					$GLOBALS['db']->query("INSERT INTO `supplier_collectible_operations`
					(`id`, `collectible_id`, `operations_id`, `value`)
					VALUES (NULL,'".$collect_id."','".$operation['operations_sn']."','".$paid."')");

				}
			}
		}

		$finance = $GLOBALS['db']->query("SELECT * FROM `suppliers_finance` WHERE `suppliers_finance_supplier_id` = '".$_collectible['collectible_supplier_id']."'  LIMIT 1 ");
        $financeTotal = $GLOBALS['db']->resultcount();
        if($financeTotal > 0)
        {

            $sitefinance = $GLOBALS['db']->fetchitem($query);
			$new = $sitefinance['suppliers_finance_credit'] - $_collectible['collectible_value'];
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `suppliers_finance` SET
			`suppliers_finance_credit`		     =	'".$new."'
			WHERE `suppliers_finance_sn` 		 = 	'".$sitefinance['suppliers_finance_sn']."' LIMIT 1 ");
		}else
		{
			$GLOBALS['db']->query("INSERT INTO `suppliers_finance`
			(`suppliers_finance_sn`, `suppliers_finance_supplier_id`, `suppliers_finance_credit`, `suppliers_status`)
			VALUES
			(NULL , '".$_collectible['collectible_supplier_id']."' , '".-$_collectible['collectible_value']."',1)
			");
		}
		if($_collectible['collectible_bank_id']== 'safe')
        {
            $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if($_collectible['collectible_type']== 'cash'){
                $cash = $sitecompany['companyinfo_opening_balance_safe'] - $_collectible['collectible_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_safe`   =  '".$cash."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

            }else{
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $_collectible['collectible_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_cheques`= '".$cheque."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
            }

        }else
        {
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

				$sitebank = $GLOBALS['db']->fetchitem($query);
				$new = $sitebank['banks_finance_credit'] - $_collectible['collectible_value'];
				$banks_total_with_benefits = $sitebank['banks_total_with_benefits'] - $_collectible['collectible_value'];
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
				`banks_finance_credit`		     =	'".$new."',
				`banks_total_with_benefits`		     =	'".$banks_total_with_benefits."'
				WHERE `banks_finance_sn` 		 = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
			}
		}
		if($_collectible['collectible_cheque_date'])
		{

			$reminders_remember_date  = date('Y-m-d',strtotime('-7days',strtotime($_collectible['collectible_cheque_date'])));
			$GLOBALS['db']->query("INSERT INTO `reminders`
			(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
			VALUES
			(NULL ,'suppliers_collectible','".$collect_id."','".$_collectible['collectible_cheque_date']."','day','7','".$reminders_remember_date."','".$reminders_remember_date."',1)");
		}

		return 1;

	}

	function Get_Supplier_Paid($id)
    {
        $GLOBALS['db']->query("SELECT * FROM `suppliers_collectible` WHERE `collectible_status` != '0' AND `collectible_payment_case` = 'later' AND `collectible_supplier_id` = '".$id."'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
    }



}
?>

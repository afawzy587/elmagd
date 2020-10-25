<?php if (!defined("inside")) exit;
if (!isset($_SESSION)) {
    session_start();
}
class systemMoney_transfers
{
    var $tableName     = "money_transfers";

    function getsiteMoney_transfers($addon = "",$search= "")
    {
		
		if($search != "")
		{
			if($search['id'] > 0){
				$id = " AND `transfers_sn` = '".$search['id']."'";
			}else{
				$id = "" ;
			}
			
			if($search['startDate'] != ""){
				$startDate = " AND `transfers_date` >= '".$search['startDate']."'";
			}else{
				$startDate = "" ;
			}
			
			if($search['endDate'] != ""){
				$endDate = " AND `transfers_date` <= '".$search['endDate']."'";
			}else{
				$endDate = "" ;
			}
			
			if($search['client_id'] != 0){
				$client_id = " AND (`transfers_client_id_from` = '".$search['client_id']."' OR `transfers_client_id_to` = '".$search['client_id']."' )";
			}else{
				$client_id = "" ;
			}
			
			if($search['prroduct_client_id'] != 0){
				$product_id = " AND (`transfers_product_id_from` = '".$search['prroduct_client_id']."' OR `transfers_product_id_to` = '".$search['prroduct_client_id']."' )";
			}else{
				$product_id = "" ;
			}
			
			if($search['startValue'] != 0){
				$startValue= " AND `transfers_value` >= '".$search['startValue']."'";
			}else{
				$startValue= "" ;
			}
			
			if($search['endValue'] != 0){
				$endValue = " AND `transfers_value` <= '".$search['endValue']."'";
			}else{
				$endValue = "" ;
			}
			
			if($search['bank'] != ""){
				if($search['bank'] == "safe")
				{
					$bank = " AND `transfers_from_in` = '".$search['bank']."'";
				}else{
					$bank = " AND `transfers_from` = '".$search['bank']."'";

				}
			}else{
				$bank = "" ;
			}
			
			if($search['account'] != 0){
				$account = " AND `transfers_client_id_from` = '".$search['account']."'";
			}else{
				$account = "" ;
			}
			
			if($search['bank_to'] != ""){
				if($search['bank_to'] == "safe")
				{
					$bank_to = " AND `transfers_to_in` = '".$search['bank_to']."'";
				}else{
					$bank_to = " AND `transfers_to` = '".$search['bank_to']."'";

				}
			}else{
				$bank_to = "" ;
			}
			
			if($search['account_to'] != 0){
				$account_to = " AND `transfers_account_id_to` = '".$search['account_to']."'";
			}else{
				$account_to = "" ;
			}
			
			if($search['cheque'] != ""){
				$cheque = " AND `transfers_cheque_number` = '".$search['cheque']."'";
			}else{
				$cheque = "" ;
			}
		}
        $query = $GLOBALS['db']->query("SELECT * FROM `" . $this->tableName . "` WHERE `transfers_status` != '0'   ".$id.$cheque.$startDate.$endDate.$client_id.$product_id.$startValue.$endValue.$bank.$bank_to.$account.$account_to."  ORDER BY `transfers_sn`  DESC " . $addon);
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            return ($GLOBALS['db']->fetchlist());
        } else {
            return null;
        }
    }

    function Add_Money_Transfer($Transfer)
    {
        if ($Transfer['transfers_from'] == 'safe') {
            $Transfer['transfers_from_in']  = 'safe';
            $Transfer['transfers_from']    = 0;
        } else {
            $Transfer['transfers_from_in']  = 'bank';
        }

        if ($Transfer['transfers_to'] == 'safe') {
            $Transfer['transfers_to_in']  = 'safe';
            $Transfer['transfers_to']    = 0;
        } else {
            $Transfer['transfers_to_in']  = 'bank';
        }
        $query = $GLOBALS['db']->query("INSERT INTO `money_transfers` 
            (`transfers_sn`, `transfers_date`, `transfers_from_in`, `transfers_from`, `transfers_account_type_from`, `transfers_account_id_from`, 
            `transfers_client_id_from`, `transfers_product_id_from`, `transfers_value`, `transfers_type`, `transfers_cheque_date`, `transfers_cheque_number`, 
            `transfers_to_in`,`transfers_to`, `transfers_account_type_to`, `transfers_account_id_to`, `transfers_client_id_to`, `transfers_product_id_to`, `transfers_cut_precent`, 
            `transfers_cut_value`, `transfers_days`, `transfers_date_pay`, `invoices_id`, `transfers_text`)
            VALUES(NULL,'" . $Transfer['transfers_date'] . "','" . $Transfer['transfers_from_in'] . "','" . $Transfer['transfers_from'] . "','" . $Transfer['transfers_account_type_from'] . "','" . $Transfer['transfers_account_id_from'] . "'
            ,'" . $Transfer['transfers_client_id_from'] . "','" . $Transfer['transfers_product_id_from'] . "','" . $Transfer['transfers_value'] . "','" . $Transfer['transfers_type'] . "','" . $Transfer['transfers_cheque_date'] . "','" . $Transfer['transfers_cheque_number'] . "'
            ,'" . $Transfer['transfers_to_in'] . "','" . $Transfer['transfers_to'] . "','" . $Transfer['transfers_account_type_to'] . "','" . $Transfer['transfers_account_id_to'] . "','" . $Transfer['transfers_client_id_to'] . "','" . $Transfer['transfers_product_id_to'] . "','" . $Transfer['transfers_cut_precent'] . "'
            ,'" . $Transfer['transfers_cut_value'] . "','" . $Transfer['transfers_days'] . "','" . $Transfer['transfers_date_pay'] . "','" . $Transfer['invoices_id'] . "','" . $Transfer['transfers_text'] . "')
        ");
		$transfer_id=$GLOBALS['db']->fetchLastInsertId();
        // ***************** PULL FROM SAFE *******************//
        if ($Transfer['transfers_from'] == 'safe') {
            $company_query = $GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if ($Transfer['transfers_type'] == 'cash') {
                $cash = $sitecompany['companyinfo_opening_balance_safe'] - $Transfer['transfers_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_safe`   =  '" . $cash . "'
                     WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
            } else {
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $Transfer['transfers_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_cheques`= '" . $cheque . "'
                     WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
            }
        } else {
            if ($Transfer['transfers_account_type_from'] == 'current' || $Transfer['transfers_account_type_from'] == 'saving') {
                if ($Transfer['transfers_type'] == 'cash') {

                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_from'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_from'] . "' ");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] - $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                        `banks_finance_credit`    = '" . $banks_finance_credit . "'
                            WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            } elseif ($Transfer['transfers_account_type_from'] == 'credit') {
                if ($Transfer['invoices_id'] > 0) {
                    $account_query = $GLOBALS['db']->query("SELECT * FROM `deposits` WHERE `deposits_sn` =  '" . $Transfer['invoices_id'] . "' ");
                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);
                    $transfer_money_pull   = $siteaccount['deposit_money_pull'] + $Transfer['transfers_value'];
                    $Transfer_pull_total   = $siteaccount['deposits_pull_total'] + $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `deposits` SET
                        `deposit_money_pull`           ='" . $transfer_money_pull . "',
                        `deposits_pull_total`          ='" . $Transfer_pull_total . "'
                         WHERE `deposits_sn` = '" . $siteaccount['deposits_sn'] . "'");
                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_from'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_from'] . "' AND `banks_finance_account_id` = '" . $Transfer['transfers_account_id_from'] . "'");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit      =  $siteaccount['banks_finance_credit'] - $Transfer['transfers_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] - $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
                            `banks_finance_credit`         = '" . $banks_finance_credit . "',
                            `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                         WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                } else {

                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_from'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_from'] . "' AND `banks_finance_account_id` = '" . $Transfer['transfers_account_id_from'] . "'");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit      =  $siteaccount['banks_finance_credit'] - $Transfer['transfers_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] - $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                            `banks_finance_credit`         = '" . $banks_finance_credit . "',
                            `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                         WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            }
        }
        //*************** PUSH IN SAVE *********//
        if ($Transfer['transfers_to'] == 'safe') {
            $company_query = $GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if ($Transfer['transfers_type'] == 'cash') {
                if ($Transfer['transfers_from'] == 'safe') {
                	$cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $Transfer['transfers_value'];
					$GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
						 `companyinfo_opening_balance_cheques`= '" . $cheque . "'
						 WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
				}else{
                    $cash = $sitecompany['companyinfo_opening_balance_safe'] + $Transfer['transfers_value'];
                    $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                        `companyinfo_opening_balance_safe`   =  '" . $cash . "'
                        WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
                }
            } else {
					$cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $Transfer['transfers_value'];
					$GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
						 `companyinfo_opening_balance_cheques`= '" . $cheque . "'
						 WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
            }
        } else {

            if ($Transfer['transfers_account_type_to'] == 'current' || $Transfer['transfers_account_type_to'] == 'saving') {
                if ($Transfer['transfers_type'] == 'cash') {

                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_to'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_to'] . "' ");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $Transfer['transfers_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                        `banks_finance_credit`    = '" . $banks_finance_credit . "',
                        `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                            WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            } elseif ($Transfer['transfers_account_type_to'] == 'credit') {
                if ($Transfer['transfers_type'] == 'cheque') {

                    $GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `transfers`
                         (`transfers_sn`, `transfers_date`, `transfers_type`, `transfers_value`, `transfers_cheque_date`, `transfers_cheque_number`, `transfers_insert_in`, `transfers_bank_id`, `transfers_account_type`, `transfers_account_id`, `transfers_client_id`, `transfers_product_id`,`transfers_cut_precent`, `transfers_cut_value`, `transfers_days`, `deposit_date_pay`, `transfers_status`) 
                        VALUES ( NULL ,'" . $Transfer['transfers_date'] . "','" . $Transfer['transfers_type'] . "','" . $Transfer['transfers_value'] . "','" . $Transfer['transfers_cheque_date'] . "', '" . $Transfer['transfers_cheque_number'] . "','bank','" . $Transfer['transfers_to'] . "','" . $Transfer['transfers_account_type_to'] . "','" . $Transfer['transfers_account_id_to'] . "','" . $Transfer['transfers_client_id_to'] . "','" . $Transfer['transfers_product_id_to'] . "','" . $Transfer['transfers_cut_precent'] . "','" . $Transfer['transfers_cut_value'] . "','" . $Transfer['transfers_days'] . "','" . $Transfer['transfers_date_pay'] . "',1)");


					$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `deposits`
						(`deposits_sn`, `deposits_date`, `deposits_type`, `deposits_value`, `deposits_cheque_date`,
						`deposits_cheque_number`, `deposits_insert_in`, `deposits_bank_id`, `deposits_account_type`,
						`deposits_account_id`,`deposits_client_id`, `deposits_product_id`,`deposits_cut_precent`, `deposits_cut_value`, `deposits_days`,
						`deposit_date_pay`,`deposits_status`)
						VALUES ( NULL ,'".$Transfer['transfers_date']."','".$Transfer['transfers_type']."','".$Transfer['transfers_value']."','".$Transfer['transfers_cheque_date']."',
						'".$Transfer['transfers_cheque_number']."','bank','".$Transfer['transfers_to']."','".$Transfer['transfers_account_type_to']."',
						'".$Transfer['transfers_account_id_to']."','".$Transfer['transfer_client_id_to']."','".$Transfer['transfer_product_id_to']."','".$Transfer['transfers_cut_precent']."','".$Transfer['transfers_cut_value']."','".$Transfer['transfers_days']."','".$Transfer['deposit_date_pay']."',1)");
						$deposits_id=$GLOBALS['db']->fetchLastInsertId();

						$reminders_remember_date  = date('Y-m-d', strtotime('-7days', strtotime($Transfer['transfers_cheque_date'])));
						$GLOBALS['db']->query("INSERT INTO `reminders`
						(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
						VALUES
						(NULL ,'deposits','" . $deposits_id . "','" . $Transfer['transfers_cheque_date'] . "','day','7','" . $reminders_remember_date . "','" . $reminders_remember_date . "',1)");

				} else {
                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_to'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_to'] . "' AND `banks_finance_account_id` = '" . $Transfer['transfers_account_id_to'] . "'");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $Transfer['transfers_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                            `banks_finance_credit`    = '" . $banks_finance_credit . "',
                            `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                         WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            }
        }
		if ($Transfer['transfers_type'] == "cheque" && $Transfer['transfers_account_type_to'] != 'credit' && $Transfer['transfers_from'] != 'safe' && $Transfer['transfers_to'] != 'safe' ) {

            $reminders_remember_date  = date('Y-m-d', strtotime('-7days', strtotime($Transfer['transfers_cheque_date'])));
            $GLOBALS['db']->query("INSERT INTO `reminders`
			(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
			VALUES
			(NULL ,'transfer','" . $transfer_id . "','" . $Transfer['transfers_cheque_date'] . "','day','7','" . $reminders_remember_date . "','" . $reminders_remember_date . "',1)");
        }
		return 1;
    }
	
	function Bank_Approved($id)
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `money_transfers` 
        WHERE `transfers_sn`= '" . $id . "'   AND `transfers_bank_approved` = '0' AND `transfers_status` = '1'");
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            $transfer = $GLOBALS['db']->fetchitem($query);
            $GLOBALS['db']->query("UPDATE `money_transfers` SET `transfers_bank_approved`=1,`transfers_bank_approved_date`=NOW() WHERE `transfers_sn` = '" . $id . "'");
            if ($transfer['transfers_account_type_to'] == 'current' || $transfer['transfers_account_type_to'] == 'saving') {
                $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $transfer['transfers_to'] . "' AND `banks_finance_account_type` = '" . $transfer['transfers_account_type_to'] . "' ");
                $siteaccount = $GLOBALS['db']->fetchitem($account_query);
                $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $transfer['transfers_value'];
                $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $transfer['transfers_value'];
                $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                 `banks_finance_credit`         = '" . $banks_finance_credit . "',
                 `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "',
                WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
            } 
            return 1;
        } else {
            return 0;
        }
    }

}

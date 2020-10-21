<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemDeposits
{
    var $tableName 	= "deposits";

    function getsiteDepoists($addon = "",$search = "")
    {
		if($search != "")
		{
			if($search['id'] > 0){
				$id = " AND `deposits_sn` = '".$search['id']."'";
			}else{
				$id = "" ;
			}
			
			if($search['startDate'] != ""){
				$startDate = " AND `deposits_date` >= '".$search['startDate']."'";
			}else{
				$startDate = "" ;
			}
			
			if($search['endDate'] != ""){
				$endDate = " AND `deposits_date` <= '".$search['endDate']."'";
			}else{
				$endDate = "" ;
			}
			
			if($search['client_id'] != 0){
				$client_id = " AND `deposits_client_id` = '".$search['client_id']."'";
			}else{
				$client_id = "" ;
			}
			
			if($search['prroduct_client_id'] != 0){
				$product_id = " AND `deposits_product_id` = '".$search['prroduct_client_id']."'";
			}else{
				$product_id = "" ;
			}
			
			if($search['startValue'] != 0){
				$startValue= " AND `deposits_value` >= '".$search['startValue']."'";
			}else{
				$startValue= "" ;
			}
			
			if($search['endValue'] != 0){
				$endValue = " AND `deposits_value` <= '".$search['endValue']."'";
			}else{
				$endValue = "" ;
			}
			
			if($search['bank'] != ""){
				if($search['bank'] == "safe")
				{
					$bank = " AND `deposits_insert_in` = '".$search['bank']."'";
				}else{
					$bank = " AND `deposits_bank_id` = '".$search['bank']."'";

				}
			}else{
				$bank = "" ;
			}
			
			if($search['account'] != 0){
				$account = " AND `deposits_account_id` = '".$search['account']."'";
			}else{
				$account = "" ;
			}
			
			if($search['cheque'] != ""){
				$cheque = " AND `deposits_cheque_number` = '".$search['cheque']."'";
			}else{
				$cheque = "" ;
			}
		}
		
        $query = $GLOBALS['db']->query("SELECT * FROM `" . $this->tableName . "` WHERE `deposits_status` != '0' ".$id.$cheque.$startDate.$endDate.$client_id.$product_id.$startValue.$endValue.$bank.$account."  ORDER BY `deposits_sn`  DESC " . $addon);
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            return ($GLOBALS['db']->fetchlist());
        } else {
            return null;
        }
    }
    
	function Add_Deposits($Deposits)
	{
        if($Deposits['deposits_bank_id']== 'safe')
        {
            $Deposits['deposits_insert_in']  = 'safe';
            $Deposits['deposits_bank_id']    = 0 ;
        }else{
            $Deposits['deposits_insert_in']  = 'bank';
        }
        $GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
        (`deposits_sn`, `deposits_date`, `deposits_type`, `deposits_value`, `deposits_cheque_date`, `deposits_cheque_number`, `deposits_insert_in`, `deposits_bank_id`, `deposits_account_type`, `deposits_account_id`, `deposits_client_id`, `deposits_product_id`,`deposits_cut_precent`, `deposits_cut_value`, `deposits_days`, `deposit_date_pay`, `deposits_status`) 
        VALUES ( NULL ,'".$Deposits['deposits_date']."','".$Deposits['deposits_type']."','".$Deposits['deposits_value']."','".$Deposits['deposits_cheque_date']."', '".$Deposits['deposits_cheque_number']."','".$Deposits['deposits_insert_in']."','".$Deposits['deposits_bank_id']."','".$Deposits['deposits_account_type']."','".$Deposits['deposits_account_id']."','".$Deposits['deposits_client_id']."','".$Deposits['deposits_product_id']."','".$Deposits['deposits_cut_precent']."','".$Deposits['deposits_cut_value']."','".$Deposits['deposits_days']."','".$Deposits['deposit_date_pay']."',1)");
        $deposits_id=$GLOBALS['db']->fetchLastInsertId();
       if($Deposits['deposits_bank_id']== 'safe')
        {
            $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if($Deposits['deposits_type']== 'cash'){
                $cash = $sitecompany['companyinfo_opening_balance_safe'] + $Deposits['deposits_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_safe`   =  '".$cash."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

            }else{
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] + $Deposits['deposits_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_cheques`= '".$cheque."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
            }
            
        }else{
            if($Deposits['deposits_account_type']=='current' || $Deposits['deposits_account_type']=='saving'){
                if($Deposits['deposits_type']== 'cash'){

                    $account_query=$GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '".$Deposits['deposits_bank_id']."' AND `banks_finance_account_type` = '".$Deposits['deposits_account_type']."' ");
                    
                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit      =  $siteaccount['banks_finance_credit'] + $Deposits['deposits_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $Deposits['deposits_value'];
                
                    $account_query=$GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                    `banks_finance_credit`    = '".$banks_finance_credit."',
                    `banks_total_with_benefits`    = '".$banks_total_with_benefits."'
                        WHERE `banks_finance_sn` = '".$siteaccount['banks_finance_sn']."'");
                }

            }elseif($Deposits['deposits_account_type']=='credit'){
                $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Deposits['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $Deposits['deposits_account_type'] . "' AND `banks_finance_account_id` = '" . $Deposits['deposits_account_id'] . "'");
                $sitebank = $GLOBALS['db']->fetchitem($account_query);
                if ($Deposits['deposits_type'] == 'cash') {
                    if(is_array($Deposits['invoices_id']))
                    {
                        $deposits_value = $Deposits['deposits_value'];
                        foreach($Deposits['invoices_id'] as $k => $i){
                            $id= intval($i);
                            $account_query=$GLOBALS['db']->query("SELECT * FROM `deposits` WHERE `deposits_sn` =  '".$id."' ");
                            $siteaccount = $GLOBALS['db']->fetchitem($account_query);
                            if($deposits_value > $siteaccount['deposits_pull_total'])
                            {
                                $paid           = $siteaccount['deposits_pull_total'];
                                $deposits_value = $Deposits['deposits_value'] - $siteaccount['deposits_pull_total'];
                                
                                 $account_query=$GLOBALS['db']->query("UPDATE `deposits` SET
                                `deposits_collected`           ='1',
                                `deposits_collected_value`     ='".$paid."',
                                `deposits_pull_total`          ='0',
                                `deposits_collected_date`=NOW()
                                 WHERE `deposits_sn` = '".$siteaccount['deposits_sn']."'");
                            }else{
                                $paid   = $deposits_value;
                                $deposits_pull_total = $siteaccount['deposits_pull_total'] - $deposits_value;
                                $deposit_money_pull = $siteaccount['deposit_money_pull'] - $deposits_value;
                                 $account_query=$GLOBALS['db']->query("UPDATE `deposits` SET
                                `deposits_collected_value`     = '".$paid."',
                                `deposit_money_pull`           = '".$deposit_money_pull."',
                                `deposits_pull_total`          = '".$deposits_pull_total."'
                                 WHERE `deposits_sn` = '".$siteaccount['deposits_sn']."'");
                            }
                            
                                 $GLOBALS['db']->query("INSERT INTO `deposits_invoices`
                                 (`deposits_invoices_sn`, `deposits_id`, `invoices_id`, `value`, `paid`) 
                                 VALUES (NULL,'".$deposits_id."','".$id."','".$siteaccount['deposits_value']."' ,'".$paid."')");
                        }
						$banks_finance_credit =  $sitebank['banks_finance_credit'] + $deposits_value;
                        $banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] + $deposits_value;
						$account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
						`banks_finance_credit`         = '" . $banks_finance_credit . "',
						`banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
						WHERE `banks_finance_sn`       = '" . $sitebank['banks_finance_sn'] . "'");
                    } else {
                        $banks_finance_credit =  $sitebank['banks_finance_credit'] + $Deposits['deposits_value'];
                        $banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] + $Deposits['deposits_value'];
                        $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                        `banks_finance_credit`    = '" . $banks_finance_credit . "',
                        `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                        WHERE `banks_finance_sn` = '" . $sitebank['banks_finance_sn'] . "'");
                    }
                }
            }
        }

        if ($Deposits['deposits_type'] == "cheque") {

            $reminders_remember_date  = date('Y-m-d', strtotime('-7days', strtotime($Deposits['deposits_cheque_date'])));
            $GLOBALS['db']->query("INSERT INTO `reminders`
			(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`)
			VALUES
			(NULL ,'deposits','" . $deposits_id . "','" . $Deposits['deposits_cheque_date'] . "','day','7','" . $reminders_remember_date . "','" . $reminders_remember_date . "',1)");
        }
      
      return 1;

    }
    
    function Get_invoices($bank,$acount,$acount_id)
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `deposits` 
        WHERE `deposits_bank_id`= '".$bank."' AND `deposits_account_type` ='".$acount."' 
        AND `deposits_account_id` ='".$acount_id."' AND `deposits_approved` = '1' AND `deposits_collected` = '0'");
        $queryTotal = $GLOBALS['db']->resultcount($query);
         if($queryTotal > 0)
         {
             return($GLOBALS['db']->fetchlist());
         }else{return null;}

    }

    function Bank_Approved($id)
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `deposits` 
        WHERE `deposits_sn`= '" . $id . "'   AND `deposits_approved` = '0' AND `deposits_status` = '1'");
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            $deposit = $GLOBALS['db']->fetchitem($query);
            $GLOBALS['db']->query("UPDATE `deposits` SET `deposits_approved`=1,`deposits_approved_date`=NOW() WHERE `deposits_sn` = '" . $id . "'");
//            if ($deposit['deposits_account_type'] == 'current' || $deposit['deposits_account_type'] == 'saving') {
//                $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $deposit['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $deposit['deposits_account_type'] . "' ");
//                $siteaccount = $GLOBALS['db']->fetchitem($account_query);
//                $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $deposit['deposits_value'];
//                $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $deposit['deposits_value'];
//                $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
//                 `banks_finance_credit`         = '" . $banks_finance_credit . "',
//                 `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
//                WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
//            } elseif ($deposit['deposits_account_type'] == 'credit') {
//                $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $deposit['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $deposit['deposits_account_type'] . "' ");
//                $siteaccount = $GLOBALS['db']->fetchitem($account_query);
//                $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $deposit['deposits_cut_value'];
//                $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + $deposit['deposits_cut_value'];
//                $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
//                 `banks_finance_credit`    = '" . $banks_finance_credit . "',
//                 `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
//                WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
//            }
            return 1;
        } else {
            return 0;
        }
    }


    function Bank_Collect($id)
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `deposits` 
        WHERE `deposits_sn`= '" . $id . "'   AND `deposits_collected` = '0' AND `deposits_status` = '1'");
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            $deposit = $GLOBALS['db']->fetchitem($query);
            $GLOBALS['db']->query("UPDATE `deposits` SET `deposits_collected`=1,`deposits_collected_date` = NOW() WHERE `deposits_sn` = '" . $id . "'");
			if($deposit['deposits_insert_in'] == 'bank')
			{
				if ($deposit['deposits_account_type'] == 'current' || $deposit['deposits_account_type'] == 'saving') {
					$account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $deposit['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $deposit['deposits_account_type'] . "' ");

					$siteaccount = $GLOBALS['db']->fetchitem($account_query);

					$banks_finance_credit =  $siteaccount['banks_finance_credit'] - $deposit['deposit_benefits'];

					$banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] - $deposit['deposit_benefits'];

					$account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
					 `banks_finance_credit`    = '" . $banks_finance_credit . "',
					 `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
					WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
				} elseif ($deposit['deposits_account_type'] == 'credit') {
					$account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $deposit['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $deposit['deposits_account_type'] . "' ");

					$siteaccount = $GLOBALS['db']->fetchitem($account_query);

					$banks_finance_credit =  $siteaccount['banks_finance_credit'] + ($deposit['deposits_value'] - ($deposit['deposit_benefits'] ));

					$banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] + ($deposit['deposits_value'] - ($deposit['deposit_benefits'] ));

					$account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
					 `banks_finance_credit`         = '" . $banks_finance_credit . "',
					 `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
					WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
				}
			}elseif($deposit['deposits_insert_in'] == 'safe'){
				$company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
				$sitecompany = $GLOBALS['db']->fetchitem($company_query);
				$cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $deposit['deposits_value'];
				$cash = $sitecompany['companyinfo_opening_balance_safe'] + $deposit['deposits_value'];
				$GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
				 `companyinfo_opening_balance_cheques`   =  '".$cheque."',
				 `companyinfo_opening_balance_safe`   =  '".$cash."'
				 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
			}

            return 1;
        } else {
            return 0;
        }
    }


	function Delete_deposits($id)
	{
		$company_query=$GLOBALS['db']->query("SELECT * FROM `deposits` WHERE `deposits_sn` = '".$id."' AND `deposits_status` !='0'");
        $Deposits = $GLOBALS['db']->fetchitem($company_query);
        if($Deposits['deposits_insert_in']== 'safe')
        {
            $company_query=$GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if($Deposits['deposits_type']== 'cash'){
                $cash = $sitecompany['companyinfo_opening_balance_safe'] - $Deposits['deposits_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_safe`   =  '".$cash."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");

            }else{
                $cheque = $sitecompany['companyinfo_opening_balance_cheques'] - $Deposits['deposits_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                 `companyinfo_opening_balance_cheques`= '".$cheque."'
                 WHERE `companyinfo_sn` = '".$sitecompany['companyinfo_sn']."'");
            }

        }else{

            if($Deposits['deposits_account_type']=='current' || $Deposits['deposits_account_type']=='saving'){
                if($Deposits['deposits_type']== 'cash'){

                    $account_query=$GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '".$Deposits['deposits_bank_id']."' AND `banks_finance_account_type` = '".$Deposits['deposits_account_type']."' ");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit      =  $siteaccount['banks_finance_credit'] - $Deposits['deposits_value'];
                    $banks_total_with_benefits =  $siteaccount['banks_total_with_benefits'] - $Deposits['deposits_value'];

                    $account_query=$GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
                    `banks_finance_credit`    = '".$banks_finance_credit."',
                    `banks_total_with_benefits`    = '".$banks_total_with_benefits."'
                        WHERE `banks_finance_sn` = '".$siteaccount['banks_finance_sn']."'");
                }

            }elseif($Deposits['deposits_account_type']=='credit')
			{
                $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Deposits['deposits_bank_id'] . "' AND `banks_finance_account_type` = '" . $Deposits['deposits_account_type'] . "' AND `banks_finance_account_id` = '" . $Deposits['deposits_account_id'] . "'");
                $sitebank = $GLOBALS['db']->fetchitem($account_query);

                if ($Deposits['deposits_type'] == 'cash') {
					$invoices=$GLOBALS['db']->query("SELECT * FROM `deposits_invoices` WHERE `deposits_id` = '".$id."' AND `status` != 0 ");
                    $invoices_Total = $GLOBALS['db']->resultcount($invoices);
					if ($invoices_Total > 0) {
						$invoices = ($GLOBALS['db']->fetchlist());

						if(is_array($invoices))
						{

							$deposits_value = $Deposits['deposits_value'];
							foreach($invoices as $k => $i){
								$invoices_id= intval($i['invoices_id']);
								$account_query=$GLOBALS['db']->query("SELECT * FROM `deposits` WHERE `deposits_sn` =  '".$invoices_id."' ");
								$siteaccount = $GLOBALS['db']->fetchitem($account_query);

								if($siteaccount['deposits_collected'] = $i['paid'])
								{
									$deposits_value = $Deposits['deposits_value'] - $i['paid'];
									$deposits_pull_total = $siteaccount['deposits_pull_total'] + $i['paid'];

									 $account_query=$GLOBALS['db']->query("UPDATE `deposits` SET
									`deposits_collected`           ='0',
									`deposits_collected_value`     ='0',
									`deposits_pull_total`          ='".$deposits_pull_total."'
									 WHERE `deposits_sn` = '".$siteaccount['deposits_sn']."'");
								}
								 $GLOBALS['db']->query("UPDATE `deposits_invoices` SET `status`= '0' WHERE `deposits_invoices_sn` = '".$i['deposits_invoices_sn']."' ");
							}
							$banks_finance_credit =  $sitebank['banks_finance_credit'] - $deposits_value;
							$banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] - $deposits_value;
							$account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
							`banks_finance_credit`         = '" . $banks_finance_credit . "',
							`banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
							WHERE `banks_finance_sn`       = '" . $sitebank['banks_finance_sn'] . "'");
						}
					}else {
                        $banks_finance_credit =  $sitebank['banks_finance_credit'] - $Deposits['deposits_value'];
                        $banks_total_with_benefits =  $sitebank['banks_total_with_benefits'] - $Deposits['deposits_value'];
                        $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
                        `banks_finance_credit`    = '" . $banks_finance_credit . "',
                        `banks_total_with_benefits`    = '" . $banks_total_with_benefits . "'
                         WHERE `banks_finance_sn` = '" . $sitebank['banks_finance_sn'] . "'");
                    }
                }
            }
        }

        if ($Deposits['deposits_type'] == "cheque") {
            $GLOBALS['db']->query("DELETE FROM `reminders` WHERE `reminders_type` ='deposits' AND `reminders_type_id` = '" . $id . "' ");
        }

		$GLOBALS['db']->query("UPDATE `deposits` SET `deposits_status`= '0'  WHERE `deposits_sn` = '".$id."' ");


      return 1;

    }



}
?>

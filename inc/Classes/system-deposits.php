<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemDeposits
{
    var $tableName 	= "deposits";
    
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

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $Deposits['deposits_value'];
                
                    $account_query=$GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                    `banks_finance_credit`    = '".$banks_finance_credit."'
                        WHERE `banks_finance_sn` = '".$siteaccount['banks_finance_sn']."'");
                }

            }elseif($Deposits['deposits_account_type']=='credit'){
                $account_query=$GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '".$Deposits['deposits_bank_id']."' AND `banks_finance_account_type` = '".$Deposits['deposits_account_type']."' AND `banks_finance_account_id` = '".$Deposits['deposits_account_id']."'");
               
                $siteaccount = $GLOBALS['db']->fetchitem($account_query);
                if($Deposits['deposits_type']== 'cash'){


                    if(is_array($Deposits['invoices_id']))
                    {
                        $deposits_value = $Deposits['deposits_value'];
                        foreach($Deposits['invoices_id'] as $k => $i){
                            $id= intval($i);
                            $account_query=$GLOBALS['db']->query("`SELECT * FROM `deposits` WHERE `deposits_sn` = = '".$id."' ");
                            $siteaccount = $GLOBALS['db']->fetchitem($account_query);
                            if($deposits_value > $siteaccount['deposits_value'])
                            {
                                $paid           = $siteaccount['deposits_value'];
                                $deposits_value = $Deposits['deposits_value'] - $siteaccount['deposits_value'];
                                $remin = ($siteaccount['deposits_value'] -($siteaccount['deposit_money_pull'] +$siteaccount['deposit_benefits']));
                                
                                $account_query=$GLOBALS['db']->query("UPDATE `deposits` SET
                                `deposits_collected`     ='1',
                                `deposits_collected_value`     ='".$paid."',
                                `deposits_collected_date`=NOW()
                                 WHERE `deposits_sn` = '".$siteaccount['deposits_sn']."'");
                                 $GLOBALS['db']->query("INSERT INTO `deposits_invoices`
                                 (`deposits_invoices_sn`, `deposits_id`, `invoices_id`, `value`, `paid`) 
                                 VALUES (NULL,'".$deposits_id."','".$id."','".$siteaccount['deposits_value']."' ,'".$paid."')");
     
                                $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $remin;
                            }else{
                                $banks_finance_credit = $deposits_value;
                            }
                            
                            $account_query=$GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                                `banks_finance_credit`    = '".$banks_finance_credit."'
                                WHERE `banks_finance_sn` = '".$siteaccount['banks_finance_sn']."'");

                            
                            

                            
                        }

                    }

                }
            }
        }
          

      
      return 1;

    }
    
    function Get_invoices($bank,$acount,$acount_id)
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `deposits` 
        WHERE `deposits_bank_id`= '".$bank."' AND `deposits_account_type` ='".$acount."' 
        AND `deposits_account_id` ='".$acount_id."' AND `deposits_approved` = '1' AND `deposits_collected` = '0'");
         $queryTotal = $GLOBALS['db']->resultcount();
         if($queryTotal > 0)
         {
             return($GLOBALS['db']->fetchlist());
         }else{return null;}

    }



}
?>

<?php
    // my system key cheker..
    define("inside",true);

	// get funcamental file which contain config and template files,settings.
	include("./inc/fundamentals.php");

                                             /****************** Update Notifications *******************/
		$date   = date('Y-m-d');
	    $query = $GLOBALS['db']->query("SELECT * FROM `reminders`  WHERE `reminders_status` = '2'");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
			$reminders = $GLOBALS['db']->fetchlist();
			foreach($reminders as $k => $r)
			{
				$next_date = date('Y-m-d',strtotime('+'.$r['reminders_number_reminder'].'days',strtotime($r['reminders_notification_date'])));
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `reminders` r  SET
				r.`reminders_notification_date`	    = 		'".$next_date."',
				r.`reminders_status`			    = 		'1'
			  WHERE r.`reminders_sn` = '".$r['reminders_sn']."' AND r.`reminders_notification_date` = '".$r['reminders_notification_date']."' AND  r.`reminders_status`  = '2' ");
			}
        }



                        /************************************ Update penfits value in Deposits *****************************************/

        $query_Deposits = $GLOBALS['db']->query("SELECT * FROM `deposits` WHERE `deposits_type` = 'cheque' AND `deposits_account_type` = 'credit' AND `deposits_approved` = '1' AND `deposits_collected` ='0' AND `deposit_money_pull` > 0 AND `deposits_status` != '0' ");
        $Totaldeposits = $GLOBALS['db']->resultcount($query_Deposits);
        if($Totaldeposits > 0)
        {
			$deposits = $GLOBALS['db']->fetchlist();

			foreach($deposits as $k => $d)
			{
                $query_credits = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE `banks_credit_sn` ='".$d['deposits_account_id']."' AND `banks_credit_status` !='0' ");
                $Totalcredits = $GLOBALS['db']->resultcount($query_Deposits);
                if($Totalcredits > 0)
                {
                    $credits =$GLOBALS['db']->fetchitem($query_credits);

                    $benefits     = $d['deposit_money_pull'] * (($credits['banks_credit_interest_rate']/100)/$credits['banks_credit_duration_of_interest']);
                    $new_benefits = $d['deposit_benefits'] + $benefits ;
                    $deposits_pull_total = $d['deposit_money_pull'] +  $new_benefits ;
                    $GLOBALS['db']->query("UPDATE `deposits` SET
                    `deposit_benefits`      =     '".$new_benefits."',
                    `deposits_pull_total`   =     '".$deposits_pull_total."'
                    WHERE `deposits_sn` ='".$d['deposits_sn']."'
                    ");
                }
            }
        }



		                        /************************************ Update penfits value in CREDIT ACCOUNT *****************************************/

        $query_CREDIT = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_account_type` = 'credit' AND `banks_finance_credit` < 0");
        $Totalcredits= $GLOBALS['db']->resultcount($query_CREDIT);
        if($Totalcredits > 0)
        {
			$account = $GLOBALS['db']->fetchlist();
			foreach($account as $k => $d)
			{
                $query_credits = $GLOBALS['db']->query("SELECT * FROM `settings_banks_credit` WHERE `banks_credit_sn` ='".$d['banks_finance_account_id']."' AND `banks_credit_status` !='0' ");
                $Totalcredits = $GLOBALS['db']->resultcount($query_Deposits);
                if($Totalcredits > 0)
                {
                    $credits =$GLOBALS['db']->fetchitem($query_credits);
                    $benefits     = $d['banks_finance_credit'] * (($credits['banks_credit_interest_rate']/100)/$credits['banks_credit_duration_of_interest']);
                    $new_benefits = $d['banks_benefits'] + $benefits ;
                    $deposits_pull_total = $d['banks_finance_credit'] +  $new_benefits ;
                    $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET
                    `banks_benefits`            =     '".$new_benefits."',
                    `banks_total_with_benefits`   =     '".$deposits_pull_total."'
                    WHERE `banks_finance_sn` ='".$d['banks_finance_sn']."'
                    ");
                }
            }
        }



    $db->disconnect();
	ob_end_flush();

?>




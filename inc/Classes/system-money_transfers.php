<?php if (!defined("inside")) exit;
if (!isset($_SESSION)) {
    session_start();
}
class systemMoney_transfers
{
    var $tableName     = "money_transfers";

    function getsiteMoney_transfers($addon = "")
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `" . $this->tableName . "` WHERE `transfer_status` != '0'  ORDER BY `transfer_sn`  DESC " . $addon);
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
            `transfers_cut_value`, `transfers_days`, `transfers_date_pay`, `invoices_id`)
            VALUES(NULL,'" . $Transfer['transfers_date'] . "','" . $Transfer['transfers_from_in'] . "','" . $Transfer['transfers_from'] . "','" . $Transfer['transfers_account_type_from'] . "','" . $Transfer['transfers_account_id_from'] . "'
            ,'" . $Transfer['transfers_client_id_from'] . "','" . $Transfer['transfers_product_id_from'] . "','" . $Transfer['transfers_value'] . "','" . $Transfer['transfers_type'] . "','" . $Transfer['transfers_cheque_date'] . "','" . $Transfer['transfers_cheque_number'] . "'
            ,'" . $Transfer['transfers_to_in'] . "','" . $Transfer['transfers_to'] . "','" . $Transfer['transfers_account_type_to'] . "','" . $Transfer['transfers_account_id_to'] . "','" . $Transfer['transfers_client_id_to'] . "','" . $Transfer['transfers_product_id_to'] . "','" . $Transfer['transfers_cut_precent'] . "'
            ,'" . $Transfer['transfers_cut_value'] . "','" . $Transfer['transfers_days'] . "','" . $Transfer['transfers_date_pay'] . "','" . $Transfer['invoices_id'] . "')
        ");
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
                    $deposit_money_pull   = $siteaccount['deposit_money_pull'] + $Transfer['transfers_value'];
                    $Transfer_pull_total   = $siteaccount['deposits_pull_total'] + $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `deposits` SET
                        `deposit_money_pull`           ='" . $deposit_money_pull . "',
                        `deposits_pull_total`          ='" . $Transfer_pull_total . "'
                         WHERE `deposits_sn` = '" . $siteaccount['deposits_sn'] . "'");
                } else {

                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_from'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_from'] . "' AND `banks_finance_account_id` = '" . $Transfer['transfers_account_id_from'] . "'");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] - $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                            `banks_finance_credit`    = '" . $banks_finance_credit . "'
                         WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            }
        }
        //*************** PUSH IN SAVE *********//
        if ($Transfer['transfers_to'] == 'safe') {
            $company_query = $GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1");
            $sitecompany = $GLOBALS['db']->fetchitem($company_query);
            if ($Transfer['transfers_type'] == 'cash') {
                $cash = $sitecompany['companyinfo_opening_balance_safe'] + $Transfer['transfers_value'];
                $GLOBALS['db']->query("UPDATE `settings_companyinfo` SET
                     `companyinfo_opening_balance_safe`   =  '" . $cash . "'
                     WHERE `companyinfo_sn` = '" . $sitecompany['companyinfo_sn'] . "'");
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

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                        `banks_finance_credit`    = '" . $banks_finance_credit . "'
                            WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            } elseif ($Transfer['transfers_account_type_to'] == 'credit') {
                if ($Transfer['transfers_type'] == 'cheque') {
                    $GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `deposits`
                         (`deposits_sn`, `deposits_date`, `deposits_type`, `deposits_value`, `deposits_cheque_date`, `deposits_cheque_number`, `deposits_insert_in`, `deposits_bank_id`, `deposits_account_type`, `deposits_account_id`, `deposits_client_id`, `deposits_product_id`,`deposits_cut_precent`, `deposits_cut_value`, `deposits_days`, `deposit_date_pay`, `deposits_status`) 
                        VALUES ( NULL ,'" . $Transfer['transfers_date'] . "','" . $Transfer['transfers_type'] . "','" . $Transfer['transfers_value'] . "','" . $Transfer['transfers_cheque_date'] . "', '" . $Transfer['transfers_cheque_number'] . "','bank','" . $Transfer['transfers_to'] . "','" . $Transfer['transfers_account_type_to'] . "','" . $Transfer['transfers_account_id_to'] . "','" . $Transfer['transfers_client_id_to'] . "','" . $Transfer['transfers_product_id_to'] . "','" . $Transfer['transfers_cut_precent'] . "','" . $Transfer['transfers_cut_value'] . "','" . $Transfer['transfers_days'] . "','" . $Transfer['transfers_date_pay'] . "',1)");
                } else {

                    $account_query = $GLOBALS['db']->query("SELECT * FROM `setiings_banks_finance` WHERE `banks_finance_bank_id` = '" . $Transfer['transfers_from'] . "' AND `banks_finance_account_type` = '" . $Transfer['transfers_account_type_from'] . "' AND `banks_finance_account_id` = '" . $Transfer['transfers_account_id_from'] . "'");

                    $siteaccount = $GLOBALS['db']->fetchitem($account_query);

                    $banks_finance_credit =  $siteaccount['banks_finance_credit'] + $Transfer['transfers_value'];

                    $account_query = $GLOBALS['db']->query("UPDATE `setiings_banks_finance` SET 
                            `banks_finance_credit`    = '" . $banks_finance_credit . "'
                         WHERE `banks_finance_sn` = '" . $siteaccount['banks_finance_sn'] . "'");
                }
            }
        }
    }
}

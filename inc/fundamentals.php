<?php  if(!defined("inside"))  exit;?>
<?php

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    //start session
	error_reporting (E_ALL ^ E_NOTICE);
    ######### Main PATHs #########
	define('INCLUDES_PATH',	dirname(__FILE__) 	. DIRECTORY_SEPARATOR);
	define('CLASSES_PATH',	INCLUDES_PATH 		. "Classes" . DIRECTORY_SEPARATOR);
	define('ROOT_PATH',		INCLUDES_PATH 		. ".." 		. DIRECTORY_SEPARATOR);
	define('ASSETS_PATH', 	ROOT_PATH 			. "assets"	. DIRECTORY_SEPARATOR);
    #########  Db & config Files  #########
	include(CLASSES_PATH 	. 	"database.class.php");
	include(INCLUDES_PATH 	. 	"config.php");
	include(ASSETS_PATH		.	"assets.php");
    ######### Admin Authorization Class #########
	include(CLASSES_PATH 	."login_class.php");
	$login = new loginClass();
    $basicLimit = 5;
    ######## LOGS #############################
    include(CLASSES_PATH  ."system.logs.php");
	$logs = new logs();
    ######## Image path #######################
    $upload_path ='./uploads';
    $path ='./uploads/';
    ######### Language files #########
    include("./assets/Languages/lang.php");

	//  **************** users information **************//
	$user_login = $login->getUserInformation();

///*********Image ***///////
    $avater_default = "/defaults/avater.png";


///*********Image ***///////

    $profile_default = "/defaults/profile-icon.png";

//************** page name ********************//
    $basename      =   basename($_SERVER['PHP_SELF']);
    $page_name     =   str_replace(".php","",$basename);
	$actual_link   =   substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
	$parts         =   parse_url($actual_link);
	$url_get_parts =  $parts['query'];
	
	$from 				= 	date('Y-m-01');
	$to			        = 	date('Y-m-01',strtotime('+3 month',strtotime($from)));



 ######## permission for user login #######
	if ($user_login['users_group'] == -1)
	{
		$group     = array(
		"group_name"                          =>          "Devolper",
		"setting_company"                     =>          1,
		"settings_department"                 =>          1,
		"setting_jobs"                        =>          1,
		"setting_stocks"                      =>          1,
		"settings_products"                   =>          1,
		"settings_users"                      =>          1,
		"settings_clients"                    =>          1,
		"settings_suppliers"                  =>          1,
		"settings_banks"                      =>          1,
		"clients_pricing"                     =>          1,
		"clients_finance"                     =>          1,
		"clients_old_pricing"                 =>          1,
		"clients_payments"                    =>          1,
		"operations"                          =>          1,
		"expense"                             =>          1,
		"deposit_check"                       =>          1,
		"bank_transfer"                       =>          1,
		"supplier_payment"                    =>          1,
		"client_payment"                      =>          1,
		"settings_user_group"                 =>          1,
		"reminders"                           =>          1,
		"delete_deposits"                     =>          1,


			);

	}else{
		$query = $GLOBALS['db']->query("SELECT * FROM `settings_user_group`  WHERE `group_sn` = '".$user_login['users_group']."' AND `group_status` != 0 LIMIT 1 ");
		$queryTotal = $GLOBALS['db']->resultcount();
		if($queryTotal > 0)
		{
			$sitegroup = $GLOBALS['db']->fetchitem($query);
			$group     = array(
			"group_name"                         =>          $sitegroup['group_name'],
			"setting_company"                    =>          $sitegroup['setting_company'],
			"settings_department"                =>          $sitegroup['settings_department'],
			"setting_jobs"                       =>          $sitegroup['setting_jobs'],
			"setting_stocks"                     =>          $sitegroup['setting_stocks'],
			"settings_products"                  =>          $sitegroup['settings_products'],
			"settings_users"                     =>          $sitegroup['settings_users'],
			"settings_clients"                   =>          $sitegroup['settings_clients'],
			"settings_suppliers"                 =>          $sitegroup['settings_suppliers'],
			"settings_banks"                     =>          $sitegroup['settings_banks'],
			"clients_pricing"                    =>          $sitegroup['clients_pricing'],
			"clients_finance"                    =>          $sitegroup['clients_finance'],
			"clients_old_pricing"                =>          $sitegroup['clients_old_pricing'],
			"clients_payments"                   =>          $sitegroup['clients_payments'],
			"operations"                         =>          $sitegroup['operations'],
			"expense"                            =>          $sitegroup['expense'],
			"deposit_check"                      =>          $sitegroup['deposit_check'],
			"bank_transfer"                      =>          $sitegroup['bank_transfer'],
			"client_payment"                     =>          $sitegroup['client_payment'],
			"supplier_payment"                   =>          $sitegroup['supplier_payment'],
			"settings_user_group"                =>          $sitegroup['settings_user_group'],
			"reminders"                          =>          $sitegroup['reminders'],
			"delete_deposits"                    =>          $sitegroup['delete_deposits'],

				);

	  	}
	}



// ************  company information ***********//
	$query = $GLOBALS['db']->query("SELECT * FROM `settings_companyinfo` LIMIT 1 ");
	$queryTotal = $GLOBALS['db']->resultcount();
	if($queryTotal > 0)
	{
		$sitecompany = $GLOBALS['db']->fetchitem($query);
		$companyinfo     = array(
		"companyinfo_name"                         =>          $sitecompany['companyinfo_name'],
		"companyinfo_address"                      =>          $sitecompany['companyinfo_address'],
		"companyinfo_phone"                        =>          $sitecompany['companyinfo_phone'],
		"companyinfo_opening_balance_safe"         =>          $sitecompany['companyinfo_opening_balance_safe'],
		"companyinfo_opening_balance_cheques"      =>          $sitecompany['companyinfo_opening_balance_cheques'],
//		"banks_finance_safe_credit"                =>          ($sitecompany['banks_finance_safe_credit']+$sitecompany['companyinfo_opening_balance_safe']),
//		"banks_finance_cheque_credit"               =>          ($sitecompany['banks_finance_cheque_credit']+$sitecompany['companyinfo_opening_balance_cheques']),
		"companyinfo_logo"                         =>          $sitecompany['companyinfo_logo'],
		"companyinfo_document"                     =>          $sitecompany['companyinfo_document'],

			);

	}



?>

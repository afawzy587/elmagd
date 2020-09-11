<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemClients_pricing
{
	var $tableName 	= "clients_pricing";

	function getsiteClients_pricing($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND (c.`clients_name` LIKE '%".$q."%' || sp.`products_name` LIKE '%".$q."%'|| r.`clients_products_rate_name` LIKE '%".$q."%')";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * 
			FROM (
			SELECT p.* ,r.`clients_products_rate_name` , c.`clients_name` ,sp.`products_name` FROM `clients_pricing` p 
			INNER JOIN `settings_clients_products_rate` r ON p.`pricing_product_rate` = r.`clients_products_rate_sn`
			INNER JOIN `settings_clients_products`cp ON cp.`clients_products_sn` = r.`clients_products_rate_product_id`
			INNER JOIN `settings_clients` c ON c.`clients_sn` = cp.`clients_products_client_id` 
			INNER JOIN `settings_products` sp ON sp.`products_sn` = cp.`clients_products_product_id` WHERE p.`pricing_status` != '0' ".$search." ORDER BY   c.`clients_name` ASC , sp.`products_name` ASC) AS d
			ORDER BY d.`pricing_start_date` DESC".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}
	
	function getsiteClients_pricing_search($_search)
	{
		if($_search['client'] != 0)
		{
			$search = "AND cp.`clients_products_client_id` = '".$_search['client']."' ";
		}
		
		if($_search['product'] != 0)
		{
			$search .="AND cp.`clients_products_sn` = '".$_search['product']."' ";
		}
		
		if(is_array($_search['supplier']))
		{
			$q = implode(',',$_search['supplier']);
			$search .="AND s.`suppliers_products_supplier_id` IN ('".$q."') ";
		}
		
		if(is_array($_search['rate']))
		{
			$r = implode(',',$_search['rate']);
			$search .="AND r.`clients_products_rate_sn` IN ('".$r."') ";
		}
		
		if($_search['startDate'] != 0)
		{
			$search .="AND p.`pricing_start_date` >= '".$_search['startDate']."' ";
		}
		
		if($_search['endDate'] != 0)
		{
			$search .="AND p.`pricing_start_date` <= '".$_search['endDate']."' ";
		}

		$query = $GLOBALS['db']->query(" 
			SELECT DISTINCT p.* ,r.`clients_products_rate_name` , c.`clients_name` ,sp.`products_name` FROM `clients_pricing` p 
			INNER JOIN `settings_clients_products_rate` r ON p.`pricing_product_rate` = r.`clients_products_rate_sn`
			INNER JOIN `settings_clients_products`cp ON cp.`clients_products_sn` = r.`clients_products_rate_product_id`
			INNER JOIN `settings_clients` c ON c.`clients_sn` = cp.`clients_products_client_id` 
			INNER JOIN `settings_products` sp ON sp.`products_sn` = cp.`clients_products_product_id`
			INNER JOIN `settings_suppliers_products` s ON s.`suppliers_products_product_id` = cp.`clients_products_product_id`
			WHERE p.`pricing_status` != '0' ".$search." ORDER BY p.`pricing_sn`  DESC ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalClients_pricing($addon = "")
	{
		if($q != "")
		{
			$search = "AND (c.`clients_name` LIKE '%".$q."%' || sp.`products_name` LIKE '%".$q."%'|| r.`clients_products_rate_name` LIKE '%".$q."%')";
		}else{
			$search = "";
		}
        $query 	= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `clients_pricing` p 
			INNER JOIN `settings_clients_products_rate` r ON p.`pricing_product_rate` = r.`clients_products_rate_sn`
			INNER JOIN `settings_clients_products`cp ON cp.`clients_products_sn` = r.`clients_products_rate_product_id`
			INNER JOIN `settings_clients` c ON c.`clients_sn` = cp.`clients_products_client_id` 
			INNER JOIN `settings_products` sp ON sp.`products_sn` = cp.`clients_products_product_id` WHERE p.`pricing_status` != '0' ".$search."");
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getClients_pricingInformation($pricing_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT p.* ,r.`clients_products_rate_name` , c.`clients_name` ,sp.`products_name` FROM `clients_pricing` p 
			INNER JOIN `settings_clients_products_rate` r ON p.`pricing_product_rate` = r.`clients_products_rate_sn`
			INNER JOIN `settings_clients_products`cp ON cp.`clients_products_sn` = r.`clients_products_rate_product_id`
			INNER JOIN `settings_clients` c ON c.`clients_sn` = cp.`clients_products_client_id` 
			INNER JOIN `settings_products` sp ON sp.`products_sn` = cp.`clients_products_product_id` 
			WHERE `pricing_sn` = '".$pricing_sn."'  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
			
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "pricing_sn"			                 => 		 $sitegroup['pricing_sn'],
                "pricing_product_rate"			         => 		 $sitegroup['pricing_product_rate'],
                "pricing_start_date"                     =>          $sitegroup['pricing_start_date'],
                "pricing_end_date"                       =>          $sitegroup['pricing_end_date'],
                "pricing_selling_price"                  =>          $sitegroup['pricing_selling_price'],
                "pricing_supply_price"                   =>          $sitegroup['pricing_supply_price'],
                "pricing_supply_percent"                 =>          $sitegroup['pricing_supply_percent'],
                "pricing_excuse_active"                  =>          $sitegroup['pricing_excuse_active'],
                "pricing_excuse_price"                   =>          $sitegroup['pricing_excuse_price'],
                "pricing_excuse_percent"                 =>          $sitegroup['pricing_excuse_percent'],
                "pricing_rate_percent"                   =>          $sitegroup['pricing_rate_percent'],
                "pricing_rate_type"                      =>          $sitegroup['pricing_rate_type'],
                "pricing_client_bonus"                   =>          $sitegroup['pricing_client_bonus'],
                "pricing_client_bonus_percent"           =>          $sitegroup['pricing_client_bonus_percent'],
                "pricing_client_bonus_amount"            =>          $sitegroup['pricing_client_bonus_amount'],
                "pricing_supply_bonus"                   =>          $sitegroup['pricing_supply_bonus'],
                "pricing_supply_bonus_percent"           =>          $sitegroup['pricing_supply_bonus_percent'],
                "pricing_supply_bonus_amount"            =>          $sitegroup['pricing_supply_bonus_amount'],
                "clients_products_rate_name"             =>          $sitegroup['clients_products_rate_name'],
                "clients_name"                           =>          $sitegroup['clients_name'],
                "products_name"                          =>          $sitegroup['products_name'],
            	"pricing_status"                         =>          $sitegroup['pricing_status']
            );
        }else{return null;}
	}
	
	function getClients_pricing_last_add($id)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `pricing_product_rate` = '".$id."' AND `pricing_status` != '0' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "pricing_selling_price"			            => 		 $sitegroup['pricing_selling_price'],
                "pricing_supply_price"			            => 		 $sitegroup['pricing_supply_price'],
                "pricing_supply_percent"                    =>          $sitegroup['pricing_supply_percent']
            );
        }else{return null;}
	}

	function setClients_pricingInformation($Clients_pricing)
	{
		if($Clients_pricing['pricing_supply_percent'] > 0 )
		{
			$Clients_pricing['pricing_supply_price'] = $Clients_pricing['pricing_selling_price'] - ($Clients_pricing['pricing_selling_price'] * ($Clients_pricing['pricing_supply_percent']/100));
		}else
		{
			$Clients_pricing['pricing_supply_percent'] = (($Clients_pricing['pricing_selling_price'] - $Clients_pricing['pricing_supply_price'])/$pricing_selling_price)*100;
		}
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
				`pricing_start_date`                  =       '".$Clients_pricing['pricing_start_date']."',
				`pricing_end_date`                    =       '".$Clients_pricing['pricing_end_date']."',
				`pricing_selling_price`               =       '".$Clients_pricing['pricing_selling_price']."',
				`pricing_supply_price`                =       '".$Clients_pricing['pricing_supply_price']."',
				`pricing_supply_percent`              =       '".$Clients_pricing['pricing_supply_percent']."',
				`pricing_excuse_price`                =       '".$Clients_pricing['pricing_excuse_price']."',
				`pricing_excuse_percent`              =       '".$Clients_pricing['pricing_excuse_percent']."',
				`pricing_excuse_active`               =       '".$Clients_pricing['pricing_excuse_active']."',
				`pricing_rate_percent`                =       '".$Clients_pricing['pricing_rate_percent']."',
				`pricing_rate_type`                   =       '".$Clients_pricing['pricing_rate_type']."',
				`pricing_client_bonus`                =       '".$Clients_pricing['pricing_client_bonus']."',
				`pricing_client_bonus_percent`        =       '".$Clients_pricing['pricing_client_bonus_percent']."',
				`pricing_client_bonus_amount`         =       '".$Clients_pricing['pricing_client_bonus_amount']."',
				`pricing_supply_bonus`                =       '".$Clients_pricing['pricing_supply_bonus']."',
				`pricing_supply_bonus_percent`        =       '".$Clients_pricing['pricing_supply_bonus_percent']."',
				`pricing_supply_bonus_amount`         =       '".$Clients_pricing['pricing_supply_bonus_amount']."'
		  WHERE `pricing_sn`    	            = 	    '".$Clients_pricing['pricing_sn']."' LIMIT 1 ");
		return 1;
		
	}
	
	function addClients_pricing($Clients_pricing)
	{
		foreach($Clients_pricing['pricing_product_rate'] as $rId => $r)
		{
			
			$pricing_product_rate              = intval($r);
			$pricing_start_date                = sanitize($Clients_pricing['pricing_start_date'][$rId]);
			$pricing_end_date                  = sanitize($Clients_pricing['pricing_end_date'][$rId]);
			$pricing_selling_price             = sanitize($Clients_pricing['pricing_selling_price'][$rId]);
			$pricing_supply_percent            = floatval($Clients_pricing['pricing_supply_percent'][$rId]);
			$pricing_supply_price              = sanitize($Clients_pricing['pricing_supply_price'][$rId]);
			$pricing_excuse_active             = sanitize($Clients_pricing['pricing_excuse_active'][$rId]);
			if($pricing_excuse_active == "off")
			{
				$pricing_excuse_price              =  0;
				$pricing_excuse_percent            =  0;
			}else{
				$pricing_excuse_price              =  sanitize($Clients_pricing['pricing_excuse_price'][$rId]);
				$pricing_excuse_percent            =  floatval($Clients_pricing['pricing_excuse_percent'][$rId]);
			}
			$pricing_rate_type                     = sanitize($Clients_pricing['pricing_rate_type'][$rId]);
			if($pricing_rate_type == "not")
			{
				$pricing_rate_percent              =  0;
			}else{
				$pricing_rate_percent              = floatval($Clients_pricing['pricing_rate_percent'][$rId]);
			}
			
			$pricing_client_bonus              = sanitize($Clients_pricing['pricing_client_bonus'][$rId]);
			$pricing_client_bonus_percent      = sanitize($Clients_pricing['pricing_client_bonus_percent'][$rId]);
			$pricing_client_bonus_amount       = sanitize($Clients_pricing['pricing_client_bonus_amount'][$rId]);
			$pricing_supply_bonus              = sanitize($Clients_pricing['pricing_supply_bonus'][$rId]);
			$pricing_supply_bonus_percent      = sanitize($Clients_pricing['pricing_supply_bonus_percent'][$rId]);
			$pricing_supply_bonus_amount       = sanitize($Clients_pricing['pricing_supply_bonus_amount'][$rId]);
			
			if($pricing_supply_percent > 0 )
			{
				$pricing_supply_price = $pricing_selling_price - ($pricing_selling_price * ($pricing_supply_percent/100));
			}else
			{
			    $pricing_supply_percent = (($pricing_selling_price - $pricing_supply_price)/$pricing_selling_price)*100;
			}
			
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `pricing_product_rate` = '".$pricing_product_rate."' ORDER BY `pricing_sn` DESC LIMIT 1 ");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal > 0)
			{
            	$sitegroup = $GLOBALS['db']->fetchitem($query);
//				if($sitegroup['pricing_end_date'] == '0000-00-00')
//				{
					$GLOBALS['db']->query("UPDATE `".$this->tableName."` SET
					  `pricing_end_date` = NOW()
					WHERE  `pricing_sn`=  '".$sitegroup['pricing_sn']."'");
//				}
			}
			
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`pricing_sn`, `pricing_product_rate`, `pricing_start_date`, `pricing_end_date`, 
			`pricing_selling_price`, `pricing_supply_price`, `pricing_supply_percent`, `pricing_excuse_active`,
			`pricing_excuse_price`, `pricing_excuse_percent`, `pricing_rate_percent`, `pricing_rate_type`,
			`pricing_client_bonus`, `pricing_client_bonus_percent`, `pricing_client_bonus_amount`,
			`pricing_supply_bonus`, `pricing_supply_bonus_percent`, `pricing_supply_bonus_amount`, `pricing_status`) 
			VALUES ( NULL ,'".$pricing_product_rate."','".$pricing_start_date."','".$pricing_end_date."'
			,'".$pricing_selling_price."','".$pricing_supply_price."','".$pricing_supply_percent."','".$pricing_excuse_active."'
			,'".$pricing_excuse_price."','".$pricing_excuse_percent."','".$pricing_rate_percent."','".$pricing_rate_type."'
			,'".$pricing_client_bonus."','".$pricing_client_bonus_percent."','".$pricing_client_bonus_amount."'
			,'".$pricing_supply_bonus."','".$pricing_supply_bonus_percent."','".$pricing_supply_bonus_amount."'
			,1)");
		}
			return 1;

		
	}
	
	function deleteClients_pricing($id)
	{
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
			`pricing_status`		 =	'0'
			WHERE `pricing_sn` 		 = 	'".$id."' LIMIT 1 ");
		return 1;
	}
	
	function add_clients_collectible($_collectible)
	{
		$GLOBALS['db']->query("INSERT INTO `clients_collectible`
		(`collectible_sn`, `collectible_client_id`, `collectible_date`, `collectible_type`, `collectible_value`, `collectible_cheque_date`, `collectible_cheque_number`, `collectible_bank_id`, `collectible_account_type`, `collectible_account_id`, `collectible_status`) 
		VALUES
		(NULL,'".$_collectible['collectible_client_id']."','".$_collectible['collectible_date']."','".$_collectible['collectible_type']."','".$_collectible['collectible_value']."','".$_collectible['collectible_cheque_date']."','".$_collectible['collectible_cheque_number']."','".$_collectible['collectible_bank_id']."','".$_collectible['collectible_account_type']."','".$_collectible['collectible_account_id']."',1)");
		$collect_id =$GLOBALS['db']->fetchLastInsertId();
		
		$finance = $GLOBALS['db']->query("SELECT * FROM `clients_finance` WHERE `clients_finance_client_id` = '".$_collectible['collectible_client_id']."'  LIMIT 1 ");
        $financeTotal = $GLOBALS['db']->resultcount();
        if($financeTotal > 0)
        {
			
            $sitefinance = $GLOBALS['db']->fetchitem($query);
			$new = $sitefinance['clients_finance_credit'] + $_collectible['collectible_value'];
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `clients_finance` SET
			`clients_finance_credit`		 =	'".$new."'
			WHERE `clients_finance_sn` 		 = 	'".$sitefinance['clients_finance_sn']."' LIMIT 1 ");
		}else{
			$GLOBALS['db']->query("INSERT INTO `clients_finance`
			(`clients_finance_sn`, `clients_finance_client_id`, `clients_finance_credit`, `clients_status`)
			VALUES
			(NULL , '".$_collectible['collectible_client_id']."' , '".$_collectible['collectible_value']."',1)
			");
		}
		
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
			$new = $sitebank['banks_finance_credit'] + $_collectible['collectible_value'];
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `setiings_banks_finance` SET
			`banks_finance_credit`		 =	'".$new."'
			WHERE `banks_finance_sn` 		 = 	'".$sitebank['banks_finance_sn']."' LIMIT 1 ");
		}else{
			
			if($_collectible['collectible_account_type'] == 'save')
			{
				$bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_saving` WHERE `banks_saving_bank_id` = '".$_collectible['collectible_bank_id']."' LIMIT 1 ");
				$Total = $GLOBALS['db']->resultcount();
				if($Total > 0)
				{
					$account = $GLOBALS['db']->fetchitem($query);
					$id = $account['banks_saving_sn'];
					$open = $account['banks_saving_open_balance'];
				}
			
			}elseif($_collectible['collectible_account_type'] == 'current'){
				$bankfinance = $GLOBALS['db']->query("SELECT * FROM `settings_banks_current` WHERE `banks_current_bank_id` = '".$_collectible['collectible_bank_id']."' LIMIT 1 ");
				$Total = $GLOBALS['db']->resultcount();
				if($Total > 0)
				{
					$account = $GLOBALS['db']->fetchitem($query);
					$id = $account['banks_current_sn'];
					$open = $account['banks_current_opening_balance'];
				}
			}
			
			
			$GLOBALS['db']->query("INSERT INTO `setiings_banks_finance`
			(`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`,`banks_finance_open_balance`,`banks_finance_credit`, `banks_finance_status`)
			VALUES
			(NULL , '".$_collectible['collectible_bank_id']."' , '".$_collectible['collectible_account_type']."', '".$id."', '".$open."', '".$_collectible['collectible_value']."',1)
			");
		}
		if($_collectible['collectible_cheque_date'])
		{
			
			$reminders_remember_date  = date('Y-m-d',strtotime('-7days',strtotime($_collectible['collectible_cheque_date'])));
			$GLOBALS['db']->query("INSERT INTO `reminders`
			(`reminders_sn`, `reminders_type`, `reminders_type_id`, `reminders_start_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_status`) 
			VALUES
			(NULL ,'clients_collectible','".$collect_id."','".$_collectible['collectible_cheque_date']."','day','7','".$reminders_remember_date."','".$reminders_remember_date."',1)");
		}
		
		return 1;
	
	}



}
?>

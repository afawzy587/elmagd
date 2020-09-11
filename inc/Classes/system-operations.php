<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemOperations
{
	var $tableName 	= "operations";

	function getOperations_sum($q)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `operations_status` != '0' AND `operations_code` = '".$q."' ORDER BY `operations_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $operation = $GLOBALS['db']->fetchlist();
			$product =[];
			foreach($operation as $oId => $o){
				$query_rate = $GLOBALS['db']->query("SELECT * FROM  `operations_rates` WHERE `rates_operation_id` = '".$o['operations_sn']."' ORDER BY `rates_sn`  DESC ");
				$product_total = $GLOBALS['db']->resultcount();
				if($product_total > 0)
				{
					$operation[$oId]['product'] = $GLOBALS['db']->fetchlist();
				}
			}
			return $operation; 
        }else{return null;}
	}

	function getTotalOperations($q)
	{
		if($q != "")
		{
			$search = "AND `operations_code` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `operations_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getOperationsInformation($operations_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `operations_sn` = '".$operations_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);

            return array(
                "operations_sn"			                            => 		 $sitegroup['operations_sn'],
                "operations_receipt"			                    => 		 $sitegroup['operations_receipt'],
                "operations_code"                                   =>          $sitegroup['operations_code'],
            	"operations_card_number"                            =>          $sitegroup['operations_card_number'],
            	"operations_date"                                   =>          $sitegroup['operations_date'],
            	"operations_supplier"                               =>          $sitegroup['operations_supplier'],
            	"operations_customer"                               =>          $sitegroup['operations_customer'],
            	"operations_product"                                =>          $sitegroup['operations_product'],
            	"operations_supplier_price"                         =>          $sitegroup['operations_supplier_price'],
            	"operations_customer_price"                         =>          $sitegroup['operations_customer_price'],
				"operations_customer_bonus"                         =>          $sitegroup['operations_customer_bonus'],
            	"operations_supplier_bonus"                         =>          $sitegroup['operations_supplier_bonus'],
            	"operations_quantity"                               =>          $sitegroup['operations_quantity'],
            	"operations_general_discount"                       =>          $sitegroup['operations_general_discount'],
            	"operations_net_quantity"                           =>          $sitegroup['operations_net_quantity'],
            	"operations_card_front_photo"                       =>          $sitegroup['operations_card_front_photo'],
            	"operations_card_back_photo"                        =>          $sitegroup['operations_card_back_photo'],
            	"operations_status"                                 =>          $sitegroup['operations_status']
            );
        }else{return null;}
	}

	function setOperationsInformation($Operations)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `operations_name` LIKE '".$Operations['operations_name']."' AND `operations_sn` !='".$Operations['operations_sn']."' AND operations_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`operations_name`                  =       '".$Operations['operations_name']."',
					`operations_description`           =       '".$Operations['operations_description']."'
			  WHERE `operations_sn`    	            = 	    '".$Operations['operations_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addOperations($Operations)
	{
		$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
		(`operations_sn`, `operations_receipt`, `operations_code`, `operations_card_number`,`operations_date`, `operations_supplier`, `operations_customer`, `operations_product`,	`operations_supplier_price`, `operations_customer_price`, `operations_quantity`, `operations_general_discount`,`operations_net_quantity`, `operations_card_front_photo`, `operations_card_back_photo`, `operations_status`)
		VALUES
		( NULL ,'".$Operations['operations_receipt']."','".$Operations['operations_code']."','".$Operations['operations_card_number']."','".$Operations['operations_date']."' ,'".$Operations['operations_supplier']."','".$Operations['operations_customer']."','".$Operations['operations_product']."','".$Operations['operations_supplier_price']."' ,'".$Operations['operations_customer_price']."','".$Operations['operations_quantity']."','".$Operations['operations_general_discount']."','".$Operations['operations_net_quantity']."' ,'".$Operations['operations_card_front_photo']."','".$Operations['operations_card_back_photo']."',1)");
		
		$opertion_id = $GLOBALS['db']->fetchLastInsertId();
		
		if($opertion_id > 0)
		{
			$finance = $GLOBALS['db']->query("SELECT * FROM `clients_finance` WHERE `clients_finance_client_id` = '".$Operations['operations_customer']."'  LIMIT 1 ");
        	$financeTotal = $GLOBALS['db']->resultcount();
			if($financeTotal > 0)
			{

				$sitefinance = $GLOBALS['db']->fetchitem($query);
				$new = $sitefinance['clients_finance_credit'] - $Operations['operations_customer_price'];
				$GLOBALS['db']->query("UPDATE LOW_PRIORITY `clients_finance` SET
				`clients_finance_credit`		 =	'".$new."'
				WHERE `clients_finance_sn` 		 = 	'".$sitefinance['clients_finance_sn']."' LIMIT 1 ");
			}else{
				$new  = - $Operations['operations_customer_price'];
				$GLOBALS['db']->query("INSERT INTO `clients_finance`
				(`clients_finance_sn`, `clients_finance_client_id`, `clients_finance_credit`, `clients_status`)
				VALUES
				(NULL , '".$Operations['operations_customer']."' , '".$new."',1)
				");
			}
			foreach($Operations['rates_product_rate_id'] as $oId => $o)
			{
				$rates_product_rate_id = intval($o);
				$rates_supplier_discount_percentage     = sanitize($Operations['rates_supplier_discount_percentage'][$oId]);
				$rates_supplier_discount_value          = sanitize($Operations['rates_supplier_discount_value'][$oId]);
				$rates_product_rate_percentage          = sanitize($Operations['rates_product_rate_percentage'][$oId]);
				$rates_product_rate_discount_percentage = sanitize($Operations['rates_product_rate_discount_percentage'][$oId]);
				$rates_product_rate_excuse_percentage   = sanitize($Operations['rates_product_rate_excuse_percentage'][$oId]);
				$rates_product_rate_excuse_price        = sanitize($Operations['rates_product_rate_excuse_price'][$oId]);
				$rates_product_rate_quantity            = sanitize($Operations['rates_product_rate_quantity'][$oId]);
				$rates_product_rate_excuse_quantity     = sanitize($Operations['rates_product_rate_excuse_quantity'][$oId]);
				$rates_product_rate_supply_price        = sanitize($Operations['rates_product_rate_supply_price'][$oId]);
				$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO  `operations_rates`
				(`rates_sn`, `rates_operation_id`, `rates_product_rate_id`, `rates_supplier_discount_percentage`, `rates_supplier_discount_value`, `rates_product_rate_percentage`, `rates_product_rate_discount_percentage`, `rates_product_rate_excuse_percentage`, `rates_product_rate_excuse_price`,`rates_product_rate_supply_price`, `rates_product_rate_quantity`, `rates_product_rate_excuse_quantity`)
				VALUES
				(NULL,'".$opertion_id."','".$rates_product_rate_id."','".$rates_supplier_discount_percentage."','".$rates_supplier_discount_value."','".$rates_product_rate_percentage."','".$rates_product_rate_discount_percentage."','".$rates_product_rate_excuse_percentage."','".$rates_product_rate_excuse_price."','".$rates_product_rate_supply_price."','".$rates_product_rate_quantity."','".$rates_product_rate_excuse_quantity."')

				");
			}

			return 1;
		}else{
			return 2;

		}
	}

	
	function get_client_supplier_product($supplier,$client)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `settings_products` p
		LEFT JOIN `settings_clients_products` c ON  c.`clients_products_product_id` = p.`products_sn`
		LEFT JOIN `settings_suppliers_products` s ON  s.`suppliers_products_product_id` = p.`products_sn`
		WHERE c.`clients_products_product_id` =  s.`suppliers_products_product_id` AND s.`suppliers_products_supplier_id` = '".$supplier."'
		AND c.`clients_products_client_id` = '".$client."' AND p.products_status != '0'
		ORDER BY p.`products_sn`  DESC ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}	
	}
	
	
	function get_client_product_rate($Id,$client)
	{

		$query = $GLOBALS['db']->query("
		SELECT DISTINCT * FROM `settings_clients_products_rate` r
		LEFT JOIN `settings_clients_products` c ON c.`clients_products_sn` = r.`clients_products_rate_product_id`
		INNER JOIN `clients_pricing` cp ON ( cp.`pricing_product_rate` = r.`clients_products_rate_sn` AND (cp.`pricing_end_date` IS NULL || cp.`pricing_end_date` > NOW()))
		WHERE `clients_products_product_id` ='".$Id."' AND c.`clients_products_client_id` ='".$client."'
		AND `clients_products_rate_status` !='0' AND cp.`pricing_end_date` != NOW() ORDER BY  cp.`pricing_sn` DESC 
		");
        $queryTotal = $GLOBALS['db']->resultcount();
		
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}


}
?>

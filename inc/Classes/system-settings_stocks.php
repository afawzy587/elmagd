<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_stocks
{
	var $tableName 	= "settings_stocks";

	function getsiteSettings_stocks($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `stocks_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_status` != '0' ".$search." ORDER BY `stocks_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_stocks($addon = "")
	{
		if($q != "")
		{
			$search = "AND `stocks_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `stocks_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_stocksInformation($stocks_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_sn` = '".$stocks_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
			
			$product = $GLOBALS['db']->query("SELECT * FROM `settings_stocks_products` WHERE `stocks_products_stock_id` = '".$sitegroup['stocks_sn']."' ");
			$productTotal = $GLOBALS['db']->resultcount();
			if($productTotal > 0)
			{
				$products = $GLOBALS['db']->fetchlist();
			}
            return array(
                "stocks_sn"			                   => 		  $sitegroup['stocks_sn'],
                "stocks_name"			               => 		  $sitegroup['stocks_name'],
                "stocks_manager_name"                  =>         $sitegroup['stocks_manager_name'],
                "stocks_phone_one"                     =>         $sitegroup['stocks_phone_one'],
                "stocks_phone_two"                     =>         $sitegroup['stocks_phone_two'],
                "stocks_manager_phone"                 =>         $sitegroup['stocks_manager_phone'],
                "stocks_email"                         =>         $sitegroup['stocks_email'],
                "stocks_address"                       =>         $sitegroup['stocks_address'],
                "stocks_products"                      =>         $products,
            	"stocks_status"                        =>         $sitegroup['stocks_status']
            );
        }else{return null;}
	}

	function setSettings_stocksInformation($Settings_stocks)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_name` LIKE '".$Settings_stocks['stocks_name']."' AND `stocks_sn` !='".$Settings_stocks['stocks_sn']."' AND stocks_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_email` = '".$Settings_stocks['stocks_email']."' AND `stocks_sn` !='".$Settings_stocks['stocks_sn']."' AND stocks_status != 0  LIMIT 1 ");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal == 0)
			{
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE (`stocks_phone_one` = '".$Settings_stocks['stocks_phone_one']."' || `stocks_phone_two` = '".$Settings_stocks['stocks_phone_one']."' || `stocks_manager_phone` = '".$Settings_stocks['stocks_phone_one']."'  ) AND `stocks_sn` !='".$Settings_stocks['stocks_sn']."' AND stocks_status != 0  LIMIT 1 ");
				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
					$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE (`stocks_phone_one` = '".$Settings_stocks['stocks_phone_two']."' || `stocks_phone_two` = '".$Settings_stocks['stocks_phone_two']."' || `stocks_manager_phone` = '".$Settings_stocks['stocks_phone_two']."' )  AND `stocks_sn` !='".$Settings_stocks['stocks_sn']."' AND stocks_status != 0  LIMIT 1 ");
					$quTotal = $GLOBALS['db']->resultcount();
					if($quTotal == 0)
					{
						$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE (`stocks_phone_one` = '".$Settings_stocks['stocks_manager_phone']."' || `stocks_phone_two` = '".$Settings_stocks['stocks_manager_phone']."' || `stocks_manager_phone` = '".$Settings_stocks['stocks_manager_phone']."'  ) AND `stocks_sn` !='".$Settings_stocks['stocks_sn']."' AND stocks_status != 0  LIMIT 1 ");
						$quTotal = $GLOBALS['db']->resultcount();
						if($quTotal == 0)
						{
							$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
								`stocks_name`                  =       '".$Settings_stocks['stocks_name']."',
								`stocks_manager_name`          =       '".$Settings_stocks['stocks_manager_name']."',
								`stocks_phone_one`             =       '".$Settings_stocks['stocks_phone_one']."',
								`stocks_phone_two`             =       '".$Settings_stocks['stocks_phone_two']."',
								`stocks_manager_phone`         =       '".$Settings_stocks['stocks_manager_phone']."',
								`stocks_email`                 =       '".$Settings_stocks['stocks_email']."',
								`stocks_address`               =       '".$Settings_stocks['stocks_address']."'
							  WHERE `stocks_sn`    	           = 	    '".$Settings_stocks['stocks_sn']."' LIMIT 1 ");
							foreach($Settings_stocks['product'] as $k => $v)
							{
								 $explod_products = explode("-",$Settings_stocks['stocks_products'][$k]);
								 $stocks_products = intval($explod_products[0]);
								 $stocks_products_id = intval($explod_products[1]);
								if(!in_array($stocks_products_id,$Settings_stocks['check']))
								{
									if($stocks_products_id>0)
									{
										$GLOBALS['db']->query("DELETE LOW_PRIORITY FROM `settings_stocks_products` WHERE `stocks_products_product_id` = '".$stocks_products_id."' AND `stocks_products_sn` = '".$stocks_products."' LIMIT 1 ");
									}
								}
									if(in_array($v,$Settings_stocks['check']))
									{
										$product_id = intval($v);
										$rate_one = sanitize($Settings_stocks['stocks_products_rate_one'][$k]);
										$rate_two = sanitize($Settings_stocks['stocks_products_rate_two'][$k]);
										$rate_three = sanitize($Settings_stocks['stocks_products_rate_three'][$k]);
										$rate_four = sanitize($Settings_stocks['stocks_products_rate_four'][$k]);
										$rate_five = sanitize($Settings_stocks['stocks_products_rate_five'][$k]);
										$rate_sex  = sanitize($Settings_stocks['stocks_products_rate_sex'][$k]);


											if($stocks_products > 0)
											{
												$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_stocks_products` SET
													`stocks_products_rate_one`                 =       '".$rate_one."',
													`stocks_products_rate_two`                 =       '".$rate_two."',
													`stocks_products_rate_three`               =       '".$rate_three."',
													`stocks_products_rate_four`                =       '".$rate_four."',
													`stocks_products_rate_five`                =       '".$rate_five."',
													`stocks_products_rate_sex`                 =       '".$rate_sex."'
												  WHERE `stocks_products_sn`    	                       = 	    '".$stocks_products."' LIMIT 1 ");
											}else
											{
												$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_stocks_products`
												(`stocks_products_sn`, `stocks_products_stock_id`, `stocks_products_product_id`, `stocks_products_rate_one`, `stocks_products_rate_two`, `stocks_products_rate_three`, `stocks_products_rate_four`, `stocks_products_rate_five`, `stocks_products_rate_sex`)
												VALUES( NULL ,'".$Settings_stocks['stocks_sn']."','".$product_id."','".$rate_one."','".$rate_two."','".$rate_three."','".$rate_four."','".$rate_five."','".$rate_sex."')");
											}
										}
							}
							 return 1;
						}else{
							return 'manager_phone';
						}

					}else{
						return 'phone_two';
					}
				}else{
					return 'phone_one';
				}

			}else{
				return 'stocks_email';
			}
		}else{
			return 'stocks_name';

		}
	}
	
	function addSettings_stocks($Settings_stocks)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_name` LIKE '".$Settings_stocks['stocks_name']."' AND stocks_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_email` = '".$Settings_stocks['stocks_email']."' AND stocks_status != 0  LIMIT 1 ");
			$queryTotal = $GLOBALS['db']->resultcount();
			if($queryTotal == 0)
			{
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_phone_one` LIKE '".$Settings_stocks['stocks_phone_one']."' || `stocks_phone_two` LIKE '".$Settings_stocks['stocks_phone_one']."' || `stocks_manager_phone` LIKE '".$Settings_stocks['stocks_phone_one']."' AND stocks_status != 0  LIMIT 1 ");
				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
					$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_phone_one` LIKE '".$Settings_stocks['stocks_phone_two']."' || `stocks_phone_two` LIKE '".$Settings_stocks['stocks_phone_two']."' || `stocks_manager_phone` LIKE '".$Settings_stocks['stocks_phone_two']."' AND stocks_status != 0  LIMIT 1 ");
					$quTotal = $GLOBALS['db']->resultcount();
					if($quTotal == 0)
					{
						$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `stocks_phone_one` LIKE '".$Settings_stocks['stocks_manager_phone']."' || `stocks_phone_two` LIKE '".$Settings_stocks['stocks_manager_phone']."' || `stocks_manager_phone` LIKE '".$Settings_stocks['stocks_manager_phone']."' AND stocks_status != 0  LIMIT 1 ");
						$quTotal = $GLOBALS['db']->resultcount();
						if($quTotal == 0)
						{
							$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
							(`stocks_sn`, `stocks_name`, `stocks_manager_name`, `stocks_phone_one`, `stocks_phone_two`, `stocks_email`, `stocks_manager_phone`,`stocks_address`, `stocks_status`) 
							VALUES ( NULL ,'".$Settings_stocks['stocks_name']."','".$Settings_stocks['stocks_manager_name']."','".$Settings_stocks['stocks_phone_one']."','".$Settings_stocks['stocks_phone_two']."','".$Settings_stocks['stocks_email']."','".$Settings_stocks['stocks_manager_phone']."','".$Settings_stocks['stocks_address']."',1)");
							 $stock_id = $GLOBALS['db']->fetchLastInsertId();
							foreach($Settings_stocks['product'] as $k => $v)
							{
								if(in_array($v,$Settings_stocks['check']))
								{
									
									$product_id = intval($v);
									$rate_one = sanitize($Settings_stocks['stocks_products_rate_one'][$k]);
									$rate_two = sanitize($Settings_stocks['stocks_products_rate_two'][$k]);
									$rate_three = sanitize($Settings_stocks['stocks_products_rate_three'][$k]);
									$rate_four = sanitize($Settings_stocks['stocks_products_rate_four'][$k]);
									$rate_five = sanitize($Settings_stocks['stocks_products_rate_five'][$k]);
									$rate_sex  = sanitize($Settings_stocks['stocks_products_rate_sex'][$k]);
									$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_stocks_products`
									(`stocks_products_sn`, `stocks_products_stock_id`, `stocks_products_product_id`, `stocks_products_rate_one`, `stocks_products_rate_two`, `stocks_products_rate_three`, `stocks_products_rate_four`, `stocks_products_rate_five`, `stocks_products_rate_sex`)
									VALUES( NULL ,'".$stock_id."','".$product_id."','".$rate_one."','".$rate_two."','".$rate_three."','".$rate_four."','".$rate_five."','".$rate_sex."')");
								}
							}
							 return 1;
						}else{
							return 'manager_phone';
						}

					}else{
						return 'phone_two';
					}
				}else{
					return 'phone_one';
				}

			}else{
				return 'stocks_email';
			}
		}else{
			return 'stocks_name';

		}
	}



}
?>

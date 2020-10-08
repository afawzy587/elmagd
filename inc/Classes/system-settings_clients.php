<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_clients
{
	var $tableName 	= "settings_clients";

	function getsiteSettings_clients($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `clients_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_status` != '0' ".$search." ORDER BY `clients_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_clients($addon = "")
	{
		if($q != "")
		{
			$search = "AND `clients_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `clients_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_clientsInformation($clients_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_sn` = '".$clients_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
			
			$product = $GLOBALS['db']->query("SELECT * FROM `settings_clients_products` WHERE `clients_products_client_id` = '".$sitegroup['clients_sn']."' ");
			$productTotal = $GLOBALS['db']->resultcount();
			if($productTotal > 0)
			{
				$products = $GLOBALS['db']->fetchlist();
				foreach($products as $pId => $p)
				{
					$rate = $GLOBALS['db']->query("SELECT * FROM `settings_clients_products_rate` WHERE `clients_products_rate_product_id` = '".$p['clients_products_sn']."' AND `clients_products_rate_status` != '0'");
					$rateTotal = $GLOBALS['db']->resultcount();
					if($rateTotal > 0)
					{
						$products[$pId]['rate'] = $GLOBALS['db']->fetchlist();
					}
				}
			}
			
			$payment = $GLOBALS['db']->query("SELECT * FROM `settings_clients_payments` WHERE `clients_payments_client_id` = '".$sitegroup['clients_sn']."' ");
			$paymentTotal = $GLOBALS['db']->resultcount();
			if($paymentTotal > 0)
			{
				$payments = $GLOBALS['db']->fetchlist();
			}
            return array(
                "clients_sn"			                => 	   	   $sitegroup['clients_sn'],
                "clients_name"			                => 		   $sitegroup['clients_name'],
                "clients_manager_name"                  =>         $sitegroup['clients_manager_name'],
                "clients_manager_email"                 =>         $sitegroup['clients_manager_email'],
                "clients_phone_one"                     =>         $sitegroup['clients_phone_one'],
                "clients_phone_two"                     =>         $sitegroup['clients_phone_two'],
                "clients_manager_phone"                 =>         $sitegroup['clients_manager_phone'],
                "clients_email"                         =>         $sitegroup['clients_email'],
                "clients_address"                       =>         $sitegroup['clients_address'],
                "clients_commercial_register"           =>         $sitegroup['clients_commercial_register'],
                "clients_tex_card"                      =>         $sitegroup['clients_tex_card'],
                "clients_products"                      =>         $products,
                "clients_payments"                      =>         $payments,
            	"clients_status"                        =>         $sitegroup['clients_status']
            );
        }else{return null;}
	}

	function setSettings_clientsInformation($Settings_clients)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_name` = '".$Settings_clients['clients_name']."' AND `clients_sn` !='".$Settings_clients['clients_sn']."' AND clients_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
//			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_email` = '".$Settings_clients['clients_email']."' AND `clients_sn` !='".$Settings_clients['clients_sn']."' AND clients_status != 0  LIMIT 1 ");
//			$queryTotal = $GLOBALS['db']->resultcount();
//			if($queryTotal == 0)
//			{
				
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE
				`clients_manager_phone` = '".$Settings_clients['clients_manager_phone']."' AND `clients_sn` !='".$Settings_clients['clients_sn']."'
				AND clients_status != 0  LIMIT 1 ");
				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
					
//					$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE
//					((`clients_phone_one` = '".$Settings_clients['clients_phone_two']."' || `clients_phone_two` = '".$Settings_clients['clients_phone_two']."' || `clients_manager_phone` = '".$Settings_clients['clients_phone_two']."' )  AND `clients_sn` !='".$Settings_clients['clients_sn']."')
//					|| ((`clients_phone_one` = '".$Settings_clients['clients_phone_two']."' || `clients_manager_phone` = '".$Settings_clients['clients_phone_two']."' )  AND `clients_sn` ='".$Settings_clients['clients_sn']."')
//					AND clients_status != 0  LIMIT 1 ");
//					$quTotal = $GLOBALS['db']->resultcount();
//					if($quTotal == 0)
//					{
//						$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE
//						((`clients_phone_one` = '".$Settings_clients['clients_manager_phone']."' || `clients_phone_two` = '".$Settings_clients['clients_manager_phone']."' || `clients_manager_phone` = '".$Settings_clients['clients_manager_phone']."'  ) AND `clients_sn` !='".$Settings_clients['clients_sn']."')
//						|| ((`clients_phone_one` = '".$Settings_clients['clients_manager_phone']."' || `clients_phone_two` = '".$Settings_clients['clients_manager_phone']."' ) AND `clients_sn` ='".$Settings_clients['clients_sn']."')
//						AND clients_status != 0  LIMIT 1 ");
//						$quTotal = $GLOBALS['db']->resultcount();
//						if($quTotal == 0)
//						{
							if($Settings_clients['clients_commercial_register'] != "")
							{
								$clients_commercial_register = "`clients_commercial_register`='".$Settings_clients['clients_commercial_register']."',";
							}else
							{
								$clients_commercial_register = "";
							}

							if($Settings_clients['clients_tex_card'] != "")
							{
								$clients_tex_card = "`clients_tex_card`='".$Settings_clients['clients_tex_card']."',";
							}else
							{
								$clients_tex_card = "";
							}
							$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
								`clients_name`                  =       '".$Settings_clients['clients_name']."',".$clients_tex_card."
								`clients_manager_name`          =       '".$Settings_clients['clients_manager_name']."',".$clients_commercial_register."
								`clients_manager_email`         =       '".$Settings_clients['clients_manager_email']."',
								`clients_phone_one`             =       '".$Settings_clients['clients_phone_one']."',
								`clients_phone_two`             =       '".$Settings_clients['clients_phone_two']."',
								`clients_manager_phone`         =       '".$Settings_clients['clients_manager_phone']."',
								`clients_email`                 =       '".$Settings_clients['clients_email']."',
								`clients_address`               =       '".$Settings_clients['clients_address']."'
							  WHERE `clients_sn`    	           = 	    '".$Settings_clients['clients_sn']."' LIMIT 1 ");
							foreach($Settings_clients['product'] as $k => $v)
							{
								$explod_products = explode("-",$Settings_clients['clients_products'][$k]);
							    $clients_products = intval($explod_products[0]);
								$clients_products_id = intval($explod_products[1]);
								if(!in_array($clients_products_id,$Settings_clients['check']))
								{
									if($clients_products_id>0)
									{
										$GLOBALS['db']->query("DELETE LOW_PRIORITY FROM `settings_clients_products` WHERE `clients_products_product_id` = '".$clients_products_id."' AND `clients_products_sn` = '".$clients_products."' LIMIT 1 ");
									}
								}
								if(is_array($Settings_clients['check']))
								{
									if(in_array($v,$Settings_clients['check']))
									{
										$product_id = intval($v);
										if($clients_products > 0)
										{
											for($i=0 ; $i < 6 ;$i++)
											{


												$rate_name  = sanitize($Settings_clients['clients_products_rate_'.$i.''][$k]);
												$rate_sn  = sanitize($Settings_clients['clients_products_rate_sn_'.$i.''][$k]);

												if($rate_name != "")
												{
													if($rate_sn > 0)
													{
														if($rate_name !="")
														{
															$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_clients_products_rate` SET
															  `clients_products_rate_name`               =       '".$rate_name."'
															WHERE `clients_products_rate_sn`    	    = 	    '".$rate_sn."' LIMIT 1 ");
														}
													}else{
														$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_products_rate`
														(`clients_products_rate_sn`, `clients_products_rate_product_id`, `clients_products_rate_name`, `clients_products_rate_status`)
														VALUES( NULL ,'".$clients_products."','".$rate_name."',1)");
													}

											  }else{
												if($rate_sn > 0)
												{
													$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_clients_products_rate` SET
															  `clients_products_rate_status`               =       '0'
															WHERE `clients_products_rate_sn`    	    = 	    '".$rate_sn."' LIMIT 1 ");
	//												$GLOBALS['db']->query("DELETE LOW_PRIORITY FROM `settings_clients_products_rate` WHERE `clients_products_rate_sn` = '".$rate_sn."'  LIMIT 1 ");
												}
											 }
											}
										}else
										{
											$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_products`
											(`clients_products_sn`, `clients_products_client_id`, `clients_products_product_id`)
											VALUES( NULL ,'".$Settings_clients['clients_sn']."','".$product_id."')");
											$rate_id = $GLOBALS['db']->fetchLastInsertId();
											for($i=0 ; $i < 6 ;$i++)
											{
												$rate_name  = sanitize($Settings_clients['clients_products_rate_'.$i.''][$k]);
												if($rate_name !== "")
												{
													$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_products_rate`
													(`clients_products_rate_sn`, `clients_products_rate_product_id`, `clients_products_rate_name`, `clients_products_rate_status`)
													VALUES( NULL ,'".$rate_id."','".$rate_name."',1)");
												}
											}
										}
									}
								}
							}
							
							foreach($Settings_clients['clients_payments_sn'] as $kY => $pay)
							{
								$payment_id       = intval($pay);
								$payments_days    = sanitize($Settings_clients['clients_payments_days'][$kY]);
								$payments_percent = sanitize($Settings_clients['clients_payments_percent'][$kY]);
						
								$GLOBALS['db']->query("UPDATE LOW_PRIORITY `settings_clients_payments` SET
									`clients_payments_days`                 =       '".$payments_days."',
									`clients_payments_percent`              =       '".$payments_percent."'
								  WHERE `clients_payments_sn`    	        = 	    '".$payment_id."' LIMIT 1 ");
							}
							 return 1;
//						}else{
//							return 'manager_phone';
//						}
//
//					}else{
//						return 'phone_two';
//					}
				}else{
					return 'phone_one';
				}

//			}else{
//				return 'clients_email';
//			}
		}else{
			return 'clients_name';

		}
	}
	
	function addSettings_clients($Settings_clients)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_name` LIKE '".$Settings_clients['clients_name']."' AND clients_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
//			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_email` = '".$Settings_clients['clients_email']."' AND clients_status != 0  LIMIT 1 ");
//			$queryTotal = $GLOBALS['db']->resultcount();
//			if($queryTotal == 0)
//			{
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_manager_phone` = '".$Settings_clients['clients_manager_phone']."'  AND clients_status != 0  LIMIT 1 ");

				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
//					$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_phone_one` LIKE '".$Settings_clients['clients_phone_two']."' || `clients_phone_two` LIKE '".$Settings_clients['clients_phone_two']."' || `clients_manager_phone` LIKE '".$Settings_clients['clients_phone_two']."' AND clients_status != 0  LIMIT 1 ");
//					$quTotal = $GLOBALS['db']->resultcount();
//					if($quTotal == 0)
//					{
//						$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `clients_phone_one` LIKE '".$Settings_clients['clients_manager_phone']."' || `clients_phone_two` LIKE '".$Settings_clients['clients_manager_phone']."' || `clients_manager_phone` LIKE '".$Settings_clients['clients_manager_phone']."' AND clients_status != 0  LIMIT 1 ");
//						$quTotal = $GLOBALS['db']->resultcount();
//						if($quTotal == 0)
//						{
							$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
							(`clients_sn`, `clients_name`, `clients_manager_name`, `clients_manager_email`, `clients_phone_one`, `clients_phone_two`, `clients_email`, `clients_manager_phone`,`clients_address`,`clients_tex_card`,`clients_commercial_register`, `clients_status`) 
							VALUES ( NULL ,'".$Settings_clients['clients_name']."','".$Settings_clients['clients_manager_name']."','".$Settings_clients['clients_manager_email']."','".$Settings_clients['clients_phone_one']."','".$Settings_clients['clients_phone_two']."','".$Settings_clients['clients_email']."','".$Settings_clients['clients_manager_phone']."','".$Settings_clients['clients_address']."','".$Settings_clients['clients_tex_card']."','".$Settings_clients['clients_commercial_register']."',1)");
							 $client_id = $GLOBALS['db']->fetchLastInsertId();
							foreach($Settings_clients['product'] as $k => $v)
							{

								if(is_array($Settings_clients['check']) && in_array($v,$Settings_clients['check']))
								{
									
									$product_id = intval($v);
									$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_products`
									(`clients_products_sn`, `clients_products_client_id`, `clients_products_product_id`)
									VALUES( NULL ,'".$client_id."','".$product_id."')");
									$rate_id = $GLOBALS['db']->fetchLastInsertId();
									for($i=0 ; $i < 6 ;$i++)
									{
										$rate_name  = sanitize($Settings_clients['clients_products_rate_'.$i.''][$k]);
										if($rate_name !== "")
										{
											$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_products_rate`
											(`clients_products_rate_sn`, `clients_products_rate_product_id`, `clients_products_rate_name`, `clients_products_rate_status`)
											VALUES( NULL ,'".$rate_id."','".$rate_name."',1)");
										}
									}
								}
							}
							foreach($Settings_clients['clients_payments_days'] as $k => $d)
							{
								$precent = floatval($Settings_clients['clients_payments_percent'][$k]);
								$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_clients_payments`
									(`clients_payments_sn`, `clients_payments_client_id`, `clients_payments_days`, `clients_payments_percent`)
									VALUES( NULL ,'".$client_id."','".$d."','".$precent."')");
							}
							 return 1;
//						}else{
//							return 'manager_phone';
//						}

//					}else{
//						return 'phone_two';
//					}
				}else{
					return 'phone_one';
				}

//			}else{
//				return 'clients_email';
//			}
		}else{
			return 'clients_name';

		}
	}
	
	
	function get_client_product($Id)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `settings_clients_products` INNER JOIN `settings_products` ON `clients_products_product_id` = `products_sn` 
		WHERE `clients_products_client_id` =  '".$Id."' AND `products_status` != '0'");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}
	
	function get_client_product_rate($Id)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `settings_clients_products_rate` WHERE `clients_products_rate_product_id` ='".$Id."' AND `clients_products_rate_status` !='0'  ORDER BY `clients_products_rate_sn` DESC" );
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}



}
?>

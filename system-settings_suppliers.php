<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_suppliers
{
	var $tableName 	= "settings_suppliers";

	function getsiteSettings_suppliers($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `suppliers_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_status` != '0' ".$search." ORDER BY `suppliers_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_suppliers($addon = "")
	{
		if($q != "")
		{
			$search = "AND `suppliers_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `suppliers_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_suppliersInformation($suppliers_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_sn` = '".$suppliers_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
			
			$product = $GLOBALS['db']->query("SELECT * FROM `settings_suppliers_products` WHERE `suppliers_products_supplier_id` = '".$sitegroup['suppliers_sn']."' ");
			$productTotal = $GLOBALS['db']->resultcount();
			if($productTotal > 0)
			{
				$products = $GLOBALS['db']->fetchlist();
			}
            return array(
                "suppliers_sn"			                  => 		 $sitegroup['suppliers_sn'],
                "suppliers_name"			              => 		 $sitegroup['suppliers_name'],
                "suppliers_phone_one"                     =>         $sitegroup['suppliers_phone_one'],
                "suppliers_phone_two"                     =>         $sitegroup['suppliers_phone_two'],
                "suppliers_photo"                         =>         $sitegroup['suppliers_photo'],
                "suppliers_doc"                           =>         $sitegroup['suppliers_doc'],
                "suppliers_address"                       =>         $sitegroup['suppliers_address'],
                "suppliers_products"                      =>         $products,
            	"suppliers_status"                        =>         $sitegroup['suppliers_status']
            );
        }else{return null;}
	}

	function setSettings_suppliersInformation($Settings_suppliers)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_name` = '".$Settings_suppliers['suppliers_name']."' AND `suppliers_sn` !='".$Settings_suppliers['suppliers_sn']."' AND suppliers_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE (`suppliers_phone_one` = '".$Settings_suppliers['suppliers_phone_one']."' || `suppliers_phone_two` = '".$Settings_suppliers['suppliers_phone_one']."'   ) AND `suppliers_sn` !='".$Settings_suppliers['suppliers_sn']."' AND suppliers_status != 0  LIMIT 1 ");
			$quTotal = $GLOBALS['db']->resultcount();
			if($quTotal == 0)
			{
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE (`suppliers_phone_one` = '".$Settings_suppliers['suppliers_phone_two']."' || `suppliers_phone_two` = '".$Settings_suppliers['suppliers_phone_two']."'  )  AND `suppliers_sn` !='".$Settings_suppliers['suppliers_sn']."' AND suppliers_status != 0  LIMIT 1 ");
				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
						if($Settings_suppliers['suppliers_photo'] != "")
						{
							$suppliers_photo = "`suppliers_photo`='".$Settings_suppliers['suppliers_photo']."',";
						}else
						{
							$suppliers_photo = "";
						}

						if($Settings_suppliers['suppliers_doc'] != "")
						{
							$suppliers_doc = "`suppliers_doc`='".$Settings_suppliers['suppliers_doc']."',";
						}else
						{
							$suppliers_doc = "";
						}
					
						if($Settings_suppliers['suppliers_password'] != "")
						{
							$suppliers_password = "`suppliers_password`='".$Settings_suppliers['suppliers_password']."',";
						}else
						{
							$suppliers_password = "";
						}
					
						
						$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
							`suppliers_name`                  =       '".$Settings_suppliers['suppliers_name']."',".$suppliers_photo."
							`suppliers_phone_one`             =       '".$Settings_suppliers['suppliers_phone_one']."',".$suppliers_doc."
							`suppliers_phone_two`             =       '".$Settings_suppliers['suppliers_phone_two']."',".$suppliers_password."
							`suppliers_address`               =       '".$Settings_suppliers['suppliers_address']."'
						  WHERE `suppliers_sn`    	           = 	    '".$Settings_suppliers['suppliers_sn']."' LIMIT 1 ");
						foreach($Settings_suppliers['product'] as $k => $v)
						{
						     $explod_products = explode("-",$Settings_suppliers['supplier_product'][$k]);
							 $suppliers_products = intval($explod_products[0]);
							 $suppliers_products_id = intval($explod_products[1]);
							if(!in_array($suppliers_products_id,$Settings_suppliers['check']))
							{
								if($suppliers_products_id > 0)
								{
									$GLOBALS['db']->query("DELETE LOW_PRIORITY FROM `settings_suppliers_products` WHERE `suppliers_products_product_id` = '".$suppliers_products_id."' AND `suppliers_products_sn` = '".$suppliers_products."' LIMIT 1 ");
								}
							}
							if(in_array($v,$Settings_suppliers['check']))
							{
									
									 $product_id = intval($v);
									if($suppliers_products == 0 || $suppliers_products == "")
									{
										$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_suppliers_products`
										(`suppliers_products_sn`, `suppliers_products_supplier_id`, `suppliers_products_product_id`)
										VALUES( NULL ,'".$Settings_suppliers['suppliers_sn']."','".$product_id."')");
									}
								}
						}
						 return 1;
				}else{
					return 'phone_two';
				}
			}else{
				return 'phone_one';
			}


		}else{
			return 'suppliers_name';

		}
	}
	
	function addSettings_suppliers($Settings_suppliers)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_name` LIKE '%".$Settings_suppliers['suppliers_name']."%' AND suppliers_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_phone_one` = '".$Settings_suppliers['suppliers_phone_one']."' || `suppliers_phone_two` = '".$Settings_suppliers['suppliers_phone_one']."'  AND suppliers_status != 0  LIMIT 1 ");
			$quTotal = $GLOBALS['db']->resultcount();
			if($quTotal == 0)
			{
				$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `suppliers_phone_one` = '".$Settings_suppliers['suppliers_phone_two']."' || `suppliers_phone_two` = '".$Settings_suppliers['suppliers_phone_two']."'  AND suppliers_status != 0  LIMIT 1 ");
				$quTotal = $GLOBALS['db']->resultcount();
				if($quTotal == 0)
				{
					$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
					(`suppliers_sn`, `suppliers_name`, `suppliers_phone_one`, `suppliers_phone_two`, `suppliers_address`,`suppliers_photo`,`suppliers_doc`, `suppliers_status`) 
					VALUES ( NULL ,'".$Settings_suppliers['suppliers_name']."','".$Settings_suppliers['suppliers_phone_one']."','".$Settings_suppliers['suppliers_phone_two']."','".$Settings_suppliers['suppliers_address']."','".$Settings_suppliers['suppliers_photo']."','".$Settings_suppliers['suppliers_doc']."',1)");
					 $supplier_id = $GLOBALS['db']->fetchLastInsertId();
					foreach($Settings_suppliers['product'] as $k => $v)
					{
						if(in_array($v,$Settings_suppliers['check']))
						{

							$product_id = intval($v);
							$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `settings_suppliers_products`
							(`suppliers_products_sn`, `suppliers_products_supplier_id`, `suppliers_products_product_id`)
							VALUES( NULL ,'".$supplier_id."','".$product_id."')");
						}
					}
					 return 1;
					
				}else{
					return 'phone_two';
				}
			}else{
				return 'phone_one';
			}
		}else{
			return 'suppliers_name';

		}
	}



}
?>

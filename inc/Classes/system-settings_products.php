<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_products
{
	var $tableName 	= "settings_products";

	function getsiteSettings_products($addon = "",$q="")
	{
		if($q != "")
		{
			$search = "AND `products_name` LIKE '%".$q."%' ";
		}else{
			$search = "";
		}
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `products_status` != '0' ".$search." ORDER BY `products_sn`  DESC ".$addon);
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
	}

	function getTotalSettings_products($addon = "")
	{
		if($q != "")
		{
			$search = "AND `products_name` = '".$q."'";
		}else{
			$search = "";
		}
        $query 				= $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `".$this->tableName."` WHERE `products_status` != '0' ".$search);
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
	}

	function getSettings_productsInformation($products_sn)
	{
		
        $query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `products_sn` = '".$products_sn."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
            return array(
                "products_sn"			                 => 		 $sitegroup['products_sn'],
                "products_name"			                 => 		 $sitegroup['products_name'],
                "products_description"                    =>          $sitegroup['products_description'],
            	"products_status"                         =>          $sitegroup['products_status']
            );
        }else{return null;}
	}

	function setSettings_productsInformation($Settings_products)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `products_name` LIKE '".$Settings_products['products_name']."' AND `products_sn` !='".$Settings_products['products_sn']."' AND products_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
					`products_name`                  =       '".$Settings_products['products_name']."',
					`products_description`           =       '".$Settings_products['products_description']."'
			  WHERE `products_sn`    	            = 	    '".$Settings_products['products_sn']."' LIMIT 1 ");
			return 1;

		}else{
			return 2;

		}
	}
	
	function addSettings_products($Settings_products)
	{
		$query = $GLOBALS['db']->query("SELECT * FROM `".$this->tableName."` WHERE `products_name` LIKE '".$Settings_products['products_name']."' AND products_status != 0  LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal == 0)
        {
			$GLOBALS['db']->query("INSERT LOW_PRIORITY INTO `".$this->tableName."`
			(`products_sn`, `products_name`, `products_description`,`products_status`) 
			VALUES ( NULL ,'".$Settings_products['products_name']."','".$Settings_products['products_description']."',1)");
			return 1;

		}else{
			return 2;

		}
	}



}
?>

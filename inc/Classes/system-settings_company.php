<?php 
if(!defined("inside")) exit;
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
class systemSettings_company
{
	var $tableName 	= "settings_companyinfo";

	

	public function setSettings_companyInformation($com)
	{
		if($com['companyinfo_logo'] != "")
		{
			$cominfo_logo = "`companyinfo_logo`='".$com['companyinfo_logo']."',";
		}else
		{
			$cominfo_logo = "";
		}
		
		if($com['companyinfo_document'] != "")
		{
			$cominfo_document = "`companyinfo_document`='".$com['companyinfo_document']."',";
		}else
		{
			$cominfo_document = "";
		}

		
		
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$this->tableName."` SET
			`companyinfo_name`                     ='".$com['companyinfo_name']."',".$cominfo_logo."
			`companyinfo_phone`                    ='".$com['companyinfo_phone']."',".$cominfo_document."
			`companyinfo_opening_balance_safe`     ='".$com['companyinfo_opening_balance_safe']."',
			`companyinfo_opening_balance_cheques`  ='".$com['companyinfo_opening_balance_cheques']."',
			`companyinfo_address`                  ='".$com['companyinfo_address']."'
			WHERE `companyinfo_sn` = '1' LIMIT 1 ");


		return 1;
	}
	
	 // ###################### delete function #####################//
    function delete_data_from_table($table,$id)
	{
	    $col = str_replace("settings_","",$table);
		
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `".$table."` SET
			`".$col."_status`			    =	'0'
			WHERE `".$col."_sn` 		    = 	'".$id."' LIMIT 1 ");
		if($col =='clients')
		{
			$GLOBALS['db']->query("UPDATE LOW_PRIORITY `clients_finance` SET
			`clients_status`			       =	'0'
			WHERE `clients_finance_client_id`  = 	'".$id."' LIMIT 1 ");
		}
		return 1;
	}
	

}
?>

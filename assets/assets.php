<?php
 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	######### Main Security Basic Filter Function ;) #########
	function sanitize( $str , $type = "str" )
	{
		$str = strip_tags($str);
		$str = trim ($str);
		$str = htmlspecialchars ($str, ENT_NOQUOTES);
		$str = addslashes ($str);
		if($type == "area")
		$str = str_replace("\n","<br />",$str);
		return $str;
	}
	function check_date($date)
	{
		 $date1  = strtotime($date);
		 $date2  = strtotime(date("Y-m-d"));
		if($date1 > $date2)
		{
			if(date('m',$date1) == date('m'))
			{
				return 'warning';
			}else{
				return 'safe';
			}
		}else{
			return 'danger';
		}
	}
	function difftime($date)
	{
		 $date1  = strtotime($date);
		 $date2  = strtotime(date("Y-m-d"));
		// Formulate the Difference between two dates

	    $diff = abs($date2 - $date1);
		// To get the year divide the resultant date into
		// total seconds in a year (365*60*60*24)
		$years = floor($diff / (365*60*60*24));

		// To get the month, subtract it with years and
		// divide the resultant date into
		// total seconds in a month (30*60*60*24)
	    $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
		// To get the day, subtract it with years and
		// months and divide the resultant date into
		// total seconds in a days (60*60*24)
		 $days = floor(($diff - (($years * 365*60*60*24) + ($months*30*60*60*24)))/ (60*60*24));
		// To get the hour, subtract it with years,
		// months & seconds and divide the resultant
		// date into total seconds in a hours (60*60)
//		$hours = floor(($diff - (($years * 365*60*60*24) - (($months*30*60*60*24) - ($days*60*60*24)))) / (60*60));
		// To get the minutes, subtract it with years,
		// months, seconds and hours and divide the
		// resultant date into total seconds i.e. 60
//		$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
		// To get the minutes, subtract it with years,
		// months, seconds, hours and minutes
//		$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
		// Print the result


		if($years   > 0 ){$y    = $years.' '.$GLOBALS['lang']['YEAR'] ; }
		if($months  > 0 ){$m    = $months.' '.$GLOBALS['lang']['MONTH'];}
		if($days    > 0 ){$d    = $days.' '.$GLOBALS['lang']['DAY'];}
//		if($hours   > 0 ){$h    = $hours." ساعة ";}
//		if($minutes > 0 ){$i    = $minutes . " دقيقة " ;}
//		if($seconds > 0 ){$s    = $seconds . " ثانية ";}

		if($date2 > $date1){
			$when = $GLOBALS['lang']['SINCE'];
		}elseif(($date2 > $date1)){
			$when = $GLOBALS['lang']['AFTER'];
		}elseif(($date2 == $date1)){
            $when = $GLOBALS['lang']['TO_DAY'];
        }
		return( $when.' '."<span title='.$date.'>" .$y.$m.$d."</span>");
	}
	
    ######### Swapping textarea Content #########
    function br2nl($str)
	{
	    $str = str_replace("<br />","\n",$str);
	    return $str;
	}
	######### Valid Email Check #########
	function checkMail($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
	}
    ////////// Valid phone check ///////////////
    function checkPhone($phone)
    {
        $phone  = str_replace("+2","",$phone);
        if(strlen($phone) == 11 || !is_numeric($phone))
        {
            $sub = substr($phone,0,3);
            $ext = ['010','011','012','015'];
            return ( ! in_array($sub,$ext) ? false : true);
        }elseif(strlen($phone) == 10 || !is_numeric($phone)){
            $pattern = "/^0[1-9]{1}[0-9]{8}$/";
            return ( !preg_match($pattern, $number) ? false : true);
        }else{
            return false;
        }
        
        
    }
   
	function format_data_base ($date)
	{
	    return  date('Y-m-d', strtotime($date));
	}
    function _date_format ($date)
	{
	    return  date('d/m/Y', strtotime($date));
	}
	function datepiker_format ($date)
	{
	    return  date('m/d/Y', strtotime($date));
	}
    function end_time ($start,$end)
	{
	    return  date('Y-m-d H:m:s', (strtotime($date)+($end*60)));
	}
    function time_format ($time)
	{
	    return  date('g:i A', strtotime($time));
	}
	function day_name ($_date )
	{
		$dayname = date('D', strtotime($_date));
		if($dayname == "Sat")
		{
			$_dayname = $GLOBALS['lang']['SAT']; 
		}elseif($dayname == "Sun")
		{
			$_dayname = $GLOBALS['lang']['SUN']; 
		}elseif($dayname == "Mon")
		{
			$_dayname = $GLOBALS['lang']['MON']; 
		}elseif($dayname == "Tue")
		{
			$_dayname = $GLOBALS['lang']['TUE']; 
		}elseif($dayname == "Wed")
		{
			$_dayname = $GLOBALS['lang']['WED']; 
		}elseif($dayname == "Thu")
		{
			$_dayname = $GLOBALS['lang']['THU']; 
		}elseif($dayname == "Fri")
		{
			$_dayname = $GLOBALS['lang']['FRI']; 
		}
		
		return($_dayname);

	}
//    function crypto_rand_secure($min, $max)
//	{
//		$range = $max - $min;
//		if ($range < 1) return $min; // not so random...
//		$log = ceil(log($range, 2));
//		$bytes = (int) ($log / 8) + 1; // length in bytes
//		$bits = (int) $log + 1; // length in bits
//		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
//		do {
//			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
//			$rnd = $rnd & $filter; // discard irrelevant bits
//		} while ($rnd > $range);
//		return $min + $rnd;
//	}
//    function generateKey($length = 15)
//	{
//		$token 		= "";
//		$key 		= "0123456789";
//		$max 		= strlen($key);
//
//		for ($i=0; $i < $length; $i++) {
//			$token .= $key[crypto_rand_secure(0, $max-1)];
//		}
//		return ($token);
//	}

	function generate_unique_code($length = 15){
//		if(!isset($length) || intval($length) <= 8 ){
//		  $length = 32;
//		}
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($length));
		}
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
		}
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($length));
		}
	}

    function sendemail($_mail,$_id)
    {
        $recovery_code 	= generate_unique_code(10);
        include_once("./inc/Classes/send_email.php");
        $send    = new sendmail();

        $link    = "https://".$_SERVER['SERVER_NAME']."/fleet"."/rest_password.php?code=".$recovery_code;

        $_link   ='link:<a href='.$link.'>'.$GLOBALS['lang']['FOR_RECOVERY_PASSWORD'].'</a>';

        $subject = $GLOBALS['lang']['REST_PASSWORD_MESSAGE'];

        $done    = $send->email($_mail,$_link,$subject);
        if($done == 1)
        {
            $expired_date   = date('Y-m-d H:i:s', strtotime('+1 day', time()+360));
            $GLOBALS['db']->query(
                "UPDATE `users` SET
                `users_recovery_code`    ='".$recovery_code."',
                `users_recovery_expired` ='".$expired_date."'
                WHERE `users_sn`='".$_id."'
            ");
            return $done;
        }else{
            return 0;
        }
        
        
    }
    function get_data($table,$return,$where,$id)
    {
		$col = str_replace("settings_","",$table);
        $query = $GLOBALS['db']->query(" SELECT * FROM `".$table."` WHERE `".$where."` = '".$id."'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return ($_data[$return]);
		}
		else
		{
			return ($GLOBALS['lang']['NOT_FOUND']);
		}
    }

	function alert_message($type,$head)
	{
		echo '<div class="alert alert-'.$type.' alert-dismissible col-md-5">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>'.$head.'!</strong>
					 </div>';
	}


	function get_client_credit($id)
	{
		$query = $GLOBALS['db']->query(" SELECT `clients_finance_credit` FROM `clients_finance` WHERE `clients_finance_client_id` = '".$id."' AND `clients_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return (number_format($_data['clients_finance_credit']));
		}
		else
		{
			return (0);
		}
	}

	function get_last_reseipt()
	{
		$query = $GLOBALS['db']->query(" SELECT `operations_receipt` FROM `operations` ORDER BY  `operations_receipt` DESC  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return ($_data['operations_receipt']+1);
		}
		else
		{
			return (1);
		}
	}

	function get_client_product($id)
	{
		$query = $GLOBALS['db']->query(" SELECT * FROM `settings_products` p  LEFT JOIN `settings_clients_products` c  ON p.`products_sn` = c.`clients_products_product_id` WHERE c.`clients_products_sn` = '".$id."' AND p.`products_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return ($_data['products_name']);
		}
	}

   	function group_check($id,$value)
	{
		echo '<div class="col-md-4">
				<input class="customized-checkbox" id="'.$id.'" type="checkbox" name="'.$id.'" value="1"';if($value == 1){echo 'checked';} echo'>
				<label class="customized-checkbox-label" for="'.$id.'">'.$GLOBALS['lang']['GROUP_'.$id].'</label>
			 </div>
		';
	}

    function get_Operation_product($id)
    {
        $GLOBALS['db']->query("SELECT *  FROM `operations_rates` WHERE
		`rates_operation_id` = '".$id."'");
		$queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            return($GLOBALS['db']->fetchlist());
        }else{return null;}
    }

	function get_banks_credit_account($id)
    {
         $query 		    = $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `settings_banks_credit` WHERE `banks_credit_bank_id` ='".$id."' AND  `banks_credit_status` != '0' LIMIT 1");
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
    }
	
	function get_banks_saving_account($id)
    {
         $query 		    = $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `settings_banks_saving` WHERE `banks_saving_bank_id` ='".$id."'  AND  `banks_saving_status` != '0'LIMIT 1");
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
    }

	function get_banks_current_account($id)
    {
         $query 		    = $GLOBALS['db']->query("SELECT COUNT(*) AS `total` FROM `settings_banks_current` WHERE `banks_current_bank_id` ='".$id."' AND  `banks_current_status` != '0' LIMIT 1");
        $queryTotal 		= $GLOBALS['db']->fetchrow();
        $total 				= $queryTotal['total'];
        return ($total);
    }

	function get_supplier_collect_value($id)
	{
		$query = $GLOBALS['db']->query(" SELECT * FROM `suppliers_collectible` WHERE `collectible_sn` =  '".$id."' AND `collectible_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return ($_data['collectible_value']);
		}
	}

	function get_client_collect_value($id)
	{
		$query = $GLOBALS['db']->query(" SELECT * FROM `clients_collectible` WHERE `collectible_sn` =  '".$id."' AND `collectible_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			return ($_data['collectible_value']);
		}
	}

	function get_supplier_return($id)
	{
		$query = $GLOBALS['db']->query(" SELECT * FROM `collect_returns` WHERE `collect_returns_person` ='supplier' AND `collect_id` = '".$id."' AND `collect_returns_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			
			if($_data['collect_returns_insert_in'] == 'safe')
			{
				$insert_in = $GLOBALS['lang']['SETTINGS_C_F_SAFE'];
			}else{
				
				$insert_in = get_data('settings_banks','banks_name','banks_sn',$_data['collect_returns_bank_id']) . ' - ';
				if($_data['collect_returns_account_type'] == 'credit')
				{
					$insert_in .= get_data('settings_banks_credit','banks_credit_name','banks_credit_sn',$_data['collect_returns_account_id']);
				}elseif($_data['collect_returns_account_type'] == 'current'){
					$insert_in .= $GLOBALS['lang']['SETTINGS_BAN_CURRENT'];
				}elseif($_data['collect_returns_account_type'] == 'saving'){
					$insert_in .= $GLOBALS['lang']['SETTINGS_BAN_SAVE'];
				}
			}
			
			
			$data_content  =  $GLOBALS['lang']['IN_ACCONT'].' : '.$insert_in.'<br />';
			$data_content .=  $GLOBALS['lang']['IN_DATE'].' : '._date_format($_data['collect_returns_date']).'<br />';
			
			if($_data['collect_returns_type'] == 'cash')
			{
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_TYPE'].' : '.$GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CASH'].'<br />';
			}else{
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_TYPE'].' : '.$GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CHEQUE'].'<br />';
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'].' : '.$_data['collect_returns_cheque_number'].'<br />';
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'].' : '.$_data['collect_returns_cheque_date'].'<br />';
			}
			return($data_content);
		}
	}
	function get_client_return($id)
	{
		$query = $GLOBALS['db']->query(" SELECT * FROM `collect_returns` WHERE `collect_returns_person` ='client' AND `collect_id` = '".$id."' AND `collect_returns_status` != '0'  LIMIT 1");
		$queryCount = $GLOBALS['db']->resultcount();
		if($queryCount == 1)
		{
			$_data = $GLOBALS['db']->fetchitem($query);
			
			if($_data['collect_returns_insert_in'] == 'safe')
			{
				$insert_in = $GLOBALS['lang']['SETTINGS_C_F_SAFE'];
			}else{
				
				$insert_in = get_data('settings_banks','banks_name','banks_sn',$_data['collect_returns_bank_id']) . ' - ';
				if($_data['collect_returns_account_type'] == 'credit')
				{
					$insert_in .= get_data('settings_banks_credit','banks_credit_name','banks_credit_sn',$_data['collect_returns_account_id']);
				}elseif($_data['collect_returns_account_type'] == 'current'){
					$insert_in .= $GLOBALS['lang']['SETTINGS_BAN_CURRENT'];
				}elseif($_data['collect_returns_account_type'] == 'saving'){
					$insert_in .= $GLOBALS['lang']['SETTINGS_BAN_SAVE'];
				}
			}
			
			
			$data_content  =  $GLOBALS['lang']['IN_ACCONT'].' : '.$insert_in.'<br />';
			$data_content .=  $GLOBALS['lang']['IN_DATE'].' : '._date_format($_data['collect_returns_date']).'<br />';
			
			if($_data['collect_returns_type'] == 'cash')
			{
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_TYPE'].' : '.$GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CASH'].'<br />';
			}else{
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_TYPE'].' : '.$GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CHEQUE'].'<br />';
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'].' : '.$_data['collect_returns_cheque_number'].'<br />';
				$data_content .=  $GLOBALS['lang']['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'].' : '.$_data['collect_returns_cheque_date'].'<br />';
			}
			return($data_content);
		}
	}

	

	


 





    


	









	

?>

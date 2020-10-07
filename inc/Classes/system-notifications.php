<?php if(!defined("inside")) exit;
if(!isset($_SESSION))
    {
        session_start();
    }
class systemnotifications
{
	var $tableName 	= "reminders";

	function getsitenotifications()
	{
		$date   = date('Y-m-d');
        $query1 = $GLOBALS['db']->query("SELECT * FROM `reminders` WHERE `reminders_notification_date` <= '".$date."' AND `reminders_status` = '1'");
        $unseen_notification = $GLOBALS['db']->resultcount();

		$query2 = $GLOBALS['db']->query("SELECT * FROM `reminders` WHERE `reminders_notification_date` <= '".$date."' AND `reminders_status` != '0'");
        $queryTotal = $GLOBALS['db']->resultcount();
		$reminders = $GLOBALS['db']->fetchlist();
		if(is_array($reminders))
		{
			foreach($reminders as $k => $r)
			{

				$output .='<span class="dropdown-item ';
				if($r['reminders_read'] == 0){ $output .='unread';}
				$output .='">';
				if($r['reminders_type'] == 'deposits')
				{
					$output .= '<a href="./deposits_list.php?id='.$r['reminders_type_id'].'" id='.$r['reminders_sn'].' class="read">
									'.$GLOBALS['lang']['NOT_DEPOSITS_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								';

				}elseif($r['reminders_type'] == 'transfer'){
					$output .= '<a href="./transfers.php?id='.$r['reminders_type_id'].'" id='.$r['reminders_sn'].' class="read">
									'.$GLOBALS['lang']['NOT_TRANSFERS_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								';

				}elseif($r['reminders_type'] == 'safe'){
					$output .= '<a href="#" id='.$r['reminders_sn'].' class="read" >
									'.$GLOBALS['lang']['NOT_SAFES_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								</span>';

				}elseif($r['reminders_type'] == 'expenses'){
					$output .= '<a  href="./expenses.php?id='.$r['reminders_type_id'].'" id='.$r['reminders_sn'].' class="read" >
									'.$GLOBALS['lang']['NOT_SAFES_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								';

				}elseif($r['reminders_type'] == 'clients_pay'){
					$output .= '<a href="#" id='.$r['reminders_sn'].' class="read" >
									'.$GLOBALS['lang']['NOT_CLIENT_PAY'].' ('.number_format($r['title']).') '.$GLOBALS['lang']['NOT_CLIENT_DAY'].' : '._date_format($r['reminders_date']).'
									<div><small>'.$GLOBALS['lang']['OPERATIONS_CLIENT'].'(  '.get_data('settings_clients','clients_name','clients_sn',$r['client_id']).') '.$GLOBALS['lang']['OPERATIONS_PRODUCT'].' : ' .get_data('settings_products','products_name','products_sn',$r['product_id']).' </small></div>
								</a>
								';

				}elseif($r['reminders_type'] == 'defult'){
					$output .= '<a href="#" id='.$r['reminders_sn'].' class="read" >
									'.$GLOBALS['lang']['Reminder'].' : ' .$r['title'].'
									<div><small>'.$GLOBALS['lang']['Reminders_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								';

				}
				$output .= '<a class="delete_reminders tableActions" style="color:red;cursor: pointer;" title="'.$GLOBALS['lang']['Reminders_STOP'].'" id="'.$r['reminders_sn'].'">'.$GLOBALS['lang']['Reminders_STOP'].'</a></span>';
			}
		}else{
					$output = '<span class="">'.$GLOBALS['lang']['NO_NOTIFICATION'].'</span>';
			}
		return(
				array(
					"unseen_notification"   => $unseen_notification,
					"notification"   => $output
				)
			);
	}

	function view_notification()
	{
		$date   = date('Y-m-d');
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `reminders` SET
            `reminders_status`			    = 		2
          WHERE `reminders_notification_date` <= '".$date."' AND  `reminders_status`  = '1' ");
	}
	
	function delete_notification($id)
	{
		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `reminders` SET
            `reminders_status`			    = 		0
          WHERE `reminders_sn` = '".$id."' ");
		return 1;
	}

    function read_notification($id)
	{

		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `reminders` SET
            `reminders_read`			    = 		1
          WHERE `reminders_sn` = '".$id."' ");
	}


}
?>

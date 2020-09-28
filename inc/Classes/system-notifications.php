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

		$query2 = $GLOBALS['db']->query("SELECT * FROM `reminders` WHERE `reminders_notification_date` <= '".$date."' AND `reminders_status` != '3'");
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
								</span>';

				}elseif($r['reminders_type'] == 'transfer'){
					$output .= '<a href="./transfers.php?id='.$r['reminders_type_id'].'" id='.$r['reminders_sn'].' class="read">
									'.$GLOBALS['lang']['NOT_TRANSFERS_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								</span>';

				}elseif($r['reminders_type'] == 'safe'){
					$output .= '<a href="#" id='.$r['reminders_sn'].' class="read" >
									'.$GLOBALS['lang']['NOT_SAFES_MESSAGE'].'
									<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($r['reminders_date']).'</small></div>
								</a>
								</span>';

				}
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

    function read_notification($id)
	{

		$GLOBALS['db']->query("UPDATE LOW_PRIORITY `reminders` SET
            `reminders_read`			    = 		1
          WHERE `reminders_sn` = '".$id."' ");
	}


}
?>

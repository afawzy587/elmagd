<?php if(!defined("inside")) exit;
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

class logs
{
	public function addLog ($typeId, $logData, $who, $id, $update = 0)
	{
		
		$query = $GLOBALS['db']->query("SELECT `users_name` FROM `settings_users` WHERE `users_sn` = '".$id."' LIMIT 1 ");
        $queryTotal = $GLOBALS['db']->resultcount();
        if($queryTotal > 0)
        {
            $sitegroup = $GLOBALS['db']->fetchitem($query);
		}
		if(is_array($logData))
		{
			$Log = "[ ".date("F j, Y, g:i a")." ] LOGS :  User => ".$sitegroup['users_name']." Id =>".$id."  Module =>".$logData['module']."  Page => ".$logData['mode'].PHP_EOL."------------------------------".PHP_EOL;
			if($logData['item'])
			{
				$Log .= " / item:".$logData['item'];
			}
		   file_put_contents('./logs/logs.txt', $Log, FILE_APPEND);
		   file_put_contents('./logs/log_user_'.$id.'.txt', $Log, FILE_APPEND);
		}
	}
}
?>

<?php
    if(!isset($_SESSION)) 
    { 
		
        session_start(); 
    } 

    // output buffer..
	ob_start("ob_gzhandler");
    // my system key cheker..
    define("inside",true);
	// get funcamental file which contain config and template files,settings.
	include("./inc/fundamentals.php");

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		 switch($_GET['do'])
		{
            case"delete":
			if($_POST)
			{
				include("./inc/Classes/system-settings_company.php");
				$setting_company = new systemSettings_company();		
				$id     = intval($_POST['id']);
				 $table  = sanitize($_POST['table']);
				$delete = $setting_company->delete_data_from_table($table,$id);
				if($delete == 1 )
				{
					echo 100;
				}else{
					echo 200;
				}
				exit;
			}
		 }
    }

?>




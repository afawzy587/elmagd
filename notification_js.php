<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    // my system key cheker..
    define("inside",true);
	// get funcamental file which contain config and template files,settings.
	include("./inc/fundamentals.php");
    include("./inc/Classes/system-notifications.php");
	$notifiactions = new systemnotifications();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
        switch($_GET['do'])
		{
            case"fetch":
                if($_POST)
                {
                    $not = $notifiactions -> getsitenotifications();
					echo  json_encode($not) ;
					exit;
                }
            break;
			case"view":
                if($_POST)
                {
                    $notifiactions -> view_notification();
                    $not = $notifiactions -> getsitenotifications();
					echo  json_encode($not) ;
					exit;
                }
            break;
            case"read":
                if($_POST)
                {
                    $id = intval($_POST['id']);
                    $notifiactions -> read_notification($id);
					exit;
                }
            break;

        }

    }
?>




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
		
    }
    include './assets/layout/header.php';

?>
<?php include './assets/layout/navbar.php';?>
 <!-- page content -->
    <div class="container mainPageContainer">
        <!-- links row -->
        <div class="row mt-5 mb-3">
            <div class="col">
                <i class="fas fa-info-circle blueSky"></i>
                <a class="blueSky" href="./index.php"> <?php echo $lang['DASHBOARD'];?> </a>
            </div>
        </div>
        
    </div>
    <!-- end page content -->
<?php include './assets/layout/footer.php'; ?>



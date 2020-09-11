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
                <a class="blueSky" href="./index.php"> <?php echo $lang['MAIN_PAGE'];?> </a>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['PERMISSION_ERROR'];?> </span>
            </div>
        </div>
        <!-- end links row -->

        <!-- about row -->
        <div class="row centerContent">
            <div class="col-md-9 darker-bg centerDarkerDiv aboutacenterDiv">
                <h5 class="text-center"><?php echo $lang['PERMISSION_ERROR_CONTENT']?> </h5>
                <div class="text-center mt-3">
                    <img src="<?php echo $path;echo ($company['companyinfo_logo'])?$company['companyinfo_logo']:$avater_default;?>" height=75 />
                </div>
                <div class="text-center mt-2">
                    <img src="<?php echo $path."defaults/icouna.png";?>" height=50 />
                </div>
            </div>
        </div>
        <!-- end about row -->
    </div>
    <!-- end page content -->
<?php include './assets/layout/footer.php';?>



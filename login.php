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
    switch($_GET['do'])
        {
            case"":
            case"login":
				if($login->doCheck() == true)
				{
					header("Location:./index.php");
                    exit;
				}else
				{
                    // recieving the parameters
				echo	$logResult = $login->doLogin(sanitize($_POST["username"]),sanitize($_POST["password"]));
                    if($logResult == "false" )
                    {
                        $message = $lang['LGN_EMPTY_DATA'];
                        
                    }elseif($logResult ==1)
                    {
                        $logs->addLog(NULL,
                                array(
                                    "type" 		        => 	"user",
                                    "module" 	        => 	"login",
                                    "mode" 		        => 	"login",
                                    "id" 	        	=>	$_SESSION['id'],
                                ),"user",$_SESSION['id'],1
                            );
                        $message = $lang['LGN_IS_SUCESSFULLY'];
                        header("Location:./index.php");
                        exit;

                    }elseif($logResult == 2)
                    {
                        $message = $lang['PASSWORD_NOT_CORRECT'];
                        
                    }elseif($logResult == 3){
                        $message = $lang['USER_NOT_FOUND'];
                    }
                    elseif($logResult == 4)
                    {
                        $message = $lang['LGN_IS_DUPLICATED'];
                        header("Location:./index.php");
                        exit;
                    }
				}
            break;
            case"logout":
                if($login->doLogout() == true)
                {
                    $logs->addLog(NULL,
                                array(
                                    "type" 		        => 	"user",
                                    "module" 	        => 	"login",
                                    "mode" 		        => 	"logout",
                                    "id" 	        	=>	$_SESSION['id'],
                                ),"user",$_SESSION['id'],1
                            );
                }else
                {
                    $message = $lang['login_first'];
                }
            break;
        }
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['LOGIN'];?></title>

    <!-- styles -->
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css"
        integrity="sha384-vus3nQHTD+5mpDiZ4rkEPlnkcyTP+49BhJ4wJeJunw06ZAp+wzzeBPUXr42fi8If" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/styles.css">

</head>
<body>
        <!-- navbar -->
    <nav class="navbar fixed-top navbar-dark main-bg-lgrd navbar-expand-md mainNavbar">
        <!-- only mob menu items -->

        <img class="mobmenuLogo" src="images/elmagdLogo.png" height=50 />

        <!-- end only mob menu items-->
        <div class="collapse navbar-collapse flex-column" id="navbar">
            <!-- top nav - header -->
            <div class="container">
                <ul class="navbar-nav topNav-ul nav w-100">
                    <a class="navbar-brand brandNP" href="#">
                        <img src="<?php echo $path;echo ($companyinfo['companyinfo_logo'])?$companyinfo['companyinfo_logo']:$avater_default;?>" height=50 />
                        <p class="mb-0 brand-name"><?php echo $lang['site_name'];?></p>
                    </a>
                </ul>
            </div>
            <!-- end top nav - header -->

            <div class="gradientBorderDiv"></div>

        </div>
    </nav>
    <!-- end navbar -->  
        <!-- page content -->
    <div class="container mainPageContainer">
        <!--  row -->
        <div class="row centerContent">
            <div class="col-md-7">
               <form action="login.php?do=login" method="post" id="loginForm">

                    <div class="row">
                        <div class="col darker-bg centerDarkerDiv ">
                            <h5 class="text-center"> <?php echo $lang['LGN_SUBMIT'];?></h5>
                            <div class="form-group">
                            <?php
							if($message)
							{
								echo '<div class="alert alert-danger faildlogin_msg" role="alert">'.$message.'</div>';
							}?>
							</div>
                            <div class="form-group">
                                <label for=""><?php echo $lang['USERNAME'];?></label>
                                <input type="text" class="form-control" placeholder="<?php echo $lang['USERNAME'];?>" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $lang['PASSWORD'];?></label>
                                <input type="password" class="form-control" placeholder="<?php echo $lang['PASSWORD'];?>" name="password" required>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"><?php echo $lang['LOG_IN'];?> </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        <!-- end  row -->
    </div>
    <!-- end page content -->
<script>
        $(function()
        {
          $('.side-bg').css({ height: $(window).innerHeight() });
          $(window).resize(function(){
            $('.side-bg').css({ height: $(window).innerHeight() });
          });
        });

        $(function ()
        {
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');

                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        });
    </script>
</body>
<footer>
	<!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"  integrity="sha384-a9xOd0rz8w0J8zqj1qJic7GPFfyMfoiuDjC9rqXlVOcGO/dmRqzMn34gZYDTel8k"
        crossorigin="anonymous"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/navbar.js"></script>
</footer>
</html>
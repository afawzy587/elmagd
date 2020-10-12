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
	include("./inc/Classes/system-reminders.php");
	$reminder = new systemReminders();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['reminders'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			if($_POST)
			{
				$_reminder['reminders_date']                        =       format_data_base($_POST["reminders_date"]);
				$_reminder['title']                 =       sanitize($_POST["title"]);
				
				$add = $reminder->addReminders($_reminder);
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"reminders",
							"mode" 		        => 	"add_reminders",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_reminders.php?action=add");
						exit;
				}
			}
		}
		
    }
    include './assets/layout/header.php';

?>
<?php include './assets/layout/navbar.php';?>
 <!-- page content -->
    <div class="container mainPageContainer">

        <!-- links row -->
<!--
        <div class="row mt-5">
            <div class="col">
                <p class="blueSky">
                    <i class="fas fa-info-circle"></i>
                    <span class="blueSky"><?php echo $lang['SETTINGS_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['Reminders'];?></span>
                </p>
            </div>
        </div>
-->
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="remindersearchForm" method="get" action="./reminders.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="remindersearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="companyDetailsForm" enctype="multipart/form-data">
                   
                    <?php 
						if($add == 1){
							echo alert_message("success",$lang['Reminders_SUCCESS']);
						}
					?>
                     <h5><?php echo $lang['Reminders_ADD'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['Reminders_DATE'];?></label>
                                    <div class="col-xs-5">
                                       <input type="date" name="reminders_date" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['Reminders_DESCRIPTION'];?></label>
                                    <input type="text" class="form-control" name="title" placeholder="<?php echo $lang['Reminders_DESCRIPTION'];?>" value="<?php echo $_reminder['title'];?>">
                                </div>
                            </div>
                        </div>
                     
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit department row -->
    </div>
    <!-- end page content -->

<?php 
$footer = '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
					crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
					crossorigin="anonymous"></script>
				<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"  integrity="sha384-a9xOd0rz8w0J8zqj1qJic7GPFfyMfoiuDjC9rqXlVOcGO/dmRqzMn34gZYDTel8k"
					crossorigin="anonymous"></script>
			<script src="./assets/js/jquery.js"></script>
			<script src="./assets/js/framework/bootstrap.js"></script>
			<script src="./assets/js/main.js"></script>
			<script src="./assets/js/navbar.js"></script>
			<script src="./assets/js/formValidation.js"></script>
			<script src="./assets/js/framework/bootstrap.js"></script>
			<script src="./assets/js/list-controls.js"></script>
			';
include './assets/layout/footer.php';
?>
<SCRIPT>
$(document).ready(function () {

    $('#remindersearch').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            // search input value =>> $(this)[0].value
            console.log($(this)[0].value);
            $('#remindersearchForm').submit();

            return false;
        }
    });


    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            reminders_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_D_INSERT_NAME'];?>'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_D_INSERT_DES'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

          input[name="reminders_name"].value = "";
          input[name="title"].value = "";
    })
})


</SCRIPT>



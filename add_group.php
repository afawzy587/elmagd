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
	include("./inc/Classes/system-settings_user_group.php");
	$user_group = new systemSettings_user_group();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_user_group'] == 0){
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
				$_group['group_name']                        =       sanitize($_POST["group_name"]);
				$_group['group_description']                 =       sanitize($_POST["group_description"]);

				$add = $user_group->addSettings_user_group($_group);
//				if($add == 1)
//				{
//					$logs->addLog(NULL,
//						array(
//							"type" 		        => 	"users",
//							"module" 	        => 	"groups",
//							"mode" 		        => 	"add_groups",
//							"id" 	        	=>	$_SESSION['id'],
//						),"admin",$_SESSION['id'],1
//						);
//
//						header("Location:./add_group.php?action=add");
//						exit;
//				}
			}
		}

    }
    include './assets/layout/header.php';

?>
<?php include './assets/layout/navbar.php';?>
 <!-- page content -->
    <div class="container mainPageContainer">

        <!-- links row -->
        <div class="row mt-5">
            <div class="col">
                <p class="blueSky">
                    <i class="fas fa-info-circle"></i>
                    <span class="blueSky"><?php echo $lang['SETTINGS_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['GROUP_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['GROUP_ADD_GROUPS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->

         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="groupSearchForm" method="get" action="./settings_user_group.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="groupSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit group row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="companyDetailsForm" enctype="multipart/form-data">

                    <?php
						if($add == 1){
							echo alert_message("success",$lang['GROUP_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['GROUP_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['GROUP_ADD_GROUPS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['GROUP_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="group_name" placeholder="<?php echo $lang['GROUP_NAME'];?>" value="<?php echo $_group['group_name'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['GROUP_DESCRIPTION'];?></label>
                                    <input type="text" class="form-control" name="group_description" placeholder="<?php echo $lang['GROUP_DESCRIPTION_HOLDER'];?>" value="<?php echo $_group['group_description'];?>">
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
        <!-- end add/edit group row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#groupSearch').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            // search input value =>> $(this)[0].value
            console.log($(this)[0].value);
            $('#groupSearchForm').submit();

            return false;
        }
    });


    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            group_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['GROUP_INSERT_NAME'];?>'
                    }
                }
            },
            group_description: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['GROUP_INSERT_DES'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

          input[name="group_name"].value = "";
          input[name="group_description"].value = "";
    })
})


</SCRIPT>



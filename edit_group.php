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
		if($group['settings_department'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u = $user_group->getSettings_user_groupInformation($mId);
				if($_POST)
				{
					$_group['group_sn']                         =         $mId;
					$_group['group_name']                       =         sanitize($_POST["group_name"]);
					$_group['group_description']                =         sanitize($_POST["group_description"]);
				    $_group['setting_company']                  =         intval($_POST["setting_company"]);
					$_group['settings_department']              =         intval($_POST["settings_department"]);
					$_group['setting_jobs']                     =         intval($_POST["setting_jobs"]);
					$_group['setting_stocks']                   =         intval($_POST["setting_stocks"]);
					$_group['settings_products']                =         intval($_POST["settings_products"]);
					$_group['settings_users']                   =         intval($_POST["settings_users"]);
					$_group['settings_clients']                 =         intval($_POST["settings_clients"]);
					$_group['settings_suppliers']               =         intval($_POST["settings_suppliers"]);
					$_group['settings_banks']                   =         intval($_POST["settings_banks"]);
					$_group['clients_pricing']                  =         intval($_POST["clients_pricing"]);
					$_group['clients_finance']                  =         intval($_POST["clients_finance"]);
					$_group['clients_old_pricing']              =         intval($_POST["clients_old_pricing"]);
					$_group['clients_payments']                 =         intval($_POST["clients_payments"]);
					$_group['operations']                       =         intval($_POST["operations"]);
					$_group['expense']                          =         intval($_POST["expense"]);
					$_group['deposit_check']                    =         intval($_POST["deposit_check"]);
					$_group['bank_transfer']                    =         intval($_POST["bank_transfer"]);
					$_group['client_payment']                   =         intval($_POST["client_payment"]);
					$_group['supplier_payment']                 =         intval($_POST["supplier_payment"]);
					$_group['settings_user_group']              =         intval($_POST["settings_user_group"]);
					$edit = $user_group->setSettings_user_groupInformation($_group);
					if($edit == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"group",
								"mode" 		        => 	"edit_user_group",
								"GROUP" 		=> 	$mId,
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);

							header("Location:./settings_user_group.php?action=edit");
							exit;
					}
				}
			}else{
				header("Location:./error.php");
				exit;
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['GROUP_EDIT_GROUPS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->

         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="groupearchForm" method="get" action="./settings_user_group.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="groupearch" name="q">
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
						if($edit == 1){
							echo alert_message("success",$lang['GROUP_SUCCESS']);
						}elseif($edit == 2){
							echo alert_message("danger",$lang['GROUP_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['GROUP_EDIT_GROUPS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['GROUP_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="group_name" placeholder="<?php echo $lang['GROUP_NAME'];?>" value="<?php if($_group){echo $_group['group_name'];}else{echo $u['group_name'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['GROUP_DESCRIPTION'];?></label>
                                    <input type="text" class="form-control" name="group_description" placeholder="<?php echo $lang['GROUP_DESCRIPTION_HOLDER'];?>" value="<?php if($_group){echo $_group['group_description'];}else{echo $u['group_description'];}?>">
                                </div>
                            </div>
                        </div>
                        <div class="darker-bg centerDarkerDiv formCenterDiv mt-2">
							<div class="row">
							<?php
								group_check('setting_company',$u['setting_company']);
								group_check('settings_department',$u['settings_department']);
								group_check('setting_jobs',$u['setting_jobs']);

								?>
							</div>
							<div class="row">
								<?php
									group_check('setting_stocks',$u['setting_stocks']);
									group_check('settings_products',$u['settings_products']);
									group_check('settings_clients',$u['settings_clients']);
									?>
							</div>
							<div class="row">
								<?php
									group_check('settings_suppliers',$u['settings_suppliers']);
									group_check('settings_banks',$u['settings_banks']);
									group_check('settings_users',$u['settings_users']);

									?>
							</div>
							<div class="row">
								<?php
									group_check('clients_pricing',$u['clients_pricing']);
									group_check('clients_finance',$u['clients_finance']);
									group_check('clients_payments',$u['clients_payments']);
									?>
							</div>
                  	 		<div class="row">
								<?php
									group_check('clients_old_pricing',$u['clients_old_pricing']);
									group_check('operations',$u['operations']);
									group_check('expense',$u['expense']);
									?>
							</div>
                  	 		<div class="row">
								<?php
									group_check('deposit_check',$u['deposit_check']);
									group_check('bank_transfer',$u['bank_transfer']);
									group_check('supplier_payment',$u['supplier_payment']);
									?>
							</div>
                  	 		<div class="row">
								<?php
									group_check('settings_user_group',$u['settings_user_group']);
									group_check('reminders',$u['reminders']);
									group_check('delete_deposits',$u['delete_deposits']);
								?>
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

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#groupearch').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            // search input value =>> $(this)[0].value
            console.log($(this)[0].value);
            $('#groupearchForm').submit();

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



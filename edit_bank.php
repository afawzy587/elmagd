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
	include("./inc/Classes/system-settings_banks.php");
	$setting_bank = new systemSettings_banks();
	include("./inc/Classes/system-settings_clients.php");
	$setting_client = new systemSettings_clients();
	include("./inc/Classes/system-settings_products.php");
	$setting_product = new systemSettings_products();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_banks'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u        = $setting_bank->getSettings_banksInformation($mId);
				$clients   = $setting_client->getsiteSettings_clients();
				$products   = $setting_product->getsiteSettings_products();

				if($_POST)
				{
					$_bank['banks_sn']                                      =       $mId;
					$_bank['banks_name']                                    =       sanitize($_POST["banks_name"]);
					$_bank['banks_account_number']                          =       sanitize($_POST["banks_account_number"]);
					$_bank['banks_credit_sn']                               =       $_POST["banks_credit_sn"];
					$_bank['banks_credit_name']                             =       $_POST["banks_credit_name"];
					$_bank['banks_credit_code']                             =       $_POST["banks_credit_code"];
					$_bank['banks_credit_open_balance']                     =       $_POST["banks_credit_open_balance"];
					$_bank['banks_credit_repayment_period']                 =       $_POST["banks_credit_repayment_period"];
					$_bank['banks_credit_interest_rate']                    =       $_POST["banks_credit_interest_rate"];
					$_bank['banks_credit_duration_of_interest']             =       $_POST["banks_credit_duration_of_interest"];
					$_bank['banks_credit_limit_value']                      =       $_POST["banks_credit_limit_value"];
					$_bank['banks_credit_cutting_ratio']                    =       $_POST["banks_credit_cutting_ratio"];
					$_bank['banks_credit_repayment_type']                   =       $_POST["banks_credit_repayment_type"];
					$_bank['banks_credit_client']                           =       $_POST["banks_credit_client"];
					$_bank['banks_credit_product']                          =       $_POST["banks_credit_product"];
					$_bank['banks_saving_sn']                               =       sanitize($_POST["banks_saving_sn"]);
					$_bank['banks_saving_account_number']                   =       sanitize($_POST["banks_saving_account_number"]);
					$_bank['banks_saving_open_balance']                     =       sanitize($_POST["banks_saving_open_balance"]);
					$_bank['banks_saving_interest_rate']                    =       sanitize($_POST["banks_saving_interest_rate"]);
					$_bank['banks_saving_duration_of_interest']             =       sanitize($_POST["banks_saving_duration_of_interest"]);
					$_bank['banks_current_sn']                              =       sanitize($_POST["banks_current_sn"]);
					$_bank['banks_current_account_number']                  =       sanitize($_POST["banks_current_account_number"]);
					$_bank['banks_current_opening_balance']                 =       sanitize($_POST["banks_current_opening_balance"]);

					$edit = $setting_bank->setSettings_banksInformation($_bank);
					if($edit == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"banks",
								"mode" 		        => 	"edit_bank",
								"item" 	         	=> 	$mId,
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);

							header("Location:./settings_banks.php?action=edit");
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_BAN_BANKS'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_BAN_EDIT_BANKS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="banksearchForm" method="get" action="./settings_banks.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="banksearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit client row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="addbankForm" enctype="multipart/form-data">
                   
                    <?php 
						if($add == 1){
							echo alert_message("success",$lang['SETTINGS_BAN_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['SETTINGS_BAN_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['SETTINGS_BAN_EDIT_BANKS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="banks_name" placeholder="<?php echo $lang['SETTINGS_BAN_NAME'];?>" value="<?php if($_bank){echo $_bank['banks_name'];}else{echo $u['banks_name'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_ACCOUNT_NUM'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="banks_account_number" value="<?php if($_bank){echo $_bank['banks_account_number'];}else{echo $u['banks_account_number'];}?>"
                                            placeholder="<?php echo $lang['SETTINGS_BAN_ACCOUNT_NUM'];?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row mt-5">
                        <div class="col">
                            <h5><?php echo $lang['SETTINGS_BAN_SUB_ACCOUNT'];?></h5>
                            <div class="row">
                                <div class="col">
                                    <div class="darker-bg centerDarkerDiv formCenterDiv ">
                                        <h5> <?php echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT'];?></h5>
                                        <!-- bank item -->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_1'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[0]" value="<?php if($_bank){echo $_bank['banks_credit_name'][0];}else{echo $u['banks_credit'][0]['banks_credit_name'];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_1'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[0]" value="<?php if($_bank){echo $_bank['banks_credit_code'][0];}else{echo $u['banks_credit'][0]['banks_credit_code'];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[0]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][0];}else{echo $u['banks_credit'][0]['banks_credit_open_balance'];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[0]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][0];}else{echo $u['banks_credit'][0]['banks_credit_repayment_period'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[0]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][0];}else{echo $u['banks_credit'][0]['banks_credit_interest_rate'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[0]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][0];}else{echo $u['banks_credit'][0]['banks_credit_duration_of_interest'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[0]" value="<?php if($_bank){echo $_bank['banks_credit_limit_value'][0];}else{echo $u['banks_credit'][0]['banks_credit_limit_value'];}?>"
                                                                        placeholder="---">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[0]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][0];}else{echo $u['banks_credit'][0]['banks_credit_cutting_ratio'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[0]" id="type1" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][0] =="day"){echo 'checked';}}else{if($u['banks_credit'][0]['banks_credit_repayment_type'] =="day"){echo 'checked';}}?> >
                                                            <label class="form-check-label" for="type1">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[0]" id="type2" value="date" <?php if($_bank){if($_bank['banks_credit_repayment_type'][0] =="date"){echo 'checked';}}else{if($u['banks_credit'][0]['banks_credit_repayment_type'] =="date"){echo 'checked';}}?>
                                                                >
                                                            <label class="form-check-label smallLabel"
                                                                for="type2">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[0]" class="form-control" id="banks_credit_client">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_client'][0] == 0){echo 'selected';}}else{if($u['banks_credit'][0]['banks_credit_client'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][0]){echo 'selected';}}else{if($c["clients_sn"] == $u['banks_credit'][0]['banks_credit_client']){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[0]" class="form-control" id="slct">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_product'][0] == 0){echo 'selected';}}else{if($u['banks_credit'][0]['banks_credit_product'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][0]){echo 'selected';}}else{if($p["products_sn"] == $u['banks_credit'][0]['banks_credit_product']){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bank item -->
                                        <div class="divider"></div>
                                        <!-- bank item  1-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_2'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[1]" value="<?php if($_bank){echo $_bank['banks_credit_name'][1];}else{echo $u['banks_credit'][1]['banks_credit_name'];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_2'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[1]" value="<?php if($_bank){echo $_bank['banks_credit_code'][1];}else{echo $u['banks_credit'][1]['banks_credit_code'];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[1]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][1];}else{echo $u['banks_credit'][1]['banks_credit_open_balance'];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[1]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][1];}else{echo $u['banks_credit'][1]['banks_credit_repayment_period'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[1]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][1];}else{echo $u['banks_credit'][1]['banks_credit_interest_rate'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[1]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][1];}else{echo $u['banks_credit'][1]['banks_credit_duration_of_interest'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[1]" value="<?php if($_bank){echo $_bank['banks_credit_limit_value'][1];}else{echo $u['banks_credit'][1]['banks_credit_limit_value'];}?>"
                                                                        placeholder="---">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[1]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][1];}else{echo $u['banks_credit'][1]['banks_credit_cutting_ratio'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[1]" id="type_1_1" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][1] =="day"){echo 'checked';}}else{if($u['banks_credit'][1]['banks_credit_repayment_type'] =="day"){echo 'checked';}}?> >
                                                            <label class="form-check-label" for="type_1_1">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[1]" id="type_2_2" <?php if($_bank){if($_bank['banks_credit_repayment_type'][1] =="date"){echo 'checked';}}else{if($u['banks_credit'][1]['banks_credit_repayment_type'] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_2">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[1]" class="form-control" id="banks_credit_client">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_client'][1] == 0){echo 'selected';}}else{if($u['banks_credit'][1]['banks_credit_client'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][1]){echo 'selected';}}else{if($c["clients_sn"] == $u['banks_credit'][1]['banks_credit_client']){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[1]" class="form-control" id="slct">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_product'][1] == 0){echo 'selected';}}else{if($u['banks_credit'][1]['banks_credit_product'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][1]){echo 'selected';}}else{if($p["products_sn"] == $u['banks_credit'][1]['banks_credit_product']){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bank item 1 -->
                                        <div class="divider"></div>
                                                                                <!-- bank item  4-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_3'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[2]" value="<?php if($_bank){echo $_bank['banks_credit_name'][2];}else{echo $u['banks_credit'][2]['banks_credit_name'];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_3'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[2]" value="<?php if($_bank){echo $_bank['banks_credit_code'][2];}else{echo $u['banks_credit'][2]['banks_credit_code'];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[2]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][2];}else{echo $u['banks_credit'][2]['banks_credit_open_balance'];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[2]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][2];}else{echo $u['banks_credit'][2]['banks_credit_repayment_period'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[2]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][2];}else{echo $u['banks_credit'][2]['banks_credit_interest_rate'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[2]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][2];}else{echo $u['banks_credit'][2]['banks_credit_duration_of_interest'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[2]" value="<?php if($_bank){echo $_bank['banks_credit_limit_value'][2];}else{echo $u['banks_credit'][2]['banks_credit_limit_value'];}?>"
                                                                        placeholder="---">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[2]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][2];}else{echo $u['banks_credit'][2]['banks_credit_cutting_ratio'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[2]" id="type_1_6" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][2] =="day"){echo 'checked';}}else{if($u['banks_credit'][2]['banks_credit_repayment_type'] =="day"){echo 'checked';}}?> >
                                                            <label class="form-check-label" for="type_1_6">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[2]" id="type_2_6" <?php if($_bank){if($_bank['banks_credit_repayment_type'][2] =="date"){echo 'checked';}}else{if($u['banks_credit'][2]['banks_credit_repayment_type'] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_6">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[2]" class="form-control" id="banks_credit_client">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_client'][2] == 0){echo 'selected';}}else{if($u['banks_credit'][2]['banks_credit_client'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][2]){echo 'selected';}}else{if($c["clients_sn"] == $u['banks_credit'][2]['banks_credit_client']){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[2]" class="form-control" id="slct">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_product'][2] == 0){echo 'selected';}}else{if($u['banks_credit'][2]['banks_credit_product'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][2]){echo 'selected';}}else{if($p["products_sn"] == $u['banks_credit'][2]['banks_credit_product']){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bank item 2 -->
                                        <div class="divider"></div>
                                        <!-- bank item  2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_4'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[3]" value="<?php if($_bank){echo $_bank['banks_credit_name'][3];}else{echo $u['banks_credit'][3]['banks_credit_name'];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_4'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[3]" value="<?php if($_bank){echo $_bank['banks_credit_code'][3];}else{echo $u['banks_credit'][3]['banks_credit_code'];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[3]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][3];}else{echo $u['banks_credit'][3]['banks_credit_open_balance'];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[3]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][3];}else{echo $u['banks_credit'][3]['banks_credit_repayment_period'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[3]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][3];}else{echo $u['banks_credit'][3]['banks_credit_interest_rate'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[3]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][3];}else{echo $u['banks_credit'][3]['banks_credit_duration_of_interest'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[3]" value="<?php if($_bank){echo $_bank['banks_credit_limit_value'][3];}else{echo $u['banks_credit'][3]['banks_credit_limit_value'];}?>"
                                                                        placeholder="---">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[3]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][3];}else{echo $u['banks_credit'][3]['banks_credit_cutting_ratio'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[3]" id="type_1_3" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][3] =="day"){echo 'checked';}}else{if($u['banks_credit'][3]['banks_credit_repayment_type'] =="day"){echo 'checked';}}?> >
                                                            <label class="form-check-label" for="type_1_3">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[3]" id="type_2_3" <?php if($_bank){if($_bank['banks_credit_repayment_type'][3] =="date"){echo 'checked';}}else{if($u['banks_credit'][3]['banks_credit_repayment_type'] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_3">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[3]" class="form-control" id="banks_credit_client">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_client'][3] == 0){echo 'selected';}}else{if($u['banks_credit'][3]['banks_credit_client'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][3]){echo 'selected';}}else{if($c["clients_sn"] == $u['banks_credit'][3]['banks_credit_client']){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[3]" class="form-control" id="slct">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_product'][3] == 0){echo 'selected';}}else{if($u['banks_credit'][3]['banks_credit_product'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][3]){echo 'selected';}}else{if($p["products_sn"] == $u['banks_credit'][3]['banks_credit_product']){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bank item 3 -->                                        
                                        
                                        <div class="divider"></div>
                                       
                                        <div class="divider"></div>
                    					                                        <!-- bank item  4-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_5'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[4]" value="<?php if($_bank){echo $_bank['banks_credit_name'][4];}else{echo $u['banks_credit'][4]['banks_credit_name'];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_5'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[4]" value="<?php if($_bank){echo $_bank['banks_credit_code'][4];}else{echo $u['banks_credit'][4]['banks_credit_code'];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[4]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][4];}else{echo $u['banks_credit'][4]['banks_credit_open_balance'];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[4]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][4];}else{echo $u['banks_credit'][4]['banks_credit_repayment_period'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[4]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][4];}else{echo $u['banks_credit'][4]['banks_credit_interest_rate'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[4]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][4];}else{echo $u['banks_credit'][4]['banks_credit_duration_of_interest'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[4]" value="<?php if($_bank){echo $_bank['banks_credit_limit_value'][4];}else{echo $u['banks_credit'][4]['banks_credit_limit_value'];}?>"
                                                                        placeholder="---">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[4]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][4];}else{echo $u['banks_credit'][4]['banks_credit_cutting_ratio'];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[4]" id="type_1_5" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="day"){echo 'checked';}}else{if($u['banks_credit'][4]['banks_credit_repayment_type'] =="day"){echo 'checked';}}?> >
                                                            <label class="form-check-label" for="type_1_5">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[4]" id="type_2_5" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="date"){echo 'checked';}}else{if($u['banks_credit'][4]['banks_credit_repayment_type'] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_5">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[4]" class="form-control" id="banks_credit_client">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_client'][4] == 0){echo 'selected';}}else{if($u['banks_credit'][4]['banks_credit_client'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][4]){echo 'selected';}}else{if($c["clients_sn"] == $u['banks_credit'][4]['banks_credit_client']){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[4]" class="form-control" id="slct">
                                                                    <option value="0" <?php if($_bank){if($_bank['banks_credit_product'][4] == 0){echo 'selected';}}else{if($u['banks_credit'][4]['banks_credit_product'] == 0 ){echo 'selected';}}?>> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][4]){echo 'selected';}}else{if($p["products_sn"] == $u['banks_credit'][4]['banks_credit_product']){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end bank item 2 -->
                                        <div class="divider"></div>

                                        <div class="row justify-content-start">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_TOTAL_CREDIT'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="form-control" id="totalCreditLineAmount" name=""
                                                            placeholder="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_TOTAL'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="form-control" name="" id="totalCredit"
                                                            placeholder="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="darker-bg centerDarkerDiv formCenterDiv" >
                                        <h5> <?php echo $lang['SETTINGS_BAN_CURRENT_ACCOUNT'];?></h5>

                                        <div class="row justify-content-start align-items-center">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-2">
                                                    <label class="form-check-label" for="typeee">
                                                         <?php echo $lang['SETTINGS_BAN_SAVE'];?>
                                                    </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CUR_CODE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_account_number" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}else{echo $u['banks_saving_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_open_balance" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}else{echo $u['banks_saving_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-start">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_interest_rate" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}else{echo $u['banks_saving_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_duration_of_interest" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}else{echo $u['banks_saving_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="divider darker"></div>

                                        <div class="row justify-content-start align-items-center">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-2">
                                                    <label class="form-check-label" for="typee">
                                                         <?php echo $lang['SETTINGS_BAN_CURRENT'];?>
                                                    </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CUR_CODE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_current_account_number" value="<?php if($_bank){echo $_bank['banks_current_account_number'];}else{echo $u['banks_current_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_current_opening_balance" value="<?php if($_bank){echo $_bank['banks_current_opening_balance'];}else{echo $u['banks_current_opening_balance'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="banks_credit_sn[0]" value="<?php echo $u['banks_credit'][0]['banks_credit_sn'];?>" hidden >
					<input name="banks_credit_sn[1]" value="<?php echo $u['banks_credit'][1]['banks_credit_sn'];?>" hidden >
					<input name="banks_credit_sn[2]" value="<?php echo $u['banks_credit'][2]['banks_credit_sn'];?>" hidden >
					<input name="banks_credit_sn[3]" value="<?php echo $u['banks_credit'][3]['banks_credit_sn'];?>" hidden >
					<input name="banks_credit_sn[4]" value="<?php echo $u['banks_credit'][4]['banks_credit_sn'];?>" hidden >
                    <input name="banks_saving_sn" value="<?php echo $u['banks_saving_sn'];?>" hidden>
                    <input name="banks_current_sn" value="<?php echo $u['banks_current_sn'];?>" hidden>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit client row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#addbankForm').formValidation({
        excluded: [':disabled'],
        fields: {
            banks_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_INSERT_NAME'];?>'
                    }
                }
            },
            banks_account_number: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_IN_ACCOUNT_NUM'];?> '
                    },
                    digits: {
                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?> '
                    }
                }
            },
            "banks_credit_name[0]": {
                validators: {
                    notEmpty: {
                        message: '  <?php echo $lang['SETTINGS_BAN_IN_CREDIT_NAME'];?>'
                    }
                }
            },
            "banks_credit_code[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_INSERT_CREDIT_NAME'];?>'
                    },
                    digits: {
                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?> '
                    }
                }
            },
            "banks_credit_open_balance[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    }
                }
            },
            "banks_credit_repayment_period[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>    '
                    },
                    digits: {
                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
                    }
                }
            },
            "banks_credit_interest_rate[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo  $lang['SETTINGS_BAN_IN_RATE_INT'];?>  '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    }
                }
            },
            "banks_credit_duration_of_interest[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>'
                    },
                    digits: {
                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
                    }
                }
            },
            "banks_credit_limit_value[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_IN_BAN_LIMIT_VALUE'];?>   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    }
                }
            },
            "banks_credit_cutting_ratio[0]": {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_IN_BAN_CUTT_VALUE'];?>'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    }
                }
            },
//            "banks_credit_client[0]": {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?>'
//                    }
//                }
//            },
//            "banks_credit_product[0]": {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?>'
//                    }
//                }
//            },
//            banks_saving_account_number: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_IN_ACCOUNT_NUM'];?>'
//                    },
//                    digits: {
//                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
//                    }
//                }
//            },
//            banks_saving_open_balance: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
//                    },
//                    regexp: {
//                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
//                        message: '<?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
//                    }
//                }
//            },
//            banks_saving_interest_rate: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo  $lang['SETTINGS_BAN_IN_RATE_INT'];?>  '
//                    },
//                    regexp: {
//                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
//                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
//                    }
//                }
//            },
//            banks_saving_duration_of_interest: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_IN_DUE_INT'];?>'
//                    },
//                    digits: {
//                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
//                    }
//                }
//            },
//            banks_current_account_number: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_IN_ACCOUNT_NUM'];?>  '
//                    },
//                    digits: {
//                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
//                    }
//                }
//            },
//            banks_current_opening_balance: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
//                    },
//                    regexp: {
//                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
//                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
//                    }
//                }
//            },
        }
    }).on('success.form.bv', function (e) {
    })
    // calc credit line
    $('input[name="banks_credit_limit_value[]"]').keyup(function () {
        var inputs = $('input.creditLineInput');
        var totalCreditLine = 0;
        $.each(inputs, function () {
            console.log($(this).val());
            if ($(this).val() != '') {
                totalCreditLine += parseFloat($(this).val());
            }

        })
        $('input#totalCreditLineAmount').val(totalCreditLine)
        console.log(totalCreditLine);

    })


    // calc credit
    $('input[name="banks_credit_open_balance[]"]').keyup(function () {
        var inputsEle = $('input.openingCreditInput');
        var totalCredit = 0;
        $.each(inputsEle, function () {
            console.log($(this).val());
            if ($(this).val() != '') {
                totalCredit += parseFloat($(this).val());
            }

        })
        $('input#totalCredit').val(totalCredit)
        console.log(totalCredit);

    })

})	
	


</SCRIPT>



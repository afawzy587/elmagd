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
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			$clients   = $setting_client->getsiteSettings_clients();
			$products   = $setting_product->getsiteSettings_products();

			if($_POST)
			{
				$_bank['banks_name']                                    =       sanitize($_POST["banks_name"]);
				$_bank['banks_account_number']                          =       sanitize($_POST["banks_account_number"]);
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
				$_bank['banks_saving_account_number']                   =       sanitize($_POST["banks_saving_account_number"]);
				$_bank['banks_saving_open_balance']                     =       sanitize($_POST["banks_saving_open_balance"]);
				$_bank['banks_saving_interest_rate']                    =       sanitize($_POST["banks_saving_interest_rate"]);
				$_bank['banks_saving_duration_of_interest']             =       sanitize($_POST["banks_saving_duration_of_interest"]);
				$_bank['banks_current_account_number']                  =       sanitize($_POST["banks_current_account_number"]);
				$_bank['banks_current_opening_balance']                 =       sanitize($_POST["banks_current_opening_balance"]);

				$add = $setting_bank->addSettings_banks($_bank);
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"banks",
							"mode" 		        => 	"add_banks",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_bank.php?action=add");
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
        <div class="row mt-5">
            <div class="col">
                <p class="blueSky">
                    <i class="fas fa-info-circle"></i>
                    <span class="blueSky"><?php echo $lang['SETTINGS_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_BAN_BANKS'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_BAN_ADD_BANKS'];?></span>
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
                     <h5><?php echo $lang['SETTINGS_BAN_ADD_BANKS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="banks_name" placeholder="<?php echo $lang['SETTINGS_BAN_NAME'];?>" value="<?php echo $_bank['banks_name'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_ACCOUNT_NUM'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="banks_account_number" value="<?php if($_bank){echo $_bank['banks_account_number'];}?>"
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
                                        <!-- bank item 1-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_1'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="account_valide form-control" name="banks_credit_name[0]" value="<?php if($_bank){echo $_bank['banks_credit_name'][0];}else{echo ''; }?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_code[0]" value="<?php if($_bank){echo $_bank['banks_credit_code'][0];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[0]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][0];}?>"
                                                                        placeholder="-----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[0]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][0];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[0]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][0];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[0]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][0];}?>"
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
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[0]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][0];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[0]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][0];}?>"
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
                                                                name="banks_credit_repayment_type[0]" id="type1" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][0] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type1">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[0]" id="type2" <?php if($_bank){if($_bank['banks_credit_repayment_type'][0] =="date"){echo 'checked';}}?>
                                                                value="date">
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][0]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][0]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- bank item 2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_2'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[1]" value="<?php if($_bank){echo $_bank['banks_credit_name'][1];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_code[1]" value="<?php if($_bank){echo $_bank['banks_credit_code'][1];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[1]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][1];}?>"
                                                                        placeholder="-----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[1]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][1];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[1]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][1];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[1]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][1];}?>"
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
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[1]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][1];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[1]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][1];}?>"
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
                                                                name="banks_credit_repayment_type[1]" id="type_1_2" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][1] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type_1_2">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[1]" id="type_2_2" <?php if($_bank){if($_bank['banks_credit_repayment_type'][1] =="date"){echo 'checked';}}?>
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][1]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][1]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- bank item 2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_3'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[2]" value="<?php if($_bank){echo $_bank['banks_credit_name'][2];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_code[2]" value="<?php if($_bank){echo $_bank['banks_credit_code'][2];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[2]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][2];}?>"
                                                                        placeholder="-----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[2]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][2];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[2]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][2];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[2]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][2];}?>"
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
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[2]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][2];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[2]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][2];}?>"
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
                                                                name="banks_credit_repayment_type[2]" id="type_1_3" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][2] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type_1_3">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[2]" id="type_2_3" <?php if($_bank){if($_bank['banks_credit_repayment_type'][2] =="date"){echo 'checked';}}?>
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
                                                                <select name="banks_credit_client[2]" class="form-control" id="banks_credit_client">
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][2]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][2]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- bank item 2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_4'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[3]" value="<?php if($_bank){echo $_bank['banks_credit_name'][3];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_code[3]" value="<?php if($_bank){echo $_bank['banks_credit_code'][3];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[3]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][3];}?>"
                                                                        placeholder="-----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[3]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][3];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[3]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][3];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[3]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][3];}?>"
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
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[3]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][3];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[3]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][3];}?>"
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
                                                                name="banks_credit_repayment_type[3]" id="type_1_4" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][3] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type_1_4">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[3]" id="type_2_4" <?php if($_bank){if($_bank['banks_credit_repayment_type'][3] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_4">
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][3]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][3]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- end bank item 4 -->
                                        <div class="divider"></div>
                                        <!-- bank item 2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_5'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[4]" value="<?php if($_bank){echo $_bank['banks_credit_name'][4];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_code[4]" value="<?php if($_bank){echo $_bank['banks_credit_code'][4];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[4]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][4];}?>"
                                                                        placeholder="-----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[4]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][4];}?>"
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
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[4]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][4];}?>"
                                                                        placeholder="--">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[4]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][4];}?>"
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
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[4]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][4];}?>"
                                                                        placeholder="----">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[4]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][4];}?>"
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
                                                                name="banks_credit_repayment_type[4]" id="type_1_5" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type_1_5">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[4]" id="type_2_5" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="date"){echo 'checked';}}?>
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][4]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
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
                                                                    <option selected > <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][4]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- end bank item 5 -->
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
                                                                <input type="text" class="account_valide form-control" name="banks_saving_account_number" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}else{echo ''; }?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_open_balance" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}?>"
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
                                                                <input type="text" class="form-control" name="banks_saving_interest_rate" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_saving_duration_of_interest" value="<?php if($_bank){echo $_bank['banks_saving_account_number'];}?>"
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
                                                                <input type="text" class="account_valide form-control" name="banks_current_account_number" value="<?php if($_bank){echo $_bank['banks_current_account_number'];}else{echo ''; }?>"
                                                                    placeholder="--">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                            <div class="col-xs-5">
                                                                <input type="text" class="form-control" name="banks_current_opening_balance" value="<?php if($_bank){echo $_bank['banks_current_opening_balance'];}?>"
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

        }
    }).on('success.form.bv', function (e) {
    })
    // calc credit line
    $('.creditLineInput').keyup(function () {
        var inputs = $('input.creditLineInput');
        var totalCreditLine = 0;
        $.each(inputs, function () {
            if ($(this).val() != '') {
                totalCreditLine += parseFloat($(this).val());
            }

        })
        $('input#totalCreditLineAmount').val(totalCreditLine)

    })

    // calc credit
    $('.openingCreditInput').keyup(function () {
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
	
	 function add_validate(){
		var y = $('[name="banks_credit_name[0]"]').val();
		var m = $('[name="banks_saving_account_number"]').val();
		var d = $('[name="banks_current_account_number"]').val();
		var banks_credit_name = $('[name="banks_credit_name[0]"]');
		var banks_credit_code = $('[name="banks_credit_code[0]"]');
		var banks_credit_open_balance = $('[name="banks_credit_open_balance[0]"]');
		var banks_credit_repayment_period = $('[name="banks_credit_repayment_period[0]"]');
		var banks_credit_interest_rate = $('[name="banks_credit_interest_rate[0]"]');
		var banks_credit_duration_of_interest = $('[name="banks_credit_duration_of_interest[0]"]');
		var banks_credit_limit_value    = $('[name="banks_credit_limit_value[0]"]');
		var banks_credit_cutting_ratio  = $('[name="banks_credit_cutting_ratio[0]"]');
		 
		var banks_saving_account_number = $('[name="banks_saving_account_number"]');
		var banks_saving_open_balance   = $('[name="banks_saving_open_balance"]');
		var banks_saving_interest_rate  = $('[name="banks_saving_interest_rate"]');
		var banks_saving_duration_of_interest  = $('[name="banks_saving_duration_of_interest"]');
		var banks_current_opening_balance  = $('[name="banks_current_opening_balance"]');
		if(!y && !m  && !d )
		{
			console.log(1);
			 $('#addbankForm').formValidation('addField', banks_credit_name, {
					validators: {
						notEmpty: {
							message: ' <?php echo $lang['SETTINGS_BAN_IN_CREDIT_NAME'];?>'
						}
					}
				})
								.formValidation('addField',banks_credit_code, {
									validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_BAN_INSERT_CREDIT_NAME'];?>'
										},
										digits: {
											message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?> '
										}
									}
								})
								.formValidation('addField', banks_credit_open_balance, {
								   validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_repayment_period, {
									 validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>    '
											},
											digits: {
												message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
											}
										}
								})
								.formValidation('addField', banks_credit_interest_rate, {
									 validators: {
										notEmpty: {
											message: '<?php echo  $lang['SETTINGS_BAN_IN_RATE_INT'];?>  '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_duration_of_interest, {
									 validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>'
											},
											digits: {
												message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
											}
										}
									})
								.formValidation('addField', banks_credit_limit_value, {
									 validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_IN_BAN_LIMIT_VALUE'];?>   '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_cutting_ratio, {
									  validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_IN_BAN_CUTT_VALUE'];?>'
											},
											regexp: {
												regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
												message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
											}
										}
								})
		}else{
			if(m !="" || d != "" || y != ""){
				if(m !="" )
				{
					console.log(3);
					$('#addbankForm')
					.formValidation('addField', banks_saving_open_balance, {
						 validators: {
							 notEmpty: {
								 message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
							 },
							 regexp: {
								 regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
								 message: '<?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
							 }
						 }
					})
					.formValidation('addField', banks_saving_interest_rate, {
						 validators: {
								notEmpty: {
									message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>    '
								},
								digits: {
									message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
								}
							}
					})
					.formValidation('addField', banks_saving_duration_of_interest, {
						validators: {
							 notEmpty: {
								 message: '<?php echo $lang['SETTINGS_BAN_IN_DUE_INT'];?>'
							 },
							 digits: {
								 message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
							 }
						}
					})
				}else if(d !="")
				{
					console.log(4);
					$('#addbankForm')
						.formValidation('addField', banks_current_opening_balance, {
							 validators: {
								 notEmpty: {
									 message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
								 },
								 regexp: {
									 regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
									 message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
								 }
							 }
						})
				}else if(y != "")
				{
					
					$('#addbankForm')
								.formValidation('addField',banks_credit_code, {
									validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_BAN_INSERT_CREDIT_NAME'];?>'
										},
										digits: {
											message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?> '
										}
									}
								})
								.formValidation('addField', banks_credit_open_balance, {
								   validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_BAN_IN_OPEN_PALANCE'];?>   '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_repayment_period, {
									 validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>    '
											},
											digits: {
												message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
											}
										}
								})
								.formValidation('addField', banks_credit_interest_rate, {
									 validators: {
										notEmpty: {
											message: '<?php echo  $lang['SETTINGS_BAN_IN_RATE_INT'];?>  '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_duration_of_interest, {
									 validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_BAN_IN_PAIED'];?>'
											},
											digits: {
												message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
											}
										}
									})
								.formValidation('addField', banks_credit_limit_value, {
									 validators: {
										notEmpty: {
											message: '<?php echo $lang['SETTINGS_IN_BAN_LIMIT_VALUE'];?>   '
										},
										regexp: {
											regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
											message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
										}
									}
								})
								.formValidation('addField', banks_credit_cutting_ratio, {
									  validators: {
											notEmpty: {
												message: '<?php echo $lang['SETTINGS_IN_BAN_CUTT_VALUE'];?>'
											},
											regexp: {
												regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
												message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
											}
										}
								})
				}
			}else if(m == "" || d == "" || y == ""){
					if(m == ""){
						console.log(5);

						banks_saving_open_balance.siblings('.help-block').hide();
						banks_saving_interest_rate.siblings('.help-block').hide();
						banks_saving_duration_of_interest.siblings('.help-block').hide();
						$('#addbankForm')
							.formValidation('removeField',banks_saving_open_balance)
							.formValidation('removeField',banks_saving_interest_rate)
							.formValidation('removeField',banks_saving_duration_of_interest)
					}else if(d == ""){
						console.log(6);
						 banks_current_opening_balance.siblings('.help-block').hide();
						$('#addbankForm')
						.formValidation('removeField',banks_current_opening_balance)
					}else if(y == ""){
					banks_credit_code.siblings('.help-block').hide();
					banks_credit_open_balance.siblings('.help-block').hide();
					banks_credit_repayment_period.siblings('.help-block').hide();
					banks_credit_interest_rate.siblings('.help-block').hide();
					banks_credit_duration_of_interest.siblings('.help-block').hide();
					banks_credit_limit_value.siblings('.help-block').hide();
					banks_credit_cutting_ratio.siblings('.help-block').hide();
					$('#addbankForm')
						.formValidation('removeField',banks_credit_name)
						.formValidation('removeField',banks_credit_code)
						.formValidation('removeField',banks_credit_open_balance)
						.formValidation('removeField',banks_credit_repayment_period)
						.formValidation('removeField',banks_credit_interest_rate)
						.formValidation('removeField',banks_credit_duration_of_interest)
						.formValidation('removeField',banks_credit_limit_value)
						.formValidation('removeField',banks_credit_cutting_ratio)
				}
			}
				
		}
		
	};
	$('.account_valide').on('change',function(){add_validate()});
	$('.roundedBtn').click(function(){add_validate()});

})	
	


</SCRIPT>



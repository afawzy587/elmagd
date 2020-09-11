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
	include("./inc/Classes/system-settings_stocks.php");
	$setting_stock = new systemSettings_stocks();

	include("./inc/Classes/system-settings_products.php");
	$setting_product = new systemSettings_products();


    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['setting_stocks'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u = $setting_stock->getSettings_stocksInformation($mId);
				$products   = $setting_product->getsiteSettings_products();
				if($_POST)
				{

					$_stock['stocks_sn']                         =       $mId;
					$_stock['stocks_name']                        =       sanitize($_POST["stocks_name"]);
					$_stock['stocks_manager_name']                =       sanitize($_POST["stocks_manager_name"]);
					$_stock['stocks_phone_one']                   =       sanitize($_POST["stocks_phone_one"]);
					$_stock['stocks_phone_two']                   =       sanitize($_POST["stocks_phone_two"]);
					$_stock['stocks_manager_phone']               =       sanitize($_POST["stocks_manager_phone"]);
					$_stock['stocks_email']                       =       sanitize($_POST["stocks_email"]);
					$_stock['stocks_address']                     =       sanitize($_POST["stocks_address"]);
					$_stock['check']                              =       $_POST["check"];
					$_stock['product']                            =       $_POST["product"];
					$_stock['stocks_products']                    =       $_POST["stocks_products"];
					$_stock['stocks_products_rate_one']           =       $_POST["stocks_products_rate_one"];
					$_stock['stocks_products_rate_two']           =       $_POST["stocks_products_rate_two"];
					$_stock['stocks_products_rate_three']         =       $_POST["stocks_products_rate_three"];
					$_stock['stocks_products_rate_four']          =       $_POST["stocks_products_rate_four"];
					$_stock['stocks_products_rate_five']          =       $_POST["stocks_products_rate_five"];
					$_stock['stocks_products_rate_sex']           =       $_POST["stocks_products_rate_sex"];
					$edit = $setting_stock->setSettings_stocksInformation($_stock);
					if($edit == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"stocks",
								"mode" 		        => 	"edit_stock",
								"item" 	         	=> 	$mId,
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);

							header("Location:./settings_stocks.php?action=edit");
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
                    <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_ST_STOCKS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="stockSearchForm" method="get" action="./settings_stocks.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="stockSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->


         <!-- add/edit stock row -->
        <div class="row centerContent">
            <div class="col">
               <?php 
						if($edit == 1){
							echo alert_message("success",$lang['SETTINGS_ST_SUCCESS']);
						}elseif($edit == 'manager_phone'){
							echo alert_message("danger",$lang['SETTINGS_ST_MA_INSERT_BEFORE']);
						}elseif($edit == 'phone_two'){
							echo alert_message("danger",$lang['SETTINGS_ST_2_INSERT_BEFORE']);
						}elseif($edit == 'phone_one'){
							echo alert_message("danger",$lang['SETTINGS_ST_1_INSERT_BEFORE']);
						}elseif($edit == 'stocks_email'){
							echo alert_message("danger",$lang['SETTINGS_ST_EMAIL_INSERT_BEFORE']);
						}elseif($edit == 'stocks_name'){
							echo alert_message("danger",$lang['SETTINGS_ST_NAME_INSERT_BEFORE']);
						}
					?>
                <form method="post" id="addstocksForm" enctype="multipart/form-data">
                    <h5><?php echo $lang['SETTINGS_ST_EDIT_STOCKS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="stocks_name" value ="<?php if($_stock){echo $_stock['stocks_name'];}else{echo $u['stocks_name'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_ST_NAME'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_ADDRESS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="stocks_address" value ="<?php if($_stock){echo $_stock['stocks_address'];}else{echo $u['stocks_address'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_ADDRESS_HOLD'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_ST_PHONE_1'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="stocks_phone_one"
                                            placeholder="<?php echo $lang['SETTINGS_ST_PHONE_1'];?>" value ="<?php if($_stock){echo $_stock['stocks_phone_one'];}else{echo $u['stocks_phone_one'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_PHONE_2'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="stocks_phone_two"
                                            placeholder="<?php echo $lang['SETTINGS_ST_PHONE_2'];?>" value ="<?php if($_stock){echo $_stock['stocks_phone_two'];}else{echo $u['stocks_phone_two'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_MANGER_NAME'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="stocks_manager_name"
                                            placeholder="<?php echo $lang['SETTINGS_ST_MANGER_NAME'];?>" value ="<?php if($_stock){echo $_stock['stocks_manager_name'];}else{echo $u['stocks_manager_name'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_MANGER_PHONE'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="stocks_manager_phone"
                                            placeholder="<?php echo $lang['SETTINGS_ST_MANGER_PHONE'];?>" value ="<?php if($_stock){echo $_stock['stocks_manager_phone'];}else{echo $u['stocks_manager_phone'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_ST_MANGER_EMAIL'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="stocks_email" value ="<?php if($_stock){echo $_stock['stocks_email'];}else{echo $u['stocks_email'];}?>"
                                            placeholder="customer@abc.com">
                                    </div>
                                </div>
                            </div>
						</div>
                  
                    </div>
                    <!-- accordion row -->
                    <div class="row mt-5">
                        <div class="col">
                            <h5>  <?php echo $lang['SETTINGS_ST_PRODUCT'];?></h5>
                            <div id="accordion">
                               <?php 
									$stocks_products = [];
									if($u['stocks_products'])
									{
										foreach($u['stocks_products'] as $sid =>$sp)
										{
											echo '<div class="card">
													<div class="card-header main-bg-lgrd" id="heading_p'.$sp['stocks_products_product_id'].'">
														<h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapse_p'.$sp['stocks_products_product_id'].'"
															aria-expanded="true" aria-controls="collapse_p'.$sp['stocks_products_product_id'].'">
															'.get_data("settings_products","products_name","products_sn",$sp['stocks_products_product_id']).'
														</h5>
													</div>

													<div id="collapse_p'.$sp['stocks_products_product_id'].'" class="collapse " aria-labelledby="heading_p'.$sp['stocks_products_product_id'].'"
														data-parent="#accordion">
														<div class="card-body">
															<div class="darker-bg centerDarkerDiv formCenterDiv">
																<div class="row">
																	<div class="col">
																		<input class="customized-checkbox" id="customized-checkbox-p'.$sp['stocks_products_product_id'].'"
																			type="checkbox" name="check[]" value="'.$sp['stocks_products_product_id'].'" checked>
																		<label class="customized-checkbox-label" for="customized-checkbox-p'.$sp['stocks_products_product_id'].'">'.$lang['SETTINGS_ST_ACTIVE_PRODUCT'].'</label>
																	</div>
																	<input name="product[]" value="'.$sp['stocks_products_product_id'].'" hidden>	
																	<input name="stocks_products[]" value="'.$sp['stocks_products_sn'].'-'.$sp['stocks_products_product_id'].'" hidden>	

																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_1'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_one[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_1'].'" value ="';if($sp){echo $sp['stocks_products_rate_one'];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_2'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_two[]" placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_2'].'" value ="';if($sp){echo $sp['stocks_products_rate_two'];}echo'">
																			</div>
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_3'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_three[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_3'].'" value ="';if($sp){echo $sp['stocks_products_rate_three'];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_4'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_four[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_4'].'" value ="';if($sp){echo $sp['stocks_products_rate_four'];}echo'">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_5'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_five[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_5'].'" value ="';if($sp){echo $sp['stocks_products_rate_five'];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_6'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_sex[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_6'].'" value ="';if($sp){echo $sp['stocks_products_rate_sex'];}echo'">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>';
											$stocks_products[$sid] = $sp['stocks_products_product_id'];
										}
									}
									if($products)
									{
										foreach($products as $k => $p)
										{
											if(!in_array($p['products_sn'],$stocks_products))
											{
											echo '<div class="card">
													<div class="card-header main-bg-lgrd" id="heading'.$k.'">
														<h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapse'.$k.'"
															aria-expanded="true" aria-controls="collapse'.$k.'">
															'.$p['products_name'].'
														</h5>
													</div>

													<div id="collapse'.$k.'" class="collapse " aria-labelledby="heading'.$k.'"
														data-parent="#accordion">
														<div class="card-body">
															<div class="darker-bg centerDarkerDiv formCenterDiv">
																<div class="row">
																	<div class="col">
																		<input class="customized-checkbox" id="customized-checkbox-'.$p['products_sn'].'"
																			type="checkbox" name="check[]" value="'.$p['products_sn'].'" ';if($_stock){if($_stock['product'][$k] == $p['products_sn']){echo 'checked';}}echo'">
																		<label class="customized-checkbox-label" for="customized-checkbox-'.$p['products_sn'].'">'.$lang['SETTINGS_ST_ACTIVE_PRODUCT'].'</label>
																	</div>
																	<input name="product[]" value="'.$p['products_sn'].'" hidden>
																	<input name="stocks_products[]" value="0" hidden>	


																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_1'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_one[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_1'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_one'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_2'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_two[]" placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_2'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_two'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_3'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_three[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_3'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_three'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_4'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_four[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_4'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_four'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_5'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_five[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_5'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_five'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_ST_PRODUCT_TYPE_6'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="stocks_products_rate_sex[]"
																					placeholder="'.$lang['SETTINGS_ST_PRODUCT_TYPE_6'].'" value ="';if($_stock){echo $_stock['stocks_products_rate_sex'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>';
											}
										}
									}
								?>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end accordion row -->


                    <div class="row mt-2 mb-5">
                        <div class="col d-flex jSTtify-content-end">
                            <button class="btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit stock row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>

	
$(document).ready(function () {

    $('#addstocksForm').formValidation({
        excluded: [':disabled'],
        fields: {
            stocks_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_ST_INSERT_NAME'];?>'
                    }
                }
            },
            stocks_address: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_ST_INSERT_ADDRESS'];?>'
                    }
                }
            },
            stocks_phone_one: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
                    }
                }
            },
            stocks_phone_two: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
                    }
                }
            },
            stocks_manager_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_ST_IN_MANAGER'];?>'
                    }
                }
            },
            stocks_manager_phone: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
                    }
                }
            },
            stocks_email: {
                validators: {
                     notEmpty: {
                        message: '<?php echo $lang['SETTINGS_ST_IN_EMAIL'];?>'
                    },
                    emailAddress: {
                        message: '<?php echo $lang['SETTINGS_ST_IN_EMAIL_CORRECT'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // warehouseName input[name="warehouseName"]
        // warehousedescription input[name="warehousedescription"]
    })

    // warehouse search input name =>> warehousesSearch
})	


</SCRIPT>



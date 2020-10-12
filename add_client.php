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
	include("./inc/Classes/system-settings_clients.php");
	$setting_client = new systemSettings_clients();

	include("./inc/Classes/system-settings_products.php");
	$setting_product = new systemSettings_products();


    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_clients'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			$products   = $setting_product->getsiteSettings_products();


			if($_POST)
			{
				$_client['clients_name']                        =       sanitize($_POST["clients_name"]);
				$_client['clients_manager_name']                =       sanitize($_POST["clients_manager_name"]);
				$_client['clients_manager_email']               =       sanitize($_POST["clients_manager_email"]);
				$_client['clients_phone_one']                   =       sanitize($_POST["clients_phone_one"]);
				$_client['clients_phone_two']                   =       sanitize($_POST["clients_phone_two"]);
				$_client['clients_manager_phone']               =       sanitize($_POST["clients_manager_phone"]);
				$_client['clients_email']                       =       sanitize($_POST["clients_email"]);
				$_client['clients_address']                     =       sanitize($_POST["clients_address"]);
				$_client['check']                               =       $_POST["check"];
				$_client['product']                             =       $_POST["product"];
				$_client['clients_products_rate_0']             =       $_POST["clients_products_rate_0"];
				$_client['clients_products_rate_1']             =       $_POST["clients_products_rate_1"];
				$_client['clients_products_rate_2']             =       $_POST["clients_products_rate_2"];
				$_client['clients_products_rate_3']             =       $_POST["clients_products_rate_3"];
				$_client['clients_products_rate_4']             =       $_POST["clients_products_rate_4"];
				$_client['clients_products_rate_5']             =       $_POST["clients_products_rate_5"];
				$_client['clients_payments_days']               =       $_POST["clients_payments_days"];
				$_client['clients_payments_percent']            =       $_POST["clients_payments_percent"];
				
				if( $_FILES && ( $_FILES['clients_commercial_register']['name'] != "") && ( $_FILES['clients_commercial_register']['tmp_name'] != "" ) )
				{
					include_once("./inc/Classes/upload.class.php");
					$allow_ext = array("jpg","jpeg","gif","png");
					$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
					$files['name'] 	= addslashes($_FILES["clients_commercial_register"]["name"]);
					$files['type'] 	= $_FILES["clients_commercial_register"]['type'];
					$files['size'] 	= $_FILES["clients_commercial_register"]['size']/1024;
					$files['tmp'] 	= $_FILES["clients_commercial_register"]['tmp_name'];
					$files['ext']		= $upload->GetExt($_FILES["clients_commercial_register"]["name"]);
					$upfile	= $upload->Upload_File($files);
					if($upfile)
					{
						$_client['clients_commercial_register']  = $upfile['newname'];

					}
//					@unlink($path.$_POST['old_clients_commercial_register']);
				}
				if( $_FILES && ( $_FILES['clients_tex_card']['name'] != "") && ( $_FILES['clients_tex_card']['tmp_name'] != "" ) )
				{
					include_once("./inc/Classes/upload.class.php");
					$allow_ext = array("jpg","jpeg","gif","png");
					$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
					$files['name'] 	= addslashes($_FILES["clients_tex_card"]["name"]);
					$files['type'] 	= $_FILES["clients_tex_card"]['type'];
					$files['size'] 	= $_FILES["clients_tex_card"]['size']/1024;
					$files['tmp'] 	= $_FILES["clients_tex_card"]['tmp_name'];
					$files['ext']		= $upload->GetExt($_FILES["clients_tex_card"]["name"]);
					$upfile	= $upload->Upload_File($files);
					if($upfile)
					{
						$_client['clients_tex_card']  = $upfile['newname'];

					}
//					@unlink($path.$_POST['old_clients_tex_card']);
				}

				$add = $setting_client->addSettings_clients($_client);
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"clients",
							"mode" 		        => 	"add_clients",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_client.php?action=add");
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
                    <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_CL_CLIENTS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="clientSearchForm" method="get" action="./settings_clients.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="clientSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->


         <!-- add/edit client row -->
        <div class="row centerContent">
            <div class="col">
               <?php 
						if($add == 1){
							echo alert_message("success",$lang['SETTINGS_CL_SUCCESS']);
						}elseif($add == 'manager_phone'){
							echo alert_message("danger",$lang['SETTINGS_CL_MA_INSERT_BEFORE']);
						}elseif($add == 'phone_two'){
							echo alert_message("danger",$lang['SETTINGS_CL_2_INSERT_BEFORE']);
						}elseif($add == 'phone_one'){
							echo alert_message("danger",$lang['SETTINGS_CL_1_INSERT_BEFORE']);
						}elseif($add == 'clients_email'){
							echo alert_message("danger",$lang['SETTINGS_CL_EMAIL_INSERT_BEFORE']);
						}elseif($add == 'clients_name'){
							echo alert_message("danger",$lang['SETTINGS_CL_NAME_INSERT_BEFORE']);
						}
					?>
                <form method="post" id="addclientsForm" enctype="multipart/form-data">
                    <h5><?php echo $lang['SETTINGS_CL_ADD_clientS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="clients_name" value ="<?php if($_client){echo $_client['clients_name'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_CL_NAME'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_ADDRESS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="clients_address" value ="<?php if($_client){echo $_client['clients_address'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_ADDRESS_HOLD'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_CL_PHONE_1'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="clients_phone_one"
                                            placeholder="<?php echo $lang['SETTINGS_CL_PHONE_1'];?>" value ="<?php if($_client){echo $_client['clients_phone_one'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_PHONE_2'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="clients_phone_two"
                                            placeholder="<?php echo $lang['SETTINGS_CL_PHONE_2'];?>" value ="<?php if($_client){echo $_client['clients_phone_two'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_CL_EMAIL'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="clients_email"
                                            placeholder="customer@abc.com">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_MANGER_NAME'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="clients_manager_name"
                                            placeholder="<?php echo $lang['SETTINGS_CL_MANGER_NAME'];?>" value ="<?php if($_client){echo $_client['clients_manager_name'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_MANGER_PHONE'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="clients_manager_phone"
                                            placeholder="<?php echo $lang['SETTINGS_CL_MANGER_PHONE'];?>" value ="<?php if($_client){echo $_client['clients_manager_phone'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_MANGER_EMAIL'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="clients_manager_email" value ="<?php if($_client){echo $_client['clients_email'];}?>"
                                            placeholder="customer@abc.com">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_COM'];?></label>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
                                                <input type="file" class="form-control uploadimage" name="clients_commercial_register">
                                            </div>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
                                            <img src="<?php echo $path;?>/defaults/img-icon.png" height="50px" class="imagePreviewURL" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_CL_TEX'];?></label>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
                                                <input type="file" class="form-control uploadimage" name="clients_tex_card">
                                            </div>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
                                            <img src="<?php echo $path;?>/defaults/img-icon.png" height="50px" class="imagePreviewURL" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
                  
                    </div>
                    <!-- accordion row -->
                    <div class="row mt-5">
                        <div class="col">
                            <h5>  <?php echo $lang['SETTINGS_CL_PRODUCT'];?></h5>
                            <div id="accordion">
                               <?php 
									if($products)
									{
										foreach($products as $k => $p)
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
																			type="checkbox" name="check[]" value="'.$p['products_sn'].'" ';if($_client){if($_client['product'][$k] == $p['products_sn']){echo 'checked';}}echo'">
																		<label class="customized-checkbox-label" for="customized-checkbox-'.$p['products_sn'].'">'.$lang['SETTINGS_CL_ACTIVE_PRODUCT'].'</label>
																	</div>
																	<input name="product[]" value="'.$p['products_sn'].'" hidden>	

																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_1'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_0[]"
																					placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_1'].'" value ="';if($_client){echo $_client['clients_products_rate_0'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_2'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_1[]" placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_2'].'" value ="';if($_client){echo $_client['clients_products_rate_1'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_3'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_2[]"
																					placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_3'].'" value ="';if($_client){echo $_client['clients_products_rate_2'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_4'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_3[]"
																					placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_4'].'" value ="';if($_client){echo $_client['clients_products_rate_3'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_5'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_4[]"
																					placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_5'].'" value ="';if($_client){echo $_client['clients_products_rate_4'][$k];}echo'">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label class="col-xs-3">'.$lang['SETTINGS_CL_PRODUCT_TYPE_6'].'</label>
																			<div class="col-xs-5">
																				<input type="text" class="form-control" name="clients_products_rate_5[]"
																					placeholder="'.$lang['SETTINGS_CL_PRODUCT_TYPE_6'].'" value ="';if($_client){echo $_client['clients_products_rate_5'][$k];}echo'">
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
								
								
								?>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end accordion row -->
					<!-- payment method row -->
                    <div class="row mt-5">
                        <div class="col">
                            <h5>  <?php echo $lang['SETTINGS_CL_PAY_SYSTEM'];?></h5>
                            <div class="darker-bg centerDarkerDiv formCenterDiv">
                                <div class="row">
                                    <div class="col-md-5">
                                        <h5><?php echo $lang['SETTINGS_CL_PAY_DAY'];?></h5>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="00" name="clients_payments_days[]" value ="<?php if($_client){echo $_client['clients_payments_days'][0];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="00" name="clients_payments_days[]" value ="<?php if($_client){echo $_client['clients_payments_days'][1];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="00" name="clients_payments_days[]" value ="<?php if($_client){echo $_client['clients_payments_days'][2];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="00" name="clients_payments_days[]" value ="<?php if($_client){echo $_client['clients_payments_days'][3];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="00" name="clients_payments_days[]" value ="<?php if($_client){echo $_client['clients_payments_days'][4];}?>">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <h5><?php echo $lang['SETTINGS_CL_PRE_INVOCE'];?></h5>
                                        <div class="col-xs-5 ">
                                            <div class="form-group">
                                                <input type="text" class="form-control percentageInput" placeholder="00" name="clients_payments_percent[]" value ="<?php if($_client){echo $_client['clients_payments_percent'][0];}?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control percentageInput" placeholder="00" name="clients_payments_percent[]" value ="<?php if($_client){echo $_client['clients_payments_percent'][1];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control percentageInput" placeholder="00" name="clients_payments_percent[]" value ="<?php if($_client){echo $_client['clients_payments_percent'][2];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control percentageInput" placeholder="00" name="clients_payments_percent[]" value ="<?php if($_client){echo $_client['clients_payments_percent'][3];}?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control percentageInput" placeholder="00" name="clients_payments_percent[]" value ="<?php if($_client){echo $_client['clients_payments_percent'][4];}?>">
                                        </div>
                                        <small id="percentageErrorMsg" class="help-block"><?php echo $lang['SETTINGS_CL_MUST_100'];?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end payment method row -->


                    <div class="row mt-2 mb-5">
                        <div class="col d-flex jSTtify-content-end">
                            <button class="btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit client row -->
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
include './assets/layout/footer.php';?>
<SCRIPT>

	
$(document).ready(function () {

    $('#addclientsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            clients_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_CL_INSERT_NAME'];?>'
                    }
                }
            },
            clients_address: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_CL_INSERT_ADDRESS'];?>'
                    }
                }
            },
//            clients_phone_one: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
//                    },
//                    regexp: {
//                        regexp: /^01[0-2]{1}[0-9]{8}/,
//                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
//                    }
//                }
//            },
//            clients_phone_two: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
//                    },
//                    regexp: {
//                        regexp: /^01[0-2]{1}[0-9]{8}/,
//                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
//                    }
//                }
//            },
            clients_manager_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_CL_IN_MANAGER'];?>'
                    }
                }
            },
            clients_manager_phone: {
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
//            clients_email: {
//                validators: {
//                     notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_CL_IN_EMAIL'];?>'
//                    },
//                    emailAddress: {
//                        message: '<?php echo $lang['SETTINGS_CL_IN_EMAIL_CORRECT'];?>'
//                    }
//                }
//            },
//			clients_manager_email: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_US_IN_EMAIL'];?>'
//                    },
//                    emailAddress: {
//                        message: '<?php echo $lang['SETTINGS_US_IN_EMAIL_CORRECT'];?>'
//                    }
//                }
//            },
//            clients_commercial_register: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_CL_IN_TEX'];?>'
//                    }
//                }
//            },
//            clients_tex_card: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_CL_IN_COM'];?>'
//                    }
//                }
//            },
        }
    }).on('success.form.bv', function (e) {

        // warehouseName input[name="warehouseName"]
        // warehousedescription input[name="warehousedescription"]
    })
    // // customer search input name =>> customersSearch
    $('.percentageInput').keyup(function () {
        var sum = 0;
        $('.percentageInput').each(function () {
            if ($(this).val() != '') { sum += parseInt($(this).val()); }
        })
        if (sum != 100) { $('#percentageErrorMsg').css({ 'display': 'block' }); } else {
            $('#percentageErrorMsg').css({ 'display': 'none' }) }
    })
    // warehouse search input name =>> warehousesSearch
})	


</SCRIPT>



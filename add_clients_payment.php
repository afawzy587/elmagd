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
	include("./inc/Classes/system-clients_collectible.php");
	$pricing = new systemClients_collectible();

	include("./inc/Classes/system-settings_banks.php");
	$setting_bank = new systemSettings_banks();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['clients_payments'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['client']) != 0)
			{
				$mId =intval($_GET['client']);
				$banks_finance=$setting_bank->get_banks_finance_details();
				$banks        = $setting_bank->getaccountsSettings_banks();
				if($_POST)
				{
					$_payment['collectible_client_id']                =       $mId;
					$_payment['collectible_date']                     =       sanitize($_POST["collectible_date"]);
					$_payment['collectible_type']                     =       sanitize($_POST["collectible_type"]);
					$_payment['collectible_value']                    =       sanitize($_POST["collectible_value"]);
					$_payment['collectible_cheque_date']              =       sanitize($_POST["collectible_cheque_date"]);
					$_payment['collectible_cheque_number']            =       sanitize($_POST["collectible_cheque_number"]);
					$_payment['collectible_bank_id']                  =       sanitize($_POST["collectible_bank_id"]);
					$_payment['collectible_account_type']             =       sanitize($_POST["collectible_account_type"]);
					$_payment['collectible_account_id']               =       sanitize($_POST["collectible_account_id"]);

					$add = $pricing->add_clients_collectible($_payment,$_GET);
					if($add == 1)
					{
						
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"clients",
								"mode" 		        => 	"clients_payment",
								"item" 		        => 	$mId,
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);
							if(intval($_POST["add_other"]) == 1)
							{
								header("Location:./add_clients_payment.php?".$url_get_parts);
							}else{
								header("Location:./".$_SESSION['page']);
							}
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_CL_CLIENTS'];?></span>
                      <a class="blueSky" href="./client_search.php" ><strong> &gt; </strong> <?php echo $lang['SETTINGS_CL_CLIENTS_FINANCES'];?> </a>
                    <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_CL_CLIENT_COLLECT'];?> </span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
     

        <!-- add/edit product row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="customersAccountsPaymentForm" enctype="multipart/form-data">
                   
                
                     <!-- account details row -->
					<div class="row justify-content-center mb-5">
						<div class="col">
							<div class="row">
								<div class="col text-center">
									<h5> <?php echo $lang['SETTINGS_C_F_CLIENT_CREDIT'];?><strong class="blueSky"><?php echo get_data('settings_clients','clients_name','clients_sn',$mId)?></strong></h5>
									<h4 class="d-inline-block bg_text text_height <?php if(get_client_credit($mId) < 0){ echo 'warning';}?>  ltrDir"><?php echo get_client_credit($mId);?></h4>
									<!-- INFO ==> remove warning class for positive values  -->
									<!-- <h4 class="d-inline-block bg_text text_height ltrDir">- 10,000</h4>  -->
								</div>
							</div>

							<div class="row mt-5">
								<div class="col-md-2 text-center">
									<h5 class="d-inline-block bg_text2 text_height2 w-100"><?php echo $lang['SETTINGS_C_F_SAFE'];?></h5>
									<h5 class="d-inline-block bg_text2 text_height2 <?php if($companyinfo['companyinfo_opening_balance_safe'] < 0){echo 'warming';}?> w-100 ltrDir"> <?php echo number_format($companyinfo['companyinfo_opening_balance_safe']);?></h5>
								</div>
								<div class="col-md-2 text-center">
									<h5 class="d-inline-block bg_text2 text_height2 w-100"><?php echo $lang['SETTINGS_C_F_CHEQE_SAFE'];?></h5>
									<h5 class="d-inline-block bg_text2 text_height2  <?php if($companyinfo['companyinfo_opening_balance_cheques'] < 0){echo 'warming';}?> w-100 ltrDir">  <?php echo number_format($companyinfo['companyinfo_opening_balance_cheques']);?></h5>
								</div>
								<?php
									if ($banks_finance) {
										foreach ($banks_finance as $k => $f) {
											 $credit = $f['banks_finance_open_balance'] + $f['banks_total_with_benefits'];
											echo '<div class="col-md-2 text-center">
											<h5 class="d-inline-block bg_text2 text_height2 w-100">' . get_data('settings_banks','banks_name','banks_sn',$f['banks_finance_bank_id']) . ' - ';
											if($f['banks_finance_account_type'] == 'credit')
											{
												 echo get_data('settings_banks_credit','banks_credit_name','banks_credit_sn',$f['banks_finance_account_id']);
											}elseif($f['banks_finance_account_type'] == 'current'){
												echo $lang['SETTINGS_BAN_CURRENT'];
											}elseif($f['banks_finance_account_type'] == 'saving'){
												echo $lang['SETTINGS_BAN_SAVE'];
											}
											echo '</h5>
											<h5 class="d-inline-block bg_text2 text_height2 ';
											if ($f['credit'] < 0) {
												echo 'warning';
											}
											echo ' w-100 ltrDir">' . number_format($credit) . '</h5>
										</div>';

										$total_finance += $credit;
									}
								}
                        	?>
							</div>

							<div class="row mt-5">
								<div class="col text-center">
									<h5><?php echo $lang['SETTINGS_C_F_AVAL_CREDIT'];?></h5>
									<h4 class="d-inline-block bg_text text_height warning ltrDir"><strong><?php echo number_format($total_finance+$companyinfo['companyinfo_opening_balance_safe']+$companyinfo['companyinfo_opening_balance_cheques']);?></strong></h4>
									<!-- INFO ==> remove warning class for positive values  -->
									<!-- <h4 class="d-inline-block bg_text2 text_height2 ltrDir"> 10,000</h4>  -->
								</div>
							</div>
						</div>
					</div>
					<!-- end account details row -->
                    
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_CL_DATE_PAYMENT'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="collectible_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_TYPE'];?></label>
                                    <div class="col-xs-5  d-flex space_between">
                                        <!-- <div class="form-group"> -->
                                            <div class="form-check radioBtn d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="collectible_type" id="cashPaymentMethod" value="cash" 
                                                        >
                                                <label class="form-check-label" for="cashPaymentMethod">
                                                     <?php echo $lang['SETTINGS_C_F_PAYMENT_CASH'];?>
                                                </label>
                                            </div>
                                            <div class="form-check radioBtn d-inline-block">
                                                <input class="form-check-input" type="radio" checked
                                                    name="collectible_type" id="checkPaymentMethod"
                                                    value="cheque" >
                                                <label class="form-check-label "
                                                    for="checkPaymentMethod">
                                                    <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE'];?>
                                                </label>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_MONEY'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="collectible_value"
                                            placeholder="-----">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="collectible_cheque_date" class="form-control" id="check_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="collectible_cheque_number" id="check_number"
                                        placeholder="-----">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ADD_TO'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="banks">
                                            <select name="collectible_bank_id" class="bank form-control">
                                                <option selected disabled> <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK'];?></option>
                                                <option value="safe"><?php echo $lang['SETTINGS_C_F_SAFE']; ?></option>
                                                <?php 
													if($banks)
													{
														foreach($banks as $k => $b)
														{
															echo '<option value="'.$b['banks_sn'].'">'.$b['banks_name'].'</option>';
														}
													}
												
												?>
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE'];?></label>
                                    <div class="col-xs-5 ">
                                        <div class="select" id="type">
<!--                                           id="account_type"-->
                                            <select name="collectible_account_type" id="account_type" class="form-control" disabled>
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_BANK_FIRST'];?></option>
                                                <option value="credit"><?php echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];?></option>
                                                <option value="saving"><?php echo $lang['SETTINGS_BAN_SAVE'];?></option>
                                                <option value="current"><?php echo $lang['SETTINGS_BAN_CURRENT'];?></option>
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_T'];?></label>
                                    <div class="col-xs-5 ">
                                        <div class="select">
<!--                                           id="bank_item"-->
                                            <select name="collectible_account_id" class="form-control" id="bank_item" disabled > 
                                                <option selected disabled> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE_FRIST'];?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

					 <input type="text" class="form-control" name="add_other" hidden>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                           <span>
                                <button class="add_other btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_F_ANOTHER'];?></button>
                                <a href="">
                                    <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
                                </a>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit product row -->
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
include './assets/layout/footer.php'; ?>
<SCRIPT>
$(document).ready(function () {

    $('#customersAccountsPaymentForm').formValidation({
        excluded: [':disabled'],
        fields: {
            collectible_date: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_INSERT_DATE'];?>'
                    }
                }
            },
            collectible_type: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_INSERT_TYPE'];?>'
                    }
                }
            },
            collectible_value: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_INSERT_VALUE'];?> '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: '  <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            },
            collectible_cheque_date: {
                validators: {
                    notEmpty: {
                        message: '  <?php echo $lang['SETTINGS_C_F_CHEQUE_DATE'];?>'
                    }
                }
            },
            collectible_cheque_number: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM'];?>'
                    },
                    digits: {
                        message: ' <?php echo $lang['SETTINGS_C_F_NUMBER_ON'];?>'
                    }
                }
            },
            collectible_bank_id: {
                validators: {
                    notEmpty: {
                        message: '  <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK'];?>'
                    }
                }
            },
            collectible_account_type: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE'];?>'
                    }
                }
            },
            collectible_account_id: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN'];?>'
                    }
                }
            },

        }
    }).on('success.form.bv', function (e) {


    })

    $('input[name="collectible_type"]').on('change', function () {
        key = $(this).val();
        switch (key) {
            case 'cheque':
                var collectible_cheque_date = $('#check_date').prop("disabled", false);
                var collectible_cheque_number = $('#check_number').prop("disabled", false);
//                var collectible_account_type = $('#account_type').prop("disabled", false);
//                var collectible_account_id = $('#bank_item').prop("disabled", false);

                $('#customersAccountsPaymentForm')
                    .formValidation('addField', collectible_cheque_date, {
                        validators: {
                            notEmpty: {
                                message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE'];?>'
                            }
                        }
                    })
                    .formValidation('addField', collectible_cheque_number, {
                        validators: {
                            notEmpty: {
                                message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM'];?>'
                            },
                            digits: {
                                message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON'];?>'
                            }
                        }
                    })
//                    .formValidation('addField', collectible_account_type, {
//                        validators: {
//                            notEmpty: {
//                                message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE'];?>'
//                            }
//                        }
//                    })
//                    .formValidation('addField', collectible_account_id, {
//                        validators: {
//                            notEmpty: {
//                                message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN'];?>'
//                            }
//                        }
//                    })
                break;

            case 'cash':
                var collectible_cheque_date = $('#check_date').prop("disabled", true);
                var collectible_cheque_number = $('#check_number').prop("disabled", true);
//                var collectible_account_type = $('#account_type').prop("disabled", true);
//                var collectible_account_id = $('#bank_item').prop("disabled", true);

                collectible_cheque_date.siblings('.help-block').hide();
                collectible_cheque_number.siblings('.help-block').hide();
//                collectible_account_type.parent().siblings('.help-block').hide();
//                collectible_account_id.parent().siblings('.help-block').hide();

                $('#customersAccountsPaymentForm')
                    .formValidation('removeField', collectible_cheque_date)
                    .formValidation('removeField', collectible_cheque_number)
//                    .formValidation('removeField', collectible_account_type)
//                    .formValidation('removeField', collectible_account_id)
                break;

            default:
                break;
        }

    });
	
		$('#banks').on('change','select.bank',function(){
			var type = $(this).val();
            if (type != "safe") {
				var collectible_account_type =$('#account_type').prop("disabled", false);
			 	$('#customersAccountsPaymentForm').formValidation('addField', collectible_account_type, {
                        validators: {
                            notEmpty: {
                                message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE'];?>'
                            }
                        }
                    })
				var page = "bank_js.php?do=account";
				if (type) {
                    $.ajax({
                        type: 'POST',
                        url: page,
                        data: {
                            bank: type
                        },
                        success: function(html) {
                            $('#account_type').html(html);
                        }
                    });
                }
			}else{
				var expenses_bank_account_id =$('#bank_item').prop("disabled", true);
				 var expenses_account_type =$('#account_type').prop("disabled", true);
				$('#suppliersAccountsPaymentForm')
                    .formValidation('removeField', expenses_bank_account_id)
                    .formValidation('removeField', expenses_account_type)
			}
		});
			
			
			
		$('#type').on('change','select#account_type',function(){
			var type     = $(this).val();
			var id       = $('select.bank').val();
			if(type == 'credit')
			{
				var collectible_account_id = $('#bank_item').prop("disabled", false)
				collectible_account_id.parent().siblings('.help-block').hide();
				$('#customersAccountsPaymentForm').formValidation('removeField', collectible_account_id)
			}else{
				var collectible_account_id =$('#bank_item').prop("disabled", true)
				$('#customersAccountsPaymentForm').formValidation('addField', collectible_account_id, {
                        validators: {
                            notEmpty: {
                                message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN'];?>'
                            }
                        }
                    })
			}
			var page   ="client_pricing_js.php?do=bank_account";
			if(id){
				$.ajax({
					type:'POST',
					url:page,
					data:{id:id,type:type},
					success:function(html)
					{
					   $('select#bank_item').html(html); 
					}
					});
			}	
		});
})

</SCRIPT>



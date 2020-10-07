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
	include("./inc/Classes/system-expenses.php");
	$expenses = new systemExpenses();

	include("./inc/Classes/system-settings_banks.php");
	$setting_bank = new systemSettings_banks();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['expense'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u   = $expenses->getExpensesInformation($mId);
				$banks_finance=$setting_bank->get_banks_finance();
				$banks        = $setting_bank->getaccountsSettings_banks();
				$count    = $setting_bank->get_bank_account($u['expenses_bank_id']);
				if($_POST)
				{
					$_expense['expenses_sn']                       =       $mId;
					$_expense['expenses_date']                     =       sanitize($_POST["expenses_date"]);
					$_expense['expenses_type']                     =       sanitize($_POST["expenses_type"]);
					$_expense['expenses_amount']                   =       sanitize($_POST["expenses_amount"]);
					$_expense['expenses_cheque_date']              =       sanitize($_POST["expenses_cheque_date"]);
					$_expense['expenses_cheque_sn']                =       sanitize($_POST["expenses_cheque_sn"]);
					$_expense['expenses_bank_id']                  =       sanitize($_POST["expenses_bank_id"]);
					$_expense['expenses_bank_account_type']        =       sanitize($_POST["expenses_bank_account_type"]);
					$_expense['expenses_bank_account_id']          =       sanitize($_POST["expenses_bank_account_id"]);
					$_expense['expenses_title']                    =       sanitize($_POST["expenses_title"]);
					$edit = $expenses->setExpensesInformation($_expense);
					if($edit == 1)
					{

						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"expenses",
								"mode" 		        => 	"edit_expense",
								'expense'			=> $mId,
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);
							header("Location:./expenses.php?action=edit");
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
                    <a class="blueSky" href="./index.php"><?php echo $lang['SETTINGS_TITLE'];?> </a>
                    <span class="blueSky"><strong> &gt; </strong><?php echo $lang['OPERATIONS_NAV_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong><?php echo $lang['OPERATIONS_DROP_TITLE_2'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->

        <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
			   <form  method="get" action="./expenses.php"  enctype="multipart/form-data">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i
                                class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="<?php echo $lang['SEARCH_BY_CHEQUE'];?>" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form   method="post" id="addInternalExpensesForm" enctype="multipart/form-data">
                    <h5>  <?php echo $lang['EXPENCE_ADD'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_DATE_PAYMENT'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="expenses_date" class="form-control" value="<?php if($_expense){echo $_expense['expenses_date'];}else{echo $u['expenses_date'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PAYMENT_TYPE'];?></label>
                                    <div class="col-xs-5  d-flex space_between">
                                        <!-- <div class="form-group"> -->
                                        <div class="form-check radioBtn d-inline-block">
                                            <input class="form-check-input" type="radio" name="expenses_type"
                                                id="cashPaymentMethod" value="cash" <?php if($u['expenses_type']=='cash'){echo 'checked';}?>>
                                            <label class="form-check-label" for="cashPaymentMethod">
                                                <?php echo $lang['SETTINGS_C_F_PAYMENT_CASH'];?>
                                            </label>
                                        </div>
                                        <div class="form-check radioBtn d-inline-block">
                                            <input class="form-check-input" type="radio" name="expenses_type"
                                                id="checkPaymentMethod" value="cheque"  <?php if($u['expenses_type']=='cheque'){echo 'checked';}?>>
                                            <label class="form-check-label " for="checkPaymentMethod">
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
                                        <input type="text" class="form-control" name="expenses_amount" placeholder="0" value="<?php if($_expense){echo $_expense['expenses_amount'];}else{echo $u['expenses_amount'];}?>">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="expenses_cheque_date" class="form-control" id="check_date" <?php if($u['expenses_type']=='cash'){echo 'disabled';}?>  value="<?php if($_expense){echo $_expense['expenses_cheque_date'];}else{echo $u['expenses_cheque_date'];}?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="expenses_cheque_sn" value="<?php if($_expense){echo $_expense['expenses_cheque_sn'];}else{echo $u['expenses_cheque_sn'];}?>" id="check_number" <?php if($u['expenses_type']=='cash'){echo 'disabled';}?> 
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_ADD_TO'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="banks">
                                            <select name="expenses_bank_id" class="bank form-control">
                                                 <option selected disabled> <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK'];?></option>
                                                <option value="safe" <?php if( $u['expenses_in'] == "safe" ){echo 'selected';} ?> ><?php echo $lang['SETTINGS_C_F_SAFE']; ?></option>
                                                <?php 
													if($banks)
													{
														foreach($banks as $k => $b)
														{
															echo '<option value="'.$b['banks_sn'].'" '; if($b['banks_sn'] == $u['expenses_bank_id']){echo 'selected';} echo'>'.$b['banks_name'].'</option>';
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
                                            <select name="expenses_bank_account_type" class="form-control" id="account_type" <?php if($u['expenses_in'] =='safe'){echo 'disabled';}?> >
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_BANK_FIRST'];?></option>
                                                <option value="credit" <?php if($u['expenses_bank_account_type'] == 'credit'){echo 'selected';}?> ><?php echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];?></option>
                                                <option value="saving" <?php if($u['expenses_bank_account_type'] == 'saving'){echo 'selected';}?>><?php echo $lang['SETTINGS_BAN_SAVE'];?></option>
                                                <option value="current" <?php if($u['expenses_bank_account_type'] == 'current'){echo 'selected';}?>><?php echo $lang['SETTINGS_BAN_CURRENT'];?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_ACCOUNT_T'];?></label>
                                    <div class="col-xs-5 ">
                                        <div class="select">
                                            <select name="expenses_bank_account_id" class="form-control" id="bank_item" <?php if($u['expenses_bank_account_type'] !='credit'){echo 'disabled';}?>>
                                            	<?php
													
													if($u['expenses_bank_account_type'] == 'credit')
													{
														foreach($count as $k => $c)
														{
															echo '<option value="'.$c['banks_credit_sn'].'" '; if($c['banks_credit_sn'] == $u['expenses_bank_account_id']){echo 'selected';} echo'>'.$c['banks_credit_name'].'</option>';
														}
													}else{
														echo'<option selected disabled>'.$lang['SETTINGS_C_F_ACCOUNT_TYPE_FRIST'].'</option>';
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
                                    <label class="col-xs-3"> <?php echo $lang['EXPENCE_TITLE'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="expenses_title"
                                            placeholder="  <?php echo $lang['EXPENCE_TITLE'];?>" value="<?php if($_expense){echo $_expense['expenses_title'];}else{echo $u['expenses_title'];}?>">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
					<input type="text" class="form-control" name="add_other" hidden>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <span>
                                <a href="">
                                    <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
                                </a>
                            </span>
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
include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#addInternalExpensesForm').formValidation({
        excluded: [':disabled'],
        fields: {
            expenses_date: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_INSERT_DATE'];?>'
                    }
                }
            },
            expenses_type: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_INSERT_TYPE'];?>'
                    }
                }
            },
            expenses_amount: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_INSERT_VALUE'];?>'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            },
            expenses_cheque_date: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE'];?>'
                    }
                }
            },
            expenses_cheque_sn: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_NUM'];?>'
                    },
                    digits: {
                        message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON'];?>'
                    }
                }
            },
            expenses_bank_id: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_F_CHOOSE_BANK'];?>'
                    }
                }
            }
			,
//            expenses_bank_account_type: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE'];?>'
//                    }
//                }
//            },
//            expenses_bank_account_id: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_C_F_ACO_IN'];?>'
//                    }
//                }
//            },
            expenses_title: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['EXPENCE_TITLE_INSERT'];?>'
                    }
                }
            },
        }
    }).on('success.form.bv', function (e) {


    })

    $('.form-check-input').on('change', function () {
        key = $(this).val();
		console.log(key);
        switch (key) {
            case 'cheque':
                var expenses_cheque_date = $('#check_date').prop("disabled", false);
                var expenses_cheque_sn = $('#check_number').prop("disabled", false);
//                var expenses_bank_account_type = $('#account_type').prop("disabled", false);
//                var expenses_bank_account_id = $('#bank_item').prop("disabled", false);

                $('#suppliersAccountsPaymentForm')
                    .formValidation('addField', expenses_cheque_date, {
                        validators: {
                            notEmpty: {
                                message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE'];?> '
                            }
                        }
                    })
                    .formValidation('addField', expenses_cheque_sn, {
                        validators: {
                            notEmpty: {
                                message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_NUM'];?>'
                            },
                            digits: {
                                message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON'];?>'
                            }
                        }
                    })
//                    .formValidation('addField', expenses_bank_account_type, {
//                        validators: {
//                            notEmpty: {
//                                message: 'اختر نوع الحساب'
//                            }
//                        }
//                    })
//                    .formValidation('addField', expenses_bank_account_id, {
//                        validators: {
//                            notEmpty: {
//                                message: 'اختر الوعاء'
//                            }
//                        }
//                    })
                break;

            case 'cash':
                var expenses_cheque_date = $('#check_date').prop("disabled", true);
                var expenses_cheque_sn = $('#check_number').prop("disabled", true);
//                var expenses_bank_account_type = $('#account_type').prop("disabled", true);
//                var expenses_bank_account_id = $('#bank_item').prop("disabled", true);

                expenses_cheque_date.siblings('.help-block').hide();
                expenses_cheque_sn.siblings('.help-block').hide();
//                expenses_bank_account_type.parent().siblings('.help-block').hide();
//                expenses_bank_account_id.parent().siblings('.help-block').hide();

                $('#suppliersAccountsPaymentForm')
                    .formValidation('removeField', expenses_cheque_date)
                    .formValidation('removeField', expenses_cheque_sn)
//                    .formValidation('removeField', expenses_bank_account_type)
//                    .formValidation('removeField', expenses_bank_account_id)
                break;

            default:
                break;
        }

    });
	
	$('#banks').on('change','select.bank',function(){
		var type = $(this).val();
        if(type != "safe"){
                var expenses_account_type =$('#account_type').prop("disabled", false);
                $('#addInternalExpensesForm').formValidation('addField', expenses_account_type, {
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
				var expenses_account_type =$('#account_type').prop("disabled", true);
			}
		});
			
	$('#type').on('change','select#account_type',function(){
			var type     = $(this).val();
			var id       = $('select.bank').val();
			if(type == 'credit')
			{
				var expenses_bank_account_id = $('#bank_item').prop("disabled", false)
				expenses_bank_account_id.parent().siblings('.help-block').hide();
				$('#addInternalExpensesForm').formValidation('removeField', expenses_bank_account_id)
			}else{
				var expenses_bank_account_id =$('#bank_item').prop("disabled", true);
				 var expenses_account_type =$('#account_type').prop("disabled", true);
				$('#suppliersAccountsPaymentForm')
                    .formValidation('removeField', expenses_bank_account_id)
                    .formValidation('removeField', expenses_account_type)
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



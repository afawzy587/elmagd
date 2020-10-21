<?php
if (!isset($_SESSION)) {
    session_start();
}
// output buffer..
ob_start("ob_gzhandler");
// my system key cheker..
define("inside", true);
// get funcamental file which contain config and template files,settings.
include("./inc/fundamentals.php");
include("./inc/Classes/system-collect_returns.php");
$collect_return = new systemcollect_returns();

include("./inc/Classes/system-settings_banks.php");
$setting_bank = new systemSettings_banks();

include("./inc/Classes/system-settings_clients.php");
$setting_client = new systemSettings_clients();

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['colect_return'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
        $banks_finance = $setting_bank->get_banks_finance_details();
        $banks         = $setting_bank->getaccountsSettings_banks();
        $clients       = $setting_client->getsiteSettings_clients();
		$s_collect = intval($_GET['s_collect']);
		$c_collect = intval($_GET['c_collect']);
        if ($_POST) {
			$_collect_return['collect_returns_type']                     =       sanitize($_POST["collect_returns_type"]);
            $_collect_return['collect_returns_value']                    =       sanitize($_POST["collect_returns_value"]);
            $_collect_return['collect_returns_cheque_date']              =       sanitize($_POST["collect_returns_cheque_date"]);
            $_collect_return['collect_returns_cheque_number']            =       sanitize($_POST["collect_returns_cheque_number"]);
            $_collect_return['collect_returns_date']                     =       sanitize($_POST["collect_returns_date"]);
            $_collect_return['collect_returns_value']                    =       sanitize($_POST["collect_returns_value"]);
            $_collect_return['collect_returns_bank_id']                  =       sanitize($_POST["collect_returns_bank_id"]);
            $_collect_return['collect_returns_account_type']             =       sanitize($_POST["collect_returns_account_type"]);
            $_collect_return['collect_returns_account_id']               =       sanitize($_POST["collect_returns_account_id"]);
			if($s_collect > 0)
			{
				$_collect_return['collect_returns_person']   = 'supplier' ;
				$_collect_return['collect_id']   = $s_collect;
			}elseif($c_collect>0)
			{
			    $_collect_return['collect_returns_person']   = 'client' ;
				$_collect_return['collect_id']   = $c_collect;
			}
            $add = $collect_return->Add_collect_returns($_collect_return);
            if($add == 1)
            {

                $logs->addLog(NULL,
                    array(
                        "type" 		        => 	"users",
                        "module" 	        => 	"collect_returns",
                        "mode" 		        => 	"add_collect_returns",
                        "id" 	        	=>	$_SESSION['id'],
                    ),"admin",$_SESSION['id'],1
                    );
				
					header("Location:./".$_SESSION['page']);
                    
                    exit;
            }
        }
    }
}
include './assets/layout/header.php';

?>
<?php include './assets/layout/navbar.php'; ?>
<!-- page content -->
<div class="container mainPageContainer">

    <!-- links row -->
    <div class="row mt-5">
        <div class="col">
            <p class="blueSky">
                <i class="fas fa-info-circle"></i>
                <a class="blueSky" href="./index.php"><?php echo $lang['SETTINGS_TITLE']; ?></a>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_AND_SAVES']; ?> </span>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_collect_return']; ?> </span>
            </p>
        </div>
    </div>

    <!-- add/edit product row -->
    <div class="row centerContent">
        <div class="col">
            <!-- account details row -->
            <div class="row justify-content-center mb-5">
                <div class="col">
                    <div class="row mt-5">
                        <div class="col-md-2 text-center">
                            <h5 class="d-inline-block bg_text2 text_height2 w-100"><?php echo $lang['SETTINGS_C_F_SAFE']; ?></h5>
                            <h5 class="d-inline-block bg_text2 text_height2 <?php if ($companyinfo['companyinfo_opening_balance_safe'] < 0) {
                                                                                echo 'warming';
                                                                            } ?> w-100 ltrDir"> <?php echo number_format($companyinfo['companyinfo_opening_balance_safe']); ?></h5>
                        </div>
                        <div class="col-md-2 text-center">
                            <h5 class="d-inline-block bg_text2 text_height2 w-100"><?php echo $lang['SETTINGS_C_F_CHEQE_SAFE']; ?></h5>
                            <h5 class="d-inline-block bg_text2 text_height2  <?php if ($companyinfo['companyinfo_opening_balance_cheques'] < 0) {
                                                                                    echo 'warming';
                                                                                } ?> w-100 ltrDir"> <?php echo number_format($companyinfo['companyinfo_opening_balance_cheques']); ?></h5>
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

                    <div class="row justify-content-center mb-5">
                            <div class="col text-center">
                                <h5><?php echo $lang['SETTINGS_C_F_AVAL_CREDIT']; ?></h5>
                                <h4 class="d-inline-block bg_text text_height warning ltrDir"><strong><?php echo number_format($total_finance + $companyinfo['companyinfo_opening_balance_safe'] + $companyinfo['companyinfo_opening_balance_cheques']); ?></strong></h4>
                            </div>
                    </div>
                </div>
            </div>
            <!-- end account details row -->
            <form method="post" id="customersAccountsPaymentForm" enctype="multipart/form-data">
                <?php 
						if($_GET['action'] == 'add'){
							echo alert_message("success",$lang['collect_returnS_SUCCESS']);
						}
					?>
                <h5><?php echo $lang['BANKS_ADD_NEW_collect_return']; ?></h5>
                <div class="darker-bg centerDarkerDiv formCenterDiv">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_collect_return_DATE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="collect_returns_date" name="collect_returns_date" class="collect_returns_cut_precent form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_DEPOSIT_TYPE']; ?></label>
                                <div class="col-xs-5  d-flex space_between">
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="invoices  form-check-input" type="radio" name="collect_returns_type" id="cashPaymentMethod" value="cash">
                                        <label class="form-check-label" for="cashPaymentMethod">
                                            <?php echo $lang['SETTINGS_C_F_PAYMENT_CASH']; ?>
                                        </label>
                                    </div>
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="form-check-input" type="radio" checked name="collect_returns_type" id="checkPaymentMethod" value="cheque">
                                        <label class="form-check-label " for="checkPaymentMethod">
                                            <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text" class="form-control" name="collect_returns_cheque_number" id="check_number" placeholder="------">
                                </div>
                            </div>
                        </div>
<!--
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="collect_returns_cheque_date" name="collect_returns_cheque_date" class="collect_returns_cut_precent form-control">
                                </div>
                            </div>
                        </div>
-->
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ADD_TO']; ?></label>
                                <div class="col-xs-5">
                                    <div class="select" id="banks">
                                        <select name="collect_returns_bank_id" id="collect_returns_bank_id" class="bank invoices form-control">
                                            <option selected disabled> <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK']; ?></option>
                                            <option value="safe"><?php echo $lang['SETTINGS_C_F_SAFE']; ?></option>
                                            <?php
                                        if ($banks) {
                                            foreach ($banks as $k => $b) {
                                                echo '<option value="' . $b['banks_sn'] . '">' . $b['banks_name'] . '</option>';
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select" id="type">
                                        <select name="collect_returns_account_type" id="account_type" class="invoices form-control" disabled>
                                            <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_BANK_FIRST']; ?></option>
                                            <option value="credit"><?php echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU']; ?></option>
                                            <option value="saving"><?php echo $lang['SETTINGS_BAN_SAVE']; ?></option>
                                            <option value="current"><?php echo $lang['SETTINGS_BAN_CURRENT']; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_T']; ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select" id="bank_item">
                                        <select name="collect_returns_account_id" class="collect_returns_cut_precent invoices form-control" id="collect_returns_account_id" disabled>
                                            <option selected disabled> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE_FRIST']; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_MONEY']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text" class="collect_returns_cut_precent  form-control" name="collect_returns_value" id="collect_returns_value" placeholder="------"
                                    <?php if($s_collect > 0 ){ echo 'value="'.get_supplier_collect_value($s_collect).'" ';}?>readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"> -->
                    <div id="myModal" class="modal fade " role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content darker-bg">
                                <div class="modal-body" id="invoices_items">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <span>
                                <!-- <button class="add_other btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_F_ANOTHER']; ?></button> -->
                                <a href="">
                                    <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE']; ?></button>
                                </a>
                            </span>
                        </div>
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
    $(document).ready(function() {

        $('#customersAccountsPaymentForm').formValidation({
            excluded: [':disabled'],
            fields: {
                collect_returns_date: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_DATE']; ?>'
                        }
                    }
                },
               
                collect_returns_value: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_VALUE']; ?> '
                        },
                        regexp: {
                            regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                            message: '  <?php echo $lang['SETTINGS_C_MAX_NUM']; ?>'
                        }
                    }
                },
                collect_returns_bank_id: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK']; ?>'
                        }
                    }
                },
                collect_returns_account_type: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                },
                collect_returns_account_id: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                        }
                    }
                },
                collect_returns_cheque_date: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
                        }
                    }
                },
                collect_returns_cheque_number: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                        },
                        digits: {
                            message: ' <?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                        }
                    }
                },
            }
        }).on('success.form.bv', function(e) {


        })
		
		$('input[name="collect_returns_type"]').on('change', function() {
            key = $(this).val();
            switch (key) {
                case 'cheque':
//                    var collect_returns_cheque_date = $('#collect_returns_cheque_date').removeClass('form-control').addClass('collect_returns_cut_precent form-control').prop("disabled", false);
                    var collect_returns_cheque_number = $('#check_number').prop("disabled", false);
                    $('div#invoices').css("display", "none");
                    //                var collect_returns_account_type = $('#account_type').prop("disabled", false);
                    //                var collect_returns_account_id = $('#bank_item').prop("disabled", false);

                    $('#customersAccountsPaymentForm')
//                        .formValidation('addField', collect_returns_cheque_date, {
//                            validators: {
//                                notEmpty: {
//                                    message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
//                                }
//                            }
//                        })
                        .formValidation('addField', collect_returns_cheque_number, {
                            validators: {
                                notEmpty: {
                                    message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                                },
                                digits: {
                                    message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                                }
                            }
                        })
                    break;

                case 'cash':
//                    var collect_returns_cheque_date = $('#collect_returns_cheque_date').removeClass('collect_returns_cut_precent form-control').addClass('form-control').prop("disabled", true);
                    var collect_returns_cheque_number = $('#check_number').prop("disabled", true);

//                    collect_returns_cheque_date.siblings('.help-block').hide();
                    collect_returns_cheque_number.siblings('.help-block').hide();

                    $('#customersAccountsPaymentForm')
//                        .formValidation('removeField', collect_returns_cheque_date)
                        .formValidation('removeField', collect_returns_cheque_number)
                    break;

                default:
                    break;
            }

        });


        $('#banks').on('change', 'select.bank', function() {
            var type = $(this).val();
            if (type == "safe") {
                var transfer_account_type = $('#account_type').prop("disabled", true);
				var collect_returns_account_id = $('#collect_returns_account_id').prop("disabled", true);
            } else {
                var collect_returns_account_type = $('#account_type').prop("disabled", false);
                
                $('#customersAccountsPaymentForm').formValidation('addField', collect_returns_account_type, {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                }
																 )
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
            }

        });

        $('#type').on('change', 'select#account_type', function() {
            var type = $(this).val();
            var id = $('select.bank').val();
            if (type != 'credit') {
                var collect_returns_account_id = $('#collect_returns_account_id').prop("disabled",true )
                collect_returns_account_id.parent().siblings('.help-block').hide();
                $('#customersAccountsPaymentForm').formValidation('removeField', collect_returns_account_id)
            } else {
                var collect_returns_account_id = $('#collect_returns_account_id').prop("disabled",false)
                $('#customersAccountsPaymentForm').formValidation('addField', collect_returns_account_id, {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                        }
                    }
                })
            }
            var page = "client_pricing_js.php?do=bank_account";
            if (id) {
                $.ajax({
                    type: 'POST',
                    url: page,
                    data: {
                        id: id,
                        type: type
                    },
                    success: function(html) {
                        $('select#collect_returns_account_id').html(html);
                    }
                });
            }
        });
        function GetFormattedDate(d) {
            var todayTime = new Date(d);
            var month = (todayTime.getMonth() + 1);
            var day = (todayTime.getDate());
            var year = (todayTime.getFullYear());
            return month + "/" + day + "/" + year;
        }
    })
</SCRIPT>

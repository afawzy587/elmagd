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
include("./inc/Classes/system-deposits.php");
$deposit = new systemDeposits();

include("./inc/Classes/system-settings_banks.php");
$setting_bank = new systemSettings_banks();

include("./inc/Classes/system-settings_clients.php");
$setting_client = new systemSettings_clients();

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['bank_transfar'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
        $banks_finance = $setting_bank->get_banks_finance();
        $banks         = $setting_bank->getaccountsSettings_banks();
        $clients       = $setting_client->getsiteSettings_clients();
        if ($_POST) {
            $_deposit['deposits_date']                     =       sanitize($_POST["deposits_date"]);
            $_deposit['deposits_type']                     =       sanitize($_POST["deposits_type"]);
            $_deposit['deposits_value']                    =       sanitize($_POST["deposits_value"]);
            $_deposit['deposits_cheque_date']              =       sanitize($_POST["deposits_cheque_date"]);
            $_deposit['deposits_cheque_number']            =       sanitize($_POST["deposits_cheque_number"]);
            $_deposit['deposits_bank_id']                  =       sanitize($_POST["deposits_bank_id"]);
            $_deposit['deposits_account_type']             =       sanitize($_POST["deposits_account_type"]);
            $_deposit['deposits_account_id']               =       sanitize($_POST["deposits_account_id"]);
            $_deposit['deposits_client_id']                =       sanitize($_POST["deposits_client_id"]);
            $_deposit['deposits_product_id']               =       sanitize($_POST["deposits_product_id"]);
            $_deposit['deposits_cut_precent']              =       sanitize($_POST["deposits_cut_precent"]);
            $_deposit['deposits_cut_value']                =       sanitize($_POST["deposits_cut_value"]);
            $_deposit['deposits_days']                     =       sanitize($_POST["deposits_days"]);
            $_deposit['deposits_date_pay']                 =       sanitize($_POST["deposits_date_pay"]);
            $_deposit['invoices_id']                       =       $_POST["invoices_id"];
            $add = $deposit->Add_Deposits($_deposit);
            if ($add == 1) {

                $logs->addLog(
                    NULL,
                    array(
                        "type"                 =>     "users",
                        "module"             =>     "deposits",
                        "mode"                 =>     "add_deposits",
                        "id"                 =>    $_SESSION['id'],
                    ),
                    "admin",
                    $_SESSION['id'],
                    1
                );
                header("Location:./deposits.php");

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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_DEPOSIT']; ?> </span>
            </p>
        </div>
    </div>
    <!-- end links row -->
    <!-- search btn row -->
    <!-- <div class="row">
        <div class="col d-flex justify-content-end">
            <a href="./banks_operations_search.html">
                <button class="btn widerBtn searchbtn" type="submit"><?php echo $lang['SEARCH']; ?></button>
            </a>
        </div>
    </div> -->
    <!-- end search btn row -->
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
                                echo '<div class="col-md-2 text-center">
                                    <h5 class="d-inline-block bg_text2 text_height2 w-100">' . $f['banks_name'] . '</h5>
                                    <h5 class="d-inline-block bg_text2 text_height2 ';
                                if ($f['credit'] < 0) {
                                    echo 'warning';
                                }
                                echo 'w-100 ltrDir">' . number_format($f['credit']) . '</h5>
                                </div>';
                                $total_finance += $f['credit'];
                            }
                        }
                        ?>
                    </div>

                    <div class="row mt-5 align-items-center  justify-content-between">
                        <div class="col-md-6 align-items-start">
                            <div class="">
                                <h5><?php echo $lang['SETTINGS_C_F_AVAL_CREDIT']; ?></h5>
                                <h4 class="d-inline-block bg_text text_height warning ltrDir"><strong><?php echo number_format($total_finance + $companyinfo['companyinfo_opening_balance_safe'] + $companyinfo['companyinfo_opening_balance_cheques']); ?></strong></h4>
                            </div>

                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="deposits_list.php" class="btn roundedBtn">
                                <?php echo $lang['SHOW_DEPOSITS']; ?>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
            <!-- end account details row -->
            <form method="post" id="customersAccountsPaymentForm" enctype="multipart/form-data">

                <h5><?php echo $lang['BANKS_ADD_NEW_DEPOSIT']; ?></h5>
                <div class="darker-bg centerDarkerDiv formCenterDiv">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_DEPOSIT_DATE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="deposits_date" name="deposits_date" class="deposits_cut_precent form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_DEPOSIT_TYPE']; ?></label>
                                <div class="col-xs-5  d-flex space_between">
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="invoices  form-check-input" type="radio" name="deposits_type" id="cashPaymentMethod" value="cash">
                                        <label class="form-check-label" for="cashPaymentMethod">
                                            <?php echo $lang['SETTINGS_C_F_PAYMENT_CASH']; ?>
                                        </label>
                                    </div>
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="form-check-input" type="radio" checked name="deposits_type" id="checkPaymentMethod" value="cheque">
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
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_MONEY']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text" class="deposits_cut_precent  form-control" name="deposits_value" id="deposits_value" placeholder="------">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="deposits_cheque_date" name="deposits_cheque_date" class="deposits_cut_precent form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text" class="form-control" name="deposits_cheque_number" id="check_number" placeholder="------">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ADD_TO']; ?></label>
                                <div class="col-xs-5">
                                    <div class="select" id="banks">
                                        <select name="deposits_bank_id" id="deposits_bank_id" class="bank invoices form-control">
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
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select" id="type">
                                        <select name="deposits_account_type" id="account_type" class="invoices form-control" disabled>
                                            <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_BANK_FIRST']; ?></option>
                                            <option value="credit"><?php echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU']; ?></option>
                                            <option value="saving"><?php echo $lang['SETTINGS_BAN_SAVE']; ?></option>
                                            <option value="current"><?php echo $lang['SETTINGS_BAN_CURRENT']; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_ACCOUNT_T']; ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select" id="bank_item">
                                        <select name="deposits_account_id" class="deposits_cut_precent invoices form-control" id="deposits_account_id" disabled>
                                            <option selected disabled> <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE_FRIST']; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_CLI'] ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select">
                                        <select name="deposits_client_id" id="deposits_client_id" class="form-control">
                                            <option selected disabled><?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT']; ?></option>
                                            <?php
                                            if ($clients) {
                                                foreach ($clients as $cId => $c) {
                                                    echo '<option value="' . $c["clients_sn"] . '"';
                                                    if ($_bank) {
                                                        if ($c["clients_sn"] == $_bank['banks_credit_client'][0]) {
                                                            echo 'selected';
                                                        }
                                                    }
                                                    echo '>' . $c["clients_name"] . '</option>';
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
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PRODUCT'] ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select">
                                        <select name="deposits_product_id" id="deposits_product_id" class="form-control">
                                            <option selected disabled> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" id="invoices" style="display: none;">
                            <div class="form-group">
                                <!-- <label class="col-xs-3"><?php echo $lang['BANKS_DEPOSIT_INVOICES']; ?></label> -->
                                <div class="col-xs-5">
                                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo $lang['BANKS_DEPOSIT_INVOICES']; ?></button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="deposits_cut_precent" name="deposits_cut_precent" placeholder="0" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_CUT_VALUE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="deposits_cut_value" name="deposits_cut_value" placeholder="0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_REPAYMENT']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="deposits_days" name="deposits_days" placeholder="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['BANKS_DEPOSIT_BUY_DATE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="deposits_date_pay" name="deposits_date_pay" placeholder="0/00/0000" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control" name="add_other" hidden>
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
                deposits_date: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_DATE']; ?>'
                        }
                    }
                },
                deposits_type: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_TYPE']; ?>'
                        }
                    }
                },
                deposits_value: {
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
                deposits_cheque_date: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
                        }
                    }
                },
                deposits_cheque_number: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                        },
                        digits: {
                            message: ' <?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                        }
                    }
                },
                deposits_bank_id: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK']; ?>'
                        }
                    }
                },
                deposits_account_type: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                },
                deposits_account_id: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                        }
                    }
                },
                deposits_client_id: {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT']; ?>'
                        }
                    }
                },
                deposits_product_id: {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT']; ?>'
                        }
                    }
                },

            }
        }).on('success.form.bv', function(e) {


        })

        $('input[name="deposits_type"]').on('change', function() {
            key = $(this).val();
            switch (key) {
                case 'cheque':
                    var deposits_cheque_date = $('#deposits_cheque_date').removeClass('form-control').addClass('deposits_cut_precent form-control').prop("disabled", false);
                    var deposits_cheque_number = $('#check_number').prop("disabled", false);
                    $('div#invoices').css("display", "none");
                    //                var deposits_account_type = $('#account_type').prop("disabled", false);
                    //                var deposits_account_id = $('#bank_item').prop("disabled", false);

                    $('#customersAccountsPaymentForm')
                        .formValidation('addField', deposits_cheque_date, {
                            validators: {
                                notEmpty: {
                                    message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
                                }
                            }
                        })
                        .formValidation('addField', deposits_cheque_number, {
                            validators: {
                                notEmpty: {
                                    message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                                },
                                digits: {
                                    message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                                }
                            }
                        })
                    //                    .formValidation('addField', deposits_account_type, {
                    //                        validators: {
                    //                            notEmpty: {
                    //                                message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                    //                            }
                    //                        }
                    //                    })
                    //                    .formValidation('addField', deposits_account_id, {
                    //                        validators: {
                    //                            notEmpty: {
                    //                                message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                    //                            }
                    //                        }
                    //                    })
                    break;

                case 'cash':
                    var deposits_cheque_date = $('#deposits_cheque_date').removeClass('deposits_cut_precent form-control').addClass('form-control').prop("disabled", true);
                    var deposits_cheque_number = $('#check_number').prop("disabled", true);
                    //                var deposits_account_type = $('#account_type').prop("disabled", true);
                    //                var deposits_account_id = $('#bank_item').prop("disabled", true);

                    deposits_cheque_date.siblings('.help-block').hide();
                    deposits_cheque_number.siblings('.help-block').hide();
                    //                deposits_account_type.parent().siblings('.help-block').hide();
                    //                deposits_account_id.parent().siblings('.help-block').hide();

                    $('#customersAccountsPaymentForm')
                        .formValidation('removeField', deposits_cheque_date)
                        .formValidation('removeField', deposits_cheque_number)
                    //                    .formValidation('removeField', deposits_account_type)
                    //                    .formValidation('removeField', deposits_account_id)
                    break;

                default:
                    break;
            }

        });

        $('#banks').on('change', 'select.bank', function() {
            var type = $(this).val();
            if (type != "safe") {
                var deposits_account_type = $('#account_type').prop("disabled", false);
                $('#customersAccountsPaymentForm').formValidation('addField', deposits_account_type, {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                })
            }

        });

        $('#type').on('change', 'select#account_type', function() {
            var type = $(this).val();
            var id = $('select.bank').val();
            if (type == 'credit') {
                var deposits_account_id = $('#deposits_account_id').prop("disabled", false)
                deposits_account_id.parent().siblings('.help-block').hide();
                $('#customersAccountsPaymentForm').formValidation('removeField', deposits_account_id)
            } else {
                var deposits_account_id = $('#deposits_account_id').prop("disabled", true)
                $('#customersAccountsPaymentForm').formValidation('addField', deposits_account_id, {
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
                        $('select#deposits_account_id').html(html);
                    }
                });
            }
        });
        $('#customersAccountsPaymentForm').on('change', '.deposits_cut_precent', function() {
            if ($('.deposits_cut_precent').filter(function() {
                    return $.trim($(this).val()).length == 0
                }).length == 0) {
                var id = $('#deposits_account_id').val();
                var page = "bank_js.php?do=item_data";
                if (id) {
                    $.ajax({
                        type: 'POST',
                        url: page,
                        dataType: "json",
                        data: {
                            id: id
                        },
                        success: function(responce) {
                            if (responce !== "400") {
                                $('input#deposits_cut_precent').val(responce['banks_credit_cutting_ratio']);
                                if (responce['banks_credit_repayment_type'] == "day") {
                                    $('input#deposits_days').val(responce['banks_credit_repayment_period']);
                                    var deposits_date = $('input#deposits_date').val();
                                    var days = parseInt(responce['banks_credit_repayment_period']);
                                    var date = new Date(deposits_date);
                                    date.setDate(date.getDate() + days);
                                    var deposits_date = GetFormattedDate(date);
                                    $('input#deposits_date_pay').val(deposits_date);
                                } else {
                                    var deposits_date = $('input#deposits_date').val();
                                    var deposits_cheque_date = $('input#deposits_cheque_date').val();
                                    date1 = new Date(deposits_date);
                                    date2 = new Date(deposits_cheque_date);
                                    const diffTime = Math.abs(date2 - date1);
                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                    $('input#deposits_days').val(diffDays);
                                    $('input#deposits_date_pay').val(deposits_cheque_date);
                                }
                                var deposits_value = parseInt($('input#deposits_value').val());
                                var deposits_cut_value = (deposits_value * (parseInt(responce['banks_credit_cutting_ratio']) / 100));
                                console.log(deposits_cut_value);
                                $('input#deposits_cut_value').val(deposits_cut_value);

                            }
                        }
                    });
                }
            }
        })

        $('#deposits_client_id').change(function() {
            var id = $(this).val();
            var page = "client_pricing_js.php?do=client_product";
            if (id) {
                $.ajax({
                    type: 'POST',
                    url: page,
                    data: {
                        id: id
                    },
                    success: function(html) {
                        $('select#deposits_product_id').html(html);
                    }
                });
            }

        })

        $('#customersAccountsPaymentForm').on('change', '.invoices', function() {
            if ($('.invoices').filter(function() {
                    return $.trim($(this).val()).length == 0
                }).length == 0) {
                if ($('#cashPaymentMethod').is(':checked') && $('#cashPaymentMethod').val() == 'cash') {
                    $('div#invoices').css("display", "block");
                }
                var bank = $('#deposits_bank_id').val();
                var acount_type = $('#account_type').val();
                var acount_id = $('#deposits_account_id').val();
                var page = "bank_js.php?do=get_invoices";
                if (bank && acount_type && acount_id) {
                    $.ajax({
                        type: 'POST',
                        url: page,
                        data: {
                            bank: bank,
                            acount: acount_type,
                            acount_id: acount_id
                        },
                        success: function(html) {
                            console.log(html);
                            $('div#invoices_items').html(html);
                        }
                    });
                }

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
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
 $_SESSION['page']  = $basename;
include("./inc/Classes/system-money_transfers.php");
$transfer = new systemMoney_transfers();

include("./inc/Classes/system-settings_banks.php");
$setting_bank = new systemSettings_banks();

include("./inc/Classes/system-settings_clients.php");
$setting_client = new systemSettings_clients();

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['bank_transfer'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
        $banks_finance = $setting_bank->get_banks_finance_details();
        $banks         = $setting_bank->getaccountsSettings_banks();
        $clients       = $setting_client->getsiteSettings_clients();
        if ($_POST) {
            $_transfer['transfers_date']                     =       sanitize($_POST["transfers_date"]);
            $_transfer['transfers_from']                     =       sanitize($_POST["transfer_from"]);
            $_transfer['transfers_account_type_from']        =       sanitize($_POST["transfer_account_type_from"]);
            $_transfer['transfers_account_id_from']          =       sanitize($_POST["transfer_account_id_from"]);
            $_transfer['transfers_client_id_from']           =       sanitize($_POST["transfer_client_id_from"]);
            $_transfer['transfers_product_id_from']          =       sanitize($_POST["transfer_product_id_from"]);
            $_transfer['transfers_value']                    =       sanitize($_POST["transfer_value"]);
            $_transfer['transfers_type']                     =       sanitize($_POST["transfer_type"]);
            $_transfer['transfers_cheque_date']              =       sanitize($_POST["transfer_cheque_date"]);
            $_transfer['transfers_cheque_number']            =       sanitize($_POST["transfer_cheque_number"]);
            $_transfer['transfers_to']                       =       sanitize($_POST["transfer_to"]);
            $_transfer['transfers_account_type_to']          =       sanitize($_POST["transfer_account_type_to"]);
            $_transfer['transfers_account_id_to']            =       sanitize($_POST["transfer_account_id_to"]);
            $_transfer['transfers_client_id_to']             =       sanitize($_POST["transfer_client_id_to"]);
            $_transfer['transfers_product_id_to']            =       sanitize($_POST["transfer_product_id_to"]);
            $_transfer['transfers_cut_precent']              =       sanitize($_POST["transfer_cut_precent"]);
            $_transfer['transfers_cut_value']                =       sanitize($_POST["transfer_cut_value"]);
            $_transfer['transfers_days']                     =       sanitize($_POST["transfer_days"]);
            $_transfer['transfers_text']                     =       sanitize($_POST["transfers_text"]);
            $_transfer['transfers_date_pay']                 =       format_data_base($_POST["transfers_date_pay"]);
            $_transfer['invoices_id']                        =       $_POST["invoices_id"][0];
            $add = $transfer->Add_Money_Transfer($_transfer);
             if ($add == 1) {

                 $logs->addLog(
                     NULL,
                     array(
                         "type"                 =>     "users",
                         "module"               =>     "transfer_money",
                         "mode"                 =>     "add_transfer",
                         "id"                   =>    $_SESSION['id'],
                     ),
                     "admin",
                     $_SESSION['id'],
                     1
                 );
                 header("Location:./transfers.php?action=add");

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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_TRANSFAR']; ?> </span>
            </p>
        </div>
    </div>
    <!-- search btn row -->
        <div class="row">
            <div class="col d-flex justify-content-end">
                <a href="./transfers_search.php">
                    <button class="btn widerBtn searchbtn" ><?php echo $lang['SEARCH']; ?></button>
                </a>
            </div>
        </div>
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

                    <div class="row mt-5 align-items-center  justify-content-between">
                        <div class="col-md-6 align-items-start">
                            <div class="">
                                <h5><?php echo $lang['SETTINGS_C_F_AVAL_CREDIT']; ?></h5>
                                <h4 class="d-inline-block bg_text text_height warning ltrDir"><strong><?php echo number_format($total_finance + $companyinfo['companyinfo_opening_balance_safe'] + $companyinfo['companyinfo_opening_balance_cheques']); ?></strong></h4>
                            </div>

                        </div>
                        <div class="col-md-3 d-flex justify-content-end">
                            <a href="deposits_list.php" class="btn roundedBtn">
                                <?php echo $lang['SHOW_DEPOSITS'];?>
                            </a>
                        </div>
                        <div class="col-md-3 d-flex justify-content-end">
                            <a href="transfers.php" class="btn roundedBtn">
                                <?php echo $lang['TRANSFER_LIST']; ?>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
            <!-- end account details row -->
            <form method="post" id="customersAccountsPaymentForm" enctype="multipart/form-data">
                   <?php 
						if($_GET['action']=="add"){
							echo alert_message("success",$lang['TRANSFER_SUCCESS']);
						}
					?>
                <h5><?php echo $lang['TRANSFER_ADD_NEW']; ?></h5>
                <div class="darker-bg centerDarkerDiv formCenterDiv">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['TRANSFER_DATE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="transfers_date" name="transfers_date" class="transfer_cut_precent form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['TRANSFER_FROM']; ?></label>
                                <div class="col-xs-5">
                                    <div class="select">
                                        <select name="transfer_from" id="transfer_from" class="bank invoices max_value form-control">
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
                                    <div class="select type">
                                        <select name="transfer_account_type_from" id="account_type_from" class="invoices account_type max_value form-control" disabled>
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
                                    <div class="select bank_item">
                                        <select name="transfer_account_id_from" class="invoices max_value form-control" id="transfer_account_id_from" disabled>
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
                                        <select name="transfer_client_id_from" id="transfer_client_id_from" class="transfer_client form-control">
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
                                        <select name="transfer_product_id_from" id="transfer_product_id_from" class="form-control">
                                            <option selected disabled> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['transfer_TYPE']; ?></label>
                                <div class="col-xs-5  d-flex space_between">
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="max_value form-check-input" type="radio" name="transfer_type" id="cashPaymentMethod" value="cash">
                                        <label class="form-check-label" for="cashPaymentMethod">
                                            <?php echo $lang['SETTINGS_C_F_PAYMENT_CASH']; ?>
                                        </label>
                                    </div>
                                    <div class="form-check radioBtn d-inline-block">
                                        <input class="max_value form-check-input" type="radio" checked name="transfer_type" id="checkPaymentMethod" value="cheque">
                                        <label class="form-check-label " for="checkPaymentMethod">
                                            <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" id="invoices" style="display: none;">
                            <div class="form-group">
                                <label class="col-xs-3"><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo $lang['INVOICES']; ?></button></label>
                                <div class="col-xs-5">
                                    <input type="number" name="max_value" id="max_value" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_MONEY']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text"  class="transfer_cut_precent  form-control" name="transfer_value" id="transfer_value" placeholder="------">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM']; ?></label>
                                <div class="col-xs-5">
                                    <input type="text" class="form-control" name="transfer_cheque_number" id="check_number" placeholder="------">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE']; ?></label>
                                <div class="col-xs-5">
                                    <input type="date" id="transfer_cheque_date" name="transfer_cheque_date" class="transfer_cut_precent form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['TRANSFER_TO']; ?></label>
                                <div class="col-xs-5">
                                    <div class="select">
                                        <select name="transfer_to" id="transfer_to" class="bank  form-control">
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
                                    <div class="select type">
                                        <select name="transfer_account_type_to" id="account_type_to" class="account_type form-control" disabled>
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
                                    <div class="select bank_item">
                                        <select name="transfer_account_id_to" class="transfer_cut_precent  form-control" id="transfer_account_id_to" disabled>
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
                                        <select name="transfer_client_id_to" id="transfer_client_id_to" class="transfer_client form-control">
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
                                        <select name="transfer_product_id_to" id="transfer_product_id_to" class="form-control">
                                            <option selected disabled> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['TRANSFER_TEXT'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="transfers_text"
                                            placeholder="  <?php echo $lang['TRANSFER_TEXT'];?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row justify-content-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="transfer_cut_precent" name="transfer_cut_precent" placeholder="0" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"> <?php echo $lang['BANKS_CUT_VALUE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="transfer_cut_value" name="transfer_cut_value" placeholder="0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_REPAYMENT']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="transfer_days" name="transfer_days" placeholder="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['BANKS_DEPOSIT_BUY_DATE']; ?></label>
                                <div class="col-xs-5 ">
                                    <input type="text" class="form-control" id="transfers_date_pay" name="transfers_date_pay" placeholder="0/00/0000" readonly>
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
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close </button>
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
                transfers_date: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_DATE']; ?>'
                        }
                    }
                },
                transfer_type: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_INSERT_TYPE']; ?>'
                        }
                    }
                },
                transfer_value: {
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
                transfer_cheque_date: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
                        }
                    }
                },
                transfer_cheque_number: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                        },
                        digits: {
                            message: ' <?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                        }
                    }
                },
                transfer_from: {
                    validators: {
                        notEmpty: {
                            message: '  <?php echo $lang['SETTINGS_C_F_CHOOSE_BANK']; ?>'
                        }
                    }
                },
                transfer_account_type_from: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                },
                transfer_account_type_to: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                },
                transfer_account_id_from: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                        }
                    }
                },
                transfer_account_id_to: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                        }
                    }
                }
             
                

            }
        }).on('success.form.bv', function(e) {


        })

        $('input[name="transfer_type"]').on('change', function() {
            key = $(this).val();
            switch (key) {
                case 'cheque':
                    var transfer_cheque_date = $('#transfer_cheque_date').removeClass('form-control').addClass('transfer_cut_precent form-control').prop("disabled", false);
                    var transfer_cheque_number = $('#check_number').prop("disabled", false);
                    //                var transfer_account_type = $('#account_type').prop("disabled", false);
                    //                var transfer_account_id = $('#bank_item').prop("disabled", false);

                    $('#customersAccountsPaymentForm')
                        .formValidation('addField', transfer_cheque_date, {
                            validators: {
                                notEmpty: {
                                    message: '<?php echo $lang['SETTINGS_C_F_CHEQUE_DATE']; ?>'
                                }
                            }
                        })
                        .formValidation('addField', transfer_cheque_number, {
                            validators: {
                                notEmpty: {
                                    message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                                },
                                digits: {
                                    message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                                }
                            }
                        })
                    //                    .formValidation('addField', transfer_account_type, {
                    //                        validators: {
                    //                            notEmpty: {
                    //                                message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                    //                            }
                    //                        }
                    //                    })
                    //                    .formValidation('addField', transfer_account_id, {
                    //                        validators: {
                    //                            notEmpty: {
                    //                                message: ' <?php echo $lang['SETTINGS_C_F_ACO_IN']; ?>'
                    //                            }
                    //                        }
                    //                    })
                    break;

                case 'cash':
                    var transfer_cheque_date = $('#transfer_cheque_date').removeClass('transfer_cut_precent form-control').addClass('form-control').prop("disabled", true);
                    var transfer_cheque_number = $('#check_number').prop("disabled", true);
                    //                var transfer_account_type = $('#account_type').prop("disabled", true);
                    //                var transfer_account_id = $('#bank_item').prop("disabled", true);

                    transfer_cheque_date.siblings('.help-block').hide();
                    transfer_cheque_number.siblings('.help-block').hide();
                    //                transfer_account_type.parent().siblings('.help-block').hide();
                    //                transfer_account_id.parent().siblings('.help-block').hide();

                    $('#customersAccountsPaymentForm')
                        .formValidation('removeField', transfer_cheque_date)
                        .formValidation('removeField', transfer_cheque_number)
                    //                    .formValidation('removeField', transfer_account_type)
                    //                    .formValidation('removeField', transfer_account_id)
                    break;

                default:
                    break;
            }

        });
        $('.bank').change(function() {
            var id = $(this).attr('id').replace('transfer_', '');
			var bank_id = $(this).val();
            var type = $(this).val();
            if (type == "safe") {
                var transfer_account_type = $('#account_type_' + id).prop("disabled", true);
                var transfer_account_id = $('#transfer_account_id_' + id).prop("disabled", true);
            } else {
                var transfer_account_type = $('#account_type_' + id).prop("disabled", false);
                $('#customersAccountsPaymentForm').formValidation('addField', transfer_account_type, {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $lang['SETTINGS_C_F_ACCOUNT_TYPE']; ?>'
                        }
                    }
                })
				 var page = "bank_js.php?do=account";
				if (bank_id) {
                    $.ajax({
                        type: 'POST',
                        url: page,
                        data: {
                            bank: bank_id
                        },
                        success: function(html) {
                            $('#account_type_' + id).html(html);
                        }
                    });
                }

            }

        });

        $('.type').on('change', 'select.account_type', function() {
            var type = $(this).val();
            var type_id = $(this).attr('id').replace('account_type_', '');
            var id = $('select#transfer_'+type_id).val();
            if (type == 'credit') {
                var transfer_account_id = $('#transfer_account_id_' + type_id).prop("disabled", false)
                transfer_account_id.parent().siblings('.help-block').hide();
                $('#customersAccountsPaymentForm').formValidation('removeField', transfer_account_id)
            } else {
                var transfer_account_id = $('#transfer_account_id_' + type_id).prop("disabled", true)
                $('#customersAccountsPaymentForm').formValidation('addField', transfer_account_id, {
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
                        $('select#transfer_account_id_' + type_id).html(html);
                    }
                });
            }
        });
        $('#customersAccountsPaymentForm').on('change', '.transfer_cut_precent', function() {
            if ($('.transfer_cut_precent').filter(function() {
                    return $.trim($(this).val()).length == 0
                }).length == 0) {
                var id = $('#transfer_account_id_to').val();
                console.log(id)
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
                            if (responce != "400") {
                                $('input#transfer_cut_precent').val(responce['banks_credit_cutting_ratio']);
                                if (responce['banks_credit_repayment_type'] == "day") {
                                    $('input#transfer_days').val(responce['banks_credit_repayment_period']);
                                    var transfers_date = $('input#transfers_date').val();
                                    var days = parseInt(responce['banks_credit_repayment_period']);
                                    var date = new Date(transfers_date);
                                    date.setDate(date.getDate() + days);
                                    var transfers_date = GetFormattedDate(date);
                                    $('input#transfers_date_pay').val(transfers_date);
                                } else {
                                    var transfers_date = $('input#transfers_date').val();
                                    var transfer_cheque_date = $('input#transfer_cheque_date').val();
                                    date1 = new Date(transfers_date);
                                    date2 = new Date(transfer_cheque_date);
                                    const diffTime = Math.abs(date2 - date1);
                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                    $('input#transfer_days').val(diffDays);
                                    $('input#transfers_date_pay').val(transfer_cheque_date);
                                }
                                var transfer_value = parseInt($('input#transfer_value').val());
                                var transfer_cut_value = (transfer_value * (parseInt(responce['banks_credit_cutting_ratio']) / 100));
                                $('input#transfer_cut_value').val(transfer_cut_value);

                            }
                        }
                    });
                }
            }
        })

        $('.transfer_client').change(function() {
            var type = $(this).attr('id').replace('transfer_client_id_', '');
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
                        $('select#transfer_product_id_' + type).html(html);
                    }
                });
            }

        })

        $('#customersAccountsPaymentForm').on('change', '.invoices', function() {
            if ($('.invoices').filter(function() {
                    return $.trim($(this).val()).length == 0
                }).length == 0) {
                $('div#invoices').css("display", "block");
                var bank = $('#transfer_from').val();
                var acount_type = $('#account_type_from').val();
                var acount_id = $('#transfer_account_id_from').val();
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

        $('#invoices_items').on('click', "input:checkbox", function() {

            var $box = $(this);
            if ($box.is(":checked")) {
                // the name of the box is retrieved using the .attr() method
                // as it is assumed and expected to be immutable
                var group = "input:checkbox[name='" + $box.attr("name") + "']";
                // the checked state of the group/box on the other hand will change
                // and the current value is retrieved using .prop() method
                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
            var id = $(this).attr('id').replace('customized-checkbox-', '');
            var max_value = $('input#max_' + id).val();
            $('#max_value').val(max_value);
           valitate_transfer_value(max_value);
			
        });
		$('#customersAccountsPaymentForm').on('change', '.max_value', function() {
			var bank            = $('#transfer_from').val();
			var account_type    = $('#account_type_from').val();
			var account_id      = $('#transfer_account_id_from').val();
			var transfer_type   = $('input[name="transfer_type"]:checked').val();
			var page = "bank_js.php?do=max_value";
			if (bank && transfer_type) {
				$.ajax({
					type: 'POST',
					url: page,
					data: {
						bank: bank,
						transfer_type: transfer_type,
						account_type: account_type,
						account_id: account_id
					},
					success: function(responce) {
						valitate_transfer_value(responce);
					}
				});
			}

		});
		function valitate_transfer_value(max_value){
			var transfer_value = $('#transfer_value');
			$('#customersAccountsPaymentForm').formValidation('addField', transfer_value, {
                            validators: {
                                notEmpty: {
                                    message: ' <?php echo $lang['SETTINGS_C_F_CHEQUE_NUM']; ?>'
                                },
                                digits: {
                                    message: '<?php echo $lang['SETTINGS_C_F_NUMBER_ON']; ?>'
                                },between: {
									min: 1,
									max: max_value,
									message: '<?php echo $lang['VALUE_LESS_THAN']; ?> ' +max_value+ '<?php echo $lang['VALUE_GREATER_THAN']; ?>' + 0 ,

								}
                            }
                        })
		}
    })
</SCRIPT>

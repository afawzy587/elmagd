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
include("./inc/Classes/system-money_transfers.php");
$trensfer = new systemMoney_transfers();

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['deposit_check'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {

		if($_GET){
			$search['id'] 		                 = intval($_GET['id']);
			$search['startDate'] 		         = sanitize($_GET['startDate']);
			$search['endDate'] 		             = sanitize($_GET['endDate']);
			$search['client_id'] 		         = intval($_GET['client_id']);
			$search['prroduct_client_id'] 		 = intval($_GET['prroduct_client_id']);
			$search['startValue']                = sanitize($_GET['startValue']);
			$search['endValue']                  = sanitize($_GET['endValue']);
			$search['bank'] 		             = sanitize($_GET['bank']);
			$search['cheque'] 		             = sanitize($_GET['cheque']);
			$search['bank_to'] 		             = sanitize($_GET['bank_to']);
			$search['account_to'] 		         = sanitize($_GET['account_to']);
			$search['account'] 		             = intval($_GET['account']);

		}
        //			include("./inc/Classes/pager.class.php");
        //			$page;
        //			$pager       = new pager();
        //			$page 		 = intval($_GET['page']);
        //			$total       = $setting_department->getTotalSettings_money_transfers($q);
        //			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"settings_money_transfers.php".$search.$paginationAddons,$paginationDialm);
        //			$thispage    = $pager->getPage();
        //			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
        //			$pager       = $pager->getAnalysis();
        $money_transfers   = $trensfer->getsiteMoney_transfers($limitmequry,$search);

        $logs->addLog(
            NULL,
            array(
                "type"               =>     "admin",
                "module"             =>     "money_transfers",
                "mode"               =>     "list",
                "total"              =>     $total,
                "id"                 =>    $_SESSION['id'],
            ),
            "admin",
            $_SESSION['id'],
            1
        );
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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['TRANSFER_LIST']; ?> </span>
            </p>
        </div>
    </div>
    <!-- end links row -->
    <!-- search btn row -->
        <div class="row">
            <div class="col d-flex justify-content-end">
                <a href="./transfers_search.php">
                    <button class="btn widerBtn searchbtn" ><?php echo $lang['SEARCH']; ?></button>
                </a>
            </div>
        </div>
    <!-- end search btn row -->

    <!-- table row -->
    <div class="row">
        <div class="col">
            <table class="table table-fluid " id="departmentTable">
                <?php
                if (empty($money_transfers)) {
                    echo "<tr><th colspan=\"5\">" . $lang['SETTINGS_NO_ITEMS'] . "</th></tr>";
                } else {
                    echo '
						<thead>
                            <tr>
                                <th>' . $lang['OPERATIONS_DATE'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_PAYMENT_MONEY'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_PAYMENT_TYPE'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'] . '</th>
                                <th>' . $lang['TRANSFER_FROM'] . '</th>
                                <th>' . $lang['TRANSFER_TO'] . '</th>
                                <th>' . $lang['TRANSFER_INVOICE'] . '</th>
                                <th>' . $lang['TRANSFER_TEXT'] . '</th>
                                <th style="width: 2rem;">' . $lang['DEPOSITS_BANK_APPROVE'] . '</th>
                             </tr>
						</thead>
						<tbody>';
                    foreach ($money_transfers as $k => $u) {
                        echo '<tr id=tr_' . $u['transfers_sn'] . '>
							    <td>' . _date_format($u['transfers_date']) . '</td>
                                <td>' . $u['transfers_value'] . '</td>
								<td>';
                        echo $u['transfers_type'] == "cash" ? $lang['SETTINGS_C_F_PAYMENT_CASH'] : $lang['SETTINGS_C_F_PAYMENT_CHEQUE'];
                        echo '</td>
                                <td>';
                        echo $u['transfers_cheque_number'] != "" ? $u['transfers_cheque_number'] : '-------';
                        echo '</td>
                               <td>';
                        echo  $u['transfers_cheque_date'] != "0000-00-00" ? _date_format($u['transfers_cheque_date'])  : '-------';
                        echo '</td>
                               <td>';
                        echo  $u['transfers_from_in'] == "safe" ? $lang['SETTINGS_C_F_SAFE']  : get_data('settings_banks', 'banks_name', 'banks_sn', $u['transfers_from']);
							 if ($u['transfers_from_in'] == "bank") {
								 echo '<br/>'.$lang['SETTINGS_BAN_ACCONT_TYPE'].' / ';
								if ($u['transfers_account_type_from'] == "credit") {
									echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];
								} elseif ($u['transfers_account_type_from'] == "current") {
									echo $lang['SETTINGS_BAN_CURRENT'];
								} elseif ($u['transfers_account_type_from'] == "saving") {
									echo $lang['SETTINGS_BAN_SAVE'];
								}
							} 
						
							if ($u['transfers_from_in'] == "bank") {
								if ($u['transfers_account_type_from'] == "credit") {
									echo '<br/>'.$lang['SETTINGS_C_F_ACCOUNT_T'].' / '.get_data('settings_banks_credit', 'banks_credit_name', 'banks_credit_sn', $u['transfers_account_id_from']);
								} 
							} 
             
                        
                        echo '</td>
						       <td>';
                        echo  $u['transfers_to_in'] == "safe" ? $lang['SETTINGS_C_F_SAFE']  : get_data('settings_banks', 'banks_name', 'banks_sn', $u['transfers_to']);
							 if ($u['transfers_to_in'] == "bank") {
								 echo '<br/>'.$lang['SETTINGS_BAN_ACCONT_TYPE'].' / ';
								if ($u['transfers_account_type_to'] == "credit") {
									echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];
								} elseif ($u['transfers_account_type_to'] == "current") {
									echo $lang['SETTINGS_BAN_CURRENT'];
								} elseif ($u['transfers_account_type_to'] == "saving") {
									echo $lang['SETTINGS_BAN_SAVE'];
								}
							} 
						
							if ($u['transfers_to_in'] == "bank") {
								if ($u['transfers_account_type_to'] == "credit") {
									echo '<br/>'.$lang['SETTINGS_C_F_ACCOUNT_T'].' / '.get_data('settings_banks_credit', 'banks_credit_name', 'banks_credit_sn', $u['transfers_account_id_to']);
								} 
							} 
             
                        
                         echo '</td>
                                <td>' . $u['invoices_id'] . '</td>
                                <td>' . $u['transfers_text'] . '</td>
                                <td class="text-center tableaprove" id="approve_' . $u['transfers_sn'] . '">';
							if ($u['transfers_to_in'] == "bank" && $u['transfers_type'] != "cash" && $u['transfers_account_type_to'] != "credit") {
								if ($u['transfers_bank_approved'] == 1) {
									echo '<i class=" far fa-check-circle fa-w-16 fa-2x  text-success" title="' .  _date_format($u['transfers_bank_approved_date']) . '" id="approve_' . $u['transfers_sn'] . '"></i>';
								} else {
									echo '<i class="approve fas fa-check fa-w-16 fa-2x grab text-warning " title="' . $lang['DEPOSITS_BANK_APPROVE'] . '" id="approve_' . $u['transfers_sn'] . '"></i>';
								}
							} else {
								if($u['transfers_account_type_to'] == "credit" && $u['transfers_type'] == "cheque")
								{
									echo $lang['TRANSFER_ADD_TO_DEPOSITS'];
								}
								
							}
                        echo
                            ' </td>
							</tr>';
                    }
                    echo '</tbody>';
                } ?>
            </table>
            <div class="row">
                <div class="row mt-2 mb-5">
                    <div class="col d-flex justify-content-end">
                        <a class="btn roundedBtn mr-2" href="<?php echo $_SESSION['page'];?>"><?php echo $lang['BACK']; ?></a>
                    </div>
                </div>
            </div>
            <?php echo $pager; ?>
        </div>

        <!-- buttons row -->

        <!-- end buttons row -->
    </div>
    <!-- end table row -->
</div>
<!-- end page content -->


<?php
$footer = '<script src="http://code.jquery.com/jquery-1.7.min.js"></script>
	
				<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
					crossorigin="anonymous"></script>
				<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"  integrity="sha384-a9xOd0rz8w0J8zqj1qJic7GPFfyMfoiuDjC9rqXlVOcGO/dmRqzMn34gZYDTel8k"
					crossorigin="anonymous"></script>
				<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
				<script src="./assets/js/framework/bootstrap.js"></script>
				<script src="./assets/js/main.js"></script>
				<script src="./assets/js/navbar.js"></script>
				<script src="./assets/js/list-controls.js"></script>
				';

include './assets/layout/footer.php';
?>
<SCRIPT>
    $(document).ready(function() {
        $('#departmentTable').DataTable({
            "searching": false,
            "ordering": false,
            "lengthChange": false,
            "info": false,
            "language": {
                "paginate": {
                    "next": ">",
                    "previous": "<"
                }
            }
        });

    });
    $('.approve').click(function() {
        var id = $(this).attr('id').replace("approve_", "");
        var page = "bank_js.php?do=bank_approve_transfer";
        if (id) {
            $.ajax({
                type: 'POST',
                url: page,
                data: {
                    id: id,
                },
                success: function(responce) {

                    if (responce == 100) {
                        $("td#approve_" + id).animate({
                            height: 'auto',
                            opacity: '0.2'
                        }, "slow");
                        $("td#approve_" + id).animate({
                            width: 'auto',
                            opacity: '0.9'
                        }, "slow");
                        $("td#approve_" + id).animate({
                            height: 'auto',
                            opacity: '0.2'
                        }, "slow");
                        $("td#approve_" + id).animate({
                            width: 'auto',
                            opacity: '1'
                        }, "slow");

                        $('i#approve_' + id).removeClass('approve fas fa-check fa-w-16 fa-2x grab text-warning');
                        var today = new Date();
                        var date = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();
                        $('i#approve_' + id).prop('title', date);
                        $('i#approve_' + id).addClass('far fa-check-circle fa-w-16 fa-2x  text-success');
                        $('i#collect_' + id).css("display", "block");

                    }
                }

            });
        }
    })



</SCRIPT>

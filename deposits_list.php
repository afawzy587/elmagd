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

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['deposit_check'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {

		$id 		 = intval($_GET['id']);

        //			include("./inc/Classes/pager.class.php");
        //			$page;
        //			$pager       = new pager();
        //			$page 		 = intval($_GET['page']);
        //			$total       = $setting_department->getTotalSettings_deposits($q);
        //			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"settings_deposits.php".$search.$paginationAddons,$paginationDialm);
        //			$thispage    = $pager->getPage();
        //			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
        //			$pager       = $pager->getAnalysis();
        $deposits   = $deposit->getsiteDepoists($limitmequry,$id);
        $logs->addLog(
            NULL,
            array(
                "type"               =>     "admin",
                "module"             =>     "deposits",
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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['DEPOSITS_LISTS']; ?> </span>
            </p>
        </div>
    </div>
    <!-- end links row -->

    <!-- table row -->
    <div class="row">
        <div class="col">
            <table class="table table-fluid " id="departmentTable">
                <?php
                if (empty($deposits)) {
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
                                <th>' . $lang['DEPOSITS_BANK_IN'] . '</th>
                                <th>' . $lang['SETTINGS_BAN_ACCONT_TYPE'] . '</th>
                                <th style="width: 6rem;">' . $lang['SETTINGS_C_F_ACCOUNT_T'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_M_CLIENT'] . '</th>
                                <th>' . $lang['SETTINGS_C_F_M_PRODUCT'] . '</th>
                                <th>' . $lang['BANKS_CUT_VALUE'] . '</th>
                                <th style="width: 2rem;">' . $lang['DEPOSITS_BANK_APPROVE'] . '</th>
                                <th style="width: 2rem;">' . $lang['DEPOSITS_COLLECTED'] . '</th>
                             </tr>
						</thead>
						<tbody>';
                    foreach ($deposits as $k => $u) {
                        echo '<tr id=tr_' . $u['deposits_sn'] . '>
							    <td>' . _date_format($u['deposits_date']) . '</td>
                                <td>' . $u['deposits_value'] . '</td>
								<td>';
                        echo $u['deposits_type'] == "cash" ? $lang['SETTINGS_C_F_PAYMENT_CASH'] : $lang['SETTINGS_C_F_PAYMENT_CHEQUE'];
                        echo '</td>
                                <td>';
                        echo $u['deposits_cheque_number'] != "" ? $u['deposits_cheque_number'] : '-------';
                        echo '</td>
                               <td>';
                        echo  $u['deposits_cheque_date'] != "0000-00-00" ? _date_format($u['deposits_cheque_date'])  : '-------';
                        echo '</td>
                               <td>';
                        echo  $u['deposits_insert_in'] == "safe" ? $lang['SETTINGS_C_F_SAFE']  : get_data('settings_banks', 'banks_name', 'banks_sn', $u['deposits_bank_id']);
                        echo  '</td>
                                <td>';
                        if ($u['deposits_insert_in'] == "bank") {
                            if ($u['deposits_account_type'] == "credit") {
                                echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];
                            } elseif ($u['deposits_account_type'] == "current") {
                                echo $lang['SETTINGS_BAN_CURRENT'];
                            } elseif ($u['deposits_account_type'] == "saving") {
                                echo $lang['SETTINGS_BAN_SAVE'];
                            }
                        } else {
                            echo '-----';
                        }

                        echo   '</td>
                                <td>';
                        if ($u['deposits_insert_in'] == "bank") {
                            if ($u['deposits_account_type'] == "credit") {
                                echo get_data('settings_banks_credit', 'banks_credit_name', 'banks_credit_sn', $u['deposits_account_id']);
                            } elseif ($u['deposits_account_type'] == "current") {
                                echo '-----';
                            } elseif ($u['deposits_account_type'] == "saving") {
                                echo '-----';
                            }
                        } else {
                            echo '-----';
                        }
                        echo '</td>
                                <td>' . get_data('settings_clients', 'clients_name', 'clients_sn', $u['deposits_client_id']) . '</td>
                                <td>' . get_client_product($u['deposits_product_id']) . '</td>
                                <td>' . $u['deposits_cut_value'] . '</td>
                                <td class="text-center tableaprove" id="approve_' . $u['deposits_sn'] . '">';
                        if ($u['deposits_insert_in'] == "bank" && $u['deposits_type'] != "cash") {
                            if ($u['deposits_approved'] == 1) {
                                echo '<i class=" far fa-check-circle fa-w-16 fa-2x  text-success" title="' .  _date_format($u['deposits_approved_date']) . '" id="approve_' . $u['deposits_sn'] . '"></i>';
                            } else {
                                echo '<i class="approve fas fa-check fa-w-16 fa-2x grab text-warning " title="' . $lang['DEPOSITS_APPROVE'] . '" id="approve_' . $u['deposits_sn'] . '"></i>';
                            }
                        } else {
                            echo '-----';
                        }
                        echo
                            ' </td>
                              <td class="text-center tableaprove" id="collect_' . $u['deposits_sn'] . '">';
                        if ($u['deposits_insert_in'] == "bank"  && $u['deposits_type'] != "cash") {
                            if ($u['deposits_collected'] == 1) {
                                // <i class= "fas fa-money-check-alt"></i>
                                echo '<i class="fas fa-thumbs-up fa-w-16 fa-2x  text-success" title="' .  _date_format($u['deposits_collected_date']) . '" id="collect_' . $u['deposits_sn'] . '"></i>';
                            } else {
                                echo '<i class="collect far fa-thumbs-down fa-w-16 fa-2x grab text-warning " title="' . $lang['DEPOSITS_COLLECT'] . '" id="collect_' . $u['deposits_sn'] . '"';
                                echo  $u['deposits_approved'] != "1" ? 'style="display:none;"' : '';
                                echo '></i>';
                            }
                        } else {
                            echo '-----';
                        }
                        echo ' </td>';
                        echo ' </td>
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
        var page = "bank_js.php?do=bank_approve";
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


    $('.collect').click(function() {
        var id = $(this).attr('id').replace("collect_", "");
        var page = "bank_js.php?do=collect";
        if (id) {
            $.ajax({
                type: 'POST',
                url: page,
                data: {
                    id: id,
                },
                success: function(responce) {
                    if (responce == 100) {
                        $("td#collect_" + id).animate({
                            height: 'auto',
                            opacity: '0.2'
                        }, "slow");
                        $("td#collect_" + id).animate({
                            width: 'auto',
                            opacity: '0.9'
                        }, "slow");
                        $("td#collect_" + id).animate({
                            height: 'auto',
                            opacity: '0.2'
                        }, "slow");
                        $("td#collect_" + id).animate({
                            width: 'auto',
                            opacity: '1'
                        }, "slow");

                        $('i#collect_' + id).removeClass('collect far fa-thumbs-down fa-w-16 fa-2x grab text-warning');
                        var today = new Date();
                        var date = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();
                        $('i#collect_' + id).prop('title', date);
                        $('i#collect_' + id).addClass('fas fa-thumbs-up fa-w-16 fa-2x  text-success');
                    }
                }

            });
        }
    })
</SCRIPT>

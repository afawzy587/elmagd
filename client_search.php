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

include("./inc/Classes/system-clients_collectible.php");
$clients_collectible = new systemClients_collectible();

include("./inc/Classes/system-settings_banks.php");
$setting_bank = new systemSettings_banks();

include("./inc/Classes/system-settings_clients.php");
$setting_client = new systemSettings_clients();

include("./inc/Classes/system-settings_suppliers.php");
	$setting_supplier = new systemSettings_suppliers();

if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['clients_finance'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
        $clients_finance = $clients_collectible->GetClientFinance();
        $banks         = $setting_bank->getaccountsSettings_banks();
        $clients       = $setting_client->getsiteSettings_clients();
		$suppliers     = $setting_supplier->getsiteSettings_suppliers();
                $logs->addLog(NULL,
                    array(
                        "type" 		        => 	"users",
                        "module" 	        => 	"deposits",
                        "mode" 		        => 	"add_deposits",
                        "id" 	        	=>	$_SESSION['id'],
                    ),"admin",$_SESSION['id'],1
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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_CL_CLIENTS']; ?> </span>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_CL_CLIENTS_FINANCES']; ?></span>
            </p>
        </div>
    </div>
 
    <div class="row centerContent">
        <div class="col">
            <!-- account details row -->
            <div class="row justify-content-center mb-5">
                <div class="col">
                    <div class="row mt-5">
                        <?php
                            if ($clients_finance) {
                                foreach ($clients_finance as $k => $f) {
                                    echo '<div class="col-md-2 text-center">
                                    <h5 class="d-inline-block bg_text2 text_height2 w-100">' . $f['clients_name'] . '</h5>
                                    <h5 class="d-inline-block bg_text2 text_height2 ';
                                    if ($f['clients_finance_credit'] < 0) {
                                        echo 'warning';
                                    }
                                    echo ' w-100 ltrDir">' . number_format($f['clients_finance_credit']) . '</h5>
                                </div>';
                                $total_finance += $f['clients_finance_credit'];
                            }
                        }
                        ?>
                    </div>

                    <div class="row mt-5">
						<div class="col text-center">
							<h5><?php echo $lang['C_S_TOTAL_REMAIN'];?></h5>
							<h4 class="d-inline-block bg_text text_height ltrDir"><strong> <?php echo number_format($total_finance);?></strong></h4>
						</div>
					</div>
                </div>
            </div>
            <!-- end account details row -->
            <form method="GET" action="./client_search_result.php" id="customersAccountsPaymentForm" enctype="multipart/form-data">
                <h5><?php echo $lang['C_S_SEARCH_CLIENT']; ?></h5>
                <div class="darker-bg centerDarkerDiv formCenterDiv">
                    <div class="row">
                       <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_CLI'] ?></label>
                                <div class="col-xs-5 ">
                                    <div class="select">
                                        <select name="client" id="client" class="show_product_rate form-control">
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
                                        <select name="product" id="product" class="show_product_rate form-control">
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
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_SUPPLIER'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="supplier_div">
                                            <select name="supplier" id="supplier" class=" form-control ">
                                                <option selected disabled value=""> <?php echo $lang['OPERATIONS_SUPPLIER_IN'];?></option>
                                                <?php
													if($suppliers)
													{
														foreach($suppliers as $sId=> $s)
														{
															echo '<option value="'.$s["suppliers_sn"].'" >'.$s["suppliers_name"].'</option>';
														}
													}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--
                        <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['C_S_DEGREES'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="quality" id="quality" class="form-control">
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_PRODUCT_FIRST'];?></option>
                                                
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>    
-->
                    </div>
                    <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_START_DATE'];?> </label>
                                    <div class="col-xs-5">
                                          <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_END_DATE'];?> </label>
                                    <div class="col-xs-5">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label class="col-xs-3"><?php echo $lang['C_S_FROM_SERIAL'];?> </label>
								<div class="col-xs-5 ">
									<input type="text" class="form-control" name="serial_from" placeholder="-----">
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="col-xs-3"><?php echo $lang['C_S_TO_SERIAL'];?> </label>
								<div class="col-xs-5 ">
									<input type="text" class="form-control" name="serial_to" placeholder="-----">
								</div>
							</div>
						</div>
					</div>
                    <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['C_S_INVOICES_FROM'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="invoice_from" placeholder="-----">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['C_S_INVOICES_TO'];?>  </label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="invoice_to" placeholder="-----">
                                    </div>
                                </div>
                            </div>
                        </div>
				</div>
				<div class="row mt-2 mb-5">
					<div class="col d-flex justify-content-end">
						<button class="btn roundedBtn" type="submit"><?php echo $lang['SHOW'];?></button>
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
                client: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['OPERATIONS_CLIENT_IN'];?> '
                        }
                    }
                },


            }
        }).on('success.form.bv', function(e) {


        })
        $('#client').change(function() {
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
                        $('select#product').html(html);
                    }
                });
            }

        })
		
	$('.show_product_rate').on('change', function () {
        if ($('.show_product_rate').filter(function () {
            return $.trim($(this).val()).length == 0
        }).length == 0) {
            var id     = $('#product').val();
            var page   ="operations_js.php?do=product_rate_select";
            if(id && client){
                $.ajax({
                        type:'POST',
                        url:page,
                        data:{id:id},
                        success:function(html)
                        {
                            $('select#quality').html(html);
                        }
                    });
            }
            
        }
     });
      
    })
</SCRIPT>

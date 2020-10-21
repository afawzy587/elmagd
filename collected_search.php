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
    if ($group['supplier_payment'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
		$suppliers     = $setting_supplier->getsiteSettings_suppliers();
                $logs->addLog(NULL,
                    array(
                        "type" 		        => 	"users",
                        "module" 	        => 	"collected_search",
                        "mode" 		        => 	"collected_search",
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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_C_F_CLIENT']; ?> </span>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_C_F_COLLECTED']; ?></span>
            </p>
        </div>
    </div>
 
    <div class="row centerContent">
        <div class="col">
            
            <form method="GET" action="./supplier_collected.php" id="customersAccountsPaymentForm" enctype="multipart/form-data">
                <h5><?php echo $lang['SUPPLIER_SEARCH_COLLECTED']; ?></h5>
                <div class="darker-bg centerDarkerDiv formCenterDiv">
                    <div class="row">
                       <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_SUPPLIER'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="supplier" id="supplier" class=" form-control ">
                                                <option selected disabled value=""> <?php echo $lang['OPERATIONS_SUPPLIER_IN'];?></option>
                                                <?php
													if($suppliers)
													{
														foreach($suppliers as $sId=> $s)
														{
															echo '<option value="'.$s["suppliers_sn"].'">'.$s["suppliers_name"].'</option>';
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
                supplier: {
                    validators: {
                        notEmpty: {
                            message: ' <?php echo $lang['SETTINGS_C_F_CHOOSE_SUPLLIER'];?> '
                        }
                    }
                },


            }
        }).on('success.form.bv', function(e) {


        })
        $('#supplier').change(function() {
            var id = $(this).val();
            var page = "client_pricing_js.php?do=supplier_product";
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
		
	
    })
</SCRIPT>

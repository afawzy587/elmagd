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

include("./inc/Classes/system-settings_banks.php");
$setting_bank = new systemSettings_banks();

include("./inc/Classes/system-settings_clients.php");
$setting_client = new systemSettings_clients();



if ($login->doCheck() == false) {
    header("Location:./login.php");
    exit;
} else {
    if ($group['deposit_check'] == 0) {
        header("Location:./permission.php");
        exit;
    } else {
        $banks         = $setting_bank->getaccountsSettings_banks();
        $clients       = $setting_client->getsiteSettings_clients();
                $logs->addLog(NULL,
                    array(
                        "type" 		        => 	"users",
                        "module" 	        => 	"deposits",
                        "mode" 		        => 	"deposits_search",
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
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_AND_SAVES']; ?> </span>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['BANKS_DEPOSIT']; ?> </span>
                <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['DEPOSITS_SEARCH_DETAILS']; ?> </span>
            </p>
        </div>
    </div>
 
     <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form action="./deposits_list.php" id="banksOperationsSearchForm">
                    <h5> <?php echo $lang['DEPOSITS_SEARCH_TITLE']; ?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_START_DATE'];?> </label>
                                    <div class="col-xs-5">
                                          <input type="date" name="startDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_END_DATE'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="endDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_CLI'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="client">
                                            <select name="client_id"  class="client form-control">
                                                <option selected disabled><?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                 <?php
													if($clients)
													{
														foreach($clients as $cId=> $c)
														{
															echo '<option value="'.$c["clients_sn"].'">'.$c["clients_name"].'</option>';
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
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PRODUCT'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="product_client">
                                            <select name="prroduct_client_id" id="product_client_id" class="product_client_id form-control">
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_CLIENT_FIRST'];?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['DEPOSITS_STARTVALUE'] ;?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="startValue"  value=""
                                            placeholder="-----">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['DEPOSITS_ENDVALUE'] ;?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="endValue" value=""
                                            placeholder="-----">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_NAME'] ;?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
											<select name="bank" id="transfer_from" class="bank invoices form-control">
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
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CREDIT_NAME'] ;?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="account" id="transfer_account_id_from" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'] ;?></label>
									<div class="col-xs-5">
										<input class="form-control" type="text" name="cheque" value="">
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
include './assets/layout/footer.php'; ?>
<SCRIPT>
	$(document).ready(function () {

    $('#banksOperationsSearchForm').formValidation({
        excluded: [':disabled'],
        fields: {
            startDate: {
                validators: {
                    callback: {
                      message: '<?php echo $lang['SETTINGS_C_F_START_B_END'];?>',
                      callback: function (startDate, validator, $field) {
                          return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
                      }
                  }
                }
            },
            endDate: {
                validators: {
                    callback: {
                      message: '<?php echo $lang['SETTINGS_C_F_END_AFTER_START'];?>',
                      callback: function (startDate, validator, $field) {
                          return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
                      }
                  }
                }
            },
          
  
            startValue: {
                validators: {
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    },
                    callback: {
                      message: '<?php echo $lang['DEPOSITS_COLLECT_END'];?>',
                      callback: function () {
                          return checkNumbersRange($('input[name="startValue"]').val(),$('input[name="endValue"]').val());
                      }
                  }
                }
            },
            endValue:{
                validators: {
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    },
                    callback: {
                      message: '<?php echo $lang['DEPOSITS_COLLECT_START'] ;?>',
                      callback: function () {
                          return checkNumbersRange($('input[name="startValue"]').val(), $('input[name="endValue"]').val());
                      }
                  }
                }
            },

        }
    }).on('success.form.bv', function (e) {


    })

  // dates validation
  var checkDateRange = function (startDate, endDate) {
    startDate = startDate.split("/").reverse().join("/");
    endDate = endDate.split("/").reverse().join("/");
    var isValid = (startDate != "" && endDate != "") ? startDate < endDate : true;
    return isValid;
  }

  var checkNumbersRange = function (startNum, endNum) {
	  console.log(startNum);
	  console.log("endNum"+endNum);
    var isValid = (startNum != "" && endNum != "") ? startNum < endNum : true;
    return isValid;
  }

  $('input[name="startDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endDate'));
  });

  $('input[name="endDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startDate"]'));
  });

  $('input[name="startValue"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endValue'));
  });

  $('input[name="endValue"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startValue"]'));
  });
		
 $('#client').on('change','select.client',function(){
        var id     = $(this).val();
        var page   ="client_pricing_js.php?do=price_product";
        if(id){
            $.ajax({
                type:'POST',
                url:page,
                data:{id:id},
                success:function(html)
                {
                   $('select#product_client_id').html(html); 
                }
                });
        }
	});
  $('.bank').change(function() {
            var type    = 'credit';
            var type_id = $(this).attr('id').replace('transfer_', '');
            var id = $(this).val();
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

})

</SCRIPT>

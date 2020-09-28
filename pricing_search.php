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
	include("./inc/Classes/system-clients_pricing.php");
	$pricing = new systemClients_pricing();
	include("./inc/Classes/system-settings_clients.php");
	$setting_client = new systemSettings_clients();
	include("./inc/Classes/system-settings_suppliers.php");
	$setting_supplier = new systemSettings_suppliers();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['clients_pricing'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			
			$clients     = $setting_client->getsiteSettings_clients();
			$suppliers   = $setting_supplier->getsiteSettings_suppliers();
			$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"pricing_search",
							"mode" 		        => 	"pricing_search",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_PREFICE_PRICE'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="get" id="customersPricingAddEditForm" action="pricing_result.php" enctype="multipart/form-data">
                     <h5><?php echo $lang['SETTINGS_C_F_SEARCH_TITLE'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
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
                                            <select name="product" id="product_client_id" class="product form-control ">
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
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLIERS'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="supplier[]" class="mySelect for form-control" multiple>
                                                <option selected disabled> <?php echo $lang['SETTINGS_C_F_CHOOSE_SUPLLIER'];?></option>
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_RATES'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="rate[]" id="product_rate" class="mySelect for form-control" multiple>
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_PRODUCT_FIRST'];?></option>
                                                
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
                                          <input type="date" name="startDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_END_DATE'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="endDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                      
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
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
			<link rel="stylesheet" href="./assets/css/select2.min.css">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
			';


include './assets/layout/footer.php';?>

<SCRIPT>
$(document).ready(function () {

  $('#customersPricingAddEditForm').formValidation({
        excluded: [':disabled'],
        fields: {
            customerName: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?> '
                    }
                }
            },
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
            }
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


  $('input[name="startDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endDate'));
  });

  $('input[name="endDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startDate"]'));
  });


	$('#client').on('change','select.client',function(){
        var id     = $(this).val();
        var page   ="client_pricing_js.php?do=client_product";
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
	
	$('#product_client').on('change','select.product',function(){
        var id     = $(this).val();
        var page   ="client_pricing_js.php?do=product_rate_select";
        if(id){
            $.ajax({
                type:'POST',
                url:page,
                data:{id:id},
                success:function(html)
                {
                   $('select#product_rate').html(html); 
                }
                });
        }
	});
	var data = []; // Programatically-generated options array with > 5 options
    $(".mySelect").select2({
		height: 'resolve',
		theme: "classic",
		color:'#495057',
		lineHeight:'1.5',
        allowClear: false,
        minimumResultsForSearch: 5
    });
})

</SCRIPT>



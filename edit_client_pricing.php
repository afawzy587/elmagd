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
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u  = $pricing->getClients_pricingInformation($mId);
				$clients   = $setting_client->getsiteSettings_clients();
				if($_POST)
				{

					$_price['pricing_sn']                                  =       $mId;
					$_price['pricing_start_date']                          =       sanitize($_POST["pricing_start_date"]);
					$_price['pricing_end_date']                            =       sanitize($_POST["pricing_end_date"]);
					$_price['pricing_selling_price']                       =       sanitize($_POST["pricing_selling_price"]);
					$_price['pricing_supply_price']                        =       sanitize($_POST["pricing_supply_price"]);
					$_price['pricing_supply_percent']                      =       sanitize($_POST["pricing_supply_percent"]);
					$_price['pricing_excuse_price']                        =       sanitize($_POST["pricing_excuse_price"]);
					$_price['pricing_excuse_percent']                      =       sanitize($_POST["pricing_excuse_percent"]);
					$_price['pricing_excuse_active']                       =       sanitize($_POST["pricing_excuse_active"]);
					$_price['pricing_rate_percent']                        =       sanitize($_POST["pricing_rate_percent"]);
					$_price['pricing_rate_type']                           =       sanitize($_POST["pricing_rate_type"]);
					$_price['pricing_client_bonus']                        =       sanitize($_POST["pricing_client_bonus"]);
					$_price['pricing_client_bonus_percent']                =       sanitize($_POST["pricing_client_bonus_percent"]);
					$_price['pricing_client_bonus_amount']                 =       sanitize($_POST["pricing_client_bonus_amount"]);
					$_price['pricing_supply_bonus']                        =       sanitize($_POST["pricing_supply_bonus"]);
					$_price['pricing_supply_bonus_percent']                =       sanitize($_POST["pricing_supply_bonus_percent"]);
					$_price['pricing_supply_bonus_amount']                 =       sanitize($_POST["pricing_supply_bonus_amount"]);
					$edit = $pricing->setClients_pricingInformation($_price);
					if($edit == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"client_pricings",
								"mode" 		        => 	"edit_client_pricings",
								"id" 	        	=>	$_SESSION['id'],
								"total"				=> $mId
							),"admin",$_SESSION['id'],1
							);

							header("Location:./".$_SESSION['page']);
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
                    <span class="blueSky"><?php echo $lang['SETTINGS_TITLE'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_CLIENT'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_PRICE'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="departmentSearchForm" method="get" action="./clients_pricing.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="departmentSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="customersPricingAddEditForm" enctype="multipart/form-data">
                   
                   
                     <h5><?php echo $lang['SETTINGS_C_F_EDIT'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_CLI'];?></label>
                                    <div class="col-xs-5">
                                       <input type="text" class="form-control" value="<?php echo $u['clients_name']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PRODUCT'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" value="<?php echo $u['products_name']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="productToEditRow">
						<div class="col">
                            <h5> <?php echo $lang['SETTINGS_C_F_RATE_EDIT'];?><span class="blueSky"><?php echo $u['clients_products_rate_name']?></span> </h5>
                            <div class="darker-bg centerDarkerDiv formCenterDiv ">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_START_DATE'];?></label>
                                            <div class="col-xs-5">
                                                <input type="date" name="pricing_start_date" class="form-control" value="<?php echo $u['pricing_start_date']?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_C_F_END_DATE'];?> </label>
                                            <div class="col-xs-5">
                                                <input type="date" name="pricing_end_date" class="form-control" value="<?php echo $u['pricing_end_date']?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SELLING_NOW'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" name="currentSellingPrice" class="form-control" value="<?php echo $last['pricing_selling_price']?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLY_NOW'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text"  class="form-control" value="<?php echo $last['pricing_supply_price'];?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SELLING_PRICE'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" name="pricing_selling_price" class="form-control" value="<?php echo $u['pricing_selling_price']?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end align-items-center supplyStateCol">
                                        <div class="form-check radioBtn" style="margin-top: 26px;">
                                            <input class="supplyState form-check-input" type="radio" name="supplyState"
                                                id="supplyState1" value="1" checked>
                                            <label class="form-check-label smallerFont" for="supplyState1">
                                            </label>
                                        </div>
                                        <div class="form-group" style="width: 82%;">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLY_PRICE'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" name="pricing_supply_price" id="pricing_supply_price" class="form-control supplyStateInput" value="<?php echo $u['pricing_supply_price']?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLY_PERCENT'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" name="currentBuyingPercentage" class="form-control" value="<?php echo $last['pricing_supply_percent'];?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end align-items-center supplyStateCol">
                                        <div class="form-check radioBtn" style="margin-top: 26px;">
                                            <input class="supplyState form-check-input" type="radio" name="supplyState"
                                                id="supplyState2" value="2" >
                                            <label class="form-check-label smallerFont" for="supplyState2">
                                            </label>
                                        </div>
                                        <div class="form-group " style="width: 82%;">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLY_PER'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" name="pricing_supply_percent" id="pricing_supply_percent" class="form-control supplyStateInput" value="<?php echo $u['pricing_supply_percent'];?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PRICE_EXUCE'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" id="pricing_excuse_price" name="pricing_excuse_price" class="form-control" value="<?php echo $u['pricing_excuse_price'];?>" <?php if($u['pricing_excuse_active'] == 'off'){echo 'disabled';} ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PERCENT_EXUCE'];?></label>
                                            <div class="col-xs-5">
                                                <input type="text" id="pricing_excuse_percent" name="pricing_excuse_percent" class="form-control" value="<?php echo $u['pricing_excuse_percent'];?>" <?php if($u['pricing_excuse_active'] == 'off'){echo 'disabled';} ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="col-xs-5  d-flex flex-column">
                                                <div class="form-check radioBtn">
                                                    <input class="pricing_excuse_active form-check-input" type="radio" name="pricing_excuse_active"
                                                        id="activatePrice" value="on" <?php if($u['pricing_excuse_active'] == 'on'){echo 'checked';} ?>>
                                                    <label class="form-check-label smallerFont" for="activatePrice">
                                                        <?php echo $lang['SETTINGS_C_F_EXUCE_ACTIVE'];?>
                                                    </label>
                                                </div>
                                                <div class="form-check radioBtn ">
                                                    <input class="pricing_excuse_active form-check-input" type="radio" name="pricing_excuse_active"
                                                        id="activatePrice2" value="off" <?php if($u['pricing_excuse_active'] == 'off'){echo 'checked';} ?>>
                                                    <label class="form-check-label smallerFont" for="activatePrice2">
                                                        <?php echo $lang['SETTINGS_C_F_EXUCE_NOT_ACTIVE'];?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_RATE_PERCENT'];?></label>
                                            <div class="col-xs-5 ">
                                                <input type="text" name="pricing_rate_percent" class="form-control" id="pricing_rate_percent_1" value="<?php echo $u['pricing_rate_percent'];?>"
                                                       <?php if($u['pricing_rate_type'] == 'not'){echo 'disabled="disabled"';}?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="col-xs-5  d-flex flex-column">
                                               <?php 
													if($u['pricing_rate_type'] == 'amount')
													{
														echo'<input class="pricing_rate_type form-check-input" type="text" name="pricing_rate_type" id="qualityDetail_1" value="amount" hidden>
                                                    
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="pricing_rate_off customized-checkbox" id="customized-checkbox-1" type="checkbox" name="check[]" value="not" >
                                                                    <label class="customized-checkbox-label" for="customized-checkbox-1">'.$lang['SETTINGS_C_F_NOT_WORK'].'</label>
                                                                </div>
                                                            </div>';
													}elseif($u['pricing_rate_type'] == 'extra'){
														echo'<input class="pricing_rate_type form-check-input" type="text" name="pricing_rate_type" id="qualityDetail_1" value="extra" >';
													}else{
                                                        
                                                        echo'<input class="pricing_rate_type form-check-input" type="text" name="pricing_rate_type" id="qualityDetail_1" value="not" hidden>
                                                    
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="pricing_rate_off customized-checkbox" id="customized-checkbox-1" type="checkbox" name="check[]" value="not" checked>
                                                                    <label class="customized-checkbox-label" for="customized-checkbox-1">'.$lang['SETTINGS_C_F_NOT_WORK'].'</label>
                                                                </div>
                                                            </div>';
													}
												
												?>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_CLIENT_BOUNC'];?></label>
                                                    <div class="col-xs-5  ">
                                                        <div class="form-check radioBtn">
                                                            <input class="pricing_client_bonus form-check-input" type="radio"
                                                                name="pricing_client_bonus" id="customerBouns" value="yes"
                                                                <?php if($u['pricing_client_bonus'] == 'yes'){echo 'checked';}?>>
                                                            <label class="form-check-label smallerFont"
                                                                for="customerBouns">
                                                                <?php echo $lang['SETTINGS_C_F_YES'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn ">
                                                            <input class="pricing_client_bonus form-check-input" type="radio"
                                                                name="pricing_client_bonus" id="customerBouns2" value="no" <?php if($u['pricing_client_bonus'] == 'no'){echo 'checked';}?>>
                                                            <label class="form-check-label smallerFont"
                                                                for="customerBouns2">
                                                                <?php echo $lang['SETTINGS_C_F_NO'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PERCENT_EXTRA'];?>
                                                        (<span class="blueSky"><?php echo $lang['SETTINGS_C_F_YES'];?></span>)</label>
                                                    <div class="col-xs-5">
                                                        <input type="text" name="pricing_client_bonus_percent" id="pricing_client_bonus_percent"
                                                            class="form-control" value="<?php echo $u['pricing_client_bonus_percent'];?>" <?php if($u['pricing_client_bonus'] == 'no'){echo 'disabled';}?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_AMOUNT'];?> <span class="blueSky"><?php echo $lang['SETTINGS_C_F_DALY'];?></span>
                                                        <?php echo $lang['SETTINGS_C_F_KG_A'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" name="pricing_client_bonus_amount" id="pricing_client_bonus_amount" class="form-control" value="<?php echo $u['pricing_client_bonus_amount'];?>" <?php if($u['pricing_client_bonus'] == 'no'){echo 'disabled';}?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_SUPPLY_BOUNC'];?></label>
                                                    <div class="col-xs-5  ">
                                                        <div class="form-check radioBtn">
                                                            <input class="pricing_supply_bonus form-check-input" type="radio"
                                                                name="pricing_supply_bonus" id="supplierBouns" value="yes"
                                                                <?php if($u['pricing_supply_bonus'] == 'yes'){echo 'checked';}?>>
                                                            <label class="form-check-label smallerFont"
                                                                for="supplierBouns">
                                                                <?php echo $lang['SETTINGS_C_F_YES'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn ">
                                                            <input class="pricing_supply_bonus form-check-input" type="radio"
                                                                name="pricing_supply_bonus" id="supplierBouns2" value="no" <?php if($u['pricing_supply_bonus'] == 'no'){echo 'checked';}?>>
                                                            <label class="form-check-label smallerFont"
                                                                for="supplierBouns2">
                                                                <?php echo $lang['SETTINGS_C_F_NO'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_PERCENT_EXTRA'];?>
                                                        (<span class="blueSky"><?php echo $lang['SETTINGS_C_F_YES'];?></span>)</label>
                                                    <div class="col-xs-5">
                                                        <input type="text" name="pricing_supply_bonus_percent" id="pricing_supply_bonus_percent"
                                                            class="form-control" value="<?php echo $u['pricing_supply_bonus_percent'];?>" <?php if($u['pricing_supply_bonus'] == 'no'){echo 'disabled';}?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_F_AMOUNT'];?> <span class="blueSky"><?php echo $lang['SETTINGS_C_F_DALY'];?></span>
                                                        <?php echo $lang['SETTINGS_C_F_KG_A'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" name="pricing_supply_bonus_amount" id="pricing_supply_bonus_amount" class="form-control" value="<?php echo $u['pricing_supply_bonus_amount'];?>" <?php if($u['pricing_supply_bonus'] == 'no'){echo 'disabled';}?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
			';


include './assets/layout/footer.php';?>

<SCRIPT>
$(document).ready(function () {

  $('#customersPricingAddEditForm').formValidation({
        excluded: [':disabled'],
        fields: {
            client_id: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?> '
                    }
                }
            },
            prroduct_client_id: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?>  '
                    }
                }
            },
        }
    }).on('success.form.bv', function (e) {


    }).on('added.field.fv', function (e, data) {
            if (data.field.includes('startDate')) {
                var inputnameIndex = Object.values(data.element)[0].name.slice(9);
                var inputnameObj = Object.values(data.element)[0];
                data.element.on('change', function () {
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $(this));
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $('input[name="endDate' + inputnameIndex + '"'));
                })

            } else if (data.field.includes('endDate')) {
                data.element.on('change', function () {
                    var inputnameIndex2 = Object.values(data.element)[0].name.slice(7);
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $(this));
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $('input[name="startDate' + inputnameIndex2 + '"]'));
                })
            }
        });

    // dates validation
    var checkDateRange = function (startDate, endDate) {
        var isValid = (startDate != "" && endDate != "") ? startDate < endDate : true;
        return isValid;
    }

	$('#productToEditRow').on('change','input.supplyState',function(){
        var inputs = $(this).attr('id');
        $.each(inputs, function(){
            if($(this).is(':checked')){
                var input = $(this).parents('.supplyStateCol').find('input.supplyStateInput').prop("disabled", false);
            } else {
                 $(this).parents('.supplyStateCol').find('input.supplyStateInput').prop("disabled", true);
                }
        })
         console.log($(this));
    })
	
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
	
	$('#product_client').on('change','select.product_client_id',function(){
        var id     = $(this).val();
        var page   ="client_pricing_js.php?do=product_rate";
        if(id){
            $.ajax({
                type:'POST',
                url:page,
                data:{id:id},
                success:function(html)
                {
                   $('div#productToEditRow').html(html); 
                }
                });
        }
	});
	
	$('.pricing_client_bonus').click(function(){
		var type = $(this).val();
		var id   = $(this).attr('id');
		if(type == "yes")
		{
			$('input#pricing_client_bonus_percent').removeAttr('disabled');
			$('input#pricing_client_bonus_amount').removeAttr('disabled');
			
		}else if(type == 'no')
		{
			$('input#pricing_client_bonus_percent').attr('disabled','disabled');
			$('input#pricing_client_bonus_amount').attr('disabled','disabled');
		}
		
	})
	
	
	$('.pricing_supply_bonus').click(function(){
		var type = $(this).val();
		var id   = $(this).attr('id');
		if(type == "yes")
		{
			$('input#pricing_supply_bonus_percent').removeAttr('disabled');
			$('input#pricing_supply_bonus_amount').removeAttr('disabled');
			
		}else if(type == 'no')
		{
			$('input#pricing_supply_bonus_percent').attr('disabled','disabled');
			$('input#pricing_supply_bonus_amount').attr('disabled','disabled');
		}
		
	})	
	
	$('.supplyState').click(function(){
		var type = $(this).val();
		var id   = $(this).attr('id');
		if(type == 1)
		{
			$('input#pricing_supply_price').removeAttr('disabled');
			$('input#pricing_supply_percent').attr('disabled','disabled');
			
		}else if(type == 2)
		{
			$('input#pricing_supply_percent').removeAttr('disabled');
			$('input#pricing_supply_price').attr('disabled','disabled');
		}
		
	})
    
    $('#productToEditRow').on('click','input.pricing_rate_off',function(){
		var type = $(this).val();
		var id   = $(this).attr('id').replace('customized-checkbox-','');
        var off  = $(this).attr('id');
          if ($(this).checked) {
              $('input#pricing_rate_percent_'+id).attr('disabled','disabled');
              $('input#qualityDetail_'+id).attr('value',type);
          } else {
              $('input#pricing_rate_percent_'+id).removeAttr('disabled');
              $('input#qualityDetail_'+id).attr('value','amount');
          }
	})
    
    
    	$('#productToEditRow').on('click','input.pricing_client_bonus',function(){
		var type = $(this).val();
		var id   = $(this).attr('id').replace('customerBouns_','').replace('customerBouns2_','');
		if(type == "yes")
		{
			$('input#pricing_client_bonus_percent_'+id).removeAttr('disabled');
			$('input#pricing_client_bonus_amount_'+id).removeAttr('disabled');
			
		}else if(type == 'no')
		{
			$('input#pricing_client_bonus_percent_'+id).attr('disabled','disabled');
			$('input#pricing_client_bonus_amount_'+id).attr('disabled','disabled');
		}
		
	})
	
	$('#productToEditRow').on('click','input.pricing_supply_bonus',function(){
		var type = $(this).val();
		var id   = $(this).attr('id').replace('supplierBouns1_','').replace('supplierBouns2_','');
		if(type == "yes")
		{
			$('input#pricing_supply_bonus_percent_'+id).removeAttr('disabled');
			$('input#pricing_supply_bonus_amount_'+id).removeAttr('disabled');
			
		}else if(type == 'no')
		{
			$('input#pricing_supply_bonus_percent_'+id).attr('disabled','disabled');
			$('input#pricing_supply_bonus_amount_'+id).attr('disabled','disabled');
		}
		
	})	
	
	$('#productToEditRow').on('click','input.supplyState',function(){
		var type = $(this).val();
		var id   = $(this).attr('id').replace('supplyState1_','').replace('supplyState2_','');
		if(type == 1)
		{
			$('input#pricing_supply_price_'+id).removeAttr('disabled');
			$('input#pricing_supply_percent_'+id).attr('disabled','disabled');
			
		}else if(type == 2)
		{
			$('input#pricing_supply_percent_'+id).removeAttr('disabled');
			$('input#pricing_supply_price_'+id).attr('disabled','disabled');
		}
		
	})
	
	$('#productToEditRow').on('click','input.pricing_excuse_active',function(){
		var type = $(this).val();
		if(type == "on")
		{
			$('input#pricing_excuse_price').removeAttr('disabled');
			$('input#pricing_excuse_percent').removeAttr('disabled');
			
		}else if(type == "off")
		{
			$('input#pricing_excuse_price').attr('disabled','disabled');
			$('input#pricing_excuse_percent').attr('disabled','disabled');
		}
	})
	
})


</SCRIPT>



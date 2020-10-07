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
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			$clients   = $setting_client->getsiteSettings_clients();
			if($_POST)
			{
				$_price['pricing_product_rate']                        =       $_POST["pricing_product_rate"];
				$_price['pricing_start_date']                          =       $_POST["pricing_start_date"];
				$_price['pricing_end_date']                            =       $_POST["pricing_end_date"];
				$_price['pricing_selling_price']                       =       $_POST["pricing_selling_price"];
				$_price['pricing_supply_price']                        =       $_POST["pricing_supply_price"];
				$_price['pricing_supply_percent']                      =       $_POST["pricing_supply_percent"];
				$_price['pricing_excuse_price']                        =       $_POST["pricing_excuse_price"];
				$_price['pricing_excuse_percent']                      =       $_POST["pricing_excuse_percent"];
				$_price['pricing_excuse_active']                       =       $_POST["pricing_excuse_active"];
				$_price['pricing_rate_percent']                        =       $_POST["pricing_rate_percent"];
				$_price['pricing_rate_type']                           =       $_POST["pricing_rate_type"];
				$_price['pricing_client_bonus']                        =       $_POST["pricing_client_bonus"];
				$_price['pricing_client_bonus_percent']                =       $_POST["pricing_client_bonus_percent"];
				$_price['pricing_client_bonus_amount']                 =       $_POST["pricing_client_bonus_amount"];
				$_price['pricing_supply_bonus']                        =       $_POST["pricing_supply_bonus"];
				$_price['pricing_supply_bonus_percent']                =       $_POST["pricing_supply_bonus_percent"];
				$_price['pricing_supply_bonus_amount']                 =       $_POST["pricing_supply_bonus_amount"];
				$add = $pricing->addClients_pricing($_price);
				
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"departments",
							"mode" 		        => 	"addd_departments",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_client_pricing.php?action=add");
						exit;
				}
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
                   
                    <?php 
						if($add == 1){
							echo alert_message("success",$lang['SETTINGS_C_F_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['SETTINGS_D_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['SETTINGS_C_F_ADD'];?></h5>
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
                                            <select name="prroduct_client_id" id="product_client_id" class="product_client_id form-control">
                                                <option selected disabled><?php echo $lang['SETTINGS_C_F_CHOOSE_CLIENT_FIRST'];?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="productToEditRow">

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
$footer = 'true';


include './assets/layout/footer.php';?>

<SCRIPT>
$(document).ready(function (){

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
    })
	
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
		var id   = $(this).attr('id').replace('activatePrice_','').replace('activatePrice2_','');
		if(type == "on")
		{
			$('input#pricing_excuse_price'+id).removeAttr('disabled');
			$('input#pricing_excuse_percent_'+id).removeAttr('disabled');
			
		}else if(type == "off")
		{
			$('input#pricing_excuse_price'+id).attr('disabled','disabled');
			$('input#pricing_excuse_percent_'+id).attr('disabled','disabled');
		}
	})
	
    
	$('#productToEditRow').on('click','input.pricing_rate_off',function(){
		var type = $(this).val();
		var id   = $(this).attr('id').replace('customized-checkbox-','');
        var off  = $(this).attr('id');
          if (document.getElementById(off).checked) {
              $('input#pricing_rate_percent_'+id).attr('disabled','disabled');
              $('input#qualityDetail_'+id).attr('value',type);
          } else {
              $('input#pricing_rate_percent_'+id).removeAttr('disabled');
              $('input#qualityDetail_'+id).attr('value','amount');
          }
	})
})


</SCRIPT>



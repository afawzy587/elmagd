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
	include("./inc/Classes/system-operations.php");
	$setting_operation = new systemOperations();
	include("./inc/Classes/system-settings_clients.php");
	$setting_client = new systemSettings_clients();
	include("./inc/Classes/system-settings_suppliers.php");
	$setting_supplier = new systemSettings_suppliers();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['operations'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['o'])
			{
				$p = sanitize($_GET['o']);
				$clients     = $setting_client->getsiteSettings_clients();
				$suppliers   = $setting_supplier->getsiteSettings_suppliers();
				if($_POST)
				{
					$_operation['operations_code']                             =       $p;
					$_operation['operations_date']                             =       sanitize($_POST["operations_date"]);
					$_operation['operations_supplier']                         =       intval($_POST["operations_supplier"]);
					$_operation['operations_customer']                         =       intval($_POST["operations_customer"]);
					$_operation['operations_product']                          =       intval($_POST["operations_product"]);
					$_operation['operations_receipt']                          =       intval($_POST["operations_receipt"]);
					$_operation['operations_supplier_price']                   =       floatval($_POST["operations_supplier_price"]);
					$_operation['operations_customer_price']                   =       floatval($_POST["operations_customer_price"]);
					$_operation['operations_card_number']                      =       sanitize($_POST["operations_card_number"]);
					$_operation['operations_quantity']                         =       sanitize($_POST["operations_quantity"]);
					$_operation['operations_general_discount']                 =       sanitize($_POST["operations_general_discount"]);
					$_operation['operations_net_quantity']                     =       sanitize($_POST["operations_net_quantity"]);
					$_operation['rates_product_rate_id']                       =       $_POST["rates_product_rate_id"];
					$_operation['rates_supplier_discount_percentage']          =       $_POST["rates_supplier_discount_percentage"];
					$_operation['rates_supplier_discount_value']               =       $_POST["rates_supplier_discount_value"];
					$_operation['rates_product_rate_percentage']               =       $_POST["rates_product_rate_percentage"];
					$_operation['rates_product_rate_discount_percentage']      =       $_POST["rates_product_rate_discount_percentage"];
					$_operation['rates_product_rate_excuse_percentage']        =       $_POST["rates_product_rate_excuse_percentage"];
					$_operation['rates_product_rate_supply_price']             =       $_POST["rates_supplier_discount_value"];
					$_operation['rates_product_rate_excuse_price']             =       $_POST["rates_product_rate_excuse_price"];
					$_operation['rates_product_rate_quantity']                 =       $_POST["rates_product_rate_quantity"];
					$_operation['rates_product_rate_excuse_quantity']          =       $_POST["rates_product_rate_excuse_quantity"];
				
					if( $_FILES && ( $_FILES['operations_card_front_photo']['name'] != "") && ( $_FILES['operations_card_front_photo']['tmp_name'] != "" ) )
					{
						include_once("./inc/Classes/upload.class.php");
						$allow_ext = array("jpg","jpeg","gif","png");
						$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
						$files['name'] 	= addslashes($_FILES["operations_card_front_photo"]["name"]);
						$files['type'] 	= $_FILES["operations_card_front_photo"]['type'];
						$files['size'] 	= $_FILES["operations_card_front_photo"]['size']/1024;
						$files['tmp'] 	= $_FILES["operations_card_front_photo"]['tmp_name'];
						$files['ext']		= $upload->GetExt($_FILES["operations_card_front_photo"]["name"]);
						$upfile	= $upload->Upload_File($files);
						if($upfile)
						{
							$_operation['operations_card_front_photo']  = $upfile['newname'];

						}
					}
					if( $_FILES && ( $_FILES['operations_card_back_photo']['name'] != "") && ( $_FILES['operations_card_back_photo']['tmp_name'] != "" ) )
					{
						include_once("./inc/Classes/upload.class.php");
						$allow_ext = array("jpg","jpeg","gif","png");
						$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
						$files['name'] 	= addslashes($_FILES["operations_card_back_photo"]["name"]);
						$files['type'] 	= $_FILES["operations_card_back_photo"]['type'];
						$files['size'] 	= $_FILES["operations_card_back_photo"]['size']/1024;
						$files['tmp'] 	= $_FILES["operations_card_back_photo"]['tmp_name'];
						$files['ext']		= $upload->GetExt($_FILES["operations_card_back_photo"]["name"]);
						$upfile	= $upload->Upload_File($files);
						if($upfile)
						{
							$_operation['operations_card_back_photo']  = $upfile['newname'];

						}
					}
					$add = $setting_operation->addOperations($_operation);
					
					if($add == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"operations",
								"mode" 		        => 	"add_operations",
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);
							if(intval($_POST["add_other"]) == 1)
							{
								unset($_SESSION['supplier']);
								unset($_SESSION['customer']);
								header("Location:./operation_sum.php?code=".sanitize($_GET['o']));
							}else{

								$_SESSION['supplier'] =  $_operation['operations_supplier'];
								$_SESSION['customer'] =  $_operation['operations_customer'];
								header("Location:./add_operation.php?o=".sanitize($_GET['o']));
							}
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['OPERATIONS_NAV_TITLE'];?></span>
					<span class="blueSky"><strong> &gt; </strong>  <a class="blueSky" href="./add_operation.php?o=<?php echo generate_unique_code(4);?>" >   <?php echo $lang['OPERATIONS_DROP_TITLE_1'];?></a></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="departmentSearchForm" method="get" action="./operations.php" enctype="multipart/form-data">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="<?php echo $lang['OPERATIONS_SEARCH_PLACEHOLDER'];?>" id="departmentSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="newTransactionForm"  enctype="multipart/form-data">
                   
                    <?php 
						if($add == 1){
							echo alert_message("success",$lang['OPERATIONS_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['OPERATIONS_FAILD']);
						}
					?>
                     <h5 ><?php echo $lang['OPERATIONS_ADD'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                       <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_DATE'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" id="operations_date" name="operations_date" class="form-control transactiondetailsInput">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_SUPPLIER'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="supplier_div">
                                            <select name="operations_supplier" id="supplier" class=" form-control transactiondetailsInput">
                                                <option selected disabled value=""> <?php echo $lang['OPERATIONS_SUPPLIER_IN'];?></option>
                                                <?php
													if($suppliers)
													{
														foreach($suppliers as $sId=> $s)
														{
															echo '<option value="'.$s["suppliers_sn"].'"';if(isset($_SESSION['supplier'])){if($s["suppliers_sn"] == $_SESSION['supplier']){echo ' selected';}}echo'>'.$s["suppliers_name"].'</option>';
														}
													}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_CLIENT'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="client">
                                            <select name="operations_customer" id="operations_customer"  class="client form-control">
                                                <option selected disabled><?php echo $lang['OPERATIONS_CLIENT_IN'];?></option>
                                                 <?php
													if($clients)
													{
														foreach($clients as $cId=> $c)
														{
															echo '<option value="'.$c["clients_sn"].'"';if(isset($_SESSION['customer'])){if($c["clients_sn"] == $_SESSION['customer']){echo ' selected';}}echo'>'.$c["clients_name"].'</option>';
														}
													}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['OPERATIONS_PRODUCT'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select" id="product_client">
                                            <select name="operations_product" id="operations_product" class="operations_product form-control transactiondetailsInput">
												<?php if(isset($_SESSION['supplier']))
													{
														$products = $setting_operation->get_client_supplier_product($_SESSION['supplier'],$_SESSION['customer']);
														if ($products)
														{
															echo'<option selected disabled>'.$lang["SETTINGS_BAN_CHOOSE_PRODUCT"].'</option>';
															foreach($products as $k => $p)
															{
																echo '<option value="'.$p["products_sn"].'">'.$p["products_name"].'</option>';
															}
														}else{
															echo '<option selected disabled>'.$lang["NO_CLIENT_PRODUCT"].'</option>';
														}
													}else{
														echo'<option selected disabled>'.$lang['OPERATIONS_CHOOSE_CLIENT_FIRST'].'</option>';
													}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="transactionDetailsRowContainer" >
                            <!-- transaction Details Row -->
                            <div class="row hideRow" id="transactionDetailsRow">
                                <div class="col">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="col-xs-3"><?php echo  $lang['OPERATIONS_RECIEPT']?></label>
												<div class="col-xs-5">
													<input type="text" class="form-control" name="operations_receipt" readonly
														placeholder="----" value="<?php echo  get_last_reseipt()?>">
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-xs-3"><?php echo  $lang['OPERATIONS_SUPPLER_PRICE']?></label>
														<div class="col-xs-5">
															<input type="text" class="cullc_price form-control" id="operations_supplier_price" name="operations_supplier_price"
																readonly placeholder="----" value="">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-xs-3"> <?php echo  $lang['OPERATIONS_CLIENT_PRICE']?></label>
														<div class="col-xs-5">
															<input type="text" class="form-control" id="operations_customer_price" name="operations_customer_price"
																readonly placeholder="----" value="">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="col-xs-3"><?php echo  $lang['OPERATIONS_CARD_SERIAL']?></label>
												<div class="col-xs-5">
													<input type="text" class="form-control" name="operations_card_number" placeholder="-----">
												</div>
											</div>
										</div>
									</div>
									<div id="bonus">
										
									</div>
                                    <div id="calc">
                                        <div class="row justify-content-start align-items-baseline mb-5 " id="mainInputs">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo  $lang['OPERATIONS_QUANTITY'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="cullc_price form-control" id="operations_quantity" name="operations_quantity"
                                                            placeholder="----">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo  $lang['OPERATIONS_GENERAL_DIS'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="form-control" id="operations_general_discount" name="operations_general_discount"
                                                            placeholder="----">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="col-xs-3"><?php echo  $lang['OPERATIONS_QUANTITY_AFTER'];?></label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="cullc_price form-control" id="operations_net_quantity" name="operations_net_quantity" readonly placeholder="----">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Quality Items container row  -->
                                        <div class="mb-5" id="qualityItemsContainer">
                                        </div>
                                    </div>
								<!-- end Quality Items container row -->

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class="row">
												<div class="col-md-7 ">
													<label class="col-xs-3"><?php echo $lang['OPERATIONS_CARD_FRONT'];?></label>
													<div class="upload-btn-wrapper">
														<button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
														<input type="file" class="form-control uploadimage"
															name="operations_card_front_photo">
													</div>
												</div>
												<div
													class="col-md-5 d-flex justify-content-end align-items-end mb-2">
													<img src="<?php echo  $path;?>/defaults/img-icon.png" height="50px"
														class="imagePreviewURL" alt="">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="row">
												<div class="col-md-7 ">
													<label class="col-xs-3"> <?php echo  $lang['OPERATIONS_CARD_BACK'];?></label>
													<div class="upload-btn-wrapper">
														<button class="btn"><?php echo  $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
														<input type="file" class="form-control uploadimage"
															name="operations_card_back_photo">
													</div>
												</div>
												<div
													class="col-md-5 d-flex justify-content-end align-items-end mb-2">
													<img src="<?php echo  $path;?>/defaults/img-icon.png" height="50px"
														class="imagePreviewURL" alt="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
                         
                            </div>
                            <!-- transaction Details Row end -->
                        </div>
							<input type="text" class="form-control" name="add_other" hidden>
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                           <span>
                                <button class="btn roundedBtn mr-2" type="submit"> <?php echo $lang['SETTINGS_C_SAVE'];?></button>
                                <button class="add_other btn roundedBtn mr-2" type="submit"> <?php echo $lang['OPERATIONS_SUM'];?></button>
<!--
                                <a href="./operation_sum.php?code=<?php echo $_GET['o'];?>" >
                                    <button class="add_other btn roundedBtn" type="button"> <?php echo $lang['OPERATIONS_SUM'];?></button>
                                </a>
-->
                            </span>
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


include  './assets/layout/footer.php';?>

<SCRIPT>
  $(document).ready(function () {
  var rate_count = 0;

   var validation = $('#newTransactionForm').formValidation({
        excluded: [':disabled'],
        fields: {
            operations_date: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['OPERATIONS_DATE_IN'];?> '
                    }
                }
            },
            operations_supplier: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['OPERATIONS_SUPPLIER_IN'];?>'
                    }
                }
            },
            operations_customer: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['OPERATIONS_CLIENT_IN'];?>  '
                    }
                }
            },
            operations_product: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['OPERATIONS_PRODUCT_IN'];?>'
                    }
                }
            },
            operations_card_number: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['OPERATIONS_CARD_IN'];?>'
                    },
                    digits: {
                        message: ' <?php echo $lang['SETTINGS_C_F_NUMBER_ON'];?>'
                    }
                }
            },
            operations_quantity: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['OPERATIONS_QUANTITY_IN'];?>'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: '  <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            },
            operations_general_discount: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['OPERATIONS_GENERAL_DIS_IN'];?>'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            }

        }
    }).on('success.form.bv', function (e) {
    })
    .on('added.field.fv', function (e, data) {
            if (data.field.includes('qualityName')) {
            }
        });


	$('#client').on('change','select.client',function(){
        var client     = $(this).val();
        var supplier   = $('#supplier').val();
        var page   ="operations_js.php?do=product";
        if(client && supplier){
            $.ajax({
                type:'POST',
                url:page,
                data:{supplier:supplier,client:client},
                success:function(html)
                {
                   $('select#operations_product').html(html); 
                }
                });
        }
	});
     
    $('.transactiondetailsInput').on('change', function () {
        if ($('.transactiondetailsInput').filter(function () {
            return $.trim($(this).val()).length == 0
        }).length == 0) {
            var id     = $('#operations_product').val();
            var client = $('#operations_customer').val();
            var date   = $('#operations_date').val();
            var page   ="operations_js.php?do=product_rate";
            if(id && client && date){
                $.ajax({
                        type:'POST',
                        url:page,
                        dataType: "json",
                        data:{id:id,client:client,date:date},
                        success:function(responce)
                        {
                            $('div#qualityItemsContainer').html(responce['products']);
                            $('div#bonus').html(responce['bonus']);
                            rate_count = responce['count'];
                            $('#transactionDetailsRow').removeClass('hideRow');
                        }
                    });
            }
            
        }
     });
	$('#supplier_div').on('change','select#supplier',function(){
		
		  $('#operations_customer').prop('selectedIndex',0);
		  $('#transactionDetailsRow').addClass('hideRow');

	  });
	
 	$('#operations_quantity').change(function(){
	 var operations_quantity                =  $('#operations_quantity').val(); // c
        var operations_general_discount     =  $('#operations_general_discount').val(); // d
		var e                               =  operations_quantity - (operations_quantity * (operations_general_discount / 100));
        $('#operations_net_quantity').val(e); 
        cullc_price();
	 });
      
      
	$('#operations_general_discount').change(function(){
		 var operations_quantity            =  $('#operations_quantity').val(); // c
        var operations_general_discount     =  $('#operations_general_discount').val(); // d
		var e                               =  operations_quantity - (operations_quantity * (operations_general_discount / 100));
        $('#operations_net_quantity').val(e); 
        cullc_price();
	 });
	var new_quantity=0;
	var supplier_price = 0;
	var customer_price = 0;
	var i_actall = 0;
      
	$('#qualityItemsContainer').on('keyup','.rate_percentage',function(){ 

        var operations_quantity             =  $('#operations_quantity').val(); // c
        var operations_general_discount     =  $('#operations_general_discount').val(); // d
		var e                               =  operations_quantity - (operations_quantity * (operations_general_discount / 100));
		$('#operations_net_quantity').val(e);  // e = c-(c*d)
		var rate_id                         =  $(this).attr('id').replace('rate_percentage_','').replace('excuse_percentage_','').replace('discount_percentage_','');
		var pricing_rate_type               =  $('#pricing_rate_type_'+rate_id).val();
		var a                               =  $('#pricing_rate_percent_'+rate_id).val();
		var pricing_selling_price           =  $('#pricing_selling_price_'+rate_id).val();
		var pricing_supply_rate             =  $('#pricing_supply_rate_'+rate_id).val();
		var pricing_supply_price            =  pricing_selling_price - (pricing_selling_price * (pricing_supply_rate/100)).toFixed(2);
		$('#pricing_supply_price_'+rate_id).val(pricing_supply_price);
		var pricing_excuse_price            =  $('#pricing_excuse_price_'+rate_id).val();
		if(pricing_rate_type == 'amount' || pricing_rate_type == 'not')
		{
			var f                           =  $('#rate_percentage_'+rate_id).val() - a;  // f
			var g                           =  $('#discount_percentage_'+rate_id).val();  // g
			var e_alfa                      =  parseInt((e-(e*(g/100))));
			var h                           =  $('#excuse_percentage_'+rate_id).val();  // h
			var i_alfa                      =  ((f-h)/100) * e_alfa;
			var j_alfa                      =  ((h/100)* e_alfa);
			var i                           =  ((i_alfa -(i_alfa*(g/100))) + e_alfa ).toFixed(2)//i
			var rate_quantity               =  $('#rate_quantity_'+rate_id).val(i);  // i
			var j							=  j_alfa - (j_alfa*(g/100)).toFixed(2);
			var excuse_quantity             =  $('#excuse_quantity_'+rate_id).val(j);  // j
			 excuse_price                =  (j * pricing_excuse_price);
			 supplier_price              =  (i*pricing_supply_price )+excuse_price;
			 customer_price              =  (i*pricing_selling_price) + excuse_price;
			$('#operations_supplier_price').val(supplier_price);
			$('#operations_customer_price').val(customer_price);
		}else if(pricing_rate_type  == 'extra' || pricing_rate_type == 'not')
		{
            var f                           =  $('#rate_percentage_'+rate_id).val() - a; 
			var g                           =  $('#discount_percentage_'+rate_id).val();  // g

		    if(rate_id == '1')
			{
				var new_quantity    =   parseInt((e-(e*(g/100))));
			}else{
				var pre_quantity= 0;
				var new_quantity = 0;
				var all_quantity = 0;
				for(i=1;i<rate_id;i++)
				{
                    var a_al             =  $('#pricing_rate_percent_'+i).val();
					var rate_percentage  =  $('#rate_percentage_'+i).val()-a_al;
                    if(i==1)
                    {
                        var rate_quantity    = (e*(rate_percentage/100));
                    }else{
                        for(j=i; j<rate_id;j++)
                        {
                            var a_al             =  $('#pricing_rate_percent_'+j).val();
					        var rate_percentage  =  $('#rate_percentage_'+j).val()-a_al;
                            if(j==1)
                            {
                                var pre_quantity     =  (e*(rate_percentage/100));
                            }else{
                                var k = j-1;
                                var a_al_new             =  $('#pricing_rate_percent_'+k).val();
					            var rate_percentage_new  =  $('#rate_percentage_'+k).val()-a_al_new;
                                var e_new            =  e -((e*(rate_percentage_new/100)));
                                var pre_quantity     =  (e_new*(rate_percentage_new/100));
                            }
                             all_quantity    += pre_quantity;
                        }
                        var rate_quantity    = all_quantity;
                    }
					 pre_quantity       += rate_quantity;
				}
				if(pre_quantity > 0)
				{
					quantity        =  e - pre_quantity
				}else{
					quantity     =  parseInt((e-(e*(f/100))));
				}
                 new_quantity     =  parseInt((quantity-(quantity*(g/100))));
			}

			
			var f                           =  $('#rate_percentage_'+rate_id).val() - a;  // f
			var h                           =  $('#excuse_percentage_'+rate_id).val();  // h
			var i_alfa                      =  ((f-h)/100) * new_quantity;
			if(rate_id == rate_count)
			{
				i_actall                        =  ((((f-h)/100) * new_quantity) + new_quantity).toFixed(2);

				var j							=  (new_quantity*(h/100)).toFixed(2);
			    var excuse_quantity             =  $('#excuse_quantity_'+rate_id).val(j);  // j
			}else{
				var j							=  (new_quantity*(h/100)).toFixed(2);
			    var excuse_quantity             =  $('#excuse_quantity_'+rate_id).val(j);  // j
				 i_actall                       =   i_alfa.toFixed(2);
			}
			$('#rate_quantity_'+rate_id).val(i_actall)
			var excuse_price                =  (j * pricing_excuse_price);
			 supplier_price              =  ((i_actall*pricing_supply_price)+excuse_price).toFixed(2);
			 customer_price              =  ((i_actall*pricing_selling_price)+excuse_price).toFixed(2);
		}
		

		cullc_price();
			

	});
	  
   function cullc_price (){
		var s_price = 0;
		var c_price = 0;
		for(i=1;i<=rate_count;i++)
		{
			var rate_quantity               =  parseInt($('#rate_quantity_'+i).val()) || 0;
			var excuse_quantity             =  parseInt($('#excuse_quantity_'+i).val()) || 0;  // j
			var selling_price               =  $('#pricing_selling_price_'+i).val() || 0;
			var pricing_supply_rate         =  $('#pricing_supply_rate_'+i).val();
			var supply_price                =  selling_price - (selling_price * (pricing_supply_rate/100)).toFixed(2);
			$('#pricing_supply_price_'+i).val(supply_price);
			var excuse_price                =  $('#pricing_excuse_price_'+i).val() * excuse_quantity;
			supplier_price                  =  parseInt(((rate_quantity*supply_price)+(excuse_price))).toFixed(2);
			customer_price                  =  parseInt(((rate_quantity*selling_price)+(excuse_price))).toFixed(2);
			s_price  +=parseInt(supplier_price);
			c_price  +=parseInt(customer_price);
		}
		$('#operations_supplier_price').val(s_price);
		$('#operations_customer_price').val(c_price);
   }
//   $('#calc').keyup(function(){
//          $('#qualityItemsContainer').trigger('keyup');
//          $('.rate_percentage').trigger('change');
//          cullc_price();
//
//      });
	  
	  
})


</SCRIPT>



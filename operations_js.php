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
    include("./inc/Classes/system-settings_clients.php");
	$setting_clients = new systemSettings_clients();
	include("./inc/Classes/system-operations.php");
	$setting_operation = new systemOperations();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		switch($_GET['do'])
		{
			case"delete_expence":
				if($_POST)
					{
						include("./inc/Classes/system-expenses.php");
						$expenses = new systemExpenses();
						$id = intval($_POST['id']);
						$delete = $expenses->deleteExpence($id);
						if ($delete ==1)
						{
							echo 100;
						}else{
							echo 200;
						}
						
						
						exit;
					}
			case"delete":
				if($_POST)
					{
						$id = intval($_POST['id']);
						$delete = $setting_operation->deleteOperation($id);
						if ($delete ==1)
						{
							echo 100;
						}else{
							echo 200;
						}
						
						
						exit;
					}
            case"product":
				if($_POST)
					{
						$client = intval($_POST['client']);
						$supplier = intval($_POST['supplier']);
						$products = $setting_operation->get_client_supplier_product($supplier,$client);
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
						exit;
					}
				
			case"product_rate":
				if($_POST)
				{
					$id       = intval($_POST['id']);
					$client   = intval($_POST['client']);
					$date     = sanitize($_POST['date']);
					$products = $setting_operation->get_client_product_rate($id,$client,$date);
					if($products)
					{
						$count =count($products);
						foreach($products as $k => $p)
						{
							$k = $k+1;
							$pricing_suplly[$k] = $p['pricing_supply_bonus'];
							$pricing_client[$k] = $p['pricing_client_bonus'];
							
							$html_rate .='<div class="qualityItemRow" id="qualityItem'.$k.'">
												<div class="row justify-content-start align-items-baseline">
													<div class="col-md-2">
														<div class="form-group">
															<label class="col-xs-3">'.$lang["SETTINGS_C_F_M_RATE"].$k.'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price form-control" name="qualityName1['.$k.']" readonly="" placeholder="" value="'.$p['clients_products_rate_name'].'" >
																<input type="number" class="cullc_price" name="rates_product_rate_id['.$k.']"  value="'.$p['clients_products_rate_sn'].'" hidden>
																<input type="text" class="cullc_price" name="pricing_supply_price['.$k.']" id="pricing_supply_price_'.$k.'" value="'.$p['pricing_supply_price'].'" hidden>
																<input type="text" class="cullc_price" name="pricing_selling_price['.$k.']" id="pricing_selling_price_'.$k.'" value="'.$p['pricing_selling_price'].'" hidden>
																<input type="text" class="cullc_price" name="rates_product_rate_excuse_price['.$k.']" id="pricing_excuse_price_'.$k.'" value="'.$p['pricing_excuse_price'].'" hidden>
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="col-xs-3">'.$lang["OPERATIONS_RATE_DES_SUPPLIER"].'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price form-control" name="rates_supplier_discount_percentage['.$k.']" placeholder="0" value="'.$p['pricing_supply_percent'].'" >
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="col-xs-3">'.$lang["OPERATIONS_VALUE_DES_SUPPLIER"].'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price rate_percentage form-control" name="rates_supplier_discount_value['.$k.']" placeholder="0" value="'.$p['pricing_supply_price'].'" >
															</div>
														</div>
													</div>
												</div>
												<div class="row justify-content-start align-items-baseline">
													<div class="col-md-2" '; if($p['pricing_rate_type'] == 'not'){ $html_rate .= 'style="display: none;" ';} $html_rate .='>
															<div class="form-group">
																<label class="col-xs-3">'.$lang["SETTINGS_C_F_M_PERCENT"].'</label>
																<div class="col-xs-5">
																	<input type="text" class="cullc_price rate_percentage form-control" id="rate_percentage_'.$k.'" name="rates_product_rate_percentage['.$k.']" ';if($p['pricing_rate_type'] == 'amount'){$html_rate .= 'max="'.$p['pricing_rate_percent'].'"  value = "'.$p['pricing_rate_percent'].'" '   ;}elseif($p['pricing_rate_type'] == 'extra'){ $html_rate .= ' value = "'.$p['pricing_rate_percent'].'"';}elseif($p['pricing_rate_type'] == 'not'){ $html_rate .= ' value = "0" hidden';} $html_rate .=' id="check_number" placeholder="0" >
																	<input type="number" name="cullc_price pricing_rate_percent['.$k.']" id="pricing_rate_percent_'.$k.'"  value="'.$p['pricing_rate_percent'].'" hidden >
																	<input type="text" name="cullc_price pricing_rate_type['.$k.']" id="pricing_rate_type_'.$k.'"  value="'.$p['pricing_rate_type'].'" hidden >
																</div>
															</div>
														</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="col-xs-3">'.$lang["OPERATIONS_VALUE_PRECENT"].'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price rate_percentage  form-control" id="discount_percentage_'.$k.'" name="rates_product_rate_discount_percentage['.$k.']" placeholder="0" >
															</div>
														</div>
													</div>
													<div class="col-md-2" ';if($p['pricing_excuse_active'] != 'on'){ $html_rate .= ' style="display: none;" ';} $html_rate .= '>
														<div class="form-group">
															<label class="col-xs-3">'.$lang["SETTINGS_C_F_PERCENT_EXUCE"].'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price rate_percentage  form-control" id="excuse_percentage_'.$k.'" name="rates_product_rate_excuse_percentage['.$k.']" placeholder="0" ';if($p['pricing_excuse_active'] == 'on'){ $html_rate .= 'value="'.$p['pricing_excuse_percent'].'" max="'.$p['pricing_excuse_percent'].'"';}else{$html_rate .= ' value = "0" hidden';}; $html_rate .='>
																<input type="text" class="cullc_price pricing_excuse_price  form-control" id="pricing_excuse_price'.$k.'" name="pricing_excuse_price['.$k.']" placeholder="0" ';if($p['pricing_excuse_active'] == 'on'){ $html_rate .= 'value="'.$p['pricing_excuse_price'].'"  ' ;} $html_rate .='hidden>
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="col-xs-3">'.$lang["OPERATIONS_RATE_QANTITY"].'</label>
															<div class="col-xs-5">
																<input type="text" class="cullc_price form-control" id="rate_quantity_'.$k.'" name="rates_product_rate_quantity['.$k.']" readonly="" placeholder="0" >
															</div>
														</div>
													</div>
													<div class="col-md-2" ';if($p['pricing_excuse_active'] != 'on'){ $html_rate .= ' style="display: none;" ';} $html_rate .= '>
															<div class="form-group">
																<label class="col-xs-3">'.$lang['OPERATIONS_EXUCE_QANTITY'].'</label>
																<div class="col-xs-5">
																	<input type="text" class="cullc_price form-control" id="excuse_quantity_'.$k.'" name="rates_product_rate_excuse_quantity['.$k.']" readonly="" placeholder="0" >
																</div>
															</div>
													</div>
												</div>
											</div>';
						}
						
						if(in_array('yes',$pricing_client))
						{
							$bonus ='<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="blueSky">'. $lang['OPERATIONS_BON_1'].' <a class="whiteText"  href=""> '. $lang['OPERATIONS_CLIENT'].'</a> , '. $lang['OPERATIONS_BON_2'].'
											</label>
											<div class="col-xs-5 ">
												<div class="col-xs-5  d-flex ">
													<div class="form-check radioBtn d-inline-block">
														<input class="cullc_price form-check-input" type="radio"
															name="customerBouns" id="customerBouns1" value="yes"
															checked>
														<label class="form-check-label" for="customerBouns1">
															'. $lang['YES'].'
														</label>
													</div>
													<div class="form-check radioBtn d-inline-block ml-5">
														<input class="cullc_price form-check-input" type="radio"
															name="customerBouns" id="customerBouns2" value="no">
														<label class="form-check-label " for="customerBouns2">
															'. $lang['NO'].'
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';
						}
						if(in_array('yes',$pricing_suplly))
						{
						$bonus.='<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="blueSky">'. $lang['OPERATIONS_BON_1'].' <a class="whiteText"  href=""> '. $lang['OPERATIONS_SUPPLIER'].'</a> , '. $lang['OPERATIONS_BON_2'].'
										</label>
										<div class="col-xs-5 ">
											<div class="col-xs-5  d-flex ">
												<div class="form-check radioBtn d-inline-block">
													<input class="cullc_price form-check-input" type="radio"
														name="supplierBouns" id="supplierBouns1" value="yes"
														checked>
													<label class="form-check-label" for="supplierBouns1">
														'. $lang['YES'].'
													</label>
												</div>
												<div class="form-check radioBtn d-inline-block ml-5">
													<input class="cullc_price form-check-input" type="radio"
														name="supplierBouns" id="supplierBouns2" value="no">
													<label class="form-check-label " for="supplierBouns2">
														'. $lang['NO'].'
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
						}
						$data   = array(
								"count"     => $count,
								"bonus"     => $bonus,
								"products"  => $html_rate,
						);
						echo json_encode($data);
						exit;
					}else{
						$products ='<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-xs-12">'.$lang["OPERATIONS_INSERT_PRICE_FIRST"].'</label>
											<div class="col-xs-2">
											</div>
										</div>
									</div>
								</div>';
						$data   = array(
								"products"  => $products,
						);
						echo json_encode($data);
						exit;
					}
					exit;
				}	
			
			case"product_rate_select":
				if($_POST)
				{
					$id = intval($_POST['id']);
					$products = $setting_clients->get_client_product_rate($id);
					if ($products)
					{
						foreach($products as $k => $p)
						{
							echo '<option value="'.$p["clients_products_rate_sn"].'">'.$p["clients_products_rate_name"].'</option>';
						}
					}else{
						echo '<option selected disabled>'.$lang["NO_PRODUCT_RATE"].'</option>';
					}
					
				}
		}
    }

?>




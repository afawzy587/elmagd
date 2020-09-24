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
	include("./inc/Classes/system-clients_pricing.php");
	$pricing = new systemClients_pricing();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		switch($_GET['do'])
		{
			case"delete":
			if($_POST)
			{
				$id = intval($_POST['id']);
				$delete = $pricing->deleteClients_pricing($id);
				if ($delete ==1)
				{
					echo 100;
				}else{
					echo 200;
				}
				
				
				exit;
			}
            case"bank_account":
			if($_POST)
			{
				include("./inc/Classes/system-settings_banks.php");
				$setting_bank = new systemSettings_banks();
				$id       = intval($_POST['id']);
				$type     = sanitize($_POST['type']);
				$count    = $setting_bank->get_bank_account($id);
				if ($count)
				{
					echo'<option selected disabled>'.$lang["SETTINGS_C_F_ACO_IN"].'</option>';
					foreach($count as $k => $c)
					{
						echo '<option value="'.$c["banks_credit_sn"].'">'.$c["banks_credit_name"].'</option>';
					}
				}else{
					echo '<option selected disabled>'.$lang["NO_COUNT"].'</option>';
				}
				
				
				exit;
			}
				
            case"client_product":
			if($_POST)
			{
				$id = intval($_POST['id']);
				$products = $setting_clients->get_client_product($id);
				if ($products)
				{
					echo'<option selected disabled>'.$lang["SETTINGS_BAN_CHOOSE_PRODUCT"].'</option>';
					foreach($products as $k => $p)
					{
						echo '<option value="'.$p["clients_products_sn"].'">'.$p["products_name"].'</option>';
					}
				}else{
					echo '<option selected disabled>'.$lang["NO_CLIENT_PRODUCT"].'</option>';
				}
				
				
				exit;
			}
				
			case"product_rate":
			if($_POST)
			{
				
				
				$id = intval($_POST['id']);
				$products = $setting_clients->get_client_product_rate($id);
				if ($products)
				{
					$count = count($products);
					foreach($products as $k => $p)
					{
						$k = $k+1;
						$last = $pricing->getClients_pricing_last_add($p['clients_products_rate_sn']);
						echo '<div class="row productRow mt-3">
							<div class="col">
								<h5>'.$lang['SETTINGS_C_F_RATE_ADD'].'<span class="blueSky">'.$p['clients_products_rate_name'].'</span> </h5>
								<input name="pricing_product_rate['.$k.']" value ="'.$p['clients_products_rate_sn'].'" hidden>
								<div class="darker-bg centerDarkerDiv formCenterDiv ">
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_START_DATE'].'</label>
												<div class="col-xs-5">
													<input type="date" name="pricing_start_date['.$k.']" class="form-control">
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_END_DATE'].'</label>
												<div class="col-xs-5">
													<input type="date" name="pricing_end_date['.$k.']" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<!------- need edit ----!>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SELLING_NOW'].'</label>
												<div class="col-xs-5">
													<input type="text" name="selling_now['.$k.']" class="form-control" value="'.$last['pricing_selling_price'].'" readonly>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SUPPLY_NOW'].'</label>
												<div class="col-xs-5">
													<input type="text" name="supply_now['.$k.']" class="form-control" value="'.$last['pricing_supply_price'].'" readonly>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SELLING_PRICE'].'</label>
												<div class="col-xs-5">
													<input type="text" name="pricing_selling_price['.$k.']" class="form-control">
												</div>
											</div>
										</div>
										<div class="col-md-6 d-flex justify-content-end align-items-center supplyStateCol">
											<div class="form-check radioBtn" style="margin-top: 26px;">
												<input class="supplyState form-check-input" type="radio" name="supplyState"
													id="supplyState1_'.$k.'" value="1" checked>
												<label class="form-check-label smallerFont" for="supplyState1_'.$k.'">
												</label>
											</div>
											<div class="form-group" style="width: 82%;">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SUPPLY_PRICE'].'</label>
												<div class="col-xs-5">
													<input type="text" name="pricing_supply_price['.$k.']" id="pricing_supply_price_'.$k.'" class="form-control supplyStateInput">
												</div>
											</div>

										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SUPPLY_PERCENT'].'</label>
												<div class="col-xs-5">
													<input type="text" name="pricing_supply_percent_now['.$k.']"  value="'.$last['pricing_supply_percent'].'" class="form-control" readonly>
												</div>
											</div>
										</div>
										<div class="col-md-6 d-flex justify-content-end align-items-center supplyStateCol">
											<div class="form-check radioBtn" style="margin-top: 26px;">
												<input class="supplyState form-check-input" type="radio" name="supplyState"
													id="supplyState2_'.$k.'" value="2" >
												<label class="form-check-label smallerFont" for="supplyState2_'.$k.'">
												</label>
											</div>
											<div class="form-group " style="width: 82%;">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_SUPPLY_PER'].'</label>
												<div class="col-xs-5">
													<input type="text" name="pricing_supply_percent['.$k.']" id="pricing_supply_percent_'.$k.'" class="form-control supplyStateInput">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_PRICE_EXUCE'].'</label>
												<div class="col-xs-5">
													<input type="text" id="pricing_excuse_price'.$k.'" name="pricing_excuse_price['.$k.']" class="form-control">
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_PERCENT_EXUCE'].'</label>
												<div class="col-xs-5">
													<input type="text" id="pricing_excuse_percent_'.$k.'" name="pricing_excuse_percent['.$k.']" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<div class="col-xs-5  d-flex flex-column">
													<div class="form-check radioBtn">
														<input class="pricing_excuse_active form-check-input" type="radio" name="pricing_excuse_active['.$k.']"
															id="activatePrice_'.$k.'" value="on" checked>
														<label class="form-check-label smallerFont" for="activatePrice_'.$k.'">
															'.$lang['SETTINGS_C_F_EXUCE_ACTIVE'].'
														</label>
													</div>
													<div class="form-check radioBtn ">
														<input class="pricing_excuse_active form-check-input" type="radio" name="pricing_excuse_active['.$k.']"
															id="activatePrice2_'.$k.'" value="off">
														<label class="form-check-label smallerFont" for="activatePrice2_'.$k.'">
															'.$lang['SETTINGS_C_F_EXUCE_NOT_ACTIVE'].'
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="col-xs-3">'.$lang['SETTINGS_C_F_RATE_PERCENT'].'</label>
												<div class="col-xs-5 ">
													<input type="text" id="pricing_rate_percent_'.$k.'" name="pricing_rate_percent['.$k.']" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<div class="col-xs-5  d-flex flex-column">';
												 if($count == 1)
												 {
													echo'<input class="pricing_rate_type form-check-input" type="text" name="pricing_rate_type['.$k.']" id="qualityDetail_'.$k.'" value="amount" hidden>
                                                    
                                                    <div class="row">
                                                        <div class="col">
                                                            <input class="pricing_rate_off customized-checkbox" id="customized-checkbox-'.$k.'" type="checkbox" name="check[]" value="not" >
                                                            <label class="customized-checkbox-label" for="customized-checkbox-1">'.$lang['SETTINGS_C_F_NOT_WORK'].'</label>
                                                        </div>
                                                    </div>
													'; 
												 }else
												 {
                                                     echo'<input class="pricing_rate_type form-check-input" type="text" name="pricing_rate_type['.$k.']" id="qualityDetail_'.$k.'" value="extra" hidden >';
                                                 }
													
													
												echo'
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_CLIENT_BOUNC'].'</label>
														<div class="col-xs-5  ">
															<div class="form-check radioBtn">
																<input class="pricing_client_bonus form-check-input" type="radio"
																	name="pricing_client_bonus['.$k.']" id="customerBouns_'.$k.'" value="yes"
																	checked>
																<label class="form-check-label smallerFont"
																	for="customerBouns_'.$k.'">
																	'.$lang['SETTINGS_C_F_YES'].'
																</label>
															</div>
															<div class="form-check radioBtn ">
																<input class="pricing_client_bonus form-check-input" type="radio"
																	name="pricing_client_bonus['.$k.']" id="customerBouns2_'.$k.'" value="no">
																<label class="form-check-label smallerFont"
																	for="customerBouns2_'.$k.'">
																	'.$lang['SETTINGS_C_F_NO'].'
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_PERCENT_EXTRA'].'
															(<span class="blueSky">'.$lang['SETTINGS_C_F_YES'].'</span>)</label>
														<div class="col-xs-5">
															<input type="text" name="pricing_client_bonus_percent['.$k.']" id="pricing_client_bonus_percent_'.$k.'"
																class="form-control">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_AMOUNT'].'<span class="blueSky">'.$lang['SETTINGS_C_F_DALY'].'</span>
															'.$lang['SETTINGS_C_F_KG_A'].'</label>
														<div class="col-xs-5">
															<input type="text" name="pricing_client_bonus_amount['.$k.']" id="pricing_client_bonus_amount_'.$k.'" class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_SUPPLY_BOUNC'].'</label>
														<div class="col-xs-5  ">
															<div class="form-check radioBtn">
																<input class="pricing_supply_bonus form-check-input" type="radio"
																	name="pricing_supply_bonus['.$k.']" id="supplierBouns1_'.$k.'" value="yes"
																	checked>
																<label class="form-check-label smallerFont"
																	for="supplierBouns1_'.$k.'">
																	'.$lang['SETTINGS_C_F_YES'].'
																</label>
															</div>
															<div class="form-check radioBtn ">
																<input class="pricing_supply_bonus form-check-input" type="radio"
																	name="pricing_supply_bonus['.$k.']" id="supplierBouns2_'.$k.'" value="no">
																<label class="form-check-label smallerFont"
																	for="supplierBouns2_'.$k.'">
																	'.$lang['SETTINGS_C_F_NO'].'
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_PERCENT_EXTRA'].'
															(<span class="blueSky">'.$lang['SETTINGS_C_F_YES'].'</span>)</label>
														<div class="col-xs-5">
															<input type="text" name="pricing_supply_bonus_percent['.$k.']" id="pricing_supply_bonus_percent_'.$k.'"
																class="form-control">
														</div>
													</div>
													
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="form-group">
														<label class="col-xs-3">'.$lang['SETTINGS_C_F_PERCENT_EXTRA'].'
															(<span class="blueSky">'.$lang['SETTINGS_C_F_YES'].'</span>)</label>
														<div class="col-xs-5">
															<input type="text" name="pricing_supply_bonus_amount['.$k.']" id="pricing_supply_bonus_amount_'.$k.'"
																class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					}
				}else{
					echo '<option selected disabled>'.$lang["NO_CLIENT_PRODUCT"].'</option>';
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
//					echo'<option selected disabled>'.$lang["SETTINGS_BAN_CHOOSE_PRODUCT"].'</option>';
					foreach($products as $k => $p)
					{
						echo '<option value="'.$p["clients_products_rate_sn"].'">'.$p["clients_products_rate_name"].'</option>';
					}
				}else{
					echo '<option selected disabled>'.$lang["NO_PRODUCT_RATE"].'</option>';
				}
				
			}
				
			case"supplier_product":
			if($_POST)
			{
				include("./inc/Classes/system-settings_suppliers.php");
				$setting_supplier = new systemSettings_suppliers();
				$id = intval($_POST['id']);
				$products = $setting_supplier->get_supplier_product($id);
				if ($products)
				{
					echo'<option selected disabled>'.$lang["SETTINGS_BAN_CHOOSE_PRODUCT"].'</option>';
					foreach($products as $k => $p)
					{
						echo '<option value="'.$p["clients_products_sn"].'">'.$p["products_name"].'</option>';
					}
				}else{
					echo '<option selected disabled>'.$lang["NO_CLIENT_PRODUCT"].'</option>';
				}
				
				
				exit;
			}
		}
    }

?>




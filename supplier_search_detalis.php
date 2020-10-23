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
	include("./inc/Classes/system-suppliers_collectible.php");
	$suppliers_collectible = new systemSuppliers_collectible();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_department'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['product'] != 0 && $_GET['start'] != 0 && $_GET['end'] != 0)
			{
				$operations = $suppliers_collectible->Getcolection_details($_GET);
			}else{
				header("Location:./error.php");
				exit;
			}

			$logs->addLog(NULL,
					array(
						"type" 		        => 	"user",
						"module" 	        => 	"client_search_result",
						"mode" 		        => 	"list",
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_CLIENT'];?></span>
                    <a class="blueSky" href="<?php echo $_SESSION['page'];?>"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_FINANCES'];?></a>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['C_S_SEARCH_RESULT'];?></span>
                </p>
            </div>
        </div>
        <!-- account details row -->
        <div class="row justify-content-center mb-5">
            <div class="col text-center">
                <h5><?php echo $lang['OPERATIONS_M_SU_FINANCE'];?> <strong class="blueSky"><?php echo get_data('settings_suppliers','suppliers_name','suppliers_sn',$operations[0]['operations_supplier']);?></strong></h5>
            </div>
        </div>
        <!-- end account details row -->
        <?php
		if(is_array($operations))
		{
		  foreach($operations as $k => $o)
		  {
			  echo'<!-- end links row -->
					<!-- search results row -->
					<div class="row">
						<div class="col">
							<!-- results details -->
							<div class="row">
								<div class="col">
								   <!-- results header -->
									<div class="row">
										<div class="col-md-3">
											<h6>'.$lang['C_S_DATE'].'</h6>
											<p class="tdStyle">
												'._date_format($o['operations_date']).'
											</p>
										</div>
										<div class="col-md-3">
											<h6>'.$lang['SETTINGS_CL_CLIENT'].'</h6>
											<p class="tdStyle">
											'.get_data('settings_clients','clients_name','clients_sn',$o['operations_customer']).'
											</p>
										</div>
									</div>
									<div class="row">
										<div class="col">
										</div>
									</div>
									<div class="row">
										<div class="col">
											<div class="row">
												<div class="col table-responsive">
													<table class="table table-fluid accountDetailsTable" id="">
														<thead>
															<tr>
																<th class="smallFont">'.$lang['OPERATIONS_SERIAL'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_CARD_SERIAL'].'</th>
																<th class="smallFont">'.$lang['SETTINGS_C_F_PRODUCT'].'</th>
																<th class="smallFont">'.$lang['C_S_TOTAL_AMOUNT'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_GENERAL_DIS'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_QUANTITY_AFTER'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_CLIENT_PRICE'].'</th>
																<th class="smallFont">'.$lang['C_S_CLIENT_COLLECT'].'</th>
																<th class="smallFont">'.$lang['C_S_CLIENT_REMIEN'].'</th>
																<th class="smallFont"></th>
															</tr>
														</thead>
														<tbody>
															<tr id="tr_'.$o['operations_sn'].'">
																<td>'.$o['operations_receipt'].'</td>
																<td>'.$o['operations_code'].'</td>
																<td>'.get_data('settings_products','products_name','products_sn',$o['operations_product']).'</td>
																<td>'.number_format($o['operations_quantity']).'</td>
																<td>'.number_format($o['operations_general_discount']).'</td>
																<td id="operations_net_quantity_'.$o['operations_sn'].'">'.number_format($o['operations_net_quantity']).'</td>
																<td id="operations_supplier_price_'.$o['operations_sn'].'">'.number_format($o['operations_supplier_price']).'</td>
																<td id="operations_supplier_paid_'.$o['operations_sn'].'">'.number_format($o['operations_supplier_paid']).'</td>
																<td id="operations_supplier_remain_'.$o['operations_sn'].'">'.number_format($o['operations_supplier_remain']).'</td>
																<td class="text-center tableActions" id="td_'.$o['operations_sn'].'">';
																		if($o['operations_status'] == 1 )
																		{
																			if($group['delete_operation'] == 1 )
																			{
																				echo'<i class="delete_operation fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$o['operations_sn'].'"></i>';
																			}else{
																				echo'-------';
																			}
																		}else{
																			echo '<span class="rose">'.$lang['DELETE_DONE'].'</span>' ;
																		}
																echo'</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>';
			  	                            $product_rate = get_Operation_product($o['operations_sn']);
			  								if(is_array($product_rate))
											{
												echo'<div class="row">
												<div class="col table-responsive">
													<table class="table table-fluid accountDetailsTable" id="">
														<thead>
															<tr>
																<th class="smallFont">'.$lang['SETTINGS_C_F_M_RATE'].'</th>
																<th class="smallFont">'.$lang['SETTINGS_C_F_M_PERCENT'].'</th>
																<th class="smallFont">'.$lang['OPERATION_RATE_DES_SUPPLIER'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_M_DIC'].'</th>
																<th class="smallFont">'.$lang['SETTINGS_C_F_M_EXUCE'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_EXUCE_QANTITY'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_RATE_QANTITY'].'</th>
																<th class="smallFont">'.$lang['SETTINGS_C_F_M_EXUCE_PRICE'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_SUPPLER_PRICE'].'</th>
																<th class="smallFont">'.$lang['OPERATIONS_CLIENT_PRICE'].'</th>
															</tr>
														</thead>
														<tbody>';
                                                        foreach($product_rate as $k => $p){
                                                            echo'<tr>
																    <td>'.get_data('settings_clients_products_rate','clients_products_rate_name','clients_products_rate_sn',$p['rates_product_rate_id']).'</td>
																    <td>'.$p['rates_product_rate_percentage'].'</td>
																    <td>'.$p['rates_supplier_discount_percentage'].'</td>
																    <td>'.$p['rates_supplier_discount_value'].'</td>
																    <td>'.$p['rates_product_rate_excuse_percentage'].'</td>
																    <td>'.$p['rates_product_rate_excuse_quantity'].'</td>
																    <td>'.$p['rates_product_rate_quantity'].'</td>
																    <td>'.$p['rates_product_rate_excuse_price'].'</td>
																    <td>'.$p['rates_product_rate_supply_price'].'</td>
																    <td>'.round((($p['rates_supplier_discount_percentage']/100)*$p['rates_product_rate_supply_price'])+$p['rates_product_rate_supply_price'],2).'</td>
															    	
																</tr>';
                                                        }
															
								                    echo'</tbody>
													</table>
												</div>
											</div>';
											}
                                            
                                            
                                     echo'<div class="lightDivider"></div>
									';
			  $operations_net_quantity    += $o['operations_net_quantity'];
			  $operations_supplier_price   += $o['operations_supplier_price'];
			  $operations_supplier_paid   += $o['operations_supplier_paid'];
			  $operations_supplier_remain += $o['operations_supplier_remain'];
		  }
			echo '
					<div class="row">
						<div class="col table-responsive">
							<table class="table table-fluid accountDetailsTable" id="">
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th class="smallFont">'.$lang['MOUNT_TOTAL'] .'</th>
										<th class="smallFont">'.$lang['FINNACE_TOTAL'] .'</th>
										<th class="smallFont">'.$lang['COLLECTED_TOTAL'] .'</th>
										<th class="smallFont">'.$lang['REMAIN_TOTAL'] .'</th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<td class="emptyTD"></td>
										<td class="emptyTD"></td>
										<td class="emptyTD"></td>
										<td id="MOUNT_TOTAL">'.number_format($operations_net_quantity).'</td>
										<td id="FINNACE_TOTAL">'.number_format($operations_supplier_price).'</td>
										<td id="COLLECTED_TOTAL">'.number_format($operations_supplier_paid).'</td>
										<td id="REMAIN_TOTAL">'.number_format($operations_supplier_remain).'</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
					<!-- end table footer row -->
				</div>
			</div>
			<!-- end results details -->

		</div>
	</div>
	<!-- end search results row -->

	<!-- buttons row -->
	<div class="row mt-2 mb-5">
		<div class="col d-flex justify-content-end">
			<button class="btn roundedBtn mr-2" type="submit">طباعة</button>
		</div>
	</div>
	<!-- end buttons row -->';
		}

		?>
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
$(document).ready( function () {
    $('.accountDetailsTable').DataTable({
        "searching": false,
        "ordering": false,
        "lengthChange": false,
        "info": false,
        "paging": false
    });
	
	$('i.delete_operation').click(function(e){
        e.preventDefault();
		var id               = $(this).attr('id').replace("item_","");
        var page             = "operations_js.php?do=delete";
		
		if (id != 0)
		{
			$.ajax( {
				async :true,
				type :"POST",
				url :page,
				data: {id:id},
				success : function(responce) {

                    if(responce == 100)
                     {
						 if(typeof  directon == "undefined")
						 {
							$("#tr_"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("#tr_"+id).animate({width: 'auto', opacity: '0.9'}, "slow");
							$("#tr_"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
                            $("#tr_"+id).animate({width: 'auto', opacity: '1'}, "slow");
                            $("#td_"+id).html('<span class="rose"><?php echo $lang['DELETE_DONE'];?></span>');
							// $("#tr_"+id).fadeTo(400, 0, function () { $("#tr_" + id).slideUp(400);}); 
							//*************** update total **********************//
							var operations_net_quantity     = parseInt($("#operations_net_quantity_"+id).text().replace(',',''));
							var operations_supplier_price   = parseInt($("#operations_supplier_price_"+id).text().replace(',',''));
							var operations_supplier_paid    = parseInt($("#operations_supplier_paid_"+id).text().replace(',',''));
							var operations_supplier_remain  = parseInt($("#operations_supplier_remain_"+id).text().replace(',',''));
							var MOUNT_TOTAL                 = parseInt($("#MOUNT_TOTAL").text().replace(',','')) - operations_net_quantity;
							var FINNACE_TOTAL               = parseInt($("#FINNACE_TOTAL").text().replace(',','')) - operations_supplier_price;
							var COLLECTED_TOTAL             = parseInt($("#COLLECTED_TOTAL").text().replace(',','')) - operations_supplier_paid;
							var REMAIN_TOTAL                = parseInt($("#REMAIN_TOTAL").text().replace(',','')) - operations_supplier_remain;

							$("#MOUNT_TOTAL").html(MOUNT_TOTAL);
							$("#FINNACE_TOTAL").html(FINNACE_TOTAL);
							$("#COLLECTED_TOTAL").html(COLLECTED_TOTAL);
							$("#REMAIN_TOTAL").html(REMAIN_TOTAL);
						 }
						
                      }
				},
				error : function() {
					return true;
				}
			});
		}
	});


} );
</SCRIPT>



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
	include("./inc/Classes/system-clients_collectible.php");
	$clients_collectible = new systemClients_collectible();

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
				$operations = $clients_collectible->Getcolection_details($_GET);
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
                <h5><?php echo $lang['SETTINGS_C_F_CLIENT_CREDIT'];?> <strong class="blueSky"><?php echo get_data('settings_clients','clients_name','clients_sn',$operations[0]['operations_customer']);?></strong></h5>
            </div>
        </div>
        <!-- end account details row -->
        <?php
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
											<h6>'.$lang['SETTINGS_SU_SUPPLIER'].'</h6>
											<p class="tdStyle">
											'.get_data('settings_suppliers','suppliers_name','suppliers_sn',$o['operations_supplier']).'
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
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>'.$o['operations_receipt'].'</td>
																<td>'.$o['operations_code'].'</td>
																<td>'.get_data('settings_products','products_name','products_sn',$o['operations_product']).'</td>
																<td>'.number_format($o['operations_quantity']).'</td>
																<td>'.number_format($o['operations_general_discount']).'</td>
																<td>'.number_format($o['operations_net_quantity']).'</td>
																<td>'.number_format($o['operations_supplier_price']).'</td>
																<td>'.number_format($o['operations_supplier_paid']).'</td>
																<td>'.number_format($o['operations_supplier_remain']).'</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											
									';
			  $operations_net_quantity    += $o['operations_net_quantity'];
			  $operations_supplier_price   += $o['operations_supplier_price'];
			  $operations_supplier_paid   += $o['operations_supplier_paid'];
			  $operations_supplier_remain += $o['operations_supplier_remain'];
		  }
		echo '<div class="lightDivider"></div>
				<div class="row">
					<div class="col table-responsive">
						<table class="table table-fluid accountDetailsTable" id="">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th class="smallFont"> صافي اجمالي الكمية (كجم)</th>
									<th class="smallFont"> اجمالي حساب العميل (ج.م)</th>
									<th class="smallFont"> اجمالي تحصيل من العميل (ج.م) </th>
									<th class="smallFont"> باقي علي العميل (ج.م)</th>


								</tr>
							</thead>
							<tbody>

								<tr>
									<td class="emptyTD"></td>
									<td class="emptyTD"></td>
									<td class="emptyTD"></td>
									<td>'.number_format($operations_net_quantity).'</td>
									<td>'.number_format($operations_supplier_price).'</td>
									<td>'.number_format($operations_supplier_paid).'</td>
									<td>'.number_format($operations_supplier_remain).'</td>
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


} );
</SCRIPT>



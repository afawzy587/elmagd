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
			
			$q = $_GET['code'];
			
			$operations   = $setting_operation->getOperations_sum($q);
			// print_r($operations);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"operation_sum",
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
                    <a class="blueSky" href="./add_operation.php?o=<?php echo generate_unique_code(4);?>"><strong> &gt; </strong>  <?php echo $lang['OPERATIONS_DROP_TITLE_1'];?> </a>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['OPERATIONS_CALC'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
        <!-- results header -->
        <div class="row justify-content-center mt-3">
            <div class="col-md-3">
                <h6><?php echo $lang['OPERATIONS_CALC_DATE'];?></h6>
                <p class="tdStyle">
                    <?php echo _date_format($operations[0]['operations_date']);?>
                </p>
            </div>
            <div class="col-md-3">
                <h6><?php echo $lang['OPERATIONS_SUPPLIER'];?></h6>
                <p class="tdStyle">
                     <?php echo get_data('settings_suppliers','suppliers_name','suppliers_sn',$operations[0]['operations_supplier'])?>
                </p>
            </div>
            <div class="col-md-3">
                <h6> <?php echo $lang['OPERATIONS_CALC_CODE'];?></h6>
                <p class="tdStyle">
                    <?php echo $q;?>
                </p>
            </div>
        </div>
        <!-- end results header -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="settings_operations" id="table">
            <input type="hidden" value="settings_department" id="permission">
            <div class="col">
              
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($operations))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th class="smallFont"> '.$lang['OPERATIONS_SERIAL'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_RECIEPT_MENU'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_DATE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_CLIENT'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_PRODUCT'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_QU_B_DISC'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_PRE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_RATE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_RATE_PER'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_RATE_DI'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_EX_PRE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_EX_UAN'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_UAN_AFTR'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_EX_PRICE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_SU_PRICE'].'</th>
								<th class="smallFont"> '.$lang['OPERATIONS_M_SU_FINANCE'].'</th>
							</tr>
							</tr>
						</thead>
						<tbody class="shadow">';
						foreach($operations as $k => $u)
						{
							$count = count($u['product']);
							if($count > 1)
							{
								$rowspan = 'rowspan="'.$count.'" class="rowspanTd"';
							}else{
								$rowspan = "";
							}
							$total_operations_supplier_price += $u['operations_supplier_price'];
						
						 echo'<tr id=tr_'.$u['operations_sn'].' role="row">
								<td '.$rowspan.'>'.$u['operations_sn'].'</td>
								<td '.$rowspan.'>'.$u['operations_card_number'].'</td>
								<td '.$rowspan.'>'._date_format($u['operations_date']).'</td>
								<td '.$rowspan.'>'.get_data('settings_clients','clients_name','clients_sn',$u['operations_customer']).'</td>
								<td '.$rowspan.'>'.get_data('settings_products','products_name','products_sn',$u['operations_product']).'</td>
								<td '.$rowspan.'>'.$u['operations_quantity'].'</td>
								<td '.$rowspan.'>'.$u['operations_general_discount'].'</td>
							    ';
								foreach($u['product'] as $pId => $v)
								{
										if($count > 1){

											if($pId == 0){
												echo'
													<td>'.get_data('settings_clients_products_rate','clients_products_rate_name','clients_products_rate_sn',$v['rates_product_rate_id']).'</td>
													<td>'.$v['rates_product_rate_percentage'].'</td>
													<td>'.$v['rates_product_rate_discount_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_quantity'].'</td>
													<td>'.$v['rates_product_rate_quantity'].'</td>
													<td>'.$v['rates_product_rate_excuse_price'].'</td>
													<td>'.$v['rates_product_rate_supply_price'].'</td>
													<td '.$rowspan.'>'.$u['operations_supplier_price'].'</td>
												';

											}else{
												echo'<tr>
													<td>'.get_data('settings_clients_products_rate','clients_products_rate_name','clients_products_rate_sn',$v['rates_product_rate_id']).'</td>
													<td>'.$v['rates_product_rate_percentage'].'</td>
													<td>'.$v['rates_product_rate_discount_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_quantity'].'</td>
													<td>'.$v['rates_product_rate_quantity'].'</td>
													<td>'.$v['rates_product_rate_excuse_price'].'</td>
													<td>'.$v['rates_product_rate_supply_price'].'</td>
												</tr>';
											}
										}else{
											echo'
													<td>'.get_data('settings_clients_products_rate','clients_products_rate_name','clients_products_rate_sn',$v['rates_product_rate_id']).'</td>
													<td>'.$v['rates_product_rate_percentage'].'</td>
													<td>'.$v['rates_product_rate_discount_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_percentage'].'</td>
													<td>'.$v['rates_product_rate_excuse_quantity'].'</td>
													<td>'.$v['rates_product_rate_quantity'].'</td>
													<td>'.$v['rates_product_rate_excuse_price'].'</td>
													<td>'.$v['rates_product_rate_supply_price'].'</td>
													<td '.$rowspan.'>'.$u['operations_supplier_price'].'</td>
												';
										}


								}
								
							echo'
							</tr>';
						}
						
						echo '<tr class="emptyTr">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="btnTd darker-bg"><strong>'.$total_operations_supplier_price.'</strong></td>

							</tr>
						</tbody>';
					}?>
                </table>
				<!--		Start Pagination -->
<!--
				<div class='pull-left pagination-container'>
					<?php echo $pager;?>
				</div>
-->
				<!-- <div class="rows_count">Showing 11 to 20 of 91 entries</div> -->
            </div>
        </div>
        <!-- end table row -->
        
               <!-- buttons row -->
        <div class="row mt-5 mb-5">
            <div class="col d-flex justify-content-end">
                <button class="btn roundedBtn mr-2" type="submit"><?php echo $lang['PRINT'];?></button>
                <a href="./supplier_search_result.php?<?php echo 'supplier='.$operations[0]['operations_supplier'].'&code='.$q;?>">
                    <button class="btn roundedBtn mr-2" type="button"> <?php echo $lang['OPERATIONS_SUPPULIER_FINANCE'];?></button>
                </a>

            </div>
        </div>
        <!-- end buttons row -->
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
    $('#departmentTable').DataTable({
        "searching": false,
        "ordering": false,
        "lengthChange": false,
        "info": false,
		"paginate": false
    });

} );
</SCRIPT>



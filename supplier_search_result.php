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
	$_SESSION['page']  = $actual_link;
	include("./inc/Classes/system-suppliers_collectible.php");
	$suppliers_collectible = new systemSuppliers_collectible();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['supplier_payment'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET)
			{
				$supplier = $suppliers_collectible->GetSupplierFinanceByid(intval($_GET['supplier']));
				$Supplier_Paid = $suppliers_collectible->Get_Supplier_Paid(intval($_GET['supplier']));

				$result   = $suppliers_collectible->GetSearchResult($_GET);
			}else{
				header("Location:./error.php");
				exit;
			}

			$logs->addLog(NULL,
					array(
						"type" 		        => 	"user",
						"module" 	        => 	"supplier_search_result",
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
                    <a class="blueSky" href="./supplier_search.php"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_FINANCES'];?></a>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['C_S_SEARCH_RESULT'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->

        <!-- account details row -->
        <div class="row justify-content-center mb-5">
            <div class="col text-center">
                <h5><?php echo $lang['OPERATIONS_M_SU_FINANCE'];?> <strong class="blueSky"><?php echo $supplier['name'];?></strong></h5>
                <h4 class="d-inline-block bg_text text_height <?php echo $supplier['credit'] < 0 ? "warning" : " ";?> ltrDir"><?php echo number_format($supplier['credit']);?></h4>
            </div>
        </div>
        <!-- end account details row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="settings_departments" id="table">
            <input type="hidden" value="settings_department" id="permission">
            <div class="col">

                <table class="table table-fluid " id="departmentTable">
                   <?php
					if($result[0]['operations_customer'] == 0)
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['OPERATIONS_DATE'].'</th>
								<th>'.$lang['SETTINGS_CL_CLIENT'].'</th>
								<th>'.$lang['OPERATIONS_PRODUCT'].'</th>
								<th>'.$lang['C_S_FROM_SERIAL'].'</th>
								<th>'.$lang['C_S_TO_SERIAL'].'</th>
								<th>'.$lang['C_S_TOTAL'].'</th>
								<th>'.$lang['C_S_PAID'].'</th>
								<th>'.$lang['C_S_REMAIN'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($result as $k => $u)
						{
						 echo'<tr>
								<td>'.$lang['FROM'].' : '. _date_format($u['start_date']).'<br/>'.
							 	$lang['TO'].' : '. _date_format($u['end_date'])
							 	.'</td>
								<td>'.get_data('settings_clients','clients_name','clients_sn',$u['operations_customer']).'</td>
								<td>'.get_data('settings_products','products_name','products_sn',$u['operations_product']).'</td>
								<td>'.$u['start'].'</td>
								<td>'.$u['end'].'</td>
								<td>'.number_format($u['total']).'</td>
								<td>'.number_format($u['paid']).'</td>
								<td>'.number_format($u['remain']).'</td>
								<td class="text-center tableaprove">
									<a href="./supplier_search_detalis.php?product='.$u['operations_product'].'&start='.$u['start'].'&end='.$u['end'].'" class="mr-2">
										<i class="fas fa-eye  dark_blue"></i>
									</a>
								</td>
							</tr>';
							$total  += $u['total'];
							$paid   += $u['paid'];
							$remain += $u['remain'];
						}
						if(is_array($Supplier_Paid))
						{
							foreach($Supplier_Paid as $k => $v){
								echo'<tr>
								<td>'. _date_format($v['collectible_date']).'</td>
								<td colspan="5">'.$lang['P_S_LATER'].'</td>
								<td>'.number_format($v['collectible_value']).'</td>
								<td colspan="2">'.$lang['P_S_RECIPIENT'].' : '.$v['collectible_recipient'].'</td>
							</tr>';
								$paid +=$v['collectible_value'];
							}
						}

						echo'<tr>
								<td class="emptyTD"></td>
								<td class="emptyTD"></td>
								<td class="emptyTD"></td>
								<td class="emptyTD"></td>
								<td class="emptyTD"></td>
								<td>'.number_format($total).'</td>
								<td>'.number_format($paid).'</td>
								<td>'.number_format(($total-$paid)).'</td>
								<td class="emptyTD"></td>

							</tr>';
						echo '</tbody>';
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
        <div class="row mt-2 mb-5">
            <div class="col d-flex justify-content-end">
                <button class="btn roundedBtn mr-2" type="submit"><?php echo $lang['PRINT'];?></button>
                <a href="./add_suppliers_payment.php?<?php echo $url_get_parts;?>"><button class="btn roundedBtn" type="submit"> <?php echo $lang['P_S_PAY_TO_SUPPLIER'];?></button></a>

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
        "language": {
            "paginate": {
              "next": ">",
              "previous": "<"
            }
          }
    });

} );
</SCRIPT>



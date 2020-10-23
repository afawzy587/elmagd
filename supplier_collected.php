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
		if($group['suppliers_collect'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			
			if($_GET)
			{
//				$supplier  = $suppliers_collectible->GetSupplierFinanceByid(intval($_GET['supplier']));
				$collected = $suppliers_collectible->Get_Supplier_Collected($_GET);

				$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"collected_search",
						"mode" 		        => 	"list",
						"id" 	        	=>	$_SESSION['id'],
					),"admin",$_SESSION['id'],1
				);
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
                    <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_C_F_CLIENT']; ?> </span>
               		 <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_C_F_COLLECTED']; ?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- account details row -->
<!--
         <?php 
			if(!$_GET['id']){
				echo'<div class="row justify-content-center mb-5">
            <div class="col text-center">
                <h5>'.$lang['OPERATIONS_M_SU_FINANCE'].' <strong class="blueSky"> '.$supplier['name'].' </strong></h5>
                <h4 class="d-inline-block bg_text text_height ';$supplier['credit'] < 0 ? "warning" : " ";echo'ltrDir">'.number_format($supplier['credit']).'</h4>
            </div>
        </div>';
			}
		?>
-->
        
        <!-- end account details row -->

        <!-- table row -->
        <div class="row">
            <div class="col">
              
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($collected))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['OPERATIONS_DATE'].'</th>
								<th>'.$lang['P_S_PAID_REASION'].'</th>
								<th>'.$lang['SETTINGS_C_F_PAYMENT_TYPE'].'</th>
								<th>'.$lang['C_S_PAID'].'</th>
								<th>'.$lang['TRANSFER_FROM'].'</th>
								<th>'.$lang['P_S_RECIPIENT'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($collected as $k => $u)
						{
						 echo'<tr id=tr_'.$u['collectible_sn'].'>
								<td>'._date_format($u['collectible_date']).'</td>
								<td>';
								if($u['collectible_payment_case'] == 'later')
								{
									echo $lang['P_S_LATER']  .'<br />';
								}elseif($u['collectible_payment_case'] == 'return'){
									echo $lang['OPERTION_RETURN'].' ( '. $u['collectible_recipient'] .' ) <br />';
								}elseif($u['collectible_payment_case'] == 'paid'){
									echo '<span  data-html="true"  data-toggle="popover" title="'.$lang['INVOICES'].'" data-content="'.$suppliers_collectible->get_collect_operation($u['collectible_sn']).'">'.$lang['OPERTION_PAID'].'</span>
										<br />' ;
								}
							echo'</td>
								<td>';
							 	echo $u['collectible_type'] == "cash" ? $lang['SETTINGS_C_F_PAYMENT_CASH'] : $lang['SETTINGS_C_F_PAYMENT_CHEQUE'];
							 	echo'</td>
								<td>'.$u['collectible_value'].'</td>
								<td>';
								if($u['collectible_insert_in'] == "safe"){
									echo $lang['SETTINGS_C_F_SAFE'] ;
								}else{
									echo get_data('settings_banks', 'banks_name', 'banks_sn', $u['collectible_bank_id']) .'<br />' ;
									
									if ($u['collectible_account_type'] == "credit") {
										echo get_data('settings_banks_credit', 'banks_credit_name', 'banks_credit_sn', $u['collectible_account_id']);
									} elseif ($u['collectible_account_type'] == "current") {
										 echo $lang['SETTINGS_BAN_CURRENT'];
									} elseif ($u['collectible_account_type'] == "saving") {
										echo $lang['SETTINGS_BAN_SAVE'];
									}
								} 
								echo '</td>
								<td>';if($u['collectible_payment_case'] == 'return'){echo '-------';}else{ echo $u['collectible_recipient'];}echo'</td>
								<td class="text-center tableaprove">';
									if($u['collectible_status'] == 1 )
                                    {
										if($group['colect_return'] == 1 )
										{	
										   echo'
											<a href="./supplier_return.php?s_collect='.$u['collectible_sn'].'" title="'.$lang['P_S_RETURN'].'" class="mr-2">
												<i class="fas fa-undo  success"></i>
											</a>';
										}
                                    }else{
                                        echo '<span class="rose" data-html="true"  data-toggle="popover" title="'.$lang['P_S_RETURNED'].'" data-content="'.get_supplier_return($u['collectible_sn']).'">'.$lang['P_S_RETURNED'].'</span>
										' ;
                                    }
							echo'</td>
							</tr>';
						}
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
	$('[data-toggle="popover"]').popover({
        placement : 'top',
        trigger : 'hover'
    });
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



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
	include("./inc/Classes/system-settings_banks.php");
	$setting_bank = new systemSettings_banks();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_banks'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			
			$q = $_GET['q'];
			if($q != ""){
				$paginationDialm = 'true';
				$search= "?q=".$q;
			 }
//			include("./inc/Classes/pager.class.php");
//			$page;
//			$pager       = new pager();
//			$page 		 = intval($_GET['page']);
//			$total       = $setting_bank->getTotalSettings_banks($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"settings_banks.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
			$banks   = $setting_bank->getaccountsSettings_banks($limitmequry,$q);
//			print_r($banks);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"banks",
						"mode" 		        => 	"list",
						"total" 		    => 	$total,
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_BAN_BANKS'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['LIST'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="bankSearchForm" action="./settings_banks.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="bankSearch" name="q" value="<?php echo $q;?>">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="settings_banks_credit" id="table">
            <input type="hidden" value="setting_banks" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_BAN_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="bankTable">
                   <?php 
					if(empty($banks))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['SETTINGS_BAN_NAME'].'</th>
								<th>'.$lang['SETTINGS_BAN_ACCOUNT_NUM'].'</th>
								<th>'.$lang['SETTINGS_BAN_ACCONT_TYPE'].'</th>
								<th>'.$lang['SETTINGS_BAN_CREDIT_CODE'].'</th>
								<th>'.$lang['SETTINGS_BAN_CREDIT_NAME'].'</th>
								<th>'.$lang['SETTINGS_BAN_CLIENT_MAIN'].'</th>
								<th>'.$lang['SETTINGS_BAN_PRODUCT'].'</th>
								<th>'.$lang['SETTINGS_BAN_TOTAL'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>
						
						';
						
						foreach($banks as $k => $u)
						{
							$count = count($u['banks_accounts']);
							if($count > 1)
							{
								$rowspan = 'rowspan="'.$count.'" class="rowspanTd"';
							}else{
								$rowspan = "";
							}

						 echo'<tr id=tr_'.$u['banks_sn'].'>
								<td '.$rowspan.'>'.$u['banks_name'].' </td>
								<td '.$rowspan.'>'.$u['banks_account_number'].'</td>';
								foreach($u['banks_accounts'] as $cId => $c)
								{
									if($cId == 0)
									{
										if($c['type'] == 'credit')
										{
											echo '<td>'.$lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'].'</td>
												<td>'.$c['banks_credit_code'].'</td>
												<td>'.$c['banks_credit_name'].'</td>
												<td>'.get_data('settings_clients','clients_name','clients_sn',$c['banks_credit_client']).'</td>
												<td>'.get_data('settings_products','products_name','products_sn',$c['banks_credit_product']).'</td>
												<td>'.($c['banks_credit_limit_value']-$c['banks_credit_open_balance']).'</td>
												<td class="text-center tableActions" >
													<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
													<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="credit_'.$c['banks_credit_sn'].'"></i>
												</td>
											  ';
										}else{
											if($c['type'] == 'save')
											{
												echo '<td>'.$lang['SETTINGS_BAN_SAVE'].'</td>
														<td>'.$c['banks_saving_account_number'].'</td>
														<td></td>
														<td></td>
														<td></td>
														<td>'.$c['banks_saving_open_balance'].'</td>
														<td class="text-center tableActions" >
															<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i>
															</a>
															<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="save_'.$c['banks_credit_sn'].'"></i>
															';
											}elseif($c['type'] == 'current')
											{
												echo '<td>'.$lang['SETTINGS_BAN_CURRENT_ACCOUNT'].'</td>
													  <td>'.$c['banks_current_account_number'].'</td>
														<td></td>
														<td></td>
														<td></td>
														<td>'.$c['banks_current_opening_balance'].'</td>
														<td class="text-center tableActions" >
															<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i>
															</a>
															<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="current_'.$c['banks_current_sn'].'"></i>';
											}
											echo'</td>';
										}
										;
									}else{
										if($c['type'] == 'credit')
										{
											echo '<tr id=credit_'.$c['banks_credit_sn'].'>
													<td>'.$lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'].'</td>
													<td>'.$c['banks_credit_code'].'</td>
													<td>'.$c['banks_credit_name'].'</td>
													<td>'.get_data('settings_clients','clients_name','clients_sn',$c['banks_credit_client']).'</td>
													<td>'.get_data('settings_products','products_name','products_sn',$c['banks_credit_product']).'</td>
													<td>'.($c['banks_credit_limit_value']-$c['banks_credit_open_balance']).'</td>
													<td class="text-center tableActions" >
														<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
														<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="credit_'.$c['banks_credit_sn'].'"></i>
													</td>
												</tr>
											  '
											;
											
										}else{
											if($c['type'] == 'save')
											{

												echo '<tr id=save_'.$c['banks_saving_sn'].'>
													<td>'.$lang['SETTINGS_BAN_SAVE'].'</td>
													<td>'.$c['banks_saving_account_number'].'</td>
													<td></td>
													<td></td>
													<td></td>
													<td>'.$c['banks_saving_open_balance'].'</td>
													<td class="text-center tableActions" >
														<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
														<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="save_'.$c['banks_saving_sn'].'"></i>
													</td>
												</tr>
											  '
											;
												
											}elseif($c['type'] == 'current')
											{
												echo '<tr id=current_'.$c['banks_current_sn'].'>
													<td>'.$lang['SETTINGS_BAN_CURRENT_ACCOUNT'].'</td>
													<td>'.$c['banks_current_account_number'].'</td>
													<td></td>
													<td></td>
													<td></td>
													<td>'.$c['banks_current_opening_balance'].'</td>
													<td class="text-center tableActions" >
														<a href="./edit_bank.php?id='.$u['banks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
														<i class="delete_bank fas fa-trash rose" title="'.$lang['DELETE'].'" id="current_'.$c['banks_current_sn'].'"></i>
													</td>
												</tr>
											  ';
												
											}
											
										}
									}
									
								}
								
						echo'
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
				<script src="./assets/js/main.js"></script>
				<script src="./assets/js/navbar.js"></script>
				<script src="./assets/js/list-controls.js"></script>
				';


	include './assets/layout/footer.php';
?>
<SCRIPT>
$(document).ready( function () {


    $('i.delete_bank').click(function(){

		var id               = $(this).attr('id');
        var page             = "bank_js.php?do=delete";
		if (id != 0)
		{
			$.ajax( {
				async :true,
				type :"POST",
				url :page,
				data: "&id=" + id,
				success : function(responce) {
                    if(responce == 100)
                     {
							$("#"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("#"+id).animate({width: 'auto', opacity: '0.9'}, "slow");
							$("#"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("#"+id).animate({width: 'auto', opacity: '1'}, "slow");
							$("#"+id).fadeTo(400, 0, function () { $("#" + id).slideUp(400);});
                      }
				},
				error : function() {
					return true;
				}
			});
		}
	});

 $('#bankTable').DataTable({
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



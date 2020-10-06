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
	include("./inc/Classes/system-expenses.php");
	$expenses = new systemExpenses();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['expense'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
            if($_GET){
			 $search['id']		                 = intval($_GET['id']);
			 $search['q']		                 = intval($_GET['q']);
            }
			
//			$q = $_GET['q'];
//			if($q != ""){
//				$paginationDialm = 'true';
//				$search= "?q=".$q;
//			 }
//			include("./inc/Classes/pager.class.php");
//			$page;
//			$pager       = new pager();
//			$page 		 = intval($_GET['page']);
//			$total       = $expenses->getTotalExpenses($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"Expenses.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
			$expenses   = $expenses->getsiteExpenses($limitmequry,$search);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"user",
						"module" 	        => 	"expenses",
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
                    <span class="blueSky"><strong> &gt; </strong><?php echo $lang['OPERATIONS_NAV_TITLE'];?></span>
                    <a class="blueSky" href="./add_expense.php"><strong> &gt; </strong><?php echo $lang['OPERATIONS_DROP_TITLE_2'];?></a>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="expensesearchForm" action="./expenses.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="<?php echo $lang['SEARCH_BY_CHEQUE'];?>" id="expensesearch" name="q" value="<?php echo $q;?>" >
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="Expenses" id="table">
            <input type="hidden" value="settings_department" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_X_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($expenses))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['SETTINGS_C_F_DATE_PAYMENT'].'</th>
								<th>'.$lang['SETTINGS_C_F_PAYMENT_TYPE'].'</th>
								<th>'.$lang['SETTINGS_C_F_PAYMENT_MONEY'].'</th>
								<th>'.$lang['SETTINGS_C_F_PAYMENT_CHEQUE_NUM'].'</th>
								<th>'.$lang['SETTINGS_C_F_PAYMENT_DATE_CHEQUE'].'</th>
								<th>'.$lang['SETTINGS_C_F_ADD_TO'].'</th>
								<th>'.$lang['SETTINGS_C_F_ACCOUNT_TYPE'].'</th>
								<th>'.$lang['SETTINGS_C_F_ACCOUNT_T'].'</th>
								<th>'.$lang['EXPENCE_TITLE'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($expenses as $k => $u)
						{
						 echo'<tr id=tr_'.$u['expenses_sn'].'>
								<td>'.$u['expenses_date'].'</td>
								<td>';if($u['expenses_type']=='cheque'){echo $lang['SETTINGS_C_F_PAYMENT_CHEQUE'];}elseif($u['expenses_type']=='cash'){echo $lang['SETTINGS_C_F_PAYMENT_CASH'];}echo'</td>
								<td>'.$u['expenses_amount'].'</td>
								<td>';echo $u['expenses_cheque_sn']?$u['expenses_cheque_sn']:'--------';echo'</td>
								<td>';echo $u['expenses_cheque_date'] != '0000-00-00'?$u['expenses_cheque_date']:'--------';echo'</td>
								<td>'.get_data('settings_banks','banks_name','banks_sn',$u['expenses_bank_id']).'</td>
								<td>';if($u['expenses_bank_account_type']=='saving'){echo $lang['SETTINGS_BAN_SAVE'];}elseif($u['expenses_bank_account_type']=='current'){echo $lang['SETTINGS_BAN_CURRENT'];}elseif($u['expenses_bank_account_type']=='credit'){echo $lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'];}echo'</td>
								<td>';if($u['expenses_bank_account_type']=='credit'){echo get_data('settings_banks_credit','banks_credit_name','banks_credit_sn',$u['expenses_bank_account_id']);}echo'</td>
								<td>'.$u['expenses_title'].'</td>
								<td class="text-center tableActions">
									<a href="./edit_expense.php?id='.$u['expenses_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
									<i class="delete_expense fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['expenses_sn'].'"></i>
								</td>
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
$('i.delete_expense').click(function(e){
        e.preventDefault();
		var id               = $(this).attr('id').replace("item_","");
        var page             = "operations_js.php?do=delete_expence";
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
							$("#tr_"+id).fadeTo(400, 0, function () { $("#tr_" + id).slideUp(400);}); 
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



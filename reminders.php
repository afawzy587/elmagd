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
	include("./inc/Classes/system-reminders.php");
	$ueminders = new systemReminders();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['reminders'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			
			$q = $_GET['q'];
//			if($q != ""){
//				$paginationDialm = 'true';
//				$search= "?q=".$q;
//			 }
//			include("./inc/Classes/pager.class.php");
//			$page;
//			$pager       = new pager();
//			$page 		 = intval($_GET['page']);
//			$total       = $setting_department->getTotalReminders($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"Reminders.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
			$ueminders   = $ueminders->getsiteReminders($limitmequry,$q);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"reminders",
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['Reminders'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
<!--
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="remindersearchForm" action="./Reminders.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="remindersearch" name="q" value="<?php echo $q;?>">
                    </div>
                </form>
            </div>
        </div>
-->
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="reminders" id="table">
            <input type="hidden" value="reminders" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_D_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($ueminders))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['Reminders_DATE'].'</th>
								<th>'.$lang['Reminders_DESCRIPTION'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($ueminders as $k => $u)
						{
						 echo'<tr id=tr_'.$u['reminders_sn'].'>
								<td>'._date_format($u['reminders_date']).'</td>
								<td>';
							echo '<span class="dropdown-item ';
							if($u['reminders_read'] == 0){ echo 'unread';}
							echo '">';
							if($u['reminders_type'] == 'deposits')
							{
								echo  '<a href="./deposits_list.php?id='.$u['reminders_type_id'].'" id='.$u['reminders_sn'].' class="read">
												'.$GLOBALS['lang']['NOT_DEPOSITS_MESSAGE'].'
												<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($u['reminders_date']).'</small></div>
											</a>
											</span>';

							}elseif($u['reminders_type'] == 'transfer'){
								echo  '<a href="./transfers.php?id='.$u['reminders_type_id'].'" id='.$u['reminders_sn'].' class="read">
												'.$GLOBALS['lang']['NOT_TRANSFERS_MESSAGE'].'
												<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($u['reminders_date']).'</small></div>
											</a>
											</span>';

							}elseif($u['reminders_type'] == 'safe'){
								echo  '<a href="#" id='.$u['reminders_sn'].' class="read" >
												'.$GLOBALS['lang']['NOT_SAFES_MESSAGE'].'
												<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($u['reminders_date']).'</small></div>
											</a>
											</span>';

							}elseif($u['reminders_type'] == 'expenses'){
								echo  '<a  href="./expenses.php?id='.$u['reminders_type_id'].'" id='.$u['reminders_sn'].' class="read" >
												'.$GLOBALS['lang']['NOT_SAFES_MESSAGE'].'
												<div><small>'.$GLOBALS['lang']['NOT_DEPOSITS_DATE'].' : '._date_format($u['reminders_date']).'</small></div>
											</a>
											</span>';

							}elseif($u['reminders_type'] == 'clients_pay'){
								echo  '<a href="#" id='.$u['reminders_sn'].' class="read" >
												'.$GLOBALS['lang']['NOT_CLIENT_PAY'].' ('.number_format($u['title']).') '.$GLOBALS['lang']['NOT_CLIENT_DAY'].' : '._date_format($u['reminders_date']).'
												<div><small>'.$GLOBALS['lang']['OPERATIONS_CLIENT'].'(  '.get_data('settings_clients','clients_name','clients_sn',$u['client_id']).') '.$GLOBALS['lang']['OPERATIONS_PRODUCT'].' : ' .get_data('settings_products','products_name','products_sn',$u['product_id']).' </small></div>
											</a>
											</span>';

							}elseif($u['reminders_type'] == 'defult'){
								echo  '<a href="#" id='.$u['reminders_sn'].' class="read" >
												'.$GLOBALS['lang']['Reminder'].' : ' .$u['title'].'
												<div><small>'.$GLOBALS['lang']['Reminders_DATE'].' : '._date_format($u['reminders_date']).'</small></div>
											</a>
											</span>';

							}
							
							echo'</td>
								<td class="text-center tableActions">';
								echo'<i class="delete_reminders fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['reminders_sn'].'"></i>
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
	$('i.delete_reminders').click(function(){

		var id               = $(this).attr('id').replace("item_",'');
        var page             = "notification_js.php?do=delete";
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


} );
</SCRIPT>



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
	include("./inc/Classes/system-clients_pricing.php");
	$price = new systemClients_pricing();
	$_SESSION['page'] = $actual_link;

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['clients_pricing'] == 0){
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
//			$total       = $price->getTotalClients_pricing($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"Clients_pricing.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
			$pricing   = $price->getsiteClients_pricing($limitmequry,$q);
//			print_r($pricing);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"jobs",
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_CLIENT'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_F_PREVIOUS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="jobSearchForm" action="./Clients_pricing.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="jobSearch" name="q" value="<?php echo $q;?>">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="Clients_pricing" id="table">
            <input type="hidden" value="settings_job" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_D_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="jobTable">
                   <?php 
					if(empty($pricing))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_START'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_END'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_CLIENT'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_PRODUCT'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_RATE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_SUP_PRICE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_PERCENT'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_EXUCE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_EXUCE_PRICE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_F_BOUNCE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_F_BOUNCE_PRICE'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_SUP_BOU_P'].'</th>
								<th class="smallFont">'.$lang['SETTINGS_C_F_M_SUP_BOU'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($pricing as $k => $u)
						{
						 echo'<tr id=tr_'.$u['pricing_sn'].'>
								<td>'._date_format($u['pricing_start_date']).'</td>
								<td>';echo $u['pricing_end_date']!='0000-00-00'?_date_format($u['pricing_end_date']):'ــــــــــــــــــ';echo'</td>
								<td>'.$u['clients_name'].'</td>
								<td>'.$u['products_name'].'</td>
								<td>'.$u['clients_products_rate_name'].'</td>
								<td>'.$u['pricing_supply_price'].'</td>
								<td>'.$u['pricing_rate_percent'].'</td>
								<td>'.$u['pricing_excuse_price'].'</td>
								<td>'.$u['pricing_excuse_percent'].'</td>
								<td>'.$u['pricing_client_bonus_percent'].'</td>
								<td>'.$u['pricing_client_bonus_amount'].'</td>
								<td>'.$u['pricing_supply_bonus_percent'].'</td>
								<td>'.$u['pricing_supply_bonus_amount'].'</td>
								<td class="text-center tableActions">
									<a href="./edit_client_pricing.php?id='.$u['pricing_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_PRICING'].'"></i></a>
									<i class="delete_price fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['pricing_sn'].'"></i>
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
    $('#jobTable').DataTable({
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
	
	$('i.delete_price').click(function(e){
        e.preventDefault();
		var id               = $(this).attr('id').replace("item_","");
        var page             = "client_pricing_js.php?do=delete";
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



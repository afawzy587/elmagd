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
	include("./inc/Classes/system-settings_stocks.php");
	$setting_stock = new systemSettings_stocks();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['setting_stocks'] == 0){
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
//			$total       = $setting_stock->getTotalSettings_stocks($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"settings_stocks.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
			$stocks   = $setting_stock->getsiteSettings_stocks($limitmequry,$q);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"stocks",
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_ST_STOCKS'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['LIST'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="stockSearchForm" action="./settings_stocks.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="stockSearch" name="q" value="<?php echo $q;?>">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="settings_stocks" id="table">
            <input type="hidden" value="setting_stocks" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_ST_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="stockTable">
                   <?php 
					if(empty($stocks))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['SETTINGS_ST_NAME'].'</th>
								<th>'.$lang['SETTINGS_ST_PHONE'].'</th>
								<th>'.$lang['SETTINGS_ST_MANGE_NAME'].'</th>
								<th>'.$lang['SETTINGS_ST_MANGER_PHONE'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($stocks as $k => $u)
						{
//							;if($u['stocks_phone_two']){echo'<br>'.$u['stocks_phone_two'];}echo
						 echo'<tr id=tr_'.$u['stocks_sn'].'>
								<td>'.$u['stocks_name'].'</td>
								<td>'.$u['stocks_phone_one'].'</td>
								<td>'.$u['stocks_manager_name'].'</td>
								<td>'.$u['stocks_manager_phone'].'</td>
								<td class="text-center tableActions">
									<a href="./edit_stock.php?id='.$u['stocks_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['EDIT'].'"></i></a>
									<i class="delete fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['stocks_sn'].'"></i>
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
    $('#stockTable').DataTable({
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



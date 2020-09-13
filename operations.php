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
	include("./inc/Classes/system-Operations.php");
	$operations = new systemOperations();

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
			
			$q = $_GET['q'];
			if($q != ""){
				$paginationDialm = 'true';
				$search= "?q=".$q;
			 }
//			include("./inc/Classes/pager.class.php");
//			$page;
//			$pager       = new pager();
//			$page 		 = intval($_GET['page']);
//			$total       = $operations->getTotalOperations($q);
//			$pager->doAnalysisPager("page",$page,$basicLimit,$total,"Operations.php".$search.$paginationAddons,$paginationDialm);
//			$thispage    = $pager->getPage();
//			$limitmequry = " LIMIT ".($thispage-1) * $basicLimit .",". $basicLimit;
//			$pager       = $pager->getAnalysis();
            $_operations   = $operations->GetAllOperations($limitmequry,$q);
			$logs->addLog(NULL,
					array(
						"type" 		        => 	"admin",
						"module" 	        => 	"operations",
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['OPERATIONS_NAV_TITLE'];?></span>
                    <a class="blueSky" href="./add_operation.php?o=<?php echo generate_unique_code(2)?>"><strong> &gt; </strong> <?php echo $lang['OPERATIONS_DROP_TITLE_1'];?> </a>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['OPERATIONS_LIST'];?></span>
                </p>
                
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="$_operationsearchForm" action="./Operations.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="<?php echo $lang['OPERATIONS_SEARCH_PLACEHOLDER'];?>" name="q" value="<?php echo $q;?>">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- table row -->
        <div class="row">
            <input type="hidden" value="operations" id="table">
            <input type="hidden" value="Operations" id="permission">
            <div class="col">
               <?php 
					if($_GET['action'] == 'edit'){
						echo alert_message("success",$lang['SETTINGS_D_EDIT_SUCCESS']);
					}
				?>
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($_operations))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['OPERATIONS_DATE'].'</th>
								<th>'.$lang['OPERATIONS_SUPPLIER'].'</th>
								<th>'.$lang['OPERATIONS_NUMBER'].'</th>
								<th>'.$lang['OPERATIONS_CARD_SERIAL'].'</th>
								<th>'.$lang['OPERATIONS_SUPLLY_TOTAL'].'</th>
								<th>'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($_operations as $k => $u)
						{
						 echo'<tr id=tr_'.$u['operations_sn'].'>
								<td>'._date_format($u['operations_date']).'</td>
								<td>'.$u['suppliers_name'].'</td>
								<td>'.$u['operations_code'].'</td>
								<td>'.$u['operations_card_number'].'</td>
								<td>'.$u['operations_supplier_price'].'</td>
                                <td class="text-center tableActions" id="td_'.$u['operations_sn'].'">';
                                    if($u['operations_status'] == 1)
                                    {
									  echo'<i class="delete_operation fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['operations_sn'].'"></i>';
                                    }else{
                                        echo '<span class="rose">'.$lang['DELETE_DONE'].'</span>' ;
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



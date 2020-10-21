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
		if($group['settings_department'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			
			if($_GET)
			{
				$supplier = $suppliers_collectible->GetSupplierFinanceByid(intval($_GET['supplier']));
				$Supplier_Paid = $suppliers_collectible->Get_Supplier_Paid($_GET);

				$result   = $suppliers_collectible->GetSearchResult($_GET);
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
        <div class="row justify-content-center mb-5">
            <div class="col text-center">
                <h5><?php echo $lang['OPERATIONS_M_SU_FINANCE'];?> <strong class="blueSky"><?php echo $supplier['name'];?></strong></h5>
                <h4 class="d-inline-block bg_text text_height <?php echo $supplier['credit'] < 0 ? "warning" : " ";?> ltrDir"><?php echo number_format($supplier['credit']);?></h4>
            </div>
        </div>
        <!-- end account details row -->

        <!-- table row -->
        <div class="row">
            <div class="col">
              
                <table class="table table-fluid " id="departmentTable">
                   <?php 
					if(empty($departments))
					{
						echo "<tr><th colspan=\"5\">".$lang['SETTINGS_NO_ITEMS']."</th></tr>";
					}else{
						echo '
						<thead>
							<tr>
								<th>'.$lang['SETTINGS_D_DEPARMENT'].'</th>
								<th>'.$lang['SETTINGS_D_DESCRIPTION'].'</th>
								<th style="width: 6rem;">'.$lang['SETTINGS_ACTION'].'</th>
							</tr>
						</thead>
						<tbody>';
						foreach($departments as $k => $u)
						{
						 echo'<tr id=tr_'.$u['departments_sn'].'>
								<td>'.$u['departments_name'].'</td>
								<td>'.$u['departments_description'].'</td>
								<td class="text-center tableActions">
									<a href="./edit_department.php?id='.$u['departments_sn'].'"><i class="fas fa-edit mr-3 green" title="'.$lang['SETTINGS_D_EDIT_DEPARMENTS'].'"></i></a>
									<i class="delete fas fa-trash rose" title="'.$lang['DELETE'].'" id="item_'.$u['departments_sn'].'"></i>
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

} );
</SCRIPT>



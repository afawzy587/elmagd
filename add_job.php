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
	include("./inc/Classes/system-settings_jobs.php");
	$setting_job = new systemSettings_jobs();
	include("./inc/Classes/system-settings_departments.php");
	$setting_department = new systemSettings_departments();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['setting_jobs'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			$departments   = $setting_department->getsiteSettings_departments();
			if($_POST)
			{
				$_job['jobs_name']                       =       sanitize($_POST["jobs_name"]);
				$_job['jobs_department']                 =       intval($_POST["jobs_department"]);
				
				$add = $setting_job->addSettings_jobs($_job);
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"jobs",
							"mode" 		        => 	"add_jobs",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_job.php?action=add");
						exit;
				}
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['Settings_jobs'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_J_TITLE'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="jobsearchForm" method="get" action="./settings_jobs.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="jobsearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->

        <!-- add/edit department row -->
        <div class="row centerContent">
            <div class="col">
                <form  method="post" id="companyDetailsForm" enctype="multipart/form-data">
                   
                    <?php 
						if($add == 1){
							echo alert_message("success",$lang['SETTINGS_J_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['SETTINGS_J_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['SETTINGS_J_ADD_JOBS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_J_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="jobs_name" placeholder="<?php echo $lang['SETTINGS_J_NAME'];?>" value="<?php echo $_job['jobs_name'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_D_DEPARMENT'];?></label>
                                    <div class="col-xs-5 ">
                                        <div class="select">
                                            <select name="jobs_department" class="form-control" id="slct">
                                                <option selected disabled><?php echo $lang['SETTINGS_J_CHOOSE_D']; ?></option>
                                                <?php
													if($departments)
													{
														foreach($departments as $k => $d)
														{
															echo '<option value="'.$d['departments_sn'].'"';if($_job['jobs_department'] == $d['departments_sn']){echo 'selected';}echo'>'.$d['departments_name'].'</option>';
															
														}
													}
												?>
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"><?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit department row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#addRoleForm').formValidation({
        excluded: [':disabled'],
        fields: {
            jobs_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_J_INSERT_NAME'];?>'
                    }
                }
            },
            jobs_department: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_J_CHOOSE_D'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // roleName input[name="roleName"]
        // department input[name="department"]
    })
})


</SCRIPT>



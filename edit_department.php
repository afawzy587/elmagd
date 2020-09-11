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
	include("./inc/Classes/system-settings_departments.php");
	$setting_department = new systemSettings_departments();

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
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u = $setting_department->getSettings_departmentsInformation($mId);
				if($_POST)
				{
					$_department['departments_sn']                        =       $mId;
					$_department['departments_name']                      =       sanitize($_POST["departments_name"]);
					$_department['departments_description']               =       sanitize($_POST["departments_description"]);

					$edit = $setting_department->setSettings_departmentsInformation($_department);
					if($edit == 1)
					{
//						$logs->addLog(NULL,
//							array(
//								"type" 		        => 	"users",
//								"module" 	        => 	"departments",
//								"mode" 		        => 	"edit_departments",
//								"deparment" 		=> 	$mId,
//								"id" 	        	=>	$_SESSION['id'],
//							),"admin",$_SESSION['id'],1
//							);

							header("Location:./settings_departments.php?action=edit");
							exit;
					}
				}
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['Settings_departments'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_D_DEPARMENTS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="departmentSearchForm" method="get" action="./settings_departments.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="departmentSearch" name="q">
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
						if($edit == 1){
							echo alert_message("success",$lang['SETTINGS_D_SUCCESS']);
						}elseif($edit == 2){
							echo alert_message("danger",$lang['SETTINGS_D_INSERT_BEFORE']);
						}
					?>
                     <h5><?php echo $lang['SETTINGS_D_EDIT_DEPARMENTS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_D_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="departments_name" placeholder="<?php echo $lang['SETTINGS_D_NAME'];?>" value="<?php if($_department){echo $_department['departments_name'];}else{echo $u['departments_name'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_D_DESCRIPTION'];?></label>
                                    <input type="text" class="form-control" name="departments_description" placeholder="<?php echo $lang['SETTINGS_D_DESCRIPTION_HOLDER'];?>" value="<?php if($_department){echo $_department['departments_description'];}else{echo $u['departments_description'];}?>">
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

    $('#departmentSearch').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            // search input value =>> $(this)[0].value
            console.log($(this)[0].value);
            $('#departmentSearchForm').submit();

            return false;
        }
    });


    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            departments_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_D_INSERT_NAME'];?>'
                    }
                }
            },
            departments_description: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_D_INSERT_DES'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

          input[name="departments_name"].value = "";
          input[name="departments_description"].value = "";
    })
})


</SCRIPT>



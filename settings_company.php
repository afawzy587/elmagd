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
	include("./inc/Classes/system-settings_company.php");
	$setting_company = new systemSettings_company();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['setting_company'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_POST)
			{
				$_company['companyinfo_name']                        =       sanitize($_POST["companyinfo_name"]);
				$_company['companyinfo_phone']                       =       sanitize($_POST["companyinfo_phone"]);
				$_company['companyinfo_opening_balance_safe']        =       sanitize($_POST["companyinfo_opening_balance_safe"]);
				$_company['companyinfo_opening_balance_cheques']     =       sanitize($_POST["companyinfo_opening_balance_cheques"]);
				$_company['companyinfo_address']                     =       sanitize($_POST["companyinfo_address"]);
				if( $_FILES && ( $_FILES['companyinfo_logo']['name'] != "") && ( $_FILES['companyinfo_logo']['tmp_name'] != "" ) )
				{
					include_once("./inc/Classes/upload.class.php");
					$allow_ext = array("jpg","jpeg","gif","png");
					$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
					$files['name'] 	= addslashes($_FILES["companyinfo_logo"]["name"]);
					$files['type'] 	= $_FILES["companyinfo_logo"]['type'];
					$files['size'] 	= $_FILES["companyinfo_logo"]['size']/1024;
					$files['tmp'] 	= $_FILES["companyinfo_logo"]['tmp_name'];
					$files['ext']		= $upload->GetExt($_FILES["companyinfo_logo"]["name"]);
					$upfile	= $upload->Upload_File($files);
					if($upfile)
					{
						$_company['companyinfo_logo']  = $upfile['newname'];

					}
					@unlink($path.$_POST['old_companyinfo_logo']);
				}
				if( $_FILES && ( $_FILES['companyinfo_document']['name'] != "") && ( $_FILES['companyinfo_document']['tmp_name'] != "" ) )
				{
					include_once("./inc/Classes/upload.class.php");
					$allow_ext = array("jpg","jpeg","gif","png");
					$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
					$files['name'] 	= addslashes($_FILES["companyinfo_document"]["name"]);
					$files['type'] 	= $_FILES["companyinfo_document"]['type'];
					$files['size'] 	= $_FILES["companyinfo_document"]['size']/1024;
					$files['tmp'] 	= $_FILES["companyinfo_document"]['tmp_name'];
					$files['ext']		= $upload->GetExt($_FILES["companyinfo_document"]["name"]);
					$upfile	= $upload->Upload_File($files);
					if($upfile)
					{
						$_company['companyinfo_document']  = $upfile['newname'];

					}
					@unlink($path.$_POST['old_companyinfo_document']);
				}
				$edit = $setting_company->setSettings_companyInformation($_company);
				if($edit == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"setting_company",
							"mode" 		        => 	"edit_company",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
//						header("Location:./settings_company.php");
//						exit;
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_COMPANY'];?></span>
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_C_DETAIL'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->

        <!-- companyDetails Form row -->
        <div class="row centerContent">
            <div class="col">
                <form action="./settings_company.php" method="post" id="companyDetailsForm" enctype="multipart/form-data" >
                    <h5> <?php echo $lang['SETTINGS_C_EDIT'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="companyinfo_name" value="<?php echo $companyinfo['companyinfo_name']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_PHONE'];?></label>
                                    <input type="text" class="form-control" name="companyinfo_phone" value="<?php echo $companyinfo['companyinfo_phone']?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xs-3 smallLabel mb-0"> <?php echo $lang['SETTINGS_C_SAFES'];?>
                                                </label>
                                            <input type="text" class="form-control" name="companyinfo_opening_balance_safe" value="<?php echo $companyinfo['companyinfo_opening_balance_safe']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xs-3 smallLabel mb-0">  <?php echo $lang['SETTINGS_C_CHEQUES'];?>
                                               </label>
                                            <input type="text" class="form-control" name="companyinfo_opening_balance_cheques" value="<?php echo $companyinfo['companyinfo_opening_balance_cheques']?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_C_ADDRES'];?></label>
                                    <input type="text" class="form-control" name="companyinfo_address" value="<?php echo $companyinfo['companyinfo_address']?>" >
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_LOGO'];?></label>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
                                                <input type="file" class="form-control uploadimage" id="logo" name="companyinfo_logo" >
                                                <input type="text" class="form-control uploadimage"  name="old_companyinfo_logo" value="<?php echo $companyinfo['companyinfo_logo']?>" hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
                                            <img src="<?php echo $path.$companyinfo['companyinfo_logo']?>" height="50px" class="imagePreviewURL" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <label class="col-xs-3"><?php echo $lang['SETTINGS_C_DOCS'];?></label>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
                                                <input type="file" class="form-control uploadimage" name="companyinfo_document">
                                                 <input type="text" class="form-control uploadimage"  name="old_companyinfo_document" value="<?php echo $companyinfo['companyinfo_document']?>" hidden>

                                            </div>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
                                            <img src="<?php echo $path.$companyinfo['companyinfo_document']?>" height="50px" class="imagePreviewURL" alt="">
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
        <!-- end companyDetails Form row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function(){

    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            companyinfo_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_C_NSERT_NAME'];?>'
                    }
                }
            },
            companyinfo_address: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['INSERT_ADDRESS'];?>'
                    }
                }
            },
            companyinfo_phone: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['INSERT_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: ' <?php echo $lang['INSERT_CORRECT_PHONE'];?>',
                    }
                }
            },
            old_companyinfo_logo: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_LOGO_CHOOSE'];?>'
                    }
                }
            },
            companyinfo_opening_balance_safe: {
                validators: {
                    notEmpty: {
                        message: '  <?php echo $lang['SETTINGS_C_SAFE_INSERT'];?>'
                    },
                    regexp: {
                      regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                      message: ' <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            },
            companyinfo_opening_balance_cheques: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_CHEQUE_INSERT'];?>'
                    },
                    regexp: {
                      regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                      message: '  <?php echo $lang['SETTINGS_C_MAX_NUM'];?>'
                    }
                }
            },
            old_companyinfo_document: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_C_CHOOSE_DOC'];?>'
                    }
                }
            }
        }
    })


})


</SCRIPT>



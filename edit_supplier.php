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
	include("./inc/Classes/system-settings_suppliers.php");
	$setting_supplier = new systemSettings_suppliers();

	include("./inc/Classes/system-settings_products.php");
	$setting_product = new systemSettings_products();


    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_suppliers'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if (intval($_GET['id']) != 0)
			{
				$mId =intval($_GET['id']);
				$u = $setting_supplier->getSettings_suppliersInformation($mId);
				$products   = $setting_product->getsiteSettings_products();


				if($_POST)
				{
					$_supplier['suppliers_sn']                          =      $mId;
					$_supplier['suppliers_name']                        =       sanitize($_POST["suppliers_name"]);
					$_supplier['suppliers_phone_one']                   =       sanitize($_POST["suppliers_phone_one"]);
					$_supplier['suppliers_phone_two']                   =       sanitize($_POST["suppliers_phone_two"]);
					$_supplier['suppliers_address']                     =       sanitize($_POST["suppliers_address"]);
					$_supplier['check']                                 =       $_POST["check"];
					$_supplier['product']                               =       $_POST["product"];
					$_supplier['supplier_product']                      =       $_POST["supplier_product"];
					if(sanitize($_POST["users_password"]) != "")
					{
						$_supplier['suppliers_password']                    =       password_hash(sanitize($_POST["suppliers_password"]),PASSWORD_DEFAULT);
					}else
					{
						$_supplier['suppliers_password']              = "";
					}
					if( $_FILES && ( $_FILES['suppliers_photo']['name'] != "") && ( $_FILES['suppliers_photo']['tmp_name'] != "" ) )
					{
						include_once("./inc/Classes/upload.class.php");
						$allow_ext = array("jpg","jpeg","gif","png");
						$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
						$files['name'] 	= addslashes($_FILES["suppliers_photo"]["name"]);
						$files['type'] 	= $_FILES["suppliers_photo"]['type'];
						$files['size'] 	= $_FILES["suppliers_photo"]['size']/1024;
						$files['tmp'] 	= $_FILES["suppliers_photo"]['tmp_name'];
						$files['ext']		= $upload->GetExt($_FILES["suppliers_photo"]["name"]);
						$upfile	= $upload->Upload_File($files);
						if($upfile)
						{
							$_supplier['suppliers_photo']  = $upfile['newname'];

						}
						@unlink($path.$_POST['old_suppliers_photo']);
					}
					if( $_FILES && ( $_FILES['suppliers_doc']['name'] != "") && ( $_FILES['suppliers_doc']['tmp_name'] != "" ) )
					{
						include_once("./inc/Classes/upload.class.php");
						$allow_ext = array("jpg","jpeg","gif","png");
						$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
						$files['name'] 	= addslashes($_FILES["suppliers_doc"]["name"]);
						$files['type'] 	= $_FILES["suppliers_doc"]['type'];
						$files['size'] 	= $_FILES["suppliers_doc"]['size']/1024;
						$files['tmp'] 	= $_FILES["suppliers_doc"]['tmp_name'];
						$files['ext']		= $upload->GetExt($_FILES["suppliers_doc"]["name"]);
						$upfile	= $upload->Upload_File($files);
						if($upfile)
						{
							$_supplier['suppliers_doc']  = $upfile['newname'];

						}
						@unlink($path.$_POST['old_suppliers_doc']);
					}
					$edit = $setting_supplier->setSettings_suppliersInformation($_supplier);
					if($edit == 1)
					{
						$logs->addLog(NULL,
							array(
								"type" 		        => 	"users",
								"module" 	        => 	"suppliers",
								"mode" 		        => 	"add_suppliers",
								"id" 	        	=>	$_SESSION['id'],
							),"admin",$_SESSION['id'],1
							);

							header("Location:./settings_suppliers.php?action=edit");
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
                    <span class="blueSky"><strong> &gt; </strong> <?php echo $lang['SETTINGS_SU_SUPPLIERS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="supplierSearchForm" method="get" action="./settings_suppliers.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="supplierSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->


         <!-- add/edit supplier row -->
        <div class="row centerContent">
            <div class="col">
               <?php 
						if($edit == 1){
							echo alert_message("success",$lang['SETTINGS_SU_SUCCESS']);
						}elseif($edit == 'manager_phone'){
							echo alert_message("danger",$lang['SETTINGS_SU_MA_INSERT_BEFORE']);
						}elseif($edit == 'phone_two'){
							echo alert_message("danger",$lang['SETTINGS_SU_2_INSERT_BEFORE']);
						}elseif($edit == 'phone_one'){
							echo alert_message("danger",$lang['SETTINGS_SU_1_INSERT_BEFORE']);
						}elseif($edit == 'suppliers_email'){
							echo alert_message("danger",$lang['SETTINGS_SU_EMAIL_INSERT_BEFORE']);
						}elseif($edit == 'suppliers_name'){
							echo alert_message("danger",$lang['SETTINGS_SU_NAME_INSERT_BEFORE']);
						}
					?>
                <form method="post" id="addsuppliersForm" enctype="multipart/form-data">
                    <h5><?php echo $lang['SETTINGS_SU_ADD_supplierS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3">  <?php echo $lang['SETTINGS_SU_NAME'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="suppliers_name" placeholder=" <?php echo $lang['SETTINGS_SU_NAME'];?>" value ="<?php if($_supplier){echo $_supplier['suppliers_name'];}else{echo $u['suppliers_name'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_SU_ADDRESS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="suppliers_address" value ="<?php if($_supplier){echo $_supplier['suppliers_address'];}else{echo $u['suppliers_address'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_ADDRESS_HOLD'];?>">
                                    </div>
                                </div>
                               <div class="form-group">
                                    <label class="col-xs-3"><?php echo $lang['SETTINGS_SU_PHONE_1'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="suppliers_phone_one"
                                            placeholder="<?php echo $lang['SETTINGS_SU_PHONE_1'];?>" value ="<?php if($_supplier){echo $_supplier['suppliers_phone_one'];}else{echo $u['suppliers_phone_one'];}?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_SU_PHONE_2'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="suppliers_phone_two"
                                            placeholder="<?php echo $lang['SETTINGS_SU_PHONE_2'];?>" value ="<?php if($_supplier){echo $_supplier['suppliers_phone_two'];}else{echo $u['suppliers_phone_two'];}?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="checkBOxesComponent mb-2">
                                    <label for=""></label>
                                    <div class="row">
                                       <?php 
										
											$suppliers_products = [];
											if($u['suppliers_products'])
											{
												foreach($u['suppliers_products'] as $sid =>$sp)
												{
													echo'<div class="col-md-4">
														<div class="form-group">
															<input class="customized-checkbox" id="customized-checkbox-'.$sp['suppliers_products_product_id'].'"
																type="checkbox" name="check[]" value="'.$sp['suppliers_products_product_id'].'"  checked >
																
															<label class="customized-checkbox-label" for="customized-checkbox-'.$sp['suppliers_products_product_id'].'">
															</label>
															<label class="customized-chxlabel-bg">'.get_data("settings_products","products_name","products_sn",$sp['suppliers_products_product_id']).'</label>
														</div>
														<input name="product[]" value="'.$sp['suppliers_products_product_id'].'" hidden>
														<input name="supplier_product[]" value="'.$sp['suppliers_products_sn'].'-'.$sp['suppliers_products_product_id'].'" hidden>
													</div>';
													$suppliers_products[$sid] = $sp['suppliers_products_product_id'];
												}
											}
											if($products)
											{
												foreach($products as $k => $p)
												{
													if(!in_array($p['products_sn'],$suppliers_products))
													{
														echo'<div class="col-md-4">
																<div class="form-group">
																	<input class="customized-checkbox" id="customized-checkbox-'.$p['products_sn'].'"
																		type="checkbox" name="check[]" value="'.$p['products_sn'].'"  ';if($_supplier){if($_supplier['check'][$k] == $p['products_sn']){echo 'checked';}}echo'>

																	<label class="customized-checkbox-label" for="customized-checkbox-'.$p['products_sn'].'">
																	</label>
																	<label class="customized-chxlabel-bg">'.$p['products_name'].'</label>
																</div>
																<input name="product[]" value="'.$p['products_sn'].'" hidden>
															</div>';
													}
												}
											}
										?>
                                        
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
											<div class="row">
												<div class="col-md-7 ">
													<label class="col-xs-3"> <?php echo $lang['SETTINGS_SU_PHOTO'];?></label>
													<div class="upload-btn-wrapper">
														<button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
														<input type="file" class="form-control uploadimage" name="suppliers_photo">
														<input type="text" class="form-control uploadimage"  name="suppliers_photo" value="<?php echo $u['old_clients_commercial_register']?>" hidden>
													</div>
												</div>
												<div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
													<img src="<?php echo $path; if($u){echo $u['suppliers_photo'];}else{echo '/defaults/img-icon.png';}?>" height="50px" class="imagePreviewURL" alt="">
												</div>
											</div>
										</div>
                                
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
											<div class="row">
												<div class="col-md-7 ">
													<label class="col-xs-3"><?php echo $lang['SETTINGS_SU_DOC'];?></label>
													<div class="upload-btn-wrapper">
														<button class="btn"><?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
														<input type="file" class="form-control uploadimage" name="suppliers_doc">
														<input type="text" class="form-control uploadimage"  name="old_suppliers_doc" value="<?php echo $u['old_clients_commercial_register']?>" hidden>
													</div>
												</div>
												<div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
													<img src="<?php echo $path; if($u){echo $u['suppliers_doc'];}else{echo '/defaults/img-icon.png';}?>" height="50px" class="imagePreviewURL" alt="">
												</div>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-md-4">
                                
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_PASS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="suppliers_password"  autocomplete="new-password"
                                            placeholder="********">
                                            <p class="help-block"><?php echo $lang['NOCHANGEINPASS'];?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_RE_PASS'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="password" class="form-control" name="confirmusers_password" autocomplete="new-password"
                                            placeholder="********">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 mb-5">
                        <div class="col d-flex jSTtify-content-end">
                            <button class="btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit supplier row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#addsuppliersForm').formValidation({
        excluded: [':disabled'],
        fields: {
            suppliers_name: {
                validators: {
                    notEmpty: {
						message: '<?php echo $lang['SETTINGS_SU_INSERT_NAME'];?>'
                    }
                }
            },
            suppliers_address: {
                validators: {
                    notEmpty: {
						message: '<?php echo $lang['SETTINGS_SU_INSERT_ADDRESS'];?>'

                    }
                }
            },
             suppliers_phone_one: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
                    }
                }
            },
//            suppliers_phone_two: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
//                    },
//                    regexp: {
//                        regexp: /^01[0-2]{1}[0-9]{8}/,
//                        message: '<?php echo $lang['INSERT_CORRECT_PHONE'];?>'
//                    }
//                }
//            },
//            suppliers_photo: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_SU_PHOTO'];?>'
//                    }
//                }
//            },
//            suppliers_doc: {
//                validators: {
//                    notEmpty: {
//                        message: '<?php echo $lang['SETTINGS_SU_IN_DOC'];?>'
//                    }
//                }
//            },
            suppliers_password: {
                validators: {
//                    notEmpty: {
//                        message: ' <?php echo $lang['SETTINGS_US_IN_PASS'];?>'
//                    },
                    stringLength: {
                        min: 8,
                        message: '<?php echo $lang['SETTINGS_US_MIN_8'];?>'
                    }
                }
            },
            confirmsuppliers_password: {
                 validators: {
//                    notEmpty: {
//                        message: '  <?php echo $lang['SETTINGS_US_IN_PASS_AGAIN'];?>'
//                    },
                    identical: {
                        field: 'users_password',
                        message: '<?php echo $lang['SETTINGS_US_IN_NOT_SAME'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // suppliers_name input[name="suppliers_name"]
        // suppliers_address input[name="suppliers_address"]
        // suppliers_phone_one input[name="suppliers_phone_one"]
        // suppliers_phone_two input[name="suppliers_phone_two"]
        // supplierImg input[name="supplierImg"]
        // suppliers_doc input[name="suppliers_doc"]
        // password input[name="password"]
        // confirmusers_password input[name="confirmusers_password"]
    })

    // product search input name =>> suppliersSearch
})	


</SCRIPT>



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
	include("./inc/Classes/system-settings_users.php");
	$setting_user = new systemSettings_users();
	include("./inc/Classes/system-settings_jobs.php");
	$setting_job = new systemSettings_jobs();
	include("./inc/Classes/system-settings_user_group.php");
	$setting_users_group = new systemSettings_user_group();
	include("./inc/Classes/system-settings_departments.php");
	$setting_department = new systemSettings_departments();

    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		if($group['settings_users'] == 0){
			header("Location:./permission.php");
			exit;
		}else
		{
			if($_GET['action'] == 'add')
			{
				$add = true;
			}
			$departments   = $setting_department->getsiteSettings_departments();
			$jobs          = $setting_job->getsiteSettings_jobs();
			$groups        = $setting_users_group->getsiteSettings_user_group();

			if($_POST)
			{
				$_user['users_name']                        =       sanitize($_POST["users_name"]);
				$_user['users_birthday']                    =       sanitize($_POST["users_birthday"]);
				$_user['users_department_id']               =       intval($_POST["users_department_id"]);
				$_user['users_job_id']                      =       intval($_POST["users_job_id"]);
				$_user['users_graduation_year']               =       sanitize($_POST["users_graduation_year"]);
				$_user['users_qualification']               =       sanitize($_POST["users_qualification"]);
				$_user['users_phone']                       =       sanitize($_POST["users_phone"]);
				$_user['users_email']                       =       sanitize($_POST["users_email"]);
				$_user['users_address']                     =       sanitize($_POST["users_address"]);
				$_user['users_group']                       =       intval($_POST["users_group"]);
				$_user['users_salary']                      =       sanitize($_POST["users_salary"]);
				$_user['users_password']                    =       password_hash(sanitize($_POST["users_password"]),PASSWORD_DEFAULT);
				
				if( $_FILES && ( $_FILES['users_photo']['name'] != "") && ( $_FILES['users_photo']['tmp_name'] != "" ) )
				{
					include_once("./inc/Classes/upload.class.php");
					$allow_ext = array("jpg","jpeg","gif","png");
					$upload    = new Upload($allow_ext,false,0,0,5000,$upload_path,".","",false);
					$files['name'] 	= addslashes($_FILES["users_photo"]["name"]);
					$files['type'] 	= $_FILES["users_photo"]['type'];
					$files['size'] 	= $_FILES["users_photo"]['size']/1024;
					$files['tmp'] 	= $_FILES["users_photo"]['tmp_name'];
					$files['ext']		= $upload->GetExt($_FILES["users_photo"]["name"]);
					$upfile	= $upload->Upload_File($files);
					if($upfile)
					{
						$_user['users_photo']  = $upfile['newname'];

					}
//					@unlink($path.$_POST['old_users_photo']);
				}
				
				$add = $setting_user->addSettings_users($_user);
				if($add == 1)
				{
					$logs->addLog(NULL,
						array(
							"type" 		        => 	"users",
							"module" 	        => 	"users",
							"mode" 		        => 	"add_users",
							"id" 	        	=>	$_SESSION['id'],
						),"admin",$_SESSION['id'],1
						);
					
						header("Location:./add_user.php?action=add");
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
                    <span class="blueSky"><strong> &gt; </strong>   <?php echo $lang['SETTINGS_US_USERS'];?></span>
                </p>
            </div>
        </div>
        <!-- end links row -->
        
         <!-- search bar row -->
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <form id="userSearchForm" method="get" action="./settings_users.php">
                    <div class="form-group has-search">
                        <label for=""><?php echo $lang['SEARCH'];?></label>
                        <button class="btn btn-info form-control-feedback" type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="" id="userSearch" name="q">
                    </div>
                </form>
            </div>
        </div>
        <!-- end search bar row -->


         <!-- add/edit user row -->
        <div class="row centerContent">
            <div class="col">
               <?php 
						if($add == 1){
							echo alert_message("success",$lang['SETTINGS_US_SUCCESS']);
						}elseif($add == 2){
							echo alert_message("danger",$lang['SETTINGS_US_INSERT_BEFORE']);
						}elseif($add == 3){
							echo alert_message("danger",$lang['SETTINGS_US_INSERT_PHONE_BEFORE']);
						}
					?>
                <form method="post" id="addusersForm" enctype="multipart/form-data">
                    <h5><?php echo $lang['SETTINGS_US_ADD_USERS'];?></h5>
                    <div class="darker-bg centerDarkerDiv formCenterDiv">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_NAME_JOB'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="users_name" value ="<?php if($_user){echo $_user['users_name'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_NAME_JOB'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_BIRTH_DAY'];?></label>
                                    <div class="col-xs-5">
                                        <input type="date" name="users_birthday" class="form-control" value ="<?php if($_user){echo $_user['users_birthday'];}?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_D_DEPARMENT'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="users_department_id" class="form-control" id="slct">
                                                <option selected disabled> <?php echo $lang['SETTINGS_J_CHOOSE_D'];?></option>
                                                <?php 
													if($departments)
													{
														foreach($departments as $k=>$d)
														{
															echo'<option value="'.$d['departments_sn'].'"';if( $_user['users_department_id'] == $d['departments_sn']){echo'selected';}echo'>'.$d['departments_name'].'</option>';
														}
													}
												?>
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_JOB'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="users_job_id" class="form-control" id="slct">
                                                <option selected disabled> <?php echo $lang['SETTINGS_US_JOB_C'];?></option>
                                                <?php 
													if($jobs)
													{
														foreach($jobs as $k=>$j)
														{
															echo'<option value="'.$j['jobs_sn'].'"';if($_user['users_group']==$j['jobs_sn']){echo'selected';}echo'>'.$j['jobs_name'].'</option>';
														}
													}
												?>
                                                
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_QALIF'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="users_qualification" value ="<?php if($_user){echo $_user['users_qualification'];}?>"
                                            placeholder="<?php echo $lang['SETTINGS_US_QALIF'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_YEAR'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="users_graduation_year" value ="<?php if($_user){echo $_user['users_graduation_year'];}?>"
                                            placeholder="<?php echo $lang['SETTINGS_US_YEAR'];?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_PHONE'];?> </label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="users_phone" value ="<?php if($_user){echo $_user['users_phone'];}?>"
                                            placeholder="<?php echo $lang['SETTINGS_US_PHONE'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_PHOTO'];?></label>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn"> <?php echo $lang['SETTINGS_C_UPLOAD_FILE'];?></button>
                                                <input type="file" class="form-control uploadimage" id="logo"
                                                    name="users_photo">
                                            </div>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end align-items-end mb-2">
                                            <img src="images/img-icon.png" height="50px" class="imagePreviewURL" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_ADDRESS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="users_address" value ="<?php if($_user){echo $_user['users_address'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_ADDRESS_HOLD'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_EMAIL'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="users_email" autocomplete="new-password"  placeholder="user@abc.com" value ="<?php if($_user){echo $_user['users_email'];}?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_PASS'];?></label>
                                    <div class="col-xs-5">
                                        <input type="password" class="form-control" name="users_password"  autocomplete="new-password"
                                            placeholder="********">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_RE_PASS'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="password" class="form-control" name="confirmusers_password" autocomplete="new-password"
                                            placeholder="********">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_GROUP'];?></label>
                                    <div class="col-xs-5">
                                        <div class="select">
                                            <select name="users_group" class="form-control" id="slct">
                                                <option selected disabled> <?php echo $lang['SETTINGS_US_GROUP_C'];?></option>
                                                <?php 
													if($groups)
													{
														foreach($groups as $k=>$g)
														{
															echo'<option value="'.$g['group_sn'].'"';if($_user['users_group']==$g['group_sn']){echo'selected';}echo'>'.$g['group_name'].'</option>';
														}
													}
												?>
                                                
                                              </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-xs-3"> <?php echo $lang['SETTINGS_US_SALARY'];?></label>
                                    <div class="col-xs-5 ">
                                        <input type="text" class="form-control" name="users_salary" value ="<?php if($_user){echo $_user['users_salary'];}?>"
                                            placeholder=" <?php echo $lang['SETTINGS_US_SALARY'];?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                  
                    </div>

                    <div class="row mt-2 mb-5">
                        <div class="col d-flex justify-content-end">
                            <button class="btn roundedBtn" type="submit"> <?php echo $lang['SETTINGS_C_SAVE'];?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end add/edit user row -->
    </div>
    <!-- end page content -->

<?php include './assets/layout/footer.php';?>
<SCRIPT>
$(document).ready(function () {

    $('#addusersForm').formValidation({
        excluded: [':disabled'],
        fields: {
            users_name: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_USER_NAME'];?>'
                    }
                }
            },
            users_birthday:{
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_BIRTH'];?>'
                    }
                }
            },
            users_department_id:{
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_J_CHOOSE_D'];?>'
                    }
                }
            },
            users_job_id:{
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_JOB_C'];?>'
                    }
                }
            },
            users_qualification: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_QUAL'];?>'
                    }
                }
            },
            gradYear: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_YEAR'];?>'
                    },
                    digits:{
                        message: '<?php echo $lang['SETTINGS_US_MUST_NUM'];?>'
                    },
                    stringLength: {
                      min: 4,
                      max: 4,
                      message: ' <?php echo $lang['SETTINGS_US_MAX_4'];?>'
                    }
                }
            },
            users_phone: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHONE'];?>'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: '<?php echo $lang['SETTINGS_US_CORRECT_PHONE'];?>'
                    }
                }
            },
            users_photo:{
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_PHOTO'];?>'
                    }
                }
            },
            users_address: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_ADDRESS'];?>'
                    }
                }
            },
            userEmail: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_EMAIL'];?>'
                    },
                    emailAddress: {
                        message: '<?php echo $lang['SETTINGS_US_IN_EMAIL_CORRECT'];?>'
                    }
                }
            },
            users_password: {
                validators: {
                    notEmpty: {
                        message: ' <?php echo $lang['SETTINGS_US_IN_PASS'];?>'
                    },
                    stringLength: {
                        min: 8,
                        message: '<?php echo $lang['SETTINGS_US_MIN_8'];?>'
                    }
                }
            },
            confirmusers_password: {
                validators: {
                    notEmpty: {
                        message: '  <?php echo $lang['SETTINGS_US_IN_PASS_AGAIN'];?>'
                    },
                    identical: {
                        field: 'users_password',
                        message: '<?php echo $lang['SETTINGS_US_IN_NOT_SAME'];?>'
                    }
                }
            },
            userAccess: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_GROUP_C'];?>'
                    }
                }
            },
            users_salary: {
                validators: {
                    notEmpty: {
                        message: '<?php echo $lang['SETTINGS_US_IN_SALARY'];?>'
                    },
                    regexp: {
                        regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: '<?php echo $lang['SETTINGS_US_SALARY_CH'];?>'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // users_name input[name="users_name"]
        // userdescription input[name="userdescription"]
    })



})


</SCRIPT>



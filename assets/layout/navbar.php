<!-- navbar -->
<nav class="navbar fixed-top navbar-dark main-bg-lgrd navbar-expand-md mainNavbar">
	<!-- only mob menu items -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<img class="mobmenuLogo" src="<?php echo $path;echo ($companyinfo['companyinfo_logo'])?$companyinfo['companyinfo_logo']:$avater_default;?>" height=50 />
	<a class="nav-link dropdown-toggle notificationIcon p-0" href="#" id="noti" role="button" data-toggle="dropdown"
		aria-haspopup="true" aria-expanded="false">
		<!-- <i class="fas fa-bell cool-grey mainBell"></i> -->
		<i class="fas fa-bell cool-grey mobmenuBell"></i>
	</a>
	<div class="dropdown-menu notifiDropdown mobileNoti_dp" aria-labelledby="noti">
		<a class="dropdown-item" href="#">
			لقد تم .. .. .. .. .. .. .. .. ..
			<div><small>منذ 3 ساعات</small></div>
		</a>
		<a class="dropdown-item" href="#">
			يوجد اشعار .. ..
			<div><small>أمس 09:10 AM</small></div>
		</a>
	</div>
	<!-- end only mob menu items-->
	<div class="collapse navbar-collapse flex-column" id="navbar">
		<!-- top nav - header -->
		<div class="container">
			<ul class="navbar-nav topNav-ul nav w-100">
				<a class="navbar-brand brandNP" href="./index.php">
					<img src="<?php echo $path;echo ($companyinfo['companyinfo_logo'])?$companyinfo['companyinfo_logo']:$avater_default;?>" height=50 />
					<p class="mb-0 brand-name"><?php echo $companyinfo['companyinfo_name'];?></p>
				</a>

				<div class="userNav-info mt-2 mt-md-0 top_nav">
					<span>
						<a class="nav-link dropdown-toggle notificationIcon" href="#" id="noti" role="button"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
								class="fas fa-bell cool-grey mainBell"></i>
						</a>
						<div class="dropdown-menu notifiDropdown" aria-labelledby="noti">
							<a class="dropdown-item" href="#">
								لقد تم .. .. .. .. .. .. .. .. ..
								<div><small>منذ 3 ساعات</small></div>
							</a>
							<a class="dropdown-item" href="#">
								يوجد اشعار .. ..
								<div><small>أمس 09:10 AM</small></div>
							</a>
						</div>

					</span>

					<hr>
					<span>
						<a class="nav-link dropdown-toggle " id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo $user_login['users_name'];?>
							<small class="userGroup"><?php echo $group['group_name'];?></small>
						</a>

						<div class="dropdown-menu userDropdown" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="#"> <?php echo $lang['PROFILE_PAGE'];?></a>
							<a class="dropdown-item" href="./login.php?do=logout"><?php echo $lang['LGUT_SUBMIT'];?></a>
						</div>
					</span>
					<img src="<?php echo $path; if($user_login['users_photo']!= ""){echo $user_login['users_photo'];}else{echo $profile_default;}?>" class="rounded-circle userImg">
				</div>
			</ul>
		</div>
		<!-- end top nav - header -->

		<!-- Navbar - menu Items -->
		<div class="gradientBorderDiv"></div>
		<div class="main-bg-lgrd" style="width: 100%;">
			<div class="container">
				<ul class="navbar-nav nav-ul  nav w-100">
					<li class="nav-item <?php if($page_name == "index"){echo "active";}?>">
						<a class="nav-link" href="./index.php" >
							<i class="fas fa-chart-bar"></i>
							<?php echo $lang['DASHBOARD'];?>
						</a>
					</li>
					<?php
					echo'<li class="nav-item dropdown  '; if($page_name == "add_operation" || $page_name == "operations" || $page_name == "expense" ){echo 'active';}echo'">
						<a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-suitcase"></i>
							 '.$lang['OPERATIONS_NAV_TITLE'].'
						</a>
						<ul class="dropdown-menu" aria-labelledby="dropdown1">';
							if($group['operations']  == 1)
							{
							  echo'<li class="dropdown-item" href="./add_operation.php?o='.generate_unique_code(2).'"><a> '.$lang['OPERATIONS_DROP_TITLE_1'].'</a></li>';
							}
							if($group['clients_pricing']  == 1)
							{
							  echo'<li class="dropdown-item" href="./add_expense.php"><a>'.$lang['OPERATIONS_DROP_TITLE_2'].'</a></li>';
							}
							if($group['expense']  == 1)
							{
							  echo'<li class="dropdown-item" href="./add_expense.php"><a> '.$lang['OPERATIONS_DROP_TITLE_3'].'</a></li>';
							}
						echo'</ul>
					</li>';
						?>
					<li class="nav-item dropdown ">
						<a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-user-friends"></i>
							موردين
						</a>
						<ul class="dropdown-menu" aria-labelledby="dropdown2">
							<li class="dropdown-item" href="./suppliers_accounts_search"><a>حسابات
									الموردين</a></li>
							<li class="dropdown-item" href="./suppliers_pricing_search"><a>تسعير سابق</a>
							</li>
						</ul>
					</li>
					<?php
						echo'<li class="nav-item dropdown '; if($page_name == "pricing_search" ||$page_name == "add_client_pricing" ||$page_name == "pricing_search" ){echo 'active';}echo'">
								<a class="nav-link dropdown-toggle" id="dropdown3" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-chart-line"></i>
									'.$lang['SETTINGS_C_F_CLIENT'].'
								</a>
								<ul class="dropdown-menu" aria-labelledby="dropdown3">';
								if($group['clients_pricing']  == 1)
								{
								  echo'<li class="dropdown-item" href="./add_client_pricing.php"><a>'.$lang['SETTINGS_C_F_PRICE'].' </a></li>';
								}
								if($group['clients_finance']  == 1)
								{
								  echo'<li class="dropdown-item" href="./customers_accounts_search"><a >'.$lang['SETTINGS_C_F_FINANCES'].'</a></li>';
								}
								if($group['clients_old_pricing']  == 1)
								{
								  echo'<li class="dropdown-item" href="./pricing_search.php"><a >  '.$lang['SETTINGS_C_F_PREFICE_PRICE'].'</a></li>';
								}
									
								echo'</ul>
							</li>';
						?>
					
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="dropdown4" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-file-invoice-dollar"></i>
							خزنة وبنوك
						</a>
						<ul class="dropdown-menu" aria-labelledby="dropdown4">
							<li class="dropdown-item" href="./banks_operations_deposit"><a>ايداع
								</a></li>
							<li class="dropdown-item" href="./banks_operations_withdrawal"><a>سحب
								</a></li>
							<li class="dropdown-item" href="./banks_operations_transfer"><a>تحويل
								</a></li>
							<li class="dropdown-item" href="./banks_credits_Details"><a>الاطلاع علي
									الأرصدة </a></li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./reminders">
							<i class="fas fa-bell"></i>
							تذكيرات
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="dropdown5" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-chart-pie"></i>
							تقارير ومرفقات
						</a>
						<ul class="dropdown-menu" aria-labelledby="dropdown5">
							<li class="dropdown-item" href="./reports"><a>تقارير</a></li>
							<li class="dropdown-item" href="./documents"><a>مرفقات</a></li>
						</ul>
					</li>
					
				<?php 
				echo '<li class="nav-item dropdown '; if($page_name == "settings_company" ||$page_name == "settings_departments"||$page_name == "add_department"||$page_name == "edit_department" ||$page_name == "setting_jobs" ||$page_name == "add_job" ||$page_name == "edit_job" ||$page_name == "setting_products" ||$page_name == "add_product" ||$page_name == "edit_product" ||$page_name == "settings_users" ||$page_name == "add_user" ||$page_name == "edit_user" ||$page_name == "add_stock" ||$page_name == "edit_stock" ||$page_name == "setting_stocks"||$page_name == "add_client" ||$page_name == "edit_client" ||$page_name == "settings_clients" ||$page_name == "add_supplier" ||$page_name == "edit_supplier" ||$page_name == "settings_suppliers"||$page_name == "add_bank" ||$page_name == "edit_bank" ||$page_name == "settings_banks"  ){echo "active";}echo'">
						<a class="nav-link dropdown-toggle" id="dropdown6" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-cog"></i>
							'.$lang['SETTINGS_TITLE'].'
						</a>
						<ul class="dropdown-menu" aria-labelledby="dropdown6">';
						if($group['setting_company']  == 1 ||$group['settings_department']  == 1 || $group['setting_jobs']  == 1 ||$group['setting_stocks']  == 1 )
						{
							echo'<li class="dropdown-item dropdown submenutitle">
									<a class="dropdown-toggle" id="dropdown6-1" data-toggle="dropdown"
										aria-haspopup="true" aria-expanded="false"> '.$lang['SETTINGS_COMPANY'].'</a>
									<ul class="dropdown-menu submenu" aria-labelledby="dropdown6-1">';
									  if($group['setting_company']  == 1 )
									  {
										echo'<li class="dropdown-item" href="./settings_company.php"><a>'.$lang['SETTINGS_C_DETAIL'].'</a></li>';
									  }
									  if($group['settings_department']  == 1 )
									  {
										echo'<li class="dropdown-item" href="./add_department.php"><a>'.$lang['SETTINGS_DEPARTMENTS'].'</a></li>';
									  }
									  if($group['setting_jobs']  == 1 )
									  {
										echo'<li class="dropdown-item" href="./add_job.php"><a>'.$lang['SETTINGS_JOBS'].'</a></li>';
									  }
									 if($group['setting_stocks']  == 1 )
									  {
										echo'<li class="dropdown-item" href="./add_stock.php"><a>'.$lang['SETTINGS_STOCKS'].'</a></li>';
									  }
								echo'</ul>
								</li>';
						}
						if($group['settings_products']  == 1 )
						  {
							echo'<li class="dropdown-item" href="./add_product.php"><a>'.$lang['SETTINGS_P_PRODUCTS'].'</a></li>';
						  }
					 
						if($group['settings_suppliers']  == 1 )
						  {
							echo'<li class="dropdown-item" href="./add_supplier.php"><a>'.$lang['SETTINGS_SU_SUPPLIERS'].'</a></li>';
						  }
						if($group['settings_clients']  == 1 )
						  {
							echo'<li class="dropdown-item" href="./add_client.php"><a>'.$lang['SETTINGS_CL_CLIENTS'].'</a></li>';
						  }
						if($group['settings_banks']  == 1 )
						  {
							echo'<li class="dropdown-item" href="./add_bank.php"><a>'.$lang['SETTINGS_BAN_BANKS'].'</a></li>';
						  }
						 if($group['settings_users']  == 1 )
						  {
							echo'<li class="dropdown-item" href="./add_user.php"><a>'.$lang['SETTINGS_US_USERS'].'</a></li>';
						  }
							
							echo '<li class="dropdown-item" href="./settings_access"><a>صلاحيات
									النظام</a></li>
						</ul>
					</li>';?>

					<li class="nav-item <?php if($page_name == "about" ){echo "active";}?>">
						<a class="nav-link" href="./about.php" >
							<i class="fas fa-info-circle"></i>
							<?php echo $lang['ABOUT_TITLE']?>
						</a>
					</li>
				</ul>
			</div>

		</div>
		<!-- end Navbar - menu Items -->

	</div>
</nav>
<!-- end navbar -->

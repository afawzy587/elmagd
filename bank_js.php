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
    include("./inc/Classes/system-settings_banks.php");
	$setting_bank = new systemSettings_banks();
	
	include("./inc/Classes/system-deposits.php");
	$deposit = new systemDeposits();
    if($login->doCheck() == false)
    {
        header("Location:./login.php");
        exit;
    }else{
		switch($_GET['do'])
		{
		case "bank_approve_transfer":
			if ($_POST) {
				include("./inc/Classes/system-money_transfers.php");
				$trensfer = new systemMoney_transfers();
				$id      = intval($_POST['id']);
				$data    = $trensfer->Bank_Approved($id);
				if ($data == 1) {
					echo 100;
				} else {
					echo 400;
				}
				exit;
			}
	    case "bank_approve":
			if ($_POST) {
				$id      = intval($_POST['id']);
				$data    = $deposit->Bank_Approved($id);
				if ($data == 1) {
					echo 100;
				} else {
					echo 400;
				}
				exit;
			}
		case "collect":
			if ($_POST) {
				$id      = intval($_POST['id']);
				$data    = $deposit->Bank_Collect($id);
				if ($data == 1) {
					echo 100;
				} else {
					echo 400;
				}
				exit;
			}
        case"item_data":
			if($_POST)
			{
				$id      = intval($_POST['id']);
				$data    = $setting_bank->Get_Account_data($id);
				if(is_array($data))
				{
                    echo json_encode($data) ;
                    
				}else{
					echo "400";
				}
				exit;
			}
		case"get_invoices":
			if($_POST)
			{
				$bank         = intval($_POST['bank']);
				$acount       = sanitize($_POST['acount']);
				$acount_id     = intval($_POST['acount_id']);
				$invoices = $deposit->Get_invoices($bank,$acount,$acount_id);
				if($invoices){
					$html =' <table class="table .table-bordered">
								<thead>
									<tr>
										<th></th>
										<th>'.$lang['OPERATIONS_DATE'].'</th>
										<th>'.$lang['SETTINGS_C_F_PAYMENT_MONEY'].'</th>
										<th>'.$lang['BANKS_CUT_VALUE'].'</th>
										<th>'.$lang['BANKS_MONEY_PULL'].'</th>
										<th>'.$lang['BANKS_BENFITS'].'</th>
										<th>'.$lang['BANKS_MONEY_DIS'].'</th>
										<th>'.$lang['BANKS_MONEY_REMIN'].'</th>
										<th>'.$lang['SETTINGS_C_F_M_CLIENT'].'</th>
										<th>'.$lang['SETTINGS_C_F_M_PRODUCT'].'</th>
									</tr>
								</thead>
								<tbody>';
					
					foreach($invoices as $k => $i)
					{
						$html .= '<tr>
									<td>
										<input class="customized-checkbox" id="customized-checkbox-' . $i['deposits_sn'] . '" type="checkbox" name="invoices_id[]" value="' . $i['deposits_sn'] . '" >
										<label class="customized-checkbox-label" for="customized-checkbox-' . $i['deposits_sn'] . '"></label>
										<input type="text" id="max_' . $i['deposits_sn'] . '" value="' . ($i['deposits_cut_value'] - ($i['deposit_money_pull'])) . '" hidden>
									</td>
									<td>' . _date_format($i['deposits_cheque_date']) . '</td>
									<td>' . $i['deposits_value'] . '</td>
									<td>' . $i['deposits_cut_value'] . '</td>
									<td>' . $i['deposit_money_pull'] . '</td>
									<td>' . $i['deposit_benefits'] . '</td>
									<td>' . ($i['deposit_benefits'] + $i['deposit_money_pull']) . '</td>
									<td>' . ($i['deposits_cut_value'] - ($i['deposit_money_pull'])) . '</td>
									<td>'.get_data('settings_clients','clients_name','clients_sn',$i['deposits_client_id']).'</td>
									<td>'.get_client_product($i['deposits_product_id']).'</td>
								 </tr>';

					}
					$html .='</tbody>';
				}else{
					$html ='<h5>'.$lang['NO_INVOICES'].'</h5>';
				}
				echo $html;
				exit;
			}
		case"delete":
			if($_POST)
			{
				$id      = sanitize($_POST['id']);
				$delete  = $setting_bank->Delete_bank($id);
				if($delete == 1)
				{
					echo 100;
					exit;
				}else{
					echo 400;
					exit;
				}
				
			}
		case"delete_bank":
			if($_POST)
			{
				$id      = sanitize($_POST['id']);
				$delete  = $setting_bank->Delete_bank_main($id);
				if($delete == 1)
				{
					echo 100;
					exit;
				}else{
					echo 400;
					exit;
				}
			}
		case"account":
			if($_POST)
			{
				$id       = intval($_POST['bank']);
				$credit   = get_banks_credit_account($id);
				$saving   = get_banks_saving_account($id);
				$current  = get_banks_current_account($id);
				$html = '<option selected disabled>'.$lang['SETTINGS_C_F_CHOOSE_BANK_FIRST'].'</option>';
				if($credit >0)
				{
					$html .= '<option value="credit">'.$lang['SETTINGS_BAN_CREDIT_ACCOUNT_MENU'].'</option>';
				}
				if($saving >0)
				{
					$html .= '<option value="saving">'.$lang['SETTINGS_BAN_SAVE'].'</option>';
				}
				if($current >0)
				{
					$html .= '<option value="current">'.$lang['SETTINGS_BAN_CURRENT'].'</option>';
				}
				echo $html;
				exit;
			}
		case"max_value":
			if($_POST)
			{
				$data['bank']             = sanitize($_POST['bank']);
				$data['transfer_type']    = sanitize($_POST['transfer_type']);
				$data['account_type']     = sanitize($_POST['account_type']);
				$data['account_id']       = sanitize($_POST['account_id']);
				$value                    = $setting_bank->Get_Max_Value($data);
				echo floatval($value);
				exit;
			}
		case"delete_deposit":
			if($_POST)
			{
				$id      = sanitize($_POST['id']);
				$delete  = $deposit->Delete_deposits($id);
				if($delete == 1)
				{
					echo 100;
					exit;
				}else{
					echo 400;
					exit;
				}
			}
		}
    }

?>




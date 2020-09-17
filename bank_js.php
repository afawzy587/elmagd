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
										<input class="customized-checkbox" id="customized-checkbox-'.$i['deposits_sn'].'" type="checkbox" name="invoices_id['.$k.']" value="'.$i['deposits_sn'].'" >
										<label class="customized-checkbox-label" for="customized-checkbox-'.$i['deposits_sn'].'"></label>
									</td>
									<td>'._date_format($i['deposits_cheque_date']).'</td>
									<td>'.$i['deposits_value'].'</td>
									<td>'.$i['deposits_cut_value'].'</td>
									<td>'.$i['deposit_money_pull'].'</td>
									<td>'.$i['deposit_benefits'].'</td>
									<td>'.($i['deposit_benefits']+$i['deposit_money_pull']).'</td>
									<td>'.($i['deposits_value']-($i['deposit_benefits']+$i['deposit_money_pull'])).'</td>
									<td>'.get_data('settings_clients','clients_name','clients_sn',$i['deposits_client_id']).'</td>
									<td>'.get_client_product($i['deposits_product_id']).'</td>
								 </tr>';

					}
					$html .='</tbody>';
				}else{
					$html ='<h5>'.$lang['NO_INVOICES'].'</h5>';
				}
				echo $html;
			}
		}
    }

?>




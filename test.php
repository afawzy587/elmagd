<!-- bank item 2-->
                                        <div class="bank_item">
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_NAME_5'];?></label>
                                                        <div class="col-xs-5">
                                                            <input type="text" class="form-control" name="banks_credit_name[4]" value="<?php if($_bank){echo $_bank['banks_credit_name'][4];}?>"
                                                                placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_NAME_5'];?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_code[4]" value="<?php if($_bank){echo $_bank['banks_credit_code'][4];}?>"
                                                                        placeholder="<?php echo $lang['SETTINGS_BAN_CREDIT_CODE'];?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_OPEN_PALANCE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control openingCreditInput" name="banks_credit_open_balance[4]"  value="<?php if($_bank){echo $_bank['banks_credit_open_balance'][4];}?>"
                                                                        placeholder="0000">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_REPAYMENT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_repayment_period[4]" value="<?php if($_bank){echo $_bank['banks_credit_repayment_period'][4];}?>"
                                                                        placeholder="00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_RATE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_interest_rate[4]" value="<?php if($_bank){echo $_bank['banks_credit_interest_rate'][4];}?>"
                                                                        placeholder="00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3">  <?php echo $lang['SETTINGS_BAN_DUE_INT'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_duration_of_interest[4]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][4];}?>"
                                                                        placeholder="00">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_LIMIT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control creditLineInput" name="banks_credit_limit_value[4]" value="<?php if($_bank){echo $_bank['banks_credit_duration_of_interest'][4];}?>"
                                                                        placeholder="000">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_CUTT_VALUE'];?></label>
                                                                <div class="col-xs-5">
                                                                    <input type="text" class="form-control" name="banks_credit_cutting_ratio[4]" value="<?php if($_bank){echo $_bank['banks_credit_cutting_ratio'][4];}?>"
                                                                        placeholder="00">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[4]" id="type_1_5" value="day" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="day"){echo 'checked';}}else{echo 'checked';}?> >
                                                            <label class="form-check-label" for="type_1_5">
                                                                 <?php echo $lang['SETTINGS_BAN_BY_DAY'];?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check radioBtn">
                                                            <input class="form-check-input" type="radio"
                                                                name="banks_credit_repayment_type[4]" id="type_2_5" <?php if($_bank){if($_bank['banks_credit_repayment_type'][4] =="date"){echo 'checked';}}?>
                                                                value="date">
                                                            <label class="form-check-label smallLabel"
                                                                for="type_2_5">
                                                                <?php echo $lang['SETTINGS_BAN_BY_DATE'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"> <?php echo $lang['SETTINGS_BAN_CLIENT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_client[4]" class="form-control" id="banks_credit_client">
                                                                    <option selected disabled> <?php echo $lang['SETTINGS_BAN_CHOOSE_CLIENT'];?></option>
                                                                    <?php
																		if($clients)
																		{
																			foreach($clients as $cId=> $c)
																			{
																				echo '<option value="'.$c["clients_sn"].'"';if($_bank){if($c["clients_sn"] == $_bank['banks_credit_client'][4]){echo 'selected';}}echo'>'.$c["clients_name"].'</option>';
																			}
																		}
																	?>
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-xs-3"><?php echo $lang['SETTINGS_BAN_PRODUCT'];?></label>
                                                        <div class="col-xs-5">
                                                            <div class="select">
                                                                <select name="banks_credit_product[4]" class="form-control" id="slct">
                                                                    <option selected disabled> <?php echo $lang['SETTINGS_BAN_CHOOSE_PRODUCT'];?></option>
                                                                    <?php
																		if($products)
																		{
																			foreach($products as $pId=> $p)
																			{
																				echo '<option value="'.$p["products_sn"].'"';if($_bank){if($p["products_sn"] == $_bank['banks_credit_product'][4]){echo 'selected';}}echo'>'.$p["products_name"].'</option>';
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
                                        <!-- end bank item 5 -->
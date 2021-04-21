<style>
/*    .tab-container .nav.nav-tabs li a {
        background: #414141 !important;
        color: white !important;
    }
    .tab-container .nav.nav-tabs li a:hover {
        background: #414141 !important;
        color: white !important;
    }
    .tab-container .nav.nav-tabs li a:focus {
        background: #414141 !important;
        color: white !important;
    }
*/
    table.table_journal_entries_review td {
        border: 0px !important;
    }
    tr {
        border: none!important;
    }
/*    tr:nth-child(even){
        background: #414141 !important;
        border: none!important;
    }
*/
/*
    tr:hover {
        transition: .4s;
        background: transparent !important;
        color: white;
    }
    tr:hover .btn {
        border-color: #494949!important;
        border-radius: 0!important;
        -webkit-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
        box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.75);
    }
*/
</style>
<center>
    <table class="table_journal_entries_review"  width="100%" style="font-family: tahoma;">
        <tbody>
        <tr>
            <td>
                <br />
                <div class="tab-container tab-top tab-default">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#journal_review_bill<?php echo $info->temp_journal_id; ?>" data-toggle="tab"><i class="fa fa-gavel"></i> Review Journal</a></li>
<!--                         <li class=""><a href="#purchase_review_<?php //echo $info->temp_journal_id; ?>" data-toggle="tab"><i class="fa fa-folder-open-o"></i> Transaction</a></li> -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="journal_review_bill<?php echo $info->temp_journal_id; ?>" data-parent-id="<?php echo $info->temp_journal_id; ?>" style="min-height: 300px;">
                            <form id="frm_journal_review" role="form" class="form-horizontal row-border">
                            <input type="hidden" name="ref_no" value="<?php echo $info->ref_no; ?>">
                                <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Cash Receipt Journal</strong></span></h4>
                                <hr />

                                <div class="row">
                                    <div class="col-md-8">
                                        <div style="border: 1px solid lightgrey;padding: 2%;border-radius: 5px;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="hidden" name="temp_journal_id" value="<?php echo $info->temp_journal_id; ?>">
                                                    <label><b class="required">*</b> Txn # :</label><br/>
                                                    <input type="text" name="txn_no" class="form-control" style="font-weight: bold;" placeholder="TXN-MMDDYYY-XXX" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label> OR # / AR # :</label><br/>
                                                    <input type="text" name="or_no" id="or_no" class="form-control" value="<?php echo $info->ref_no; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label> <b class="required">*</b> Particular : </label><br/>
                                                    <select id="cbo_particulars" name="particular_id" class=" cbo_supplier_list selectpicker show-tick form-control" data-live-search="true" data-error-msg="Particular is required." required>
                                                        <optgroup label="Customers">
                                                            <?php foreach($customers as $customer){ ?>
                                                                <option value='C-<?php echo $customer->customer_id; ?>' <?php echo ($info->customer_id===$customer->customer_id?'selected':''); ?>><?php echo $customer->customer_name; ?></option>
                                                            <?php } ?>
                                                        </optgroup>

                                                        <optgroup label="Suppliers">
                                                            <?php foreach($suppliers as $supplier){ ?>
                                                                <option value='S-<?php echo $supplier->supplier_id; ?>' ><?php echo $supplier->supplier_name; ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label> <b class="required">*</b> Date :</label><br/>
                                                    <input type="text" name="date_txn" class="date-picker  form-control" value="<?php echo $info->date_txn; ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label> <b class="required">*</b> Branch : </label><br/>
                                                    <select name="department_id" class="cbo_department_list" data-error-msg="" required>
                                                        <?php foreach($departments as $department){ ?>
                                                            <option value="<?php echo $department->department_id; ?>" <?php echo ($info->link_department_id===$department->department_id?'selected':''); ?>><?php echo $department->department_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div style="border: 1px solid lightgrey;padding: 4%;border-radius: 5px;">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label><b class="required">*</b> Method of Payment :</label><br />
                                                            <select name="payment_method" class="cbo_payment_method pay_type_<?php echo $info->temp_journal_id; ?>">
                                                                <?php foreach($methods as $method){ ?>
                                                                    <option value="<?php echo $method->payment_method_id; ?>" <?php echo ($info->payment_method_id===$method->payment_method_id?'selected':''); ?>><?php echo $method->payment_method; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row for_check_billing_advances_<?php echo $info->temp_journal_id; ?> <?php if($info->payment_method_id != 2){ echo 'hidden'; } ?>">
                                                        <div class="col-lg-6">
                                                            <label><b class="required">*</b> Check Date :</label><br />
                                                            <div class="input-group">
                                                                <input type="text" name="check_date" class="date-picker form-control check_date_<?php echo $info->temp_journal_id; ?>" value="<?php if($info->check_date != '00/00/0000'){ echo $info->check_date; } ?>" data-error-msg="Check date is required!">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label><b class="required">*</b> Check # :</label><br />
                                                            <input type="text" name="check_no" class="form-control check_no_<?php echo $info->temp_journal_id; ?>" value="<?php echo $info->check_no ?>" data-error-msg="Check # is required!">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label>Amount :</label><br />
                                                            <input type="text" name="amount" class="form-control numeric" data-error-msg="Amount is required" value="<?php echo $info->amount; ?>">
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Journal Entries</strong></span></h4>
                                <hr />
                                <table id="tbl_entries_for_review_bill<?php echo $info->temp_journal_id; ?>" class="table table-striped" style="width: 100% !important;">
                                    <thead>
                                    <tr style="border-bottom:solid gray;">
                                        <th style="width: 30%;">Account</th>
                                        <th style="width: 15%;">Memo</th>
                                        <th style="width: 15%;text-align: right;">Dr</th>
                                        <th style="width: 15%;text-align: right;">Cr</th>
                                        <th style="width: 15%;text-align: left;">Department</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            $dr_total=0.00; $cr_total=0.00;
                                            foreach($entries as $entry){
                                        ?>
                                        <tr>
                                            <td>
                                                <select name="accounts[]" class="selectpicker show-tick form-control selectpicker_accounts" data-live-search="true" >
                                                    <?php foreach($accounts as $account){ ?>
                                                        <option value='<?php echo $account->account_id; ?>' <?php echo ($entry->account_id==$account->account_id?'selected':''); ?> ><?php echo $account->account_title; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="memo[]" class="form-control"  value="<?php echo $entry->memo; ?>"></td>
                                            <td><input type="text" name="dr_amount[]" class="form-control numeric" value="<?php echo number_format($entry->dr_amount,2); ?>"></td>
                                            <td><input type="text" name="cr_amount[]" class="form-control numeric"  value="<?php echo number_format($entry->cr_amount,2);?>"></td>
                                            <td><select  name="department_id_line[]" class="dept show-tick form-control selectpicker" data-live-search="true" > 
                                                <option value="0">[ None ]</option> 
                                                <?php foreach($departments as $department){ ?> 
                                                    <option value='<?php echo $department->department_id; ?>'  <?php echo ($info->link_department_id===$department->department_id?'selected':''); ?>><?php echo $department->department_name; ?></option> 
                                                <?php } ?> 
                                            </select></td> 
                                            <td>
                                                <button type="button" class="btn btn-default add_account"><i class="fa fa-plus-circle" style="color: green;"></i></button>
                                                <button type="button" class="btn btn-default remove_account"><i class="fa fa-times-circle" style="color: red;"></i></button>
                                            </td>
                                        </tr>
                                    <?php
                                                $dr_total+=$entry->dr_amount;
                                                $cr_total+=$entry->cr_amount;
                                            }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Total</strong></td>
                                        <td align="right"><strong><?php echo number_format($dr_total,2); ?></strong></td>
                                        <td align="right"><strong><?php echo number_format($cr_total,2); ?></strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <hr />
                                <label class="col-lg-2"> Remarks :</label><br />
                                <div class="col-lg-12">
                                    <textarea name="remarks" class="form-control" style="width: 100%;"></textarea>
                                </div>
                                <br /><hr />
                            </form>
                            <br /><br /><hr />
                            <div class="row">
                                <div class="col-lg-12">
                                    <button name="btn_finalize_journal_review" class="btn btn-primary"><i class="fa fa-check-circle"></i> <span class=""></span> Finalize and Post this Journal</button>

                                    <button name="btn_cancel_journal_review" style="float: right;" class="btn btn-danger"><i class="fa fa-times-circle"></i> <span class=""></span> Cancel Journal</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</center>
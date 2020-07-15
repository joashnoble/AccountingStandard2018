<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>JCORE - <?php echo $title; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-cdjp-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="">

    <?php echo $_def_css_files; ?>

    <link rel="stylesheet" href="assets/plugins/spinner/dist/ladda-themeless.min.css">
    <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
    <!--<link href="assets/dropdown-enhance/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">-->
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/iCheck/skins/minimal/blue.css" rel="stylesheet">              <!-- iCheck -->
    <link type="text/css" href="assets/plugins/iCheck/skins/minimal/_all.css" rel="stylesheet">                   <!-- Custom Checkboxes / iCheck -->

    <style>
        .alert {
            border-width: 0;
            border-style: solid;
            padding: 24px;
            margin-bottom: 32px;
        }
        .alert-danger, .alert-danger h1, .alert-danger h2, .alert-danger h3, .alert-danger h4, .alert-danger h5, .alert-danger h6, .alert-danger small {
            color: white;
        }

        .alert-danger {
            color: #dd191d;
            background-color: #f9bdbb;
            border-color: #e84e40;
        }


        .toolbar{
            float: left;
        }

        body {
            overflow-x: hidden;
        }

        td.details-control {
            background: url('assets/img/Folder_Closed.png') no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url('assets/img/Folder_Opened.png') no-repeat center center;
        }

        .child_table{
            padding: 5px;
            border: 1px #ff0000 solid;
        }

        .glyphicon.spinning {
            animation: spin 1s infinite linear;
            -webkit-animation: spin2 1s infinite linear;
        }
        .select2-container{
            min-width: 100%;
            z-index: 999999999;
        }
        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }


        .custom_frame{

            border: 1px solid lightgray;
            margin: 1% 1% 1% 1%;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        .numeric{
            text-align: right;
        }

        .boldlabel {
            font-weight: bold;
        }

        .form-group {
            padding:0;
            margin:5px;
        }

        .input-group {
            padding:0;
            margin:0;
        }

        textarea {
            resize: none;
        }

        .modal-body p {
            margin-left: 20px !important;
        }

        #img_user {
            padding-bottom: 15px;
        }

        .select2-container {
            width: 100% !important;
        }

        .right_align_items{
        	text-align: right;
        }

        input[type=checkbox] {
          /* Double-sized Checkboxes */
          margin-top: 10px;
          margin-left: 10px;
          -ms-transform: scale(1.5); /* IE */
          -moz-transform: scale(1.5); /* FF */
          -webkit-transform: scale(1.5); /* Safari and Chrome */
          -o-transform: scale(1.5); /* Opera */
        }

        #tbl_temp_vouchers_list_filter{
            display: none;
        }
         div.dataTables_processing{ 
        position: absolute!important; 
        top: 0%!important; 
        right: -45%!important; 
        left: auto!important; 
        width: 100%!important; 
        height: 40px!important; 
        background: none!important; 
        background-color: transparent!important; 
        } 
        #tbl_accounts_receivable_filter{
            display: none;
        }
        .centered{
            text-align: center;
        }
    </style>

</head>

<body class="animated-content" style="font-family: tahoma;">

<?php echo $_top_navigation; ?>

<div id="wrapper">
<div id="layout-static">

<?php echo $_side_bar_navigation;?>

<div class="static-content-wrapper white-bg">
<div class="static-content"  >

<div class="page-content"><!-- #page-content -->

<ol class="breadcrumb" style="margin-bottom: 0px;">
    <li><a href="dashboard">Dashboard</a></li>
    <li><a href="Cash_vouchers">Temporary Voucher</a></li>
</ol>

<div class="container-fluid">
<div data-widget-group="group1">
<div class="row">
<div class="col-md-12">

<div id="div_payable_list">
    <div class="panel-group panel-default" id="accordionA">
        <div class="panel panel-default" style="border-radius:6px;">
            <div class="panel-body panel-responsive">
            <h2 class="h2-panel-heading">Temporary Vouchers Journal</h2><hr>
                <div id="collapseOne" class="collapse in">
                <div class="row">
                    <div class="col-lg-3">
                    &nbsp;<br>
                        <button class="btn btn-primary" id="btn_new" style="text-transform: none;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="New Journal" ><i class="fa fa-plus"></i> New Temporary Voucher</button>
                    </div>
                    <div class="col-lg-2">
                            From :<br />
                            <div class="input-group">
                                <input type="text" id="txt_start_date_cdj" name="" class="date-picker form-control" value="<?php echo date("m").'/01/'.date("Y"); ?>">
                                 <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                 </span>
                            </div>
                    </div>
                    <div class="col-lg-2">
                            To :<br />
                            <div class="input-group">
                                <input type="text" id="txt_end_date_cdj" name="" class="date-picker form-control" value="<?php echo date("m/t/Y"); ?>">
                                 <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                 </span>
                            </div>
                    </div>
                    <div class="col-lg-3">
                            Filter:<br />
                            <select id="cbo_table_filter" class="form-control">
                                <option value="1">Pending / For Verification</option>
                                <option value="2">Approved</option>
                                <option value="3">Disapproved</option>
                            </select> 
                    </div>
                    <div class="col-lg-2">
                        Search :<br />
                        <input type="text" id="searchbox_cdj" class="form-control">
                    </div>
                </div><br>
                        <div class="">
                            <table id="tbl_temp_vouchers_list" class="table-striped table" cellspacing="0" width="100%">
                                <thead class="">
                                <tr>    
                                    <th></th>
                                    <th style="width: 15%;">Txn #</th>
                                    <th>Type</th>
                                    <th>Particular</th>
                                    <th>Method</th>
                                    <th>Txn Date</th>
                                    <th>Prepared By</th>
                                    <th style="width: 15%;"><center>Action</center></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>

<div id="div_payable_fields" style="display: none;">
    <div class="panel panel-default" style="border-radius:6px;" >
    <div class="panel-body panel-responsive">
    <h2 class="h2-panel-heading"> Temporary Journal</h2>
        <form id="frm_journal" role="form" class="form-horizontal">
            <div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                       <b class="required"> * </b> <label>Date  :</label><br />
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" name="date_txn" class="date-picker form-control" data-error-msg="Date is required." required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                       <b class="required"> * </b> <label>Txn #  :</label><br />
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-code"></i>
                                            </span>
                                            <input type="text" name="txn_no" class="form-control" placeholder="TXN-YYYYMMDD-XXX" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <b class="required"> * </b> <label>Reference type :</label><br />
                                        <select id="cbo_refType" class="form-control" name="ref_type" data-error-msg="Reference type is required." required>
                                            <option value="CV" selected>CV</option>
                                            <option value="JV">JV</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                         <label>Reference #:</label><br />
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-code"></i>
                                            </span>
                                            <input type="text" maxlength="15" class="form-control" name="ref_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <b class="required"> * </b> <label>Supplier  :</label><br />
                                        <select id="cbo_suppliers" name="supplier_id" class="selectpicker show-tick form-control" data-live-search="true" data-error-msg="Supplier name is required." required>
                                            <?php foreach($suppliers as $supplier){ ?>
                                                <option value='<?php echo $supplier->supplier_id; ?>'><?php echo $supplier->supplier_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                       <b class="required"> * </b> <label>Department  :</label><br />
                                        <select id="cbo_branch" name="department_id" class="selectpicker show-tick form-control" data-live-search="true" data-error-msg="Department is required." required>
                                            <?php foreach($departments as $department){ ?>
                                                <option value='<?php echo $department->department_id; ?>'><?php echo $department->department_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div style="margin-top: 25px;">
                                            <input type="checkbox" id="is_2307" value="1">
                                            &nbsp;<label for="is_2307">Apply 2307 Form</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div style="margin-top: 5px;">
                                            <label>ATC :</label><br />
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-code"></i>
                                                </span>
                                                <input type="text" name="atc_2307" id="atc_2307" class="form-control" data-error-msg="ATC is required.">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                            <label>Remarks :</label><br />
                                            <textarea class="form-control" name="remarks_2307" id="remarks_2307" data-error-msg="Remarks is required." rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <b class="required"> * </b> <label>Method of Payment  :</label><br />
                                        <select id="cbo_pay_type" name="payment_method" class="form-control" data-error-msg="Payment method is required." required>
                                            <?php foreach($payment_methods as $payment_method){ ?>
                                                <option value='<?php echo $payment_method->payment_method_id; ?>'><?php echo $payment_method->payment_method; ?></option>
                                            <?php } ?>
                                        </select>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Check Type :</label><br />
                                        <select id="cbo_check_type" class="form-control" name="check_type_id">
                                        <option value="0">None </option>
                                        <?php foreach($check_types as $check_type){ ?>
                                            <option value='<?php echo $check_type->check_type_id; ?>'><?php echo $check_type->check_type_desc; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Check Date :</label><br />
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" name="check_date" id="check_date" class="date-picker form-control" data-error-msg="Check date is required!" >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Check # :</label><br />
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-list-alt"></i>
                                            </span>
                                            <input type="text" name="check_no" id="check_no" maxlength="15" class="form-control" data-error-msg="Check number is required!">
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <b class="required"> * </b>  <label>Amount  :</label><br />
                                        <input class="form-control text-center numeric" id="cash_amount" type="text" maxlength="12" value="0.00" name="amount" required data-error-msg="Amount is Required!">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div>
                <span><strong><i class="fa fa-bars"></i> Journal Entries</strong></span>
                <hr />

                <div style="width: 100%;">
                    <table id="tbl_entries" class="table-striped table">
                        <thead class="">
                        <tr>
                            <th style="width: 30%;">Account</th>
                            <th style="width: 15%;">Memo</th>
                            <th style="width: 15%;text-align: right;">Dr</th>
                            <th style="width: 15%;text-align: right;">Cr</th>
                            <th style="width: 15%;text-align: left;">Department</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>

                                <select name="accounts[]" class="selectpicker show-tick form-control selectpicker_accounts" data-live-search="true" >
                                    <?php $i=0; foreach($accounts as $account){ ?>
                                        <option value='<?php echo $account->account_id; ?>' <?php echo ($i==0?'':''); ?>><?php echo $account->account_title; ?></option>
                                        <?php $i++; } ?>
                                </select>
                            </td>
                            <td><input type="text" name="memo[]" class="form-control"></td>
                            <td><input type="text" name="dr_amount[]" class="form-control numeric"></td>
                            <td><input type="text" name="cr_amount[]" class="form-control numeric"></td>
                            <td>       
                                <select  name="department_id_line[]" class="selectpicker show-tick form-control dept" data-live-search="true" >
                                    <option value="0">[ None ]</option>
                                    <?php foreach($departments as $department){ ?>
                                        <option value='<?php echo $department->department_id; ?>'><?php echo $department->department_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default add_account"><i class="fa fa-plus-circle" style="color: green;"></i></button>
                                <button type="button" class="btn btn-default remove_account"><i class="fa fa-times-circle" style="color: red;"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <select name="accounts[]" class="selectpicker show-tick form-control selectpicker_accounts" data-live-search="true">
                                    <?php $i=0; foreach($accounts as $account){ ?>
                                        <option value='<?php echo $account->account_id; ?>' <?php echo ($i==0?'':''); ?> > <?php echo $account->account_title; ?> </option>
                                        <?php $i++; } ?>
                                </select>
                            </td>
                            <td><input type="text" name="memo[]" class="form-control"></td>
                            <td><input type="text" name="dr_amount[]" class="form-control numeric"></td>
                            <td><input type="text" name="cr_amount[]" class="form-control numeric"></td>
                            <td>       
                                <select  name="department_id_line[]" class="selectpicker show-tick form-control dept" data-live-search="true" >
                                    <option value="0">[ None ]</option>
                                    <?php foreach($departments as $department){ ?>
                                        <option value='<?php echo $department->department_id; ?>'><?php echo $department->department_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default add_account"><i class="fa fa-plus-circle" style="color: green;"></i></button>
                                <button type="button" class="btn btn-default remove_account"><i class="fa fa-times-circle" style="color: red;"></i></button>
                            </td>
                        </tr>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="2" align="right"><strong>Total</strong></td>
                            <td align="right"><strong>0.00</strong></td>
                            <td align="right"><strong>0.00</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <label>Remarks :</label><br />
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
            </div>
        </form>
        <div id="div_check">
        </div>
        <div id="div_no_check">
        </div>

        <br />
        <div class="row">
            <div class="col-sm-12">
                <button id="btn_save" class="btn-primary btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span>  Save and Post</button>
                <button id="btn_cancel" class="btn-default btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"">Cancel</button>
            </div>
        </div>
    </div>
    </div>


    <table id="table_hidden" class="hidden">
        <tr>
            <td width="30%">
                <select name="accounts[]" class="selectpicker show-tick form-control selectpicker_accounts" data-live-search="true" >
                    <?php $i=0; foreach($accounts as $account){ ?>
                        <option value='<?php echo $account->account_id; ?>' <?php echo ($i==0?'':''); ?>><?php echo $account->account_title; ?></option>
                        <?php $i++; } ?>
                </select>
            </td>
            <td><input type="text" name="memo[]" class="form-control"></td>
            <td><input type="text" name="dr_amount[]" class="form-control numeric"></td>
            <td><input type="text" name="cr_amount[]" class="form-control numeric"></td>
            <td>       
                <select  name="department_id_line[]" class="selectpicker show-tick form-control dept" data-live-search="true" >
                    <option value="0">[ None ]</option>
                    <?php foreach($departments as $department){ ?>
                        <option value='<?php echo $department->department_id; ?>'><?php echo $department->department_name; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-default add_account"><i class="fa fa-plus-circle" style="color: green;"></i></button>
                <button type="button" class="btn btn-default remove_account"><i class="fa fa-times-circle" style="color: red;"></i></button>
            </td>
        </tr>
    </table>


</div>
</div>





</div>




</div>
</div>
</div>
</div> <!-- .container-fluid -->
</div> <!-- #page-content -->
</div>

<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2018 - JDEV OFFICE SOLUTION INC</h6></li>
        </ul>
        <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
    </div>
</footer>

</div>
</div>
</div>


<div id="modal_recurring" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: white;"><i class="fa fa-folder-open-o"></i>  Browse Recurring Templates</h4>
            </div>
            <div class="modal-body">
                <table id="tbl_recurring" class="table table-striped" width="100%">
                    <thead>
                        <th>Template Code</th>
                        <th>Template Description</th>
                        <th>Payee / Particular</th>
                        <th><center>Action</center></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btn_cancel_browsing" class="btn btn-default">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div id="modal_confirmation" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color: white;"><span id="modal_mode"> </span>Confirm Deletion</h4>

            </div>

            <div class="modal-body">
                <p id="modal-body-message">Are you sure you want to delete this voucher?</p>
            </div>

            <div class="modal-footer">
                <button id="btn_yes" type="button" class="btn btn-danger" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Yes</button>
                <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">No</button>
            </div>
        </div>
    </div>
</div>
<div id="modal_confirmation_verified" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color: white;"><span id="modal_mode"> </span>Confirm Verification</h4>

            </div>

            <div class="modal-body">
                <p id="modal-body-message">Are you sure you want to mark this as verified?</p>
            </div>

            <div class="modal-footer">
                <button id="btn_yes_verified" type="button" class="btn btn-danger" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Yes</button>
                <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">No</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_check_layout" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#2ecc71;">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:#ecf0f1 !important;"><span id="modal_mode"> </span>Select Check Layout</h4>

            </div>

            <div class="modal-body" style="padding-right: 20px;">

                <div class="row">
                        <div class="container-fluid">
                            <div class="col-xs-12">
                                <select name="check_layout" class="form-control" id="cbo_layouts">
                                    <?php foreach($layouts as $layout){ ?>
                                        <option value="<?php echo $layout->check_layout_id; ?>"><?php echo $layout->check_layout; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                </div>


            </div>

            <div class="modal-footer">
                <button id="btn_preview_check" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> Preview Check</button>
                <button id="btn_close_check" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
            </div>
        </div><!---content-->
    </div>
</div><!---modal-->




<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>


<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/ellipsis.js"></script>
<!-- Select2-->
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!---<script src="assets/plugins/dropdown-enhance/dist/js/bootstrap-select.min.js"></script>-->



<!-- Date range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>



<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>



<script>
$(document).ready(function(){
    var _txnMode; var _cboSuppliers; var _cboMethods; var _selectRowObj; var _selectedID; var _txnMode, _cboBranches, _cboPaymentMethod, _cboCheckTypes;
     var cbo_refType; var _cboLayouts; var dtRecurring; var _attribute; var _TableFilter; var _selectedDepartment = 0;


    var oTBJournal={
        "dr" : "td:eq(2)",
        "cr" : "td:eq(3)"
    };

    var oTFSummary={
        "dr" : "td:eq(1)",
        "cr" : "td:eq(2)"
    };


    var initializeControls=function(){
        _TableFilter=$('#cbo_table_filter').select2({
            placeholder: "Please select Filter.",
            minimumResultsForSearch: -1
        });

        dt=$('#tbl_temp_vouchers_list').DataTable({
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "order": [[ 8, "desc" ]],
            oLanguage: {
                    sProcessing: '<center><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></center>'
            },
            processing : true,
            "ajax" : {
                "url" : "Cash_vouchers/transaction/list",
                "bDestroy": true,            
                "data": function ( d ) {
                        return $.extend( {}, d, {
                            "tsd":$('#txt_start_date_cdj').val(),
                            "ted":$('#txt_end_date_cdj').val(),
                            "fil":$('#cbo_table_filter').val()

                        });
                    }
            }, 
            "columns": [
                {
                    "targets": [0],
                    "class":          "details-control",
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ""
                },
                { targets:[1],data: "txn_no" },
                { targets:[2],data: "ref_type" },
                { targets:[3],data: "particular" },
                { targets:[4],data: "payment_method" },
                { targets:[5],data: "date_txn" },
                { targets:[6],data: "posted_by" },
                {sClass: "centered",
                    targets:[7],data:null,
                    render: function (data, type, full, meta){
                        var btn_verified='<button class="btn btn-warning btn-sm" name="mark_verified" title="Mark as Verified">Verify</button>';
                        var btn_edit='<button class="btn btn-primary btn-sm" name="edit_info" title="Edit"><i class="fa fa-pencil"></i> </button>';
                        var btn_cancel='<button class="btn btn-red btn-sm" name="delete_info" title="Delete Temporary Voucher"><i class="fa fa-trash"></i> </button>';
                        var btn_for_approval='<button class="btn btn-info btn-sm" disabled>For Approval</button>';
                        var btn_approved='<button class="btn btn-light btn-sm" disabled>Approved</button>';
                        var btn_disapproved='<button class="btn btn-light btn-sm" disabled>Disapproved</button>';

                        if(data.verified_by_user == "0"  && data.verified_by_user == "0" && data.approved_by_user == "0") {
                            return btn_verified+"&nbsp;"+btn_edit+"&nbsp;"+btn_cancel+'';
                        }else if(data.verified_by_user > "0" && data.approved_by_user == "0" && data.cancelled_by_user == "0"){
                            return btn_for_approval ;
                        }else if (data.verified_by_user > "0" && data.approved_by_user > "0" && data.cancelled_by_user == "0"){
                            return btn_approved ;
                        }else if (data.verified_by_user > "0" && data.approved_by_user == "0" && data.cancelled_by_user > "0"){
                            return btn_disapproved ;
                        }
                        	
                    }
                },
                { targets:[8],data: "cv_id",visible:false },


            ]
        });

        reInitializeNumeric();
        reInitializeDropDownAccounts($('#tbl_entries'),false);

        $('.date-picker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true

        });

        _cboCheckTypes=$('#cbo_check_type').select2({
            placeholder: "Please Select Check Type",
            allowClear:false
        });

        _cboSuppliers=$('#cbo_suppliers').select2({
            placeholder: "Please select supplier.",
            allowClear: true
        });
        _cboSuppliers.select2('val',null);

        _cboPaymentMethod = $('#cbo_pay_type').select2({
            placeholder: "Please select Payment Type.",
            allowClear: true
        });
        _cboPaymentMethod.select2('val',null);

         _cboBranches=$('#cbo_branch').select2({
            placeholder: "Please select department.",
            allowClear: true
        });
        _cboBranches.select2('val',null);

        _cboLayouts=$('#cbo_layouts').select2({
            placeholder: "Please select check layout.",
            allowClear: true
        });
        _cboLayouts.select2('val',null);
        _cboCheckTypes.select2('val',null);

        cbo_refType=$('#cbo_refType').select2({
            placeholder: "Please select reference type.",
            allowClear: true
        });

    }();



    var bindEventHandlers=function(){

        $("#txt_start_date_cdj").on("change", function () {        
            $('#tbl_temp_vouchers_list').DataTable().ajax.reload()
        });

        $("#txt_end_date_cdj").on("change", function () {        
            $('#tbl_temp_vouchers_list').DataTable().ajax.reload()
        });
        $("#searchbox_cdj").keyup(function(){         
            dt
                .search(this.value)
                .draw();
        });

        var detailRows = [];

        $('#tbl_temp_vouchers_list tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );

            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                //console.log(row.data());
                var d=row.data();

                $.ajax({
                    "dataType":"html",
                    "type":"POST",
                    "url":"Templates/layout/journal-cdj-voucher?id="+ d.cv_id,
                    "beforeSend" : function(){
                        row.child( '<center><br /><img src="assets/img/loader/ajax-loader-lg.gif" /><br /><br /></center>' ).show();
                    }
                }).done(function(response){
                    row.child( response,'no-padding' ).show();
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                });
            }
        } );

        $('#btn_preview_check').click(function(){
            if ($('#cbo_layouts').select2('val') != null || $('#cbo_layouts').select2('val') != undefined)
                window.open('Templates/layout/print-check?id='+$('#cbo_layouts').val()+'&jid='+_selectedID);
            else
                showNotification({ title: 'Error', msg: 'Please select check layout!', stat: 'error' });
        });

        $('#is_2307').click(function(){
            if ($(this).is(":checked") == false){
                $('#atc_2307').val("");
                $('#remarks_2307').val("");
            }
        });

        $('#atc_2307').on('keyup',function(){
            if($(this).val() != null || ""){
                $('#is_2307').prop('checked', true);
            }
        });

        $('#remarks_2307').on('keyup',function(){
            if($(this).val() != null || ""){
                $('#is_2307').prop('checked', true);
            }
        });


        $('#btn_new').click(function(){
            _txnMode="new";
            $('#div_check').show();
            $('#div_no_check').hide();
            var _currentDate=<?php echo json_encode(date("m/d/Y")); ?>;

            reInitializeDropDownAccounts($('#tbl_entries'),true);
            clearFields($('#frm_journal'));

            $('#cbo_branch').select2('val',null);
            $('#cbo_pay_type').select2('val',1);
            $('#cbo_suppliers').select2('val',null);
            $('#cbo_refType').select2('val',"CV");
            $('#is_2307').prop('checked', false);
            //set defaults
            _cboPaymentMethod.select2('val',1);//set cash as default
            _cboCheckTypes.select2('val',0);//set cash as default
            $('input[name="date_txn"]').val(_currentDate);
            showList(false);

        });


        //add account button on table
        $('#tbl_entries').on('click','button.add_account',function(){

            var row=$('#table_hidden').find('tr');
            row.clone().insertAfter('#tbl_entries > tbody > tr:last');

            reInitializeNumeric();
            reInitializeDropDownAccounts($('#tbl_entries'),false);
            $('#tbl_entries > tbody > tr:last select.dept').each(function(){ $(this).select2('val',_selectedDepartment)});

        });

        var _oTblEntries=$('#tbl_entries > tbody');
        _oTblEntries.on('keyup','input.numeric',function(){
            var _oRow=$(this).closest('tr');

            if(_oTblEntries.find(oTBJournal.dr).index()===$(this).closest('td').index()){ //if true, this is Debit amount
                if(getFloat(_oRow.find(oTBJournal.dr).find('input.numeric').val())>0){
                    _oRow.find(oTBJournal.cr).find('input.numeric').val('0.00');
                }
            }else{

                if(getFloat(_oRow.find(oTBJournal.cr).find('input.numeric').val())>0) {
                    _oRow.find(oTBJournal.dr).find('input.numeric').val('0.00');
                }
            }


            reComputeTotals($('#tbl_entries'));
        });


        $('#tbl_temp_vouchers_list').on('click','button[name="print_check"]',function(){

            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.cv_id;

            $('#modal_check_layout').modal('show');
        });


        $('#tbl_temp_vouchers_list').on('click','button[name="delete_info"]',function(){
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.cv_id;
            $('#modal_confirmation').modal('show');
        });

        $('#tbl_temp_vouchers_list').on('click','button[name="mark_verified"]',function(){
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.cv_id;
            $('#modal_confirmation_verified').modal('show');
        });

        $('#btn_yes').click(function(){
            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Cash_vouchers/transaction/delete",
                "data":{cv_id : _selectedID},
                "success": function(response){
                    showNotification(response);
                    if(response.stat=="success"){
                        dt.row(_selectRowObj).remove().draw();
                    }

                }
            });
        });

        $('#btn_yes_verified').click(function(){
            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Cash_vouchers/transaction/verify",
                "data":{cv_id : _selectedID},
                "success": function(response){
                    showNotification(response);
                    if(response.stat=="success"){
                        dt.row(_selectRowObj).data(response.row_updated[0]).draw();
                    }

                }
            });
        });

        $('#tbl_temp_vouchers_list').on('click','button[name="edit_info"]',function(){
            _txnMode="edit";

            $('#div_check').hide();
            $('#div_no_check').show();

            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.cv_id;

            $('input,textarea, select').each(function(){
                var _elem=$(this);
                $.each(data,function(name,value){
                    if(_elem.attr('name')==name){
                        _elem.val(value);
                    }
                });
            });
            $('#cbo_pay_type').select2('val',data.payment_method_id);
            $('#cbo_suppliers').select2('val',data.supplier_id);
            $('#cbo_branch').select2('val',data.department_id);
            $('#cbo_refType').select2('val',data.ref_type);
            $('#cbo_check_type').select2('val',data.check_type_id);

            if(data.check_date == '00/00/0000'){
                $('input[name="check_date"]').val('');
            }
            if(data.is_2307 == true){
                $('#is_2307').prop('checked', true);
            }else{
                $('#is_2307').prop('checked', false);
            }
            $.ajax({
                url: 'Cash_vouchers/transaction/get-entries?id=' + data.cv_id,
                type: "GET",
                cache: false,
                dataType: 'html',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#tbl_entries > tbody').html('<tr><td align="center" colspan="4"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
                }
            }).done(function(response){
                $('#tbl_entries > tbody').html(response);
                reInitializeNumeric();
                reInitializeDropDownAccounts($('#tbl_entries'),false); //do not clear dropdown accounts
                reComputeTotals($('#tbl_entries'));
            });

            showList(false);

        });


        $('#tbl_entries').on('click','button.remove_account',function(){
            var oRow=$('#tbl_entries > tbody tr');
            if(oRow.length>1){
                $(this).closest('tr').remove();
            }else{
                showNotification({"title":"Error!","stat":"error","msg":"Sorry, you cannot remove all rows."});
            }
            reComputeTotals($('#tbl_entries'));
        });


        $('#btn_save').click(function(){
            var btn=$(this);
            var f=$('#frm_journal');
            if(validateRequiredFields(f)){
                if(_txnMode=="new"){
                    createJournal().done(function(response){
                        showNotification(response);
                        if(response.stat=="success"){
                            dt.row.add(response.row_added[0]).draw();
                            clearFields(f);
                            showList(true);
                        }
                    }).always(function(){
                        showSpinningProgress(btn);
                    });
                }else{
                    updateJournal().done(function(response){
                        showNotification(response);
                        if(response.stat=="success"){
                            dt.row(_selectRowObj).data(response.row_updated[0]).draw();
                            clearFields(f);
                            showList(true);
                        }
                    }).always(function(){
                        showSpinningProgress(btn);
                    });
                }
            }
        });

        $('#btn_cancel').click(function(){
            showList(true);
        });

        $("#cbo_pay_type").change(function(){
            if($(this).val() == 2) {
                $('#check_date').prop('required',true);
                $('#check_no').prop('required',true);
            } else {
                $('#check_date').prop('required',false);
                $('#check_no').prop('required',false);
            }
        });

        _TableFilter.on("select2:select", function (e) {
            $('#tbl_temp_vouchers_list').DataTable().ajax.reload()
        });

        _cboBranches.on("select2:select", function (e) {
            _selectedDepartment = $(this).select2('val'); 
            $('#tbl_entries select.dept').each(function(){ $(this).select2('val',_selectedDepartment)}); 
        });
    }();





    //*********************************************************************8
    //              user defines

    var createJournal=function(){
        var _data=$('#frm_journal').serializeArray();
        if($('#is_2307').is(':checked')==true){
        _data.push({name : "is_2307" ,value : 1}); }else{ 
        _data.push({name : "is_2307" ,value : 0}); }

        console.log(_data);
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Cash_vouchers/transaction/create",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))

        });
    };


    var updateJournal=function(){
        var _data=$('#frm_journal').serializeArray();
        if($('#is_2307').is(':checked')==true){
        _data.push({name : "is_2307" ,value : 1}); }else{ 
        _data.push({name : "is_2307" ,value : 0}); }
        console.log(_data)
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Cash_vouchers/transaction/update?id="+_selectedID,
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };

    var showList=function(b){
        if(b){
            $('#div_payable_list').show();
            $('#div_payable_fields').hide();
        }else{
            $('#div_payable_list').hide();
            $('#div_payable_fields').show();
        }
    };

    var clearFields=function(f){
        $('input,textarea',f).val('');
        $('#tbl_entries > tbody tr').slice(2).remove();
        $('#tbl_entries > tfoot tr').find(oTFSummary.dr).html('<b>0.00</b>');
        $('#tbl_entries > tfoot tr').find(oTFSummary.cr).html('<b>0.00</b>');
    };

    //initialize numeric text
    function reInitializeNumeric(){
        $('.numeric').autoNumeric('init');
    };

    function reInitializeDropDownAccounts(tbl,bClear=false){
        var obj=tbl.find('select.selectpicker');
        var objdept=tbl.find('select.dept');

        obj.select2({
            placeholder: "Please Select an Account.",
            allowClear: false
        });


        if(bClear){
            $.each(obj,function(){
                $(this).select2('val',null);
            });

            $.each(objdept,function(){
                $(this).select2('val',0);
            });
        }

    };


    function reInitializeSpecificDropDown(elem){
        elem.select2({
            placeholder: "Please select item.",
            allowClear: false
        });
    };

    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode === 8 || event.keyCode === 46
            || event.keyCode === 37 || event.keyCode === 39) {
            return true;
        }
        else if ( key < 48 || key > 57 ) {
            return false;
        }
        else return true;
    };

    var showSpinningProgress=function(e){
        $(e).toggleClass('disabled');
        $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
    };


    var reComputeTotals=function(tbl){
        var oRows=tbl.find('tbody tr');
        var _DR_amount=0.00; var _CR_amount=0.00;

        $.each(oRows,function(i,value){
            _DR_amount+=getFloat($(this).find(oTBJournal.dr).find('input.numeric').val());
            _CR_amount+=getFloat($(this).find(oTBJournal.cr).find('input.numeric').val());


        });



        tbl.find('tfoot tr').find(oTFSummary.dr).html('<b>'+accounting.formatNumber(_DR_amount,2)+'</b>');
        tbl.find('tfoot tr').find(oTFSummary.cr).html('<b>'+accounting.formatNumber(_CR_amount,2)+'</b>');

    };

    var getFloat=function(f){
        return parseFloat(accounting.unformat(f));
    };


    var showNotification=function(obj){
        PNotify.removeAll(); //remove all notifications
        new PNotify({
            title:  obj.title,
            text:  obj.msg,
            type:  obj.stat
        });
    };


    var validateRequiredFields=function(f){
        var stat=true;

        $('div.form-group').removeClass('has-error');
        $('input[required],textarea[required],select[required]',f).each(function(){

            if($(this).is('select')){
                if($(this).select2('val')==0||$(this).select2('val')==null){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }else{
                if($(this).val()==""){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }



        });


        if(!isBalance()){
            showNotification({title:"Error!",stat:"error",msg:'Please make sure Debit and Credit is balance.'});
            stat=false;
        }

        return stat;
    };


    var isBalance=function(opTable=null){
        var oRow; var dr; var cr;

        if(opTable==null){
            oRow=$('#tbl_entries > tfoot tr');
        }else{
            oRow=$(opTable+' > tfoot tr');
        }

        dr=getFloat(oRow.find(oTFSummary.dr).text());
        cr=getFloat(oRow.find(oTFSummary.cr).text());

        return (dr==cr);
    };


});


</script>

</body>

</html>
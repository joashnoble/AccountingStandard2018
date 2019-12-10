<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>JCORE - <?php echo $title; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="">

    <?php echo $_def_css_files; ?>

    <link rel="stylesheet" href="assets/plugins/spinner/dist/ladda-themeless.min.css">
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <style>
        .select2-container{
            min-width: 100%;
        }


        .select2-dropdown{
            z-index: 9999999999;
        }

        .datepicker-dropdown{
            z-index: 9999999999;
        }

        .dropdown-menu{
            z-index: 9999999999;
        }

        .glyphicon.spinning {
            animation: spin 1s infinite linear;
            -webkit-animation: spin2 1s infinite linear;
        }

        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }

    </style>

</head>

<body class="animated-content">

<?php echo $_top_navigation; ?>

<div id="wrapper">
    <div id="layout-static">

        <?php echo $_side_bar_navigation;?>

        <div class="static-content-wrapper white-bg">
            <div class="static-content"  >
                <div class="page-content"><!-- #page-content -->
                    <ol class="breadcrumb" style="margin:0%;">
                        <li><a href="dashboard">Dashboard</a></li>
                        <li><a href="Comparative_Balance_sheet">Comparative Balance Sheet</a></li>
                    </ol>
                    <div class="container-fluid">
                        <div data-widget-group="group1">

                            <div id="modal_bsheet" class="modal fade" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" style="color: white;">Comparative Balance Sheet</h4>
                                        </div>
                                        <div class="modal-body">


                                            <div class="row">
                                                <div class="col-sm-12">
                                                 <b class="required">*</b> Choose <b>Admin</b> to use all branches/departments .<br>
                                                 <b class="required">*</b> Previous year of the selected Year will be the default for comparative report .<br>
                                                    <br/>
                                                    Branch : <br />
                                                    <select name="department" id="cbo_departments" data-error-msg="Branch is required." required>
                                                        <?php foreach($departments as $department){ ?>
                                                            <option value="<?php echo $department->department_id; ?>" <?php echo ($department->department_id==1?"selected":""); ?>><?php echo $department->department_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <br/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br/>
                                                <div class="col-sm-4">
                                                    Year : <br />
                                                    <select name="year" id="cbo_year" data-error-msg="Year is required." required>
                                                        <?php $minyear=1990; $maxyear=2500;
                                                            while($minyear!=$maxyear){?>
                                                                <option value="<?php echo $minyear; ?>">
                                                                    <?php echo $minyear; ?>
                                                                </option>';
                                                        <?php $minyear++;
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    Start Date : <br />
                                                    <div class="input-group" style="z-index: 99999">
                                                            <input type="text" name="dt_start_date" id="dt_start_date" class="form-control" placeholder="Start Date" data-error-msg="Start date is required!" readonly>
                                                        <span class="input-group-addon">
                                                             <i class="fa fa-calendar"></i>
                                                        </span>

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    End Date : <br />
                                                    <div class="input-group" style="z-index: 99999">
                                                        <span id="end_date_panel">
                                                            <input type="text" name="dt_end_date" id="dt_end_date" class="form-control" value="<?php echo date("m/d/Y"); ?>"  placeholder="End Date" data-error-msg="End date is required!" required>
                                                        </span>
                                                        <span class="input-group-addon">
                                                             <i class="fa fa-calendar"></i>
                                                        </span>

                                                    </div>
                                                </div>
                                            </div><br />

                                            <div class="row">

                                                <div class="col-sm-4">
                                                    Compared Year : <br />
                                                    <select name="cbo_end_year" id="cbo_end_year" data-error-msg="Year is required." required>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    Compared Start Date : <br />
                                                    <div class="input-group" style="z-index: 99999">
                                                            <input type="text" name="co_dt_start_date" id="co_dt_start_date" class="form-control" placeholder="Start Date" data-error-msg="Start date is required!" required readonly>
                                                        <span class="input-group-addon">
                                                             <i class="fa fa-calendar"></i>
                                                        </span>

                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    Compared End Date : <br />
                                                    <div class="input-group" style="z-index: 99999">
                                                            <input type="text" name="co_dt_end_date" id="co_dt_end_date" class="form-control" placeholder="End Date" data-error-msg="End date is required!" required readonly>
                                                        <span class="input-group-addon">
                                                             <i class="fa fa-calendar"></i>
                                                        </span>

                                                    </div>
                                                </div>
                                            </div><br />

                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-xs-12">
                                                <a id="btn_print" href="#" target="_blank" class="btn btn-green" style="text-transform:none;font-family: tahoma;" title=" Print" ><i class="fa fa-print"></i> Print </a>
                                                <button id="btn_export" class="btn btn-primary" title="All departments"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
<!--                                                 <a href="Templates/layout/income-statement?type=&type=pdf" class="btn btn-primary" style="text-transform:none;font-family: tahoma;" ><i class="fa fa-file-pdf-o"></i> Download as PDF </a> -->
                                                <!-- <button class="btn btn-primary" style="margin-right: 5px; margin-top: 10px; margin-bottom: 10px;" id="btn_email" style="text-transform: none; font-family: Tahoma, Georgia, Serif; " data-toggle="modal" data-target="#salesInvoice" data-placement="left" title="Send to Email (All departments)" >
                                                <i class="fa fa-share"></i> Email </button> -->
                                                <button class="btn btn-red" data-dismiss="modal" style="text-transform: capitalize;">Close</button>
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
                        <li><h6 style="margin: 0;">&copy; 2018 - JDEV OFFICE SOLUTION</h6></li>
                    </ul>
                    <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
                </div>
            </footer>
        </div>
    </div>
</div>


<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>


<!-- Select2 -->
<script src="assets/plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var _cboDepartments; var _cboYears; var _comparedYear;

        var year = new Date().getFullYear();

        var _modal_filter = $('#modal_bsheet'),
            _modal_balance = $('#modal_balance'),
            _date_from = $('#date_from'),
            _date_to = $('#date_to'),
            _datepicker = $('.date-picker'),
            _btnPrint = $('#btn_print');

        _cboDepartments=$('#cbo_departments').select2({
            placeholder: "Please select department.",
            allowClear: false
        }); 

        _cboYears=$('#cbo_year').select2({
            placeholder: "Please select year.",
            allowClear: false
        });

        _cboYears.select2('val',year);

        _comparedYear=$('#cbo_end_year').select2({
            placeholder: "Please select year.",
            allowClear: false
        });


        var validate_datepicker = function(){

            var selected_year = _cboYears.select2('val');

            $('#end_date_panel').html("");

            $('#end_date_panel').html('<input type="text" name="dt_end_date" id="dt_end_date" class="date-picker form-control" value="<?php echo date("m/d"); ?>/'+selected_year+'"  placeholder="End Date" data-error-msg="End date is required!" required>');

            var startYear = new Date(selected_year, 0, 1);
            var endYear = new Date(selected_year, 11, 31);

            $('#dt_start_date').val('01/01/'+selected_year);

            var compared_year = selected_year - 1;

            $('#cbo_end_year').find('option').remove();

            for(i = 1990; i < 2500;i++){

                if (i <= compared_year){
                    $('#cbo_end_year').append('<option value='+i+'>'+i+'</option>');
                }
            }

            _comparedYear.select2('val',compared_year);

            $('#co_dt_start_date').val('01/01/'+compared_year);

            var end_date = $('#dt_end_date').val();
            var date = new Date(end_date);

            $('#co_dt_end_date').val((date.getMonth() +1 )+'/'+(date.getDate())+'/'+compared_year);

            $('.date-picker').datepicker({
                todayBtn: "linked",
                startDate: startYear,
                endDate: endYear,
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        };

        validate_datepicker();

        $('#btn_export').click(function(){
            window.open("Comparative_Balance_sheet/transaction/export-excel?year="+$('#cbo_year').val()+"&date="+$('#dt_end_date').val()+"&year_end="+$('#cbo_end_year').val()+"&end_date="+$('#co_dt_end_date').val()+"&depid="+_cboDepartments.select2('val'));
        });

        $('#btn_email').on('click', function() {
        showNotification({title:"Sending!",stat:"info",msg:"Please wait for a few seconds."});

        var btn=$(this);
    
        $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Comparative_Income_statement/transaction/email-excel?start="+$('#dt_start_date').val()+'&end='+$('#dt_end_date').val(),
            "beforeSend": showSpinningProgress(btn)
        }).done(function(response){
            showNotification(response);
            showSpinningProgress(btn);

        });
        });

    _cboYears.on('change', function(){
        validate_datepicker();
    });

    _comparedYear.on('change', function(){

        $('#co_dt_start_date').val('01/01/'+$(this).val());

        var end_date = $('#dt_end_date').val();
        var date = new Date(end_date);

        $('#co_dt_end_date').val((date.getMonth() +1 )+'/'+(date.getDate())+'/'+$(this).val());

    });    

    $('#dt_end_date').on('change', function(){
        var date = new Date($(this).val());

        var cbo_end_year = $('#cbo_end_year').val();

        $('#co_dt_end_date').val((date.getMonth() +1 )+'/'+(date.getDate())+'/'+cbo_end_year);
    });

    var showSpinningProgress=function(e){
        $(e).toggleClass('disabled');
        $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
    };


    var showNotification=function(obj){
        PNotify.removeAll(); //remove all notifications
        new PNotify({
            title:  obj.title,
            text:  obj.msg,
            type:  obj.stat
        });
    };
    
        $('#btn_print').click(function(){
            window.open("Comparative_Balance_sheet/transaction/bs?year="+$('#cbo_year').val()+"&date="+$('#dt_end_date').val()+"&year_end="+$('#cbo_end_year').val()+"&end_date="+$('#co_dt_end_date').val()+"&depid="+_cboDepartments.select2('val'));
        });



        // _btnPrint.on('click', function(){
        //     $('#modal_balance').modal('show');
        // });

        _modal_filter.modal('show');
    })();
</script>
<script src="assets/plugins/spinner/dist/spin.min.js"></script>
<script src="assets/plugins/spinner/dist/ladda.min.js"></script>
<script src="assets/plugins/select2/select2.full.min.js"></script>
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Fixed Asset Management</title>
    <style type="text/css">
        body {
            font-family: 'Calibri',sans-serif;
            font-size: 12px;
        }
        
       @page {
          size: A4 landscape;
        }
        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .align-center {
            text-align: center;
        }
            table{
        border:none!important;
    }
    table-td.left{
        border-left: 1px solid gray!important;
    }
    table-td.right{
        border-left: 1px solid gray!important;
    }
    #tbl_supplier thead tr th {
        border-bottom: 2px solid gray;text-align: left;height: 30px;padding: 6px;
    }
</style>
</head>
<body>
	<table width="100%" cellspacing="5" cellspacing="0">
        <tr>
            <td width="10%"  class="bottom"><img src="<?php echo base_url($company_info->logo_path); ?>" style="height: 90px; width: 120px; text-align: left;"></td>
            <td width="90%"  class="bottom" >
                <h1 class="report-header" style="margin-bottom: 0"><strong><?php echo $company_info->company_name; ?></strong></h1>
                <span><?php echo $company_info->company_address; ?></span><br>
                <span><?php echo $company_info->landline.'/'.$company_info->mobile_no; ?></span><br>
                <span><?php echo $company_info->email_address; ?></span><br>

            </td>
        </tr>
    </table>
    <br>
    <h2>Fixed Asset Management</h2>
	<table width="100%" style="border-collapse: collapse;border-spacing: 0;font-family: tahoma;font-size: 11" id="tbl_supplier">
		<thead>
			<tr>
				<th>Asset Code</th>
				<th>Asset Description</th>
				<th>Present Location</th>
				<th>Present Status</th>
				<th>Date</th>
				<th>Record</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($data as $row){?>
				<tr>
					<td><?php echo $row->asset_code; ?></td>
					<td><?php echo $row->asset_description; ?></td>
					<td><?php echo $row->location_name; ?></td>
					<td><?php echo $row->asset_property_status; ?></td>
					<td><?php echo $row->date_movement; ?></td>
					<td><?php if($row->is_acquired == 1){ echo 'Acquired'; }else{ echo 'Moved'; }?></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<br/>
    Printed Date : <?php echo date('m/d/Y');?>
	Printed By : <?php echo $user; ?><br/>
</body>
</html>


<script type="text/javascript">
    window.print();
</script>

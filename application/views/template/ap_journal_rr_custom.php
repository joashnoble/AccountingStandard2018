<style type="text/css">
    span{
        position: absolute;
        z-index: 200;
        font-size: 11pt;
        text-transform: uppercase;
        font-family: Arial;
        font-weight: bold;
    }

    .panel{
        position: absolute;    
        z-index: 200;  
        width: auto;
        height: auto;   
        top: 180px;      
        left: 30px;    
        font-family: Arial;
        font-weight: bold;
    }

    @page { 
      size: A5-L; 
      margin: default; 
      scale: 100%; 

    }
    
</style>
<!-- <img src="<?php //echo base_url(); ?>/assets/img/rr.jpg" style="top: 0px; left: 0px; width: 100%;position: absolute;z-index: 100;height: 100%;"> -->

<span style="top: 100px; left: 160px;"><?php echo $purchase_info->supplier_name; ?></span>
<span style="top: 100px; left: 565px;"><?php echo $purchase_info->date_delivered; ?></span>

<div class="panel">

        <table width="100%" style="font-size: 12pt; font-family: Arial; font-weight: bold;">

            <?php 
            $dr_amount = 0;
            $cr_amount = 0;

            if(count($entries) <= 0){ ?>

            <tr>
                <td colspan="3"></td>
            </tr>

            <?php }else{?>

            <?php foreach($entries as $entry){

            $dr_amount += $entry->dr_amount;
            $cr_amount += $entry->cr_amount;
            ?>
                <?php if($entry->dr_amount > 0){ ?>
                    <tr>
                        <td style="width: 370px;max-width: 370px;">
                            <?php echo $entry->account_title; ?>
                        </td>
                        <td style="width: 165px;max-width: 165px;" align="center">
                            <?php echo number_format($entry->dr_amount,2); ?>
                        </td>
                        <td style="width: 165px;max-width: 165px;" align="center">&nbsp;</td>
                    </tr>
                <?php } ?>

                <?php if($entry->cr_amount > 0){ ?>
                    <tr>
                        <td style="width: 370px;max-width: 370px;padding-left: 150px;">
                            <?php echo $entry->account_title; ?>
                        </td>
                        <td style="width: 165px;max-width: 165px;" align="center">&nbsp;</td>
                        <td style="width: 165px;max-width: 165px;" align="center">
                            <?php echo number_format($entry->cr_amount,2); ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php }?>

            <?php } ?>
            <?php if($purchase_info->remarks != null || ""){ ?>
                <tr>
                        <td colspan="3" align="center">
                            <?php echo $purchase_info->remarks; ?>
                        </td>
                </tr>
            <?php }?>
        </table>


</div>


<span style="top: 380px; left: 500px;">
    <?php echo number_format($dr_amount,2) ?>
</span>
<span style="top: 380px; left: 650px;">
    <?php echo number_format($cr_amount,2) ?>
</span>
<script type="text/javascript">
    window.print();
</script>

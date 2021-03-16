<style type="text/css">
/*    span, table{
    font-size: 12pt;
    font-family: Calibri Light!important;
    font-weight: 100!important;
    /*color: #696969!important;*/
    /*color: black!important;*/
}
*/
.border-left{
    border-left: 1px solid black!important;
}
.border-right{
    border-right: 1px solid black!important;
}  
.border-bottom{
    border-bottom: 1px solid black!important;
}   
.border-top{
    border-top: 1px solid black!important; 
}     
</style>

<table width="100%" cellspacing="5" cellpadding="0">
    <tr>
        <td width="50%">CUSTOMER : <?php echo $sales_order->customer_name; ?></td>
        <td width="50%" align="right">SO# : <?php echo $sales_order->so_no; ?></td>
    </tr>
    <tr>
        <td>ADDRESS : <?php echo $sales_order->address; ?></td>
        <td align="right">DATE : <?php echo date_format(new DateTime($sales_order->date_order),"m/d/Y"); ?></td>
    </tr>
</table>
<br />
<table width="100%" style="border-collapse: collapse;" cellspacing="5" cellpadding="5">
    <tr>
        <td width="10%" class="border-right border-bottom" align="center">QTY</td>
        <td width="10%" class="border-right border-bottom" align="center">UM</td>
        <td width="40%" class="border-right border-bottom" align="center">DESCRIPTION</td>
        <td width="20%" class="border-right border-bottom" align="right">UNIT PRICE</td>
        <td width="20%" class="border-bottom" align="right">AMOUNT</td>
    </tr>
    <?php 
        $subtotal=0;
        foreach($sales_order_items as $item){ 

        if($is_basyo->basyo_product_id != $item->product_id){
        $subtotal+=$item->so_line_total_price;
    ?>
        <tr>
            <td valign="top" class="border-right"><center><?php echo number_format($item->so_qty,2); ?></center></td>
            <td valign="top" class="border-right"><?php echo $item->unit_code; ?></td>
            <td valign="top" class="border-right"><?php echo $item->product_desc; ?></td>
            <td valign="top" class="border-right" align="right"><?php echo number_format($item->so_price-$item->so_discount,2); ?></td>
            <!-- <td valign="top" align="right"><?php echo number_format($item->so_discount,2); ?></td> -->
            <td valign="top" align="right"><?php echo number_format($item->so_line_total_price,2); ?></td>
        </tr>
    <?php }}?>           
    <tr>        
        <td valign="top"></td>
        <td valign="top"></td>
        <td valign="top"></td>
        <td valign="top" align="right">SUB-TOTAL :</td>
        <td valign="top" align="right" style="font-size: 14pt!important;"><?php echo number_format($subtotal,2); ?></td>        
    </tr>
    <?php foreach($sales_order_items as $item){
        if($is_basyo->basyo_product_id == $item->product_id){ ?>
    <tr>
        <td valign="top"><center><?php echo number_format($item->so_qty,2); ?></center></td>
        <td valign="top"><?php echo $item->unit_code; ?></td>
        <td valign="top"><?php echo $item->product_desc; ?></td>
        <td valign="top" align="right"><?php echo number_format($item->so_price-$item->so_discount,2); ?></td>
        <td valign="top" align="right"><?php echo number_format($item->so_line_total_price,2); ?></td>
    </tr>
    <?php }}?>     
    <tr>
        <td width="80%" colspan="4" align="right">TOTAL AMOUNT DUE :</td>
        <td width="20%" align="right" style="font-size: 15pt!important;"><?php echo number_format($sales_order->total_after_tax,2); ?></td>
    </tr>    
</table>

<table width="100%" style="border-collapse: collapse;" cellspacing="5" cellpadding="5">
    <tr>
        <td colspan="2" style="height: 5px;"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 9pt!important;">
            ENCODED BY : <?php echo $sales_order->encoded_by; ?><br/>
            CHECKED BY : </td>
    </tr>
    <tr>
        <td colspan="2">NOTE : <?php echo $sales_order->remarks; ?></td>
    </tr>        
</table>

<?php

class Purchase_request_items_model extends CORE_Model {
    protected  $table="purchase_request_items";
    protected  $pk_id="pr_item_id";
    protected  $fk_id="purchase_request_id";

    function __construct() {
        parent::__construct();
    } 

    function get_products_with_balance_qty2($purchase_request_id)
    {
        $sql = "SELECT 
                o.*, 
                (po_line_total - (po_line_total * global_percentage)) po_line_total_after_global,
                ((po_line_total - (po_line_total * global_percentage)) / (1 + tax_rate_decimal)) as non_tax_amount,
                ((po_line_total - (po_line_total * global_percentage)) - ((po_line_total - (po_line_total * global_percentage)) / (1 + tax_rate_decimal))) as tax_amount
            FROM
                (SELECT 
                    n.*,
                    ((n.po_price * n.po_qty) - (n.po_discount * n.po_qty)) AS po_line_total,
                    (n.po_discount * n.po_qty) AS po_line_total_discount, /* after line discount*/
                    (n.total_overall_discount / 100) as global_percentage /* we still need to compute global discount*/

                    -- ((n.po_price * n.po_qty) - ((n.po_price * n.po_qty) * (n.po_discount / 100))) AS po_line_total,
                    -- ((n.po_price * n.po_qty)*(n.po_discount/100)) AS po_line_total_discount, /* after line discount*/
                FROM
                    (SELECT 
                    main.*,
                        (main.po_tax_rate / 100) AS tax_rate_decimal,
                        p.product_code,
                        p.product_desc,                        
                        p.purchase_cost,
                        p.parent_unit_id,
                        p.bulk_unit_id,
                        p.child_unit_id,
                        p.child_unit_desc,
                        p.is_bulk,
                        (CASE
                            WHEN p.is_parent = TRUE 
                                THEN p.bulk_unit_id
                            ELSE p.parent_unit_id
                        END) as product_unit_id,
                        (CASE
                            WHEN p.is_parent = TRUE 
                                THEN blkunit.unit_name
                            ELSE chldunit.unit_name
                        END) as product_unit_name,
                        (SELECT unit_name FROM units u WHERE u.unit_id = p.parent_unit_id) as parent_unit_name,
                        (SELECT unit_name FROM units u WHERE u.unit_id = p.child_unit_id) as child_unit_name
                FROM
                    (SELECT 
                        m.purchase_request_id,
                        m.pr_no,
                        MAX(m.total_overall_discount) as total_overall_discount,
                        m.product_id,
                       
                        MAX(m.po_price) AS po_price,
                        MAX(m.po_discount) AS po_discount,
                        MAX(m.po_tax_rate) AS po_tax_rate,
                        (SUM(m.PrQty) - SUM(m.PoQty)) AS po_qty,
                        MAX(m.unit_id) as unit_id,
                        MAX(m.is_parent) as is_parent
                FROM
                    (SELECT 
                    pr.purchase_request_id,
                        pr.pr_no,
                        0 as total_overall_discount,
                        poi.product_id,
                        SUM(poi.po_qty) AS PoQty,
                        0 AS PrQty,
                        0 AS po_price,
                        0 AS po_discount,
                        0 AS po_tax_rate,
                        0 as unit_id,
                        0 as is_parent

                    FROM
                    purchase_order AS po
                    INNER JOIN purchase_order_items AS poi ON po.purchase_order_id = poi.purchase_order_id
                    LEFT JOIN purchase_request pr ON pr.purchase_request_id = po.purchase_request_id
                    WHERE
                    pr.purchase_request_id = $purchase_request_id
                        AND po.is_active = TRUE
                        AND po.is_deleted = FALSE
                    GROUP BY pr.pr_no , poi.product_id 
                    
                    
                    UNION ALL 
                    
                    
                    SELECT 
                    pr.purchase_request_id,
                        pr.pr_no,
                        0 as total_overall_discount,
                        pri.product_id,
                        0 AS PoQty,
                        SUM(pri.po_qty) AS PrQty,
                        pri.po_price,
                        pri.po_discount,
                        pri.po_tax_rate,
                        pri.unit_id,
                        pri.is_parent
                FROM
                    purchase_request AS pr
                INNER JOIN purchase_request_items AS pri ON pri.purchase_request_id = pr.purchase_request_id
                WHERE
                    pr.purchase_request_id = $purchase_request_id
                        AND pr.is_active = TRUE
                        AND pr.is_deleted = FALSE
                GROUP BY pr.pr_no , pri.product_id

                ) AS m



                GROUP BY m.pr_no , m.product_id
                HAVING po_qty > 0) AS main
                LEFT JOIN products AS p ON main.product_id = p.product_id
                LEFT JOIN units as blkunit ON blkunit.unit_id = p.bulk_unit_id
                LEFT JOIN units as chldunit ON chldunit.unit_id = p.parent_unit_id
                
                ) AS n) AS o";

            return $this->db->query($sql)->result();
    }

    function get_products_with_balance_qty($purchase_order_id){
        $sql="SELECT o.*,(o.po_line_total-o.non_tax_amount)as tax_amount FROM

                (SELECT n.*,

                ((n.po_price*n.po_qty)-(n.po_discount*n.po_qty))as po_line_total,
                ((n.po_price*n.po_qty)/(1+tax_rate_decimal))as non_tax_amount,
                (n.po_discount*n.po_qty) as po_line_total_discount


                FROM
                (SELECT main.*,(main.po_tax_rate/100)as tax_rate_decimal,p.product_code,p.product_desc,p.unit_id,u.unit_name FROM

                (SELECT
                m.purchase_order_id,
                m.po_no,m.product_id,
                MAX(m.po_price)as po_price,
                MAX(m.po_discount)as po_discount,
                MAX(m.po_tax_rate)as po_tax_rate,
                (SUM(m.PoQty)-SUM(m.DrQty))as po_qty


                FROM

                (SELECT po.purchase_order_id,po.po_no,poi.product_id,SUM(poi.po_qty) as PoQty,0 as DrQty,
                poi.po_price,poi.po_discount,poi.po_tax_rate FROM purchase_order as po
                INNER JOIN purchase_order_items as poi ON po.purchase_order_id=poi.purchase_order_id
                WHERE po.purchase_order_id=$purchase_order_id AND po.is_active=TRUE AND po.is_deleted=FALSE
                GROUP BY po.po_no,poi.product_id


                UNION ALL

                SELECT po.purchase_order_id,po.po_no,dii.product_id,0 as PoQty,SUM(dii.dr_qty) as DrQty,
                0 as po_price,0 as po_discount,0 as po_tax_rate FROM (delivery_invoice as di
                INNER JOIN purchase_order as po ON di.purchase_order_id=po.purchase_order_id)
                INNER JOIN delivery_invoice_items as dii ON di.dr_invoice_id=dii.dr_invoice_id
                WHERE po.purchase_order_id=$purchase_order_id AND di.is_active=TRUE AND di.is_deleted=FALSE
                GROUP BY po.po_no,dii.product_id)as

                m GROUP BY m.po_no,m.product_id HAVING po_qty>0)as main


                LEFT JOIN products as p ON main.product_id=p.product_id
                LEFT JOIN units as u ON p.unit_id=u.unit_id)as n) as o";

        return $this->db->query($sql)->result();

    }

    function get_list_open_requests(){
       $sql="SELECT 
            o.*
        FROM
            (SELECT 
                n.*
            FROM
                (SELECT 
                main.*, p.product_code, p.product_desc
            FROM
                (SELECT 
                m.purchase_request_id,
                    m.pr_no,
                    m.product_id,
                    m.date_created,
                    MAX(m.date_ordered) AS last_date_ordered,
                    m.PrQty AS PrQtyTotal,
                    (m.PrQty - (SUM(m.PrQty) - SUM(m.PoQty))) AS PrQtyDelivered,
                    (SUM(m.PrQty) - SUM(m.PoQty)) AS PrQtyBalance
            FROM
                (SELECT 
                pr.purchase_request_id,
                    pr.date_created,
                    '' AS date_ordered,
                    pr.pr_no,
                    pri.product_id,
                    SUM(pri.po_qty) AS PrQty,
                    0 AS PoQty,
                    pri.po_price,
                    pri.po_discount,
                    pri.po_tax_rate
            FROM
                purchase_request AS pr
            INNER JOIN purchase_request_items AS pri ON pr.purchase_request_id = pri.purchase_request_id
            WHERE
                pr.is_active = TRUE
                    AND pr.is_deleted = FALSE
                    AND (pr.order_status_id = 1
                    OR pr.order_status_id = 3)
            GROUP BY pr.pr_no , pri.product_id UNION ALL SELECT 
                po.purchase_request_id,
                    po.date_created,
                    '' AS date_ordered,
                    pr.pr_no,
                    poi.product_id,
                    0 AS PrQty,
                    SUM(poi.po_qty) AS PoQty,
                    0 AS po_price,
                    0 AS po_discount,
                    0 AS po_tax_rate
            FROM
                (purchase_order AS po
            INNER JOIN purchase_request AS pr ON pr.purchase_request_id = po.purchase_request_id)
            INNER JOIN purchase_order_items AS poi ON poi.purchase_order_id = po.purchase_order_id
            WHERE
                po.is_active = TRUE
                    AND po.is_deleted = FALSE
            GROUP BY pr.pr_no , poi.product_id) AS m
            GROUP BY m.pr_no , m.product_id
            HAVING PrQtyBalance > 0) AS main
            LEFT JOIN products AS p ON main.product_id = p.product_id) AS n) AS o";
        return $this->db->query($sql)->result();




    }

    function get_pr_no_of_open_purchase_request(){
        $sql="SELECT o.* FROM
            (SELECT n.*
            FROM
            (SELECT 
            DISTINCT main.pr_no
            FROM

            (SELECT
            m.purchase_request_id,
            m.pr_no,
            (SUM(m.PrQty)-SUM(m.PoQty))as PrQtyBalance
            
            FROM
            
            (SELECT pr.purchase_request_id,pr.pr_no,pri.product_id,SUM(pri.po_qty) as PrQty,0 as PoQty
            FROM purchase_request as pr
            INNER JOIN purchase_request_items as pri ON pri.purchase_request_id=pr.purchase_request_id
            WHERE pr.is_active=TRUE AND pr.is_deleted=FALSE
            AND (pr.order_status_id=1 OR pr.order_status_id=3)
            GROUP BY pr.pr_no,pri.product_id

            UNION ALL

            SELECT po.purchase_request_id,pr.pr_no,poi.product_id,0 as PrQty,SUM(poi.po_qty) as PoQty
            FROM (purchase_order as po
            INNER JOIN purchase_request as pr ON pr.purchase_request_id=po.purchase_request_id)
            INNER JOIN purchase_order_items as poi ON po.purchase_order_id=poi.purchase_order_id
            WHERE po.is_active=TRUE AND po.is_deleted=FALSE
            GROUP BY pr.pr_no,poi.product_id)as

            m GROUP BY m.pr_no,m.product_id HAVING PrQtyBalance>0)as main

           )as n) as o
            ";

        return $this->db->query($sql)->result();





    }




}



?>
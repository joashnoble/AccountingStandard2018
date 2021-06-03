<?php

class Adjustment_model extends CORE_Model
{
protected $table = "adjustment_info";
protected $pk_id = "adjustment_id";

function __construct()
{
parent::__construct();
}
// OUT ADJUSTMENT
 function get_journal_entries_2($adjustment_id){
        $sql="SELECT 
        main.* 
        FROM(

        SELECT
		(SELECT pi.adj_debit_id FROM purchasing_integration pi) as account_id,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as dr_amount,
		0 as cr_amount,
		'' as memo

		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.expense_account_id > 0
		GROUP BY adj.adjustment_id

		UNION ALL

		SELECT 
		p.expense_account_id as account_id,
		0 as dr_amount,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as cr_amount,
		'' as memo

		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.expense_account_id > 0
		GROUP BY p.expense_account_id) as main 
		WHERE main.dr_amount > 0 OR main.cr_amount > 0";
        return $this->db->query($sql)->result();

    }
// IN ADJUSTMENT
     function get_journal_entries_2_in($adjustment_id){
        $sql="SELECT 
        main.* 
        FROM(
		SELECT
		p.expense_account_id as account_id,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as dr_amount,
		0 as cr_amount,
		
		'' as memo
		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.expense_account_id > 0
		GROUP BY p.expense_account_id



		UNION ALL

        SELECT
		(SELECT pi.adj_credit_id FROM purchasing_integration pi) as account_id,
		0 as dr_amount,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as cr_amount,
		
		'' as memo

		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.expense_account_id > 0
		GROUP BY adj.adjustment_id

		) as main 
		WHERE main.dr_amount > 0 OR main.cr_amount > 0";

        return $this->db->query($sql)->result();



    }

	function get_journal_entries_salesreturn($adjustment_id){
		$sql="SELECT 
        main.* 
        FROM(
        -- Sales Return
		SELECT
		p.sales_return_account_id as account_id,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as dr_amount,
		0 as cr_amount,
		
		'' as memo
		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.sales_return_account_id > 0
		GROUP BY p.sales_return_account_id

		-- Inventory
		UNION ALL

        SELECT
		p.expense_account_id as account_id,
		SUM(adj.adjust_qty * p.purchase_cost) as dr_amount,
		0 as cr_amount,
		
		'' as memo

		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.expense_account_id > 0
		GROUP BY p.expense_account_id

		-- Output Tax
	    UNION ALL

	    SELECT output_tax.account_id,
	    SUM(output_tax.dr_amount) as dr_amount,
	    0 as cr_amount,
	    output_tax.memo
	     FROM
	    (SELECT adj.product_id,

	    (SELECT output_tax_account_id FROM account_integration) as account_id
	    ,
	    '' as memo,
	    SUM(adj.adjust_tax_amount) as dr_amount,
	    0 as cr_amount
	    FROM `adjustment_items` as adj
	    INNER JOIN products as p ON adj.product_id=p.product_id
	    WHERE adj.adjustment_id=$adjustment_id AND p.income_account_id>0
	    )as output_tax GROUP BY output_tax.account_id

	    -- AR / Cash 
		UNION ALL
				
		SELECT 
			main.*
		FROM
			(SELECT (CASE WHEN ai.inv_type_id = 1 THEN (SELECT receivable_account_id FROM account_integration)
						WHEN ai.inv_type_id = 2 THEN (SELECT payment_from_customer_id FROM account_integration)
						ELSE 0
					END) AS account_id,
					0 AS dr_amount,
					SUM(IFNULL(adj.adjust_line_total_price, 0)) AS cr_amount,
					'' AS memo
			FROM
				adjustment_items adj
			INNER JOIN products p ON p.product_id = adj.product_id
			LEFT JOIN adjustment_info ai ON ai.adjustment_id = adj.adjustment_id
			WHERE
				adj.adjustment_id = $adjustment_id) AS main
		WHERE
			main.account_id > 0
		GROUP BY main.account_id
		
		-- Cost of Sales
		UNION ALL
        
        SELECT
		p.cos_account_id as account_id,
		0 as dr_amount,
		SUM(adj.adjust_qty * p.purchase_cost) as cr_amount,
		'' as memo
		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.cos_account_id > 0
		GROUP BY p.cos_account_id

		-- Discount
		UNION ALL

		SELECT
		p.sd_account_id as account_id,
		0 as dr_amount,
		SUM(IFNULL(adj.adjust_line_total_discount,0)) as cr_amount,
		
		'' as memo
		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.sd_account_id > 0
		GROUP BY p.sd_account_id
       
        

		) as main 
		WHERE main.dr_amount > 0 OR main.cr_amount > 0";
        return $this->db->query($sql)->result();
	}

	function get_journal_entries_purchasereturn($adjustment_id){
		$sql="SELECT 
			    main.*
			FROM
			    (

			    SELECT 
			        main.*
			    FROM
			        (SELECT 
			        	(SELECT payable_account_id FROM account_integration) AS account_id,
			            SUM(IFNULL(adj.adjust_line_total_price, 0)) AS dr_amount,
			            0 AS cr_amount,
			            '' AS memo
			    FROM
			        adjustment_items adj
			    INNER JOIN products p ON p.product_id = adj.product_id
			    LEFT JOIN adjustment_info ai ON ai.adjustment_id = adj.adjustment_id
			    WHERE
			        adj.adjustment_id = $adjustment_id) AS main
			    WHERE
			        main.account_id > 0
			    GROUP BY main.account_id 


			    UNION ALL 


			    SELECT 
			        p.pd_account_id AS account_id,
			            SUM(IFNULL(adj.adjust_line_total_discount, 0)) AS dr_amount,
			            0 AS cr_amount,
			            '' AS memo
			    FROM
			        adjustment_items adj
			    INNER JOIN products p ON p.product_id = adj.product_id
			    WHERE
			        adj.adjustment_id = $adjustment_id
			            AND p.pd_account_id > 0
			    GROUP BY p.pd_account_id 


			    UNION ALL 

			    SELECT 
			        p.po_return_account_id AS account_id,
			            0 AS dr_amount,
			            SUM(IFNULL((adj.adjust_qty * adj.adjust_price)-adj.adjust_tax_amount, 0)) AS cr_amount,
			            '' AS memo
			    FROM
			        adjustment_items adj
			    INNER JOIN products p ON p.product_id = adj.product_id
			    WHERE
			        adj.adjustment_id = $adjustment_id
			            AND p.po_return_account_id > 0
			    GROUP BY p.po_return_account_id 

			    
			    UNION ALL 


			    SELECT 
			        input_tax.account_id,
			            0 AS dr_amount,
			            SUM(input_tax.dr_amount) AS cr_amount,
			            input_tax.memo
			    FROM
			        (SELECT 
			        adj.product_id,
			            (SELECT 
			                    input_tax_account_id
			                FROM
			                    account_integration) AS account_id,
			            '' AS memo,
			            SUM(adj.adjust_tax_amount) AS dr_amount,
			            0 AS cr_amount
			    FROM
			        adjustment_items AS adj
			    INNER JOIN products AS p ON adj.product_id = p.product_id
			    WHERE
			        adj.adjustment_id = $adjustment_id
			            AND p.expense_account_id > 0) AS input_tax
			    GROUP BY input_tax.account_id) AS main
			WHERE
			    main.dr_amount > 0 OR main.cr_amount > 0";
        return $this->db->query($sql)->result();
	}

	function list_per_customer($customer_id = null){
        $sql="SELECT 
			si.sales_inv_no as inv_no,
			si.is_journal_posted,
			si.total_overall_discount,
			((sii.inv_gross - sii.inv_line_total_discount)*(si.total_overall_discount/100)) as global_discount_amount,
			'1' as inv_type_id,
			p.product_code,
			p.product_desc,
			sii.inv_qty,
			u.unit_name,
			p.is_bulk,
			p.child_unit_id,
			p.parent_unit_id,
			p.child_unit_desc,
			p.sale_price,
            (CASE
                WHEN p.is_parent = TRUE 
                    THEN p.bulk_unit_id
                ELSE parent_unit_id
            END) as product_unit_id,
            (CASE
                WHEN p.is_parent = TRUE 
                    THEN blkunit.unit_name
                ELSE chldunit.unit_name
            END) as product_unit_name,
			IF(si.is_journal_posted = TRUE, 'Note: Invoice is posted in Accounting', 'Note: Invoice is not yet posted in Accounting') as note,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.parent_unit_id) as parent_unit_name,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.child_unit_id) as child_unit_name,
			sii.*,
			DATE_FORMAT(sii.exp_date,'%m/%d/%Y') as exp_date

			FROM sales_invoice_items sii

			LEFT JOIN sales_invoice si ON si.sales_invoice_id = sii.sales_invoice_id
			LEFT JOIN products p ON p.product_id = sii.product_id
			LEFT JOIN units u ON u.unit_id = sii.unit_id
            LEFT JOIN units as blkunit ON blkunit.unit_id = p.bulk_unit_id
            LEFT JOIN units as chldunit ON chldunit.unit_id = p.parent_unit_id			
			WHERE si.is_active = TRUE 
			AND si.is_deleted = FALSE
			AND si.customer_id= '$customer_id'

			UNION ALL

			SELECT
			ci.cash_inv_no as inv_no,
			ci.is_journal_posted,
			ci.total_overall_discount,
			((cii.inv_gross - cii.inv_line_total_discount)*(ci.total_overall_discount/100)) as global_discount_amount,
			'2' as inv_type_id,
			p.product_code,
			p.product_desc,
			cii.inv_qty,
			u.unit_name,
			p.is_bulk,
			p.child_unit_id,
			p.parent_unit_id,
			p.child_unit_desc,
			p.sale_price,
            (CASE
                WHEN p.is_parent = TRUE 
                    THEN p.bulk_unit_id
                ELSE parent_unit_id
            END) as product_unit_id,
            (CASE
                WHEN p.is_parent = TRUE 
                    THEN blkunit.unit_name
                ELSE chldunit.unit_name
            END) as product_unit_name,			
			IF(ci.is_journal_posted = TRUE, 'Invoice is posted in Accounting', 'Invoice is not yet posted in Accounting') as note,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.parent_unit_id) as parent_unit_name,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.child_unit_id) as child_unit_name,
			cii.*,
			DATE_FORMAT(cii.exp_date,'%m/%d/%Y') as exp_date

			FROM cash_invoice_items cii
			LEFT JOIN cash_invoice ci ON ci.cash_invoice_id = cii.cash_invoice_id
			LEFT JOIN products p ON p.product_id = cii.product_id
			LEFT JOIN units u ON u.unit_id = cii.unit_id
            LEFT JOIN units as blkunit ON blkunit.unit_id = p.bulk_unit_id
            LEFT JOIN units as chldunit ON chldunit.unit_id = p.parent_unit_id			
			WHERE ci.is_active = TRUE 
			AND ci.is_deleted = FALSE
			AND ci.customer_id= '$customer_id'
			";

        return $this->db->query($sql)->result();

    }

	function list_per_supplier($supplier_id = null){
        $sql="SELECT 
			    di.dr_invoice_no,
				'3' as inv_type_id,
			    di.is_journal_posted,
			    p.product_code,
			    p.product_desc,
			    dii.dr_qty,
			    u.unit_name,
			    p.is_bulk,
			    p.child_unit_id,
			    p.parent_unit_id,
			    p.child_unit_desc,
			    p.sale_price,
			    (CASE
			        WHEN p.is_parent = TRUE THEN p.bulk_unit_id
			        ELSE parent_unit_id
			    END) AS product_unit_id,
			    (CASE
			        WHEN p.is_parent = TRUE THEN blkunit.unit_name
			        ELSE chldunit.unit_name
			    END) AS product_unit_name,
			    IF(di.is_journal_posted = TRUE,
			        'Note: Invoice is posted in Accounting',
			        'Note: Invoice is not yet posted in Accounting') AS note,
			    (SELECT units.unit_name FROM units WHERE units.unit_id = p.parent_unit_id) AS parent_unit_name,
			    (SELECT units.unit_name FROM units WHERE units.unit_id = p.child_unit_id) AS child_unit_name,
			    dii.*,
			    DATE_FORMAT(dii.exp_date,'%m/%d/%Y') as exp_date,
			    dii.dr_price as cost_upon_invoice,
			    di.total_overall_discount,
				(((dii.dr_qty*dii.dr_price) - dii.dr_line_total_discount)*(di.total_overall_discount/100)) as global_discount_amount

			FROM
			    delivery_invoice_items dii
			        LEFT JOIN
			    delivery_invoice di ON di.dr_invoice_id = dii.dr_invoice_id
			        LEFT JOIN
			    products p ON p.product_id = dii.product_id
			        LEFT JOIN
			    units u ON u.unit_id = dii.unit_id
			        LEFT JOIN
			    units AS blkunit ON blkunit.unit_id = p.bulk_unit_id
			        LEFT JOIN
			    units AS chldunit ON chldunit.unit_id = p.parent_unit_id
			WHERE
			    di.is_active = TRUE
			        AND di.is_deleted = FALSE
			        AND di.is_finalized = TRUE
			        AND di.supplier_id = $supplier_id";
        return $this->db->query($sql)->result();

    }

     function get_adjustments_for_review(){
        $sql='SELECT main.*

			FROM(SELECT 
			ai.adjustment_id,
			ai.adjustment_code,
			ai.remarks,
			ai.adjustment_type,
			ai.date_created,
			DATE_FORMAT(ai.date_adjusted,"%m/%d/%Y") as date_adjusted,
			d.department_id,
			d.department_name

			FROM adjustment_info ai
			LEFT JOIN departments d ON d.department_id = ai.department_id

			WHERE
			ai.is_active=TRUE AND
			ai.is_deleted=FALSE AND 
			is_journal_posted=FALSE
			AND ai.adjustment_type = "IN"
			AND ai.is_closed = FALSE
			AND ai.approval_id = 1

			UNION ALL

			SELECT 

			ai.adjustment_id,
			ai.adjustment_code,
			ai.remarks,
			ai.adjustment_type,
			ai.date_created,
			DATE_FORMAT(ai.date_adjusted,"%m/%d/%Y") as date_adjusted,
			d.department_id,
			d.department_name

			FROM adjustment_info ai
			LEFT JOIN departments d ON d.department_id = ai.department_id

			WHERE
			ai.is_active=TRUE AND
			ai.is_deleted=FALSE AND 
			is_journal_posted=FALSE
			AND ai.adjustment_type = "OUT"
			AND ai.is_closed = FALSE
			AND ai.approval_id = 1) as main

			ORDER BY main.adjustment_id';
        return $this->db->query($sql)->result();



    }
}


?>
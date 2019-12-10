<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comparative_Income_statement extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->model(array(
                'Account_class_model',
                'Journal_info_model',
                'Journal_account_model',
                'Departments_model',
                'Accounting_period_model',
                'Users_model',
                'Company_model'
            )
        );
        $this->load->library('excel');
        $this->load->model('Email_settings_model');

    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', true);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', true);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', true);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', true);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', true);
        $data['title'] = 'Income Statement';

        $data['departments']=$this->Departments_model->get_list('is_deleted=FALSE');
        $data['income_accounts']=$this->Journal_info_model->get_account_balance(4);
        $data['expense_accounts']=$this->Journal_info_model->get_account_balance(5);
        (in_array('9-23',$this->session->user_rights)? 
        $this->load->view('comparative_income_statement_view', $data)
        :redirect(base_url('dashboard')));
        
    }

    function format_display($balance){
        $balance=(float)$balance;
        if($balance<0){
            $balance=str_replace("-","",$balance);
            return "(".number_format($balance,2).")";
        }else{
            return number_format($balance,2);
        }

    }

    function format_display_percentage($balance){
        $balance=(float)$balance;
        if($balance<0){
            $balance=str_replace("-","",$balance);
            return "(".number_format($balance,2)."%)";
        }else{
            if ($balance == 0){
                return number_format(100,2).'%';
            }else{
                return number_format($balance,2).'%';
            }
        }
    }  

    function transaction($txn)
    {
        switch($txn)
        {
            case 'export-excel':

                $single_underline = array(
                  'borders' => array(
                    'top' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                  )
                );                

                $m_journal = $this->Journal_info_model;
                $m_company=$this->Company_model;

                $company_info=$m_company->get_list();
                $start=$this->input->get('start',TRUE);
                $end=$this->input->get('end',TRUE);
                $dep_id=$this->input->get('depid',TRUE);

                $selected_year = date("Y",strtotime($start));
                $previous_year = date("Y",strtotime($start.' -1 year'));

                $departments = $this->Departments_model->get_list($dep_id);
                $department_name = $departments[0]->department_name;
                $income_accounts = $m_journal->get_account_balance(4,$dep_id,date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)));
                $expense_accounts = $m_journal->get_account_balance(5,$dep_id,date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)));

                $excel=$this->excel;
   

                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->getColumnDimension('A')
                                        ->setAutoSize(false)
                                        ->setWidth('50');

                $excel->getActiveSheet()->getColumnDimension('B')
                                        ->setAutoSize(false)
                                        ->setWidth('20');

                $excel->getActiveSheet()->setTitle('Comparative Income Statement');

                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->email_address)
                                        ->setCellValue('A4',$company_info[0]->mobile_no);

                $excel->getActiveSheet()
                        ->mergeCells('A9:F9')
                        ->setCellValue('A9', 'Income')
                        ->getStyle('A9:F9')
                        ->getFont()
                        ->setBold(TRUE);


                $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('A6','COMPARATIVE INCOME STATEMENT - '.$department_name)
                                        ->setCellValue('A7','FOR THE PERIOD ENDED '.date('F d, Y', strtotime($end)).' & '.date('F d, Y', strtotime($end.' -1 year')));


                $excel->getActiveSheet()->getColumnDimension('C')
                                        ->setAutoSize(false)
                                        ->setWidth('20');


                $excel->getActiveSheet()->getColumnDimension('D')
                                        ->setAutoSize(false)
                                        ->setWidth('20');


                $excel->getActiveSheet()->getColumnDimension('E')
                                        ->setAutoSize(false)
                                        ->setWidth('20');

                $excel->getActiveSheet()->getColumnDimension('F')
                                        ->setAutoSize(false)
                                        ->setWidth('40');

                $excel->getActiveSheet()->setCellValue('B8',$selected_year)
                                        ->setCellValue('C8',$previous_year)
                                        ->setCellValue('D8','Increase / (Decrease)')
                                        ->setCellValue('E8','% Change')
                                        ->setCellValue('F8','*Explanation of Increase/(Decrease)');


                $excel->getActiveSheet()->getStyle('B8:F8')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('B8:F8')
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('B9:D9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('A7')->getFont()->setItalic(TRUE);

                $i = 9;
                $income_total=0;
                $total_prev_income=0; 
                $total_change_amount=0; 
                $total_percentage_change=0; 

                $total_net = 0;
                $total_prev = 0;
                $total_change = 0;
                $total_percentage = 0;

                foreach($income_accounts as $income_account)
                {
                    $i++;

                    $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 

                    $excel->getActiveSheet()->setCellValue('A'.$i,$income_account->account_title);
                    $excel->getActiveSheet()->setCellValue('B'.$i,$this->format_display($income_account->account_balance))
                            ->getStyle('B'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()->setCellValue('C'.$i,$this->format_display($income_account->prev_account_balance))
                            ->getStyle('C'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()->setCellValue('D'.$i,$this->format_display($income_account->change_amount))
                            ->getStyle('D'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()->setCellValue('E'.$i,$this->format_display_percentage($income_account->percentage_change))
                            ->getStyle('E'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);                            


                    $income_total+=$income_account->account_balance;
                    $total_prev_income+=$income_account->prev_account_balance; 
                    $total_change_amount+=$income_account->change_amount; 

                    if ($total_prev_income == 0){
                        $total_percentage_change = 100;
                    }else{
                        $total_percentage_change = (($income_total - $total_prev_income ) / $total_prev_income) * 100;
                    }

                }

                $i++;

                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 
                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->applyFromArray($single_underline);

                $excel->getActiveSheet()->setCellValue('A'.$i,'Total Income')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $excel->getActiveSheet()->setCellValue('B'.$i,$this->format_display($income_total))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('C'.$i,$this->format_display($total_prev_income))
                                        ->getStyle('C'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('C'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('D'.$i,$this->format_display($total_change_amount))
                                        ->getStyle('D'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('D'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);     

                $excel->getActiveSheet()->setCellValue('E'.$i,$this->format_display_percentage($total_percentage_change))
                                        ->getStyle('E'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('E'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);                                                                       


                $i+=1;


                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 

                $excel->getActiveSheet()
                        ->mergeCells('A'.$i.':'.'F'.$i)
                        ->setCellValue('A'.$i, 'Expenses')
                        ->getStyle('A'.$i.':'.'F'.$i)
                        ->getFont()
                        ->setBold(TRUE);

                $expense_total = 0;
                $total_prev_expense=0; 
                $total_change_amount_expense=0; 
                $total_percentage_change_expense=0; 

                foreach($expense_accounts as $expense_account)
                {
                    $i++;

                   $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 

                    $excel->getActiveSheet()
                            ->setCellValue('A'.$i,$expense_account->account_title);

                    $excel->getActiveSheet()
                            ->setCellValue('B'.$i,$this->format_display($expense_account->account_balance))
                            ->getStyle('B'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()
                            ->setCellValue('C'.$i,$this->format_display($expense_account->prev_account_balance))
                            ->getStyle('C'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()
                            ->setCellValue('D'.$i,$this->format_display($expense_account->change_amount))
                            ->getStyle('D'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $excel->getActiveSheet()
                            ->setCellValue('E'.$i,$this->format_display_percentage($expense_account->percentage_change))
                            ->getStyle('E'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);                  

                    $expense_total+=$expense_account->account_balance;
                    $total_prev_expense+=$expense_account->prev_account_balance; 
                    $total_change_amount_expense+=$expense_account->change_amount;

                    if ($total_prev_expense == 0){
                        $total_percentage_change_expense = 100;
                    }else{
                        $total_percentage_change_expense =  (($expense_total - $total_prev_expense) / $total_prev_expense) * 100;
                    }

                    $total_net = $income_total - $expense_total;
                    $total_prev = $total_prev_income-$total_prev_expense;
                    $total_change = $total_change_amount-$total_change_amount_expense;

                    if($total_prev==0){
                        $total_percentage = 100;
                    }else{
                        $total_percentage = (($total_net - $total_prev) / $total_prev) * 100;
                    }

                }

                $i++;


                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 
                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->applyFromArray($single_underline);

                $excel->getActiveSheet()->setCellValue('A'.$i,'Total Expense')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $excel->getActiveSheet()->setCellValue('B'.$i,$this->format_display($expense_total))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('C'.$i,$this->format_display($total_prev_expense))
                                        ->getStyle('C'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('C'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);      

                $excel->getActiveSheet()->setCellValue('D'.$i,$this->format_display($total_change_amount_expense))
                                        ->getStyle('D'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('D'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);      

                $excel->getActiveSheet()->setCellValue('E'.$i,$this->format_display_percentage($total_percentage_change_expense))
                                        ->getStyle('E'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('E'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);                                                                                                                                                                     

                $i++;

                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); 
                $excel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->applyFromArray($single_underline);

                $excel->getActiveSheet()->setCellValue('A'.$i, 'Net Income')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $excel->getActiveSheet()->setCellValue('B'.$i, $this->format_display($total_net))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                $excel->getActiveSheet()->setCellValue('C'.$i, $this->format_display($total_prev))
                                        ->getStyle('C'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('C'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                $excel->getActiveSheet()->setCellValue('D'.$i, $this->format_display($total_change))
                                        ->getStyle('D'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('D'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                $excel->getActiveSheet()->setCellValue('E'.$i, $this->format_display_percentage($total_percentage))
                                        ->getStyle('E'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('E'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                                                                                                                                                

                $a = $i+2;
                $excel->getActiveSheet()->setCellValue('A'.$a, '*Explanation of increase/(decrease) shall only be applied to those accounts that increase or decrease by >= P100,000.00 and is equivalent to 10% of the change based on the prior period.')
                                        ->getStyle('A'.$a)
                                        ->getFont()
                                        ->getActiveSheet()
                                        ->getStyle('A'.$a)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                        

                $excel->getActiveSheet()->mergeCells('A'.$a.':F'.$a);


                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Comparative Income Statement ('.date('F d, Y', strtotime($end)).' & '.date('F d, Y', strtotime($end.' -1 year')).').xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');
                break;

                case 'email-excel':
                $m_journal = $this->Journal_info_model;
                $m_company=$this->Company_model;
                
                $m_email=$this->Email_settings_model;
                $filter_value = 2;
                $email=$m_email->get_list(2);    

                $company_info=$m_company->get_list();
                $start=$this->input->get('start',TRUE);
                $end=$this->input->get('end',TRUE);

                $income_accounts = $m_journal->get_account_balance(4,null,date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)));
                $expense_accounts = $m_journal->get_account_balance(5,null,date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)));

                $excel=$this->excel;

                ob_start();
                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->getColumnDimension('A')
                                        ->setAutoSize(false)
                                        ->setWidth('50');

                $excel->getActiveSheet()->getColumnDimension('B')
                                        ->setAutoSize(false)
                                        ->setWidth('25');

                $excel->getActiveSheet()->setTitle('Income Statement');

                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->email_address)
                                        ->setCellValue('A4',$company_info[0]->mobile_no);

                $excel->getActiveSheet()
                        ->mergeCells('A9:B9')
                        ->setCellValue('A9', 'INCOME')
                        ->getStyle('A9:B9')->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '53C1F0')
                                )
                            )
                        )->getFont()
                        ->setItalic(TRUE)
                        ->setBold(TRUE);

                

                $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('A6','INCOME STATEMENT')
                                        ->setCellValue('A7',$start.' to '.$end);

                $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('B9:D9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('A7')->getFont()->setItalic(TRUE);

                $i = 9;
                $income_total=0;
                $total_net = 0;
                foreach($income_accounts as $income_account)
                {
                    $i++;

                    $excel->getActiveSheet()->setCellValue('A'.$i,$income_account->account_title);
                    $excel->getActiveSheet()->setCellValue('B'.$i,number_format($income_account->account_balance,2))
                            ->getStyle('B'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $income_total+=$income_account->account_balance;

                }

                $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Total Income:')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('B'.$i,number_format($income_total,2))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $i+=2;

                $excel->getActiveSheet()
                        ->mergeCells('A'.$i.':'.'B'.$i)
                        ->setCellValue('A'.$i, 'EXPENSE')
                        ->getStyle('A'.$i.':'.'B'.$i)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '53C1F0')
                                )
                            )
                        )->getFont()
                        ->setItalic(TRUE)
                        ->setBold(TRUE);

                $expense_total = 0;
                foreach($expense_accounts as $expense_account)
                {
                    $i++;

                    $excel->getActiveSheet()
                            ->setCellValue('A'.$i,$expense_account->account_title);

                    $excel->getActiveSheet()
                            ->setCellValue('B'.$i,number_format($expense_account->account_balance,2))
                            ->getStyle('B'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $expense_total+=$expense_account->account_balance;
                }

                $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Total Expense:')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('B'.$i,number_format($expense_total,2))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $total_net = $income_total - $expense_total;

                $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i, 'NET INCOME:')
                                        ->getStyle('A'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('A'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('B'.$i, number_format($total_net,2))
                                        ->getStyle('B'.$i)
                                        ->getFont()
                                        ->setBold(TRUE)
                                        ->getActiveSheet()
                                        ->getStyle('B'.$i)
                                        ->getAlignment()
                                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Income Statement '.date('Y-m-d',strtotime($end)).'.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');
                $data = ob_get_clean();

                          $file_name='Income Statement '.date('Y-m-d h:i:A', now());
                            $excelFilePath = $file_name.".xlsx"; //generate filename base on id
                            //download it.
                            // Set SMTP Configuration
                            $emailConfig = array(
                                'protocol' => 'smtp', 
                                'smtp_host' => 'ssl://smtp.googlemail.com', 
                                'smtp_port' => 465, 
                                'smtp_user' => $email[0]->email_address, 
                                'smtp_pass' => $email[0]->password, 
                                'mailtype' => 'html', 
                                'charset' => 'iso-8859-1'
                            );

                            // Set your email information
                            
                            $from = array(
                                'email' => $email[0]->email_address,
                                'name' => $email[0]->name_from
                            );

                            $to = array($email[0]->email_to);
                            $subject = 'Income Statement';
                          //  $message = 'Type your gmail message here';
                            $message = '<p>To: ' .$email[0]->email_to. '</p></ br>' .$email[0]->default_message.'</ br><p>Sent By: '. '<b>'.$this->session->user_fullname.'</b>'. '</p></ br>' .date('Y-m-d h:i:A', now());

                            // Load CodeIgniter Email library
                            $this->load->library('email', $emailConfig);
                            // Sometimes you have to set the new line character for better result
                            $this->email->set_newline("\r\n");
                            // Set email preferences
                            $this->email->from($from['email'], $from['name']);
                            $this->email->to($to);
                            $this->email->subject($subject);
                            $this->email->message($message);
                            $this->email->attach($data, 'attachment', $excelFilePath , 'application/ms-excel');
                            $this->email->set_mailtype("html");
                            // Ready to send email and check whether the email was successfully sent
                            if (!$this->email->send()) {
                                // Raise error message
                            $response['title']='Try Again!';
                            $response['stat']='error';
                            $response['msg']='Please check the Email Address of your Supplier or your Internet Connection.';

                            echo json_encode($response);
                            } else {
                                // Show success notification or other things here
                            $response['title']='Success!';
                            $response['stat']='success';
                            $response['msg']='Email Sent successfully.';

                            echo json_encode($response);
                            }
                break;
        }
    }
}

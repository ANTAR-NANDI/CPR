<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lservice {

     //Service Category Add Form
     public function add_category_form() {
        $CI = & get_instance();
        $data = array(
             'title'    => "Add Service Category",
        );
        $serviceForm = $CI->parser->parse('service/add_service_category_form', $data, true);
        return $serviceForm;
    }
    // Service Category List
    public function category_list() {
        $CI = & get_instance();
        $CI->load->model('Service');
        $data['total_categories'] = $CI->Service->count_categories();
        $data['title']             = "Manage Service Category";
        $serviceList = $CI->parser->parse('service/category', $data, true);
        return $serviceList;
    }
    // Service Category Edit Data
    public function category_edit_data($category_id)
    {
        $CI = &get_instance();
        $CI->load->model('Service');
        $service_category = $CI->Service->service_category_detail($category_id);
        $data = array(
            'title'           => "Edit Service Category",
            'category_id'     => $service_category->id,
            'category_name'     => $service_category->name,
        );
        $chapterList = $CI->parser->parse('service/edit_service_category_form', $data, true);
        return $chapterList;
    }

    //Service Add
    public function service_add_form() {
        $CI = & get_instance();
        $categories = $CI->db->select('*')->from('service_category')->get()->result_array();
        $data = array(
             'title'    => display('add_service'),
             'categories' => $categories
        );
        $serviceForm = $CI->parser->parse('service/add_service_form', $data, true);
        return $serviceForm;
    }
     //Retrieve  service List	
     public function service_list() {
        $CI = & get_instance();
        $CI->load->model('Service');
        $data = array(
            'title'        => display('manage_service'),
            'total_categories' => $CI->Service->count_services(),
        );
        $serviceList = $CI->parser->parse('service/service', $data, true);
        return $serviceList;
    }
    // Service Edit Data
    public function service_edit_data($service_id)
    {
        $CI = &get_instance();
        $categories = $CI->db->select('*')->from('service_category')->get()->result_array();
        $CI->load->model('Service');
        $service= $CI->Service->service_detail($service_id);
        $data = array(
            'title'           => "Edit Service",
            'category_id'     => $service->category_id,
            'service_id'     => $service->id,
            'service_name'     => $service->name,
            'categories' => $categories
        );
        $chapterList = $CI->parser->parse('service/edit_service_form', $data, true);
        return $chapterList;
    }
    // Add Pos Invoice Form
    public function service_invoice_add_form() {
        $CI = & get_instance();
        $CI->load->model('Service');
        $CI->load->model('Purchases');
        $CI->load->model('Rqsn');
        $CI->load->model('Web_settings');
        $CI->load->model('Categories');
        $CI->load->model('Brands');
        $CI->load->model('Units');
        $CI->load->model('Warehouse');
        $CI->load->model('Technicians');
        $category_list = $CI->Categories->cates();
        $brand_list = $CI->Brands->category_list_product();
        $unit_list     = $CI->Units->unit_list();
        $all_supplier = $CI->Purchases->select_all_supplier();
        // Get Outlet data
        $central_warehouse = $CI->Rqsn->cw_list();
        $outlet_user = $CI->session->userdata('outlet_id');
        $outlet_list = $CI->Warehouse->get_outlet($outlet_user);
        
        if($outlet_list)
        {
            $outlet_id = $outlet_list[0]['outlet_id'];
            $outlet_name = $outlet_list[0]['outlet_name'];
        }
        else{
            $outlet_id = $central_warehouse[0]['warehouse_id'];
           $outlet_name = $central_warehouse[0]['central_warehouse'];
        }
        $CI->db->select('service_invoice.invoice_number');
        $CI->db->from('service_invoice');
        $CI->db->where('outlet_id', $outlet_id);
        $CI->db->order_by('id', 'DESC');
        $CI->db->limit(1);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $latestEntry = $query->row();
            $invoice_number = ($latestEntry->invoice_number) + 1;
        } else {
            $invoice_number = 1;
        }
        $categories = $CI->db->select('*')->from('service_category')->get()->result_array();
        $fund_deductions = $CI->db->select('*')->from('fund_deductions')->get()->result_array();
        $tech_list = $CI->Technicians->technician_list();
        $data = array(
            'title'         => display('service_invoice'),
            'outlet_name'   => $outlet_name,
            'outlet_id'     => $outlet_id,
            'invoice_number' => $invoice_number,
            'categories' => $categories,
            'category_list' => $category_list,
            'unit_list'    => $unit_list,
            'brand_list' => $brand_list,
            'all_supplier'  => $all_supplier,
            'fund_deductions' => $fund_deductions,
            'tech_list'     => $tech_list,
            'technician_id' => $CI->session->userdata('user_id')

        );
        // echo "<pre>";
        // print_r($data);
        // exit();
        
        $invoiceForm = $CI->parser->parse('service/add_invoice_form', $data, true);
        return $invoiceForm;
    }
    //Retrieve  service List	
    public function service_invoice_list() {
        $CI = & get_instance();
        $CI->load->model('Service');
        $CI->load->model('Technicians');
        $CI->load->model('Rqsn');
        $data = array(
            'title'        => display('manage_service'),
            'total_service_invoice' => $CI->Service->count_service_invoice(),
            'technician_list' => $CI->Technicians->technician_list(),
            'outlet_list' =>  $CI->Rqsn->outlet_list_to(),
            'cw_list' =>  $CI->Rqsn->cw_list()
        );
        $serviceList = $CI->parser->parse('service/service_invoice', $data, true);
        return $serviceList;
    }
    //Service Invoice html Data manual
    public function service_invoice_print($invoice_id)
    {
        $CI = &get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->library('numbertowords');
        $CI->load->library('converter');
        $CI->load->model('Warehouse');
        $CI->load->model('Service');
        $redirect_url = $_SESSION['redirect_uri'];
        ///////////////////////
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $invoice_setting_data = $CI->Invoices->getSettingData();
        $company_info = $CI->Invoices->retrieve_company();
        $invoice_info = $CI->Service->retrieve_service_invoice_html_data($invoice_id);
        $outlet_details = $CI->Service->outlet_details($invoice_info[0]->outlet_id);

        $data = array(
            'company_info'   => $company_info,
            'invoice_info'   => $invoice_info,
            'outlet_details' => $outlet_details,
            'company_logo'   => $currency_details[0]['invoice_logo'],
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
            'red_url'        => isset($redirect_url) ? $redirect_url : null,
            'service_name'  => $invoice_info[0]['service_name'],
            'technician_name' => $invoice_info[0]['technician_first_name'] . " ". $invoice_info[0]['technician_last_name'],
            'served_by' => $invoice_info[0]['outlet_first_name'] . " ". $invoice_info[0]['outlet_last_name'],
            'customer_name' => $invoice_info[0]['customer_name'],
            'customer_mobile' => $invoice_info[0]['customer_mobile'],
            'customer_address' => $invoice_info[0]['customer_address'],




            
        );
        // echo "<pre>";
        // print_r($data);
        // exit();


        // $material_details = $CI->Service->invoice_material_details($invoice_id);
        // $service_name_details = $CI->Service->service_name_details($invoice_info[0]->service_id);
        // // if($invoice_info[0]->outlet_id == "HK7TGDT69VFMXB7"){

        // // }
        // $technician_details = $CI->Service->user_details($invoice_info[0]->technician_id);
        // $sales_details = $CI->Service->user_details($invoice_info[0]->sales_by);
        // $payment_info = $CI->Invoices->payment_details_total($invoice_id);
        
        

       



       

        foreach ($invoice_setting_data as $key => $invoice_setting) {
            $data[$invoice_setting->OptionSlug] = $invoice_setting->status;
        }



        if ($invoice_setting_data[0]->status == "A4") {
            $chapterList = $CI->parser->parse('service/service_invoice_print_a4.php', $data, true);
        }
        if ($invoice_setting_data[0]->status == "A5") {
            $chapterList = $CI->parser->parse('service/service_invoice_print_a5', $data, true);
        }

        if ($invoice_setting_data[0]->status == "80") {
            $chapterList = $CI->parser->parse('service/service_invoice_print_80mm', $data, true);
        }
        if ($invoice_setting_data[0]->status == "55") {
            $chapterList = $CI->parser->parse('service/service_invoice_print_55mm.php', $data, true);
        }

        return $chapterList;
    }
    //////////////////Report Module /////////////////////

    // Earning Report
    public function technician_earning_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Technicians');
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $data['title'] = 'Summary Report';
        $company_info = $CI->Reports->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['company_info'] = $company_info;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['technician_list'] = $CI->Technicians->technician_list();
        $data['heading_text'] = "Summary Report";
        $reportList = $CI->parser->parse('service/technician_earning_report', $data, true);
        return $reportList;
    }
    public function purchase_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Technicians');
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $CI->load->model('Suppliers');
        $supplier_list = $CI->Suppliers->supplier_list();
        $data['title'] = 'Purchase Report';
        $data['heading_text'] = "Purchase Report";
        $company_info = $CI->Reports->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['company_info'] = $company_info;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['technician_list'] = $CI->Technicians->technician_list();
        $data['supplier_list'] = $supplier_list;
        $reportList = $CI->parser->parse('service/purchase_report', $data, true);
        return $reportList;
    }
    public function product_wise_stock_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Products');
        $CI->load->model('Rqsn');
        $CI->load->model('categories');
        $CI->load->model('suppliers');
        $CI->load->model('Web_settings');
        $data['title'] = 'Product Wise Stock Report';
        $cat_list = $CI->categories->cates();
        $sku_list = $CI->Products->sku_list();
        $company_info = $CI->Reports->retrieve_company();
        $supplier_list = $CI->suppliers->supplier_list();
        $logged_outlet_id = $CI->session->userdata('outlet_id');
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['totalnumber'] = $CI->Reports->totalnumberof_purchase();
        $data['company_info'] = $company_info;
        $data['pr_status'] = 1;
        $data['cat_list'] = $cat_list;
        $data['sku_list'] = $sku_list;
        $data['supplier_list'] = $supplier_list;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['purchase_id'] =  $CI->Reports->all_purchase_id();
        $data['heading_text'] = "";
        $data['logged_outlet_id'] = $logged_outlet_id;
        $reportList = $CI->parser->parse('service/product_wise_stock_report', $data, true);
        return $reportList;
    }
    public function warranty_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Technicians');
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $data['title'] = 'Warranty Report';
        $company_info = $CI->Reports->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['company_info'] = $company_info;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['technician_list'] = $CI->Technicians->technician_list();
        $data['heading_text'] = "Warranty Report";
        $reportList = $CI->parser->parse('service/warranty_report', $data, true);
        return $reportList;
    }
    public function sales_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Technicians');
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $data['title'] = 'Sales Report';
        $company_info = $CI->Reports->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['company_info'] = $company_info;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['technician_list'] = $CI->Technicians->technician_list();
        $data['heading_text'] = "Sales Report";
        $reportList = $CI->parser->parse('service/sales_report', $data, true);
        return $reportList;
    }
     // Earning Report
     public function technician_payment_report()
     {
         $CI = &get_instance();
         $CI->load->model('Reports');
         $CI->load->model('Rqsn');
         $CI->load->model('Technicians');
         $CI->load->model('Warehouse');
         $CI->load->model('Web_settings');
         $data['title'] = 'Technician Payment Report';
         $company_info = $CI->Reports->retrieve_company();
         $currency_details = $CI->Web_settings->retrieve_setting_editdata();
         $data['currency'] = $currency_details[0]['currency'];
         $data['company_info'] = $company_info;
         $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
         $data['cw_list'] =  $CI->Rqsn->cw_list();
         $data['technician_list'] = $CI->Technicians->technician_list();
         $data['heading_text'] = "Technician Payment Report";
         $reportList = $CI->parser->parse('service/technician_payment_report', $data, true);
         return $reportList;
     }
     // Earning Report
    public function account_report()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Technicians');
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $data['title'] = 'Account Report';
        $company_info = $CI->Reports->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency'] = $currency_details[0]['currency'];
        $data['company_info'] = $company_info;
        $data['outlet_list'] =  $CI->Rqsn->outlet_list_to();
        $data['cw_list'] =  $CI->Rqsn->cw_list();
        $data['technician_list'] = $CI->Technicians->technician_list();
        $data['heading_text'] = "Account Report";
        $reportList = $CI->parser->parse('service/account_report', $data, true);
        return $reportList;
    }

































    public function service_invoice_edit_data($invoice_id){

        $CI = & get_instance();
        $CI->load->model('Service');
        $CI->load->model('Web_settings');
        $bank_list        = $CI->Web_settings->bank_list();
        $bkash_list        = $CI->Web_settings->bkash_list();
        $employee_list    = $CI->Service->employee_list();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $CI->Service->service_invoice_updata($invoice_id);
        $customer_info    =  $CI->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo  = $CI->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $CI->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
        $data = array(
            'title'         => display('update_service_invoice'),
            'employee_list' => $employee_list,
            'invoice_id'    => $service_inv_main[0]['voucher_no'],
            'date'          => $service_inv_main[0]['date'],
            'customer_id'   => $service_inv_main[0]['customer_id'],
            'customer_name' => $customer_info->customer_name,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => $service_inv_main[0]['total_amount'],
            'total_discount'=> $service_inv_main[0]['total_discount'],
            'invoice_discount'=> $service_inv_main[0]['invoice_discount'],
            'total_tax'     => $service_inv_main[0]['total_tax'],
            'paid_amount'   => $service_inv_main[0]['paid_amount'],
            'due_amount'    => $service_inv_main[0]['due_amount'],
            'shipping_cost' => $service_inv_main[0]['shipping_cost'],
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'    => $service_inv_main[0]['employee_id'],
            'previous'     => $service_inv_main[0]['previous'],
            'paytype'     => $service_inv_main[0]['paytype'],
            'bkash_id'     => $service_inv_main[0]['bkash_id'],
            'bkash_no'     => $service_inv_main[0]['bkash_no'],
            'ac_name'     => $service_inv_main[0]['ac_name'],
            'bank_id'     => $service_inv_main[0]['bank_id'],
            'bank_name'     => $service_inv_main[0]['bank_name'],
            'bank_list'     => $bank_list,
            'bkash_list'     => $bkash_list,


        );

       // echo '<pre>';print_r($data);exit();
        $invoiceForm = $CI->parser->parse('service/update_invoice_form', $data, true);
        return $invoiceForm;

    }

     public function service_invoice_view_data($invoice_id){

        $CI = & get_instance();
        $CI->load->model('Service');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('occational');
        $employee_list = $CI->Service->employee_list();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $CI->Service->service_invoice_updata($invoice_id);
        $customer_info =  $CI->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo = $CI->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $CI->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();

     $taxreg = $CI->db->select('*')
                ->from('tax_settings')
                ->where('is_show',1)
                ->get()
                ->result_array();
        $txregname ='';
        foreach($taxreg as $txrgname){
        $regname = $txrgname['tax_name'].' Reg No  - '.$txrgname['reg_no'].', ';
        $txregname .= $regname;
        }    
        $company_info = $CI->Invoices->retrieve_company();

        $subTotal_quantity = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;

        if (!empty($service_inv_main)) {
            foreach ($service_inv_main as $k => $v) {
                $service_inv_main[$k]['final_date'] = $CI->occational->dateConvert($service_inv_main[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $service_inv_main[$k]['qty'];
                $subTotal_ammount = $subTotal_ammount + $service_inv_main[$k]['total'];
            }

            $i = 0;
            foreach ($service_inv_main as $k => $v) {
                $i++;
                $service_inv_main[$k]['sl'] = $i;
            }
        }
        $data = array(
            'title'         => display('service_details'),
            'employee_list' => $employee_list,
            'invoice_id'    => $service_inv_main[0]['voucher_no'],
            'final_date'    => $service_inv_main[0]['final_date'],
            'customer_id'   => $service_inv_main[0]['customer_id'],
            'customer_name' => $customer_info->customer_name,
            'customer_address'=> $customer_info->customer_address,
            'customer_mobile'=> $customer_info->customer_mobile,
            'customer_email'=> $customer_info->customer_email,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => number_format($service_inv_main[0]['total_amount'], 2, '.', ','),
            'total_discount'=> number_format($service_inv_main[0]['total_discount'], 2, '.', ','),
            'invoice_discount'=> number_format($service_inv_main[0]['invoice_discount'], 2, '.', ','),
            'subTotal_ammount'=> number_format($subTotal_ammount, 2, '.', ','),
            'subTotal_quantity'=>number_format($subTotal_quantity, 2, '.', ','),
            'total_tax'     => number_format($service_inv_main[0]['total_tax'], 2, '.', ','),
            'paid_amount'   => number_format($service_inv_main[0]['paid_amount'], 2, '.', ','),
            'due_amount'    => number_format($service_inv_main[0]['due_amount'], 2, '.', ','),
            'shipping_cost' => number_format($service_inv_main[0]['shipping_cost'], 2, '.', ','),
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'     => $service_inv_main[0]['employee_id'],
            'previous'      => number_format($service_inv_main[0]['previous'], 2, '.', ','),
            'company_info'  => $company_info,
            'tax_regno'     => $txregname,

        );
        $invoiceForm = $CI->parser->parse('service/invoice_html', $data, true);
        return $invoiceForm;

    }


}

?>
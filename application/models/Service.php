<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lservice');
        $this->load->library('Smsgateway');
        $this->load->library('session');
        $this->load->model('Service');
        $this->auth->check_admin_auth();
    }
    //Count Sevice category
    public function count_categories()
    {
        return $this->db->count_all("service_category");
    }
    public function getCategoryList($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (service_category.name like '%" . $searchValue . "%' or service_category.created_date like '%"  . $searchValue . "%'
            or service_category.updated_date like '%"  . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('service_category');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('service_category');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("service_category.*");
        $this->db->from('service_category');
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

                $button .= '<a href="' . $base_url . 'Cservice/service_category_update_form/' . $record->id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'Cservice/service_category_delete/' . $record->id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            $data[] = array(
                'sl'               => $sl,
                'category_name'    => html_escape($record->name),
                'created_date'    => html_escape($record->created_date),
                'updated_date' => $record->updated_date ? html_escape($record->updated_date) : "N/A",
                'button'           => $button,

            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }
    //Retrieve Service Category data
    public function service_category_detail($category_id)
    {
        $this->db->select('*');
        $this->db->from('service_category');
        $this->db->where('id', $category_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }
     //Count Sevice category
     public function count_services()
     {
         return $this->db->count_all("product_service");
     }
     // Ajax Service Data
     public function getServiceList($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (product_service.name like '%" . $searchValue . "%' or service_category.name like '%" . $searchValue . "%' or product_service.created_date like '%"  . $searchValue . "%'
            or product_service.updated_date like '%"  . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_service');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_service');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("product_service.id,service_category.name as category_name,product_service.name as service_name,
        product_service.created_date,product_service.updated_date");
        $this->db->from('product_service');
        $this->db->join('service_category', 'service_category.id = product_service.category_id', 'left');
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

                $button .= '<a href="' . $base_url . 'Cservice/service_update_form/' . $record->id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'Cservice/service_delete/' . $record->id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            $data[] = array(
                'sl'               => $sl,
                'service_name'    => html_escape($record->service_name),
                'category_name'    => html_escape($record->category_name),
                'created_date'    => html_escape($record->created_date),
                'updated_date' => $record->updated_date ? html_escape($record->updated_date) : "N/A",
                'button'           => $button,

            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }
    public function service_search($service_name)
    {
        $query =
            $this->db->select('*')->from('product_service')
            ->group_start()
            ->like('name', $service_name)
            ->group_end()
            ->limit(30)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function autocompletproductdata($product_name, $pr_status = null)
    {
        $this->db->select('*')
            ->from('product_information');

        if ($pr_status) {
            $this->db->where('product_information.finished_raw', $pr_status);
        }

        $query =  $this->db->group_start()
            ->like('product_name', $product_name, 'both')
            ->or_like('sku', $product_name, 'both')
            ->group_end()
            ->order_by('product_name', 'asc')
            ->limit(15)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //Count Sevice Invoice
    public function count_service_invoice()
    {
        return $this->db->count_all("service_invoice");
    }
    // Ajax Service Data
    public function getServiceInvoiceList($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        $outlet_id = $this->input->post('outlet_id',TRUE);
        $technician_id = $this->input->post('technician_id',TRUE);
        $from_date = $this->input->post('from_date',TRUE);
        $to_date = $this->input->post('to_date',TRUE);
        $delivery_status = $this->input->post('delivery_status',TRUE);
        $payment_status = $this->input->post('payment_status',TRUE);

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (customer_information.customer_name like '%" . $searchValue . "%' or outlet_warehouse.outlet_name like '%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select("service_invoice.service_invoice_id,service_invoice.invoice_number,service_invoice.invoice_date,
        customer_information.customer_name,
        customer_information.customer_mobile,
        outlet_warehouse.outlet_name,
        service_invoice.total_amount,service_invoice.service_price,service_invoice.total_discount,
        service_invoice.deduction_amount,
        service_invoice.paid_amount,
        service_invoice.net_total,
        service_invoice.time,
        service_invoice.delivery_status,
        service_invoice.due_amount,
        service_invoice.total_selling_price,
            service_invoice.total_purchase_price,
        service_invoice.technician_id,
        service_invoice.delivery_date,
        user.first_name,user.last_name,commissions.rate,
        technician.first_name as technician_first_name,
        technician.last_name as technician_last_name
        ");
        $this->db->from('service_invoice');
        $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
        $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = service_invoice.outlet_id', 'left');
        $this->db->join('users as user', 'user.user_id = service_invoice.user_id', 'left');
        $this->db->join('users as technician', 'technician.user_id = service_invoice.technician_id', 'left');
        $this->db->join('commissions', 'commissions.technician_id = service_invoice.technician_id', 'left');
        $this->db->where('service_invoice.is_delete',0);
        // if ($searchValue != '')
        //     $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }
            if ($to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($delivery_status != '') {
                $this->db->where('service_invoice.delivery_status', $delivery_status);
            }
            if ($payment_status == 'due') {
                $this->db->where('service_invoice.due_amount >', 0);
            }
            if ($payment_status == 'paid') {
                $this->db->where('service_invoice.due_amount', 0);
            }
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select("service_invoice.service_invoice_id,service_invoice.invoice_number,service_invoice.invoice_date,
        customer_information.customer_name,
        customer_information.customer_mobile,
        outlet_warehouse.outlet_name,
        service_invoice.total_amount,service_invoice.service_price,service_invoice.total_discount,
        service_invoice.deduction_amount,
        service_invoice.paid_amount,
        service_invoice.net_total,
        service_invoice.time,
        service_invoice.delivery_status,
        service_invoice.due_amount,
        service_invoice.total_selling_price,
            service_invoice.total_purchase_price,
        service_invoice.technician_id,
        service_invoice.delivery_date,
        user.first_name,user.last_name,commissions.rate,
        technician.first_name as technician_first_name,
        technician.last_name as technician_last_name
        ");
        $this->db->from('service_invoice');
        $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
        $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = service_invoice.outlet_id', 'left');
        $this->db->join('users as user', 'user.user_id = service_invoice.user_id', 'left');
        $this->db->join('users as technician', 'technician.user_id = service_invoice.technician_id', 'left');
        $this->db->join('commissions', 'commissions.technician_id = service_invoice.technician_id', 'left');
        $this->db->where('service_invoice.is_delete',0);
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }
            if ($to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($delivery_status != '') {
                $this->db->where('service_invoice.delivery_status', $delivery_status);
            }
            if ($payment_status == 'due') {
                $this->db->where('service_invoice.due_amount >', 0);
            }
            if ($payment_status == 'paid') {
                $this->db->where('service_invoice.due_amount', 0);
            }
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("service_invoice.service_invoice_id,service_invoice.invoice_number,service_invoice.invoice_date,
        customer_information.customer_name,
        customer_information.customer_mobile,
        outlet_warehouse.outlet_name,
        service_invoice.total_amount,service_invoice.service_price,service_invoice.total_discount,
        service_invoice.deduction_amount,
        service_invoice.paid_amount,
        service_invoice.net_total,
        service_invoice.time,
        service_invoice.delivery_status,
        service_invoice.due_amount,
        service_invoice.total_selling_price,
            service_invoice.total_purchase_price,
        service_invoice.technician_id,
        service_invoice.delivery_date,
        user.first_name,user.last_name,commissions.rate,
        technician.first_name as technician_first_name,
        technician.last_name as technician_last_name
        ");
        $this->db->from('service_invoice');
        $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
        $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = service_invoice.outlet_id', 'left');
        $this->db->join('users as user', 'user.user_id = service_invoice.user_id', 'left');
        $this->db->join('users as technician', 'technician.user_id = service_invoice.technician_id', 'left');
        $this->db->join('commissions', 'commissions.technician_id = service_invoice.technician_id', 'left');
        $this->db->where('service_invoice.is_delete',0);
        $this->db->order_by('service_invoice.invoice_number','desc');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        if ($outlet_id != '') {
            $this->db->where('service_invoice.outlet_id', $outlet_id);
        }
        if ($technician_id != '') {
            $this->db->where('service_invoice.technician_id', $technician_id);
        }
        if ($from_date != '') {
            $this->db->where('service_invoice.invoice_date >=', $from_date);
        }
        if ($to_date != '') {
            $this->db->where('service_invoice.invoice_date <=', $to_date);
        }
        if ($delivery_status != '') {
            $this->db->where('service_invoice.delivery_status', $delivery_status);
        }
        if ($payment_status == 'due') {
            $this->db->where('service_invoice.due_amount >', 0);
        }
        if ($payment_status == 'paid') {
            $this->db->where('service_invoice.due_amount', 0);
        }
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        $base_url = base_url();
        foreach ($records as $record) {

            $deduduction_details = $this->getDeductionDetails($record->service_invoice_id);
            $resultString = '';
            if(count($deduduction_details) > 0)
            {
                foreach ($deduduction_details as $key => $item) {
                    $resultString .= $item['name'] . ' => ' . $item['amount'] . "<br>";                    
                }
            }
            // Technician commission 
            $comission_rate = $record->rate ? $record->rate : 0.00;
            // Technician Amount
            if($comission_rate == 0)
            {
                $amount = 0.00;
            }
            else{
                if(($record->total_purchase_price -$record->total_selling_price) == 0)
                {
                    $amount = 0.00;
                }
                else{
                    $amount = (($record->total_selling_price -$record->total_purchase_price)-($record->deduction_amount ? $record->deduction_amount : 0)) * ($comission_rate/100);
                }
            } 
            $technician_details = '  <a href="' . $base_url . 'Cservice/technician_due_payment/' . $record->technician_id . '" class="" >' . $record->technician_first_name . " ". $record->technician_last_name . '</a>';

            $button = '';
            $base_url = base_url();
            if (($record->paid_amount - $record->net_total) >= 0) {
                $payment = '<span class="label label-success ">Paid</span>';
            } else {
                $payment = '<span class="label label-danger ">Due</span>';
            }
            $jsaction = "return confirm('Are You Sure ?')";
            $button .= '<a class="btn btn-black btn-sm" data-toggle="tooltip" data-placement="left" title="Change Status" onclick="updateStatus(\'' . $record->service_invoice_id . '\',\'' . $record->delivery_status . '\')""><i class="fa fa-money" aria-hidden="true"></i></a>';
            $button .= '  <a href="' . $base_url . 'Cservice/service_invoice_print/' . $record->service_invoice_id . '" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="' . "Service Invoice" . '"><i class="fa fa-window-restore" aria-hidden="true"></i></a><br>';
            $button .= ' <a  class="btn btn-black btn-sm" data-toggle="tooltip" data-placement="left" title="Payment" onclick="payment_modal(' . $record->service_invoice_id . ',' . $record->net_total . ',' . $record->paid_amount . ',' . $record->due_amount . ')"><i class="fa fa-money" aria-hidden="true"></i></a> ';
            $button .= '<a href="' . $base_url . 'Cservice/service_invoice_delete/' . $record->service_invoice_id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
                $data[] = array(
                'sl'               => $sl,
                'invoice_number'    => html_escape($record->invoice_number),
                'invoice_date'    => html_escape($record->invoice_date) . " ". $record->time,
                'customer_name'    => html_escape($record->customer_name) . "<br>". html_escape($record->customer_mobile),
                'outlet_name'    => $record->outlet_name ? html_escape($record->outlet_name) : "Central Warehouse",
                'sales_person'    => html_escape($record->first_name . " ". $record->last_name),
                'technician_name'    => $technician_details,
                'technician_percentage'    => $comission_rate,
                'deductions'    => $resultString,
                'total_amount'    => number_format((float)html_escape($record->total_amount), 2, '.', ''),
                'paid_amount'    => number_format((float)html_escape($record->paid_amount), 2, '.', ''),
                'due_amount'    => number_format((float)html_escape($record->due_amount), 2, '.', ''),
                'technician_amount'    => number_format((float)$amount, 2, '.', ''),
                'delivery_date'    => html_escape($record->delivery_date),
                'payment_status'    => $payment,
                'status'    => html_escape($record->delivery_status),
                'button'           => $button,

            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }
    public function retrieve_service_invoice_html_data($service_invoice_id)
    {
        $this->db->select("service_invoice.outlet_id,
        customer_information.customer_name,
        customer_information.customer_mobile,
        customer_information.customer_address,
        service_invoice.service_invoice_id,
        service_invoice.invoice_number,
        service_invoice.invoice_date,
        product_information.product_name,
        service_invoice_details.quantity,
        service_invoice_details.warranty_date,
        service_invoice_details.price as item_rate,
        outlet_warehouse.outlet_name,
        service_invoice.total_amount,service_invoice.rounding, service_invoice.grand_total,service_invoice.service_price,service_invoice.total_discount,
        service_invoice.deduction_amount,
        service_invoice.net_total,
        service_invoice.invoice_date,
        service_invoice.delivery_date,
        service_invoice.total_selling_price,
        service_invoice.paid_amount,
        service_invoice.due_amount,
        service_invoice.delivery_date,
        product_service.name as service_name,
        user.first_name as outlet_first_name,user.last_name as outlet_last_name,
        technician.first_name as technician_first_name,technician.last_name as technician_last_name
        ");
        $this->db->from('service_invoice');
        $this->db->join('product_service', 'product_service.id = service_invoice.service_id', 'left');
        $this->db->join('service_invoice_details', 'service_invoice_details.service_invoice_id = service_invoice.service_invoice_id', 'left');
        $this->db->join('product_information', 'product_information.product_id = service_invoice_details.product_id', 'left');
        $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = service_invoice.outlet_id', 'left');
        $this->db->join('users as technician', 'technician.user_id = service_invoice.technician_id', 'left');
        $this->db->join('users as user', 'user.user_id = service_invoice.user_id', 'left');
        $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
        $this->db->where('service_invoice.service_invoice_id', $service_invoice_id);
        return $this->db->get()->result_array();
    }
    public function outlet_details($outlet_id)
    {

        $this->db->select('*');
        $this->db->from('outlet_warehouse');
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function user_details($user_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
    public function getDeductionDetails($service_id)
    {
        $this->db->select('fund_deductions.name,fund_deductions.type,service_deduction_map.percentage,service_deduction_map.amount');
        $this->db->from('service_deduction_map');
        $this->db->join('fund_deductions','fund_deductions.id = service_deduction_map.deduction_id');
        $this->db->where('service_deduction_map.service_invoice_id', $service_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        }
        return false;
    }
    public function get_commissions($technician_id) {
        $this->db->select('rate');
        $this->db->from('commissions');
        $this->db->where('commissions.technician_id', $technician_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
    public function invoice_entry(){
        $CI = & get_instance();
        $CI->load->model('Rqsn');
        $CI->load->model('Reports');
        // Customer Headcode
            $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $this->input->post('customer_id',true))->get()->row();
            $headname = $this->input->post('customer_id',true) . '-' . $cusifo->customer_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headname)->get()->row();
            $customer_headcode = $coainfo->HeadCode;

        // Add Data in Service Invoice Table
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');
        $invoice_id = $this->generator(10);
        $invoice_date = $this->input->post('invoice_date',true) ? $this->input->post('invoice_date',true) : date('Y-m-d');
        $data = array(
            'service_invoice_id'     => $invoice_id,
            'invoice_date'     => $invoice_date,
            'time'             => date("h:i A"),
            'delivery_date'            => $this->input->post('delivery_date',true) ?$this->input->post('delivery_date',true):date('Y-m-d', strtotime(date('Y-m-d'). ' + 10 days')),
            'invoice_number'    => $this->input->post('invoice_number',true),
            'outlet_id'       => $this->input->post('outlet_id',true),
            'customer_id'      => $this->input->post('customer_id',true),
            'service_price'         => $this->input->post('service_price',true),
            'service_id'=> $this->input->post('service_id',true),
            'technician_id'  => $this->input->post('technician_id',true),
            'user_id'  =>        $this->session->userdata('user_id'),
            'deduction_amount'   => $this->input->post('total_deduction',true),
            'total_amount'     => $this->input->post('total_service_price',true),
            'grand_total'      => $this->input->post('grand_total',true),
            'rounding'        => $this->input->post('rounding',true),
            'net_total'        => $this->input->post('net_total',true),
            'total_discount'        => $this->input->post('total_discount_amount',true),
            'invoice_discount'        => $this->input->post('discount_amount',true),
            'total_selling_price'        => $this->input->post('total_selling_price',true),
            'total_purchase_price'        => $this->input->post('total_purchase_price',true),
            'percent_discount'        => $this->input->post('discount_percentage',true) > 0 ? ($this->input->post('discount_percentage',true) * $this->input->post('total_service_price',true))/100 : 0,
            'paid_amount'        => $this->input->post('paid_amount',true),
            'due_amount'        => $this->input->post('due_amount',true),
            'change_amount'        => $this->input->post('change_amount',true),
            'previous'        => $this->input->post('previous',true),
            'details'        => $this->input->post('inva_details',true),
            'delivery_status'        => 'Placed',
            'created_date'   => date('Y-m-d'),
            'is_delete'   => 0,
            'created_by'       => $this->session->userdata('user_id'),

        );
        $this->db->insert('service_invoice', $data);
        $service_invoice_id = $this->db->insert_id();
        // add Technician Payment data
         $commission_details = $this->get_commissions($this->input->post('technician_id',true));
         if($commission_details)
         {
            $commission_rate = $commission_details->rate;
         }
         else{
            $commission_rate = 0;
         }
        $technician_payment_array = array(
                'service_invoice_id' => $invoice_id,
                'invoice_date'    =>  $this->input->post('invoice_date',true) ? $this->input->post('invoice_date',true) : date('Y-m-d'),
                'technician_id'     => $this->input->post('technician_id',true),
                'commission_amount'         => $commission_rate == 0 ? 0 : (($this->input->post('total_selling_price',true) + $this->input->post('service_price',true))
                                        - ($this->input->post('total_purchase_price',true) + $this->input->post('total_deduction',true))) * ($commission_rate/100),
                'paid_amount'                 => 0,
                'due_amount'           => $commission_rate == 0 ? 0 : (($this->input->post('total_selling_price',true) + $this->input->post('service_price',true))
                                        - ($this->input->post('total_purchase_price',true) + $this->input->post('total_deduction',true))) * ($commission_rate/100),
                'payment_status'      => 0,
                                        'created_date'   => date('Y-m-d'),
                'created_by'       => $this->session->userdata('user_id'),
             );
           $this->db->insert('technician_payment', $technician_payment_array);
         ////////////////////////////////////////////                 //////////////////////////////////////////////////
        // Add Data In Service Invoice Details Table 
       $product_id = $this->input->post('product_id',true);
       $purchase_price = $this->input->post('purchase_price',true);
       $selling_price = $this->input->post('selling_price',true);
       $quantity = $this->input->post('quantity',true);
       $deduction_fund = $this->input->post('deduction_fund',true);
       $warranty_date = $this->input->post('warranty_date',true);
       $warranty_percentage = $this->input->post('claim_percentage',true);
        for ($i = 0, $n = count($product_id); $i < $n; $i++) {
                    if ($this->input->post('outlet_id',true) == 'HK7TGDT69VFMXB7') {
                        $stock_details = $this->Reports->getExpiryCheckList($product_id[$i])['aaData'];
                    } else {
                        $stock_details = $this->Rqsn->expiry_outlet_stock($product_id[$i])['aaData'];
                    }
                        $details_array = array(
                            'service_invoice_id' => $invoice_id,
                            'purchase_id'         => $stock_details[0]['purchase_id'],
                            'purchase_price'     => $purchase_price[$i],
                            'product_id'         => $product_id[$i],
                            'price'                 => $selling_price[$i],
                            'quantity'           => $quantity[$i],
                            'warranty_date'        => $warranty_date[$i] ? $warranty_date[$i] : null,
                            'warranty_percentage'      => $warranty_percentage[$i],
                            'deduction_amount'               => $deduction_fund[$i],
                            // 'status'      => 1
                        );

                    $this->db->insert('service_invoice_details', $details_array);
        }
        // Deduction data Add
        $value_array = array();
        $deduction_array = array();
        $percentage_array = array();
        $total_product = count($product_id);
        for ($i = 1; $i <= $total_product; $i++) {
            if (isset($_POST['value_'.$i]) && is_array($_POST['value_'.$i])) {
                $deduction_array = $_POST['deduction_id_'.$i];
                $percentage_array = $_POST['percentage_'.$i];
                $value_array = $_POST['value_'.$i];
                foreach($value_array as $key => $value)
                {
                    
                            if($value > 0)
                                {
                                    $details_array = array(
                                        'service_invoice_id' => $invoice_id,
                                        'product_id'         => $product_id[$i - 1],
                                        'deduction_id'       => $deduction_array[$key],
                                        'percentage'           => $percentage_array[$key],
                                        'amount'        => $value_array[$key],
                                        'created_date'       =>$createdate,
                                        'created_by'     =>  $createby,
                                    );


                                    $deduction_id = $deduction_array[$key];
                                    $deduction_info = $this->db->select('*')->from('fund_deductions')->where('id', $deduction_id)->get()->row();
                                   
                                    if($deduction_info->type == 'fund')
                                    {
                                        $headname = "Fund";
                                    }
                                    else{
                                        $headname = "Other Incomes";
                                    }
                                    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headname)->get()->row();
                                    $deduction_headcode = $coainfo->HeadCode;
                                    // echo "<pre>";
                                    // print_r($customer_headcode);
                                    // exit();
                                    // Deduction Account Transaction
                                    $$deduction_account_dr = array(
                                        'VNo'            =>  $invoice_id,
                                        'Vtype'          =>  'Deduction',
                                        'VDate'          =>  $invoice_date,
                                        'COAID'          =>  $deduction_headcode,
                                        'Narration'      =>  'Credit for Deduction',
                                        'Debit'          =>  0,
                                        'Credit'         =>  $value_array[$key],
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                            
                                    );
                                    $this->db->insert('acc_transaction', $deduction_account_dr);

                                }
                            $this->db->insert('service_deduction_map', $details_array);

                }   
            }
        }

            

        // Payment Data Insertion
        $bank_id = $this->input->post('bank_id_m', TRUE);
        $bkash_id = $this->input->post('bkash_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);
        $nagad_id = $this->input->post('nagad_id', TRUE);
        $rocket_id = $this->input->post('rocket_id', TRUE);
        $paid = $this->input->post('p_amount', TRUE);
        $pay_type = $this->input->post('paytype', TRUE);

        
        if (count($paid) > 0) {
            for ($i = 0; $i < count($pay_type); $i++) {

                if ($paid[$i] > 0) {
                    if ($pay_type[$i] == 1) {

                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  1020101,
                            'Narration'      =>  'Cash Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer Credit for Service',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => 1020101
                        );

                        $this->db->insert('paid_amount', $paid_amount);
                    }
                    if ($pay_type[$i] == 4) {
                        if (!empty($bank_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Bank Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer Credit for Service',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => $bankcoaid
                        );

                        $this->db->insert('paid_amount', $paid_amount);
                    }
                    if ($pay_type[$i] == 3) {
                        if (!empty($bkash_id)) {
                            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
                        } else {
                            $bkashcoaid = '';
                        }
                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $bkashcoaid,
                            'Narration'      =>  'Bkash Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                                'VNo'            =>  $invoice_id,
                                'Vtype'          =>  'Service Invoice',
                                'VDate'          =>  $invoice_date,
                                'COAID'          =>  $customer_headcode,
                                'Narration'      =>  'Customer Credit for Service',
                                'Debit'          =>  0,
                                'Credit'         =>  $paid[$i],
                                'IsPosted'       =>  1,
                                'CreateBy'       =>  $createby,
                                'CreateDate'     =>  $createdate,
                                'IsAppove'       =>  1,

                            );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => $bkashcoaid
                        );
                        $this->db->insert('paid_amount', $paid_amount);
                    }
                    if ($pay_type[$i] == 5) {

                        if (!empty($nagad_id)) {
                            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
                        } else {
                            $nagadcoaid = '';
                        }
                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $nagadcoaid,
                            'Narration'      =>  'Nagad Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                                'VNo'            =>  $invoice_id,
                                'Vtype'          =>  'Service Invoice',
                                'VDate'          =>  $invoice_date,
                                'COAID'          =>  $customer_headcode,
                                'Narration'      =>  'Customer Credit for Service',
                                'Debit'          =>  0,
                                'Credit'         =>  $paid[$i],
                                'IsPosted'       =>  1,
                                'CreateBy'       =>  $createby,
                                'CreateDate'     =>  $createdate,
                                'IsAppove'       =>  1,

                            );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => $nagadcoaid
                        );
                        $this->db->insert('paid_amount', $paid_amount);

                        
                    }
                    if ($pay_type[$i] == 7) {

                        if (!empty($rocket_id)) {
                            $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;

                            $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
                        } else {
                            $rocketcoaid = '';
                        }

                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $rocketcoaid,
                            'Narration'      =>  'Rocket Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer Credit for Service',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => $rocketcoaid
                            );
                        $this->db->insert('paid_amount', $paid_amount);
                    }
                    if ($pay_type[$i] == 6) {

                        $card_info = $CI->Settings->get_real_card_data($card_id[$i]);

                        if (!empty($card_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $paid_amount_dr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Card Received for Service',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_dr);

                        $paid_amount_cr = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'Service Invoice',
                            'VDate'          =>  $invoice_date,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer Credit for Service',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        $paid_amount = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $invoice_date,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => $bankcoaid
                            );
                        $this->db->insert('paid_amount', $paid_amount);
                        
                    }
                }
            }
        }
     // For Total Service Price
        $customer_account_dr = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'Service Invoice',
            'VDate'          =>  $invoice_date,
            'COAID'          =>  $customer_headcode,
            'Narration'      =>  'Debit for Customer',
            'Debit'          =>  $this->input->post('total_service_price',true),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $customer_account_dr);
        $service_income_cr = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'Service Invoice',
            'VDate'          =>  $invoice_date,
            'COAID'          =>  305,
            'Narration'      =>  'Service Income',
            'Debit'          =>  0,
            'Credit'         =>  $this->input->post('total_service_price',true) - $this->input->post('total_deduction',true),
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $service_income_cr);
        // For Total Discount Price
        $sale_discount_dr = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'Service Invoice',
            'VDate'          =>  $invoice_date,
            'COAID'          =>   406,
            'Narration'      =>  'Sale Discount',
            'Debit'          =>  $this->input->post('total_discount_amount',true),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $sale_discount_dr);
        $discount_cr = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'Service Invoice',
            'VDate'          =>  $invoice_date,
            'COAID'          =>  $customer_headcode,
            'Narration'      =>  'Customer Credit for Discount',
            'Debit'          =>  0,
            'Credit'         =>  $this->input->post('total_discount_amount',true),
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $discount_cr);   
       
        return $invoice_id;
    }

    public function technician_due_payment($technician_id)
    {
        $this->db->select('technician_payment.*,users.first_name,users.last_name');
        $this->db->from('technician_payment');
        $this->db->join('users', 'users.user_id=technician_payment.technician_id');
        $this->db->where('technician_payment.payment_status', 0);
        $this->db->where('technician_payment.technician_id', $technician_id);
        return $this->db->get()->result_array();
            
    }

    // Technician due Payment Entry
    public function technician_due_update_entry(){
       
        $total_pay_amount = $this->input->post('total_pay_amount', TRUE);
        $total_paid_amount = $this->input->post('paid_amount', TRUE);
        if($total_paid_amount != $total_pay_amount)
        {
            return false;
        }
        else{
             $pay_amount = $this->input->post('pay_amount', TRUE);
             $previous_paid_amount = $this->input->post('previous_paid_amount', TRUE);
             $commission_amount = $this->input->post('commission_amount', TRUE);
             $service_invoice_id = $this->input->post('service_invoice_id', TRUE);
             $createby = $this->session->userdata('user_id');
             $createdate = date('Y-m-d');
             $invoice_date = date('Y-m-d');
             // payment Coaid
              // Payment Data Insertion
        $bank_id = $this->input->post('bank_id_m', TRUE);
        $bkash_id = $this->input->post('bkash_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);
        $nagad_id = $this->input->post('nagad_id', TRUE);
        $rocket_id = $this->input->post('rocket_id', TRUE);
        $paid = $this->input->post('p_amount', TRUE);
        $pay_type = $this->input->post('pay_type', TRUE);
            if (count($paid) > 0) {
                for ($i = 0; $i < count($pay_type); $i++) {

                    if ($paid[$i] > 0) {
                        if ($pay_type[$i] == 1) {
                            $COAID = 1020101;
                        }
                        if ($pay_type[$i] == 4) {
                            if (!empty($bank_id)) {
                                $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;
                                $COAID = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                            } else {
                                $COAID = '';
                            }
                        }
                        if ($pay_type[$i] == 3) {
                            if (!empty($bkash_id)) {
                                $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;
                                $COAID = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
                            } else {
                                $COAID = '';
                            }
                        }
                        if ($pay_type[$i] == 5) {
                            if (!empty($nagad_id)) {
                                $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;
                                $COAID = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
                            } else {
                                $COAID = '';
                            } 
                        }
                        if ($pay_type[$i] == 7) {
                            if (!empty($rocket_id)) {
                                $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;
                                $COAID = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
                            } else {
                                $COAID = '';
                            }
                        }
                        if ($pay_type[$i] == 6) {
                            $card_info = $this->Settings->get_real_card_data($card_id[$i]);
                            if (!empty($card_id)) {
                                $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;
                                $COAID = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                            } else {
                                $COAID = '';
                            }
                            
                            
                        }
                    }
                }
            }
            foreach($pay_amount as $key => $value)
            {
                if($value > 0)
                {
                    $data = array(
                        'paid_amount' => $value + $previous_paid_amount[$key],
                        'due_amount' => $commission_amount[$key] - ($value + (float)$previous_paid_amount[$key]),
                        'updated_date'   => date('Y-m-d'),
                        'updated_by'       => $this->session->userdata('user_id')
                    );
                    $this->db->where('service_invoice_id', $service_invoice_id[$key]);
                    $this->db->update('technician_payment', $data);
                    // Technician Commission Insertion
                    $service_invoice_transaction = array(
                        'VNo'            =>  $service_invoice_id[$key],
                        'Vtype'          =>  'Technician Commission',
                        'VDate'          =>  $invoice_date,
                        'COAID'          =>  40111,
                        'Narration'      =>  'Technician Commission',
                        'Debit'          =>  $value,
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,
            
                    );
                    $this->db->insert('acc_transaction', $service_invoice_transaction);

                    // Payment Transaction
                    $payment_transaction = array(
                        'VNo'            =>  $service_invoice_id[$key],
                        'Vtype'          =>  'Technician Payment',
                        'VDate'          =>  $invoice_date,
                        'COAID'          =>  $COAID,
                        'Narration'      =>  'Payment for Technician',
                        'Debit'          =>  0,
                        'Credit'         =>  $value,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,
            
                    );
                    $this->db->insert('acc_transaction', $payment_transaction);
                }
                
            }
            return true;
        }
        
    }

    
    //Technician earning Report
     // Invoice Wise Item Details
     public function get_product_details($service_invoice_id) {
        $this->db->select('product_information.product_name');
        $this->db->join('product_information', 'product_information.product_id = service_invoice_details.product_id');
        $this->db->where('service_invoice_details.service_invoice_id',$service_invoice_id);
        $query = $this->db->get('service_invoice_details')->result_array();
        $products = '';
        foreach($query as $key => $value)
        {
            ($key == count($query) -1) ? $products .= $value['product_name']. "<br>" : $products .= $value['product_name']. " ,<br>";
        }
        return $products;
    }
     // Invoice Wise Item Details
     public function get_supplier_details($service_invoice_id) {
        $this->db->select('supplier_information.supplier_name');
        $this->db->join('product_purchase', 'product_purchase.purchase_id = service_invoice_details.purchase_id');
        $this->db->join('supplier_information', 'supplier_information.supplier_id = product_purchase.supplier_id');
        $this->db->where('service_invoice_details.service_invoice_id',$service_invoice_id);
        $query = $this->db->get('service_invoice_details')->result_array();
        $suppliers = '';
        foreach($query as $key => $value)
        {
            ($key == count($query) -1) ? $suppliers .= $value['supplier_name']. "<br>" : $suppliers .= $value['supplier_name']. " ,<br>";
        }
        return $suppliers;
    }
    
    public function TechnicianEarningReport($postData = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $technician_id = $this->input->post('technician_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $columnIndex = $postData['order'][0]['column']; // Column index
            $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("service_invoice.service_invoice_id,
            product_service.name as service_name,
            service_invoice.invoice_number,
            service_invoice.invoice_date,
            service_invoice.total_selling_price,
            service_invoice.total_purchase_price,
            users.first_name,users.last_name,
            service_invoice.deduction_amount,
            customer_information.customer_name,
            customer_information.customer_mobile,
            commissions.rate
            ");
            $this->db->join('users', 'users.user_id = service_invoice.technician_id', 'left');
            $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
            $this->db->join('product_service', 'product_service.id = service_invoice.service_id');
             $this->db->join('commissions', 'commissions.technician_id = service_invoice.technician_id', 'left');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            $this->db->order_by('service_invoice.invoice_number','desc');
            $this->db->group_by('service_invoice.service_invoice_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date && $from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        $base_url = base_url();

        foreach ($records as $record) {
        
            $net_amount = ($record->total_selling_price -$record->total_purchase_price)-($record->deduction_amount ? $record->deduction_amount : 0);
            if($record->rate == 0)
            {
                $amount = 0.00;
            }
            else{
                if(($record->total_purchase_price -$record->total_selling_price) == 0)
                {
                    $amount = 0.00;
                }
                else{
                    $amount =  $net_amount * ($record->rate/100);
                }
            } 
            $service_invoice_id = '<a href="' . $base_url . 'Cservice/service_invoice_print/' . $record->service_invoice_id . '" class="" >' . $record->invoice_number . '</a>';
            $data[] = array(
                'sl'            =>   $sl,
                'service_invoice_id'  =>  $service_invoice_id,
                'invoice_date'  =>  $record->invoice_date,
                'technician_name'  =>  $record->first_name. " ".$record->last_name ,
                'customer_name'  =>  $record->customer_name . " <br>". $record->customer_mobile,
                'service_name'  =>  $record->service_name,
                'item_name'  =>  $this->get_product_details($record->service_invoice_id),
                'supplier_name'  =>  $this->get_supplier_details($record->service_invoice_id),
                'total_selling_price'  =>  number_format((float)$record->total_selling_price, 2, '.', ''),
                'total_purchase_price'  =>  number_format((float)$record->total_purchase_price, 2, '.', ''),
                'gross_amount'  =>  number_format((float)($record->total_selling_price -$record->total_purchase_price), 2, '.', ''),
                'deduction_amount'  => $record->deduction_amount ? number_format((float)$record->deduction_amount, 2, '.', '') : 0.00,
                'net_amount'  => number_format((float)$net_amount, 2, '.', ''),
                'technician_percentage'  =>  $record->rate . " %",
                'net_salary'  =>  number_format((float)$amount, 2, '.', ''),
                'cpr_amount'  => number_format((float)($record->total_selling_price -$record->total_purchase_price)-($record->deduction_amount ? $record->deduction_amount : 0) - $amount, 2, '.', ''),
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }

    public function ProductPurchaseReport($postData = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }

            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $columnIndex = $postData['order'][0]['column']; // Column index
            $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('product_purchase_details.*');
            $this->db->from('product_purchase_details');
            $this->db->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id');
            
            if ($from_date != '') {
                $this->db->where('product_purchase.purchase_date >=', $from_date);
            }

            if ($to_date != '') {
                $this->db->where('product_purchase.purchase_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            if ($supplier_id != '') {
                $this->db->where('product_purchase.supplier_id', $supplier_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('product_purchase_details.*');
            $this->db->from('product_purchase_details');
            $this->db->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id');
            if ($from_date != '') {
                $this->db->where('product_purchase.purchase_date >=', $from_date);
            }

            if ($to_date != '') {
                $this->db->where('product_purchase.purchase_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            if ($supplier_id != '') {
                $this->db->where('product_purchase.supplier_id', $supplier_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("
            product_purchase.purchase_date,
            product_purchase.purchase_id,
            supplier_information.supplier_name,
            product_purchase_details.quantity,
            product_purchase_details.rate,
            product_purchase_details.total_amount,
            outlet_warehouse.outlet_name,
            product_information.product_name,
            users.first_name,users.last_name
            ");
            $this->db->join('product_information', 'product_information.product_id = product_purchase_details.product_id','left');
            $this->db->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id','left');
            $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = product_purchase.outlet_id', 'left');
            $this->db->join('users', 'users.user_id = product_purchase.created_by', 'left');
            $this->db->join('supplier_information', 'supplier_information.supplier_id = product_purchase.supplier_id', 'left');
            $this->db->from('product_purchase_details');
            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->limit($rowperpage, $start);
            if ($from_date != '') {
                $this->db->where('product_purchase.purchase_date >=', $from_date);
            }

            if ($to_date != '') {
                $this->db->where('product_purchase.purchase_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            if ($supplier_id != '') {
                $this->db->where('product_purchase.supplier_id', $supplier_id);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
           
        $data = array();
        $sl = 1;
        $base_url = base_url();

        foreach ($records as $record) {
             $purchase_id = '<a href="' . $base_url . 'Cpurchase/purchase_details_data/' . $record->purchase_id . '" class="" >' . $record->purchase_id . '</a>';
            $data[] = array(
                'sl'            =>   $sl,
                'outlet_name'  =>  $record->outlet_name ? $record->outlet_name : "Central Warehouse",
                'purchase_date'  =>  $record->purchase_date,
                'purchase_id'  =>  $purchase_id,
                'supplier_name'  =>  $record->supplier_name,
                'product_name'  =>  $record->product_name,
                'quantity'  =>  $record->quantity ? $record->quantity : 0.00,
                'rate'  =>  $record->rate ? $record->rate : 0.00,
                'total_amount'  => $record->total_amount ? $record->total_amount : 0.00,
                'purchased_by'  =>  $record->first_name . " ". $record->last_name,

            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }

    public function Service_Stock_Report($postData = null)
    {
        $this->load->library('occational');
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        // Outlet ID
        if ($this->input->post('outlet_id')) {
            $outlet_id = $this->input->post('outlet_id');
            if ($outlet_id === 'All') {
                $outlet_id = null;
            } else {
                $outlet_id = $this->input->post('outlet_id');
            }
        } else {
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $response = array();
        $type = 1;

        $product_type = $this->input->post('product_status', TRUE);
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $purchase_id = $this->input->post('purchase_id', TRUE);
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
            $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');
            if ($supplier_id != '') {
                $this->db->where_in('pro.supplier_id', $supplier_id);
            }
            if ($from_date) {
                $this->db->where('pro.purchase_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('pro.purchase_date <=', $to_date);
            }
            if ($purchase_id) {
                $this->db->where('p.purchase_id', $purchase_id);
            }
            if (isset($product_type) && $product_type != '') {
                $this->db->where('a.finished_raw', $product_type);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            $this->db->where('pro.is_delete', 0);
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));
            $this->db->order_by('a.sku', 'asc');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;


            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
            $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');
            if ($supplier_id != '') {
                $this->db->where_in('pro.supplier_id', $supplier_id);
            }
            if ($from_date) {
                $this->db->where('pro.purchase_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('pro.purchase_date <=', $to_date);
            }
            if ($purchase_id) {
                $this->db->where('p.purchase_id', $purchase_id);
            }
            if (isset($product_type) && $product_type != '') {
                $this->db->where('a.finished_raw', $product_type);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            $this->db->where('pro.is_delete', 0);
            $this->db->order_by('a.sku', 'asc');
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));

            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;

        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                a.price,
                b.name,
                p.purchase_id,
                p.rate,
                p.expired_date,
                sup.supplier_name,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
        $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');
        //        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->order_by('p.product_id', 'asc');
        $this->db->group_by(array('p.purchase_id', 'a.product_id'));
        $this->db->limit($rowperpage, $start);
       
        if ($supplier_id != '') {
            $this->db->where_in('pro.supplier_id', $supplier_id);
        }
        if ($from_date) {
            $this->db->where('pro.purchase_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('pro.purchase_date <=', $to_date);
        }
        if ($purchase_id) {
            $this->db->where('p.purchase_id', $purchase_id);
        }
        if (isset($product_type) && $product_type != '') {
            $this->db->where('a.finished_raw', $product_type);
        }
        $this->db->where('p.qty >', 0);
        $this->db->where('pro.is_delete', 0);
        $records = $this->db->get()->result();



        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;
        // echo "<pre>";
        // print_r($records);
        // exit();
        foreach ($records as $record) {
            $purchase_id = $record->purchase_id;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            if ($purchase_id) {
                $this->db->where('product_purchase.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
                ->where('product_purchase_details.qty >', 0)
                 ->where('product_purchase.is_delete', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            $total_in = '';
            if ($type == 1) {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0) + (!empty($total_rqsn_transfer_taken->total_rqsn_transfer) ? $total_rqsn_transfer_taken->total_rqsn_transfer : 0);
            } else {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0);
            }


            if ($purchase_id) {
                $this->db->where('product_return.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')
                ->from('product_return')
                ->where('usablity', 2)
                ->where('product_id', $record->product_id)
                ->where('customer_id', '')
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_return_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return !=', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }

            $total_rqsn_return_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->get()
                ->row();

            if ($purchase_id) {
                $this->db->where('a.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $total_sale = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where(array('a.product_id' => $record->product_id))
                ->get()
                ->row();


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($total_purchase->purchaseprice) ? sprintf('%0.2f', $total_purchase->purchaseprice) : 0);

            $total_damage = (!empty($total_purchase->damaged_qty) ?  $total_purchase->damaged_qty : 0);


            $total_out = '';

            if ($record->finished_raw == 1) {
                // echo "<pre>";
                // print_r("yyymmmmyy");
                // exit();
                if ($type == 1) {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
                } else {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                }
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                    
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $sold_qnty = '';


            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $sold_qnty = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                } else {
                    $sold_qnty = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                }
            }
            else{
                $sold_item = $this->db->select('SUM(service_invoice_details.quantity) as sold')
                    ->from('service_invoice_details')
                    ->join('service_invoice', 'service_invoice.service_invoice_id=service_invoice_details.service_invoice_id', 'left')
                    ->where(array('service_invoice_details.product_id' => $record->product_id, 'service_invoice_details.purchase_id' => $record->purchase_id))
                    ->where('service_invoice.is_delete', 0)
                    ->group_by(array('service_invoice_details.product_id', 'service_invoice_details.purchase_id'))
                    ->get()
                    ->row();
                $sold_qnty = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($sold_item) ? $sold_item->sold : 0);
            }
            

            $total_transfer = '';

            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $total_transfer = (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
                }
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                    
                $total_transfer = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $stock = ($total_in - $sold_qnty - $total_transfer - $total_damage);

            $date_now = date("Y-m-d");
            $expired_date   = date($record->expired_date);


            if ($date_now > $expired_date) {
                $expiry_status = '<span class="label label-danger ">Expired</span>';
            } else {
                $expiry_status = '<span class="label label-success ">Available</span>';
            }


            $closing_stock = $stock;


            //            if ($closing_stock > 0){
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'expiry_status'  =>  $expiry_status,
                'purchase_id'  =>  $record->purchase_id,
                'supplier_name'  =>  $record->supplier_name,
                'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
                'expired_date'  =>  $record->expired_date,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'product_id' => ($record->product_id ? $record->product_id : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $record->rate,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $total_purchase->damaged_qty,
                'totalSalesQnty' =>  $total_out,
                'sold_qnty' =>  $sold_qnty,
                'total_transfer' =>  $total_transfer,
                'stok_quantity' => sprintf('%0.2f', ($closing_stock)),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,
                'purchase_total' => (($closing_stock * $record->rate) != 0) ? ($closing_stock * $record->rate) : 0,
                'sold_value' => (($sold_qnty * $record->rate) != 0) ? ($sold_qnty * $record->rate) : 0,

            );
            $sl++;
        }

       
            $response = array(
                "purchase_id" => $data[0]['purchase_id'],
                "central_stock" => $stock,
                "aaData" =>  $data,
            );
        
        return $response;
    }
    public function ajaxWarrantyReport($postData = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $technician_id = $this->input->post('technician_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $columnIndex = $postData['order'][0]['column']; // Column index
            $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("service_invoice.service_invoice_id,
            service_invoice.invoice_date,
            customer_information.customer_name,
            customer_information.customer_mobile,
            technician.first_name,technician.last_name,
            product_service.name as service_name,
            service_invoice.invoice_number,
            service_invoice.total_selling_price,
            service_invoice.total_purchase_price,
            service_invoice.deduction_amount,
            service_invoice_details.warranty_date
            ");
            $this->db->join('service_invoice_details', 'service_invoice_details.service_invoice_id = service_invoice.service_invoice_id');
            $this->db->join('users as technician', 'technician.user_id = service_invoice.technician_id', 'left');
            $this->db->join('customer_information', 'customer_information.customer_id = service_invoice.customer_id', 'left');
            $this->db->join('product_service', 'product_service.id = service_invoice.service_id');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            $this->db->where('service_invoice.deduction_amount > ', 0);
            $this->db->order_by('service_invoice.invoice_number','desc');
            $this->db->group_by('service_invoice.service_invoice_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date && $from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        $base_url = base_url();

        foreach ($records as $record) {
            $today = new DateTime();
            $current_date = date('Y-m-d');
            $specifiedDate = new DateTime($record->warranty_date);
            $interval = $today->diff($specifiedDate);
           $daysDifference = $interval->days;
            $deduduction_details = $this->getDeductionDetails($record->service_invoice_id);
            $resultString = '';
            if(count($deduduction_details) > 0)
            {
                foreach ($deduduction_details as $key => $item) {
                    $resultString .= $item['name'] . '('.$item['percentage']. ' %)';                    
                }
            }
            $service_invoice_id = '<a href="' . $base_url . 'Cservice/service_invoice_print/' . $record->service_invoice_id . '" class="" >' . $record->invoice_number . '</a>';
            $data[] = array(
                'sl'            =>   $sl,
                'service_invoice_id'  =>  $service_invoice_id,
                'invoice_date'  =>  $record->invoice_date,
                'technician_name'  =>  $record->first_name . " ".$record->last_name ,
                'customer_name'  =>  $record->customer_name . "<br>". $record->customer_mobile,
                'service_name'  =>  $record->service_name,
                'supplier_name'  =>  $this->get_supplier_details($record->service_invoice_id),
                'item_name'  =>  $this->get_product_details($record->service_invoice_id),
                'total_selling_price'  =>  $record->total_selling_price,
                'deduction'  => $resultString,
                'deduction_amount'  => $record->deduction_amount,
                'expired_date'  =>  $record->warranty_date,
                'due_date'  => $current_date > $record->warranty_date ? "Expired" : ($daysDifference > 1 ?  $daysDifference.' Days' : 'Day'),
                'status'  => $current_date > $record->warranty_date ? "Expired" : (($current_date < $record->warranty_date) ?  "Due" : 'Claimed')
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    public function ajaxSalesReport($postData = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        // $technician_id = $this->input->post('technician_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $columnIndex = $postData['order'][0]['column']; // Column index
            $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            // if ($technician_id != '') {
            //     $this->db->where('service_invoice.technician_id', $technician_id);
            // }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('*');
            $this->db->from('service_invoice');
            $this->db->where('service_invoice.is_delete', 0);
            if ($from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }

            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('service_invoice.outlet_id', $outlet_id);
            }
            // if ($technician_id != '') {
            //     $this->db->where('service_invoice.technician_id', $technician_id);
            // }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select('service_invoice.invoice_date,service_invoice.invoice_number,service_invoice.service_invoice_id,service_invoice.due_amount,service_invoice.outlet_id,
        (SELECT sum(id.quantity) from service_invoice_details as id WHERE id.service_invoice_id = service_invoice.service_invoice_id) as total_product,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "1" AND paid.invoice_id = service_invoice.service_invoice_id) as total_cash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "3" AND paid.invoice_id = service_invoice.service_invoice_id) as total_bkash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "6" AND paid.invoice_id = service_invoice.service_invoice_id) as total_card,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "5" AND paid.invoice_id = service_invoice.service_invoice_id) as total_nagad,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "7" AND paid.invoice_id = service_invoice.service_invoice_id) as total_rocket,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "2" AND paid.invoice_id = service_invoice.service_invoice_id) as total_cheque,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "4" AND paid.invoice_id = service_invoice.service_invoice_id) as total_bank,
        service_invoice.total_discount,service_invoice.total_amount,service_invoice.paid_amount,service_invoice.service_price,service_invoice.invoice_number,b.customer_id,b.customer_name,b.customer_mobile,pi.product_id,pi.product_name');
        $this->db->from('service_invoice');
        $this->db->join('customer_information b', 'b.customer_id = service_invoice.customer_id', 'left');
        $this->db->join('paid_amount p', 'p.invoice_id = service_invoice.service_invoice_id', 'left');
        $this->db->join('service_invoice_details id', 'id.service_invoice_id = service_invoice.service_invoice_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
        $this->db->where('service_invoice.invoice_date >=', $from_date);
        $this->db->where('service_invoice.invoice_date <=', $to_date);
        if ($outlet_id) {
            $this->db->where('service_invoice.outlet_id', $outlet_id);
        }
        $this->db->where('service_invoice.is_delete', 0);
        $this->db->order_by('service_invoice.invoice_date', 'desc');
        $this->db->order_by('service_invoice.invoice_number', 'desc');
        $records = $this->db->get()->result_array();
       
        $final_array = array();
        foreach ($records as $key => $value) {

            $test_array[$value['service_invoice_id']] = $value['service_invoice_id'];
            $final_array[$value['service_invoice_id']]['invoice_date'] = $value['invoice_date'];
            $final_array[$value['service_invoice_id']]['invoice_number'] = $value['invoice_number'];
            $final_array[$value['service_invoice_id']]['service_invoice_id'] = $value['service_invoice_id'];
            $final_array[$value['service_invoice_id']]['outlet_id'] = $value['outlet_id'];
            $final_array[$value['service_invoice_id']]['customer_name'] = $value['customer_name'] ."<br>".$value['customer_mobile'];
            if (array_key_exists($value['service_invoice_id'], $test_array)) {
                $final_array[$value['service_invoice_id']]['product_id'] = $final_array[$value['service_invoice_id']]['product_name'] . "," . $value['product_name'];
            } else {
                $final_array[$value['service_invoice_id']]['product_id'] = $value['product_name'] . ",";
            }
            $final_array[$value['service_invoice_id']]['bkash'] = $value['total_bkash'];
            $final_array[$value['service_invoice_id']]['cash'] = $value['total_cash'];
            $final_array[$value['service_invoice_id']]['nagad'] = $value['total_nagad'];
            $final_array[$value['service_invoice_id']]['card'] = $value['total_card'];
            $final_array[$value['service_invoice_id']]['rocket'] = $value['total_rocket'];
            $final_array[$value['service_invoice_id']]['cheque'] = $value['total_cheque'];
            $final_array[$value['service_invoice_id']]['bank'] = $value['total_bank'];
            $final_array[$value['service_invoice_id']]['total_amount'] = $value['total_amount'];
            $final_array[$value['service_invoice_id']]['due_amount'] = $value['due_amount'];
            $final_array[$value['service_invoice_id']]['total_discount'] = $value['total_discount'];
            $final_array[$value['service_invoice_id']]['net_sales'] = $value['total_amount'] - $value['total_discount'];
            $final_array[$value['service_invoice_id']]['total_product'] = $value['total_product'];
            $final_array[$value['service_invoice_id']]['paid_amount'] = $value['paid_amount'];
            $final_array[$value['service_invoice_id']]['service_price'] = $value['service_price'];
        }

        if ($records) {
            $final_array = array_values($final_array);
        }
        $data = array();
        $sl = 1;
        $base_url = base_url();
        // echo "<pre>";
        // print_r($final_array);
        // exit();
        foreach ($final_array as $record) {

            $service_invoice_id = '<a href="' . $base_url . 'Cservice/service_invoice_print/' . $record['service_invoice_id'] . '" class="" >' . $record['invoice_number'] . '</a>';
            $data[] = array(
                'sl'            =>   $sl,
                'invoice_date'  =>  $record['invoice_date'],
                'invoice_number'  =>  $service_invoice_id,
                'customer_name'  =>  $record['customer_name'],
                'product_id'  =>  ltrim($record['product_id'], ','),
                'quantity'  =>  $record['total_product'],
                'discount'  =>  $record['total_discount'],
                'service_charge'  =>  $record['service_price'],
                'net_sales'  =>  $record['net_sales'],
                'due'  => $record['due_amount'] ? $record['due_amount'] : 0.00,
                'cash'  =>  $record['cash'] ? $record['cash'] : 0.00,
                'cheque'  =>  $record['cheque'] ? $record['cheque'] : 0.00,
                'bank'  => $record['bank'] ? $record['bank'] : 0.00,
                'card'  =>  $record['card'] ? $record['card'] : 0.00,
                'bkash'  => $record['bkash'] ? $record['bkash'] : 0.00,
                'rocket'  =>  $record['rocket'] ? $record['rocket'] : 0.00,
                'total_received_amount'  =>  000000000000000,
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    public function TechnicianPaymentReport($postData = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $technician_id = $this->input->post('technician_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $columnIndex = $postData['order'][0]['column']; // Column index
            $columnName = $postData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            $searchValue = $postData['search']['value']; // Search value

            ## Search
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('users.*,user_login.*');
            $this->db->from('users');
            $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
            $this->db->where('user_login.user_type', 3);
            
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('users.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('users.user_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('users.*,user_login.*');
            $this->db->from('users');
            $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
            $this->db->where('user_login.user_type', 3);
            
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('users.outlet_id', $outlet_id);
            }
            if ($technician_id != '') {
                $this->db->where('users.user_id', $technician_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("
            users.first_name,users.last_name,
            sum(service_invoice.total_selling_price) as total_selling_price,
            sum(service_invoice.total_purchase_price) as total_purchase_price,
            sum(service_invoice.deduction_amount) as deduction_amount,
            outlet_warehouse.outlet_name,
            commissions.rate
            ");
            $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
            $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = users.outlet_id','left');
            $this->db->join('service_invoice', 'service_invoice.technician_id = users.user_id', 'left');
            $this->db->join('commissions', 'commissions.technician_id = service_invoice.technician_id', 'left');
            $this->db->from('users');
            $this->db->where('user_login.user_type', 3);
            // $this->db->where('service_invoice.is_delete', 0);
            $this->db->group_by('users.user_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date && $from_date != '') {
                $this->db->where('service_invoice.invoice_date >=', $from_date);
            }
            if ($technician_id != '') {
                $this->db->where('service_invoice.technician_id', $technician_id);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('service_invoice.invoice_date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('users.outlet_id', $outlet_id);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        $base_url = base_url();

        foreach ($records as $record) {
        
            $net_amount = ($record->total_selling_price -$record->total_purchase_price)-($record->deduction_amount ? $record->deduction_amount : 0);
            if($record->rate == 0)
            {
                $amount = 0.00;
            }
            else{
                if(($record->total_purchase_price -$record->total_selling_price) == 0)
                {
                    $amount = 0.00;
                }
                else{
                    $amount =  $net_amount * ($record->rate/100);
                }
            } 
            $data[] = array(
                'sl'            =>   $sl,
                'technician_name'  =>  $record->first_name. " ".$record->last_name ,
                'outlet_name'  =>  $record->outlet_name ? $record->outlet_name : "Central Warehouse",
                'total_selling_price'  =>  number_format((float)$record->total_selling_price, 2, '.', ''),
                'total_purchase_price'  =>  number_format((float)$record->total_purchase_price, 2, '.', ''),
                'gross_amount'  =>  number_format((float)($record->total_selling_price -$record->total_purchase_price), 2, '.', ''),
                'deduction_amount'  => $record->deduction_amount ? number_format((float)$record->deduction_amount, 2, '.', '') : 0.00,
                'net_amount'  => number_format((float)$net_amount, 2, '.', ''),
                'technician_percentage'  =>  $record->rate . " %",
                'net_salary'  =>  number_format((float)$amount, 2, '.', ''),
                'cpr_amount'  => number_format((float)($record->total_selling_price -$record->total_purchase_price)-($record->deduction_amount ? $record->deduction_amount : 0) - $amount, 2, '.', ''),
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function service_detail($service_id)
    {
        $this->db->select('*');
        $this->db->from('product_service');
        $this->db->where('id', $service_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }

    //customer List
    public function service_list() {
        $this->db->select('*');
        $this->db->from('product_service');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //customer List
    public function service_list_product() {
        $this->db->select('*');
        $this->db->from('product_service');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //customer List
    public function service_list_count() {
        $this->db->select('*');
        $this->db->from('product_service');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //service Search Item
    public function service_search_item($service_id) {
        $this->db->select('*');
        $this->db->from('product_service');
        $this->db->where('service_id', $service_id);
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Count customer
    public function service_entry($data) {
        $this->db->select('*');
        $this->db->from('product_service');
        $this->db->where('service_name', $data['service_name']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('product_service', $data);
            $this->db->select('*');
            $this->db->from('product_service');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_service[] = array('label' => $row->service_name, 'value' => $row->service_id);
            }
            $cache_file = './my-assets/js/admin_js/json/service.json';
            $serviceList = json_encode($json_service);
            file_put_contents($cache_file, $serviceList);
            return TRUE;
        }
    }

    //Retrieve customer Edit Data
    public function retrieve_service_editdata($service_id) {
        $this->db->select('*');
        $this->db->from('product_service');
        $this->db->where('service_id', $service_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Update Categories
    public function update_service($data, $service_id) {
        $this->db->where('service_id', $service_id);
        $this->db->update('product_service', $data);
            $this->db->select('*');
            $this->db->from('product_service');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_service[] = array('label' => $row->service_name, 'value' => $row->service_id);
            }
            $cache_file = './my-assets/js/admin_js/json/service.json';
            $serviceList = json_encode($json_service);
            file_put_contents($cache_file, $serviceList);
        return true;
    }

    // Delete customer Item
    public function delete_service($service_id) {
        $this->db->where('service_id', $service_id);
        $this->db->delete('product_service');
            $this->db->select('*');
            $this->db->from('product_service');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_service[] = array('label' => $row->service_name, 'value' => $row->service_id);
            }
            $cache_file = './my-assets/js/admin_js/json/service.json';
            $serviceList = json_encode($json_service);
            file_put_contents($cache_file, $serviceList);
        return true;
    }

        public function get_total_service_invoic($service_id) {
      
        $this->db->select('*');
        $this->db->from('product_service');
        $this->db->where(array('service_id' => $service_id));
        $serviceinfo = $this->db->get()->row();

        $CI = & get_instance();
        $CI->load->model('Web_settings');
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $tablecolumn = $this->db->list_fields('product_service');
               $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $data2[$taxfield]=$serviceinfo->$taxfield;
    $taxvar[$i] = $serviceinfo->$taxfield;
    $data2['taxdta'] = $taxvar;
    //
   } $data2['txnmber'] = $num_column;
     $data2['price'] = $serviceinfo->charge;
     $data2['discount_type'] = $currency_details[0]['discount_type'];

        return $data2;
    }
    // Employee list
    public function employee_list(){
        return $list = $this->db->select('*')->from('employee_history')->get()->result_array();
    }
     public function number_generator() {
        $this->db->select_max('voucher_no', 'voucher_no');
        $query = $this->db->get('service_invoice');
        $result = $query->result_array();
        $voucher_no = $result[0]['voucher_no'];
        if ($voucher_no != '') {
            $voucher_no = $voucher_no + 1;
        } else {
            $voucher_no = 1000;
        }
        return $voucher_no;
    }
          // Service invoice list
            public function service_invoice_list($limit = null, $start = null)
    {
             return $this->db->select('a.*,b.customer_name')   
            ->from('service_invoice a')
            ->join('customer_information b','b.customer_id=a.customer_id','left')
            ->order_by('a.id', 'desc')
            ->limit($limit, $start)
            ->get()
            ->result_array();
    }

    // Service Invoice Delete
    public function delete_service_invoice($invoice_id){
        //service invoice delete
      $this->db->where('voucher_no', $invoice_id);
        $this->db->delete('service_invoice');
       //service invoice details delete
        $this->db->where('service_inv_id', $invoice_id);
        $this->db->delete('service_invoice_details');
        //acc transaction delete
         $this->db->where('VNo', $invoice_id);
        $this->db->delete('acc_transaction');
    }
    // Service Invoice Update Information
    public function service_invoice_updata($invoice_id){
      return $this->db->select('a.*,b.*,c.service_name,d.*,e.*')
            ->from('service_invoice a')
            ->join('service_invoice_details b','b.service_inv_id=a.voucher_no','left')
            ->join('product_service c','c.service_id=b.service_id','left')
            ->join('bank_add d','d.bank_id=a.bank_id','left')
            ->join('bkash_add e','e.bkash_id=a.bkash_id','left')
            ->where('a.voucher_no',$invoice_id)
            ->order_by('b.id', 'asc')
            ->get()
            ->result_array(); 
    }

    // customer information 
    public function customer_info($customer_id){
        return $this->db->select('*')
                    ->from('customer_information')
                    ->where('customer_id',$customer_id)
                    ->get()
                    ->row();
    }

    // tax for service information
    public function service_invoice_taxinfo($invoice_id){
       return $this->db->select('*')   
            ->from('tax_collection')
            ->where('relation_id',$invoice_id)
            ->get()
            ->result_array(); 
    }

    public function invoice_update(){


         $tablecolumn = $this->db->list_fields('tax_collection');
               $num_column = count($tablecolumn)-4;
        $invoice_id = $this->input->post('invoice_id',true);
        $employee = $this->input->post('employee_id',true);
        $employee_id = implode(',' , $employee);
        $createby=$this->session->userdata('user_id');
        $createdate=date('Y-m-d H:i:s');
        $bank_id = $this->input->post('bank_id_m',TRUE);
        if(!empty($bank_id)){
            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;

            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
        }else{
            $bankcoaid='';
        }

        $bkash_id = $this->input->post('bkash_id',TRUE);
        if(!empty($bkash_id)){
            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id',$bkash_id)->get()->row()->bkash_no;

            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bkashname)->get()->row()->HeadCode;
        }else{
            $bkashcoaid='';
        }
        if(!empty($invoice_id)){
            //Customer Ledger
        // Account Transaction
        $this->db->where('VNo', $invoice_id);
        $this->db->delete('acc_transaction');
        //Service Invoice Details
        
        $this->db->where('service_inv_id', $invoice_id);
        $this->db->delete('service_invoice_details');
        //tax_collection
        $this->db->where('relation_id', $invoice_id);
        $this->db->delete('tax_collection');
        }

        if (($this->input->post('customer_name_others',true) == null) && ($this->input->post('customer_id',true) == null ) && ($this->input->post('customer_name',true) == null )) {
            $this->session->set_userdata(array('error_message' => display('please_select_customer')));
            redirect(base_url() . 'Cservice/service_invoice_form');
        }
         if ($this->input->post('employee_id') == null ) {
            $this->session->set_userdata(array('error_message' => display('please_select_employee')));
            redirect(base_url() . 'Cservice/service_invoice_form');
        }


        if (($this->input->post('customer_id') == null ) && ($this->input->post('customer_name') == null )) {
            $customer_id = $this->auth->generator(15);
            //Customer  basic information adding.
             $coa = $this->headcode();
           if($coa->HeadCode!=NULL){
                $headcode=$coa->HeadCode+1;
           }else{
                $headcode="102030101";
            }
             $c_acc=$customer_id.'-'.$this->input->post('customer_name_others',true);
            $createby=$this->session->userdata('user_id');
            $createdate=date('Y-m-d H:i:s');
            $data = array(
                'customer_id'      => $customer_id,
                'customer_name'    => $this->input->post('customer_name_others',true),
                'customer_address' => $this->input->post('customer_name_others_address',true),
                'customer_mobile'  => $this->input->post('customer_mobile',true),
                'customer_email'   => "",
                'status'           => 2
            );
           $customer_coa = [
             'HeadCode'         => $headcode,
             'HeadName'         => $c_acc,
             'PHeadName'        => 'Customer Receivable',
             'HeadLevel'        => '4',
             'IsActive'         => '1',
             'IsTransaction'    => '1',
             'IsGL'             => '0',
             'HeadType'         => 'A',
             'IsBudget'         => '0',
             'IsDepreciation'   => '0',
             'DepreciationRate' => '0',
             'CreateBy'         => $createby,
             'CreateDate'       => $createdate,
        ];

            $this->db->insert('customer_information', $data);
            $this->db->insert('acc_coa',$customer_coa);
            $this->db->select('*');
            $this->db->from('customer_information');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);
            }
            $cache_file = './my-assets/js/admin_js/json/customer.json';
            $customerList = json_encode($json_customer);
            file_put_contents($cache_file, $customerList);


            //Previous balance adding -> Sending to customer model to adjust the data.
            $this->Customers->previous_balance_add(0, $customer_id);
        } else {
            $customer_id = $this->input->post('customer_id');
        }


        //Full or partial Payment record.
        $paid_amount = $this->input->post('paid_amount',true);
        if ($this->input->post('paid_amount',true) >= 0) {

            $this->db->set('status', '1');
            $this->db->where('customer_id', $customer_id);

            $this->db->update('customer_information');

            $transection_id = $this->auth->generator(15);


        

           
            // Inserting for Accounts adjustment.
            ############ default table :: customer_payment :: inflow_92mizdldrv #################
        }

        //Data inserting into invoice table
        $datainv = array(
            'employee_id'     => $employee_id,
            'customer_id'     => $customer_id,
            'date'            => (!empty($this->input->post('invoice_date',true))?$this->input->post('invoice_date',true):date('Y-m-d')),
            'total_amount'    => $this->input->post('grand_total_price',true),
            'total_tax'       => $this->input->post('total_tax_amount',true),
            'voucher_no'      => $invoice_id,
            'details'         => (!empty($this->input->post('inva_details',true))?$this->input->post('inva_details',true):'Service Invoice'),
            'invoice_discount'=> $this->input->post('invoice_discount',true),
            'total_discount'  => $this->input->post('total_discount',true),
            'shipping_cost'   => $this->input->post('shipping_cost',true),
            'paid_amount'     => $this->input->post('paid_amount',true),
            'due_amount'      => $this->input->post('due_amount',true),
            'previous'        => $this->input->post('previous',true),
            'paytype'        => $this->input->post('paytype',true),
            'bank_id'        => $this->input->post('bank_id_m',true),
            'bkash_id'        => $this->input->post('bkash_id',true),


        );

         $this->db->where('voucher_no', $invoice_id);
        $this->db->update('service_invoice',$datainv);


   $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
    $headn = $customer_id.'-'.$cusifo->customer_name;
    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
    $customer_headcode = $coainfo->HeadCode;
// Cash in Hand debit
      $cc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'SERVICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand For SERVICE No'.$invoice_id,
      'Debit'          =>  $this->input->post('paid_amount',true),
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    );
        $bankc = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'SERVICE',
            'VDate'          =>  $createdate,
            'COAID'          =>  $bankcoaid,
            'Narration'      =>  'Cash in bank For SERVICE No '.$invoice_id,
            'Debit'          =>  $this->input->post('paid_amount',true),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );

        $bkashc = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'INVOICE',
            'VDate'          =>  $createdate,
            'COAID'          =>  $bkashcoaid,
            'Narration'      =>   'Cash in bkash For SERVICE No '.$invoice_id,
            'Debit'          =>  $this->input->post('paid_amount',true),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );

//service income
$service_income = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'SERVICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  304,
      'Narration'      =>  'Service Income For SERVICE No'.$invoice_id,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('grand_total_price',true),
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    );

$this->db->insert('acc_transaction',$service_income);

    //Customer debit for service Value
    $cosdr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'SERVICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer debit For service No'.$invoice_id,
      'Debit'          =>  $this->input->post('grand_total_price',true),
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$cosdr);

       ///Customer credit for Paid Amount
       $coscr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'SERVICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer credit for Paid Amount For Service No'.$invoice_id,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('paid_amount',true),
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    );
        if(!empty($this->input->post('paid_amount',true))){
            $this->db->insert('acc_transaction',$coscr);
            if($this->input->post('paytype',TRUE) == 1){
                $this->db->insert('acc_transaction',$cc);
            }
            if($this->input->post('paytype',TRUE) == 4){
                $this->db->insert('acc_transaction',$bankc);
            }
            if($this->input->post('paytype',TRUE) == 3){
                $this->db->insert('acc_transaction',$bkashc);
            }


        }
   
        $quantity            = $this->input->post('product_quantity',true);
        $rate                = $this->input->post('product_rate',true);
        $serv_id             = $this->input->post('service_id',true);
        $total_amount        = $this->input->post('total_price',true);
        $discount_rate       = $this->input->post('discount_amount',true);
        $discount_per        = $this->input->post('discount',true);
        $tax_amount          = $this->input->post('tax',true);
        $invoice_description = $this->input->post('desc',true);

        for ($i = 0, $n   = count($serv_id); $i < $n; $i++) {
            $service_qty  = $quantity[$i];
            $product_rate = $rate[$i];
            $service_id   = $serv_id[$i];
            $total_price  = $total_amount[$i];
            $disper       = $discount_per[$i];
            $disamnt      = $discount_rate[$i];
           
            $service_details = array(
                'service_inv_id'     => $invoice_id,
                'service_id'         => $service_id,
                'qty'                => $service_qty,
                'charge'             => $product_rate,
                'discount'           => $disper,
                'discount_amount'    => $disamnt,
                'total'              => $total_price,
            );
            if (!empty($quantity)) {
                $this->db->insert('service_invoice_details', $service_details);
            }
           

        }
         for($j=0;$j<$num_column;$j++){
                $taxfield = 'tax'.$j;
                $taxvalue = 'total_tax'.$j;
              $taxdata[$taxfield]=$this->input->post($taxvalue);
            }
            $taxdata['customer_id'] = $customer_id;
            $taxdata['date']        = (!empty($this->input->post('invoice_date'))?$this->input->post('invoice_date'):date('Y-m-d'));
            $taxdata['relation_id'] = $invoice_id;
            $this->db->insert('tax_collection',$taxdata);

        $message = 'Mr.'.$cusifo->customer_name.',
        '.'Your Service Charge '.$this->input->post('grand_total_price').' You have paid .'.$this->input->post('paid_amount');
        if($config_data->isservice == 1){
          $this->smsgateway->send([
            'apiProvider' => 'nexmo',
            'username'    => $config_data->api_key,
            'password'    => $config_data->api_secret,
            'from'        => $config_data->from,
            'to'          => $cusifo->customer_mobile,
            'message'     => $message
        ]);
      }
        return $invoice_id;

    }



    public function employees(){
        $this->db->select('*');
        $this->db->from('employee_history');
        $query = $this->db->get();
        $data = $query->result();
       
        $list[''] = 'Select Employee';
        if (!empty($data) ) {
          foreach ($data as $value) {
            $list[$value->id] = $value->first_name.' '.$value->last_name;
          } 
        }
        return $list;

}
   
}
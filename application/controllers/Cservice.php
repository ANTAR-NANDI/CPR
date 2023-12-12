<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cservice extends CI_Controller {

    public $menu;

    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lservice');
        $this->load->library('session');
        $this->load->model('Service');
        $this->auth->check_admin_auth();
    }

    //Add Service Category
        public function add_category() {
            $content = $this->lservice->add_category_form();
            $this->template->full_admin_html_view($content);
        }
    //Insert Service Category
        public function insert_service_category() {
            $data = array(
                'name' => $this->input->post('category_name',true),
                'status' => 1,
                'created_date'   => date('Y-m-d'),
                'created_by'       => $this->session->userdata('user_id'),

            );
            $result = $this->db->insert('service_category', $data);
    
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                    redirect(base_url('Cservice/manage_category'));
                
            } else {
                $this->session->set_userdata(array('error_message' => display('already_inserted')));
                redirect(base_url('Cservice/add_category'));
            }
        }
    //Manage Service Category
        public function manage_category() {
            $content = $this->lservice->category_list();
            $this->template->full_admin_html_view($content);
            
        }
    // Service Category List
        public function Category_List()
        {
            $this->load->model('Service');
            $postData = $this->input->post();
            $data = $this->Service->getCategoryList($postData);
            echo json_encode($data);
        }
     //Service Category Edit Form
        public function service_category_update_form($category_id)
        {
            $content = $this->lservice->category_edit_data($category_id);
            $this->template->full_admin_html_view($content);
        }
     //Service Category Update
        public function update_service_category()
        {
            $category_id = $this->input->post('category_id', TRUE);
            $data = array(
                'name' => $this->input->post('category_name', TRUE),
                'updated_date'   => date('Y-m-d'),
                'updated_by'       => $this->session->userdata('user_id')
            );
            $this->db->where('id', $category_id);
            $result = $this->db->update('service_category', $data);
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect(base_url('Cservice/manage_category'));
                exit;
            } else {
                $this->session->set_userdata(array('error_message' => display('please_try_again')));
                redirect(base_url('Cservice'));
            }
        }
     //Service Category Delete
        public function service_category_delete($category_id)
        {
            $this->db->where('id', $category_id);
            $this->db->delete('service_category');
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('Cservice/manage_category'));
            
        }
    //Service Add Form
        public function index() {
            $content = $this->lservice->service_add_form();
            $this->template->full_admin_html_view($content);
        }
      //Insert Service 
        public function insert_service() {
            $data = array(
                'name' => $this->input->post('service_name',true),
                'category_id' => $this->input->post('category_id',true),
                'status' => 1,
                'created_date'   => date('Y-m-d'),
                'created_by'       => $this->session->userdata('user_id'),

            );
            $result = $this->db->insert('product_service', $data);
    
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                    redirect(base_url('Cservice/manage_service'));
            
            } else {
                $this->session->set_userdata(array('error_message' => display('already_inserted')));
                redirect(base_url('Cservice'));
            }
        }

    //Manage Service 
        public function manage_service() {
            $content = $this->lservice->service_list();
            $this->template->full_admin_html_view($content);
            
        }
    // Service List
        public function Service_List()
        {
            $this->load->model('Service');
            $postData = $this->input->post();
            $data = $this->Service->getServiceList($postData);
            echo json_encode($data);
        }
     //Service Edit Form
        public function service_update_form($service_id)
        {
            $content = $this->lservice->service_edit_data($service_id);
            $this->template->full_admin_html_view($content);
        }
     //Service Category Update Form
     public function service_update()
        {
            $service_id = $this->input->post('service_id', TRUE);
            $data = array(
                'name' => $this->input->post('service_name',true),
                'category_id' => $this->input->post('category_id',true),
                'updated_date'   => date('Y-m-d'),
                'updated_by'       => $this->session->userdata('user_id'),
            );
            $this->db->where('id', $service_id);
            $result = $this->db->update('product_service', $data);
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect(base_url('Cservice/manage_service'));
                exit;
            } else {
                $this->session->set_userdata(array('error_message' => display('please_try_again')));
                redirect(base_url('Cservice'));
            }
        }
      //Service Delete
      public function service_delete($service_id)
      {
          $this->db->where('id', $service_id);
          $this->db->delete('product_service');
          $this->session->set_userdata(array('message' => display('successfully_delete')));
          redirect(base_url('Cservice/manage_service'));
          
      }
      // Service Pos Invoice Form
      public function service_invoice_form() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->service_invoice_add_form();
        $this->template->full_admin_html_view($content);
    }
    public function instant_customer()
    {
        $this->load->model('Customers');
        $data = array(
            'customer_name'    => $this->input->post('customer_name', TRUE),
            'customer_mobile' => $this->input->post('mobile', TRUE),
            'cus_type'   => 2,
            'outlet_id'       => $this->input->post('outlet_id', TRUE),
            'status'           => 1
        );
        $result = $this->Customers->customer_entry($data);
        if ($result) {
            $customer_id = $this->db->insert_id();
            $vouchar_no = $this->auth->generator(10);
            //Customer  basic information adding.
            $coa = $this->Customers->headcode();
            if ($coa->HeadCode != NULL) {
                $headcode = $coa->HeadCode + 1;
            } else {
                $headcode = "102030100001";
            }
            $c_acc = $customer_id . '-' . $this->input->post('customer_name', TRUE);
            $createby = $this->session->userdata('user_id');
            $createdate = date('Y-m-d H:i:s');

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
                'customer_id'      => $customer_id,
                'DepreciationRate' => '0',
                'CreateBy'         => $createby,
                'CreateDate'       => $createdate,
            ];
            //Previous balance adding -> Sending to customer model to adjust the data.
            $this->db->insert('acc_coa', $customer_coa);
            $this->Customers->previous_balance_add($this->input->post('previous_balance', TRUE), $customer_id);
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['customer_id']   = $customer_id;
            $data['customer_name'] = $data['customer_name'];
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }
    public function instant_service()
    {
        $data = array(
            'name'    => $this->input->post('service_name', TRUE),
            'category_id' => $this->input->post('category_id', TRUE),
            'created_date'   => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id'),
            'status'           => 1
        );
        $this->db->insert('product_service', $data);
        $service_id = $this->db->insert_id();
        // $data = array();
        if ($service_id) {
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['service_id']   = $service_id;
            $data['service_name'] = $data['name'];
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }
    public function service_autocomplete()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Service');
        $service_name    = $this->input->post('service_name', TRUE);
        $service_info   = $CI->Service->service_search($service_name);

        if ($service_info) {
            $json_service[''] = '';
            foreach ($service_info as $value) {
                $json_service[] = array('label' => $value['name'], 'value' => $value['id']);
            }
        } else {
            $json_service[] = 'Not found';
        }
        echo json_encode($json_service);
    }
    public function instant_product()
    {
        $data = array(
            'product_id'    => $this->generator(10),
            'product_name'    => $this->input->post('product_name', TRUE),
            'product_name_bn'    => $this->input->post('product_name_bn', TRUE),
            'unit'    => $this->input->post('unit', TRUE),
            'price'    => $this->input->post('price', TRUE),
            'purchase_price'    => $this->input->post('price', TRUE),
            'brand_id'    => $this->input->post('brand_id', TRUE),
            'category_id'    => $this->input->post('category_id', TRUE),
            'finished_raw' => 2,
            'created_date'   => date('Y-m-d'),
            'status'           => 1
        );
        $this->db->insert('product_information', $data);
        $product_id = $this->db->insert_id();
        // $data = array();
        if ($product_id) {
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            // $data['product_id']   = $product_id;
            $data['product_name'] = $data['product_name'];
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }
    public function instant_supplier()
    {
        $data = array(
            'mobile'    => $this->input->post('mobile', TRUE),
            'supplier_name'    => $this->input->post('supplier_name', TRUE),
            'status'           => 1
        );
        $this->db->insert('supplier_information', $data);
        $supplier_id = $this->db->insert_id();
        // $data = array();
        if ($supplier_id) {
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['supplier_id']   = $supplier_id;
            $data['supplier_name'] = $data['supplier_name'];
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }
    public function instant_purchase()
    {
        $supplier_id = $_POST['supplier_id'];
        $supinfo = $this->db->select('*')->from('supplier_information')->where('supplier_id', $supplier_id)->get()->row();
        $sup_head = $supinfo->supplier_id . '-' . $supinfo->supplier_name;
        $sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName', $sup_head)->get()->row();
        $createdate = date('Y-m-d H:i:s');
        $createby = $this->session->userdata('user_id');
        $today = date('Y-m-d');
        // Product Purchase Entry
        $data = array(
            'outlet_id'    => $_POST['outlet_id'],
            'purchase_id'    => date('YmdHis'),
            'supplier_id '    => $_POST['supplier_id'],
            'grand_total_amount' => $this->input->post('modal_purchase_total_price', TRUE),
            'purchase_date'      => $today,
            'paid_amount'        => $this->input->post('modal_purchase_paid_price', TRUE),
            'due_amount'         => $this->input->post('modal_purchase_due_price', TRUE),
            'status'           => 1,
            'created_date'   => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id'),
        );
        $this->db->insert('product_purchase', $data);
        $purchase_id = $this->db->insert_id();
        // Purchase Details entry
        
        $purchase_details = array(
            'purchase_detail_id' => $this->generator(15),
            'purchase_id'        => $data['purchase_id'],
            'product_id'         => $this->input->post('product_id', TRUE),   // modal_purchase_quantity
            'quantity'           => $this->input->post('modal_purchase_quantity', TRUE), 
            'qty'           =>    $this->input->post('modal_purchase_quantity', TRUE), 
            'damaged_qty'   => 0,
            'expired_date'       => date('Y-m-d', strtotime($today . ' + 50 years')),
            'rate'               => $this->input->post('modal_purchase_price', TRUE),
            'total_amount'       => $this->input->post('modal_purchase_total_price', TRUE),
            'discount'          => 0,
            'status'             => 2
        );
        $this->db->insert('product_purchase_details', $purchase_details);
        // Account Transaction Entry
        $supplier_account_credit = array(
            'VNo'            =>  $data['purchase_id'],
            'Vtype'          =>  'Purchase',
            'VDate'          =>  $today,
            'COAID'          =>   $sup_coa->HeadCode,
            'Narration'      =>  'Supplier Account',
            'Debit'          =>  0,
            'Credit'         =>  $this->input->post('modal_purchase_total_price', TRUE),
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $supplier_account_credit);
        $raw_materials_debit = array(
            'VNo'            =>  $data['purchase_id'],
            'Vtype'          =>  'Purchase',
            'VDate'          =>  $today,
            'COAID'          =>   405,
            'Narration'      =>  'Raw Material Purchase',
            'Debit'          =>  $this->input->post('modal_purchase_total_price', TRUE),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $raw_materials_debit);
        // For Payment Transaction
        $supplier_account_debit = array(
            'VNo'            =>  $data['purchase_id'],
            'Vtype'          =>  'Purchase',
            'VDate'          =>  $today,
            'COAID'          =>   $sup_coa->HeadCode,
            'Narration'      =>  'Supplier Account',
            'Debit'          =>  $this->input->post('modal_purchase_paid_price', TRUE),
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $createby,
            'CreateDate'     =>  $createdate,
            'IsAppove'       =>  1,

        );
        $this->db->insert('acc_transaction', $supplier_account_debit);
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

                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  1020101,
                            'Narration'      =>  'Cash Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);
                    }
                    if ($pay_type[$i] == 4) {
                        if (!empty($bank_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Bank Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                    }
                    if ($pay_type[$i] == 3) {
                        if (!empty($bkash_id)) {
                            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
                        } else {
                            $bkashcoaid = '';
                        }
                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  $bkashcoaid,
                            'Narration'      =>  'Bkash Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);
                    }
                    if ($pay_type[$i] == 5) {

                        if (!empty($nagad_id)) {
                            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
                        } else {
                            $nagadcoaid = '';
                        }
                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  $nagadcoaid,
                            'Narration'      =>  'Nagad Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);

                        
                    }
                    if ($pay_type[$i] == 7) {

                        if (!empty($rocket_id)) {
                            $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;

                            $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
                        } else {
                            $rocketcoaid = '';
                        }

                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  $rocketcoaid,
                            'Narration'      =>  'Rocket Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);
                    }
                    if ($pay_type[$i] == 6) {

                        $card_info = $this->Settings->get_real_card_data($card_id[$i]);

                        if (!empty($card_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $paid_amount_cr = array(
                            'VNo'            =>  $data['purchase_id'],
                            'Vtype'          =>  'Purchase',
                            'VDate'          =>  $today,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Card Payment for Purchase',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $paid_amount_cr);
                        
                    }
                }
            }
        }
        if ($purchase_id) {   
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['quantity']       = $this->input->post('modal_purchase_quantity', TRUE);
            $data['purchase_price']       =  $this->input->post('modal_purchase_price', TRUE);
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }

    public function getDeduction()
    {
        $deduction_id = $this->input->post('deduction_id') ? $this->input->post('deduction_id') : '';
        $CI = & get_instance();
        $data = $CI->db->select('id,name,percentage,type')->from('fund_deductions')->where('id',$deduction_id)->get()->result_array();
        echo json_encode($data);
    }
    public function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 61);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
     //Manage Service 
     public function manage_service_invoice() {
        $content = $this->lservice->service_invoice_list();
        $this->template->full_admin_html_view($content);
        
    }
// Service List
    public function Service_Invoice_List()
    {
        $this->load->model('Service');
        $postData = $this->input->post();
        $data = $this->Service->getServiceInvoiceList($postData);
        echo json_encode($data);
    }
    // Service Invoice Delete
    public function service_invoice_delete($service_invoice_id)
    {
        $data = array(
            'is_delete' => 1,
            'updated_date'   => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id'),
        );
        $this->db->where('service_invoice_id', $service_invoice_id);
        $this->db->update('technician_payment', $data);
        $data = array(
            'is_delete' => 1,
            'updated_date'   => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id'),
        );
        $this->db->where('service_invoice_id', $service_invoice_id);
        $result = $this->db->update('service_invoice', $data);
        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => "Successfully Deleted"));
            redirect(base_url('Cservice/manage_service_invoice'));
            exit;
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Cservice/service_invoice_form'));
        }
        
    }
    // Service Invoice Entry
    public function insert_service_invoice(){
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Service');
        $invoice_id = $CI->Service->invoice_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Cservice/service_invoice_print/'.$invoice_id));


}
    public function service_invoice_print($service_invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->service_invoice_print($service_invoice_id);

        $this->template->full_admin_html_view($content);
    }
    
    public function due_payment()
    {
        $service_invoice_id = $this->input->post('service_invoice_id');
        $notes = $this->input->post('notes');
        $inv_details = $this->db->from('service_invoice')->where('service_invoice_id', $service_invoice_id)->get()->row();
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');
        $bank_id = $this->input->post('bank_id');
        $bkash_id = $this->input->post('bkash_id');
        $nagad_id = $this->input->post('nagad_id');
        $rocket_id = $this->input->post('rocket_id');
        $paytype = $this->input->post('paytype');

        $total_amount = $this->input->post('total_amount');
        $due_amount = $this->input->post('due_amount');
        $paid_amount = $this->input->post('paid_amount');
        $pay_amount = $this->input->post('pay_amount');
          
        // Customer HeadCode
            $customar_information = $this->db->select('*')->from('customer_information')->where('customer_id', $inv_details->customer_id)->get()->row();
            $headn =  $inv_details->customer_id . '-' . $customar_information->customer_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $customar_information->customer_name;
        

        if (!empty($bank_id)) {
            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;

            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
        } else {
            $bankcoaid = '';
        }
        if (!empty($bkash_id)) {
            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id)->get()->row()->bkash_no;

            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
        } else {
            $bkashcoaid = '';
        }
        if (!empty($nagad_id)) {
            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id)->get()->row()->nagad_no;

            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
        } else {
            $nagadcoaid = '';
        }

        if (!empty($rocket_id)) {
            $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id)->get()->row()->rocket_no;

            $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
        } else {
            $rocketcoaid = '';
        }

        $cosdr = array(
            'VNo' => $service_invoice_id,
            'Vtype' => 'INV',
            'VDate' => $createdate,
            'COAID' => $customer_headcode,
            'Narration' => 'Customer debit For Invoice No -  ' . $inv_details->invoice_number . ' Customer ' . $cs_name,
            'Debit' => 0,
            'Credit' => $pay_amount,
            'IsPosted' => 1,
            'CreateBy' => $createby,
            'CreateDate' => $createdate,
            'IsAppove' => 1
        );
        $this->db->insert('acc_transaction', $cosdr);

        if ($paytype == 1) {
            $data3 = array(
                'VNo'            =>  $service_invoice_id,
                'Vtype'          =>  'INV',
                'VDate'          =>  $createdate,
                'COAID'          =>  1020101,
                'Narration'      =>  'Customer Cash Debit Amount For  Invoice NO- ' . $inv_details->invoice_number . ' Customer- ' . $cs_name,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1,
                //'paytype'=>$paytype

            );
            //  echo '<pre>';print_r($data3);exit();
            $ddd = $this->db->insert('acc_transaction', $data3);

            $payment_data = array(

                'invoice_id'    => $service_invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => 1020101
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 4) {

            $bankc = array(
                'VNo' => $service_invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $createdate,
                'COAID' => $bankcoaid,
                'Narration' => 'Customer Bank Debit Due Amount For  Invoice NO- ' . $inv_details->invoice_number . ' Customer- ' . $cs_name . 'in' . $bankname,
                'Debit' => $pay_amount,
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );
            $this->db->insert('acc_transaction', $bankc);

            $payment_data = array(

                'invoice_id'    => $service_invoice_id,
                'pay_type'      => 4,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $bankcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 3) {
            $bkashc = array(
                'VNo' => $service_invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $createdate,
                'COAID' => $bkashcoaid,
                'Narration' => 'Customer Bkash Debit Amount For  Invoice NO- ' . $inv_details->invoice_number . ' Customer- ' . $cs_name . 'in' . $bkashname,
                'Debit' => $pay_amount,
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );
            $this->db->insert('acc_transaction', $bkashc);


            $payment_data = array(

                'invoice_id'    => $service_invoice_id,
                'pay_type'      => 3,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $bkashcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 5) {
            $nagadc = array(
                'VNo'            =>  $service_invoice_id,
                'Vtype'          =>  'INVOICE',
                'VDate'          =>  $createdate,
                'COAID'          =>  $nagadcoaid,
                'Narration'      =>  'Customer Nagad Debit Amount For  Invoice NO- ' . $inv_details->invoice_number . ' Customer- ' . $cs_name . 'in' . $nagadname,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $createby,
                'CreateDate'     =>  $createdate,
                'IsAppove'       =>  1,

            );

            $this->db->insert('acc_transaction', $nagadc);

            $payment_data = array(

                'invoice_id'    => $service_invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $nagadcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 7) {
            $rocketc = array(
                'VNo'            =>  $service_invoice_id,
                'Vtype'          =>  'INVOICE',
                'VDate'          =>  $createdate,
                'COAID'          =>  $rocketcoaid,
                'Narration'      =>  'Customer Rocket Debit Amount For  Invoice NO- ' . $inv_details->invoice_number . ' Customer- ' . $cs_name . 'in' . $nagadname,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $createby,
                'CreateDate'     =>  $createdate,
                'IsAppove'       =>  1,

            );

            $this->db->insert('acc_transaction', $rocketc);

            $payment_data = array(

                'invoice_id'    => $service_invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $rocketcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }

        $data = array(

            'total_amount' => $total_amount,
            'due_amount' => $due_amount,
            'paid_amount' => $paid_amount + $pay_amount,
        );


        $this->db->where('service_invoice_id', $service_invoice_id);
        $result = $this->db->update('service_invoice', $data);

        if ($result == true) {

            redirect(base_url('Cservice/service_invoice_print/' . $service_invoice_id));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Cservice/manage_service_invoice'));
        }
    }
    public function technician_due_payment($technician_id)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Service');
        $data['title'] = 'Technician Due Payment';
        $data['technician_id'] = $technician_id;
        $data['technician_payment'] = $this->Service->technician_due_payment($technician_id);
        $content = $this->parser->parse('service/technician_payment', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function technician_due_update()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Service');
        $technician_id = $this->input->post('technician_id', TRUE);
        $status = $CI->Service->technician_due_update_entry();
        if($status)
        {
            $this->session->set_userdata(array('message' => 'Payment successfully Updated'));
            redirect(base_url('Cservice/technician_due_payment/'.$technician_id));
        }
        else{
            $this->session->set_userdata(array('message' => 'Failed to Update. Pay Amount not Same'));
            redirect(base_url('Cservice/technician_due_payment/'.$technician_id));
        }
       
    }
    public function UpdateStatus(){
        $data = array(
            'delivery_status' => $this->input->post('order_status'),
        );
        $this->db->where('service_invoice_id', $this->input->post('entity_id'));
        $this->db->update('service_invoice', $data);
    }
    // Report Module of Service
    //Technician earning Report
    public function technician_earning_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lservice');

        $content = $CI->lservice->technician_earning_report();

        $this->template->full_admin_html_view($content);
    }
    //Technician earning Report Ajax Call
    public function TechnicianEarningReport()
    {
        $this->load->model('Service');
        $postData = $this->input->post();
        $data = $this->Service->TechnicianEarningReport($postData, null);
        echo json_encode($data);
    }

     //Purchase Report
     public function purchase_report()
     {
         $CI = &get_instance();
         $this->auth->check_admin_auth();
         $CI->load->library('lservice');
 
         $content = $CI->lservice->purchase_report();
 
         $this->template->full_admin_html_view($content);
     }
     //Purchase Report Ajax Call
     public function ProductPurchaseReport()
     {
         $this->load->model('Service');
         $postData = $this->input->post();
         $data = $this->Service->ProductPurchaseReport($postData, null);
         echo json_encode($data);
     }
     public function product_wise_stock_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->product_wise_stock_report();
        $this->template->full_admin_html_view($content);
    }
    public function Service_Stock_Report()
    {
        $this->load->model('Service');
        $postData = $this->input->post();

        $pr_status = $this->input->post('pr_status');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $data = $this->Service->Service_Stock_Report($postData, null, $pr_status, $from_date, $to_date);
        echo json_encode($data);
    }
     //Warranty Report
     public function warranty_report()
     {
         $CI = &get_instance();
         $this->auth->check_admin_auth();
         $CI->load->library('lservice');
 
         $content = $CI->lservice->warranty_report();
 
         $this->template->full_admin_html_view($content);
     }
     //Warranty Report Ajax Call
     public function ajaxWarrantyReport()
     {
         $this->load->model('Service');
         $postData = $this->input->post();
         $data = $this->Service->ajaxWarrantyReport($postData, null);
         echo json_encode($data);
     }
     //Warranty Report
     public function sales_report()
     {
         $CI = &get_instance();
         $this->auth->check_admin_auth();
         $CI->load->library('lservice');
 
         $content = $CI->lservice->sales_report();
 
         $this->template->full_admin_html_view($content);
     }
     //Warranty Report Ajax Call
     public function ajaxSalesReport()
     {
         $this->load->model('Service');
         $postData = $this->input->post();
         $data = $this->Service->ajaxSalesReport($postData, null);
         echo json_encode($data);
     }
     //Technician Payment Report
    public function technician_payment_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->technician_payment_report();
        $this->template->full_admin_html_view($content);
    }
    //Technician Payment Report Ajax Call
    public function TechnicianPaymentReport()
    {
        $this->load->model('Service');
        $postData = $this->input->post();
        $data = $this->Service->TechnicianPaymentReport($postData, null);
        echo json_encode($data);
    }
    //Technician Payment Report
    public function account_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->account_report();
        $this->template->full_admin_html_view($content);
    }
    //Technician Payment Report Ajax Call
    public function AccountReport()
    {
        $this->load->model('Service');
        $postData = $this->input->post();
        $data = $this->Service->TechnicianPaymentReport($postData, null);
        echo json_encode($data);
    }



















































































































    public function service_json(){
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

    // service Update
    // public function service_update() {
    //     $this->load->model('Service');
    //     $service_id = $this->input->post('service_id',true);

    //     $tablecolumn = $this->db->list_fields('product_service');
    //                $num_column = count($tablecolumn)-4;
    //    $taxfield = [];
    //    for($i=0;$i<$num_column;$i++){
    //     $taxfield[$i] = 'tax'.$i;
    //    }
    //    foreach ($taxfield as $key => $value) {
    //     $data[$value] = $this->input->post($value)/100;
    //    }

    //         $data['service_name'] = $this->input->post('service_name',true);
    //         $data['charge']       = $this->input->post('charge',true);
    //         $data['description']  = $this->input->post('description',true);
           
    

    //     $this->Service->update_service($data, $service_id);
    //     $this->session->set_userdata(array('message' => display('successfully_updated')));
    //     redirect(base_url('Cservice/manage_service'));
    // }


      

  // Service retrieve
     public function retrieve_service_data_inv() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Service');
        $service_id = $this->input->post('service_id',true);
        $service_info = $CI->Service->get_total_service_invoic($service_id);

        echo json_encode($service_info);
    }


// sent pdf copy to customer after invoice
       public function invoice_pdf_generate($invoice_id = null) {
        $id = $invoice_id; 
        $this->load->model('Service');
        $this->load->model('Web_settings');
        $this->load->model('Invoices');
        $this->load->library('occational');
        $employee_list = $this->Service->employee_list();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $this->Service->service_invoice_updata($invoice_id);
        $customer_info =  $this->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo = $this->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
        $company_info = $this->Invoices->retrieve_company();

        $subTotal_quantity = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;

        if (!empty($service_inv_main)) {
            foreach ($service_inv_main as $k => $v) {
                $service_inv_main[$k]['final_date'] = $this->occational->dateConvert($service_inv_main[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $service_inv_main[$k]['qty'];
                $subTotal_ammount = $subTotal_ammount + $service_inv_main[$k]['total'];
            }

            $i = 0;
            foreach ($service_inv_main as $k => $v) {
                $i++;
                $service_inv_main[$k]['sl'] = $i;
            }
        }
        $name    = $customer_info->customer_name;
        $email   = $customer_info->customer_email;
        $data = array(
            'title'         => display('service_details'),
            'employee_list' => $employee_list,
            'invoice_id'    => $service_inv_main[0]['voucher_no'],
            'final_date'    => $service_inv_main[0]['final_date'],
            'customer_id'   => $service_inv_main[0]['customer_id'],
            'customer_info' => $customer_info,
            'customer_name' => $customer_info->customer_name,
            'customer_address'=> $customer_info->customer_address,
            'customer_mobile'=> $customer_info->customer_mobile,
            'customer_email'=> $customer_info->customer_email,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => $service_inv_main[0]['total_amount'],
            'total_discount'=> $service_inv_main[0]['total_discount'],
            'invoice_discount'=> $service_inv_main[0]['invoice_discount'],
            'subTotal_ammount'=> number_format($subTotal_ammount, 2, '.', ','),
            'subTotal_quantity'=>number_format($subTotal_quantity, 2, '.', ','),
            'total_tax'     => $service_inv_main[0]['total_tax'],
            'paid_amount'   => $service_inv_main[0]['paid_amount'],
            'due_amount'    => $service_inv_main[0]['due_amount'],
            'shipping_cost' => $service_inv_main[0]['shipping_cost'],
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'currency_details'=>$currency_details,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'     => $service_inv_main[0]['employee_id'],
            'previous'      => $service_inv_main[0]['previous'],
            'company_info'  => $company_info,

        );
        $this->load->library('pdfgenerator');
        $html = $this->load->view('service/invoice_download', $data, true);
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/service/' . $id . '.pdf', $output);
        $file_path = getcwd() . '/assets/data/pdf/service/' . $id . '.pdf';
        $send_email = '';
        if (!empty($email)) {
            $send_email = $this->setmail($email, $file_path, $id, $name);
            
            if($send_email){
                return 1;
                
           
            }else{
             
            return 0;
               
            }
           
        }
        return 0;
       
    }

     public function setmail($email, $file_path, $id = null, $name = null) {
        $setting_detail = $this->db->select('*')->from('email_config')->get()->row();
        $subject = 'Service Purchase  Information';
        $message = strtoupper($name) . '-' . $id;
        $config = Array(
            'protocol' => $setting_detail->protocol,
            'smtp_host' => $setting_detail->smtp_host,
            'smtp_port' => $setting_detail->smtp_port,
            'smtp_user' => $setting_detail->smtp_user,
            'smtp_pass' => $setting_detail->smtp_pass,
            'mailtype'  => 'html', 
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($setting_detail->smtp_user);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($file_path);
        $check_email = $this->test_input($email);
        if (filter_var($check_email, FILTER_VALIDATE_EMAIL)) {
            if ($this->email->send()) {
               return true;
            } else {
               
                return false;
            }
        } else {
           
            return true;
        }
    }

    //Email testing for email
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function service_invoice_data($invoice_id) {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->service_invoice_view_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }
  //pdf download service invoice details
     public function servicedetails_download($invoice_id = null) {
     
        $this->load->model('Service');
        $this->load->model('Web_settings');
        $this->load->model('Invoices');
        $this->load->library('occational');
        $employee_list = $this->Service->employee_list();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $this->Service->service_invoice_updata($invoice_id);
        $customer_info =  $this->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo = $this->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
        $company_info = $this->Invoices->retrieve_company();

        $subTotal_quantity = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;

        if (!empty($service_inv_main)) {
            foreach ($service_inv_main as $k => $v) {
                $service_inv_main[$k]['final_date'] = $this->occational->dateConvert($service_inv_main[$k]['date']);
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
            'customer_info' => $customer_info,
            'customer_name' => $customer_info->customer_name,
            'customer_address'=> $customer_info->customer_address,
            'customer_mobile'=> $customer_info->customer_mobile,
            'customer_email'=> $customer_info->customer_email,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => $service_inv_main[0]['total_amount'],
            'total_discount'=> $service_inv_main[0]['total_discount'],
            'invoice_discount'=> $service_inv_main[0]['invoice_discount'],
            'subTotal_ammount'=> number_format($subTotal_ammount, 2, '.', ','),
            'subTotal_quantity'=>number_format($subTotal_quantity, 2, '.', ','),
            'total_tax'     => $service_inv_main[0]['total_tax'],
            'paid_amount'   => $service_inv_main[0]['paid_amount'],
            'due_amount'    => $service_inv_main[0]['due_amount'],
            'shipping_cost' => $service_inv_main[0]['shipping_cost'],
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'currency_details'=>$currency_details,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'     => $service_inv_main[0]['employee_id'],
            'previous'      => $service_inv_main[0]['previous'],
            'company_info'  => $company_info,

        );



        $this->load->library('pdfgenerator');
        $dompdf = new DOMPDF();
        $page = $this->load->view('service/invoice_download', $data, true);
        $file_name = time();
        $dompdf->load_html($page);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents("assets/data/pdf/service/$file_name.pdf", $output);
        $filename = $file_name . '.pdf';
        $file_path = base_url() . 'assets/data/pdf/service/' . $filename;

        $this->load->helper('download');
        force_download('./assets/data/pdf/service/' . $filename, NULL);
        redirect("Cservice/manage_service");
    }


     public function service_invoice_update_form($invoic_id) {
        $content = $this->lservice->service_invoice_edit_data($invoic_id);
        $this->template->full_admin_html_view($content);
    }

    // public function manage_service_invoice() {
    //     $data['title']  = display('manage_service_invoice');
    //     $config["base_url"] = base_url('Cservice/manage_service_invoice');
    //     $config["total_rows"]  = $this->db->count_all('service_invoice');
    //     $config["per_page"]    = 20;
    //     $config["uri_segment"] = 3;
    //     $config["last_link"] = "Last"; 
    //     $config["first_link"] = "First"; 
    //     $config['next_link'] = 'Next';
    //     $config['prev_link'] = 'Prev';  
    //     $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
    //     $config['full_tag_close'] = "</ul>";
    //     $config['num_tag_open'] = '<li>';
    //     $config['num_tag_close'] = '</li>';
    //     $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
    //     $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
    //     $config['next_tag_open'] = "<li>";
    //     $config['next_tag_close'] = "</li>";
    //     $config['prev_tag_open'] = "<li>";
    //     $config['prev_tagl_close'] = "</li>";
    //     $config['first_tag_open'] = "<li>";
    //     $config['first_tagl_close'] = "</li>";
    //     $config['last_tag_open'] = "<li>";
    //     $config['last_tagl_close'] = "</li>";
    //     /* ends of bootstrap */
    //     $this->pagination->initialize($config);
    //     $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    //     $data["links"] = $this->pagination->create_links();
    //     $data['service'] = $this->Service->service_invoice_list($config["per_page"], $page);
    //       $content     = $this->parser->parse('service/service_invoice', $data, true);
    //       $this->template->full_admin_html_view($content);  
    // }

    public function service_invoic_delete($service_id) {
        $this->load->model('Service');
        $this->Service->delete_service_invoice($service_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
         redirect(base_url('Cservice/manage_service_invoice'));
    }
    public function update_service_invoice(){
      $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Service');
        $invoice_id = $CI->Service->invoice_update();
    $this->session->set_userdata(array('message' => display('successfully_updated')));
    redirect(base_url('Cservice/service_invoice_data/'.$invoice_id));  
    }


}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fund_Deduction extends CI_Controller
{

    public $menu;

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('lfund');
        $this->load->library('session');
        $this->load->model('FundDeductions');
        $this->auth->check_admin_auth();
    }


    //Add Commission
    public function index()
    {
        $content = $this->lfund->commission_add_form();
        $this->template->full_admin_html_view($content);
    }
    // insert Commission
    public function insert_fund_deduction()
    {
        $CI = &get_instance();
        $this->load->model('Warehouse');
        $outlet_id = $CI->session->userdata('outlet_id');
        $outlet_list = $CI->Warehouse->get_outlet($outlet_id);
        $central_warehouse = $CI->Warehouse->central_warehouse();
        $data = array(
            'name'   => $this->input->post('name', TRUE),
            'type'   => $this->input->post('type', TRUE),
            'percentage'   => $this->input->post('percentage', TRUE),
            'outlet_id'       => $outlet_list ? $outlet_list[0]['outlet_id'] : $central_warehouse[0]['warehouse_id'],
            'created_date'   => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id'),
            'status'          => 1,    
        );
      

        $this->db->insert('fund_deductions', $data);
        $commission_id = $this->db->insert_id();

            if($this->input->post('type', TRUE) == 'fund')
            {
                $headcode    = '503';
                $HeadName    = 'Fund';
                $PHeadName   = 'Liabilities';
                $HeadLevel   = 1;
                $txtHeadType = 'L';
                $IsActive    = 1;
                $IsTransaction = 0;
                $IsGL       = 1;
                $createby = $this->session->userdata('user_id');
                $createdate = date('Y-m-d H:i:s');
                $postData = array(
                    'HeadCode'       =>  $headcode,
                    'HeadName'       =>  $HeadName,
                    'PHeadName'      =>  $PHeadName,
                    'HeadLevel'      =>  $HeadLevel,
                    'IsActive'       =>  $IsActive,
                    'IsTransaction'  =>  $IsTransaction,
                    'IsGL'           =>  $IsGL,
                    'HeadType'       => $txtHeadType,
                    'IsBudget'       => 0,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                );
                $upinfo = $this->db->select('*')
                    ->from('acc_coa')
                    ->where('HeadCode', $headcode)
                    ->get()
                    ->row();
                if (empty($upinfo)) {
                    $this->db->insert('acc_coa', $postData);
                } else {
        
                    $hname = $this->input->post('HeadName', TRUE);
                    $updata = array(
                        'PHeadName'      =>  $HeadName,
                    );
                    $this->db->where('HeadCode', $headcode)
                        ->update('acc_coa', $postData);
                    $this->db->where('PHeadName', $hname)
                        ->update('acc_coa', $updata);
                }
            }
            if($this->input->post('type', TRUE) == 'income')
            {
                $headcode    = '308';
                $HeadName    = 'Other Incomes';
                $PHeadName   = 'Income';
                $HeadLevel   = 1;
                $txtHeadType = 'I';
                $IsActive    = 1;
                $IsTransaction = 1;
                $IsGL       = 1;
                $createby = $this->session->userdata('user_id');
                $createdate = date('Y-m-d H:i:s');
                $postData = array(
                    'HeadCode'       =>  $headcode,
                    'HeadName'       =>  $HeadName,
                    'PHeadName'      =>  $PHeadName,
                    'HeadLevel'      =>  $HeadLevel,
                    'IsActive'       =>  $IsActive,
                    'IsTransaction'  =>  $IsTransaction,
                    'IsGL'           =>  $IsGL,
                    'HeadType'       => $txtHeadType,
                    'IsBudget'       => 0,
                    'IsDepreciation' => 0,
                    'DepreciationRate' => 0.00,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                );
                $upinfo = $this->db->select('*')
                    ->from('acc_coa')
                    ->where('HeadCode', $headcode)
                    ->get()
                    ->row();
                if (empty($upinfo)) {
                   
                    $this->db->insert('acc_coa', $postData);
                    
                  
                } else {
        
                    $hname = $this->input->post('HeadName', TRUE);
                    $updata = array(
                        'PHeadName'      =>  $HeadName,
                    );
                    $this->db->where('HeadCode', $headcode)
                        ->update('acc_coa', $postData);
                    $this->db->where('PHeadName', $hname)
                        ->update('acc_coa', $updata);
                }
            }
        if ($commission_id) {
            redirect(base_url('Fund_Deduction/manage_fund_deductions'));
            exit;
        }else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Fund_Deduction'));
            exit;
        }
    }
    //Manage Fund Deduction
    public function manage_fund_deductions()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lfund');
        $content = $this->lfund->fund_deduction_list();
        $this->template->full_admin_html_view($content);
    }
    public function FundDeduction_List()
    {
        $this->load->model('FundDeductions');
        $postData = $this->input->post();
        $data = $this->FundDeductions->getFundDeductionList($postData);
        echo json_encode($data);
    }
     //customer Update Form
     public function fund_deduction_update_form($fund_deduction_id)
     {
         $content = $this->lfund->fund_deduction_edit_data($fund_deduction_id);
         $this->template->full_admin_html_view($content);
     }
     public function update_fund_deduction()
     {
         $this->load->model('FundDeductions');
         $fund_deduction_id = $this->input->post('fund_deduction_id', TRUE);
         $data = array(
             'name' => $this->input->post('name', TRUE),
             'type'   => $this->input->post('type', TRUE),
             'percentage'   => $this->input->post('percentage', TRUE),
            'updated_date'   => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id')

         );
         $this->db->where('id', $fund_deduction_id);
         $result = $this->db->update('fund_deductions', $data);
         if ($result == TRUE) {
             $this->session->set_userdata(array('message' => display('successfully_updated')));
             redirect(base_url('Fund_Deduction/manage_fund_deductions'));
             exit;
         } else {
             $this->session->set_userdata(array('error_message' => display('please_try_again')));
             redirect(base_url('Fund_Deduction'));
         }
     }
    public function fund_deduction_delete($fund_deduction_id)
    {
        $this->db->where('id', $fund_deduction_id);
        $this->db->delete('fund_deductions');
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Fund_Deduction/manage_fund_deductions'));
        
    }
   
}

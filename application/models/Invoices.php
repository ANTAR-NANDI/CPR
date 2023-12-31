<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lcustomer');
        $this->load->library('Smsgateway');
        $this->load->library('session');
        $this->load->model('Customers');
        $this->auth->check_admin_auth();
    }

    //Count invoice
    public function count_invoice()
    {
        $CI = &get_instance();

        $CI->load->model('Warehouse');

        // $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        $outlet_id = $CI->session->userdata('outlet_id');
        $outlet_user        = $CI->Warehouse->get_outlet($outlet_id);
        $cw = $CI->Warehouse->central_warehouse();
        $outlet_id = $outlet_user ? $outlet_user[0]['outlet_id'] : $cw[0]['warehouse_id'];
        if($outlet_user)
        {
            $data = $this->db->where('outlet_id', $outlet_id)->from("invoice")->count_all_results();
        }
        else{
            $data = $this->db->from("invoice")->count_all_results();
        }
       

        return $data;
    }

    public function get_user_role($user_id)
    {

        return $this->db->select('b.type')
            ->from('sec_userrole a')
            ->join('sec_role b', 'a.roleid=b.id')
            ->where('a.user_id', $user_id)
            ->get()->row()->type;
    }

    public function get_user_outlet($user_id)
    {

        return $this->db->select('a.outlet_id')
            ->from('outlet_warehouse a')
            // ->join('sec_role b', 'a.roleid=b.id')
            ->where('a.user_id', $user_id)
            ->get()->row()->outlet_id;
    }



    // public function getInvoiceList($postData = null)
    // {

    //     $this->load->library('occational');
    //     $this->load->model('Warehouse');
    //     $response = array();
    //     $usertype = $this->session->userdata('user_type');

    //     $userRole = $this->get_user_role($this->session->userdata('user_id'));

    //     //        echo strstr($userRole,"warehouse");
    //     //        exit();
    //     //        if (str_contains($userRole, 'Warehouse')) {
    //     //            echo 'true';
    //     //        }

    //     //        echo '<pre>';print_r($userRole);exit();
    //     $fromdate = $this->input->post('fromdate', TRUE);
    //     $todate   = $this->input->post('todate', TRUE);
    //     if (!empty($fromdate)) {
    //         $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
    //     } else {
    //         $datbetween = "";
    //     }
    //     ## Read value
    //     $draw = $postData['draw'];
    //     $start = $postData['start'];
    //     $rowperpage = $postData['length']; // Rows display per page
    //     $columnIndex = $postData['order'][0]['column']; // Column index
    //     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    //     $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    //     $searchValue = $postData['search']['value']; // Search value

    //     ## Search
    //     $searchQuery = "";
    //     if ($searchValue != '') {
    //         $searchQuery = " (b.customer_name like '%" . $searchValue . "%' or x.outlet_name like '%" . $searchValue . "%' or a.invoice like '%" . $searchValue . "%' or a.date like'%" . $searchValue . "%' or a.invoice_id like'%" . $searchValue . "%' or u.first_name like'%" . $searchValue . "%'or u.last_name like'%" . $searchValue . "%')";
    //     }

    //     ## Total number of records without filtering
    //     $this->db->select('count(*) as allcount');
    //     $this->db->from('invoice a');
    //     $this->db->where('a.is_pre', 1);
    //     $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    //     $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
    //     $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');

    //     $this->db->order_by('a.invoice', 'desc');
    //     if ($usertype == 2) {
    //         $this->db->where('a.sales_by', $this->session->userdata('user_id'));
    //     }
    //     if (!empty($fromdate) && !empty($todate)) {
    //         $this->db->where($datbetween);
    //     }
    //     if ($searchValue != '')
    //         $this->db->where($searchQuery);

    //     $records = $this->db->get()->result();
    //     $totalRecords = $records[0]->allcount;

    //     ## Total number of record with filtering
    //     $this->db->select('count(*) as allcount');
    //     $this->db->from('invoice a');
    //     $this->db->where('a.is_pre', 1);
    //     $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    //     $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
    //     $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');

    //     $this->db->order_by('a.invoice', 'desc');
    //     if ($usertype == 2) {
    //         $this->db->where('a.sales_by', $this->session->userdata('user_id'));
    //     }
    //     if (!empty($fromdate) && !empty($todate)) {
    //         $this->db->where($datbetween);
    //     }
    //     if ($searchValue != '')
    //         $this->db->where($searchQuery);

    //     $records = $this->db->get()->result();
    //     $totalRecordwithFilter = $records[0]->allcount;

    //     $cw_name = $this->Warehouse->central_warehouse()[0]['central_warehouse'];

    //     ## Fetch records
    //     $this->db->select("a.*,b.customer_name,u.first_name,u.last_name, x.*, a.outlet_id as outlt");
    //     $this->db->from('invoice a');
    //     $this->db->where('a.is_pre', 1);
    //     $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    //     $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
    //     $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');
    //     $this->db->order_by('a.invoice', 'desc');
    //     if ($usertype == 2) {
    //         if ($userRole == 'Warehouse Admin') {
    //             $this->db->where('a.sale_type', 1);
    //             $this->db->or_where('a.sale_type', 4);
    //             $this->db->or_where('a.sale_type', 3);
    //         } else {
    //             $this->db->where('a.sales_by', $this->session->userdata('user_id'));
    //         }
    //     }
    //     if (!empty($fromdate) && !empty($todate)) {
    //         $this->db->where($datbetween);
    //     }
    //     if ($searchValue != '')
    //         $this->db->where($searchQuery);

    //     $this->db->order_by($columnName, $columnSortOrder);
    //     $this->db->limit($rowperpage, $start);
    //     // echo '<pre>'; print_r($this->db->get()->result_array());exit();
    //     $records = $this->db->get()->result();
    //     $data = array();
    //     $sl = 1;

    //     // echo "<pre>";
    //     // print_r($records);
    //     // exit();

    //     foreach ($records as $record) {

    //         if (($record->paid_amount - $record->total_amount) >= 0) {
    //             $due_amount = 0;
    //         } else {
    //             $due_amount = $record->total_amount - $record->paid_amount;
    //         }
    //         $button = '';
    //         $base_url = base_url();
    //         $jsaction = "return confirm('Are You Sure ?')";

    //         $button .= '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="' . display('invoice') . '"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

    //         // if ($this->permission1->method('manage_invoice', 'update')->access()) {
    //         //     $button .= ' <a href="' . $base_url . 'Cinvoice/invoice_update_form/' . $record->invoice_id . '" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
    //         // }
    //         $button .= ' <a href="' . $base_url . 'Cretrun_m/invoice_return_form_c/' . $record->invoice_id . '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Return"><i class="fa fa-retweet" aria-hidden="true"></i></a> ';
    //         $button .= ' <a  class="btn btn-black btn-sm" data-toggle="tooltip" data-placement="left" title="Payment" onclick="payment_modal(' . $record->invoice_id . ',' . $record->total_amount . ',' . $record->paid_amount . ',' . $due_amount . ')"><i class="fa fa-money" aria-hidden="true"></i></a> ';



    //         $details = '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="" >' . $record->invoice . '</a>';
    //         $details_i = '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="" >' . $record->invoice_id . '</a>';

    //         if ($record->outlt == 'HK7TGDT69VFMXB7') {
    //             $out = $cw_name;
    //         } else {
    //             if ($record->sale_type == 4) {
    //                 $out = 'Online Order';
    //             } else {
    //                 $out =   $record->outlet_name;
    //             }
    //         }

    //         //            $out = (($record->outlt == 'HK7TGDT69VFMXB7') ? $cw_name : $record->outlet_name);


    //         $agg_id = $record->agg_id;



    //         if (!empty($agg_id)) {
    //             $agg_name = $this->db->select('aggre_name')->from('aggre_list')->where('id', $agg_id)->get()->row()->aggre_name;
    //         }

    //         if ($record->agg_id == 3) {
    //             $customer_name = $agg_name;
    //         } else {
    //             $customer_name = $record->customer_name;
    //         }

    //         // if ($record->due_amount > 0) {
    //         //     $payment_status = '<span class="label label-danger ">Due</span>';
    //         // } else {
    //         //     $payment_status = '<span class="label label-success ">Paid</span>';
    //         // }

    //         if (($record->paid_amount - $record->total_amount) >= 0) {
    //             $payment_status = '<span class="label label-success ">Paid</span>';
    //         } else {
    //             $payment_status = '<span class="label label-danger ">Due</span>';
    //         }



    //         // $outlet = ($this->Warehouse->branch_search_item($record->outlet_id)) ? $this->Warehouse->branch_search_item($record->outlet_id)[0]['outlet_name'] : '';
    //         if ($record->sale_type == 1) {
    //             $st = 'Whole Sale';
    //         }
    //         if ($record->sale_type == 2) {
    //             $st = 'Retail';
    //         }
    //         if ($record->sale_type == 3) {
    //             $st = 'Aggregators Sale';
    //         }
    //         if ($record->sale_type == 4) {
    //             $st = 'Online Retail';
    //         }

    //         if ($record->sale_type  == null) {

    //             $st = '';
    //         }

    //         if ($record->courier_status  == 1) {
    //             $courier_status = 'Processing';
    //         }

    //         if ($record->courier_status == 2) {
    //             $courier_status = 'Shipped';
    //         }
    //         if ($record->courier_status  == 3) {

    //             $courier_status = 'Delivered';
    //         }
    //         if ($record->courier_status  == 4) {

    //             $courier_status = 'Cancelled';
    //         }
    //         if ($record->courier_status  == 5) {
    //             $courier_status = 'Returned';
    //         }
    //         if ($record->courier_status  == 6) {

    //             $courier_status = 'Exchanged';
    //         }
    //         if ($record->courier_status  == 0) {

    //             $courier_status = 'N/A';
    //         }
    //         $data[] = array(
    //             'sl'               => $sl,
    //             'invoice_id'       => $details_i,
    //             'invoice'          => $details,
    //             'outlet_name'      => $out,
    //             'delivery_type'      => ($record->delivery_type == '1') ? 'Pick Up' : (($record->delivery_type == '2') ? 'Courier' : 'Nothing Selected'),
    //             'courier_status'      => $courier_status,
    //             'payment_status'      => $payment_status,
    //             'paid_amount'      => $record->paid_amount,
    //             // 'due_amount'      => $record->due_amount,
    //             'due_amount'      => $due_amount,
    //             'sale_type'      => $st,
    //             'salesman'         => $record->first_name . ' ' . $record->last_name,
    //             'customer_name'    => $customer_name,
    //             'final_date'       => $this->occational->dateConvert($record->date) . " " . $record->time,
    //             'total_amount'     => $record->total_amount,
    //             'button'           => $button,
    //         );
    //         $sl++;
    //     }

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit();
    //     ## Response
    //     $response = array(
    //         "draw" => intval($draw),
    //         "iTotalRecords" => $totalRecordwithFilter,
    //         "iTotalDisplayRecords" => $totalRecords,
    //         "aaData" => $data
    //     );

    //     return $response;
    // }

    public function getInvoiceList($postData = null)
    {

        $this->load->library('occational');
        $this->load->model('Warehouse');
        $response = array();
        $usertype = $this->session->userdata('user_type');

        $user_id = $this->session->userdata('user_id');

        $userRole = $this->get_user_role($user_id);

        $outlet_id = $this->get_user_outlet($user_id);

        //        echo strstr($userRole,"warehouse");
        //        exit();
        //        if (str_contains($userRole, 'Warehouse')) {
        //            echo 'true';
        //        }

        // echo '<pre>';
        // print_r($this->session->userdata('outlet_id'));
        // exit();
        $fromdate = $this->input->post(
            'fromdate',
            TRUE
        );
        $todate   = $this->input->post('todate', TRUE);
        if (!empty($fromdate)) {
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
        } else {
            $datbetween = "";
        }
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
            $searchQuery = " (b.customer_name like '%" . $searchValue . "%' or x.outlet_name like '%" . $searchValue . "%' or a.invoice like '%" . $searchValue . "%' or a.date like'%" . $searchValue . "%' or a.invoice_id like'%" . $searchValue . "%' or u.first_name like'%" . $searchValue . "%'or u.last_name like'%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('invoice a');
        $this->db->where('a.is_pre', 1);
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');

        $this->db->order_by(
            'a.invoice',
            'desc'
        );
        if ($usertype == 2) {
            // $this->db->where('a.sales_by', $this->session->userdata('user_id'));
            $this->db->where('a.outlet_id', $this->session->userdata('outlet_id'));
        }
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if ($searchValue != '')
            $this->db->where($searchQuery);

        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        // echo "<pre>";
        // print_r($records);
        // exit();

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('invoice a');
        $this->db->where('a.is_pre', 1);
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');

        $this->db->order_by('a.invoice', 'desc');
        if ($usertype == 2) {
            // $this->db->where('a.sales_by', $this->session->userdata('user_id'));
            $this->db->where('a.outlet_id', $this->session->userdata('outlet_id'));
        }
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if ($searchValue != '')
            $this->db->where($searchQuery);

        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $cw_name = $this->Warehouse->central_warehouse()[0]['central_warehouse'];

        ## Fetch records
        $this->db->select("a.*,b.customer_name,u.first_name,u.last_name, x.*, a.outlet_id as outlt, a.status as invoice_type_status");
        $this->db->from('invoice a');
        $this->db->where('a.is_pre', 1);
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('users u', 'u.user_id = a.sales_by', 'left');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id', 'left');
        $this->db->order_by('a.invoice', 'desc');
        if ($usertype == 2) {
            if ($userRole == 'Warehouse Admin') {
                $this->db->where('a.sale_type', 1);
                $this->db->or_where('a.sale_type', 4);
                $this->db->or_where('a.sale_type', 3);
            } else {
                $this->db->where('a.outlet_id', $this->session->userdata('outlet_id'));
                // $this->db->where('a.sales_by', $this->session->userdata('user_id'));
            }
        }
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if ($searchValue != '')
            $this->db->where($searchQuery);

        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        // echo '<pre>'; print_r($this->db->get()->result_array());exit();
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        // echo "<pre>";
        // print_r($records);
        // exit();

        foreach ($records as $record) {

            if (($record->paid_amount - $record->net_total) >= 0) {
                $due_amount = 0;
            } else {
                $due_amount = $record->net_total - $record->paid_amount;
            }
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

            $button .= '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="' . display('invoice') . '"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

            // if ($this->permission1->method('manage_invoice', 'update')->access()) {
                $button .= ' <a href="' . $base_url . 'Cinvoice/invoice_update_form/' . $record->invoice_id . '" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
                $button .= ' <a href="' . $base_url . 'Cinvoice/invoice_delete/' . $record->invoice_id . '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="' . display('delete') . '"><i class="fa fa-trash" aria-hidden="true"></i></a> ';
            // }
            if ($record->is_return_replace == 0 && $record->invoice_type_status == 1) {
                $button .= ' <a href="' . $base_url . 'Cretrun_m/invoice_return_form_c/' . $record->invoice_id . '" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Return"><i class="fa fa-retweet" aria-hidden="true"></i></a> ';
            }
            if($record->due_amount > 0 )
            {
            $button .= ' <a  class="btn btn-black btn-sm" data-toggle="tooltip" data-placement="left" title="Payment" onclick="payment_modal(' . $record->invoice_id . ',' . $record->net_total . ',' . $record->paid_amount . ',' . $due_amount . ')"><i class="fa fa-money" aria-hidden="true"></i></a> ';
            }


            $details = '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="" >' . $record->invoice . '</a>';
            $details_i = '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="" >' . $record->invoice_id . '</a>';

            if ($record->outlt == 'HK7TGDT69VFMXB7') {
                $out = $cw_name;
            } else {
                if ($record->sale_type == 4) {
                    $out = 'Online Order';
                } else {
                    $out =   $record->outlet_name;
                }
            }

            //            $out = (($record->outlt == 'HK7TGDT69VFMXB7') ? $cw_name : $record->outlet_name);


            $agg_id = $record->agg_id;

            // echo "<pre>";
            // print_r($agg_id);
            // exit();



            if (!empty($agg_id)) {
                $agg_name = $this->db->select('aggre_name')->from('aggre_list')->where('id', $agg_id)->get()->row()->aggre_name;
            }

            if ($record->agg_id == 3) {
                $customer_name = $agg_name;
            } else {
                $customer_name = $record->customer_name;
            }

            $customer_name = $record->customer_name;

            // if ($record->due_amount > 0) {
            //     $payment_status = '<span class="label label-danger ">Due</span>';
            // } else {
            //     $payment_status = '<span class="label label-success ">Paid</span>';
            // }

            if (($record->paid_amount - $record->net_total) >= 0) {
                $payment_status = '<span class="label label-success ">Paid</span>';
            } else {
                $payment_status = '<span class="label label-danger ">Due</span>';
            }


            if ($record->invoice_type_status == 1) {
                if ($record->is_return_replace == 1) {
                    $sales_status = '<span class="label label-primary ">Invoice Returned</span>';
                }
                else{
                $sales_status = '<span class="label label-info" style="background-color:#158d12;border:#158d12">Sale Invoice</span>';
                }
            } elseif ($record->invoice_type_status == 2) {
                $sales_status = '<span class="label label-warning ">Return Invoice</span>';
            }



            // $outlet = ($this->Warehouse->branch_search_item($record->outlet_id)) ? $this->Warehouse->branch_search_item($record->outlet_id)[0]['outlet_name'] : '';
            if ($record->sale_type == 1) {
                $st = 'Whole Sale';
            }
            if ($record->sale_type == 2) {
                $st = 'Retail';
            }
            if ($record->sale_type == 3) {
                $st = 'Aggregators Sale';
            }
            if ($record->sale_type == 4) {
                $st = 'Online Retail';
            }

            if ($record->sale_type  == null) {

                $st = '';
            }

            if ($record->courier_status  == 1) {
                $courier_status = 'Processing';
            }

            if (
                $record->courier_status == 2
            ) {
                $courier_status = 'Shipped';
            }
            if (
                $record->courier_status  == 3
            ) {

                $courier_status = 'Delivered';
            }
            if ($record->courier_status  == 4) {

                $courier_status = 'Cancelled';
            }
            if (
                $record->courier_status  == 5
            ) {
                $courier_status = 'Returned';
            }
            if ($record->courier_status  == 6) {

                $courier_status = 'Exchanged';
            }
            if (
                $record->courier_status  == 0
            ) {

                $courier_status = 'N/A';
            }
            $data[] = array(
                'sl'               => $sl,
                'invoice_id'       => $details_i,
                'invoice'          => $details,
                'outlet_name'      => $out,
                'delivery_type'      => ($record->delivery_type == '1') ? 'Pick Up' : (($record->delivery_type == '2') ? 'Courier' : 'Nothing Selected'),
                'courier_status'      => $courier_status,
                'payment_status'      => $payment_status,
                'sales_status'      => $sales_status,
                'paid_amount'      => $record->paid_amount,
                // 'due_amount'      => $record->due_amount,
                'due_amount'      => $due_amount,
                'sale_type'      => $st,
                'salesman'         => $record->first_name . ' ' . $record->last_name,
                'customer_name'    => $customer_name,
                'final_date'       => $this->occational->dateConvert($record->date) . " " . $record->time,
                'total_amount'     => $record->net_total,
                'button'           => $button,
            );
            $sl++;
        }

        // echo '<pre>';
        // print_r($data);
        // exit();
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }


    //Count todays_sales_report
    public function todays_sales_report()
    {
        $CI = &get_instance();

        $CI->load->model('Warehouse');

        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] ? $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] : null;


        $today = date('Y-m-d');
        $this->db->select('a.*,b.customer_name, b.customer_id, a.invoice_id,a.invoice');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date', $today);
        $this->db->where('a.outlet_id', $outlet_id);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get()->result();
        return $query;
    }

    //    ======= its for  best_sales_products ===========
    public function best_sales_products()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        //$outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;
        $this->db->select('b.product_id, b.product_name, sum(a.quantity) as quantity');
        $this->db->from('invoice_details a');
        $this->db->where('i.outlet_id', $outlet_id);
        $this->db->join('invoice i', 'i.invoice_id = a.invoice_id');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->group_by('b.product_id');
        $this->db->order_by('quantity', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    //    ======= its for  best_sales_products ===========
    public function best_saler_product_list()
    {
        $this->db->select('b.product_id, b.product_name, sum(a.quantity) as quantity');
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->group_by('b.product_id');
        $this->db->order_by('quantity', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    //    ======= its for  todays_customer_receipt ===========
    public function todays_customer_receipt($today = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_id = $CI->Warehouse->get_outlet_user()[0]['outlet_id'];
        $this->db->select('a.*,b.HeadName,c.customer_name,c.address2');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('customer_information c', 'b.customer_id=c.customer_id');
        // $this->db->where('outlet_id', $outlet_id);
        $this->db->where('a.Credit >', 0);
        $this->db->where('DATE(a.VDate)', $today);
        $this->db->where('a.IsAppove', 1);
        $query = $this->db->get();
        return $query->result();
    }

    //    ======= its for  todays_customer_receipt ===========
    public function filter_customer_wise_receipt($custome_id = null, $district = null, $from_date = null)
    {
        $this->db->select('a.*,b.HeadName,c.address2');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('customer_information c', 'b.customer_id=c.customer_id');
        if ($custome_id) {
            $this->db->where('a.Credit >', 0);
            $this->db->where('a.IsAppove', 1);
            $this->db->where('b.customer_id', $custome_id);
        }
        if ($district) {
            $this->db->where('a.IsAppove', 1);
            $this->db->where('a.Credit >', 0);
            $this->db->where('c.address2', $district);
        }
        if ($from_date) {
            $this->db->where('a.IsAppove', 1);
            $this->db->where('a.Credit >', 0);
            $this->db->where('DATE(a.VDate)', $from_date);
        }
        if ($custome_id && $district && $from_date) {
            $this->db->where('a.IsAppove', 1);
            $this->db->where('a.Credit >', 0);
            $this->db->where('b.customer_id', $custome_id);
            $this->db->where('c.address2', $district);
            $this->db->where('DATE(a.VDate)', $from_date);
        }



        $query = $this->db->get();
        return $query->result();
    }

    //invoice List
    public function invoice_list($perpage = null, $page = null)
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->order_by('a.invoice', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function todays_invoice()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->where('a.date', date('Y-m-d'));
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // pdf list
    public function invoice_list_pdf()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function user_invoice_data($user_id)
    {
        return  $this->db->select('*')->from('users')->where('user_id', $user_id)->get()->row();
    }
    // search invoice by customer id
    public function invoice_search($customer_id, $per_page, $page)
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // invoice search by invoice id
    public function invoice_list_invoice_id($invoice_no)
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->where('invoice', $invoice_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // date to date invoice list
    public function invoice_list_date_to_date($from_date, $to_date, $perpage, $page)
    {
        $dateRange = "a.date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Invoiec list date to date
    public function invoice_list_date_to_date_count($from_date, $to_date)
    {
        $dateRange = "a.date BETWEEN '$from_date%' AND '$to_date%'";
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //invoice List
    public function invoice_list_count()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    // count invoice search by customer
    public function invoice_search_count($customer_id)
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.customer_id', $customer_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //invoice Search Item
    public function search_inovoice_item($customer_id)
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('b.customer_id', $customer_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //POS invoice entry
    public function pos_invoice_setup($product_id, $outlet_id = null)
    {

        $this->load->model('Reports');
        $this->load->model('Rqsn');
        $this->load->model('Products');

        // echo '<pre>';
        // print_r($product_id);
        // exit();


        $product_information = $this->Products->get_single_pr_details($product_id, true);

        $p_id = $this->db->select('product_id')->from('product_information')->where('product_id', $product_id)->or_where('sku', $product_id)->get()->row()->product_id;

        if ($product_information != null) {
            $product_information = $product_information[0];


            if ($outlet_id == 'HK7TGDT69VFMXB7') {
                $expired_stock  = $this->Reports->getExpiryCheckList($p_id)['expired_stock'];
                $available_quantity = $expired_stock;
            } else {
                $expired_stock  = $this->Rqsn->expiry_outlet_stock($p_id)['expired_stock'];
                $available_quantity = $expired_stock;
            }


            $vat = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'vat', 'product_id' => $p_id))->get()->row();
            $tax = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'tax', 'product_id' => $p_id))->get()->row();



            $product_base_rate = $product_information->price;

            // echo "<pre>";
            // print_r($tax);
            // exit();

            if (empty($vat)) {
                $vat->percent = 0;
                $vat->vat_tax_type = 'ex';
            }

            if (empty($tax)) {
                $tax->percent = 0;
                $tax->vat_tax_type = 'ex';
            }



            if ($vat->percent > 0 || $tax->percent > 0) {

                if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'ex') {
                    $product_real_price = ($product_base_rate / (100 + $vat->percent)) * 100;
                    $product_vat = $product_real_price * ($vat->percent / 100);
                    $product_tax = $product_real_price * ($tax->percent / 100);
                }

                if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'in') {
                    $product_real_price = ($product_base_rate / (100 + $tax->percent)) * 100;
                    $product_vat = $product_real_price * ($vat->percent / 100);
                    $product_tax = $product_real_price * ($tax->percent / 100);
                }

                if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'in') {
                    $product_real_price = ($product_base_rate / (100 + $tax->percent + $vat->percent)) * 100;
                    $product_vat = $product_real_price * ($vat->percent / 100);
                    $product_tax = $product_real_price * ($tax->percent / 100);
                }

                if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'ex') {
                    $product_real_price = $product_base_rate;
                    $product_vat = $product_real_price * ($vat->percent / 100);
                    $product_tax = $product_real_price * ($tax->percent / 100);
                }
            } else {
                $product_real_price = $product_base_rate;
                $product_vat = 0;
                $product_tax = 0;
            }

            // echo "<pre>";
            // print_r($product_real_price) . "<br>";
            // print_r($product_vat) . "<br>";
            // exit();




            $product_rate = $product_real_price;


            $data2 = (object) array(
                'total_product'  => $available_quantity,
                //                'supplier_price' => $product_information->supplier_price,
                // 'price'          => $product_rate,
                'price'          =>  number_format($product_rate, 2, '.', ''),
                'purchase_price'          => $product_information->purchase_price,
                'supplier_id'    => $product_information->supplier_id,
                'product_id'     => $product_information->product_id,
                'vat'     => (!empty($product_vat) ? $product_vat : 0),
                'vat_percent'     =>   $vat->percent,
                'vat_type'     =>   $vat->vat_tax_type,
                'tax'     => (!empty($product_tax) ? $product_tax : 0),
                'tax_percent'     =>   $tax->percent,
                'tax_type'     => $tax->vat_tax_type,
                //                'product_id'     => $product_information->product_id,
                'product_name'   => $product_information->product_name,
                'product_name_bn'   => $product_information->product_name_bn,
                'product_model'  => $product_information->product_model,
                'sku'  => $product_information->product_id,
                'product_color'  => $product_information->color_name,
                'product_size'   => $product_information->size_name,
                'unit'           => $product_information->unit,
                //                'tax'            => $product_information->tax,
                'image'          => $product_information->image,
                'serial_no'      => $product_information->serial_no,
                'warehouse'      => $product_information->warehouse,
                'warrenty_date'  => $product_information->warrenty_date,
                'expired_date'   => $product_information->expired_date,
            );

            // echo '<pre>';
            // print_r($data2);
            // exit();

            return $data2;
        } else {
            return false;
        }
    }

    //POS customer setup
    public function pos_customer_setup()
    {
        $query = $this->db->select('*')
            ->from('customer_information')
            ->where('customer_name', 'Walking Customer')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }






    //        public function add_cheque($data) {
    //            $this->db->select('*');
    //            $this->db->from('customer_information');
    //            $this->db->where('customer_name', $data['customer_name']);
    //            $query = $this->db->get();
    //            if ($query->num_rows() > 0) {
    //                return FALSE;
    //            } else {
    //                $this->db->insert('customer_information', $data);
    //                return TRUE;
    //            }
    //        }





    //Count invoice
    public function invoice_entry()
    {
        // $customer_id = $this->input->post('customer_id', TRUE);

        // echo "<pre>";
        // print_r($_POST);
        // exit();


        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Web_settings');
        $CI->load->model('Settings');
        $CI->load->model('Products');
        $CI->load->model('Rqsn');
        $CI->load->model('Reports');
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        $invoice_id = $this->generator(10);
        $invoice_id = strtoupper($invoice_id);
        $outlet_id = $this->input->post('outlet_name', TRUE);
        //$outlet_id = $this->session->userdata('outlet_id');
        // echo "<pre>";
        // print_r($outlet_id);
        // exit();
        $datetime = new DateTime((!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d')));
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');
        $quantity = $this->input->post('product_quantity', TRUE);
        $invoice_no_generated = $this->number_generator($outlet_id);

        // echo "<pre>";
        // print_r($invoice_no_generated);
        // exit();
        $Vdate = (!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d'));
        $agg_id = $this->input->post('agg_id', TRUE);
        $customer_id = $this->input->post('customer_id', TRUE);

        $sel_type = $this->input->post('sel_type', TRUE);

        $pay_type = $this->input->post('paytype', TRUE);
        $p_amount = $this->input->post('p_amount', TRUE);
        // echo '<pre>'; print_r(count($pay_type)); exit();
        $cus_card = $this->input->post('cus_card', TRUE);
        $commission = $this->input->post('commission', TRUE);
        $total_commission = $this->input->post('total_commission', TRUE);
        $total_vat = $this->input->post('total_vat', TRUE);
        $total_tax = $this->input->post('total_tax', TRUE);


        $changeamount = $this->input->post('change', TRUE);
        if ($changeamount > 0) {
            $paidamount = $this->input->post('n_total', TRUE);
        } else {
            $paidamount = $this->input->post('paid_amount', TRUE);
        }

        $bank_id = $this->input->post('bank_id_m', TRUE);

        $bkash_id = $this->input->post('bkash_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);
        $nagad_id = $this->input->post('nagad_id', TRUE);
        $rocket_id = $this->input->post('rocket_id', TRUE);
        $available_quantity = $this->input->post('available_quantity', TRUE);
        $currency_details = $this->Web_settings->retrieve_setting_editdata();

        $result = array();
        foreach ($available_quantity as $k => $v) {
            if ($v < $quantity[$k]) {
                $this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_qnty')));
                redirect('Cinvoice');
            }
        }



        $product_id = $this->input->post('product_id', TRUE);
        if ($product_id == null) {
            $this->session->set_userdata(array('error_message' => display('please_select_product')));
            redirect('Cinvoice/pos_invoice');
        }
        //Data inserting into invoice table
        $delivery_type = $this->input->post('deliver_type', TRUE);
        if ($this->input->post('paid_amount', TRUE) <= 0) {
            $datainv = array(
                'invoice_id'      => $invoice_id,
                'customer_id'     => $customer_id,
                'agg_id'     => (!empty($agg_id) ? $agg_id : NULL),
                'date'            => (!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d')),
                // 'month'      =>  $datetime->format("m"),
                // 'year'      => $datetime->format("Y"),
                'time'    => date("h:i A"),
                'total_amount'    => $this->input->post('grand_total_price', TRUE),
                'net_total'    => $this->input->post('n_total', TRUE),
                'rounding'    => $this->input->post('rounding', TRUE),
                'customer_name_two'       => $this->input->post('customer_name_two', TRUE),
                'customer_mobile_two'       => $this->input->post('customer_mobile_two', TRUE),
                'invoice'         => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('inva_details', TRUE)) ? $this->input->post('inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('invoice_discount', TRUE),
                'service_charge' => $this->input->post('service_charge', TRUE),
                'perc_discount' => $this->input->post('perc_discount', TRUE),
                'total_discount'  => $this->input->post('total_discount', TRUE),
                'total_commission'  => $this->input->post('total_commission', TRUE),
                'comm_type'  => $this->input->post('commission_type', TRUE),
                // 'paid_amount'     => $this->input->post('paid_amount', TRUE),
                'paid_amount'     => $paidamount,
                'due_amount'      => $this->input->post('due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'shipping_cost'   => $this->input->post('shipping_cost', TRUE),
                'condition_cost'   => $this->input->post('condition_cost', TRUE),
                'total_tax'   => $this->input->post('total_tax', TRUE),
                'total_vat'   => $this->input->post('total_vat', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'courier_condtion'   => $this->input->post('courier_condtion', TRUE),
                'sales_by'        => $createby,
                'status'          => 1,
                'delivery_type'    =>  $delivery_type,
                'courier_id'         => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id'         => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                'delivery_ac'       =>  $this->input->post('delivery_ac', TRUE),
                'changeamount'       =>  $changeamount,
                // 'outlet_id'       =>  $this->input->post('outlet_name', TRUE),
                'outlet_id'       =>  $outlet_id,
                'reciever_id'       => $this->input->post('deli_reciever', TRUE),
                'receiver_number'     => $this->input->post('del_rec_num', TRUE),
                'discounted_points'     => $this->input->post('applied_amount', TRUE),
                'discounted_points_amount'     => $this->input->post('applied_amount', TRUE),
                'customer_card_no'      => $cus_card,
                'courier_status'      => ($delivery_type == 1) ? 0 : 1
            );

            $this->db->insert('invoice', $datainv);

            $cheque_date = $this->input->post('cheque_date', TRUE);
            $cheque_no = $this->input->post('cheque_no', TRUE);
            $cheque_type = $this->input->post('cheque_type', TRUE);
            $bank_name = $this->input->post('bank_id', TRUE);
            $amount = $this->input->post('amount', TRUE);

            $this->load->library('upload');
            $image = array();

            if ($_FILES['image']['name']) {
                $ImageCount = count($_FILES['image']['name']);
                for ($i = 0; $i < $ImageCount; $i++) {
                    $_FILES['file']['name']       = $_FILES['image']['name'][$i];
                    $_FILES['file']['type']       = $_FILES['image']['type'][$i];
                    $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
                    $_FILES['file']['error']      = $_FILES['image']['error'][$i];
                    $_FILES['file']['size']       = $_FILES['image']['size'][$i];

                    // File upload configuration
                    $uploadPath = 'my-assets/image/cheque/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                    $config['encrypt_name']  = TRUE;

                    // Load and initialize upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    // Upload file to server
                    if ($this->upload->do_upload('file')) {
                        // Uploaded file data

                        $imageData = $this->upload->data();
                        $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                        $image_url = base_url() . $uploadImgData[$i]['image'];
                    }
                }
            }

            if (!empty($cheque_no) && !empty($cheque_date)) {

                foreach ($cheque_no as $key => $value) {


                    $data['cheque_no'] = $value;
                    $data['invoice_id'] = $invoice_id;
                    $data['cheque_id'] = $this->generator(10);
                    $data['cheque_type'] = $cheque_type[$key];
                    $data['cheque_date'] = $cheque_date[$key];
                    $data['bank_name'] = $bank_name;
                    $data['amount'] = $amount[$key];
                    $data['image'] = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
                    $data['status'] = 2;
                    if (!empty($data)) {
                        $this->db->insert('cus_cheque', $data);
                    }
                }
            }
        } else {


            $cheque_date = $this->input->post('cheque_date', TRUE);
            $cheque_no = $this->input->post('cheque_no', TRUE);
            $cheque_type = $this->input->post('cheque_type', TRUE);
            $bank_name = $this->input->post('bank_id', TRUE);
            $amount = $this->input->post('amount', TRUE);



            $this->load->library('upload');
            $image = array();


            if ($_FILES['image']['name']) {
                $ImageCount = count($_FILES['image']['name']);
                for ($i = 0; $i < $ImageCount; $i++) {
                    $_FILES['file']['name']       = $_FILES['image']['name'][$i];
                    $_FILES['file']['type']       = $_FILES['image']['type'][$i];
                    $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
                    $_FILES['file']['error']      = $_FILES['image']['error'][$i];
                    $_FILES['file']['size']       = $_FILES['image']['size'][$i];

                    // File upload configuration
                    $uploadPath = 'my-assets/image/cheque/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                    $config['encrypt_name']  = TRUE;

                    // Load and initialize upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    // Upload file to server
                    if ($this->upload->do_upload('file')) {
                        // Uploaded file data

                        $imageData = $this->upload->data();
                        $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                        $image_url = base_url() . $uploadImgData[$i]['image'];
                    }
                }
            }
            // exit();
            if (!empty($cheque_no) && !empty($cheque_date)) {

                foreach ($cheque_no as $key => $value) {


                    $data['cheque_no'] = $value;
                    $data['invoice_id'] = $invoice_id;
                    $data['cheque_id'] = $this->generator(10);
                    $data['cheque_type'] = $cheque_type[$key];
                    $data['bank_name'] = $bank_name;
                    $data['cheque_date'] = $cheque_date[$key];
                    $data['amount'] = $amount[$key];
                    $data['image'] = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
                    $data['status'] = 2;
                    if (!empty($data)) {
                        $this->db->insert('cus_cheque', $data);
                    }
                }
            }



            $datainv = array(
                'invoice_id'      => $invoice_id,
                'customer_id'     => $customer_id,
                'agg_id'     => (!empty($agg_id) ? $agg_id : NULL),
                // 'month'      =>  $datetime->format("m"),
                // 'year'      => $datetime->format("Y"),
                'date'            => (!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d')),
                'time'    => date("h:i A"),
                'total_amount'    => $this->input->post('grand_total_price', TRUE),
                'net_total'    => $this->input->post('n_total', TRUE),
                'rounding'    => $this->input->post('rounding', TRUE),
                'total_tax'   => $this->input->post('total_tax', TRUE),
                'total_vat'   => $this->input->post('total_vat', TRUE),
                'invoice'         => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('inva_details', TRUE)) ? $this->input->post('inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('invoice_discount', TRUE),
                'service_charge' => $this->input->post('service_charge', TRUE),
                'perc_discount' => $this->input->post('perc_discount', TRUE),
                'total_discount'  => $this->input->post('total_discount', TRUE),
                'total_commission'  => $this->input->post('total_commission', TRUE),
                'comm_type'  => $this->input->post('commission_type', TRUE),
                'customer_name_two'       => $this->input->post('customer_name_two', TRUE),
                'customer_mobile_two'       => $this->input->post('customer_mobile_two', TRUE),
                // 'paid_amount'     => $this->input->post('paid_amount', TRUE),
                'paid_amount'     => $paidamount,
                'due_amount'      => $this->input->post('due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'shipping_cost'   => $this->input->post('shipping_cost', TRUE),
                'condition_cost'   => $this->input->post('condition_cost', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'courier_condtion'   => $this->input->post('courier_condtion', TRUE),
                'changeamount'       =>  $changeamount,
                'sales_by'        => $createby,
                'status'          => 1,
                'delivery_type'    =>  $delivery_type,
                'bank_id'         => (!empty($this->input->post('bank_id', TRUE)) ? $this->input->post('bank_id', TRUE) : null),
                'courier_id'         => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id'         => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                // 'outlet_id'       =>  $this->input->post('outlet_name', TRUE),
                'outlet_id'       =>  $outlet_id,
                'delivery_ac'       =>  $this->input->post('delivery_ac', TRUE),
                'reciever_id'       => $this->input->post('deli_reciever', TRUE),
                'receiver_number'     => $this->input->post('del_rec_num', TRUE),
                'discounted_points'     => $this->input->post('applied_amount', TRUE),
                'discounted_points_amount'     => $this->input->post('applied_amount', TRUE),
                'customer_card_no'      => $cus_card,
                'courier_status'      => ($delivery_type == 1) ? 0 : 1

            );

            $this->db->insert('invoice', $datainv);
        }
        
        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id', $product_id)->group_by('product_id')->get()->result();

        $pr_open_price = $this->db->select('supplier_price')
            ->from('supplier_product')
            ->where_in('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->result();

        $purchase_ave = [];
        $i = 0;
        if ($prinfo) {
            foreach ($prinfo as $avg) {
                $purchase_ave[] =  $avg->product_rate * $quantity[$i];
                $i++;
            }
        } else {
            foreach ($pr_open_price as $avg) {
                $purchase_ave[] =  $avg->supplier_price * $quantity[$i];
                $i++;
            }
        }
        if ($sel_type == 1 || 2) {


            $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
            $headn = $customer_id . '-' . $cusifo->customer_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->customer_name;
        }
        if ($sel_type == 3) {

            $cusifo = $this->db->select('*')->from('aggre_list')->where('id', $agg_id)->get()->row();
            $headn = $agg_id . '-' . $cusifo->aggre_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->aggre_name;
        }

        $grand_total = $this->input->post('grand_total_price', TRUE);
        $total_discount = $this->input->post('total_discount', TRUE);

        $grand_total_wtd = $grand_total + $total_discount + $commission + $total_commission;

        //$grand_total_wtd = $grand_total + $commission + $total_commission;

        $shipping_cost = $this->input->post('shipping_cost', TRUE);
        $service_charge = $this->input->post('service_charge', TRUE);
        $condition_cost = $this->input->post('condition_cost', TRUE);
        $due_amount = $this->input->post('due_amount', TRUE);

        //        if ($due_amount > 0) {
        $cosdr = array(
            'VNo' => $invoice_id,
            'Vtype' => 'INV-CC',
            'VDate' => $Vdate,
            'COAID' => $customer_headcode,
            'Narration' => 'Customer Debit for Sale',
            // 'Debit' => $grand_total_wtd - $this->input->post('shipping_cost', TRUE),
            'Debit' => $grand_total_wtd,
            'Credit' => 0,
            'IsPosted' => 1,
            'CreateBy' => $createby,
            'CreateDate' => $createdate,
            'IsAppove' => 1
        );
        $this->db->insert('acc_transaction', $cosdr);
        //        }


        if ($shipping_cost > 0) {
            $dc_income = array(
                'VNo'            =>  $invoice_id,
                'Vtype'          =>  'INV-CC',
                'VDate'          =>  $Vdate,
                'COAID'          =>  40105,
                'Narration'      =>  'Delivery Charge for Sale',
                'Credit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : 0),
                'Debit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1
            );
            $this->db->insert('acc_transaction', $dc_income);
        }

        if ($service_charge > 0) {
            $sc_income = array(
                'VNo'            =>  $invoice_id,
                'Vtype'          =>  'INV-CC',
                'VDate'          =>  $Vdate,
                'COAID'          =>  308,
                'Narration'      =>  'Service Charge for Sale',
                'Credit'          => (!empty($this->input->post('service_charge', TRUE)) ? $this->input->post('service_charge', TRUE) : 0),
                'Debit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1
            );
            $this->db->insert('acc_transaction', $sc_income);
        }


        $pro_sale_income = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'INVOICE',
            'VDate'          =>  $Vdate,
            'COAID'          =>  303,
            'Narration'      =>  'Sale Income for Sale',
            'Debit'          =>  0,
            'Credit'         => $grand_total_wtd - $this->input->post('shipping_cost', TRUE) - $this->input->post('service_charge', TRUE),
            'IsPosted'       => 1,
            'CreateBy'       => $createby,
            'CreateDate'     => $createdate,
            'IsAppove'       => 1
        );
        $this->db->insert('acc_transaction', $pro_sale_income);


        if ($total_discount > 0) {
            $dis_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => 406,
                'Narration' => 'Sales Discount for Sale',
                'Credit' => 0,
                'Debit' => $total_discount,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $dis_transaction);

            $dis_transaction_2 = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => $customer_headcode,
                // 'Narration' => 'Customer Discount for Invoice ID - ' . $invoice_id,
                'Narration'      =>  'Customer Discount for Sale',
                'Credit' => $total_discount,
                'Debit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $dis_transaction_2);
        }

        if ($commission > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => 410,
                'Narration' => 'Sales Commission for Sale',
                'Credit' => 0,
                'Debit' => $commission,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }
        if ($total_commission > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => 410,
                'Narration' => 'Sales Commission for Sale',
                'Credit' => 0,
                'Debit' => $total_commission,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }

        if ($total_vat > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => 50203,
                'Narration' => 'Total Vat',
                'Credit' => $total_vat,
                'Debit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }

        if ($total_tax > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $Vdate,
                'COAID' => 50204,
                'Narration' => 'Total Tax for Sale',
                'Credit' => $total_tax,
                'Debit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }

        ///Customer credit for Paid Amount
        $paid = $this->input->post('p_amount', TRUE);

        if (count($paid) > 0) {
            for ($i = 0; $i < count($pay_type); $i++) {

                if ($paid[$i] > 0) {
                    if ($pay_type[$i] == 1) {

                        if ($changeamount > 0) {
                            $new_cash_amount = $paid[$i] - $changeamount;
                        } else {
                            $new_cash_amount = $paid[$i];
                        }

                        $cc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  1020101,
                            'Narration'      =>  'Cash Received for Sale',
                            'Debit'          =>  $new_cash_amount,
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $this->db->insert('acc_transaction', $cc);

                        $coscr = array(
                            'VNo' => $invoice_id,
                            'Vtype' => 'INV-CC',
                            'VDate' => $Vdate,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Cash Received for Sale',
                            'Debit' => 0,
                            'Credit' => $new_cash_amount,
                            // 'Credit' =>  $paid[$i] + $total_discount,
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $coscr);


                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $new_cash_amount,
                            'pay_date'      =>  $Vdate,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => 1020101
                        );


                        $this->db->insert('paid_amount', $data);
                    }
                    if ($pay_type[$i] == 4) {
                        if (!empty($bank_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $bankc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Bank Received for Sale',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Bank Received for Sale',
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $cuscredit);

                        $this->db->insert('acc_transaction', $cc);
                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bankname,
                            'COAID'         => $bankcoaid,
                            'pay_date'       =>  $Vdate,
                            'status'        =>  1
                        );

                        $this->db->insert('paid_amount', $data);


                        $this->db->insert('acc_transaction', $bankc);
                    }
                    if ($pay_type[$i] == 3) {
                        if (!empty($bkash_id)) {
                            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
                        } else {
                            $bkashcoaid = '';
                        }
                        $bkashc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bkashcoaid,
                            'Narration'      =>  'Bkash Received for Sale',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $coscr = array(
                            'VNo' => $invoice_id,
                            'Vtype' => 'INV-CC',
                            'VDate' => $Vdate,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Bkash Received for Sale',
                            'Debit' => 0,
                            'Credit' =>  $paid[$i],
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $coscr);

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bkashname,
                            'pay_date'       =>  $Vdate,
                            'COAID'         => $bkashcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);
                        $this->db->insert('acc_transaction', $bkashc);
                    }
                    if ($pay_type[$i] == 5) {

                        if (!empty($nagad_id)) {
                            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
                        } else {
                            $nagadcoaid = '';
                        }

                        $nagadc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $nagadcoaid,
                            'Narration'      =>  'Nagad Received for Sale',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $coscr = array(
                            'VNo' => $invoice_id,
                            'Vtype' => 'INV-CC',
                            'VDate' => $Vdate,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Nagad Received for Sale',
                            'Debit' => 0,
                            'Credit' =>  $paid[$i],
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $coscr);

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'       =>  $Vdate,
                            'account'       => $nagadname,
                            'COAID'         => $nagadcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $this->db->insert('acc_transaction', $nagadc);
                    }
                    if ($pay_type[$i] == 7) {

                        if (!empty($rocket_id)) {
                            $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;

                            $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
                        } else {
                            $rocketcoaid = '';
                        }

                        $rocketc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $rocketcoaid,
                            'Narration'      =>  'Rocket Received for Sale',
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $coscr = array(
                            'VNo' => $invoice_id,
                            'Vtype' => 'INV-CC',
                            'VDate' => $Vdate,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Rocket Received for Sale',
                            'Debit' => 0,
                            'Credit' =>  $paid[$i],
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $coscr);

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'       =>  $Vdate,
                            'account'       => $rocketname,
                            'COAID'         => $rocketcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $this->db->insert('acc_transaction', $rocketc);
                    }
                    if ($pay_type[$i] == 6) {

                        $card_info = $CI->Settings->get_real_card_data($card_id[$i]);

                        if (!empty($card_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $bankc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Card Received for Sale',
                            'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );
                        $coscr = array(
                            'VNo' => $invoice_id,
                            'Vtype' => 'INV-CC',
                            'VDate' => $Vdate,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Card Received for Sale',
                            'Debit' => 0,
                            'Credit' =>  $paid[$i],
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $coscr);

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bankname,
                            'pay_date'       =>  $Vdate,
                            'COAID'         => $bankcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $carddebit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  '40404',
                            'Narration'      =>  'Card Payment Charge Debit',
                            'Debit'          =>  $paid[$i] * ($card_info[0]['percentage'] / 100),
                            'Credit'         =>  0,
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $carddebit);
                        $this->db->insert('acc_transaction', $bankc);
                    }
                }
            }
        }

        $rate                = $this->input->post('product_rate', TRUE);
        $p_id                = $this->input->post('product_id', TRUE);
        $total_amount        = $this->input->post('total_price', TRUE);
        $total_amount_wd        = $this->input->post('total_price_wd', TRUE);
        $discount        = $this->input->post('discount', TRUE);
        $distributed_discount        = $this->input->post('distributed_discount', TRUE);
        $item_total_discount        = $this->input->post('item_total_discount', TRUE);
        $total_discounted_price_wd        = $this->input->post('total_discounted_price_wd', TRUE);
        // $discount        = $this->input->post('discount', TRUE);
        $commission_per        = $this->input->post('comm', TRUE);
        $serial_n            = $this->input->post('serial_no', TRUE);
        $vat_per            = $this->input->post('vat', TRUE);
        $tax_per            = $this->input->post('tax', TRUE);
        if($this->input->post('crm', TRUE) == "enable")
        {
             // Points Earning
        $total_points = $this->input->post('total_points', TRUE);
            if (count($total_points) > 0) {
                for ($i = 0; $i < count($total_points); $i++) {
                    if($total_points[$i] > 0)
                    {
                        $array[] = array(
                            'customer_id' => $this->input->post('customer_id', TRUE),
                            'product_id'         => $p_id[$i],
                            'points'         => $total_points[$i],
                            'invoice_id'         => $invoice_id,
                            'type'         => 1,
                            'created_date' => date('Y-m-d'),
                        );
                    }
                    }
                    $this->db->insert_batch('customers_earning', $array);
                    //$this->db->insert('customers_earning', $array);
                }
                // Points Burning
                $applied_point = $this->input->post('applied_point', TRUE);
                        $array = array(
                            'customer_id' => $this->input->post('customer_id', TRUE),
                            'points'         => $applied_point,
                            'invoice_id'         => $invoice_id,
                            'type'         => 2,
                            'created_date' => date('Y-m-d'),
                            'created_by'       => $this->session->userdata('user_id')
                        );
                  
                    $this->db->insert('customers_earning', $array);
        }
       

        
                //$this->db->insert('customers_earning', $array);
        //   echo "<pre>";
        //   print_r($array);
        //   exit();

        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $serial_no  = (!empty($serial_n[$i]) ? $serial_n[$i] : null);
            $total_price_wd = $total_amount[$i];
            $total_price = (!empty($total_amount_wd[$i]) ? $total_amount_wd[$i] : $total_price_wd);
            $supplier_rate = $this->supplier_price($product_id);
            $product_discount = $discount[$i];
            $product_distributed_discount = $distributed_discount[$i];
            $product_item_total_discount = $item_total_discount[$i];
            $product_total_discounted_price_wd = $total_discounted_price_wd[$i];
            $comm = $commission_per[$i];
            $vat = $vat_per[$i];
            $tax = $tax_per[$i];

            if ($outlet_id == 'HK7TGDT69VFMXB7') {
                $stock_details = $this->Reports->getExpiryCheckList($product_id)['aaData'];
            } else {
                $stock_details = $this->Rqsn->expiry_outlet_stock($product_id)['aaData'];
            }

            echo '<pre>';
            print_r($stock_details);
            exit();

            $product_quantity_new = $product_quantity;
            $inv_details_arr = array();

            foreach ($stock_details as $stock) {
                if ($product_quantity_new < 1 || $stock['stok_quantity'] < 1) {
                    break;
                } else {

                    $rest = ($product_quantity_new >= $stock['stok_quantity']) ? $stock['stok_quantity'] :  $product_quantity_new;
                    $new_array = array(
                        'invoice_details_id' => $this->generator(15),
                        'invoice_id'         => $invoice_id,
                        'purchase_id'         => $stock['purchase_id'],
                        'product_id'         => $product_id,
                        'sn'          => $serial_no,
                        'quantity'           => $rest,
                        'description'        => 'Manual Sales',
                        'supplier_rate'      => $this->supplier_purchase_price($product_id, $stock['purchase_id']),
                        'rate'               => $product_rate,
                        'total_price'        => ($total_price / $product_quantity) * $rest,
                        'discount_per'       => ($product_discount / $product_quantity) * $rest,
                        'total_price_wd'     => ($total_price_wd / $product_quantity) * $rest,
                        'distributed_discount'   => ($product_distributed_discount / $product_quantity) * $rest,
                        'item_total_discount'       => ($product_item_total_discount / $product_quantity) * $rest,
                        'item_total_discounted_price_wd' => ($product_total_discounted_price_wd / $product_quantity) * $rest,
                        'vat'        => ($vat / $product_quantity) * $rest,
                        'tax'        => ($tax / $product_quantity) * $rest,
                        // 'commission_per'       => $comm,
                        'paid_amount'        => $paidamount,
                        'due_amount'         => $this->input->post('due_amount', TRUE),
                        'status'      => 2
                    );
                    $product_quantity_new = $product_quantity_new - $rest;

                    array_push($inv_details_arr, $new_array);
                }
            }

            // echo '<pre>';
            // print_r($inv_details_arr);
            // exit();

            foreach ($inv_details_arr as $ar) {

                if ($ar['quantity'] > 0) {
                    $result = $this->db->insert('invoice_details', $ar);
                }
            }

            // <----- Previous Start ----->
            // $stock = $stock_details[0]['stok_quantity'];



            // $rest = $product_quantity - $stock;


            // $array = array();

            // $aprv_qty = $rest > 0 ? $stock : $product_quantity;

            // $first_array = array(
            //     'invoice_details_id' => $this->generator(15),
            //     'invoice_id'         => $invoice_id,
            //     'purchase_id'         => $stock_details[0]['purchase_id'],
            //     'product_id'         => $product_id,
            //     'sn'          => $serial_no,
            //     'quantity'           => $aprv_qty,
            //     'rate'               => $product_rate,
            //     'description'        => 'Manual Sales',
            //     'discount_per'       => $disper,
            //     'commission_per'       => $comm,
            //     'paid_amount'        => $paidamount,
            //     'due_amount'         => $this->input->post('due_amount', TRUE),
            //     'supplier_rate'      => $supplier_rate,
            //     'total_price'        => $total_price,
            //     'total_price_wd'        => $total_price_wd,
            //     'vat'        => $vat,
            //     'tax'        => $tax,
            //     'status'             => 2
            // );

            // array_push($array, $first_array);

            // // echo "<pre>";
            // // print_r($stock_details);
            // // exit();

            // if ($rest > 0) {
            //     foreach (array_slice($stock_details, 1) as $item) {

            //         $stockQty = $item['stok_quantity'];

            //         if ($stockQty >= $rest) {
            //             $stockQty -= $rest;
            //             $rest = 0;
            //         } else {
            //             $stockQty = 0;
            //             $rest = $rest - $stockQty;
            //         }



            //         $array[] = array(
            //             'invoice_details_id' => $this->generator(15),
            //             'invoice_id'         => $invoice_id,
            //             'product_id'         => $product_id,
            //             'purchase_id'         => $item['purchase_id'],
            //             'sn'          => $serial_no,
            //             'quantity'           => $item['stok_quantity'] - $stockQty,
            //             'rate'               => '',
            //             'description'        => 'Manual Sales',
            //             'discount_per'       => '',
            //             'commission_per'       => '',
            //             'paid_amount'        => '',
            //             'due_amount'         => '',
            //             'supplier_rate'      => '',
            //             'total_price'        => '',
            //             'total_price_wd'        => '',
            //             'vat'        => '',
            //             'tax'        => '',
            //             'status'             => 2
            //         );
            //     }
            // }

            // foreach ($array as $ar) {

            //     if ($ar['quantity'] > 0) {
            //         $result = $this->db->insert('invoice_details', $ar);
            //     }
            // }

            // <----- Previous End ----->



            //
            //            if (!empty($quantity)) {
            //                $this->db->insert('invoice_details', $data1);
            //            }
        }

        //Send Otp To Customer
        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $this->input->post('customer_id', TRUE))->get()->row();
        $mobile_number = $cusifo->customer_mobile;
        $this->sendotp($mobile_number, $this->input->post('n_total', TRUE));

        return $invoice_id;
    }

    public function pos_invoice_entry()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Web_settings');
        $CI->load->model('Settings');
        $CI->load->model('Products');
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        $invoice_id = $this->generator(10);
        $invoice_id = strtoupper($invoice_id);
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');
        $quantity = $this->input->post('product_quantity', TRUE);
        $invoice_no_generated = $this->number_generator();

        $Vdate = $this->input->post('invoice_date', TRUE);
        $customer_id = $this->input->post('customer_id', TRUE);


        $pay_type = $this->input->post('paytype', TRUE);
        $p_amount = $this->input->post('p_amount', TRUE);
        // echo '<pre>'; print_r(count($pay_type)); exit();
        $cus_card = $this->input->post('cus_card', TRUE);


        $changeamount = $this->input->post('change', TRUE);
        if ($changeamount > 0) {
            $paidamount = $this->input->post('n_total', TRUE);
        } else {
            $paidamount = $this->input->post('paid_amount', TRUE);
        }

        $bank_id = $this->input->post('bank_id_m', TRUE);

        $bkash_id = $this->input->post('bkash_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);

        $nagad_id = $this->input->post('nagad_id', TRUE);


        $available_quantity = $this->input->post('available_quantity', TRUE);
        $currency_details = $this->Web_settings->retrieve_setting_editdata();

        $result = array();
        foreach ($available_quantity as $k => $v) {
            if ($v < $quantity[$k]) {
                $this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_qnty')));
                redirect('Cinvoice');
            }
        }



        $product_id = $this->input->post('product_id', TRUE);
        if ($product_id == null) {
            $this->session->set_userdata(array('error_message' => display('please_select_product')));
            redirect('Cinvoice/pos_invoice');
        }


        //Data inserting into invoice table
        $delivery_type = $this->input->post('deliver_type', TRUE);




        if ($this->input->post('paid_amount', TRUE) <= 0) {

            $datainv = array(
                'invoice_id'      => $invoice_id,
                'customer_id'     => $customer_id,
                'agg_id'     => (!empty($agg_id) ? $agg_id : NULL),
                'date'            => (!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d')),
                'total_amount'    => $this->input->post('grand_total_price', TRUE),
                'total_tax'       => $this->input->post('total_tax', TRUE),
                'customer_name_two'       => $this->input->post('customer_name_two', TRUE),
                'customer_mobile_two'       => $this->input->post('customer_mobile_two', TRUE),
                'invoice'         => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('inva_details', TRUE)) ? $this->input->post('inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('invoice_discount', TRUE),
                'perc_discount' => $this->input->post('perc_discount', TRUE),
                'total_discount'  => $this->input->post('total_discount', TRUE),
                'paid_amount'     => $this->input->post('paid_amount', TRUE),
                'due_amount'      => $this->input->post('due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'shipping_cost'   => $this->input->post('shipping_cost', TRUE),
                'condition_cost'   => $this->input->post('condition_cost', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'courier_condtion'   => $this->input->post('courier_condtion', TRUE),
                'changeamount'       =>  $changeamount,
                'sales_by'        => $createby,
                'status'          => 2,
                // 'payment_type'    =>  $this->input->post('paytype',TRUE),
                'delivery_type'    =>  $delivery_type,
                // 'bank_id'         => (!empty($this->input->post('bank_id', TRUE)) ? $this->input->post('bank_id', TRUE) : null),
                // 'bkash_id'         => (!empty($this->input->post('bkash_id', TRUE)) ? $this->input->post('bkash_id', TRUE) : null),
                // 'nagad_id'         => (!empty($this->input->post('nagad_id', TRUE)) ? $this->input->post('nagad_id', TRUE) : null),
                'courier_id'         => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id'         => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                'outlet_id'       =>  $this->input->post('outlet_name', TRUE),
                'reciever_id'       => $this->input->post('deli_reciever', TRUE),
                'receiver_number'     => $this->input->post('del_rec_num', TRUE),
                'customer_card_no'      => $cus_card,
                'courier_status'      => 1,

            );


            // echo '<pre>'; print_r($datainv); exit();
            $this->db->insert('invoice', $datainv);



            $cheque_date = $this->input->post('cheque_date', TRUE);
            $cheque_no = $this->input->post('cheque_no', TRUE);
            $cheque_type = $this->input->post('cheque_type', TRUE);
            $amount = $this->input->post('amount', TRUE);



            $this->load->library('upload');
            $image = array();


            if ($_FILES['image']['name']) {
                $ImageCount = count($_FILES['image']['name']);
                for ($i = 0; $i < $ImageCount; $i++) {
                    $_FILES['file']['name']       = $_FILES['image']['name'][$i];
                    $_FILES['file']['type']       = $_FILES['image']['type'][$i];
                    $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
                    $_FILES['file']['error']      = $_FILES['image']['error'][$i];
                    $_FILES['file']['size']       = $_FILES['image']['size'][$i];

                    // File upload configuration
                    $uploadPath = 'my-assets/image/cheque/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                    $config['encrypt_name']  = TRUE;

                    // Load and initialize upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    // print_r('ues');

                    // Upload file to server
                    if ($this->upload->do_upload('file')) {
                        // Uploaded file data

                        $imageData = $this->upload->data();
                        $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                        $image_url = base_url() . $uploadImgData[$i]['image'];
                        // print_r($image_url);
                    }

                    // echo '<pre>';print_r( $uploadImgData[$i]['image']);exit();
                }
            }
            // exit();
            if (!empty($cheque_no) && !empty($cheque_date)) {

                foreach ($cheque_no as $key => $value) {


                    $data['cheque_no'] = $value;
                    $data['invoice_id'] = $invoice_id;
                    $data['cheque_id'] = $this->generator(10);
                    $data['cheque_type'] = $cheque_type[$key];
                    $data['cheque_date'] = $cheque_date[$key];
                    $data['amount'] = $amount[$key];
                    $data['image'] = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
                    $data['status'] = 2;

                    //  echo '<pre>';print_r($data);
                    // $this->ProductModel->add_products($data);
                    if (!empty($data)) {
                        $this->db->insert('cus_cheque', $data);
                    }
                }
            }
        } else {


            $cheque_date = $this->input->post('cheque_date', TRUE);
            $cheque_no = $this->input->post('cheque_no', TRUE);
            $cheque_type = $this->input->post('cheque_type', TRUE);
            $amount = $this->input->post('amount', TRUE);



            $this->load->library('upload');
            $image = array();


            if ($_FILES['image']['name']) {
                $ImageCount = count($_FILES['image']['name']);
                for ($i = 0; $i < $ImageCount; $i++) {
                    $_FILES['file']['name']       = $_FILES['image']['name'][$i];
                    $_FILES['file']['type']       = $_FILES['image']['type'][$i];
                    $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
                    $_FILES['file']['error']      = $_FILES['image']['error'][$i];
                    $_FILES['file']['size']       = $_FILES['image']['size'][$i];

                    // File upload configuration
                    $uploadPath = 'my-assets/image/cheque/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                    $config['encrypt_name']  = TRUE;

                    // Load and initialize upload library
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    // print_r('ues');

                    // Upload file to server
                    if ($this->upload->do_upload('file')) {
                        // Uploaded file data

                        $imageData = $this->upload->data();
                        $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                        $image_url = base_url() . $uploadImgData[$i]['image'];
                        // print_r($image_url);
                    }

                    // echo '<pre>';print_r( $uploadImgData[$i]['image']);exit();
                }
            }
            // exit();
            if (!empty($cheque_no) && !empty($cheque_date)) {

                foreach ($cheque_no as $key => $value) {


                    $data['cheque_no'] = $value;
                    $data['invoice_id'] = $invoice_id;
                    $data['cheque_id'] = $this->generator(10);
                    $data['cheque_type'] = $cheque_type[$key];
                    $data['cheque_date'] = $cheque_date[$key];
                    $data['amount'] = $amount[$key];
                    $data['image'] = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
                    $data['status'] = 2;

                    //  echo '<pre>';print_r($data);
                    // $this->ProductModel->add_products($data);
                    if (!empty($data)) {
                        $this->db->insert('cus_cheque', $data);
                    }
                }
            }



            $datainv = array(
                'invoice_id'      => $invoice_id,
                'customer_id'     => $customer_id,
                'agg_id'     => (!empty($agg_id) ? $agg_id : NULL),
                'date'            => (!empty($this->input->post('invoice_date', TRUE)) ? $this->input->post('invoice_date', TRUE) : date('Y-m-d')),
                'total_amount'    => $this->input->post('grand_total_price', TRUE),
                'total_tax'       => $this->input->post('total_tax', TRUE),
                'invoice'         => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('inva_details', TRUE)) ? $this->input->post('inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('invoice_discount', TRUE),
                'perc_discount' => $this->input->post('perc_discount', TRUE),
                'total_discount'  => $this->input->post('total_discount', TRUE),
                'customer_name_two'       => $this->input->post('customer_name_two', TRUE),
                'customer_mobile_two'       => $this->input->post('customer_mobile_two', TRUE),
                'paid_amount'     => $this->input->post('paid_amount', TRUE),
                'due_amount'      => $this->input->post('due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'shipping_cost'   => $this->input->post('shipping_cost', TRUE),
                'condition_cost'   => $this->input->post('condition_cost', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'courier_condtion'   => $this->input->post('courier_condtion', TRUE),
                'sales_by'        => $createby,
                'status'          => 1,
                // 'payment_type'    =>  $this->input->post('paytype',TRUE)[0],
                //                'cheque_date'     =>$cheque_d,
                //                'cheque_no'    =>  $cheque,
                'delivery_type'    =>  $delivery_type,
                'changeamount'       =>  $changeamount,
                'bank_id'         => (!empty($this->input->post('bank_id', TRUE)) ? $this->input->post('bank_id', TRUE) : null),
                // 'bkash_id'         => (!empty($this->input->post('bkash_id', TRUE)) ? $this->input->post('bkash_id', TRUE) : null),
                // 'nagad_id'         => (!empty($this->input->post('nagad_id', TRUE)) ? $this->input->post('nagad_id', TRUE) : null),
                'courier_id'         => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id'         => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                'outlet_id'       =>  $this->input->post('outlet_name', TRUE),
                'reciever_id'       => $this->input->post('deli_reciever', TRUE),
                'receiver_number'     => $this->input->post('del_rec_num', TRUE),
                'customer_card_no'      => $cus_card,
                'courier_status'      => 1,

            );


            // echo '<pre>'; print_r($datainv); exit();

            $this->db->insert('invoice', $datainv);
        }


        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id', $product_id)->group_by('product_id')->get()->result();

        $pr_open_price = $this->db->select('supplier_price')
            ->from('supplier_product')
            ->where_in('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->result();

        $purchase_ave = [];
        $i = 0;
        if ($prinfo) {
            foreach ($prinfo as $avg) {
                $purchase_ave[] =  $avg->product_rate * $quantity[$i];
                $i++;
            }
        } else {
            foreach ($pr_open_price as $avg) {
                $purchase_ave[] =  $avg->supplier_price * $quantity[$i];
                $i++;
            }
        }
        $sumval = array_sum($purchase_ave);
        // print_r($sumval);
        // exit();




        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;
        $cs_name = $cusifo->customer_name;


        if ($delivery_type == 2) {
            $courier_condtion = $this->input->post('courier_condtion', TRUE);

            $courier_id = $this->input->post('courier_id', TRUE);
            $corifo = $this->db->select('*')->from('courier_name')->where('courier_id', $courier_id)->get()->row();
            $headn_cour = $corifo->id . '-' . $corifo->courier_name;
            $coainfo_cor = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn_cour)->get()->row();
            $courier_headcode = $coainfo_cor->HeadCode;
            $courier_name = $corifo->courier_name;

            $grand_total = $this->input->post('grand_total_price', TRUE);
            $shipping_cost = $this->input->post('shipping_cost', TRUE);
            $condition_cost = $this->input->post('condition_cost', TRUE);
            $due_amount = $this->input->post('due_amount', TRUE);
            $paid_amount = $this->input->post('paid_amount', TRUE);

            $courier_pay = $grand_total - ($shipping_cost + $condition_cost);
            $courier_pay_partial = $due_amount - ($shipping_cost + $condition_cost);


            $DC = $this->input->post('shipping_cost', TRUE) + $this->input->post('condition_cost', TRUE);

            if ($courier_condtion ==  1) {


                $corcr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Courier Debit For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => 0,
                    'Debit'         => (!empty($courier_pay) ? $courier_pay : null),
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcr);

                $corcc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge and Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => (!empty($DC) ? $DC : null),
                    'Debit'         =>   0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcc);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Debit'          =>  0,
                    'Credit'         => (!empty($DC) ? $DC : null),
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);
            }

            if ($courier_condtion ==  2) {




                $cordr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Courier Debit For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Debit'          => (!empty($courier_pay_partial) ? $courier_pay_partial : null),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cordr);
                $corcc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge and Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => (!empty($DC) ? $DC : null),
                    'Debit'         =>   0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcc);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Debit'          =>  0,
                    'Credit'         => (!empty($DC) ? $DC : null),
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);
            }

            if ($courier_condtion == 3) {

                $cosdr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer debit For Invoice No -  ' . $invoice_no_generated . ' Customer ' . $cs_name,
                    'Debit'          =>  $this->input->post('n_total', TRUE) - (!empty($this->input->post('previous', TRUE)) ? $this->input->post('previous', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cosdr);

                $corcr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Credit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : null),
                    'Debit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcr);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Debit'          =>  0,
                    'Credit'         => (!empty($DC) ? $DC : null),
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);

                //                $this->db->set('courier_paid',1);
                //                $this->db->where('invoice_id',$invoice_id);
                //                $this->db->update('invoice');

            }
        }



        $pro_sale_income = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'INVOICE',
            'VDate'          =>  $Vdate,
            'COAID'          =>  303,
            'Narration'      =>  'Sale Income For Invoice ID - ' . $invoice_id . ' Customer ' . $cs_name,
            'Debit'          =>  0,
            'Credit'         => (!empty($courier_pay) ? $courier_pay : null),
            'IsPosted'       => 1,
            'CreateBy'       => $createby,
            'CreateDate'     => $createdate,
            'IsAppove'       => 1
        );
        $this->db->insert('acc_transaction', $pro_sale_income);

        ///Customer credit for Paid Amount


        $paid = $this->input->post('p_amount', TRUE);
        // echo "<pre>";print_r($paid);

        if (count($paid) > 0) {
            for ($i = 0; $i < count($pay_type); $i++) {

                if ($paid[$i] > 0) {

                    if ($pay_type[$i] == 1) {

                        $cc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  1020101,
                            'Narration'      =>  'Cash in Hand in Sale for Invoice ID - ' . $invoice_id . ' customer- ' . $cs_name,
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'      =>  $Vdate,
                            'status'        =>  1,
                            'account'       => '',
                            'COAID'         => 1020101
                        );

                        $this->db->insert('paid_amount', $data);

                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer credit (Cash In Hand) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cs_name,
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $cuscredit);

                        $this->db->insert('acc_transaction', $cc);
                    }
                    if ($pay_type[$i] == 4) {
                        if (!empty($bank_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $bankc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cs_name,
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bankname,
                            'COAID'         => $bankcoaid,
                            'pay_date'       =>  $Vdate,
                            'status'        =>  1
                        );

                        $this->db->insert('paid_amount', $data);

                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cs_name,
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $cuscredit);

                        $this->db->insert('acc_transaction', $bankc);
                    }
                    if ($pay_type[$i] == 3) {
                        if (!empty($bkash_id)) {
                            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK - ' . $bkashname)->get()->row()->HeadCode;
                        } else {
                            $bkashcoaid = '';
                        }
                        $bkashc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bkashcoaid,
                            'Narration'      =>  'Cash in Bkash paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cs_name,
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bkashname,
                            'pay_date'       =>  $Vdate,
                            'COAID'         => $bkashcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer credit (Cash In Bkash) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cs_name,
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $cuscredit);

                        $this->db->insert('acc_transaction', $bkashc);
                    }
                    if ($pay_type[$i] == 5) {

                        if (!empty($nagad_id)) {
                            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG - ' . $nagadname)->get()->row()->HeadCode;
                        } else {
                            $nagadcoaid = '';
                        }

                        $nagadc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $nagadcoaid,
                            'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                            'Debit'          =>  $paid[$i],
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'pay_date'       =>  $Vdate,
                            'account'       => $nagadname,
                            'COAID'         => $nagadcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer credit (Cash In Nagad) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );
                        $this->db->insert('acc_transaction', $cuscredit);

                        $this->db->insert('acc_transaction', $nagadc);
                    }
                    if ($pay_type[$i] == 6) {

                        $card_info = $CI->Settings->get_real_card_data($card_id[$i]);

                        if (!empty($card_id)) {
                            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                        } else {
                            $bankcoaid = '';
                        }
                        $bankc = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INVOICE',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $bankcoaid,
                            'Narration'      =>  'Paid amount for customer in card - ' . $card_info[0]['card_no'] . '  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                            'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                            'Credit'         =>  0,
                            'IsPosted'       =>  1,
                            'CreateBy'       =>  $createby,
                            'CreateDate'     =>  $createdate,
                            'IsAppove'       =>  1,

                        );

                        $data = array(
                            'invoice_id'    => $invoice_id,
                            'pay_type'      => $pay_type[$i],
                            'amount'        => $paid[$i],
                            'account'       => $bankname,
                            'pay_date'       =>  $Vdate,
                            'COAID'         => $bankcoaid,
                            'status'        =>  1,
                        );

                        $this->db->insert('paid_amount', $data);

                        $cuscredit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  $customer_headcode,
                            'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cs_name,
                            'Debit'          =>  0,
                            'Credit'         =>  $paid[$i],
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );

                        $carddebit = array(
                            'VNo'            =>  $invoice_id,
                            'Vtype'          =>  'INV',
                            'VDate'          =>  $Vdate,
                            'COAID'          =>  '40404',
                            'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice NO- ' . $invoice_no_generated,
                            'Debit'          =>  $paid[$i] * ($card_info[0]['percentage'] / 100),
                            'Credit'         =>  0,
                            'IsPosted'       => 1,
                            'CreateBy'       => $createby,
                            'CreateDate'     => $createdate,
                            'IsAppove'       => 1
                        );


                        $this->db->insert('acc_transaction', $cuscredit);
                        $this->db->insert('acc_transaction', $carddebit);
                        $this->db->insert('acc_transaction', $bankc);
                    }
                }
            }
        }

        // echo '<pre>';print_r($CUS);



        //  $p_id_two=$this->db->query(" SELECT product_id_two FROM `product_information` WHERE product_id=$product_id")->result();

        $rate                = $this->input->post('product_rate', TRUE);
        $p_id                = $this->input->post('product_id', TRUE);
        $total_amount        = $this->input->post('total_price', TRUE);
        $discount_rate       = $this->input->post('discount_amount', TRUE);
        $discount_per        = $this->input->post('discount', TRUE);
        $commission_per        = $this->input->post('comm', TRUE);
        // $tax_amount          = $this->input->post('tax',TRUE);
        $invoice_description = $this->input->post('desc', TRUE);
        $serial_n            = $this->input->post('serial_no', TRUE);
        // $warehouse           =$this->input->post('warehouse',TRUE);
        $warrenty            = $this->input->post('warrenty_date', TRUE);
        // $expiry            = $this->input->post('expiry_date', TRUE);


        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            // $war=$warehouse[$i];
            // $warrenty_date = $warrenty[$i];
            // $expiry_date = $expiry[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $serial_no  = (!empty($serial_n[$i]) ? $serial_n[$i] : null);
            $total_price = $total_amount[$i];
            $supplier_rate = $this->supplier_price($product_id);
            $disper = $discount_per[$i];
            $comm = $commission_per[$i];
            $discount = is_numeric($product_quantity) * is_numeric($product_rate) * is_numeric($disper) / 100;
            // $tax = $tax_amount[$i];
            // $description = $invoice_description[$i];

            $data1 = array(
                'invoice_details_id' => $this->generator(15),
                'invoice_id'         => $invoice_id,
                'product_id'         => $product_id,
                'sn'          => $serial_no,
                'quantity'           => $product_quantity,
                // 'warrenty_date'      => $warrenty_date,
                // 'expiry_date'      => $expiry_date,
                // 'warehouse'          => $war,
                'rate'               => $product_rate,
                'discount'           => $discount,
                'description'        => 'Manual Sales',
                'discount_per'       => $disper,
                'commission_per'       => $comm,
                // 'tax'                => $tax,
                'paid_amount'        => $paidamount,
                'due_amount'         => $this->input->post('due_amount', TRUE),
                'supplier_rate'      => $supplier_rate,
                'total_price'        => $total_price,
                'status'             => 2
            );
            //  echo '<pre>';print_r($data1);exit();
            // $data2 = array(
            //     'purchase_id'=>date('YmdHis'),
            //     'purchase_detail_id' => $this->generator(15),
            //     'product_id'         => $product_id,
            //     'quantity'           => -$product_quantity,
            //     'warehouse'           => $warehouse,
            //     'warrenty_date'      => $warrenty_date,
            //     'rate'               => $product_rate,
            //     'discount'           => $discount,
            //     'total_amount'       => $total_price,
            //     'status'             => 1
            // );

            if (!empty($quantity)) {
                //echo '<pre>';print_r($data1);exit();
                $this->db->insert('invoice_details', $data1);
                //$this->db->insert('product_purchase_details', $data2);
                // $this->db->insert('product_purchase_details', $data2);
            }
        }



        // $message = 'Mr.' . $customerinfo->customer_name . ',
        // ' . 'You have purchase  ' . $this->input->post('grand_total_price', TRUE) . ' ' . $currency_details[0]['currency'] . ' You have paid .' . $this->input->post('paid_amount', TRUE) . ' ' . $currency_details[0]['currency'];


        // $config_data = $this->db->select('*')->from('sms_settings')->get()->row();
        // if ($config_data->isinvoice == 1) {
        //     $this->smsgateway->send([
        //         'apiProvider' => 'nexmo',
        //         'username'    => $config_data->api_key,
        //         'password'    => $config_data->api_secret,
        //         'from'        => $config_data->from,
        //         'to'          => $customerinfo->customer_mobile,
        //         'message'     => $message
        //     ]);
        // }
        // exit();
        return $invoice_id;
    }

    //Get Supplier rate of a product
    public function supplier_rate($product_id)
    {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get();
        return $query->result_array();

        $this->db->select('Avg(rate) as supplier_price');
        $this->db->from('product_purchase_details');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get()->row();
        return $query->result_array();
    }

    public function supplier_price($product_id)
    {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $supplier_product = $this->db->get()->row();


        $this->db->select('Avg(rate) as supplier_price');
        $this->db->from('product_purchase_details');
        $this->db->where(array('product_id' => $product_id));
        $purchasedetails = $this->db->get()->row();
        $price = (!empty($purchasedetails->supplier_price) ? $purchasedetails->supplier_price : $supplier_product->supplier_price);

        return (!empty($price) ? $price : 0);
    }

    public function supplier_Purchase_price($product_id, $purchase_id)
    {


        $this->db->select('rate as supplier_price');
        $this->db->from('product_purchase_details');
        $this->db->where(array('product_id' => $product_id, 'purchase_id' => $purchase_id));
        $purchasedetails = $this->db->get()->row();
        $price = (!empty($purchasedetails->supplier_price) ? $purchasedetails->supplier_price : 0);

        return (!empty($price) ? $price : 0);
    }


    //Retrieve invoice Edit Data
    public function retrieve_invoice_editdata($invoice_id)
    {
        $this->db->select('a.*,cr.courier_name,br.branch_name,rr.receiver_name,rr.id as rid ,a.due_amount as due_amnt, a.paid_amount as p_amnt, sum(c.quantity) as sum_quantity,sum(c.total_price) as sum_amount,c.total_price_wd, a.total_tax as taxs,a.prevous_due,b.customer_name,b.customer_mobile,c.*,c.tax as total_tax,c.product_id,d.product_name,d.product_model,d.tax,d.unit,d.*,a.total_tax as ttx');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('courier_name cr', 'cr.courier_id = a.courier_id', 'left');
        $this->db->join('branch_name br', 'br.branch_id = a.branch_id', 'left');
        $this->db->join('receiever_info rr', 'rr.id = a.reciever_id', 'left');

        // $this->db->join('employee_history u', 'a.sales_by = u.id');

        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $this->db->where('c.is_return', 0);
        $this->db->group_by('d.product_id');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function retrieve_invoice_editdata_new($invoice_id)
    {
        $this->db->select('a.*,cr.courier_name,br.branch_name,rr.receiver_name,rr.id as rid ,a.due_amount as due_amnt, a.paid_amount as p_amnt, sum(c.quantity) as sum_quantity,sum(c.total_price) as sum_amount,a.total_tax as taxs,a.prevous_due,b.customer_name,b.customer_mobile,c.*,sum(c.total_price_wd) as total_price_wd,sum(c.discount_per) as discount_per,sum(c.distributed_discount) as distributed_discount,sum(c.item_total_discount) as item_total_discount,sum(c.item_total_discounted_price_wd) as item_total_discounted_price_wd,sum(c.vat) as vat,sum(c.tax) as total_tax,c.product_id,d.product_name,d.product_model,d.unit,d.*,a.total_tax as ttx');

        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('courier_name cr', 'cr.courier_id = a.courier_id', 'left');
        $this->db->join('branch_name br', 'br.branch_id = a.branch_id', 'left');
        $this->db->join('receiever_info rr', 'rr.id = a.reciever_id', 'left');

        // $this->db->join('employee_history u', 'a.sales_by = u.id');

        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $this->db->where('c.is_return', 0);
        $this->db->group_by('d.product_id');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_invoice_order_data($invoice_id)
    {
        $this->db->select('*');
        $this->db->from('invoice a');
        $this->db->join('courier_name cr', 'cr.courier_id = a.courier_id', 'left');
        $this->db->join('branch_name br', 'br.branch_id = a.branch_id', 'left');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //update_invoice
    public function update_invoice()
    {

        $CI = &get_instance();

        $CI->load->model('Products');

        $Vdate = $this->input->post('invoice_date', TRUE);

        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column  = count($tablecolumn) - 4;
        $invoice_id  = $this->input->post('invoice_id', TRUE);
        $invoice_no  = $this->input->post('invoice', TRUE);
        $createby    = $this->session->userdata('user_id');
        $createdate  = date('Y-m-d H:i:s');
        $customer_id = $this->input->post('customer_id', TRUE);
        $quantity    = $this->input->post('product_quantity', TRUE);
        $product_id  = $this->input->post('product_id', TRUE);
        $delivery_type  = $this->input->post('deliver_type', TRUE);

        $changeamount = $this->input->post('change', TRUE);
        if ($changeamount > 0) {
            $paidamount = $this->input->post('n_total', TRUE);
        } else {
            $paidamount = $this->input->post('paid_amount', TRUE);
        }

        $this->db->where('VNo', $invoice_id);
        $this->db->delete('acc_transaction');
        $this->db->where('relation_id', $invoice_id);
        $this->db->delete('tax_collection');

        $data = array(
            'invoice_id'      => $invoice_id,
            'customer_id'     => $this->input->post('customer_id', TRUE),
            'agg_id'     => $this->input->post('agg_id', TRUE),
            'date'            => $this->input->post('invoice_date', TRUE),
            'total_amount'    => $this->input->post('grand_total_price', TRUE),
            'total_tax'       => $this->input->post('total_tax', TRUE),
            'customer_name_two'       => $this->input->post('customer_name_two', TRUE),
            'customer_mobile_two'       => $this->input->post('customer_mobile_two', TRUE),
            'invoice_details' => $this->input->post('inva_details', TRUE),
            'due_amount'      => $this->input->post('due_amount', TRUE),
            'paid_amount'     => $this->input->post('paid_amount', TRUE),
            'invoice_discount' => $this->input->post('invoice_discount', TRUE),
            'perc_discount' => $this->input->post('perc_discount', TRUE),
            'total_discount'  => $this->input->post('total_discount', TRUE),
            'prevous_due'     => $this->input->post('previous', TRUE),

            'total_commission'  => $this->input->post('total_commission', TRUE),
            'comm_type'  => $this->input->post('commission_type', TRUE),
            'is_pre'     => 1,
            'sale_type'   => $this->input->post('sel_type', TRUE),
            'reciever_id'       => $this->input->post('deli_reciever', TRUE),
            'receiver_number'     => $this->input->post('del_rec_num', TRUE),
            'courier_id'         => $this->input->post('courier_id', TRUE),

            'branch_id'         => $this->input->post('branch_id', TRUE),
            'courier_condtion'   => $this->input->post('courier_condtion', TRUE),
            // 'sales_by'     => $this->input->post('employee_id',TRUE),
            'shipping_cost'   => $this->input->post('shipping_cost', TRUE),
            'service_charge'   => $this->input->post('service_charge', TRUE),

            // 'payment_type'    => (!empty($this->input->post('paytype', TRUE)) ? $this->input->post('paytype', TRUE) : null),
            'delivery_type'    => (!empty($this->input->post('deliver_type', TRUE)) ? $this->input->post('deliver_type', TRUE) : null),
            // 'bank_id'         => (!empty($this->input->post('bank_id', TRUE)) ? $this->input->post('bank_id', TRUE) : null),
            // 'bkash_id'         =>  (!empty($this->input->post('bkash_id',TRUE))?$this->input->post('bank_id',TRUE):null),
            // 'branch_id'         =>  (!empty($this->input->post('branch_id',TRUE))?$this->input->post('branch_id',TRUE):null),
        );

        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id', $product_id)->group_by('product_id')->get()->result();

        $pr_open_price = $this->db->select('supplier_price')
            ->from('supplier_product')
            ->where_in('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->result();

        $purchase_ave = [];
        $i = 0;
        if ($prinfo) {
            foreach ($prinfo as $avg) {
                $purchase_ave[] =  $avg->product_rate * $quantity[$i];
                $i++;
            }
        } else {
            foreach ($pr_open_price as $avg) {
                $purchase_ave[] =  $avg->supplier_price * $quantity[$i];
                $i++;
            }
        }
        $sumval = array_sum($purchase_ave);

        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;
        // Cash in Hand debit

        if ($invoice_id != '') {
            $this->db->where('invoice_id', $invoice_id);
            $this->db->update('invoice', $data);
        }

        for ($j = 0; $j < $num_column; $j++) {
            $taxfield = 'tax' . $j;
            $taxvalue = 'total_tax' . $j;
            $taxdata[$taxfield] = $this->input->post($taxvalue);
        }

        // Inserting for Accounts adjustment.
        ############ default table :: customer_payment :: inflow_92mizdldrv #################

        $invoice_d_id  = $this->input->post('invoice_details_id', TRUE);
        $cartoon       = $this->input->post('cartoon', TRUE);
        $quantity      = $this->input->post('product_quantity', TRUE);
        $rate          = $this->input->post('product_rate', TRUE);
        $p_id          = $this->input->post('product_id', TRUE);
        $total_amount  = $this->input->post('total_price', TRUE);
        $total_amount_wd  = $this->input->post('total_price_wd', TRUE);
        $discount_rate = $this->input->post('total_discount', TRUE);
        $discount_per  = $this->input->post('discount', TRUE);
        $invoice_description = $this->input->post('desc', TRUE);
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_details');
        //        $this->db->where('invoice_id', $invoice_id);
        //        $this->db->delete('paid_amount');
        $serial_n       = $this->input->post('serial_no', TRUE);
        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $cartoon_quantity = $cartoon[$i];
            $product_quantity = $quantity[$i];
            $product_rate     = $rate[$i];
            $product_id       = $p_id[$i];
            $serial_no        = (!empty($serial_n[$i]) ? $serial_n[$i] : null);
            // $war        = (!empty($warehouse[$i])?$warehouse[$i]:null);
            $total_price      = $total_amount[$i];
            $total_price_wd      = $total_amount_wd[$i];
            $supplier_rate    = $this->supplier_price($product_id);
            $discount         = $discount_rate[$i];
            $dis_per          = $discount_per[$i];
            // $desciption        = $invoice_description[$i];
            if (!empty($tax_amount[$i])) {
                $tax = $tax_amount[$i];
            } else {
                $tax = $this->input->post('tax');
            }


            $data1 = array(
                'invoice_details_id' => $this->generator(15),
                'invoice_id'         => $invoice_id,
                'product_id'         => $product_id,
                'sn'          => $serial_no,
                // 'warehouse'          => $war,
                'warrenty_date'          => $serial_no,
                'expiry_date'          => $serial_no,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'discount'           => $discount,
                'total_price'        => $total_price,
                'total_price_wd'        => $total_price_wd,
                'discount_per'       => $dis_per,
                'tax'                => $this->input->post('total_tax', TRUE),
                'paid_amount'        => $paidamount,
                'supplier_rate'     => (!empty($supplier_rate)) ? $supplier_rate : null,
                'due_amount'         => $this->input->post('due_amount', TRUE),
                'pre_order' => 1
                // 'description'       => $desciption,
            );
            $this->db->insert('invoice_details', $data1);





            $customer_id = $this->input->post('customer_id', TRUE);
        }




        $invoice_id  = $this->input->post('invoice_id', TRUE);

        $paid = $this->input->post('p_amount', TRUE);
        $pay_type = $this->input->post('paytype', TRUE);
        $p_amount = $this->input->post('p_amount', TRUE);
        $bank_id = $this->input->post('bank_id_m', TRUE);
        $bkash_id = $this->input->post('bkash_id', TRUE);
        $rocket_id = $this->input->post('rocket_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);
        $nagad_id = $this->input->post('nagad_id', TRUE);
        $sel_type = $this->input->post('sel_type', TRUE);
        $invoice_no_generated = $this->input->post('invoice', TRUE);


        if ($sel_type == 1 || 2) {


            $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
            $headn = $customer_id . '-' . $cusifo->customer_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->customer_name;
        } else if ($sel_type == 3) {

            $cusifo = $this->db->select('*')->from('aggre_list')->where('id', $agg_id)->get()->row();
            $headn = $agg_id . '-' . $cusifo->aggre_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->aggre_name;
        }

        if ($delivery_type == 2) {
            $courier_condtion = $this->input->post('courier_condtion', TRUE);

            $courier_id = $this->input->post('courier_id', TRUE);
            $corifo = $this->db->select('*')->from('courier_name')->where('courier_id', $courier_id)->get()->row();
            $headn_cour = $corifo->id . '-' . $corifo->courier_name;
            $coainfo_cor = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn_cour)->get()->row();
            $courier_headcode = $coainfo_cor->HeadCode;
            $courier_name = $corifo->courier_name;

            $grand_total = $this->input->post('grand_total_price', TRUE);
            $shipping_cost = $this->input->post('shipping_cost', TRUE);
            $condition_cost = $this->input->post('condition_cost', TRUE);
            $due_amount = $this->input->post('due_amount', TRUE);
            $paid_amount = $this->input->post('paid_amount', TRUE);

            $courier_pay = $grand_total - ($shipping_cost + $condition_cost);
            $courier_pay_partial = $due_amount - ($shipping_cost + $condition_cost);


            $DC = $this->input->post('shipping_cost', TRUE) + $this->input->post('condition_cost', TRUE);

            if ($courier_condtion ==  1) {


                $corcr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Courier Debit For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => 0,
                    'Debit'         => (!empty($courier_pay) ? $courier_pay : null),
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcr);

                $corcc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge and Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => (!empty($DC) ? $DC : null),
                    'Debit'         =>   0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcc);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Debit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);

                $condition_charge = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040105,
                    'Narration'      =>  'Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Debit'          => (!empty($this->input->post('condition_cost', TRUE)) ? $this->input->post('condition_cost', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $condition_charge);
            }

            if ($courier_condtion ==  2) {




                $cordr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Courier Debit For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Debit'          => (!empty($courier_pay_partial) ? $courier_pay_partial : null),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cordr);
                $corcc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge and Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    'Credit'          => (!empty($DC) ? $DC : null),
                    'Debit'         =>   0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcc);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Debit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);

                $condition_charge = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040105,
                    'Narration'      =>  'Condition Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Debit'          => (!empty($this->input->post('condition_cost', TRUE)) ? $this->input->post('condition_cost', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $condition_charge);
            }

            if ($courier_condtion == 3) {

                $cosdr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer debit For Invoice No -  ' . $invoice_no_generated . ' Customer ' . $cs_name,
                    'Debit'          =>  $this->input->post('n_total', TRUE) - (!empty($this->input->post('previous', TRUE)) ? $this->input->post('previous', TRUE) : 0),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cosdr);

                $corcr = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  $courier_headcode,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Credit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : null),
                    'Debit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $corcr);

                $dc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV-CC',
                    'VDate'          =>  $Vdate,
                    'COAID'          =>  4040104,
                    'Narration'      =>  'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                    //                'Debit'          =>  $this->input->post('shipping_cost', TRUE),
                    'Debit'          => (!empty($this->input->post('shipping_cost', TRUE)) ? $this->input->post('shipping_cost', TRUE) : null),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $dc);

                //                $this->db->set('courier_paid',1);
                //                $this->db->where('invoice_id',$invoice_id);
                //                $this->db->update('invoice');

            }
        }


        ///Inventory credit
        $coscr = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'INV',
            'VDate'          =>  $Vdate,
            'COAID'          =>  10204,
            'Narration'      =>  'Inventory credit For Invoice No' . $invoice_no_generated,
            'Debit'          =>  0,
            'Credit'         =>  $sumval, //purchase price asbe
            'IsPosted'       => 1,
            'CreateBy'       => $createby,
            'CreateDate'     => $createdate,
            'IsAppove'       => 1
        );



        $pro_sale_income = array(
            'VNo'            =>  $invoice_id,
            'Vtype'          =>  'INVOICE',
            'VDate'          =>  $Vdate,
            'COAID'          =>  303,
            'Narration'      =>  'Sale Income For Invoice ID - ' . $invoice_id . ' Customer ' . $cs_name,
            'Debit'          =>  0,
            'Credit'         => (!empty($courier_pay) ? $courier_pay : 0),
            'IsPosted'       => 1,
            'CreateBy'       => $createby,
            'CreateDate'     => $createdate,
            'IsAppove'       => 1
        );
        $this->db->insert('acc_transaction', $pro_sale_income);

        // echo "<pre>";print_r($paid);
        if (count($paid) > 0) {
            for ($i = 0; $i < count($pay_type); $i++) {

                if ($pay_type[$i] == 1) {

                    $cc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  1020101,
                        'Narration'      =>  'Cash in Hand in Sale for Invoice ID - ' . $invoice_id . ' customer- ' . $cusifo->customer_name,
                        'Debit'          =>  $paid[$i],
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'account'       => '',
                        'COAID'         => 1020101
                    );

                    $this->db->insert('paid_amount', $data);

                    $cuscredit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Customer credit (Cash In Hand) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $paid[$i],
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );
                    $this->db->insert('acc_transaction', $cuscredit);

                    $this->db->insert('acc_transaction', $cc);
                }
                if ($pay_type[$i] == 4) {
                    if (!empty($bank_id)) {
                        $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                        $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                    } else {
                        $bankcoaid = '';
                    }
                    $bankc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $bankcoaid,
                        'Narration'      =>  'Paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                        'Debit'          =>  $paid[$i],
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'account'       => $bankname,
                        'COAID'         => $bankcoaid
                    );

                    $this->db->insert('paid_amount', $data);

                    $cuscredit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $paid[$i],
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );
                    $this->db->insert('acc_transaction', $cuscredit);

                    $this->db->insert('acc_transaction', $bankc);
                }
                if ($pay_type[$i] == 3) {
                    if (!empty($bkash_id)) {
                        $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                        $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
                    } else {
                        $bkashcoaid = '';
                    }
                    $bkashc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $bkashcoaid,
                        'Narration'      =>  'Cash in Bkash paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                        'Debit'          =>  $paid[$i],
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'account'       => $bkashname,
                        'COAID'         => $bkashcoaid
                    );

                    $this->db->insert('paid_amount', $data);

                    $cuscredit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Customer credit (Cash In Bkash) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $paid[$i],
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );
                    $this->db->insert('acc_transaction', $cuscredit);

                    $this->db->insert('acc_transaction', $bkashc);
                }
                if ($pay_type[$i] == 5) {

                    if (!empty($nagad_id)) {
                        $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                        $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
                    } else {
                        $nagadcoaid = '';
                    }

                    $nagadc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $nagadcoaid,
                        'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                        'Debit'          =>  $paid[$i],
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'account'       => $nagadname,
                        'COAID'         => $nagadcoaid,
                    );

                    $this->db->insert('paid_amount', $data);

                    $cuscredit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Customer credit (Cash In Nagad) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $paid[$i],
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );
                    $this->db->insert('acc_transaction', $cuscredit);

                    $this->db->insert('acc_transaction', $nagadc);
                }
                if ($pay_type[$i] == 7) {

                    if (!empty($rocket_id)) {
                        $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;

                        $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $rocketname)->get()->row()->HeadCode;
                    } else {
                        $rocketcoaid = '';
                    }

                    $rocketc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INVOICE',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $nagadcoaid,
                        'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                        'Debit'          =>  $paid[$i],
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'pay_date'       =>  $Vdate,
                        'account'       => $rocketname,
                        'COAID'         => $rocketcoaid,
                        'status'        =>  1,
                    );

                    $this->db->insert('paid_amount', $data);

                    $this->db->insert('acc_transaction', $rocketc);
                }
                if ($pay_type[$i] == 6) {

                    $card_info = $CI->Settings->get_real_card_data($card_id[$i]);

                    if (!empty($card_id)) {
                        $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                        $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                    } else {
                        $bankcoaid = '';
                    }
                    $bankc = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $bankcoaid,
                        'Narration'      =>  'Paid amount for customer in card - ' . $card_info[0]['card_no'] . '  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                        'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $data = array(
                        'invoice_id'    => $invoice_id,
                        'pay_type'      => $pay_type[$i],
                        'amount'        => $paid[$i],
                        'account'       => $bankname,
                        'COAID'         => $bankcoaid,
                    );

                    $this->db->insert('paid_amount', $data);

                    $cuscredit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $paid[$i],
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );

                    $carddebit = array(
                        'VNo'            =>  $invoice_id,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $Vdate,
                        'COAID'          =>  '40404',
                        'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice NO- ' . $invoice_no_generated,
                        'Debit'          =>  $paid[$i] * ($card_info[0]['percentage'] / 100),
                        'Credit'         =>  0,
                        'IsPosted'       => 1,
                        'CreateBy'       => $createby,
                        'CreateDate'     => $createdate,
                        'IsAppove'       => 1
                    );


                    $this->db->insert('acc_transaction', $cuscredit);
                    $this->db->insert('acc_transaction', $carddebit);
                    $this->db->insert('acc_transaction', $bankc);
                }
            }




            // echo '<pre>';print_r($CUS);

        }


        return $invoice_id;
    }

    public function customer_balance($customer_id)
    {
        $this->db->select("
        b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->where('a.customer_id', $customer_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve invoice_html_data
    public function retrieve_invoice_html_data($invoice_id)
    {
        $this->db->select(
            'a.total_tax,
                        a.*,a.paid_amount as paid,
                        b.*,
                        c.*,
                        d.product_id,
                        d.product_name,
                        d.product_name_bn,
                        d.sku,
                        d.image,
                        d.product_details,
                        d.unit,
                        d.product_model,
                        d.price,
                        a.paid_amount as paid_amount,
                        a.due_amount as due_amount,
                        a.status as invoice_type_status,
                        a.outlet_id as outlet_id,
                        m.color_name,
                        n.size_name,
                        e.branch_name,
                        f.courier_name,
                        o.receiver_name,
                        a.receiver_number as rec_num,
                        c.quantity,c.total_price_wd,
                        c.is_return,
                        SUM(c.quantity) as quantity,
                        SUM(c.total_price) as total_price,
                        SUM(c.total_price_wd) as total_price_wd,
                        SUM(c.discount_per) as discount_per,
                        SUM(c.item_total_discount) as item_total_discount,
                        SUM(c.vat) as vat,
                        SUM(c.tax) as tax,
                        '

        );
        $this->db->from('invoice a');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->join('color_list m', 'm.color_id = d.color', 'left');
        $this->db->join('size_list n', 'n.size_id = d.size', 'left');
        $this->db->join('branch_name e', 'e.branch_id = a.branch_id', 'left');
        $this->db->join('courier_name f', 'f.courier_id = a.courier_id', 'left');
        $this->db->join('receiever_info o', 'o.id = a.reciever_id', 'left');
        $this->db->where('a.invoice_id', $invoice_id);
        //  $this->db->where('c.is_return', 0);
        //        $this->db->where('c.quantity >', 0);
        $this->db->group_by(array('c.product_id', 'c.is_return'));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Delete invoice Item
    public function retrieve_product_data($product_id)
    {
        $this->db->select('supplier_price,price,supplier_id,tax');
        $this->db->from('product_information a');
        $this->db->join('supplier_product b', 'a.product_id=b.product.id');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    //Retrieve company Edit Data
    public function retrieve_company()
    {
        $this->db->select('*');
        $this->db->from('company_information');
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Delete invoice Item
    public function delete_invoice($invoice_id)
    {

        $this->db->where('invoice_id', $invoice_id)->delete('invoice');
        //Delete invoice_details table
        $this->db->where('invoice_id', $invoice_id)->delete('invoice_details');
        //Delete transaction from customer_ledger table
        return true;
    }

    public function invoice_search_list($cat_id, $company_id)
    {
        $this->db->select('a.*,b.sub_category_name,c.category_name');
        $this->db->from('invoices a');
        $this->db->join('invoice_sub_category b', 'b.sub_category_id = a.sub_category_id');
        $this->db->join('invoice_category c', 'c.category_id = b.category_id');
        $this->db->where('a.sister_company_id', $company_id);
        $this->db->where('c.category_id', $cat_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // GET TOTAL PURCHASE PRODUCT
    public function get_total_purchase_item($product_id)
    {
        $this->db->select('SUM(quantity) as total_purchase');
        $this->db->from('product_purchase_details');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // GET TOTAL SALES PRODUCT
    public function get_total_sales_item($product_id)
    {
        $this->db->select('SUM(quantity) as total_sale');
        $this->db->from('invoice_details');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Get total product
    public function get_total_product($product_id, $product_status)
    {

        $CI = &get_instance();
        $CI->load->model('Web_settings');
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Warehouse');

        $this->db->select('a.*');
        $this->db->from('product_information a');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));

        $product_information = $this->db->get()->row();

        $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // echo $outlet_id;exit();
        // if ($outlet_id == 'HK7TGDT69VFMXB7') {
        //     $stock = $CI->Reports->getCheckList(null, $product_id)['central_stock'];
        // } else {
        //     $stock = $CI->Rqsn->outlet_stock(null, $product_id)['outlet_stock'];
        // }

        if ($outlet_id == 'HK7TGDT69VFMXB7') {
            //     $stock_details = $CI->Reports->getExpiryCheckList($product_id, 1)['aaData'];
            $stock_details = $CI->Reports->getCheckListNew2(null, $product_id, null, null, null, null, null);
        } else {
            //     $stock_details = $CI->Rqsn->expiry_outlet_stock($product_id, 1)['aaData'];
            $stock_details = $CI->Reports->getCheckListNew2(null, $product_id, null, null, null, null, null);
        }
        // $stock = $stock_details[0]['stok_quantity'];
        $stock = $stock_details['central_stock'];
        //  $stock = $CI->Reports->getCheckList(null, $product_id,'','')['central_stock'];

        // $available_quantity=$CI->Reports->current_stock($product_id,$product_status=null);


        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data2 = array(
            'total_product'  => $stock,
            //            'supplier_price' => $product_information->supplier_price,
            //            'price'          => $product_information->price,
            //            'supplier_id'    => $product_information->supplier_id,
            'unit'           => $product_information->unit,
            'tax'            => $product_information->tax,
            'discount_type'  => $currency_details[0]['discount_type'],
        );

        return $data2;
    }

    // product information retrieve by product id
    public function get_total_product_invoic($product_id, $customer_id)
    {

        $CI = &get_instance();
        $CI->load->model('Web_settings');
        $this->load->model('Reports');
        $this->load->model('Products');
        $this->load->model('Settings');
        $this->load->model('Rqsn');
        $setting   = $this->Settings->read_all_pos_setting();
        $user_id = $this->session->userdata('user_id');

        $outlet_id = $this->input->post('outlet_id', TRUE);
        $is_exp = $this->input->post('is_exp', TRUE);
        // echo "<pre>";
        // print_r($outlet_id);
        // exit();


        $this->db->select('a.*');
        $this->db->from('product_information a');


        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));
        $product_information = $this->db->get()->row();


        $this->db->select('a.*');
        $this->db->from('discount a');
        $this->db->where('a.customer_id', $customer_id);
        $discount = $this->db->get()->row();

        if ($outlet_id == 'HK7TGDT69VFMXB7') {
            $expired_stock = $this->Reports->getExpiryCheckList($product_id, $is_exp)['expired_stock'];
            $available_quantity = $expired_stock;
        } else {
            $expired_stock = $this->Rqsn->expiry_outlet_stock($product_id, $is_exp)['expired_stock'];
            $available_quantity = $expired_stock;
        }


        // if ($is_exp) {


        //     if ($is_exp == 1) {
        //         if ($outlet_id == 'HK7TGDT69VFMXB7') {
        //             $expired_stock = $this->Reports->getExpiryCheckList($product_id)['expired_stock'];

        //             $available_quantity = $expired_stock;
        //         } else {
        //             $expired_stock = $this->Rqsn->expiry_outlet_stock($product_id)['expired_stock'];
        //             $available_quantity = $expired_stock;
        //         }
        //     } else {

        //         if ($outlet_id == 'HK7TGDT69VFMXB7') {
        //             $expired_stock = $this->Reports->getExpiryCheckList($product_id)['expired_stock'];

        //             $total_stock = $this->Reports->getCheckList(null, $product_id, '', '')['central_stock'];
        //             $available_quantity = $total_stock - $expired_stock;
        //         } else {
        //             $expired_stock = $this->Rqsn->expiry_outlet_stock($product_id, $is_exp)['expired_stock'];
        //             $total_stock = $this->Rqsn->outlet_stock(null, $product_id, '', '')['outlet_stock'];
        //             echo '<pre>';
        //             print_r($total_stock);
        //             exit();
        //             $available_quantity = $total_stock - $expired_stock;
        //             echo '<pre>';
        //             print_r($available_quantity);
        //             exit();
        //         }
        //     }
        // } else {
        //     if ($outlet_id == 'HK7TGDT69VFMXB7') {
        //         $expired_stock = $this->Reports->getExpiryCheckList($product_id)['expired_stock'];
        //         $available_quantity = $expired_stock;
        //     } else {
        //         $expired_stock = $this->Rqsn->expiry_outlet_stock($product_id)['expired_stock'];
        //         $available_quantity = $expired_stock;
        //     }
        // }



        $vat = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'vat', 'product_id' => $product_id))->get()->row();
        $tax = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'tax', 'product_id' => $product_id))->get()->row();

        $product_base_rate = $product_information->price;

        if (empty($vat)) {
            $vat->percent = 0;
            $vat->vat_tax_type = 'ex';
        }

        if (empty($tax)) {
            $tax->percent = 0;
            $tax->vat_tax_type = 'ex';
        }


        if ($vat->percent > 0 || $tax->percent > 0) {

            if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'ex') {
                $product_real_price = ($product_base_rate / (100 + $vat->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'in') {
                $product_real_price = ($product_base_rate / (100 + $tax->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'in') {
                $product_real_price = ($product_base_rate / (100 + $tax->percent + $vat->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'ex') {
                $product_real_price = $product_base_rate;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }
        } else {
            $product_real_price = $product_base_rate;
            $product_vat = 0;
            $product_tax = 0;
        }

        $product_rate = $product_real_price;


        $data2['total_product']  = $available_quantity;
        $data2['supplier_price'] = 0;
        $data2['outlet_id'] = $outlet_id;
        $data2['customer_id'] = $customer_id;
        $data2['stock']     = $available_quantity;
        $data2['discount'] = $discount->discount_percentage;
        //  $data2['warehouse']      = $product_information->warehouse;
        // $data2['price']          = $product_rate;
        $data2['price']          =  number_format($product_rate, 2, '.', '');
        $data2['vat']          = (!empty($product_vat) ? $product_vat : 0);
        $data2['tax_percent']            = ($tax) ? $tax->percent : 0;
        $data2['vat_percent']            = ($vat) ? $vat->percent : 0;
        $data2['vat_type']          =   $vat->vat_tax_type;
        $data2['tax']          = (!empty($product_tax) ? $product_tax : 0);
        $data2['tax_type']          = $tax->vat_tax_type;


        $data2['purchase_price']          = $product_information->purchase_price;
        $data2['supplier_id']    = '';

        // $data2['warrenty_date']  = $product_information->warrenty_date;
        // $data2['expired_date']  = $product_information->expired_date;
        $data2['unit']           = $product_information->unit;
        // $data2['tax']            = $product_information->tax;
        // $data2['serial']         = $html;
        // $data2['discount_type']  = $currency_details[0]['discount_type'];
        // $data2['txnmber']        = $num_column;

        // echo "<pre>";
        // print_r($data2);
        // exit();
        return $data2;
    }

    //This function is used to Generate Key
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

    //NUMBER GENERATOR
    public function number_generator($outlet_id)
    {
        $this->db->select_max('invoice', 'invoice');
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get('invoice');
        $result = $query->result_array();
        $invoice_no = $result[0]['invoice'];
        if ($invoice_no != '') {
            $invoice_no = $invoice_no + 1;
        } else {
            $invoice_no = 1000;
        }
        return $invoice_no;
    }
    public function headcode()
    {
        $query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '102030001%'");
        return $query->row();
    }
    //csv invoice
    public function invoice_csv_file()
    {
        $query = $this->db->select('a.invoice,a.invoice_id,b.customer_name,a.date,a.total_amount')
            ->from('invoice a')
            ->join('customer_information b', 'b.customer_id = a.customer_id', 'left')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function category_dropdown()
    {
        $data = $this->db->select("*")
            ->from('cats')
            ->get()
            ->result();

        $list = array('' => 'select_category');
        if (!empty($data)) {
            foreach ($data as $value)
                $list[$value->id] = $value->name;
            return $list;
        } else {
            return false;
        }
    }
    public function category()
    {
        $data = $this->db->select("*")
            ->from('cats')
            ->get()
            ->result();


        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    public function customer_dropdown()
    {
        $data = $this->db->select("*")
            ->from('customer_information')
            ->get()
            ->result();

        $list[''] = 'Select Customer';
        if (!empty($data)) {
            foreach ($data as $value)
                $list[$value->customer_id] = $value->customer_name;
            return $list;
        } else {
            return false;
        }
    }

    public function walking_customer()
    {
        return $data = $this->db->select('*')->from('customer_information')->like('customer_name', 'walking', 'after')->get()->result_array();
    }

    public function allproduct($pr_status = 1)
    {
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->where('a.finished_raw', $pr_status);
        $this->db->order_by('a.product_name', 'asc');
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }

    public function searchprod($pname = null)
    {
        $this->db->select('a.*,b.brand_name');
        $this->db->from('product_information a');
        $this->db->group_start();
        $this->db->like('a.product_name', $pname);
        $this->db->or_like('a.product_name_bn', $pname);
        $this->db->or_like('a.product_model', $pname);
        $this->db->or_like('a.product_id', $pname);
        $this->db->or_like('b.brand_name', $pname);
        $this->db->or_like('a.sku', $pname);
        $this->db->group_end();
        $this->db->join('product_brand b', 'a.brand_id=b.id', 'left');
        $this->db->order_by('a.product_name', 'asc');
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }




    public function service_invoice_taxinfo($invoice_id)
    {
        return $this->db->select('*')
            ->from('tax_collection')
            ->where('relation_id', $invoice_id)
            ->get()
            ->result_array();
    }


    public function customerinfo_rpt($customer_id)
    {
        return $this->db->select('*')
            ->from('customer_information')
            ->where('customer_id', $customer_id)
            ->get()
            ->result_array();
    }


    public function autocompletproductdata($product_name, $pr_status = null)
    {
        $this->db->select('*')
            ->from('product_information');

        // if ($pr_status) {
        //     $this->db->where('product_information.finished_raw', $pr_status);
        // }

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


    public function stock_qty_check($product_id)
    {
        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $total_sale = $this->db->get()->row();

        $this->db->select('a.*,b.*');
        $this->db->from('product_information a');
        $this->db->join('supplier_product b', 'a.product_id=b.product_id');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));
        $product_information = $this->db->get()->row();

        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);
        return (!empty($available_quantity) ? $available_quantity : 0);
    }

    public function payment_details($invoice_id)
    {
        $this->db->select('*')
            ->from('paid_amount')
            ->where('invoice_id', $invoice_id);

        $query = $this->db->get();

        return $query->result_array();
    }
    public function payment_details_total($invoice_id)
    {
        $this->db->select('sum(amount) as amount,pay_type,pay_date,notes')
            ->from('paid_amount')
            ->where('invoice_id', $invoice_id)
            ->group_by('pay_type');

        $query = $this->db->get();

        return $query->result();
    }

    public function due_invoices()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_list = $CI->Warehouse->get_outlet_user();

        $outlet_id = $this->session->userdata('outlet_id');

        // echo "<pre>";
        // print_r($outlet_list);
        // print_r($outlet_id);
        // exit();

        // $query = $this->db->select('invoice_id, date, invoice, customer_id')
        //     ->from('invoice')
        //     ->where('due_amount >', '0')
        //     ->where('outlet_id', $outlet_list[0]['outlet_id'])
        //     ->get();

        $this->db->select('invoice_id, date, invoice, customer_id');
        $this->db->from('invoice');
        $this->db->where('due_amount >', '0');
        if ($outlet_id) {
            $this->db->where('outlet_id', $outlet_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }

    public function get_invoice_details($invoice_id)
    {
        $q = $this->db->select('*')
            ->from('invoice')
            ->where('invoice_id', $invoice_id)
            ->get()
            ->result();

        return $q;
    }
    function upateSystemOption($InvoiceSettingData)
    {
        $this->db->update_batch('invoice_setting', $InvoiceSettingData, 'id');
    }
    function getSettingData()
    {
        $this->db->select('*');
        $this->db->from('invoice_setting');
        $this->db->order_by('invoice_setting.id', 'asc');
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }
    public function invoice_setting_update($data)
    {
        foreach ($data as $d) {
            $this->db->where('OptionSlug', $d['OptionSlug']);
            $this->db->set('status', $d['status']);
            $this->db->update('invoice_setting');
        }
        return true;
    }

    //Sms Snd Function 
    public function sendotp($number, $value)
    {
        $data = $this->db->select('*')->from('sms_settings')->get()->result_array();
        $url = $data[0]['url'];
        $number = "88" . $number;
        $text = $data[0]['sms_heading'] . " " . $value;
        $data = array(
            'username' => $data[0]['username'],
            'password' => $data[0]['password'],
            'number' => $number,
            'message' => $text
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];
        // return $sendstatus;
    }
    // CRM Module Developed By ANTAR NANDI
    public function getPoints($product_id,$membership_id)
    {
        $this->db->select('crm_setting.points');
        $this->db->from('crm_card_map');
        $this->db->join('crm_setting', 'crm_setting.id = crm_card_map.crm_setting_id', 'left');
        $this->db->join('crm_product_map', 'crm_product_map.crm_setting_id = crm_card_map.crm_setting_id', 'left');
        $this->db->where('crm_card_map.card_id', $membership_id);
        $this->db->where('crm_product_map.product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
          
        return false;
    }
    // CRM Module Developed By ANTAR NANDI
    public function getBurningPoints($product_id,$membership_id)
    {
        $this->db->select('burning_setting.points,burning_setting.percentage');
        $this->db->from('burning_card_map');
        $this->db->join('burning_setting', 'burning_setting.id = burning_card_map.crm_setting_id', 'left');
        $this->db->join('burning_product_map', 'burning_product_map.crm_setting_id = burning_card_map.crm_setting_id', 'left');
        $this->db->where('burning_card_map.card_id', $membership_id);
        $this->db->where('burning_product_map.product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
          
        return false;
    }

}

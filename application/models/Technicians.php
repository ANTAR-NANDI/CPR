<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Technicians extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function technician_list()
    {
        $this->db->select('users.*,user_login.*');
        $this->db->from('users');
        $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
        $this->db->where('user_login.user_type', 3);
        return $this->db->get()->result_array();
    }
    //Count Technician
    public function count_technician()
    {
        return $this->db->count_all("commissions");
    }


    public function getCommissionList($postData = null)
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
       

        if($outlet_id == "All")
        {
            $outlet_id = null;
        }

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (users.first_name like '%" . $searchValue . "%' or users.last_name like '%"  . $searchValue . "%'
            or a.rate like '%"  . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('commissions a');
        $this->db->join('users', 'a.technician_id = users.user_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('commissions a');
        $this->db->join('users', 'a.technician_id = users.user_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("a.*,users.first_name,users.last_name");
        $this->db->from('commissions a');
        $this->db->join('users', 'a.technician_id = users.user_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        // $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

                $button .= '<a href="' . $base_url . 'Ctechnician/commission_update_form/' . $record->id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'Ctechnician/commission_delete/' . $record->id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            $data[] = array(
                'sl'               => $sl,
                'technician_name'    => html_escape($record->first_name) . " ".html_escape($record->last_name) ,
                'comission_rate'    => html_escape((int)$record->rate) . " %",
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
    //Retrieve Commission data
    public function commission_detail($commission_id)
    {
        $this->db->select('commissions.id,commissions.rate,commissions.technician_id,users.first_name,users.last_name');
        $this->db->from('commissions');
        $this->db->join('users','users.user_id = commissions.technician_id');
        $this->db->where('commissions.id', $commission_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function total_technicians()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('user_login','user_login.user_id = users.user_id');
        $this->db->where('user_login.user_type', 3);
        return $this->db->get()->num_rows();
       
    }
    public function getTechnicianList($postData = null)
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
       

        if($outlet_id == "All")
        {
            $outlet_id = null;
        }

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (users.first_name like '%" . $searchValue . "%' or users.last_name like '%"  . $searchValue . "%'
            or a.rate like '%"  . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('users');
        $this->db->join('user_login','user_login.user_id = users.user_id');
        $this->db->where('user_login.user_type', 3);
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('users');
        $this->db->join('user_login','user_login.user_id = users.user_id');
        $this->db->where('user_login.user_type', 3);
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select('users.*,user_login.*,outlet_warehouse.outlet_name,users.status');
        $this->db->from('users');
        $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
        $this->db->join('outlet_warehouse', 'outlet_warehouse.outlet_id = users.outlet_id','left');
        $this->db->where('user_login.user_type', 3);
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

                $button .= '<a href="' . $base_url . 'Ctechnician/technician_edit_form/' . $record->user_id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'Ctechnician/technician_delete/' . $record->user_id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            $data[] = array(
                'sl'               => $sl,
                'technician_name'    => html_escape($record->first_name) . " ".html_escape($record->last_name) ,
                'email'    => html_escape($record->username),
                'outlet_name'    => $record->outlet_name? html_escape($record->outlet_name) : "Central Warehouse",
                'status' => $record->status == 1 ? "Active" : "Deactive",
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
    //Retrieve Commission data
    public function technician_detail($technician_id)
    {
        $this->db->select('users.*,user_login.*,users.status');
        $this->db->from('users');
        $this->db->join('user_login', 'user_login.user_id = users.user_id','left');
        $this->db->where('users.user_id', $technician_id);
        return $this->db->get()->result();
        return false;
    }

    
}

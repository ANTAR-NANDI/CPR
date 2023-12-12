<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FundDeductions extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    //Count Technician
    public function total_fund_deductions()
    {
        return $this->db->count_all("fund_deductions");
    }
    public function getFundDeductionList($postData = null)
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
            $searchQuery = " (fund_deductions.name like '%" . $searchValue . "%' or fund_deductions.type like '%"  . $searchValue . "%'
            or fund_deductions.percentage like '%"  . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('fund_deductions a');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('fund_deductions a');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("a.*");
        $this->db->from('fund_deductions a');
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

                $button .= '<a href="' . $base_url . 'Fund_Deduction/fund_deduction_update_form/' . $record->id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'Fund_Deduction/fund_deduction_delete/' . $record->id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            $data[] = array(
                'sl'               => $sl,
                'name'    => html_escape($record->name),
                'type'    => html_escape($record->type),
                'percentage'    => html_escape($record->percentage),
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
    public function fund_deduction_detail($fund_deduction_id)
    {
        $this->db->select('*');
        $this->db->from('fund_deductions');
        $this->db->where('id', $fund_deduction_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }
    
}

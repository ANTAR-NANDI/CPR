<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ltechnician{
    //Commission Add Form
    public function commission_add_form()
    {
        $CI = &get_instance();
        $CI->load->model('Technicians');
        $data = array(
            'title' => "Add Commission",
            'user_list'=> $CI->Technicians->technician_list(),
        );
        $commissionForm = $CI->parser->parse('comission/add_commission', $data, true);
        return $commissionForm;
    }
    //Retrieve  Commission List
    public function commission_list()
    {
        $CI = &get_instance();
        $CI->load->model('Technicians');
        $data['total_technicians']    = $CI->Technicians->count_technician();
        $data['title']             = "Manage Commission";
        $commissionList = $CI->parser->parse('comission/commission', $data, true);
        return $commissionList;
    }
    // Commission Edit Data
    public function commission_edit_data($commission_id)
    {
        $CI = &get_instance();
        $CI->load->model('Technicians');
        $CI->load->model('Userm');
        $commission_detail = $CI->Technicians->commission_detail($commission_id);
        $data = array(
            'title'           => "Edit Commission",
            'id'     => $commission_detail->id,
            'technician_id'     => $commission_detail->technician_id,
            'first_name'     => $commission_detail->first_name,
            'last_name'     => $commission_detail->last_name,
            'rate'            =>  $commission_detail->rate,
            'user_list'=> $CI->Userm->user_list(),
        );
        $chapterList = $CI->parser->parse('comission/edit_commission', $data, true);
        return $chapterList;
    }
     //Technician Add Form
     public function technician_add_form()
     {
         $CI = &get_instance();
         $CI->load->model('Warehouse');
         $outlet_list = $CI->Warehouse->cw_and_outlet_merged();
         $data = array(
             'title' => "Add Technician",
            'outlet_list'   => $outlet_list
         );
         $commissionForm = $CI->parser->parse('comission/add_technician', $data, true);
         return $commissionForm;
     }
     //Retrieve  Commission List
     public function technician_list()
     {
         $CI = &get_instance();
         $CI->load->model('Technicians');
         $data['total_technicians']    = $CI->Technicians->total_technicians();
         $data['title']             = "Manage Technician";
         $commissionList = $CI->parser->parse('comission/technician', $data, true);
         return $commissionList;
     }
      // Commission Edit Data
    public function technician_edit_data($technician_id)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_list = $CI->Warehouse->cw_and_outlet_merged();
        $CI->load->model('Technicians');
        $technician_detail = $CI->Technicians->technician_detail($technician_id);
       
        $data = array(
            'title'           => "Edit Technician",
             'first_name' => $technician_detail[0]->first_name,
             'last_name' => $technician_detail[0]->last_name,
             'username' => $technician_detail[0]->username,
             'user_type' => $technician_detail[0]->user_type,
             'user_id' => $technician_detail[0]->user_id,
             'outlet_id' => $technician_detail[0]->outlet_id,
             'status' => $technician_detail[0]->status,


            'outlet_list'   => $outlet_list
        );
        // echo "<pre>";
        // print_r($data);
        // exit();
        $chapterList = $CI->parser->parse('comission/edit_technician_form', $data, true);
        return $chapterList;
    }

    
}

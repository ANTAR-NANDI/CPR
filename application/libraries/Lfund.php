<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class lfund
{
    //Fund Deduction Add Form
    public function commission_add_form()
    {
        $CI = &get_instance();
        $data = array(
            'title' => "Add Fund & Deduction",
        );
        $commissionForm = $CI->parser->parse('fund_deduction/add_fund_deduction', $data, true);
        return $commissionForm;
    }  
    //Retrieve  Fund Deduction List
    public function fund_deduction_list()
    {
        $CI = &get_instance();
        $CI->load->model('FundDeductions');
        $data['total_fund_deductions']    = $CI->FundDeductions->total_fund_deductions();
        $data['title']             = "Manage Fund & Deduction";
        $fund_deductionList = $CI->parser->parse('fund_deduction/fund_deduction', $data, true);
        return $fund_deductionList;
    }
    public function fund_deduction_edit_data($fund_deduction_id)
    {
        $CI = &get_instance();
        $CI->load->model('FundDeductions');
        $fund_deduction_detail = $CI->FundDeductions->fund_deduction_detail($fund_deduction_id);
        // echo "<pre>";
        // print_r($fund_deduction_detail);
        // exit();
        $data = array(
            'title'           => "Edit Fund Deduction",
            'id'     => $fund_deduction_detail->id,
            'type'     => $fund_deduction_detail->type,
            'percentage'            =>  $fund_deduction_detail->percentage,
            'name'            =>  $fund_deduction_detail->name,

        );
       
        $chapterList = $CI->parser->parse('fund_deduction/edit_fund_deduction', $data, true);
        return $chapterList;
    }
}

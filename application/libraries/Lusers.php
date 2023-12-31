<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lusers
{
    #==============user list================#

    public function user_list()
    {
        $CI = &get_instance();
        $CI->load->model('Userm');
        $CI->load->model('Warehouse');
        $user_list = $CI->Userm->user_list();
        $i = 0;

        if (!empty($user_list)) {
            foreach ($user_list as $k => $v) {
                $i++;
                $user_list[$k]['sl'] = $i;
                if ($user_list[$k]['outlet_id'] == '') {
                    $user_list[$k]['outlet_name'] = '';
                } else if ($user_list[$k]['outlet_id'] == 'HK7TGDT69VFMXB7') {
                    $warehouse_name = $CI->Warehouse->get_central_warehouse($user_list[$k]['outlet_id']);
                    $user_list[$k]['outlet_name'] = $warehouse_name[0]['central_warehouse'];
                } else {
                    $outlet_name = $CI->Warehouse->get_outlet($user_list[$k]['outlet_id']);
                    $user_list[$k]['outlet_name'] = $outlet_name[0]['outlet_name'];
                }
            }
        }
        // echo "<pre>";
        // print_r($user_list);
        // exit();
        $data = array(
            'title'     => display('manage_users'),
            'user_list' => $user_list,
        );
        $userList = $CI->parser->parse('users/user', $data, true);
        return $userList;
    }

    #=============User Search item===============#

    public function user_search_item($user_id)
    {
        $CI = &get_instance();
        $CI->load->model('Userm');
        $user_list = $CI->Userm->user_search_item($user_id);
        $i = 0;
        foreach ($user_list as $k => $v) {
            $i++;
            $user_list[$k]['sl'] = $i;
        }
        $data = array(
            'title'     => display('manage_users'),
            'user_list' => $user_list
        );
        $userList = $CI->parser->parse('users/user', $data, true);
        return $userList;
    }

    #==============User add form===========#

    public function user_add_form()
    {
        $CI = &get_instance();
        $CI->load->model('Userm');
        $CI->load->model('Warehouse');

        $outlet_list = $CI->Warehouse->cw_and_outlet_merged();

        // echo '<pre>';
        // print_r($outlet_list);
        // print_r($central);
        // exit();

        $data = array(
            'title' => display('manage_users'),
            'outlet_list'   => $outlet_list
        );
        $userForm = $CI->parser->parse('users/add_user_form', $data, true);
        return $userForm;
    }

    #================Insert user==========#

    public function insert_user($data)
    {
        // echo "x";
        // print_r($data);
        // exit();

        $CI = &get_instance();
        $CI->load->model('Userm');
        $CI->Userm->user_entry($data);
        return true;
    }

    #===============User edit form==============#

    public function user_edit_data($user_id)
    {
        $CI = &get_instance();
        $CI->load->model('Userm');
        $user_detail = $CI->Userm->retrieve_user_editdata($user_id);
        $CI->load->model('Warehouse');

        $outlet_list = $CI->Warehouse->cw_and_outlet_merged();

        if ($user_detail[0]['outlet_id'] == '') {
            $user_detail[0]['outlet_name'] = '';
        } else if ($user_detail[0]['outlet_id'] == 'HK7TGDT69VFMXB7') {
            $warehouse_name = $CI->Warehouse->get_central_warehouse($user_detail[0]['outlet_id']);
            $user_detail[0]['outlet_name'] = $warehouse_name[0]['central_warehouse'];
            // $user_detail[0]['outlet_name'] = 'Central';
        } else {
            $outlet_name = $CI->Warehouse->get_outlet($user_detail[0]['outlet_id']);
            $user_detail[0]['outlet_name'] = $outlet_name[0]['outlet_name'];
            // $user_detail[0]['outlet_name'] = 'Outlet';
        }


        $data = array(
            'title'      => display('user_edit'),
            'user_id'    => $user_detail[0]['user_id'],
            'outlet_id'  => $user_detail[0]['outlet_id'],
            'outlet_list'  => $outlet_list,
            'first_name' => $user_detail[0]['first_name'],
            'last_name'  => $user_detail[0]['last_name'],
            'username'   => $user_detail[0]['username'],
            'password'   => $user_detail[0]['password'],
            'logo'       => $user_detail[0]['logo'],
            'status'     => $user_detail[0]['status'],
            'outlet_name' => $user_detail[0]['outlet_name'],
        );

        // echo "<pre>";
        // print_r($user_detail);
        // exit();

        $companyList = $CI->parser->parse('users/edit_users_form', $data, true);
        return $companyList;
    }
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ctechnician extends CI_Controller
{

    public $menu;

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('Ltechnician');
        $this->load->library('session');
        $this->load->model('Technicians');
        $this->auth->check_admin_auth();
    }

    //Add Commission
    public function index()
    {
        $content = $this->ltechnician->commission_add_form();
        $this->template->full_admin_html_view($content);
    }
    public function checkCommission()
    {
        $technician_id = $this->input->post('technician_id') ? $this->input->post('technician_id') : '';
        $CI = & get_instance();
        $data = $CI->db->select('*')->from('commissions')->where('technician_id',$technician_id)->get()->result_array();
        echo json_encode($data);
    }
    // insert Commission
    public function insert_comission()
    {
        $CI = &get_instance();
        $this->load->model('Warehouse');
        $outlet_id = $CI->session->userdata('outlet_id');
        $outlet_list = $CI->Warehouse->get_outlet($outlet_id);
        $central_warehouse = $CI->Warehouse->central_warehouse();
        $data = array(
            'technician_id'   => $this->input->post('user_id', TRUE),
            'rate'   => $this->input->post('commission_rate', TRUE),
            'outlet_id'       => $outlet_list ? $outlet_list[0]['outlet_id'] : $central_warehouse[0]['warehouse_id'],
            'created_date'   => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id'),
            'status'          => 1,    
        );
      

        $this->db->insert('commissions', $data);
        $commission_id = $this->db->insert_id();
        if ($commission_id) {
            redirect(base_url('Ctechnician/manage_commission'));
            exit;
        }else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Ctechnician'));
            exit;
        }
    }
    //Manage Commission
    public function manage_commission()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('ltechnician');
        $content = $this->ltechnician->commission_list();
        $this->template->full_admin_html_view($content);
    }
    public function Commission_List()
    {
        $this->load->model('Technicians');
        $postData = $this->input->post();
        $data = $this->Technicians->getCommissionList($postData);
        echo json_encode($data);
    }
    //customer Update Form
    public function commission_update_form($commission_id)
    {
        $content = $this->ltechnician->commission_edit_data($commission_id);
        $this->template->full_admin_html_view($content);
    }
    public function update_commission()
    {
        $this->load->model('Technicians');
        $commission_id = $this->input->post('commission_id', TRUE);
        $data = array(
            'technician_id' => $this->input->post('user_id', TRUE),
            'rate'   => $this->input->post('commission_rate', TRUE),
            'updated_date'   => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id')
        );
        $this->db->where('id', $commission_id);
        $result = $this->db->update('commissions', $data);
        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Ctechnician/manage_commission'));
            exit;
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Ctechnician'));
        }
    }
    public function commission_delete($commission_id)
    {
        $this->load->model('Technicians');
        $this->db->where('id', $commission_id);
        $this->db->delete('commissions');
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Ctechnician/manage_commission'));
        
    }
    public function checkEmail()
    {
        $email = $this->input->post('email') ? $this->input->post('email') : '';
        $CI = & get_instance();
        $CI->db->select('user_login.*');
        $CI->db->from('user_login');
        $CI->db->where('user_login.username', $email);
        $data = $this->db->get()->result();
        echo json_encode($data);
    }
     //Add Technician
     public function add()
     {
         $content = $this->ltechnician->technician_add_form();
         $this->template->full_admin_html_view($content);
     }
     // Random Id generator
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
     public function store_technician()
     {
        
         $this->load->library('upload');
         $this->load->library('lusers');
         if (($_FILES['logo']['name'])) {
             $files = $_FILES;
             $config = array();
             $config['upload_path'] = 'assets/dist/img/profile_picture/';
             $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
             $config['max_size'] = '1000000';
             $config['max_width'] = '1024000';
             $config['max_height'] = '768000';
             $config['overwrite'] = FALSE;
             $config['encrypt_name'] = true;
 
             $this->upload->initialize($config);
             if (!$this->upload->do_upload('logo')) {
                 $sdata['error_message'] = $this->upload->display_errors();
                 $this->session->set_userdata($sdata);
                 redirect('user');
             } else {
                 $view = $this->upload->data();
                 $logo = base_url($config['upload_path'] . $view['file_name']);
             }
         }
         $data = array(
             'user_id'    => $this->generator(15),
             'outlet_id' => $this->input->post('outlet', true),
             'first_name' => $this->input->post('first_name', true),
             'last_name'  => $this->input->post('last_name', true),
              'phone'       => $this->input->post('mobile', true),
             'email'      => $this->input->post('email', true),
             'password'   => md5("gef" . $this->input->post('password', true)),
             'user_type'  => $this->input->post('user_type', true),
             'logo'       => (!empty($logo) ? $logo : base_url() . 'assets/dist/img/profile_picture/profile.jpg'),
             'status'     => 1,
             'created_date'   => date('Y-m-d'),
             'created_by'       => $this->session->userdata('user_id'),
         );
         $this->lusers->insert_user($data);
         $this->session->set_userdata(array('message' => display('successfully_added')));
         if (isset($_POST['add-technician'])) {
             redirect('Ctechnician/manage_technician');
         } elseif (isset($_POST['add-technician-another'])) {
             redirect(base_url('Ctechnician/manage_technician'));
         }
     }
     public function manage_technician()
    {
        $content = $this->ltechnician->technician_list();
        $this->template->full_admin_html_view($content);
    }
    public function technician_List()
    {
        $this->load->model('Technicians');
        $postData = $this->input->post();
        $data = $this->Technicians->getTechnicianList($postData);
        echo json_encode($data);
    }
    public function technician_delete($technician_id)
    {
        $this->load->model('Technicians');
        $this->db->where('user_id', $technician_id);
        $this->db->delete('users');
        $this->db->where('user_id', $technician_id);
        $this->db->delete('user_login');
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Ctechnician/manage_technician'));
    }
    //customer Update Form
    public function technician_edit_form($technician_id)
    {
        $content = $this->ltechnician->technician_edit_data($technician_id);
        $this->template->full_admin_html_view($content);
    }
    public function update_technician()
    {
        $this->load->model('Technicians');
        $technician_id = $this->input->post('user_id', TRUE);
        $data = array(
            'outlet_id' => $this->input->post('outlet_id', TRUE),
            'first_name'   => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'status'     => $this->input->post('status', TRUE),
            'updated_date'   => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id'),
        );
        $this->db->where('user_id', $technician_id);
        $result = $this->db->update('users', $data);
         $data = array(
            'password'   => md5("gef" . $this->input->post('password', true)),
            'username'   => $this->input->post('username', TRUE),
        );
        $this->db->where('user_id', $technician_id);
        $result = $this->db->update('user_login', $data);
        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Ctechnician/manage_technician'));
            exit;
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Ctechnician/add'));
        }
    }


   
}

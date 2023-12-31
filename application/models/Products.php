<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //Count Product
    public function count_product()
    {
        return $this->db->count_all("product_information");
    }

    //Product List
    public function product_list($per_page, $page)
    {
        $query = $this->db->select('supplier_information.*,product_information.*,supplier_product.*')
            ->from('product_information')
            ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
            ->join('supplier_information', 'supplier_information.supplier_id = supplier_product.supplier_id', 'left')
            ->order_by('product_information.product_name', 'asc')
            ->limit($per_page, $page)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function get_product_by_category($cat_id)
    {
        $this->db->select('product_information.product_id,product_information.product_name,cats.name as category_name,product_information.category_id');
        $this->db->join('cats', 'product_information.category_id = cats.id');
        $this->db->where_in('product_information.category_id', $cat_id);
        $result =  $this->db->get('product_information')->result();
        $res = array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                if (!isset($res[$value->category_id])) {
                    $res[$value->category_id] = array();
                }
                array_push($res[$value->category_id], $value);
            }
        }
        return $res;
    }

    public function barcode_details($barcode_id)
    {
        $result = $this->db->select('b.*,p.sku,p.price,p.tag_code,p.purchase_price, p.product_name')
            ->from('barcode_print a')
            ->join('barcode_print_details b', 'a.barcode_id=b.barcode_id')
            ->join('product_information p', 'p.product_id=b.product_id')
            ->where('a.barcode_id', $barcode_id)
            ->get()
            ->result();

        return $result;
    }

    public function get_all_product()
    {
        $data = $this->db->select('product_id,product_name,product_name_bn')->from('product_information')->get()->result();

        if (!empty($data)) {
            //            $list['All'] = 'Select All';
            foreach ($data as $value)
                $list[$value->product_id] = $value->product_name_bn . '(' . $value->product_name . ')';
            return $list;
        } else {
            return false;
        }
    }
    public function allproductlist()
    {
        $data = $this->db->select('product_information.*')->from('product_information')->get()->result_array();
        return $data;
    }


    //All Product List
    public function all_product_list($pr_status = null, $just_id = null)
    {
        $selection = $just_id ? 'product_information.product_id' : 'supplier_information.*,product_information.*,supplier_product.*';

        $this->db->select($selection)
            ->from('product_information')
            ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
            ->join('supplier_information', 'supplier_information.supplier_id = supplier_product.supplier_id', 'left')
            ->order_by('product_information.product_id', 'desc');

        if ($pr_status) {
            $this->db->where('finished_raw', $pr_status);
        }
        $query =  $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function getProductList($postData = null)
    {
        $this->load->library('occational');
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
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_name_bn like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue  . "%' or a.sku like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%'  ) ";
        }

        // if ($searchValue != '') {
        //     $searchQuery = "(a.product_name like '%" . $searchValue . "%' or a.product_name_bn like '%" . "%')";
        // }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        // $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        // $this->db->join('product_category x', 'x.category_id = a.category_id', 'left');
        // $this->db->join('product_type d', 'd.ptype_id = a.ptype_id', 'left');
        // $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;


        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        // $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        //  $this->db->join('product_category x', 'x.category_id = a.category_id', 'left');
        //$this->db->join('product_type d', 'd.ptype_id = a.ptype_id', 'left');
        // $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;


        ## Fetch records
        $this->db->select("a.*
             
                ");
        $this->db->from('product_information a');
        // $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        // $this->db->join('product_category x', 'x.category_id = a.category_id', 'left');
        // $this->db->join('product_type d', 'd.ptype_id = a.ptype_id', 'left');
        // $this->db->join('products k', 'k.barcode = a.product_id', 'left');
        //$this->db->join('size_list l', 'l.size_id = a.size', 'left');
        // $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        // echo "<pre>";
        // print_r($records[0]);
        // exit();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

            // $image = '<img src="' . $record->image . '" class="img img-responsive" height="50" width="50">';
            $image = '<img src="' . $record->image . '" class="img img-responsive" height="50" width="50">';
            if ($this->permission1->method('manage_product', 'delete')->access()) {

                $button .= '<a href="' . $base_url . 'Cproduct/product_delete/' . $record->product_id . '" class="btn btn-xs btn-danger "  onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            }

            //            $button .= '  <a href="' . $base_url . 'Cqrcode/qrgenerator/' . $record->product_id . '" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('qr_code') . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a>';

            $button .= '  <a href="' . $base_url . 'Cbarcode/barcode_print/' . $record->product_id . '" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('barcode') . '"><i class="fa fa-barcode" aria-hidden="true"></i></a>';
            if ($this->permission1->method('manage_product', 'update')->access()) {
                $button .= ' <a href="' . $base_url . 'Cproduct/product_update_form/' . $record->product_id . '" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            }

            $product_name = '<a href="' . $base_url . 'Cproduct/product_details/' . $record->product_id . '">' . $record->product_name . '</a>';
            $product_name_bn = '<a href="' . $base_url . 'Cproduct/product_details/' . $record->product_id . '">' . $record->product_name_bn . '</a>';
            //$supplier = '<a href="' . $base_url . 'Csupplier/supplier_ledger_info/' . $record->supplier_id . '">' . $record->supplier_name . '</a>';
            $product_status = '';
            if ($record->finished_raw == 1) {
                $product_status = 'Finished Goods';
            }
            if ($record->finished_raw == 2) {
                $product_status = 'Raw Materials';
            }
            if ($record->finished_raw == 3) {
                $product_status = 'Tools';
            }
            $data[] = array(
                'sl'               => $sl,
                'product_name_bn'     => $product_name_bn,
                'product_name'     => $product_name,
                'created_date'    => $this->occational->dateConvert($record->created_date),
                'product_category'    => $record->category_id,
                //'product_code'     => $record->product_code,
                //'product_type'    => $record->ptype_name,
                //'product_size'    => $record->size_name,
                'product_status'      => $product_status,
                //'color'    => $record->color_name,
                'sku'    => $record->sku,
                //'supplier_name'    => $supplier,
                'price'            => $record->price,
                //'purchase_p'       => $record->supplier_price,
                'image'            => $image,
                'button'           => $button,

            );
            // echo '<pre>';
            // print_r($data);
            // exit();
            $sl++;
        }

        //  echo '<pre>';
        //     print_r($data);
        //     exit();
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }
    public function getVatProductList($postData = null)
    {
        $this->load->library('occational');
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
            $searchQuery = "(a.product_name like '%" . $searchValue ."%' or a.product_name_bn like '%" .$searchValue. "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('vat_tax_setting c');
        // $this->db->group_by('c.product_id');
        $this->db->join('product_information a', 'c.product_id = a.product_id', 'left');

        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;
        // echo "<pre>";
        // print_r($searchQuery);
        // exit();


        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('vat_tax_setting c');
        // $this->db->group_by('c.product_id');
        $this->db->join('product_information a', 'c.product_id = a.product_id', 'left');

        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;


        ## Fetch records
        $this->db->select("a.*,c.*
             
                ");
        $this->db->from('vat_tax_setting c');
        $this->db->join('product_information a', 'c.product_id = a.product_id', 'left');
        $this->db->group_by('a.product_id');

        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        // echo "<pre>";
        // print_r($records[0]);
        // exit();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";


            if ($this->permission1->method('manage_product', 'update')->access()) {
                $button .= ' <a href="' . $base_url . 'Cproduct/product_update_form/' . $record->product_id . '" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            }

            $product_name = '<a href="' . $base_url . 'Cproduct/product_details/' . $record->product_id . '">' . $record->product_name . '</a>';
            $product_name_bn = '<a href="' . $base_url . 'Cproduct/product_details/' . $record->product_id . '">' . $record->product_name_bn . '</a>';

            $product_status = '';
            if ($record->finished_raw == 1) {
                $product_status = 'Finished Goods';
            }
            if ($record->finished_raw == 2) {
                $product_status = 'Raw Materials';
            }
            if ($record->finished_raw == 3) {
                $product_status = 'Tools';
            }

            $vat_percent = $this->db->select('vat_tax_type,percent')
                ->from('vat_tax_setting')
                ->where(array('vat_tax' => 'vat', 'product_id' => $record->product_id))
                ->get()->row();

            $tax_percent = $this->db->select('vat_tax_type,percent')
                ->from('vat_tax_setting')
                ->where(array('vat_tax' => 'tax', 'product_id' => $record->product_id))
                ->get()->row();

            $vat = '';
            if ($vat_percent->vat_tax_type == 'in') {
                $vat = '<span class="label label-danger "><b>' . $vat_percent->percent . '% Inclusive</b></span>';
                //
            }
            if ($vat_percent->vat_tax_type == 'ex') {
                $vat = '<span class="label label-success "><b>' . $vat_percent->percent . '% Exclusive</b></span>';
            }

            $tax = '';
            if ($tax_percent->vat_tax_type == 'in') {

                $tax = '<span class="label label-danger "><b>' . $tax_percent->percent . '% Inclusive</b></span>';
            }
            if ($tax_percent->vat_tax_type == 'ex') {
                $tax = '<span class="label label-success "><b>' . $tax_percent->percent . '% Exclusive</b></span>';
            }

            $data[] = array(
                'sl'               => $sl,
                'product_name_bn'     => $product_name_bn,
                'product_name'     => $product_name,
                'created_date'    => $this->occational->dateConvert($record->created_at),
                'product_category'    => $record->category_id,
                'vat'     => $vat_percent > 0 ? $vat : '<span class="label label-warning "><b>NAN</b></span>',
                'tax'     => $tax_percent > 0 ? $tax :  '<span class="label label-warning "><b>NAN</b></span>',
                //                'vat_tax_type'    => $vat_type,
                //                'percent'    => $vat_type,
                'product_status'      => $product_status,
                //'color'    => $record->color_name,
                'sku'    => $record->sku,
                //'supplier_name'    => $supplier,
                'price'            => $record->price,
                //'purchase_p'       => $record->supplier_price,
                'button'           => $button,

            );
            // echo '<pre>';
            // print_r($data);
            // exit();
            $sl++;
        }

        //  echo '<pre>';
        //     print_r($data);
        //     exit();
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }


    public function vat_tax_data($product_id)
    {

        $this->db->select('*')->from('vat_tax_setting')->where('product_id', $product_id);

        $query =  $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function getFinishedProductList($postData = null)
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

        $data = array();
        $sl = 1;

        //count product




        //search  product

        if ($searchValue != '') {

            $url = api_url() . "products/count_product_search/" . $searchValue;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $total_product = curl_exec($curl);
            curl_close($curl);



            $url = api_url() . "products/search_products/" . $searchValue . "/" . $rowperpage;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $records = json_decode($resp)->data; //fetch all product

            //            echo '<pre>';
            //            print_r($records);
            //            exit();
        } else {


            $url = api_url() . "products/count_product";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $total_product = curl_exec($curl);
            curl_close($curl);

            $url = api_url() . "products/get_products/" . $rowperpage;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $records = json_decode($resp)->data;
        }






        //        $totalRecordwithFilter = $records[0]->allcount;
        foreach ($records as $record) {
            $button = '';
            //            https://swaponsworld.com/public/uploads/products/thumbnail/7nKgZ7HuR0f0qB4dwtxKCQ1CxFe37Qmnrulzjzp0.jpeg
            if ($this->permission1->method('manage_product', 'update')->access()) {
                $button .= ' <a href="'  . 'product_update_form/' . $record->id . '" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $button .= ' <a href="'  . 'product_update_form/' . $record->id . '" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }

            $image = '<img src="https://dev.swaponsworld.com/public/' . $record->thumbnail_image . '" class=" img-responsive img-zoom" height="50" width="50">';
            $site_url = 'https://swaponsworld.com';
            $product_name = '<a href="'  . $site_url .  '/product/' . $record->slug . '">' . $record->name . '</a>';

            $data[] = array(
                'sl'               => $sl,
                'product_name'     => $product_name,
                'price'            =>  $record->base_price,
                'sku'              =>  $record->sku,
                'image'            =>  $image,
                'button'           =>  $button,

            );

            $sl++;
        }



        //        var_dump($resp);


        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $total_product,
            "iTotalDisplayRecords" => $total_product,
            "aaData" => $data
            //"aaData" => $json_data
        );
        //        echo '<pre>';
        //        print_r(count($records));
        //        exit();
        return $response;
    }



    //Product List
    public function product_list_count()
    {
        $query = $this->db->select('supplier_information.*,product_information.*,supplier_product.*')
            ->from('product_information')
            ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
            ->join('supplier_information', 'supplier_information.supplier_id = supplier_product.supplier_id', 'left')
            ->order_by('product_information.product_name', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //Product tax list
    public function retrieve_product_tax()
    {
        $result = $this->db->select('*')
            ->from('tax_information')
            ->get()
            ->result();

        return $result;
    }

    //Tax selected item
    public function tax_selected_item($tax_id)
    {
        $result = $this->db->select('*')
            ->from('tax_information')
            ->where('tax_id', $tax_id)
            ->get()
            ->result();

        return $result;
    }

    //Product generator id check
    public function product_id_check($product_id)
    {
        $query = $this->db->select('*')
            ->from('product_information')
            ->where('product_id', $product_id)
            ->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //Count Product
    public function product_entry($data, $data2)
    {
        //        $this->db->insert('products', $data);
        $this->db->insert('product_information', $data2);

        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
        }
        $cache_file = './my-assets/js/admin_js/json/product.json';
        $productList = json_encode($json_product);
        file_put_contents($cache_file, $productList);
        return TRUE;
    }

    //Retrieve Product Edit Data
    public function retrieve_product_editdata($product_id)
    {
        $this->db->select('*');
        $this->db->from('product_information a');
        // $this->db->join('product_brand b', 'a.brand_id=b.id', 'left');
        // $this->db->join('cats c', 'a.category_id=c.id', 'left');
        $this->db->where('a.product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function ecom_product_edit_data($product_id)
    {
        $api_url = api_url();
        $url = $api_url . "products/show/" . $product_id;


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $product_details = json_decode(curl_exec($curl));
        curl_close($curl);

        //        echo '<pre>';print_r($product_details->product->name);exit();

        return $product_details;
    }

    // Supplier product information
    public function supplier_product_editdata($product_id)
    {
        $this->db->select('a.*,b.*');
        $this->db->from('supplier_product a');
        $this->db->join('supplier_information b', 'a.supplier_id=b.supplier_id');
        $this->db->where('a.product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //selected supplier product
    public function supplier_selected($product_id)
    {
        $this->db->select('*');
        $this->db->from('supplier_product');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
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

    //Update Categories
    public function update_product($data, $data2, $product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('product_information', $data);
        //        $this->db->where('barcode', $product_id);
        //        $this->db->update('products', $data2);
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_name_bn . ")", 'value' => $row->product_id);
        }
        $cache_file = './my-assets/js/admin_js/json/product.json';
        $productList = json_encode($json_product);
        file_put_contents($cache_file, $productList);
        return true;
    }


    public function check_calculaton($product_id)
    {
        $this->db->select('*');
        $this->db->from('product_purchase_details');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Delete Product Item
    public function delete_product($product_id)
    {


        $this->db->where('product_id', $product_id);
        $this->db->delete('product_information');
        $this->db->where('product_id', $product_id);
        $this->db->delete('supplier_product');
        return true;
    }

    //Product By Search
    public function product_search_item($product_id)
    {

        $query = $this->db->select('supplier_information.*,product_information.*,supplier_product.*')
            ->from('product_information')
            ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
            ->join('supplier_information', 'supplier_product.supplier_id = supplier_information.supplier_id', 'left')
            ->order_by('product_information.product_id', 'desc')
            ->where('product_information.product_id', $product_id)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Duplicate Entry Checking
    public function product_model_search($product_model)
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('product_model', $product_model);
        $query = $this->db->get();
        return $this->db->affected_rows();
    }

    //Product Details
    public function product_details_info($product_id)
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function product_details($product_id)
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function Product_Vat_Tax($product_id)
    {
         $this->db->select('*');
         $this->db->from('vat_tax_setting');
         $this->db->where('product_id', $product_id);
         $query = $this->db->get();
         if ($query->num_rows() > 0) {
             return $query->result();
         }
         return false;
    }

    // Product Purchase Report
    public function product_purchase_info($product_id)
    {
        $this->db->select('a.*,b.*,sum(b.quantity) as quantity,sum(b.total_amount) as total_amount,c.supplier_name');
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id = a.purchase_id');
        $this->db->join('supplier_information c', 'c.supplier_id = a.supplier_id');
        $this->db->where('b.product_id', $product_id);
        $this->db->order_by('a.purchase_id', 'asc');
        $this->db->group_by('a.purchase_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Invoice Data for specific data
    public function invoice_data($product_id)
    {
        $this->db->select('a.*,b.*,c.customer_name');
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->join('customer_information c', 'c.customer_id = a.customer_id');
        $this->db->where('b.product_id', $product_id);
        $this->db->order_by('a.invoice_id', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function previous_stock_data($product_id, $startdate)
    {

        $this->db->select('date,sum(quantity) as quantity');
        $this->db->from('product_report');
        $this->db->where('product_id', $product_id);
        $this->db->where('date <=', $startdate);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function all_product_code()
    {
        $this->db->select('product_code');
        $this->db->from('product_information');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_size_list()
    {
        $this->db->select('*');
        $this->db->from('attributes');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }
    public function get_atr_list()
    {
        $this->db->select('*');
        $this->db->from('attributes');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }

    public function attr_entry($data)
    {
        $this->db->select('*');
        $this->db->from('attributes');
        $this->db->where('name', $data['name']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('attributes', $data);
            return TRUE;
        }
    }

    public function retrieve_attr_editdata($size_id)
    {
        $this->db->select('*');
        $this->db->from('attributes');
        $this->db->where('id', $size_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function update_attr($data, $size_id)
    {
        $this->db->where('id', $size_id);
        $this->db->update('attributes', $data);
        return true;
    }


    public function get_color_list()
    {
        $this->db->select('*');
        $this->db->from('color_list');
        $query = $this->db->get();

        // echo '<pre>';

        // print_r($query->result_array());
        // exit();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }


        return false;
    }

    public function color_entry($data)
    {
        $this->db->select('*');
        $this->db->from('color_list');
        $this->db->where('status', 1);
        $this->db->where('color_name', $data['color_name']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('color_list', $data);
            return TRUE;
        }
    }

    public function retrieve_color_editdata($color_id)
    {
        $this->db->select('*');
        $this->db->from('color_list');
        $this->db->where('color_id', $color_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function update_color($data, $color_id)
    {
        $this->db->where('color_id', $color_id);
        $this->db->update('color_list', $data);
        return true;
    }

    public function product_real_price($product_id)
    {
        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')
            ->from('product_purchase_details')
            ->where_in('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->result();

        $pr_open_price = $this->db->select('supplier_price')
            ->from('supplier_product')
            ->where_in('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->result();

        $final_p = ($prinfo->product_rate) ? $prinfo->product_rate : $pr_open_price->supplier_price;
        return $final_p;
    }

    public function get_single_pr_details($product_id, $object = null) //object param used to whether returned data should be as an object or an array
    {
        $query = $this->db->select('*, product_information.finished_raw as pr_status')
            ->from('product_information')
            ->join('product_category', 'product_information.category_id=product_category.category_id', 'left')
            // ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
            //  ->join('product_purchase_details', 'product_information.product_id = product_purchase_details.product_id', 'left')
            ->where('product_information.product_id', $product_id)
            ->or_where('product_information.sku', $product_id)
            ->where('product_information.finished_raw', 1)
            ->order_by('product_information.product_name', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            if ($object) {
                return $query->result();
            }
            return $query->result_array();
        }
        return false;
    }


    public function allproduct($pr_status = 1)
    {
        $this->db->select('a.*,b.brand_name');
        $this->db->from('product_information a');
        $this->db->where('a.finished_raw', $pr_status);
        $this->db->join('product_brand b', 'a.brand_id=b.id', 'left');
        $this->db->order_by('a.product_name', 'asc');
        //        $this->db->limit(25);
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }

    public function allproduct_batch_wise($pr_status = 1)
    {

        $this->db->select('a.*,c.brand_name,b.purchase_id,b.expired_date');
        $this->db->from('product_purchase_details b');
        $this->db->join('product_information a', 'b.product_id = a.product_id', 'left');
        $this->db->join('product_brand c', 'c.brand_id=a.brand_id', 'left');
        $this->db->where('a.finished_raw', $pr_status);
        $this->db->group_by(array('b.product_id', 'b.purchase_id'));
        $this->db->order_by('a.product_name', 'asc');
        //        $this->db->limit(25);
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }

    //        $this->db->select('*');
    //        $this->db->from('product_purchase_details b');
    //        $this->db->join('product_information a', 'b.product_id = a.product_id', 'left');
    //        $this->db->where('a.finished_raw', $pr_status);
    //        $this->db->order_by('a.product_name', 'asc');
    //        $query = $this->db->get();
    //        $itemlist = $query->result();
    //        return $itemlist;
    //    }


    public function get_product_by_cat($pr_status = 1, $cat_id)
    {
        $this->db->select('a.*,b.brand_name');
        $this->db->from('product_information a');
        $this->db->where('a.finished_raw', $pr_status);
        $this->db->where('a.category_id', $cat_id);
        $this->db->join('product_brand b', 'a.brand_id=b.brand_id', 'left');

        $this->db->order_by('a.product_name', 'asc');
        //        $this->db->limit(25);
        $query = $this->db->get();
        $itemlist = $query->result();
        return $itemlist;
    }


    public function sku_list()
    {

        return $this->db->select('sku')->from('product_information')->limit(100)->get()->result();
    }
}

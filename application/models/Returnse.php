<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Returnse extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lcustomer');
        $this->load->library('session');
        $this->load->model('Customers');
        $this->auth->check_admin_auth();
    }

    //    public function return_invoice_entry()
    //    {
    //        // echo '<pre>';
    //        // print_r($_POST);
    //        // exit;
    //        $CI = &get_instance();
    //
    //        $CI->load->model('Invoices');
    //
    //        $invoice_id = $this->input->post('invoice_id', TRUE);
    //        $total          = $this->input->post('grand_total_price', TRUE);
    //        $add_cost = (!empty($this->input->post('total_tax', TRUE))) ? $this->input->post('total_tax', TRUE) : 0;
    //        $customer_id    = $this->input->post('customer_id', TRUE);
    //        $isrtn          = $this->input->post('rtn', TRUE);
    //        $cus_tot = (float)$total - (float)$add_cost;
    //
    //        $invoice_details =
    //
    //            $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
    //        $headn = $customer_id . '-' . $cusifo->customer_name;
    //        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
    //        $customer_headcode = $coainfo->HeadCode;
    //
    //        $base_total = $this->input->post('base_total', TRUE);
    //        $old_total = $this->input->post('total_amount', TRUE);
    //        $date      = date('Y-m-d');
    //        $createby  = $this->session->userdata('user_id');
    //        $createdate = date('Y-m-d H:i:s');
    //
    //        $ads      = $this->input->post('radio', TRUE);
    //        $quantity = $this->input->post('product_quantity', TRUE);
    //        $available_quantity = $this->input->post('available_quantity', TRUE);
    //
    //        $rate         = $this->input->post('product_rate', TRUE);
    //        $p_id         = $this->input->post('product_id', TRUE);
    //        $total_amount = $this->input->post('total_price', TRUE);
    //        $discount_rate = $this->input->post('discount', TRUE);
    //        $tax_amount   = $this->input->post('tax', TRUE);
    //        $soldqty      = $this->input->post('sold_qty', TRUE);
    //
    //        $is_cash_return = $this->input->post('cash_return', TRUE);
    //        $is_replace = $this->input->post('is_replace', TRUE);
    //
    //        $rep_quantity = $this->input->post('rep_qty', TRUE);
    //        $rep_rate         = $this->input->post('replace_rate', TRUE);
    //        $rep_p_id         = $this->input->post('rep_pr_id', TRUE);
    //        $rep_item_total = $this->input->post('rep_item_total', TRUE);
    //
    //        $rep_cus_return = $this->input->post('rep_grand', TRUE);
    //        $rep_cash_cost = '';
    //        $rep_add_cost = $this->input->post('rep_deduction', TRUE);
    //        $rep_grand_total = $this->input->post('rep_total_cost', TRUE);
    //        $rep_base_total = $this->input->post('rep_total', TRUE);
    //
    //
    //
    //        if ($is_replace == 1) {
    //
    //            if ($add_cost > 0) {
    //                $expense = array(
    //                    'VNo'            => $invoice_id,
    //                    'Vtype'          => 'Return',
    //                    'VDate'          => $date,
    //                    'COAID'          => 40405,
    //                    'Narration'      => 'Additional Cost Debit For Return',
    //                    'Debit'          => abs($add_cost),
    //                    'Credit'         => 0,
    //                    'IsPosted'       => 1,
    //                    'CreateBy'       => $createby,
    //                    'CreateDate'     => $createdate,
    //                    'IsAppove'       => 1
    //                );
    //                $this->db->insert('acc_transaction', $expense);
    //            }
    //
    //
    //
    //            $cash = array(
    //                'VNo'            => $invoice_id,
    //                'Vtype'          => 'Return',
    //                'VDate'          => $date,
    //                'COAID'          => 1020101,
    //                'Narration'      => 'Cash credit For Return',
    //                'Debit'          => 0,
    //                'Credit'         => $total,
    //                'IsPosted'       => 1,
    //                'CreateBy'       => $createby,
    //                'CreateDate'     => $createdate,
    //                'IsAppove'       => 1
    //            );
    //            $this->db->insert('acc_transaction', $cash);
    //
    //            $sale_income_dr = array(
    //                'VNo'            => $invoice_id,
    //                'Vtype'          => 'Return',
    //                'VDate'          => $date,
    //                'COAID'          => 303,
    //                'Narration'      => 'Sale Income Debit for return',
    //                'Debit'          => $base_total,
    //                'Credit'         => 0,
    //                'IsPosted'       => 1,
    //                'CreateBy'       => $createby,
    //                'CreateDate'     => $createdate,
    //                'IsAppove'       => 1
    //            );
    //            // $this->db->insert('acc_transaction', $cash);
    //            $this->db->insert('acc_transaction', $sale_income_dr);
    //
    //
    //            // if ($rep_grand_total > 0) {
    //            $cash = array(
    //                'VNo'            => $invoice_id,
    //                'Vtype'          => 'Return',
    //                'VDate'          => $date,
    //                'COAID'          => 1020101,
    //                'Narration'      => 'Cash Credit For Return',
    //                'Debit'          => abs($rep_base_total),
    //                'Credit'         => 0,
    //                'IsPosted'       => 1,
    //                'CreateBy'       => $createby,
    //                'CreateDate'     => $createdate,
    //                'IsAppove'       => 1
    //            );
    //            $sale_income = array(
    //                'VNo'            => $invoice_id,
    //                'Vtype'          => 'Return',
    //                'VDate'          => $date,
    //                'COAID'          => 303,
    //                'Narration'      => 'Sale Income Credit for return',
    //                'Debit'          => 0,
    //                'Credit'         => abs($rep_base_total),
    //                'IsPosted'       => 1,
    //                'CreateBy'       => $createby,
    //                'CreateDate'     => $createdate,
    //                'IsAppove'       => 1
    //            );
    //
    //
    //
    //
    //
    //            // if (is_array($p_id))
    //            for ($i = 0; $i < count($rep_p_id); $i++) {
    //
    //                $product_quantity = $rep_quantity[$i];
    //                $product_rate     = $rep_rate[$i];
    //                $product_id       = $rep_p_id[$i];
    //                $total_price      = $rep_item_total[$i];
    //                $supplier_rate    = $this->supplier_rate($product_id);
    //                // $discount         = $discount_rate[$i];
    //                // $tax              = -$tax_amount[$i];
    //
    //                $data1 = array(
    //                    'invoice_details_id' => $this->generator(15),
    //                    'invoice_id'        => $invoice_id,
    //                    'product_id'        => $product_id,
    //                    'quantity'          => $product_quantity,
    //                    'rate'              => $product_rate,
    //                    // 'discount'          => is_numeric($discount),
    //                    // 'tax'               => $tax,
    //                    'supplier_rate'     => $supplier_rate[0]['supplier_price'],
    //                    'paid_amount'       => $total_price,
    //                    'total_price'       => $total_price,
    //                    'status'            => 2
    //                );
    //
    //                $this->db->insert('invoice_details', $data1);
    //            }
    //
    //            $this->db->insert('acc_transaction', $cash);
    //            $this->db->insert('acc_transaction', $sale_income);
    //
    //            for ($i = 0; $i < count($p_id); $i++) {
    //
    //                $product_quantity = $quantity[$i];
    //                $product_rate     = $rate[$i];
    //                $product_id       = $p_id[$i];
    //                $sqty             = $soldqty[$i];
    //                $total_price      = $total_amount[$i];
    //                $supplier_rate    = $this->supplier_rate($product_id);
    //                $discount         = $discount_rate[$i];
    //                $tax              = -$tax_amount[$i];
    //
    //                $data1 = array(
    //                    'invoice_details_id' => $this->generator(15),
    //                    'invoice_id'        => $invoice_id,
    //                    'product_id'        => $product_id,
    //                    'quantity'          => -$product_quantity,
    //                    'rate'              => $product_rate,
    //                    'discount'          => -is_numeric($discount),
    //                    'tax'               => $tax,
    //                    'supplier_rate'     => $supplier_rate[0]['supplier_price'],
    //                    'paid_amount'       => -$total,
    //                    'total_price'       => -$total_price,
    //                    'status'            => 1
    //                );
    //
    //
    //                $returns = array(
    //                    'outlet_id'     => $this->input->post('outlet_id', TRUE),
    //                    'return_id'     => $this->generator(15),
    //                    'invoice_id'    => $invoice_id,
    //                    'product_id'    => $product_id,
    //                    'customer_id'   => $this->input->post('customer_id', TRUE),
    //                    'ret_qty'       => $product_quantity,
    //                    'byy_qty'       => $sqty,
    //                    'date_purchase' => $this->input->post('invoice_date', TRUE),
    //                    'date_return'   => $date,
    //                    'product_rate'  => $product_rate,
    //                    'deduction'     => $discount,
    //                    'total_deduct'  => $this->input->post('total_discount', TRUE),
    //                    'total_tax'     => $this->input->post('total_tax', TRUE),
    //                    'total_ret_amount' => $total_price,
    //                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
    //                    'reason'        => $this->input->post('details', TRUE),
    //                    'usablity'      => 2
    //                );
    //
    //                $this->db->insert('invoice_details', $data1);
    //
    //                $this->db->insert('product_return', $returns);
    //            }
    //
    //
    //            // $this->db->insert('acc_transaction', $cos);
    //        } else {
    //
    //            if ($is_cash_return  == 1) {
    //
    //                if ($add_cost > 0) {
    //                    $expense = array(
    //                        'VNo'            => $invoice_id,
    //                        'Vtype'          => 'Return',
    //                        'VDate'          => $date,
    //                        'COAID'          => 40405,
    //                        'Narration'      => 'Additional Cost Debit For Return',
    //                        'Debit'          => abs($add_cost),
    //                        'Credit'         => 0,
    //                        'IsPosted'       => 1,
    //                        'CreateBy'       => $createby,
    //                        'CreateDate'     => $createdate,
    //                        'IsAppove'       => 1
    //                    );
    //                    $this->db->insert('acc_transaction', $expense);
    //                }
    //
    //
    //                $cash = array(
    //                    'VNo'            => $invoice_id,
    //                    'Vtype'          => 'Return',
    //                    'VDate'          => $date,
    //                    'COAID'          => 1020101,
    //                    'Narration'      => 'Cash credit For Return',
    //                    'Debit'          => 0,
    //                    'Credit'         => $total,
    //                    'IsPosted'       => 1,
    //                    'CreateBy'       => $createby,
    //                    'CreateDate'     => $createdate,
    //                    'IsAppove'       => 1
    //                );
    //                $this->db->insert('acc_transaction', $cash);
    //
    //                $sale_income_dr = array(
    //                    'VNo'            => $invoice_id,
    //                    'Vtype'          => 'Return',
    //                    'VDate'          => $date,
    //                    'COAID'          => 303,
    //                    'Narration'      => 'Sale Income Debit for return',
    //                    'Debit'          => $base_total,
    //                    'Credit'         => 0,
    //                    'IsPosted'       => 1,
    //                    'CreateBy'       => $createby,
    //                    'CreateDate'     => $createdate,
    //                    'IsAppove'       => 1
    //                );
    //
    //                $this->db->insert('acc_transaction', $sale_income_dr);
    //
    //            } else {
    //                if ($add_cost > 0) {
    //                    $expense = array(
    //                        'VNo'            => $invoice_id,
    //                        'Vtype'          => 'Return',
    //                        'VDate'          => $date,
    //                        'COAID'          => 40405,
    //                        'Narration'      => 'Additional Cost Debit For Return',
    //                        'Debit'          => abs($add_cost),
    //                        'Credit'         => 0,
    //                        'IsPosted'       => 1,
    //                        'CreateBy'       => $createby,
    //                        'CreateDate'     => $createdate,
    //                        'IsAppove'       => 1
    //                    );
    //                    $this->db->insert('acc_transaction', $expense);
    //                }
    //
    //                $cash = array(
    //                    'VNo'            => $invoice_id,
    //                    'Vtype'          => 'Return',
    //                    'VDate'          => $date,
    //                    'COAID'          => 1020101,
    //                    'Narration'      => 'Cash credit For Return',
    //                    'Debit'          => 0,
    //                    'Credit'         => $total,
    //                    'IsPosted'       => 1,
    //                    'CreateBy'       => $createby,
    //                    'CreateDate'     => $createdate,
    //                    'IsAppove'       => 1
    //                );
    //
    //                $this->db->insert('acc_transaction', $cash);
    //
    //                $sale_income_dr = array(
    //                    'VNo'            => $invoice_id,
    //                    'Vtype'          => 'Return',
    //                    'VDate'          => $date,
    //                    'COAID'          => 303,
    //                    'Narration'      => 'Sale Income Debit for return',
    //                    'Debit'          => $base_total,
    //                    'Credit'         => 0,
    //                    'IsPosted'       => 1,
    //                    'CreateBy'       => $createby,
    //                    'CreateDate'     => $createdate,
    //                    'IsAppove'       => 1
    //                );
    //                $this->db->insert('acc_transaction', $sale_income_dr);
    //
    //            }
    //
    //
    //            // if (is_array($p_id))
    //            for ($i = 0; $i < count($p_id); $i++) {
    //
    //                $product_quantity = $quantity[$i];
    //                $product_rate     = $rate[$i];
    //                $product_id       = $p_id[$i];
    //                $sqty             = $soldqty[$i];
    //                $total_price      = $total_amount[$i];
    //                $supplier_rate    = $this->supplier_rate($product_id);
    //                $discount         = $discount_rate[$i];
    //                $tax              = -$tax_amount[$i];
    //
    //                $data1 = array(
    //                    'invoice_details_id' => $this->generator(15),
    //                    'invoice_id'        => $invoice_id,
    //                    'product_id'        => $product_id,
    //                    'quantity'          => -$product_quantity,
    //                    'rate'              => $product_rate,
    //                    'discount'          => -is_numeric($discount),
    //                    'tax'               => $tax,
    //                    'supplier_rate'     => $supplier_rate[0]['supplier_price'],
    //                    'paid_amount'       => -$total,
    //                    'total_price'       => -$total_price,
    //                    'status'            => 2
    //                );
    //
    //
    //                $returns = array(
    //                    'outlet_id'     => $this->input->post('outlet_id', TRUE),
    //                    'return_id'     => $this->generator(15),
    //                    'invoice_id'    => $invoice_id,
    //                    'product_id'    => $product_id,
    //                    'customer_id'   => $this->input->post('customer_id', TRUE),
    //                    'ret_qty'       => $product_quantity,
    //                    'byy_qty'       => $sqty,
    //                    'date_purchase' => $this->input->post('invoice_date', TRUE),
    //                    'date_return'   => $date,
    //                    'product_rate'  => $product_rate,
    //                    'deduction'     => $discount,
    //                    'total_deduct'  => $this->input->post('total_discount', TRUE),
    //                    'total_tax'     => $this->input->post('total_tax', TRUE),
    //                    'total_ret_amount' => $total_price,
    //                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
    //                    'reason'        => $this->input->post('details', TRUE),
    //                    'usablity'      => 1
    //                );
    //
    //
    //
    //                $this->db->insert('product_return', $returns);
    //
    //                $this->db->insert('invoice_details', $data1);
    //            }
    //        }
    //        return $invoice_id;
    //    }

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
    public function return_invoice_entry_new()
    {

        // echo "<pre>";
        // echo $this->input->post('dueAmount', TRUE);
        // echo "<br>";
        // echo $this->input->post('customer_ac', TRUE);
        // exit();

        // 'Credit' =>  $this->input->post('dueAmount', TRUE) + (abs($this->input->post('customer_ac', TRUE))),

        $CI = &get_instance();

        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Invoices');
        $CI->load->model('Settings');

        $invoice_id_new = $this->generator(10);
        $invoice_no_generated = $this->number_generator();

        $outlet_id = $this->input->post('outlet_id', TRUE);
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $delivery_type = $this->input->post('deliver_type', TRUE);
        $total = $this->input->post('grand_total_price', TRUE);
        $add_cost = (!empty($this->input->post('total_tax', TRUE))) ? $this->input->post('total_tax', TRUE) : 0;
        $customer_id = $this->input->post('customer_id', TRUE);
        $isrtn = $this->input->post('rtn', TRUE);
        $cus_tot = (float)$total - (float)$add_cost;

        $invoice_details = $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;

        $base_total = $this->input->post('base_total', TRUE);
        $old_total = $this->input->post('total_amount', TRUE);
        $old_paid = $this->input->post('old_paid', TRUE);
        $date = date('Y-m-d');
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');

        $ads = $this->input->post('radio', TRUE);
        $quantity = $this->input->post('product_quantity', TRUE);
        $available_quantity = $this->input->post('available_quantity', TRUE);


        $total_vat = $this->input->post('total_vat', TRUE);
        $re_total_vat = $this->input->post('re_total_vat', TRUE);
        $total_tax = $this->input->post('total_tax', TRUE);
        $re_total_tax = $this->input->post('re_total_tax', TRUE);

        $vat_per = $this->input->post('re_vat', TRUE);
        $tax_per = $this->input->post('re_tax', TRUE);
        $rate = $this->input->post('product_rate', TRUE);
        $p_id = $this->input->post('product_id', TRUE);
        $pur_id = $this->input->post('purchase_id', TRUE);
        $re_p_id = $this->input->post('re_product_id', TRUE);
        $total_amount = $this->input->post('total_price', TRUE);
        $net_pay = $this->input->post('net_pay', TRUE);
        $total = $this->input->post('total', TRUE);
        $discount_rate = $this->input->post('discount_per', TRUE);
        $vat_amount = $this->input->post('vat', TRUE);
        $tax_amount = $this->input->post('tax', TRUE);
        $soldqty = $this->input->post('sold_qty', TRUE);
        $courier_condtion = $this->input->post('courier_condtion', TRUE);
        $commission = $this->input->post('sku_cm', TRUE);

        $is_cash_return = $this->input->post('cash_return', TRUE);
        $is_customer_dc = $this->input->post('pay_person', TRUE);
        $pay_person = $this->input->post('pay_person', TRUE);
        $is_replace = $this->input->post('is_replace', TRUE);

        $rep_quantity = $this->input->post('re_product_quantity', TRUE);
        $re_warrenty_date = $this->input->post('re_warrenty_date', TRUE);
        $re_expiry_date = $this->input->post('re_expiry_date', TRUE);
        $re_discount = $this->input->post('re_discount', TRUE);
        $re_comm = $this->input->post('re_comm', TRUE);
        $re_perc_discount = $this->input->post('re_discount', TRUE);
        $re_total_price = $this->input->post('re_total_price', TRUE);
        $re_service_charge = $this->input->post('re_service_charge', TRUE);
        $re_total_price_wd = $this->input->post('re_total_price_wd', TRUE);
        $re_total_comm = $this->input->post('re_total_comm', TRUE);
        $rep_rate = $this->input->post('re_product_rate', TRUE);
        $rep_item_total = $this->input->post('rep_item_total', TRUE);
        $invoice = $this->input->post('invoice', TRUE);
        $total_refund = $this->input->post('total_refund', TRUE);
        $re_n_total = $this->input->post('re_n_total', TRUE);

        $courier_id = $this->input->post('courier_id', TRUE);
        $corifo = $this->db->select('*')->from('courier_name')->where('courier_id', $courier_id)->get()->row();
        $headn_cour = $corifo->id . '-' . $corifo->courier_name;
        $coainfo_cor = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn_cour)->get()->row();
        $courier_headcode = $coainfo_cor->HeadCode;
        $courier_name = $corifo->courier_name;

        $sales_return = array(
            'VNo' => $invoice_id_new,
            'Vtype' => 'Return',
            'VDate' => $date,
            'COAID' => 407,
            'Narration' => 'Sales Return For  Invoice ID - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
            'Debit' =>  $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
            'Credit' => 0,
            'IsPosted' => 1,
            'CreateBy' => $createby,
            'CreateDate' => $createdate,
            'IsAppove' => 1
        );
        $this->db->insert('acc_transaction', $sales_return);

        if ($this->input->post('sku_discount', TRUE) > 0) {
            $sales_discount = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 406,
                'Narration' => 'Return Discount For  Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Debit' =>  0,
                'Credit' => $this->input->post('sku_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $sales_discount);
        }

        if ($is_customer_dc == 1) {
            $dc = array(
                'VNo' => $invoice_id,
                'Vtype' => 'INV-CC',
                'VDate' => $date,
                'COAID' => 4040104,
                'Narration' => 'Delivery Charge For Invoice No -  ' . $invoice_no_generated . ' Courier  ' . $courier_name,
                'Debit' => (!empty($this->input->post('dc', TRUE)) ? $this->input->post('dc', TRUE) : 0),
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $dc);
        }

        if ($commission > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 410,
                'Narration' => 'Sales Commission For  Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Credit' => $commission,
                'Debit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }

        if ($is_cash_return == 1) {

            if ($total_vat > 0) {
                $vat_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 50203,
                    'Narration' => 'Total Vat Return for Invoice ID - ' . $invoice_id,
                    'Credit' => 0,
                    'Debit' => $total_vat,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $vat_transaction);
            }

            if ($total_tax > 0) {
                $tax_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $date,
                    'COAID' => 50204,
                    'Narration' => 'Total Tax Return for Invoice ID - ' . $invoice_id,
                    'Credit' => 0,
                    'Debit' => $total_tax,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $tax_transaction);
            }

            if ($total_refund < 0) {
                $cash = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 1020101,
                    'Narration' => 'Cash Refund credit For Return',
                    'Debit' => 0,
                    'Credit' =>  $this->input->post('cash_refund', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cash);

                $cus_ac = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Customer account cash refund Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => 0,
                    // 'Credit' =>  $this->input->post('dueAmount', TRUE) + (abs($this->input->post('customer_ac', TRUE))),
                    // 'Credit' => $this->input->post('sales_return', TRUE) - $this->input->post('sku_discount', TRUE),
                    'Credit' => $this->input->post('sales_return', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac);

                $cus_ac_dr = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Customer account Debit Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => $this->input->post('cash_refund', TRUE),
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac_dr);
            } else {
                $cus_ac_dr = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Customer account Debit Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => 0,
                    'Credit' => $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac_dr);

                $pay_type = $this->input->post('paytype', TRUE);

                $paid = $this->input->post('p_amount', TRUE);
                $bank_id = $this->input->post('bank_id_m', TRUE);

                $bkash_id = $this->input->post('bkash_id', TRUE);
                $bkashname = '';
                $card_id = $this->input->post('card_id', TRUE);
                $nagad_id = $this->input->post('nagad_id', TRUE);
                $rocket_id = $this->input->post('rocket_id', TRUE);
                if (count($paid) > 0) {
                    for ($i = 0; $i < count($pay_type); $i++) {

                        if ($paid[$i] > 0) {
                            if ($pay_type[$i] == 1) {

                                $cc = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  1020101,
                                    'Narration'      =>  'Cash in Hand in Return for Invoice ID - ' . $invoice_id . ' customer- ' .  $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'      =>  $createdate,
                                    'status'        =>  1,
                                    'account'       => '',
                                    'COAID'         => 1020101
                                );


                                $this->db->insert('acc_transaction', $cc);

                                //echo '<pre>';print_r($data);exit();
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'COAID'         => $bankcoaid,
                                    'pay_date'       =>  $createdate,
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bkashname,
                                    'pay_date'       =>  $createdate,
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'       =>  $createdate,
                                    'account'       => $nagadname,
                                    'COAID'         => $nagadcoaid,
                                    'status'        =>  1,
                                );



                                $this->db->insert('paid_amount', $data);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bankcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $carddebit = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  '40404',
                                    'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice NO- ' . $invoice_no_generated,
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
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $rocketcoaid,
                                    'Narration'      =>  'Cash in Rocket  paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
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
                                    'pay_date'       =>  $createdate,
                                    'account'       => $rocketname,
                                    'COAID'         => $rocketcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $this->db->insert('acc_transaction', $rocketc);
                            }
                        }
                    }
                }
            }



            $data_new_inv = array(
                'invoice_id' => $invoice_id_new,
                'customer_id' => $customer_id,
                'outlet_id' => $this->input->post('outlet_id', TRUE),
                'date' => date('Y-m-d'),
                'time'    => date("h:i A"),
                'agg_id' => (!empty($agg_id) ? $agg_id : NULL),
                'total_amount' => $this->input->post('total_refund', TRUE),
                'paid_amount' => $this->input->post('paidAmount', TRUE) > 0 ? $this->input->post('paidAmount', TRUE) : $this->input->post('paid_amount', TRUE),
                'due_amount' => $this->input->post('due_amount', TRUE),
                'invoice' => $invoice_no_generated,
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'sales_return' => $this->input->post('sales_return', TRUE),
                'total_discount' => $this->input->post('sku_discount', TRUE),
                'cash_refund' => $this->input->post('cash_refund', TRUE),
                'customer_ac' => $this->input->post('customer_ac', TRUE),
                'service_charge' => $this->input->post('service_charge', TRUE),
                'shipping_cost' => $this->input->post('shipping_cost', TRUE),
                'delivery_ac' => $this->input->post('delivery_ac', TRUE),
                'delivery_type' => $delivery_type,
                'courier_status' => 5,
                'previous_paid' => $this->input->post('paid_amount', TRUE),

                'sales_by' => $createby,
                'status' => 2,
                'payment_type' => 1,

            );
            //echo '<pre>'; print_r($data_new_inv); exit();
            $inv = $this->db->insert('invoice', $data_new_inv);

            for ($i = 0; $i < count($p_id); $i++) {

                $product_quantity = $quantity[$i];
                $product_rate = $rate[$i];
                $product_id = $p_id[$i];
                $purchase_id = $pur_id[$i];
                $sqty = $soldqty[$i];
                $total_price = $total_amount[$i];
                $supplier_rate = $this->supplier_rate($product_id);
                $discount = $discount_rate[$i];
                $tax = -$tax_amount[$i];
                $vat = -$vat_amount[$i];




                $data1 = array(
                    'invoice_details_id' => $this->generator(15),
                    'invoice_id' => $invoice_id_new,
                    'product_id' => $product_id,
                    'purchase_id' => $purchase_id,
                    'quantity' => -$product_quantity,
                    'rate' => $product_rate,
                    'discount' => -is_numeric($discount),
                    'tax' => $tax,
                    'vat' => $vat,
                    'description'        => 'Return',
                    'supplier_rate' => $supplier_rate[0]['supplier_price'],
                    'paid_amount' => -$total,
                    'total_price' => -$total_price,
                    'total_price_wd' => - ($product_quantity * $product_rate),
                    'status' => 2,
                    'is_return' => 1,
                );
                $this->db->insert('invoice_details', $data1);



                $usabilty = '';

                if ($is_cash_return == 1) {
                    $usabilty = 1;
                }

                if ($is_replace == 1) {
                    $usabilty = 2;
                }



                $returns = array(
                    'outlet_id' => $this->input->post('outlet_id', TRUE),
                    'return_id' => $this->generator(15),
                    'invoice_id' => $invoice_id,
                    'invoice_id_new' =>  $invoice_id_new,
                    'product_id' => $product_id,
                    'customer_id' => $this->input->post('customer_id', TRUE),
                    'ret_qty' => $product_quantity,
                    'byy_qty' => $sqty,
                    'date_purchase' => $this->input->post('invoice_date', TRUE),
                    'date_return' => $date,
                    'product_rate' => $product_rate,
                    'deduction' => $discount,
                    'vat' => $vat,
                    'total_tax' => $tax,
                    'total_deduct' => $this->input->post('total_discount', TRUE),
                    'delivery_charge' => $add_cost,
                    //                'total_tax'     => $this->input->post('total_tax', TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
                    'reason' => $this->input->post('details', TRUE),
                    'usablity' => $usabilty
                );

                $this->db->insert('product_return', $returns);
            }
            redirect(base_url('Cinvoice/invoice_inserted_data/' . $invoice_id_new));
        }

        if ($is_replace == 1) {

            $datainv = array(
                'invoice_id' => $invoice_id_new,
                'customer_id' => $customer_id,
                'date' => date('Y-m-d'),
                'time'    => date("h:i A"),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'agg_id' => (!empty($agg_id) ? $agg_id : NULL),
                'total_amount' => $this->input->post('re_grandTotal', TRUE),
                'invoice' => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('re_inva_details', TRUE)) ? $this->input->post('re_inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('re_invoice_discount', TRUE),
                'perc_discount' => $this->input->post('re_perc_discount', TRUE),
                'total_discount'  => $this->input->post('re_total_discount', TRUE),
                'total_commission'  => $this->input->post('re_total_commission', TRUE),
                'service_charge'  => $this->input->post('re_service_charge', TRUE),
                'comm_type'  => $this->input->post('commission_type', TRUE),
                'paid_amount' => $this->input->post('re_paid_amount', TRUE),
                'due_amount' => $this->input->post('re_due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'shipping_cost' => (!empty($add_cost) ? $add_cost : null),
                'condition_cost'   => $this->input->post('re_condition_cost', TRUE),
                'courier_condtion' => $this->input->post('re_courier_condtion', TRUE),
                'delivery_ac' => $this->input->post('re_delivery_ac', TRUE),
                'sales_by' => $createby,
                'status' => 2,
                'payment_type' => 1,
                'delivery_type' => $delivery_type,
                'courier_id' => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id' => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                'outlet_id' => $this->input->post('outlet_id', TRUE),
                'reciever_id' => $this->input->post('reciever_id', TRUE),
                'receiver_number' => $this->input->post('receiver_number', TRUE),
                'courier_status' => 6,
                'previous_paid' => $this->input->post('paid_amount', TRUE),

            );

            if ($this->input->post('re_n_total') < 0) {
                $datainv_new = array(
                    'sales_return' => abs($this->input->post('re_n_total', TRUE)),
                    'cash_refund' => $this->input->post('re_cash_refund', TRUE),
                    'customer_ac' => abs($this->input->post('re_customer_ac', TRUE)),

                );
            }

            $main_datainv = array_merge($datainv, $datainv_new);

            $inv = $this->db->insert('invoice', $main_datainv);

            for ($i = 0; $i < count($p_id); $i++) {

                $product_quantity = $quantity[$i];
                $product_rate = $rate[$i];
                $product_id = $p_id[$i];
                $purchase_id = $pur_id[$i];
                $sqty = $soldqty[$i];
                $total_price = $total_amount[$i];
                $supplier_rate = $this->supplier_price($product_id);
                $discount = $discount_rate[$i];
                $tax = -$tax_amount[$i];

                $data1 = array(
                    'invoice_details_id' => $this->generator(15),
                    'invoice_id' => $invoice_id_new,
                    'product_id' => $product_id,
                    'purchase_id' => $purchase_id,
                    'quantity' => -$product_quantity,
                    'rate' => $product_rate,
                    'description'        => 'Return',
                    'discount' => -is_numeric($discount),
                    'tax' => $tax,
                    'supplier_rate' => $supplier_rate[0]['supplier_price'],
                    'paid_amount' => -$total,
                    'total_price' => -$total_price,
                    'total_price_wd' => - ($product_quantity * $product_rate),
                    'status' => 2,
                    'is_return' => 1,
                );
                $this->db->insert('invoice_details', $data1);

                $usabilty = '';

                if ($is_cash_return == 1) {
                    $usabilty = 1;
                }

                if ($is_replace == 1) {
                    $usabilty = 2;
                }

                $returns = array(
                    'outlet_id' => $this->input->post('outlet_id', TRUE),
                    'return_id' => $this->generator(15),
                    'invoice_id' => $invoice_id,
                    'invoice_id_new' =>  $invoice_id_new,
                    'product_id' => $product_id,
                    'customer_id' => $this->input->post('customer_id', TRUE),
                    'ret_qty' => $product_quantity,
                    'byy_qty' => $sqty,
                    'date_purchase' => $this->input->post('invoice_date', TRUE),
                    'date_return' => $date,
                    'product_rate' => $product_rate,
                    'deduction' => $discount,
                    'total_deduct' => $this->input->post('total_discount', TRUE),
                    'delivery_charge' => $add_cost,
                    //                'total_tax'     => $this->input->post('total_tax', TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
                    'reason' => $this->input->post('details', TRUE),
                    'usablity' => $usabilty
                );

                $this->db->insert('product_return', $returns);
            }
            for ($i = 0; $i < count($re_p_id); $i++) {

                $product_rate = $rep_rate[$i];
                $product_id = $re_p_id[$i];
                $product_quantity = $rep_quantity[$i];
                $total_price = $re_total_price[$i];
                $total_price_wd = (!empty($re_total_price_wd[$i]) ? $re_total_price_wd[$i] : $total_price);
                $supplier_rate = $this->supplier_price($product_id);
                $disper = $re_discount[$i];
                $comm = $re_comm[$i];
                $vat = $vat_per[$i];
                $tax = $tax_per[$i];

                if ($outlet_id == 'HK7TGDT69VFMXB7') {
                    $stock_details = $CI->Reports->getExpiryCheckList($product_id, 1)['aaData'];
                } else {
                    $stock_details = $CI->Rqsn->expiry_outlet_stock($product_id, 1)['aaData'];
                }

                $stock = $stock_details[0]['stok_quantity'];
                $rest = $product_quantity - $stock;
                $array = array();

                $aprv_qty = $rest > 0 ? $stock : $product_quantity;

                $first_array = array(
                    'invoice_details_id' => $this->generator(15),
                    'invoice_id'         => $invoice_id_new,
                    'purchase_id'         => $stock_details[0]['purchase_id'],
                    'product_id'         => $product_id,
                    'quantity'           => $aprv_qty,
                    'rate'               => $product_rate,
                    'description'        => 'Replacement',
                    'discount_per'       => $disper,
                    'commission_per'       => $comm,
                    'supplier_rate'      => $supplier_rate,
                    'total_price'        => $total_price,
                    'total_price_wd'        => $total_price_wd,
                    'vat'        => $vat,
                    'tax'        => $tax,
                    'status'             => 2
                );

                array_push($array, $first_array);


                if ($rest > 0) {
                    foreach (array_slice($stock_details, 1) as $item) {

                        $stockQty = $item['stok_quantity'];

                        if ($stockQty >= $rest) {
                            $stockQty -= $rest;
                            $rest = 0;
                        } else {
                            $stockQty = 0;
                            $rest = $rest - $stockQty;
                        }



                        $array[] = array(
                            'invoice_details_id' => $this->generator(15),
                            'invoice_id'         => $invoice_id_new,
                            'product_id'         => $product_id,
                            'purchase_id'         => $item['purchase_id'],
                            'quantity'           => $item['stok_quantity'] - $stockQty,
                            'rate'               => '',
                            'description'        => 'Replacement',
                            'discount_per'       => '',
                            'commission_per'       => '',
                            'paid_amount'        => '',
                            'due_amount'         => '',
                            'supplier_rate'      => '',
                            'total_price'        => '',
                            'total_price_wd'        => '',
                            'vat'        => '',
                            'tax'        => '',
                            'status'             => 2
                        );
                    }
                }

                foreach ($array as $ar) {

                    if ($ar['quantity'] > 0) {
                        $result = $this->db->insert('invoice_details', $ar);
                    }
                }

                // $inv_dt=$this->db->insert('invoice_details', $data1);
            }

            $cus_ac_cr = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => $customer_headcode,
                'Narration' => 'Customer Credit For  Invoice ID - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Debit' =>  0,
                'Credit' => $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $cus_ac_cr);

            if ($this->input->post('re_total_discount', TRUE) > 0) {
                $re_sales_discount = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Replacement',
                    'VDate' => $date,
                    'COAID' => 406,
                    'Narration' => 'Sales Discount  Replacement For  Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => $this->input->post('re_total_discount', TRUE),
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $re_sales_discount);
            }
            $sale_income_cr = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 303,
                'Narration' => 'Sales Credit for  Replacement  Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Debit' => 0,
                'Credit' => $this->input->post('grandTotal', TRUE) + $this->input->post('re_total_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $sale_income_cr);

            $cus_ac_db_repl = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => $customer_headcode,
                'Narration' => 'Customer Account Debit for Replacement Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Debit' => $this->input->post('grandTotal', TRUE) + $this->input->post('re_total_discount', TRUE),
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $cus_ac_db_repl);

            if ($re_total_vat > 0) {
                $vat_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 50203,
                    'Narration' => 'Total Vat  for Invoice ID - ' . $invoice_id,
                    'Credit' => $re_total_vat,
                    'Debit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $vat_transaction);
            }

            if ($re_total_tax > 0) {
                $tax_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $date,
                    'COAID' => 50204,
                    'Narration' => 'Total Tax  for Invoice ID - ' . $invoice_id,
                    'Credit' => $re_total_tax,
                    'Debit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $tax_transaction);
            }

            // echo $re_n_total;exit();
            if ($re_n_total < 0) {
                if ($this->input->post('re_cash_refund', TRUE) > 0) {
                    $cash = array(
                        'VNo' => $invoice_id_new,
                        'Vtype' => 'Replacement',
                        'VDate' => $date,
                        'COAID' => 1020101,
                        'Narration' => 'Cash Refund credit For Replacement For  Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                        'Debit' => 0,
                        'Credit' =>  $this->input->post('re_cash_refund', TRUE),
                        'IsPosted' => 1,
                        'CreateBy' => $createby,
                        'CreateDate' => $createdate,
                        'IsAppove' => 1
                    );
                    $this->db->insert('acc_transaction', $cash);

                    $cash_db_repl = array(
                        'VNo' => $invoice_id_new,
                        'Vtype' => 'Replacement',
                        'VDate' => $date,
                        'COAID' => $customer_headcode,
                        'Narration' => 'Cash Refund Debit For Replacement For Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                        'Debit' => $this->input->post('re_cash_refund', TRUE),
                        'Credit' =>  0,
                        'IsPosted' => 1,
                        'CreateBy' => $createby,
                        'CreateDate' => $createdate,
                        'IsAppove' => 1
                    );
                    $this->db->insert('acc_transaction', $cash_db_repl);

                    $re_customer_ac = abs($this->input->post('re_customer_ac', TRUE));
                    if ($re_customer_ac > 0) {
                        $cus_ac = array(
                            'VNo' => $invoice_id_new,
                            'Vtype' => 'Replacement',
                            'VDate' => $date,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Customer account cash refund Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                            'Debit' => 0,
                            'Credit' => abs($this->input->post('re_customer_ac', TRUE)),
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $cus_ac);
                    }
                }
            } else {
                //echo $re_n_total;exit();

                if ($this->input->post('re_due_amount', TRUE) > 0) {
                    $re_sales_discount = array(
                        'VNo' => $invoice_id_new,
                        'Vtype' => 'Replacement',
                        'VDate' => $date,
                        'COAID' => $customer_headcode,
                        'Narration' => 'Customer account Debit Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                        'Debit' => $this->input->post('re_due_amount', TRUE),
                        'Credit' => 0,
                        'IsPosted' => 1,
                        'CreateBy' => $createby,
                        'CreateDate' => $createdate,
                        'IsAppove' => 1
                    );
                    $this->db->insert('acc_transaction', $re_sales_discount);
                }
                $pay_type = $this->input->post('paytype', TRUE);
                $paid = $this->input->post('p_amount', TRUE);
                $bank_id = $this->input->post('bank_id_m', TRUE);

                $bkash_id = $this->input->post('bkash_id', TRUE);
                $bkashname = '';
                $card_id = $this->input->post('card_id', TRUE);
                $nagad_id = $this->input->post('nagad_id', TRUE);
                $total_paid_repl = 0;
                if (count($paid) > 0) {
                    //   echo count($paid);exit();
                    for ($i = 0; $i < count($pay_type); $i++) {

                        if ($paid[$i] > 0) {
                            $total_paid_repl += $paid[$i];
                            if ($pay_type[$i] == 1) {

                                $cc = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  1020101,
                                    'Narration'      =>  'Cash in Hand in Replacement for Invoice ID - ' . $invoice_id_new . ' customer- ' .  $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );


                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'      =>  $createdate,
                                    'status'        =>  1,
                                    'account'       => '',
                                    'COAID'         => 1020101
                                );

                                $this->db->insert('acc_transaction', $cc);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bankcoaid,
                                    'Narration'      =>  'Paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'COAID'         => $bankcoaid,
                                    'pay_date'       =>  $createdate,
                                    'status'        =>  1
                                );



                                $this->db->insert('paid_amount', $data);


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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bkashcoaid,
                                    'Narration'      =>  'Cash in Bkash paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bkashname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bkashcoaid,
                                    'status'        =>  1,
                                );


                                $this->db->insert('paid_amount', $data);
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $nagadcoaid,
                                    'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'       =>  $createdate,
                                    'account'       => $nagadname,
                                    'COAID'         => $nagadcoaid,
                                    'status'        =>  1,
                                );



                                $this->db->insert('paid_amount', $data);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bankcoaid,
                                    'Narration'      =>  'Paid amount for customer in card - ' . $card_info[0]['card_no'] . '  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bankcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $carddebit = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  '40404',
                                    'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice ID- ' . $invoice_id_new,
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

                    $cus_ac_cr_repl = array(
                        'VNo'            =>  $invoice_id_new,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $createdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Cash Payment Credit in Replacement for Invoice ID - ' . $invoice_id_new . ' customer- ' .  $cusifo->customer_name,
                        'Debit'          =>  0,
                        'Credit'         =>  $total_paid_repl,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $this->db->insert('acc_transaction', $cus_ac_cr_repl);
                }
            }

            redirect('Cinvoice/invoice_inserted_data/' . $invoice_id_new);
        }

        return $invoice_id;
    }

    public function return_invoice_entry_new_2()
    {

        // echo "<pre>";
        // echo $this->input->post('dueAmount', TRUE);
        // echo "<br>";
        // echo $this->input->post('customer_ac', TRUE);
        // exit();

        // 'Credit' =>  $this->input->post('dueAmount', TRUE) + (abs($this->input->post('customer_ac', TRUE))),

        $CI = &get_instance();

        $CI->load->model('Reports');
        $CI->load->model('Rqsn');
        $CI->load->model('Invoices');
        $CI->load->model('Settings');

        $invoice_id_new = $this->generator(10);
        $invoice_no_generated = $this->number_generator();

        $outlet_id = $this->input->post('outlet_id', TRUE);
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $old_invoice_id = $this->input->post('invoice_id', TRUE);
        $delivery_type = $this->input->post('deliver_type', TRUE);
        $total = $this->input->post('grand_total_price', TRUE);
        $add_cost = (!empty($this->input->post('total_tax', TRUE))) ? $this->input->post('total_tax', TRUE) : 0;
        $customer_id = $this->input->post('customer_id', TRUE);
        $isrtn = $this->input->post('rtn', TRUE);
        $cus_tot = (float)$total - (float)$add_cost;

        $invoice_details = $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;

        $base_total = $this->input->post('base_total', TRUE);
        $old_total = $this->input->post('total_amount', TRUE);
        $old_paid = $this->input->post('old_paid', TRUE);
        $date = date('Y-m-d');
        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');

        $ads = $this->input->post('radio', TRUE);
        $quantity = $this->input->post('product_quantity', TRUE);
        $total_return_qnt = $this->input->post('total_return_qntt', TRUE);
        $available_quantity = $this->input->post('available_quantity', TRUE);


        $total_vat = $this->input->post('total_vat', TRUE);
        $re_total_vat = $this->input->post('re_total_vat', TRUE);
        $total_tax = $this->input->post('total_tax', TRUE);
        $re_total_tax = $this->input->post('re_total_tax', TRUE);

        $vat_per = $this->input->post('re_vat', TRUE);
        $tax_per = $this->input->post('re_tax', TRUE);
        $rate = $this->input->post('product_rate', TRUE);
        $p_id = $this->input->post('product_id', TRUE);

        // echo "<pre>";
        // print_r($p_id);
        // exit();


        $pur_id = $this->input->post('purchase_id', TRUE);
        $re_p_id = $this->input->post('re_product_id', TRUE);
        $total_amount = $this->input->post('total_price', TRUE);
        $net_pay = $this->input->post('net_pay', TRUE);
        $total = $this->input->post('total', TRUE);
        $total_rate_return = $this->input->post('total_rate_return', TRUE);
        $item_total_discounted_price_wd_return = $this->input->post('item_total_discounted_price_wd_return', TRUE);
        $total_price_return = $this->input->post('total_price_return', TRUE);
        $discount_rate = $this->input->post('discount_per', TRUE);
        $discount_per_return = $this->input->post('discount_per_return', TRUE);
        $distributed_discount_return = $this->input->post('distributed_discount_return', TRUE);
        $item_total_discount_return = $this->input->post('item_total_discount_return', TRUE);
        $vat_amount = $this->input->post('vat', TRUE);
        $vat_amount_return = $this->input->post('vat_return', TRUE);
        $tax_amount = $this->input->post('tax', TRUE);
        $tax_amount_return = $this->input->post('tax_return', TRUE);
        $soldqty = $this->input->post('sold_qty', TRUE);
        $courier_condtion = $this->input->post('courier_condtion', TRUE);
        $commission = $this->input->post('sku_cm', TRUE);

        $is_cash_return = $this->input->post('cash_return', TRUE);
        $is_customer_dc = $this->input->post('pay_person', TRUE);
        $pay_person = $this->input->post('pay_person', TRUE);
        $is_replace = $this->input->post('is_replace', TRUE);

        $rep_quantity = $this->input->post('re_product_quantity', TRUE);
        $re_warrenty_date = $this->input->post('re_warrenty_date', TRUE);
        $re_expiry_date = $this->input->post('re_expiry_date', TRUE);
        $re_discount = $this->input->post('re_discount', TRUE);
        $re_comm = $this->input->post('re_comm', TRUE);
        $re_perc_discount = $this->input->post('re_discount', TRUE);
        $re_total_price = $this->input->post('re_total_price', TRUE);
        $re_service_charge = $this->input->post('re_service_charge', TRUE);
        $re_total_price_wd = $this->input->post('re_total_price_wd', TRUE);


        $re_total_discounted_price_wd = $this->input->post('re_total_discounted_price_wd', TRUE);
        $re_distributed_discount = $this->input->post('re_distributed_discount', TRUE);
        $re_item_total_discount = $this->input->post('re_item_total_discount', TRUE);
        // $re_total_price_wd = $this->input->post('re_total_price_wd', TRUE);
        // $re_total_price_wd = $this->input->post('re_total_price_wd', TRUE);
        // $re_total_price_wd = $this->input->post('re_total_price_wd', TRUE);





        $re_total_comm = $this->input->post('re_total_comm', TRUE);
        $rep_rate = $this->input->post('re_product_rate', TRUE);
        $rep_item_total = $this->input->post('rep_item_total', TRUE);
        $invoice = $this->input->post('invoice', TRUE);
        $total_refund = $this->input->post('total_refund', TRUE);
        $re_n_total = $this->input->post('re_n_total', TRUE);

        $courier_id = $this->input->post('courier_id', TRUE);
        $corifo = $this->db->select('*')->from('courier_name')->where('courier_id', $courier_id)->get()->row();
        $headn_cour = $corifo->id . '-' . $corifo->courier_name;
        $coainfo_cor = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn_cour)->get()->row();
        $courier_headcode = $coainfo_cor->HeadCode;
        $courier_name = $corifo->courier_name;

        // $this->db->where('invoice_id', $invoice_id);
        // $this->db->set(array(
        //     'paid_amount' => $old_total,
        //     'due_amount' => 0,
        //     'is_return_replace' => 1,
        //     'return_replace_invoice_id' => $invoice_id_new,
        // ));
        // $this->db->update('invoice');

        $sales_return = array(
            'VNo' => $invoice_id_new,
            'Vtype' => 'Return',
            'VDate' => $date,
            'COAID' => 407,
            'Narration' => 'Sales Return - for '.$invoice_id_new,
            'Debit' =>  $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
            'Credit' => 0,
            'IsPosted' => 1,
            'CreateBy' => $createby,
            'CreateDate' => $createdate,
            'IsAppove' => 1
        );
        $this->db->insert('acc_transaction', $sales_return);

        if ($this->input->post('sku_discount', TRUE) > 0) {
            $sales_discount = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 406,
                'Narration' => 'Return Discount for Sales Return - for' .$invoice_id_new,
                'Debit' =>  0,
                'Credit' => $this->input->post('sku_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $sales_discount);
        }

        if ($is_customer_dc == 1) {
            $dc = array(
                'VNo' => $invoice_id,
                'Vtype' => 'INV-CC',
                'VDate' => $date,
                'COAID' => 4040104,
                'Narration' => 'Delivery Charge for Sales Return - for' .$invoice_id_new,
                'Debit' => (!empty($this->input->post('dc', TRUE)) ? $this->input->post('dc', TRUE) : 0),
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $dc);
        }

        if ($commission > 0) {
            $com_transaction = array(

                'VNo' => $invoice_id,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 410,
                'Narration' => 'Sales Commission for Sales Return- for' .$invoice_id_new,
                'Credit' => $commission,
                'Debit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );

            $this->db->insert('acc_transaction', $com_transaction);
        }

        if ($is_cash_return == 1) {

            if ($total_vat > 0) {
                $vat_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 50203,
                    'Narration' => 'Vat Return for Sales return- for' .$invoice_id_new,
                    'Credit' => 0,
                    'Debit' => $total_vat,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $vat_transaction);
            }

            if ($total_tax > 0) {
                $tax_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $date,
                    'COAID' => 50204,
                    'Narration' => 'Tax Return for Sales Return- for' .$invoice_id_new,
                    'Credit' => 0,
                    'Debit' => $total_tax,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $tax_transaction);
            }

            if ($total_refund < 0) {
                $cash = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 1020101,
                    'Narration' => 'Cash Refund for Sales Return of' .$invoice_id_new ,
                    'Debit' => 0,
                    'Credit' =>  $this->input->post('cash_refund', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cash);
                $cus_ac_dr = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Sales Return',
                    'Debit' => $this->input->post('cash_refund', TRUE),
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac_dr);
                $cus_ac = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Cash refund For Sales Return',
                    'Debit' => 0,
                    // 'Credit' =>  $this->input->post('dueAmount', TRUE) + (abs($this->input->post('customer_ac', TRUE))),
                    // 'Credit' => $this->input->post('sales_return', TRUE) - $this->input->post('sku_discount', TRUE),
                    'Credit' => $this->input->post('sales_return', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac);    
            } else {
                $cus_ac_dr = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => $customer_headcode,
                    'Narration' => 'Sales Return',
                    'Debit' => 0,
                    'Credit' => $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cus_ac_dr);

                $pay_type = $this->input->post('paytype', TRUE);

                $paid = $this->input->post('p_amount', TRUE);
                $bank_id = $this->input->post('bank_id_m', TRUE);

                $bkash_id = $this->input->post('bkash_id', TRUE);
                $bkashname = '';
                $card_id = $this->input->post('card_id', TRUE);
                $nagad_id = $this->input->post('nagad_id', TRUE);
                $rocket_id = $this->input->post('rocket_id', TRUE);
                if (count($paid) > 0) {
                    for ($i = 0; $i < count($pay_type); $i++) {

                        if ($paid[$i] > 0) {
                            if ($pay_type[$i] == 1) {

                                $cc = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  1020101,
                                    'Narration'      =>  'Cash in Hand in Return',
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'      =>  $createdate,
                                    'status'        =>  1,
                                    'account'       => '',
                                    'COAID'         => 1020101
                                );


                                $this->db->insert('acc_transaction', $cc);
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bankcoaid,
                                    'Narration'      =>  'Bank Payment for Return',
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'COAID'         => $bankcoaid,
                                    'pay_date'       =>  $createdate,
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bkashname,
                                    'pay_date'       =>  $createdate,
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'       =>  $createdate,
                                    'account'       => $nagadname,
                                    'COAID'         => $nagadcoaid,
                                    'status'        =>  1,
                                );



                                $this->db->insert('paid_amount', $data);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
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
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bankcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $carddebit = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  '40404',
                                    'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice NO- ' . $invoice_no_generated,
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
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $rocketcoaid,
                                    'Narration'      =>  'Cash in Rocket  paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
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
                                    'pay_date'       =>  $createdate,
                                    'account'       => $rocketname,
                                    'COAID'         => $rocketcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $this->db->insert('acc_transaction', $rocketc);
                            }
                        }
                    }
                }
            }





            $data_new_inv = array(
                'invoice_id' => $invoice_id_new,
                'customer_id' => $customer_id,
                'outlet_id' => $this->input->post('outlet_id', TRUE),
                'date' => date('Y-m-d'),
                'time'    => date("h:i A"),
                'agg_id' => (!empty($agg_id) ? $agg_id : NULL),
                'total_amount' => $this->input->post('total_refund', TRUE),
                'rounding' => $this->input->post('rounding', TRUE),
                'net_total' => $this->input->post('total_refund', TRUE),
                'paid_amount' => $this->input->post('paidAmount', TRUE) - ($this->input->post('cash_refund', TRUE) > 0 ? $this->input->post('cash_refund', TRUE) : 0), //> 0 ? $this->input->post('paidAmount', TRUE) : $this->input->post('paid_amount', TRUE),
                'due_amount' => $this->input->post('due_amount', TRUE),
                'total_vat' => -$this->input->post('total_vat', TRUE),
                'total_tax' => -$this->input->post('total_tax', TRUE),
                'invoice' => $invoice_no_generated,
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'sales_return' => $this->input->post('sales_return', TRUE),
                'total_discount' => -$this->input->post('sku_discount', TRUE),
                'cash_refund' => $this->input->post('cash_refund', TRUE),
                'customer_ac' => $this->input->post('customer_ac', TRUE),
                'service_charge' => $this->input->post('service_charge', TRUE),
                'shipping_cost' => $this->input->post('shipping_cost', TRUE),
                'delivery_ac' => $this->input->post('delivery_ac', TRUE),
                'delivery_type' => $delivery_type,
                'courier_status' => 5,
                'previous_paid' => $this->input->post('paid_amount', TRUE),

                'sales_by' => $createby,
                'status' => 2,
                'payment_type' => 1,

            );

            $inv = $this->db->insert('invoice', $data_new_inv);

            for ($i = 0; $i < count($p_id); $i++) {

                $product_quantity = $quantity[$i];
                $return_qnt = $total_return_qnt[$i];
                $product_rate = $rate[$i];
                $product_id = $p_id[$i];
                $purchase_id = $pur_id[$i];
                $sqty = $soldqty[$i];
                $total_price = $total_rate_return[$i];
                $item_total_discounted_price_wd = $item_total_discounted_price_wd_return[$i];
                $total_price_wd = $total_price_return[$i];
                // $total_price = $total_amount[$i];

                $previous_sold = $this->previous_sold($old_invoice_id, $product_id);
                $supplier_rate = $this->supplier_rate($product_id);
                $discount = $discount_rate[$i];
                $discount_per = $discount_per_return[$i];
                $distributed_discount = $distributed_discount_return[$i];
                $item_total_discount = $item_total_discount_return[$i];
                $tax = -$tax_amount_return[$i];
                $vat = -$vat_amount_return[$i];

                $inv_details_arr = array();

                $return_qnt_new = $return_qnt;

                foreach ($previous_sold as $sold) {
                    if ($return_qnt_new < 1 || $sold['quantity'] < 1) {
                        break;
                    } else {

                        $rest = ($return_qnt_new >= $sold['quantity']) ? $sold['quantity'] :  $return_qnt_new;

                        $data1 = array(
                            'invoice_details_id' => $this->generator(15),
                            'invoice_id' => $invoice_id_new,
                            'product_id' => $product_id,
                            'purchase_id' => $sold['purchase_id'],
                            'quantity' => -$rest,
                            'rate' => $product_rate,
                            'total_price' => - ($total_price / $return_qnt) * $rest,
                            'discount_per' => - ($discount_per / $return_qnt) * $rest,
                            'total_price_wd' => - ($total_price_wd / $return_qnt) * $rest,
                            'distributed_discount' => - ($distributed_discount / $return_qnt) * $rest,
                            'item_total_discount' => - ($item_total_discount / $return_qnt) * $rest,
                            'item_total_discounted_price_wd' => - ($item_total_discounted_price_wd / $return_qnt) * $rest,
                            'tax' => ($tax / $return_qnt) * $rest,
                            'vat' => ($vat / $return_qnt) * $rest,
                            'description'        => 'Return',
                            'supplier_rate' => $sold['supplier_rate'],
                            'paid_amount' => -$total,
                            'status' => 2,
                            'is_return' => 1,
                        );

                        $this->db->insert('invoice_details', $data1);

                        $return_qnt_new = $return_qnt_new - $rest;
                    }
                }

                // echo '<pre>';
                // print_r($inv_details_arr);
                // exit();

                $usabilty = '';

                if ($is_cash_return == 1) {
                    $usabilty = 1;
                }

                if ($is_replace == 1) {
                    $usabilty = 2;
                }



                $returns = array(
                    'outlet_id' => $this->input->post('outlet_id', TRUE),
                    'return_id' => $this->generator(15),
                    'invoice_id' => $invoice_id,
                    'invoice_id_new' =>  $invoice_id_new,
                    'product_id' => $product_id,
                    'customer_id' => $this->input->post('customer_id', TRUE),
                    'ret_qty' => $return_qnt,
                    'byy_qty' => $sqty,
                    'date_purchase' => $this->input->post('invoice_date', TRUE),
                    'date_return' => $date,
                    'product_rate' => $product_rate,
                    'deduction' => $discount,
                    'vat' => $vat,
                    'total_tax' => $tax,
                    'total_deduct' => $this->input->post('total_discount', TRUE),
                    'delivery_charge' => $add_cost,
                    //                'total_tax'     => $this->input->post('total_tax', TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
                    'reason' => $this->input->post('details', TRUE),
                    'usablity' => $usabilty
                );

                $this->db->insert('product_return', $returns);
            }
            redirect(base_url('Cinvoice/invoice_inserted_data/' . $invoice_id_new));
        }

        if ($is_replace == 1) {


            $datainv = array(
                'invoice_id' => $invoice_id_new,
                'customer_id' => $customer_id,
                'date' => date('Y-m-d'),
                'time'    => date("h:i A"),
                'sale_type'   => $this->input->post('sel_type', TRUE),
                'agg_id' => (!empty($agg_id) ? $agg_id : NULL),
                'total_amount' => $this->input->post('re_grandTotal', TRUE),
                'rounding' => $this->input->post('re_rounding', TRUE),
                'invoice' => $invoice_no_generated,
                'invoice_details' => (!empty($this->input->post('re_inva_details', TRUE)) ? $this->input->post('re_inva_details', TRUE) : ''),
                'invoice_discount' => $this->input->post('re_invoice_discount', TRUE),
                'perc_discount' => $this->input->post('re_perc_discount', TRUE),
                'total_discount'  => $this->input->post('re_total_discount', TRUE),
                'total_commission'  => $this->input->post('re_total_commission', TRUE),
                'total_vat'  => $this->input->post('re_total_vat', TRUE),
                'total_tax'  => $this->input->post('re_total_tax', TRUE),
                'service_charge'  => $this->input->post('re_service_charge', TRUE),
                'comm_type'  => $this->input->post('commission_type', TRUE),
                'paid_amount' => $this->input->post('re_paid_amount', TRUE),
                'due_amount' => $this->input->post('re_due_amount', TRUE),
                'prevous_due'     => $this->input->post('previous', TRUE),
                'commission'   => $this->input->post('commission', TRUE),
                'shipping_cost' => (!empty($add_cost) ? $add_cost : null),
                'condition_cost'   => $this->input->post('re_condition_cost', TRUE),
                'courier_condtion' => $this->input->post('re_courier_condtion', TRUE),
                'delivery_ac' => $this->input->post('re_delivery_ac', TRUE),
                'sales_by' => $createby,
                'status' => 2,
                'payment_type' => 1,
                'delivery_type' => $delivery_type,
                'courier_id' => (!empty($this->input->post('courier_id', TRUE)) ? $this->input->post('courier_id', TRUE) : null),
                'branch_id' => (!empty($this->input->post('branch_id', TRUE)) ? $this->input->post('branch_id', TRUE) : null),
                'outlet_id' => $this->input->post('outlet_id', TRUE),
                'reciever_id' => $this->input->post('reciever_id', TRUE),
                'receiver_number' => $this->input->post('receiver_number', TRUE),
                'courier_status' => 6,
                'previous_paid' => $this->input->post('paid_amount', TRUE),

            );



            $datainv_new = [];
            if ($this->input->post('re_n_total') < 0) {
                $datainv_new = array(
                    'sales_return' => abs($this->input->post('re_n_total', TRUE)),
                    'cash_refund' => $this->input->post('re_cash_refund', TRUE),
                    'customer_ac' => abs($this->input->post('re_customer_ac', TRUE)),
                );
            } else {
                $datainv_new = array(
                    'net_total' => ($this->input->post('re_n_total', TRUE))
                );
            }
            $datainv = array_merge($datainv, $datainv_new);

            // echo "<pre>";
            // print_r($datainv);
            // exit();



            $inv = $this->db->insert('invoice', $datainv);


            for ($i = 0; $i < count($p_id); $i++) {

                $product_quantity = $quantity[$i];
                $return_qnt = $total_return_qnt[$i];
                $product_rate = $rate[$i];
                $product_id = $p_id[$i];
                $purchase_id = $pur_id[$i];
                $sqty = $soldqty[$i];
                $total_price = $total_rate_return[$i];
                $item_total_discounted_price_wd = $item_total_discounted_price_wd_return[$i];
                $total_price_wd = $total_price_return[$i];
                // $total_price = $total_amount[$i];

                $previous_sold = $this->previous_sold($old_invoice_id, $product_id);
                $supplier_rate = $this->supplier_rate($product_id);
                $discount = $discount_rate[$i];
                $discount_per = $discount_per_return[$i];
                $distributed_discount = $distributed_discount_return[$i];
                $item_total_discount = $item_total_discount_return[$i];
                $tax = -$tax_amount_return[$i];
                $vat = -$vat_amount_return[$i];

                $inv_details_arr = array();

                $return_qnt_new = $return_qnt;

                foreach ($previous_sold as $sold) {
                    if ($return_qnt_new < 1 || $sold['quantity'] < 1) {
                        break;
                    } else {

                        $rest = ($return_qnt_new >= $sold['quantity']) ? $sold['quantity'] :  $return_qnt_new;

                        $data1 = array(
                            'invoice_details_id' => $this->generator(15),
                            'invoice_id' => $invoice_id_new,
                            'product_id' => $product_id,
                            'purchase_id' => $sold['purchase_id'],
                            'quantity' => -$rest,
                            'rate' => $product_rate,
                            'total_price' => - ($total_price / $return_qnt) * $rest,
                            'discount_per' => - ($discount_per / $return_qnt) * $rest,
                            'total_price_wd' => - ($total_price_wd / $return_qnt) * $rest,
                            'distributed_discount' => - ($distributed_discount / $return_qnt) * $rest,
                            'item_total_discount' => - ($item_total_discount / $return_qnt) * $rest,
                            'item_total_discounted_price_wd' => - ($item_total_discounted_price_wd / $return_qnt) * $rest,
                            'tax' => ($tax / $return_qnt) * $rest,
                            'vat' => ($vat / $return_qnt) * $rest,
                            'description'        => 'Return',
                            'supplier_rate' => $sold['supplier_rate'],
                            'paid_amount' => -$total,
                            'status' => 2,
                            'is_return' => 1,
                        );

                        // array_push($inv_details_arr, $data1);

                        $this->db->insert('invoice_details', $data1);

                        $return_qnt_new = $return_qnt_new - $rest;
                    }
                }

                // echo '<pre>';
                // print_r($inv_details_arr);
                // exit();


                $usabilty = '';

                if ($is_cash_return == 1) {
                    $usabilty = 1;
                }

                if ($is_replace == 1) {
                    $usabilty = 2;
                }

                $returns = array(
                    'outlet_id' => $this->input->post('outlet_id', TRUE),
                    'return_id' => $this->generator(15),
                    'invoice_id' => $invoice_id,
                    'invoice_id_new' =>  $invoice_id_new,
                    'product_id' => $product_id,
                    'customer_id' => $this->input->post('customer_id', TRUE),
                    'ret_qty' => $product_quantity,
                    'byy_qty' => $sqty,
                    'date_purchase' => $this->input->post('invoice_date', TRUE),
                    'date_return' => $date,
                    'product_rate' => $product_rate,
                    'deduction' => $discount,
                    'total_deduct' => $this->input->post('total_discount', TRUE),
                    'delivery_charge' => $add_cost,
                    //                'total_tax'     => $this->input->post('total_tax', TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
                    'reason' => $this->input->post('details', TRUE),
                    'usablity' => $usabilty
                );

                $this->db->insert('product_return', $returns);
            }

            for ($i = 0; $i < count($re_p_id); $i++) {

                $product_quantity = $rep_quantity[$i];
                $product_rate = $rep_rate[$i];
                $product_id = $re_p_id[$i];
                $total_price_wd = $re_total_price_wd[$i];
                $total_price = $re_total_price[$i];
                $supplier_rate = $this->supplier_price($product_id);
                $product_discount = $re_discount[$i];
                $product_distributed_discount = $re_distributed_discount[$i];
                $product_item_total_discount = $re_item_total_discount[$i];
                $product_total_discounted_price_wd = $re_total_discounted_price_wd[$i];
                $comm = $re_comm[$i];
                $vat = $vat_per[$i];
                $tax = $tax_per[$i];

                if ($outlet_id == 'HK7TGDT69VFMXB7') {
                    $stock_details = $this->Reports->getExpiryCheckList($product_id)['aaData'];
                } else {
                    $stock_details = $this->Rqsn->expiry_outlet_stock($product_id)['aaData'];
                }

                $product_quantity_new = $product_quantity;
                $inv_details_arr = array();

                foreach ($stock_details as $stock) {
                    if ($product_quantity_new < 1 || $stock['stok_quantity'] < 1) {
                        break;
                    } else {

                        $rest = ($product_quantity_new >= $stock['stok_quantity']) ? $stock['stok_quantity'] :  $product_quantity_new;
                        $new_array = array(
                            'invoice_details_id' => $this->generator(15),
                            'invoice_id'         => $invoice_id_new,
                            'purchase_id'         => $stock['purchase_id'],
                            'product_id'         => $product_id,
                            'quantity'           => $rest,
                            'description'        => 'Replacement',
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
                //     'invoice_id'         => $invoice_id_new,
                //     'purchase_id'         => $stock_details[0]['purchase_id'],
                //     'product_id'         => $product_id,
                //     'quantity'           => $aprv_qty,
                //     'rate'               => $product_rate,
                //     'description'        => 'Replacement',
                //     'discount_per'       => $disper,
                //     'commission_per'       => $comm,
                //     'supplier_rate'      => $supplier_rate,
                //     'total_price'        => $total_price,
                //     'total_price_wd'        => $total_price_wd,
                //     'vat'        => $vat,
                //     'tax'        => $tax,
                //     'status'             => 2
                // );

                // array_push($array, $first_array);


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
                //             'invoice_id'         => $invoice_id_new,
                //             'product_id'         => $product_id,
                //             'purchase_id'         => $item['purchase_id'],
                //             'quantity'           => $item['stok_quantity'] - $stockQty,
                //             'rate'               => '',
                //             'description'        => 'Replacement',
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
            }

            // <----- Previous End ----->

            $cus_ac_cr = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => $customer_headcode,
                'Narration' => 'Sales Return',
                'Debit' =>  0,
                'Credit' => $this->input->post('sales_return', TRUE) + $this->input->post('sku_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $cus_ac_cr);

            if ($this->input->post('re_total_discount', TRUE) > 0) {
                $re_sales_discount = array(
                    'VNo' => $invoice_id_new,
                    'Vtype' => 'Replacement',
                    'VDate' => $date,
                    'COAID' => 406,
                    'Narration' => 'Sales Discount  Replacement For  Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => $this->input->post('re_total_discount', TRUE),
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $re_sales_discount);
            }
            $sale_income_cr = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => 303,
                'Narration' => 'Sales Credit for  Replacement  Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                'Debit' => 0,
                'Credit' => $this->input->post('grandTotal', TRUE) + $this->input->post('re_total_discount', TRUE),
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $sale_income_cr);

            $cus_ac_db_repl = array(
                'VNo' => $invoice_id_new,
                'Vtype' => 'Return',
                'VDate' => $date,
                'COAID' => $customer_headcode,
                'Narration' => 'Replacement Against Return',
                'Debit' => $this->input->post('grandTotal', TRUE) + $this->input->post('re_total_discount', TRUE),
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $cus_ac_db_repl);

            if ($re_total_vat > 0) {
                $vat_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'Return',
                    'VDate' => $date,
                    'COAID' => 50203,
                    'Narration' => 'Total Vat  for Invoice ID - ' . $invoice_id,
                    'Credit' => $re_total_vat,
                    'Debit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $vat_transaction);
            }

            if ($re_total_tax > 0) {
                $tax_transaction = array(

                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $date,
                    'COAID' => 50204,
                    'Narration' => 'Total Tax  for Invoice ID - ' . $invoice_id,
                    'Credit' => $re_total_tax,
                    'Debit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $tax_transaction);
            }

            // echo $re_n_total;exit();
            if ($re_n_total < 0) {
                if ($this->input->post('re_cash_refund', TRUE) > 0) {
                    $cash = array(
                        'VNo' => $invoice_id_new,
                        'Vtype' => 'Replacement',
                        'VDate' => $date,
                        'COAID' => 1020101,
                        'Narration' => 'Cash Refund credit For Replacement For  Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                        'Debit' => 0,
                        'Credit' =>  $this->input->post('re_cash_refund', TRUE),
                        'IsPosted' => 1,
                        'CreateBy' => $createby,
                        'CreateDate' => $createdate,
                        'IsAppove' => 1
                    );
                    $this->db->insert('acc_transaction', $cash);

                    $cash_db_repl = array(
                        'VNo' => $invoice_id_new,
                        'Vtype' => 'Replacement',
                        'VDate' => $date,
                        'COAID' => $customer_headcode,
                        'Narration' => 'Cash Refund Debit For Replacement For Invoice No - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                        'Debit' => $this->input->post('re_cash_refund', TRUE),
                        'Credit' =>  0,
                        'IsPosted' => 1,
                        'CreateBy' => $createby,
                        'CreateDate' => $createdate,
                        'IsAppove' => 1
                    );
                    $this->db->insert('acc_transaction', $cash_db_repl);

                    $re_customer_ac = abs($this->input->post('re_customer_ac', TRUE));
                    if ($re_customer_ac > 0) {
                        $cus_ac = array(
                            'VNo' => $invoice_id_new,
                            'Vtype' => 'Replacement',
                            'VDate' => $date,
                            'COAID' => $customer_headcode,
                            'Narration' => 'Customer account cash refund Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                            'Debit' => 0,
                            'Credit' => abs($this->input->post('re_customer_ac', TRUE)),
                            'IsPosted' => 1,
                            'CreateBy' => $createby,
                            'CreateDate' => $createdate,
                            'IsAppove' => 1
                        );
                        $this->db->insert('acc_transaction', $cus_ac);
                    }
                }
            } else {
                //echo $re_n_total;exit();

                // if ($this->input->post('re_due_amount', TRUE) > 0) {
                //     $re_sales_discount = array(
                //         'VNo' => $invoice_id_new,
                //         'Vtype' => 'Replacement',
                //         'VDate' => $date,
                //         'COAID' => $customer_headcode,
                //         'Narration' => 'Customer account Debit Amount For Customer Invoice NO - ' . $invoice_no_generated . ' Customer- ' . $cusifo->customer_name,
                //         'Debit' => $this->input->post('re_due_amount', TRUE),
                //         'Credit' => 0,
                //         'IsPosted' => 1,
                //         'CreateBy' => $createby,
                //         'CreateDate' => $createdate,
                //         'IsAppove' => 1
                //     );
                //     $this->db->insert('acc_transaction', $re_sales_discount);
                // }
                $pay_type = $this->input->post('paytype', TRUE);
                $paid = $this->input->post('p_amount', TRUE);
                $bank_id = $this->input->post('bank_id_m', TRUE);

                $bkash_id = $this->input->post('bkash_id', TRUE);
                $bkashname = '';
                $card_id = $this->input->post('card_id', TRUE);
                $nagad_id = $this->input->post('nagad_id', TRUE);
                $total_paid_repl = 0;
                if (count($paid) > 0) {
                    //   echo count($paid);exit();
                    for ($i = 0; $i < count($pay_type); $i++) {

                        if ($paid[$i] > 0) {
                            $total_paid_repl += $paid[$i];
                            if ($pay_type[$i] == 1) {

                                $cc = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  1020101,
                                    'Narration'      =>  'Cash in Hand in Replacement for Invoice ID - ' . $invoice_id_new . ' customer- ' .  $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );


                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'      =>  $createdate,
                                    'status'        =>  1,
                                    'account'       => '',
                                    'COAID'         => 1020101
                                );

                                $this->db->insert('acc_transaction', $cc);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bankcoaid,
                                    'Narration'      =>  'Paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'COAID'         => $bankcoaid,
                                    'pay_date'       =>  $createdate,
                                    'status'        =>  1
                                );



                                $this->db->insert('paid_amount', $data);


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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bkashcoaid,
                                    'Narration'      =>  'Cash in Bkash paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bkashname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bkashcoaid,
                                    'status'        =>  1,
                                );


                                $this->db->insert('paid_amount', $data);
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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $nagadcoaid,
                                    'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          =>  $paid[$i],
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'pay_date'       =>  $createdate,
                                    'account'       => $nagadname,
                                    'COAID'         => $nagadcoaid,
                                    'status'        =>  1,
                                );



                                $this->db->insert('paid_amount', $data);

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
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INVOICE',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  $bankcoaid,
                                    'Narration'      =>  'Paid amount for customer in card - ' . $card_info[0]['card_no'] . '  Invoice ID - ' . $invoice_id_new . ' customer -' . $cusifo->customer_name,
                                    'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                                    'Credit'         =>  0,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $createby,
                                    'CreateDate'     =>  $createdate,
                                    'IsAppove'       =>  1,

                                );

                                $data = array(
                                    'invoice_id'    => $invoice_id_new,
                                    'pay_type'      => $pay_type[$i],
                                    'amount'        => $paid[$i],
                                    'account'       => $bankname,
                                    'pay_date'       =>  $createdate,
                                    'COAID'         => $bankcoaid,
                                    'status'        =>  1,
                                );

                                $this->db->insert('paid_amount', $data);

                                $carddebit = array(
                                    'VNo'            =>  $invoice_id_new,
                                    'Vtype'          =>  'INV',
                                    'VDate'          =>  $createdate,
                                    'COAID'          =>  '40404',
                                    'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice ID- ' . $invoice_id_new,
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

                    $cus_ac_cr_repl = array(
                        'VNo'            =>  $invoice_id_new,
                        'Vtype'          =>  'INV',
                        'VDate'          =>  $createdate,
                        'COAID'          =>  $customer_headcode,
                        'Narration'      =>  'Cash Payment Against Replacement',
                        'Debit'          =>  0,
                        'Credit'         =>  $total_paid_repl,
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $createby,
                        'CreateDate'     =>  $createdate,
                        'IsAppove'       =>  1,

                    );

                    $this->db->insert('acc_transaction', $cus_ac_cr_repl);
                }
            }

            redirect('Cinvoice/invoice_inserted_data/' . $invoice_id_new);
        }

        return $invoice_id;
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

    //Get Supplier rate of a product
    public function supplier_rate($product_id)
    {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function previous_sold($old_invoice_id, $product_id)
    {
        $this->db->select('id, supplier_rate, purchase_id, quantity');
        $this->db->from('invoice_details');
        $this->db->where(array('invoice_id' => $old_invoice_id, 'product_id' => $product_id));
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    ///#################### Supplier return  Entry ############///////////
    public function return_supplier_entry()
    {
       
        $purchase_id = $this->input->post('purchase_id', TRUE);
        $total       = $this->input->post('grand_total_price', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $isrtn       = $this->input->post('rtn', TRUE);
        $supinfo     = $this->db->select('*')->from('supplier_information')->where('supplier_id', $supplier_id)->get()->row();
        $sup_head    = $supinfo->supplier_id . '-' . $supinfo->supplier_name;
        $sup_coa     = $this->db->select('*')->from('acc_coa')->where('HeadName', $sup_head)->get()->row();
        $receive_by   = $this->session->userdata('user_id');
        $receive_date = date('Y-m-d');
        $createdate   = date('Y-m-d H:i:s');

        $this->load->model('Warehouse');
        $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
       
        $data = array(
            'returned_amount' => $total,
            'due_amount' => $this->input->post('due_amount', TRUE)- $total
        );
        $this->db->where('purchase_id', $purchase_id);
        $result = $this->db->update('product_purchase', $data);
        $date  = date('Y-m-d');

        $supplierledger = array(
            'VNo'            =>  $purchase_id,
            'Vtype'          =>  'Return',
            'VDate'          =>  $date,
            'COAID'          =>  $sup_coa->HeadCode,
            'Narration'      =>  'Supplier Return Debit for .' . $supinfo->supplier_name,
            'Debit'          =>  $total,
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $receive_by,
            'CreateDate'     =>  $receive_date,
            'IsAppove'       =>  1
        );
        $this->db->insert('acc_transaction', $supplierledger);
        if($this->input->post('total_discount', TRUE) > 0)
        {
            $purchase_return_loss = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Return',
                'VDate'          =>  $date,
                'COAID'          =>  $sup_coa->HeadCode,
                'Narration'      =>  'Loss on Purchase Return',
                'Debit'          =>  $this->input->post('total_discount', TRUE),
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1
            );
            $this->db->insert('acc_transaction', $purchase_return_loss);
        }
        if($this->input->post('total_itemwise_discount', TRUE) > 0)
        {
            $discount_adjustment = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Return',
                'VDate'          =>  $date,
                'COAID'          =>  301,
                'Narration'      =>  'Discount adjusted for Purchase Return',
                'Debit'          =>  $this->input->post('total_itemwise_discount', TRUE),
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1
            );
            $this->db->insert('acc_transaction', $discount_adjustment);
        }
       
        $purchase_return_cr = array(
            'VNo'            =>  $purchase_id,
            'Vtype'          =>  'Return',
            'VDate'          =>  $date,
            'COAID'          =>  307,
            'Narration'      =>  'Purchase Return Credit',
            'Debit'          =>  0,
            'Credit'         =>  $total + $this->input->post('total_discount', TRUE),
            'IsPosted'       =>  1,
            'CreateBy'       =>  $receive_by,
            'CreateDate'     =>  $receive_date,
            'IsAppove'       =>  1
        );
        $this->db->insert('acc_transaction', $purchase_return_cr);




        $quantity           = $this->input->post('product_quantity', TRUE);
        $available_quantity = $this->input->post('available_quantity', TRUE);
        $cartoon            = $this->input->post('cartoon', TRUE);
        $rate               = $this->input->post('product_rate', TRUE);
        $p_id               = $this->input->post('product_id', TRUE);
        $total_amount       = $this->input->post('total_price', TRUE);
        $discount_rate      = $this->input->post('discount', TRUE);
        $soldqty            = $this->input->post('ret_qty', TRUE);

        $pdid = $this->input->post('purchase_detail_id');


        if (is_array($p_id))
            for ($i = 0; $i < count($p_id); $i++) {
                $cartoon_quantity = $cartoon[$i];
                $product_quantity = $quantity[$i];
                $product_rate     = $rate[$i];
                $product_id       = $p_id[$i];
                $sqty             = $soldqty[$i];
                $total_price      = $total_amount[$i];
                $discount         = $discount_rate[$i];
                $detais_id        = $pdid[$i];

                $data1 = array(
                    'purchase_detail_id' => $detais_id,
                    'purchase_id'        => $purchase_id,
                    'product_id'         => $product_id,
                    'qty'           => -$product_quantity,
                    'quantity'           => -$product_quantity,
                    'rate'               => $product_rate,
                    'discount'           => -is_numeric($discount),
                    'total_amount'       => -$total_price,
                    'status'             => 1
                );


                $returns = array(
                    'return_id'    => $this->generator(15),
                    'outlet_id'  => $outlet_id ? $outlet_id : "HK7TGDT69VFMXB7",
                    'purchase_id'  => $purchase_id,
                    'product_id'   => $product_id,
                    'supplier_id'  => $this->input->post('supplier_id', TRUE),
                    'ret_qty'      => $product_quantity,
                    'byy_qty'      => $sqty,
                    'date_purchase' => $this->input->post('return_date', TRUE),
                    'date_return'  => $date,
                    'product_rate' => $product_rate,
                    'deduction'    => $discount,
                    'total_deduct' => $this->input->post('total_discount', TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price', TRUE),
                    'reason'       => $this->input->post('details', TRUE),
                    'usablity'     => $this->input->post('radio', TRUE)
                );


                $this->db->insert('product_purchase_details', $data1);

                $this->db->insert('product_return', $returns);
            }

        // $this->db->insert('acc_transaction', $supplierledger);

        return $purchase_id;
    }

    public function number_generator()
    {
        $this->db->select_max('invoice', 'invoice');
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

    // return list count
    public function return_list_count()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    ///start  return list
    public function return_list($perpage, $page)
    {
        $this->db->select('a.net_total_amount,a.*,b.customer_name,i.*');
        $this->db->from('product_return a');
        $this->db->join('invoice i', 'i.invoice_id = a.invoice_id');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->or_where('usablity', 2);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.date_return', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // date between search return list  invoice
    public function return_dateWise_invoice($from_date, $to_date, $perpage, $page)
    {
        $dateRange = "a.date_return BETWEEN '$from_date' AND '$to_date'";

        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        return $query->result_array();
    }

    // supplier return list
    public function supplier_return_list($perpage, $page)
    {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.purchase_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // date between search return list  supplier/purchase
    public function return_dateWise_supplier($from_date, $to_date, $perpage, $page)
    {
        $dateRange = "a.date_return BETWEEN '$from_date' AND '$to_date'";

        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->group_by('a.purchase_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function retrieve_invoice_html_data($invoice_id)
    {
        $this->db->select('c.total_ret_amount,
						c.*,
						b.*,
						d.product_id,
						d.product_name,
						d.product_details,
						d.product_model');
        $this->db->from('product_return c');
        $this->db->join('customer_information b', 'b.customer_id = c.customer_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('c.invoice_id', $invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function retrieve_replace_html_data($invoice_id)
    {
        return $this->db->select('a.*,b.product_name,b.product_model')
            ->from('invoice_details a')
            ->join('product_information b', 'a.product_id=b.product_id')
            ->where('invoice_id', $invoice_id)->get()->result();
    }

    // supplier return html data
    public function supplier_return_html_data($purchase_id)
    {
        $this->db->select('c.total_ret_amount,
						c.*,
                        c.product_rate as price,
						b.*,
						d.product_id,
						d.product_name,
						d.product_details,
						d.product_model');
        $this->db->from('product_return c');
        $this->db->join('supplier_information b', 'b.supplier_id = c.supplier_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('c.purchase_id', $purchase_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

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

    // wastage return list bellow
    public function wastage_return_list_count()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 3);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    // supplier list count
    public function supplier_return_list_count()
    {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    ///start  return list
    public function wastage_return_list($perpage, $page)
    {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 3);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /////////// supplier returns form data
    public function supplier_return($purchase_id)
    {
        $this->db->select('product_purchase.*,product_information.product_id,product_information.product_name,product_information.product_model,supplier_information.*,
        ((select ifnull(sum(product_purchase_details.quantity),0) from product_purchase_details where product_purchase_details.status = 2 )+(select ifnull(sum(product_purchase_details.quantity),0) from product_purchase_details where product_purchase_details.status = 1 )) as quantity,((select ifnull(sum(product_purchase_details.qty),0) from product_purchase_details where product_purchase_details.status = 2 )+(select ifnull(sum(product_purchase_details.qty),0) from product_purchase_details where product_purchase_details.status = 1 )) as qty,product_purchase_details.rate,product_purchase_details.discount,product_purchase_details.total_amount');
        $this->db->from('product_purchase_details');
        $this->db->join('product_purchase', 'product_purchase.purchase_id =product_purchase_details.purchase_id');
        $this->db->join('product_information', 'product_information.product_id = product_purchase_details.product_id');
        $this->db->join('supplier_information', 'supplier_information.supplier_id = product_purchase.supplier_id');
        $this->db->where('product_purchase_details.purchase_id', $purchase_id);
        $this->db->group_by('product_purchase_details.product_id');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // return delete with invoice id
    public function returninvoice_delete($invoice_id = null)
    {
        $this->db->where('invoice_id', $invoice_id)
            ->delete('product_return');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    // return delete with purchase id
    public function return_purchase_delete($purchase_id = null)
    {
        $this->db->where('purchase_id', $purchase_id)
            ->delete('product_return');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    // pdf invoice return list
    public function pdf_invoice_return_list()
    {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->group_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // supplier return list pdf
    public function pdf_supplier_return_list()
    {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.purchase_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}

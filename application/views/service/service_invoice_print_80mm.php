<?php
$CI = &get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice_onloadprint.js" type="text/javascript"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="border:0; margin:0">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>
            </div>
        <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>
            </div>
        <?php
            $this->session->unset_userdata('error_message');
        }
        ?>
        <div class="row" style="border: 0 !important; margin: 0 !important;">
            <div class="col-sm-12" style="border: 0 !important; margin: 0 !important;">
                <div class="panel panel-bd" style="border: 0 !important; margin: 0 !important;">
                    <div id="printableArea" style="border: 0 !important; margin: 0 !important;">
                        <style type="text/css" scoped>
                            @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

                            @import url('https://fonts.googleapis.com/css2?family=Petrona:wght@300&display=swap');

                            @media print {

                                .subt {
                                    font-size: 14px !important;
                                    line-height: 1 !important;
                                }

                                html,
                                body {
                                    border: 0;
                                    margin: 0;
                                    box-sizing: border-box;

                                    /*width: 58mm;*/
                                    width: 80mm !important;
                                    /* padding-right: 5px !important; */
                                    /* letter-spacing: 1px; */
                                    font-family: 'Share Tech Mono', monospace !important;

                                }

                                .com-name {
                                    font-size: 15px !important;
                                }


                                /*table>thead>th.pr {*/
                                /*    width: 70%;*/
                                /*}*/
                                /*table>thead>th.ur {*/
                                /*    width:10%;*/
                                /*}*/



                                .invo {
                                    display: flex;
                                    justify-content: space-around;
                                }

                                .td-style {
                                    /* border-left: 1px solid black !important; */
                                    /*border-bottom: 1px solid black !important;*/
                                }


                                table#tbl {
                                    width: 80mm !important;
                                    font-size: 15px !important;
                                    border-collapse: collapse !important;
                                    /* white-space: nowrap; */
                                    table-layout: auto;
                                    word-break: break-all !important;
                                }

                                table#tbl td {
                                    overflow-x: hidden !important;
                                    /* border-bottom: 1px solid black !important; */
                                }

                                /* table#tbl th:nth-of-type(1) {
                                    width: 35mm !important;
                                }

                                table#tbl th:nth-of-type(2) {
                                    width: 25mm !important;
                                }

                                table#tbl th:nth-of-type(3) {
                                    width: 20mm !important;
                                } */

                                /* ///=========================// */

                                th {
                                    border-bottom: 1px solid black !important;
                                    border-top: 1px solid black !important;
                                }

                                .header1 {
                                    width: 40%;
                                }

                                .header2 {
                                    width: 40%;
                                }

                                .header3 {
                                    width: 20%;
                                }

                                .header4 {
                                    width: 10%;
                                }

                                #SalesTotal {
                                    font-weight: bold;
                                    border-bottom: 1px solid black !important;
                                    border-top: 1px solid black !important;
                                    /* padding-right:20px; */

                                }

                                td.paddingLeft {
                                    padding-left: 15px !important;
                                }


                                td.paddingBottom {
                                    padding-bottom: 3px !important;
                                }

                                /* ///=========================// */



                                /* table#tbl td:nth-of-type(1) {
                                    width: 25mm !important;
                                }

                                table#tbl td:nth-of-type(2) {
                                    width: 18mm !important;
                                }

                                table#tbl td:nth-of-type(3) {
                                    width: 15mm !important;
                                } */

                                table#tbl td {
                                    word-break: break-all !important;
                                }

                                /*Setting the width of column 3.*/

                                /* table.item-table>tr>td {
                                    font-size: 11px !important;
                                    word-break: break-word !important;
                                } */



                                /* th,
                                td {
                                    overflow: hidden;
                                } */
                            }
                        </style>
                        <div class="panel-body" style="border: 0 !important; margin-left: -3mm !important;">

                            <?php if ($setting_company_logo == "1") { ?>
                                <div style="width: 100%;" class="text-center">

                                    <img src="<?= $company_logo ?>" style="width: 20mm; height: auto;"><br>
                                </div>
                            <?php } ?>
                            <div class="text-center">
                                <div align="" style="line-height: 1; border: 0; padding:0">
                                    {company_info}
                                    <?php if ($setting_company_name == "1") { ?>
                                        <span style="font-size: 15px !important;">
                                            <strong>{company_name}</strong>
                                        </span><br>
                                    <?php } ?>
                                    <?php if ($setting_company_address == "1") { ?>
                                        <span style="font-size: 14px !important;">
                                            <p>{address}</p>
                                        </span><br>
                                    <?php } ?>
                                    {/company_info}

                                    <?php if ($invoice_info[0]['outlet_id'] == "HK7TGDT69VFMXB7") { ?>
                                        <?php if ($setting_outlet_name == "1") { ?>
                                            <span class="subt">Central Warehouse</span><br>
                                        <?php } ?>
                                    <?php } else { ?>
                                        {outlet_details}
                                        <?php if ($setting_outlet_logo == "1") { ?>
                                            <div style="width: 100%;" class="text-center">
                                                <img src="<?= '{image}' ?>" style="width: 20mm; height: auto;">
                                            </div>
                                        <?php } ?>
                                        <?php if ($setting_outlet_name == "1") { ?>
                                            <span class="subt">{outlet_name}</span><br>
                                        <?php } ?>
                                        <?php if ($setting_outlet_address == "1") { ?>
                                            <span class="subt">{address}</span><br>
                                        <?php } ?>
                                        <?php if ($setting_outlet_number == "1") { ?>
                                            <span class="subt">{phn_no}</span><br>
                                        <?php } ?>
                                        {/outlet_details}
                                        <!-- <?php if ($setting_govt_licence == "1") { ?>
                                        <span class="subt">{tax_regno}</span>
                                    <?php } ?> -->

                                    <?php } ?>


                                </div>
                            </div>

                            <?php if ($invoice_info[0]['outlet_id'] != "HK7TGDT69VFMXB7") { ?>
                                <?php if ($setting_govt_licence == "1") { ?>
                                    <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">
                                        {outlet_details}
                                        <div class="col-xs-6">

                                            <nobr>BIN: {bin}</nobr>
                                        </div>
                                        <div class="col-xs-6 text-right m-0">
                                            <nobr> Mushak: {mushak}</nobr>
                                        </div>
                                        {/outlet_details}
                                    </div>
                                <?php } ?>

                            <?php }  ?>



                            <!-- <?php if ($setting_govt_licence == "1") { ?>
                                <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">
                                    {outlet_details}
                                    <div class="col-xs-6">

                                        <nobr>BIN: {bin}</nobr>
                                    </div>
                                    <div class="col-xs-6 text-right m-0">
                                        <nobr> Mushak: {mushak}</nobr>
                                    </div>
                                    {/outlet_details}
                                </div>
                            <?php } ?> -->
                            <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">
                                <div class="col-xs-6">
                                    <nobr>Service Name: <?= $service_name ?></nobr>
                                </div>


                            </div>
                            <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">

                                <div class="col-xs-6">
                                    <nobr>Technician Name:<?= $technician_name ?></nobr>
                                </div>

                            </div>
                            
                            <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">

                                <div class="col-xs-6">
                                    <nobr>Apprx. Delivery Date: <?= $invoice_info[0]['delivery_date'] ?></nobr>
                                </div>

                            </div>
                            
                            <?php if ($setting_sales_by == "1") { ?>
                                <div class="row" style="font-size: 14px; margin-top: 0.2cm !important">

                                    <div class="col-xs-4">

                                    </div>

                                    <div class="col-xs-8 text-right m-0">
                                        <nobr> Served By: <?= $served_by ?></nobr>
                                    </div>


                                </div>
                            <?php } ?>

                            <nobr> _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _</nobr>

                            <?php if ($setting_customer_information == "1") { ?>
                                <div class="text-center" align="" style="font-size: 15px; margin-top: 0.2cm !important">
                                    <div><b><?= $customer_name ?></b><br>
                                        <?= $customer_address ?><br>
                                        <?= $customer_mobile ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="text-center">
                                <?php if ($setting_invoice_number == "1") { ?>

                                    <div style="margin-top: 0.3cm !important;">
                                        <div>
                                            <strong><?php echo display('invoice_no'); ?> : <?= $invoice_info[0]['invoice_number'] ?></strong>
                                            </nobr>

                                        </div>
                                    </div>

                                <?php } ?>
                                <?php if ($setting_invoice_date == "1") { ?>
                                    <div style=" font-size: 14px !important">
                                        <nobr>
                                            <date>
                                                Date: <?= $invoice_info[0]['invoice_date'] ?>
                                            </date>
                                        </nobr>
                                    </div>
                                <?php } ?>
                            </div>

                            <div style="margin: 0; padding:0">
                                <?php if ($invoice_info) { ?>
                                    <table class="item_table " id="tbl">
                                        <thead>
                                            <th class="text-left pr header1"><?php echo display('item'); ?></th>
                                            <th class="text-center td-style ur header2">Qty</th>
                                            <th class="text-center td-style ur header2">Total</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            $s_total = 0;
                                            foreach ($invoice_info as $invoice) { ?>
                                                <tr>
                                                    <td align="left">
                                                        <?= $invoice['product_name'] ?>
                                                        <?= $invoice['warranty_date'] ? "<br>warranty date:<br>(".$invoice['warranty_date']. ")": '' ?>
                                                    </td>
                                                    

                                                    <td align="center" class="td-style">
                                                          <?= $invoice['quantity'] ?>
                                                    </td>
                                                    <td align="right">
                                                        <?= $invoice['quantity'] * $invoice['item_rate'] ?>
                                                    </td>
                                                </tr>

                                            <?php } ?>

                                        </tbody>

                                    </table>
                                <?php } ?>
                            </div>

                            <div style="margin: 0; padding:0">
                                <br>
                                <table class="item_table " id="tbl">
                                    <tbody>
                                    <tr id="SalesTotal">
                                            <td align="right" colspan="2">
                                                <nobr>Total Price</nobr>
                                            </td>
                                            <td align="right" class="td-style">
                                                <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['total_selling_price']  : $invoice_info[0]['total_selling_price'] . " ".$currency)) ?>
                                                </nobr>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="2">
                                                <nobr>Service Price</nobr>
                                            </td>
                                            <td align="right" class="td-style">
                                                <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['service_price']  : $invoice_info[0]['service_price'] . " ".$currency)) ?>
                                                </nobr>
                                            </td>
                                        </tr>
                                            <tr>
                                                <td align="right" colspan="2">
                                                    <nobr><?php echo display('total_discount') ?></nobr>
                                                </td>
                                                <td align="right" class="td-style">
                                                    <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['total_discount']  : $invoice_info[0]['total_discount'] . " ".$currency)) ?>
                                                    </nobr>
                                                </td>
                                            </tr>
                                        
                                       
                                            <tr>
                                                <td align="right" colspan="2">
                                                    <nobr><?php echo display('grand_total') ?></nobr>
                                                </td>
                                                <td align="right" class="td-style">
                                                    <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['grand_total']  : $invoice_info[0]['grand_total'] . " ".$currency)) ?>
                                                    </nobr>
                                                </td>
                                            </tr>
                                        <tr>
                                            <td align="right" colspan="2">
                                                <nobr><?php echo display('rounding') ?></nobr>
                                            </td>
                                            <td align="right" class="td-style">
                                                <nobr>
                                                <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['rounding']  : $invoice_info[0]['rounding'] . " ".$currency)) ?>
                                                </nobr>
                                            </td>
                                        </tr>
                                            <tr>
                                                <td align="right" colspan="2">
                                                    <nobr><strong>Net Payable</strong></nobr>
                                                </td>
                                                <td align="right" class="td-style">
                                                    <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['net_total']  : $invoice_info[0]['net_total'] . " ".$currency)) ?>
                                                    </nobr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" colspan="2">
                                                    <nobr><?php echo display('paid_amount') ?></nobr>
                                                </td>
                                                <td align="right" class="td-style">
                                                    <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['paid_amount']  : $invoice_info[0]['paid_amount'] . " ".$currency)) ?>
                                                    </nobr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" colspan="2">
                                                    <nobr><?php echo display('due_amount') ?></nobr>
                                                </td>
                                                <td align="right" class="td-style">
                                                    <nobr>
                                                    <?php echo html_escape((($position == 0) ? $currency . " ". $invoice_info[0]['due_amount']  : $invoice_info[0]['due_amount'] . " ".$currency)) ?>
                                                    </nobr>
                                                </td>
                                            </tr>

                                    </tbody>

                                </table>




                                <div class="text-center" align="center" style="">
                                    <!-- <div style="margin:0px"><b>Note: </b><br> -->
                                    <div style="text-align:center; margin-top: 0.1cm;">
                                        <td>
                                            <p style="font-size: 10.5px;">

                                                Exchange must be within 15 days & only for once. <br>
                                                Please bring this invoice to exchange product. <br>
                                                // No exchange or refund on discount product. <br>




                                            </p>
                                        </td>

                                    </div>
                                    <!-- </div> -->
                                </div>

                                <div style="text-align:center; margin-top: 0;font-size: 13px">
                                    <td>Powered by <strong>Devenport</strong></a></td>

                                </div>
                            </div>

                        </div>


                    </div>
                </div>
                <!--  -->
                <div class="panel-footer text-left">
                    <input type="hidden" name="" id="url" value="<?php echo base_url('Cservice/manage_service_invoice'); ?>">
                    <a class="btn btn-danger" href="<?php echo base_url('Cservice/manage_service_invoice'); ?>"><?php echo display('cancel') ?></a>
                    <a class="btn btn-info" href="#" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></a>
                </div>
            </div>
        </div>
</div>
</section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
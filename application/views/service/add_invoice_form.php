
<!-- <script src="<?php echo base_url() ?>my-assets/js/admin_js/json/service.js.php" ></script> -->
<!-- service Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/service.js" type="text/javascript"></script>

<style>
    .modal-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  background: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}
 /* Styling for the inner divs */
 .d_deduction {
      display: none;
    }

    /* Styling for the active inner div */
    .d_deduction.active {
      display: block;
    }

</style>
<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('service') ?></h1>
            <small><?php echo display('service_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('service') ?></a></li>
                <li class="active"><?php echo display('service_invoice') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
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
        <!--Add Invoice -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('service_invoice') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Cservice/insert_service_invoice', array('class' => 'form-vertical', 'id' => '', 'name' => '')) ?>
                    <div class="panel-body">
                       <!-- Invoice First Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Date" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <?php
                               
                                        $date = date('Y-m-d');
                                        ?>
                                        <input class="datepicker form-control" type="text" size="50" name="invoice_date" id="date" required value="<?php echo $date; ?>" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Delivery Date" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <?php
                               
                                        $date = date('Y-m-d');
                                        ?>
                                        <input class="datepicker form-control" type="text" size="50" name="delivery_date" id="delivery_date" required value="<?php echo $date; ?>" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section 2 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Invoice Number" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input readonly class="form-control" type="text" size="50" name="invoice_number" required value="<?php echo $invoice_number; ?>" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Outlet Name" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                       <select readonly  name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="<?php echo html_escape($outlet_id) ?>" selected><?php echo html_escape($outlet_name); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Section 2 End -->
                        <!-- Section 3 Start -->
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="form-group row">
                                    <label for="customer_name" class="col-sm-4 col-form-label"><?php
                                        echo display('customer_name');
                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                    <input autocomplete="off" type="text" size="100" name="customer_name" class=" form-control" placeholder="<?php echo pos_display('customer_name'); ?>" id="customer_name" tabindex="1" onkeyup="customer_autocomplete()" value="Walking Customer" />
                                        <!-- <input type="text" size="100"  name="customer_name" class=" form-control" placeholder='<?php echo display('customer_name')?>' id="customer_name" tabindex="1" onkeyup="customer_autocomplete()"/> -->

                                        <input id="autocomplete_customer_id" class="customer_hidden_value abc" value="1" type="hidden" name="customer_id">
                                    </div>
                                     <?php if($this->permission1->method('add_customer','create')->access()){ ?>
                                    <div  class=" col-sm-2">
                                       <a href="#" class="client-add-btn btn btn-success" aria-hidden="true" data-toggle="modal" data-target="#customer_add"><i class="ti-plus m-r-2"></i></a>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Service Price" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                    <input class="form-control" onkeyup="quantity_calculate();" onchange="quantity_calculate();" id="service_charge" type="number" size="50" name="service_price" value="0.00" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section 3 End -->
                        <!-- Section 4 Start -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="service_name" class="col-sm-4 col-form-label"><?php
                                        echo display('service_name');
                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text" size="100" id="service_name"  name="service_id" class=" form-control" placeholder='<?php echo display('service_name') ?>' tabindex="1" onkeyup="service_autocomplete()"/>

                                        <input id="autocomplete_service_id" class="service_hidden_value abc" type="hidden" name="service_id">
                                    </div>
                                     <?php if($this->permission1->method('add_service','create')->access()){ ?>
                                    <div  class=" col-sm-2">
                                       <a href="#" class="client-add-btn btn btn-success" aria-hidden="true" data-toggle="modal" data-target="#service_add"><i class="ti-plus m-r-2"></i></a>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="technician_id" class="col-sm-4 col-form-label">
                                        <?php echo "Technician Name"; ?><i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select name="technician_id" id="technician_id" required class="form-control">
                                            <?php
                                            if($outlet_name == 'Central Warehouse')
                                            {
                                                foreach ($tech_list as $tech) {                                                ?>
                                                    <option selected value="<?php echo $tech['user_id']; ?>"><?php echo $tech['first_name'] . " " . $tech['last_name']; ?></option>
                                                <?php
                                                    }
                                            }
                                            else{
                                            foreach ($tech_list as $tech) {
                                                if($technician_id == $tech['user_id']){
                                            ?>
                                                <option selected value="<?php echo $tech['user_id']; ?>"><?php echo $tech['first_name'] . " " . $tech['last_name']; ?></option>
                                            <?php
                                                }
                                            }
                                        }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="table-responsive" id="form_table">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field"><?php echo "Item Information" ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo "Stock" ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields" ><?php echo "Purchase Price" ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo "Selling Price" ?> </th>
                                        <th class="text-center"><?php echo "Quantity" ?> </th>
                                        <th class="text-center"><?php echo "Deduction / Fund" ?> 
                                        </th>
                                        <th class="text-center"><?php echo "Warranty" ?></th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <tr data-rowid="1">
                                        <td width = "35%">
                                            <div class="col-md-10">
                                                <input type="hidden" class="serial_no" id="serial_no">
                                            <input autocomplete="off" type="text" required name="product_name" onkeypress="invoice_productList(1)" id="product_name_1" class="form-control productSelection" placeholder="<?php echo display('product_name') ?>" tabindex="5">
                                                
                                                <input type="hidden" class="product_id_1" name="product_id[]" id="product_id_1">
                                                <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>">
                                            </div>
                                            <div class="col-md-1">
                                                
                                            <a href="#" class="client-add-btn btn btn-success open-modal-product open-modal-button  open-modal" data-serial="1" aria-hidden="true" data-toggle="modal" data-target="#product_add">
                                                <i class="ti-plus m-r-6"></i>
                                            </a>
                                            </div>
                                        </td>

                                        <td width = "11%">
                                            <input type="number" name="stock[]" class="stock_1 form-control  text-right" id="stock_1" placeholder="0.00" min="0" tabindex="8" required="required"/>
                                        </td>
                                        <td width = "11%">
                                        <input type="hidden" id="total_purchase_quantity_price_1" class="form-control total_purchase_quantity_price  text-right" name="total_purchase_quantity_price[]" value="0.00" readonly="readonly"/>

                                            <input type="text" name="purchase_price[]" id="purchase_price_1" class="purchase_price_1 purchase_price form-control text-right" tabindex="9" required="" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                            <br>
                                            <a href="#" class="btn btn-info open-modal-purchase open-modal-button" data-serial="1" aria-hidden="true" data-toggle="modal" data-target="#purchase_add">
                                                Add Purchase
                                            </a>
                                        </td>
                                        <td width = "11%">
                                        <input type="hidden" name="total_selling_price[]" id="total_selling_price_1" class="total_selling_price_1 total_selling_price form-control text-right" tabindex="9" required="" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                            <input type="text" name="selling_price[]" id="selling_price_1" class="selling_price_1 selling_price form-control text-right" tabindex="9" required="" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                            <br>
                                        </td>
                                        <td width = "10%">
                                            <input type="text" name="quantity[]" id="quantity_1" class="quantity_1 quantity form-control text-right" tabindex="9" required="" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                            <br>
                                        </td>
                                        <td width = "10%">
                                            <input type="text" name="deduction_fund[]" id="deduction_fund_1" class="deduction_fund_1 deduction_fund form-control text-right" tabindex="9"  onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                            
                                            <!-- <a style="margin-left: 10px;margin-bottom:10px" href="#" class="btn btn-primary open-modal-view_deduction" data-serial="1" aria-hidden="true" data-toggle="modal" data-target="#view_deduction">
                                             <i class="fa fa-eye"></i>
                                            </a> -->
                                         <br>
                                            <a class="btn btn-info open-modal-deduction_button open-modal-button" data-serial="1" aria-hidden="true" data-toggle="modal">
                                                Add Deduction
                                            </a>
                                        </td>

                                        
                                        

                                        <td width = "13%">
                                            <!-- <?php
                                            $date = date('d-m-Y');
                                            $threeMonthsLater = date('d-m-Y', strtotime($date . ' +3 months'));

                                            ?> -->
                                           <input class="datepicker warranty_date form-control" type="text" size="50" name="warranty_date[]" id="warranty_date_1" value="" tabindex="6" />
                                           <br>
                                           <input type="number" name="claim_percentage[]" class="claim_percentage form-control text-right" id="claim_percentage_1" placeholder="0.00" min="0" tabindex="8"/>

                                        </td>

                                        
                                    </tr>
                                </tbody>
                              <tfoot>

                                        <tr>
                                            <td colspan="4" rowspan="2">
                                                <center><label  for="details" class="  col-form-label text-center"><?php echo display('invoice_details') ?></label></center>
                                                <textarea name="inva_details" class="form-control" placeholder="<?php echo display('invoice_details') ?>"></textarea>
                                            </td>
                                            <td>
                                                <input type="button" id="add_invoice_item" class="btn btn-info" name="add-invoice-item"  onClick="addInputField('addinvoiceItem');" value="<?php echo display('add') ?>" />
                                            </td>
                                            <td class="text-right" colspan="1">
                                                <b><?php echo "Total Service Price" ?>:</b>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" id="total_service_price" class="form-control text-right" name="total_service_price" placeholder="0.00"  />
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            
                                            
                                            <td class="text-right" colspan="2"><b><?php echo "Discount(Amount)" ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="discount_amount" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" class="form-control text-right" name="discount_amount" value="0.00" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="6"><b><?php echo "Discount(%)" ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="discount_percentage" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" class="form-control text-right" name="discount_percentage" value="0.00" />
                                            </td>
                                            
                                        </tr>
                                       
                                        <tr>
                                            <td class="text-right" colspan="6"><b><?php echo display('total_discount') ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="total_discount_amount" class="form-control text-right" name="total_discount_amount" value="0.00" readonly="readonly" />
                                            </td>
                                            
                                        </tr>           

                                        <tr>
                                            <td class="text-right" colspan="6"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="grand_total" class="form-control text-right" name="grand_total" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);"  placeholder="0.00"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"  class="text-right"><b><?php echo "Previous Due" ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="previous" class="form-control text-right" name="previous" value="0.00" readonly="readonly" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"  class="text-right"><b><?php echo "Auto Rounding"; ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="rounding" class="form-control text-right" name="rounding" value="0.00" readonly="readonly" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"  class="text-right"><b><?php echo display('net_total'); ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="net_total" class="form-control text-right" name="net_total" value="0" readonly="readonly" placeholder="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                            

                                                <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>"/>
                                            </td>
                                            <td class="text-right" colspan="5"><b><?php echo display('paid_ammount') ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="paid_amount" 
                                                    onkeyup="invoice_paidamount();" readonly class="form-control text-right" name="paid_amount" placeholder="0.00" tabindex="13" value=""/>
                                            </td>
                                        </tr>
                                    <tr>
                                        

                                        <td class="text-right" colspan="6"><b><?php echo display('due') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="due_amount" class="form-control text-right" name="due_amount" value="0.00" readonly="readonly"/>
                                            <input type="hidden" id="total_deduction" class="form-control text-right" name="total_deduction" value="0.00" readonly="readonly"/>
                                            <input type="hidden" id="total_purchase_price" class="form-control text-right" name="total_purchase_price" value="0.00" readonly="readonly"/>
                                            <input type="hidden" id="total_selling_price" class="form-control text-right" name="total_selling_price" value="0.00" readonly="readonly"/>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                            <td colspan="5"  class="text-right"><b><?php echo "Change Amount"; ?>:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="change_amount" class="form-control text-right" name="change_amount" value="0" readonly="readonly" placeholder="" />
                                            </td>
                                        </tr>
                              </tfoot>
                            </table>
                            <div class="modal fade modal-success" id="view_deduction" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            
                                            <a href="#" class="close" data-dismiss="modal">&times;</a>
                                            <h3 class="modal-title"><?php echo "View Deduction" ?></h3>
                                        </div>
                                        
                                        <div class="modal-body">
                                        <div class="panel-body" id="dataContainer" style="display: block;">
                                        <!-- Appended data will appear here -->
                                        </div>
                                    
                                        </div>

                                        <div class="modal-footer">
                                            
                                            <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                            
                                            <!-- <input type="submit" class="btn btn-success" value="Submit"> -->

                                        </div>
                                    
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-12" id="payment_div">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h3><?php echo pos_display('payment'); ?></h3>
                                            <input type="hidden" id="count" value="2">
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <div id="pay_div">
                                            <div class="row row_div">
                                                <div class="col-sm-4">
                                                    <label for="payment_type" class="col-sm-5 col-form-label">
                                                        <?php echo pos_display('payment_type'); ?>
                                                        <i class="text-danger">*</i>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <select name="paytype[]" class="form-control pay_type" required="" onchange="bank_paymet(this.value, 1)" tabindex="3">
                                                            <option value="1"><?php echo pos_display('cash_payment') ?></option>
                                                            <option value="2"><span class="">Cheque Payment</span></option>
                                                            <option value="4"><?php echo pos_display('bank_payment') ?></option>
                                                            <option value="3">Bkash Payment</option>
                                                            <option value="5">Nagad Payment</option>
                                                            <option value="7">Rocket Payment</option>
                                                            <option value="6">Card Payment</option>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-sm-4" id="bank_div_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-3 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">

                                                            <input type="text" name="bank_id" class="form-control" id="bank_id_1" placeholder="Bank">

                                                        </div>

                                                        <div class="col-sm-1">
                                                            <a href="#" class="client-add-btn btn btn-sm btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>
                                                        </div>
                                                    </div>

                                                </div>



                                                <div class="col-sm-4" id="bank_div_m_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-5 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <select name="bank_id_m[]" class="form-control bankpayment" id="bank_id_m_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bank_list as $bank) { ?>
                                                                    <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="bank_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bank_list as $bank) { ?>
                                                                <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                            <?php } ?>'>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="bkash_div_1">

                                                    <div class="form-group row">
                                                        <label for="bkash" class="col-sm-5 col-form-label">Bkash Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="bkash_id[]" class="form-control bankpayment" id="bkash_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bkash_list as $bkash) { ?>
                                                                    <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                            <input type="hidden" id="bkash_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                                <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                            <?php } ?>'>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="nagad_div_1">
                                                    <div class="form-group row">
                                                        <label for="nagad" class="col-sm-5 col-form-label">Nagad Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="nagad_id[]" class="form-control bankpayment" id="nagad_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($nagad_list as $nagad) { ?>
                                                                    <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="nagad_list" value='<option value="">Select One</option>
                                                            <?php foreach ($nagad_list as $nagad) { ?>
                                                                <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="rocket_div_1">
                                                    <div class="form-group row">
                                                        <label for="rocket" class="col-sm-5 col-form-label">Rocket Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="rocket_id[]" class="form-control bankpayment" id="rocket_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($rocket_list as $rocket) { ?>
                                                                    <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="rocket_list" value='<option value="">Select One</option>
                                            <?php foreach ($rocket_list as $rocket) { ?>
                                                <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>


                                                <div class="col-sm-4" style="display: none" id="card_div_1">
                                                    <div class="form-group row">
                                                        <label for="card" class="col-sm-5 col-form-label">Card Type <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="card_id[]" class="form-control bankpayment" id="card_id_1" onchange="">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($card_list as $card) { ?>
                                                                    <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="card_list" value='<option value="">Select One</option>
                                                            <?php foreach ($card_list as $card) { ?>
                                                                <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>


                                                </div>

                                                <div class="col-sm-3" id="ammnt_1">
                                                    <label for="p_amount" class="col-sm-5 col-form-label"> <?php echo pos_display('amount '); ?> <i class="text-danger">*</i></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control p_amount" type="text" name="p_amount[]" onchange="calc_paid()" onkeyup="calc_paid()">
                                                    </div>


                                                </div>
                                                <div class="col-sm-1">
                                                    <a id="add_pt_btn" onclick="add_pay_row(1)" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>

                                <input type="submit" id="add_invoice" class="btn btn-success" name="add-invoice" value="<?php echo display('submit') ?>" tabindex="15"/>
                            </div>

                            

                            <div class="modal fade modal-success" id="cheque_info" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <a href="#" class="close" data-dismiss="modal">&times;</a>
                                            <h3 class="modal-title">Add Cheque</h3>
                                        </div>

                                        <div class="modal-body">
                                            <div id="customeMessage" class="alert hide"></div>

                                            <div class="panel-body">
                                                <div class="addCheque">
                                                    <div id="cheque" class="cheque">
                                                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                                        <label for="bank" class="col-sm-4 col-form-label">Cheque type:
                                                            <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="cheque_type[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Cheque NO:
                                                            <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">
                                                            <input type="number" name="cheque_no[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>


                                                        <label for="date" class="col-sm-4 col-form-label">Due Date <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">

                                                            <input class="form-control" type="date" size="50" name="cheque_date[]" id="" value="" tabindex="4" autocomplete="off" placeholder="mm/dd/yyyy" />
                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Amount:
                                                            <i class="text-danger">*</i></label>

                                                        <div class="col-sm-6">
                                                            <input type="number" name="amount[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Image:
                                                            <i class="text-danger">*</i></label>

                                                        <div class="col-sm-6" style="padding-bottom:10px ">
                                                            <input type="file" name="image[]" class="form-control" id="image" tabindex="4">

                                                        </div>




                                                        <div class=" col-sm-1">
                                                            <a href="javascript:" id="Add_cheque" class="client-add-btn btn btn-primary add_cheque"><i class="fa fa-plus-circle m-r-2"></i></a>
                                                        </div>


                                                    </div>
                                                </div>



                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>


                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->


                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
              <!-- Add Customer Modal -->
                <div class="modal fade modal-success" id="customer_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo display('add_new_customer') ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <div id="customer_message" class="alert hide"></div>
                                <?php echo form_open('Cservice/instant_customer', array('class' => 'form-vertical', 'id' => 'add_customer')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                        <label for="customer_name" class="col-sm-3 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="customer_name" id="" type="text" placeholder="<?php echo display('customer_name') ?>"  required="" tabindex="1">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('customer_mobile') ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="mobile" id="mobile" type="number" placeholder="<?php echo display('customer_mobile') ?>" min="0" tabindex="3">
                                        </div>
                                    </div>
                                
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
              <!-- Add Customer Modal -->
              <!-- Add Service Modal -->
              <div class="modal fade modal-success" id="service_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo "Add New Service" ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <div id="service_message" class="alert hide"></div>
                                <?php echo form_open('Cservice/instant_service', array('class' => 'form-vertical', 'id' => 'add_service')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                    <label for="category_id" class="col-sm-4 col-form-label"><?php echo "Service Category" ?> <i class="text-danger">*</i></label>
                                        <div class="col-sm-6">
                                            <select name="category_id" id="category_id" class="form-control" required="">
                                                <option value="">Select Service Category</option>
                                                <?php foreach ($categories as $cat) { ?>
                                                    <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label"><?php echo "Service name" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="service_name" id="service_name" type="text" placeholder="<?php echo display('service_name') ?>" tabindex="2"> 
                                        </div>
                                    </div>

                                    
                                
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
              <!-- Add Service Modal -->
              <!-- Add Product Modal -->
              <div class="modal fade modal-success" id="product_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo "Add New Product" ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <!-- <div id="product_message" class="alert hide"></div> -->
                                <?php echo form_open('Cservice/instant_product', array('class' => 'form-vertical', 'id' => 'add_product')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                        <label for="product_type" class="col-sm-4 col-form-label"><?php echo "Product Type" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" value="Raw Material" name ="product_type" id="product_type" readonly type="text" placeholder="Raw Material" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name_bn" class="col-sm-4 col-form-label"><?php echo "Item Name (In Bangla)" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="product_name_bn" id="product_name_bn" type="text" placeholder="Enter Item Name (Bangla)" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_type" class="col-sm-4 col-form-label"><?php echo "Item Name (In English)" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="product_name" id="product_name" type="text" placeholder="Enter Item Name (English)" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="unit" class="col-sm-4 col-form-label"><?php echo "Base Unit" ?></label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="unit" name="unit" aria-hidden="true">
                                                <!-- <option value="">Select One</option> -->
                                                <?php foreach ($unit_list as $unit) {
                                                    if($unit['unit_name'] == "PC"){
                                                    ?>
                                                         <option selected value="<?php echo html_escape($unit['unit_name']) ?>"><?php echo html_escape($unit['unit_name']); ?></option>
                                                    <?php } else{ ?>
                                                        <option value="<?php echo html_escape($unit['unit_name']) ?>"><?php echo html_escape($unit['unit_name']); ?></option>
                                                        <?php } } ?>
                                                <!-- {unit_list}
                                                    <option value="{unit_name}" <?php echo ($unit_name == 'PC') ? 'selected' : $unit_name; ?>>
                                                        <?php echo $unit_name; ?>
                                                    </option>
                                                    {/unit_list} -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="unit" class="col-sm-4 col-form-label"><?php echo "Category" ?></label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="category_id" name="category_id" tabindex="3">
                                                <option value=""></option>
                                                {category_list}
                                                <option value="{id}">{name_bn}-{name}</option>
                                                {/category_list}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="unit" class="col-sm-4 col-form-label"><?php echo "Brand Name" ?></label>
                                        <div class="col-sm-6">
                                        <select class="form-control" id="brand_id" name="brand_id" tabindex="3">
                                            <option value=""></option>
                                            <?php if ($brand_list) { ?>
                                                {brand_list}
                                                <option value="{id}">{brand_name}</option>
                                                {/brand_list}
                                            <?php } ?>
                                        </select>
                                        </div>
                                    </div>
                                   

                                    <!-- <div class="form-group row">
                                        <label for="barcode" class="col-sm-4 col-form-label"><?php echo "Barcode" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="product_id" id="product_id" type="text" placeholder="Enter Barcode" tabindex="2"> 
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="price" class="col-sm-4 col-form-label"><?php echo "Selling Price" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name ="price" id="price" type="text" placeholder="Enter Price" tabindex="2"> 
                                        </div>
                                    </div>

                                    
                                
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">

                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
              <!-- Add Product Modal -->
              <!-- Add Purchase Modal -->
              <div class="modal fade modal-success" id="purchase_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo "Add Purchase" ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <!-- <div id="purchase_message" class="alert hide"></div> -->
                                <?php echo form_open('Cservice/instant_purchase', array('class' => 'form-vertical', 'id' => 'add_purchase')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                        <label for="product_type" class="col-sm-4 col-form-label"><?php echo "Product Type" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" value="Raw Material" name ="product_type" id="product_type" readonly type="text" placeholder="Raw Material" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name_bn" class="col-sm-4 col-form-label"><?php echo "Purchase Date" ?></label>
                                        <div class="col-sm-6">
                                                        <?php
                                            
                                            $date = date('Y-m-d');
                                            ?>
                                            <input readonly class="datepicker form-control" type="text" size="50" name="purchase_date" id="purchase_date" required value="<?php echo $date; ?>" tabindex="6" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo "Outlet Name" ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                       <select  name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="<?php echo html_escape($outlet_id) ?>" selected><?php echo html_escape($outlet_name); ?></option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="unit" class="col-sm-4 col-form-label"><?php echo "Supplier Name" ?></label>
                                        <div class="col-sm-6">
                                        <select name="supplier_id" id="modal_purchase_supplier_id" class="form-control " required="" tabindex="1">
                                            <!-- <option value=" "><?php echo display('select_one') ?></option> -->
                                            {all_supplier}
                                            <option value="{supplier_id}">{supplier_name}</option>
                                            {/all_supplier}
                                        </select>
                                        </div>
                                        <a href="#" class="client-add-btn btn btn-success open-modal-supplier" data-serial="1" aria-hidden="true" data-toggle="modal" data-target="#supplier_add">
                                                <i class="ti-plus m-r-6"></i>
                                            </a>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name" class="col-sm-4 col-form-label"><?php echo "Product Name" ?></label>
                                        <div class="col-sm-6">
                                        <input class="form-control" value="" name ="product_id" id="modal_purchase_product_id" type="hidden" tabindex="2"> 
                                        <input class="form-control" value="" name ="product_name" id="modal_purchase_product_name" readonly type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="quantity" class="col-sm-4 col-form-label"><?php echo "Quantity" ?></label>
                                        <div class="col-sm-6">
                                        <input class="form-control" onchange="purchase_modal_calculation()" name="modal_purchase_quantity" id="modal_purchase_quantity" type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                   

                                    <div class="form-group row">
                                       <label for="price" class="col-sm-4 col-form-label"><?php echo "Price" ?></label>
                                        <div class="col-sm-6">
                                        <input class="form-control" onchange="purchase_modal_calculation()" name="modal_purchase_price" id="modal_purchase_price" type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="total_amount" class="col-sm-4 col-form-label"><?php echo "Total Amount" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="modal_purchase_total_price" id="modal_purchase_total_price" type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="total_amount" class="col-sm-4 col-form-label"><?php echo "Paid Amount" ?></label>
                                        <div class="col-sm-6">
                                            <input readonly class="form-control" name="modal_purchase_paid_price" id="modal_purchase_paid_price" type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="total_amount" class="col-sm-4 col-form-label"><?php echo "Due Amount" ?></label>
                                        <div class="col-sm-6">
                                            <input readonly class="form-control" name="modal_purchase_due_price" id="modal_purchase_due_price" type="text" tabindex="2"> 
                                        </div>
                                    </div>
                                    <!-- Payment Modal Starts from here -->
                                    <div class="col-sm-12" id="payment_div">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h3><?php echo pos_display('payment'); ?></h3>
                                            <input type="hidden" id="count_modal" value="3">
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <div id="pay_div_modal">
                                            <div class="row row_div">
                                                <div class="col-md-5">
                                                    <label for="payment_type" class="col-sm-5 col-form-label">
                                                        <?php echo pos_display('payment_type'); ?>
                                                        <i class="text-danger">*</i>
                                                    </label>
                                                    <br>
                                                        <select name="paytype[]" class="form-control pay_type" required="" onchange="bank_payment_modal(this.value, 1)" tabindex="3">
                                                            <option value="1"><?php echo pos_display('cash_payment') ?></option>
                                                            <option value="2"><span class="">Cheque Payment</span></option>
                                                            <option value="4"><?php echo pos_display('bank_payment') ?></option>
                                                            <option value="3">Bkash Payment</option>
                                                            <option value="5">Nagad Payment</option>
                                                            <option value="7">Rocket Payment</option>
                                                            <option value="6">Card Payment</option>

                                                        </select>

                                                   

                                                </div>

                                                <div class="col-sm-4" id="modal_bank_div_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-3 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">

                                                            <input type="text" name="bank_id" class="form-control" id="modal_bank_id_1" placeholder="Bank">

                                                        </div>

                                                        <div class="col-sm-1">
                                                            <a href="#" class="client-add-btn btn btn-sm btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-4" id="modal_bank_div_m_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-5 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <select name="bank_id_m[]" class="form-control bankpayment" id="modal_bank_id_m_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bank_list as $bank) { ?>
                                                                    <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="bank_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bank_list as $bank) { ?>
                                                                <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                            <?php } ?>'>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="modal_bkash_div_1">

                                                    <div class="form-group row">
                                                        <label for="bkash" class="col-sm-5 col-form-label">Bkash Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="bkash_id[]" class="form-control bankpayment" id="modal_bkash_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bkash_list as $bkash) { ?>
                                                                    <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                            <input type="hidden" id="bkash_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                                <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                            <?php } ?>'>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="modal_nagad_div_1">
                                                    <div class="form-group row">
                                                        <label for="nagad" class="col-sm-5 col-form-label">Nagad Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="nagad_id[]" class="form-control bankpayment" id="modal_nagad_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($nagad_list as $nagad) { ?>
                                                                    <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="nagad_list" value='<option value="">Select One</option>
                                                            <?php foreach ($nagad_list as $nagad) { ?>
                                                                <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="modal_rocket_div_1">
                                                    <div class="form-group row">
                                                        <label for="rocket" class="col-sm-5 col-form-label">Rocket Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="rocket_id[]" class="form-control bankpayment" id="modal_rocket_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($rocket_list as $rocket) { ?>
                                                                    <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="rocket_list" value='<option value="">Select One</option>
                                                    <?php foreach ($rocket_list as $rocket) { ?>
                                                        <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                                    <?php } ?>'>

                                                                </div>


                                                            </div>
                                                </div>


                                                <div class="col-sm-4" style="display: none" id="modal_card_div_1">
                                                    <div class="form-group row">
                                                        <label for="card" class="col-sm-5 col-form-label">Card Type <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="card_id[]" class="form-control bankpayment" id="modal_card_id_1" onchange="">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($card_list as $card) { ?>
                                                                    <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="card_list" value='<option value="">Select One</option>
                                                            <?php foreach ($card_list as $card) { ?>
                                                                <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="col-sm-4" id="modal_ammnt_1">
                                                    <label for="p_amount" class="col-sm-5 col-form-label"> <?php echo pos_display('amount '); ?> <i class="text-danger">*</i></label>
                                                    <br>
                                                        <input class="form-control p_amount_modal" type="text" name="p_amount[]" onchange="modal_calc_paid()" onkeyup="modal_calc_paid()">
                                                    
                                                </div>
                                                <div class="col-sm-1">
                                                    <a id="add_pt_btn" onclick="add_pay_row_modal(1)" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                   

                                    
                                
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
              <!-- Add Purchase Modal -->
              <!-- Add Supplier Modal -->
              <div class="modal fade modal-success" id="supplier_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo "Add Supplier" ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <div id="supplier_message" class="alert hide"></div>
                                <?php echo form_open('Cservice/instant_supplier', array('class' => 'form-vertical', 'id' => 'add_supplier')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                        <label for="supplier_name" class="col-sm-4 col-form-label"><?php echo "Supplier Name" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" value="" name ="supplier_name" id="supplier_name"  type="text" placeholder="Enter Spplier Name" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="mobile" class="col-sm-4 col-form-label"><?php echo "Mobile Number" ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control" value="" name ="mobile" id="mobile" type="text" placeholder="Enter Supplier Mobile Number" tabindex="2"> 
                                        </div>
                                    </div>
                        
                                      
                                    
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
              <!-- Add Supplier Modal -->
              <select style="display:none;" name="deduction_id" id="deduction_id_hidden" class="form-control deduction_id" tabindex="1">
                <option value=" "><?php echo display('select_one') ?></option>
                {fund_deductions}
                <option value="{id}">{name}</option>
                {/fund_deductions}
            </select>
              <!-- Add Warranty Modal -->
              <div class="modal fade modal-success" id="warranty_add" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                
                                <a href="#" class="close" data-dismiss="modal">&times;</a>
                                <h3 class="modal-title"><?php echo "Add Warranty" ?></h3>
                            </div>
                            
                            <div class="modal-body">
                                <div id="warranty_message" class="alert hide"></div>
                                <?php echo form_open('Cservice/instant_deduction', array('class' => 'form-vertical', 'id' => 'add_warranty')) ?>
                                <div class="panel-body">
                                    <input type ="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group row">
                                        <label for="product_type" class="col-sm-4 col-form-label"><?php echo "Select Deduction / Fund" ?></label>
                                        <div class="col-sm-6">
                                            <select name="deduction_id" id="deduction_id" class="form-control " required="" tabindex="1">
                                                <option value=" "><?php echo display('select_one') ?></option>
                                                {fund_deductions}
                                                <option value="{id}">{name}</option>
                                                {/fund_deductions}
                                            </select>
                                        </div>
                                        <a href="#" class="client-add-btn btn btn-success open-modal-supplier" data-serial="1" aria-hidden="true" data-toggle="modal" data-target="#supplier_add">
                                                <i class="ti-plus m-r-6"></i>
                                        </a>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name_bn" class="col-sm-4 col-form-label"><?php echo "Percentage (%)" ?></label>
                                        <div class="col-sm-6">
                                           <input class="form-control" value="" name ="percentage" id="percentage" readonly type="text" placeholder="" tabindex="2"> 
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name_bn" class="col-sm-4 col-form-label"><?php echo "Amount" ?></label>
                                        <div class="col-sm-6">
                                           <input class="form-control" value="" name ="amount" id="amount" type="text" placeholder="Enter Amount" tabindex="2"> 
                                        </div>
                                    </div>
                                   
                                

                                    
                                
                                </div>
                        
                            </div>

                            <div class="modal-footer">
                                
                                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                                
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <?php echo form_close() ?>
                        </div><!-- /.modal-content -->
                    </div>
                </div>
              <!-- Add Deduction Modal -->
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        
    $('#date').datepicker();
    $('#delivery_date').datepicker();
    $('.warranty_date').datepicker();
});
function cloneOptions(sourceSelect) {
    var optionsHTML = "";
    for (var i = 0; i < sourceSelect.options.length; i++) {
        optionsHTML += `<option value="${sourceSelect.options[i].value}">${sourceSelect.options[i].text}</option>`;
    }
    return optionsHTML;
}
function addRow()
       {
           var serial_no = $('#serial_no').val();
           var table = document.getElementById("deductionTable_"+serial_no);
           var previousRowNumber = table.rows.length;
           var inner_div_row_number = previousRowNumber + 1;

           var Select_id = "deduction_id_"+serial_no+"_"+ inner_div_row_number;
           var Select_class = "deduction_id_"+serial_no;
           var Select_name = "deduction_id_"+serial_no+ "[]";
           var Percentage_id = "percentage_"+serial_no + "_"+ inner_div_row_number;
           var Percentage_class = "percentage_"+serial_no;
           var Percentage_name = "percentage_" + serial_no + "[]";
           var Value_id = "value_"+serial_no + "_"+ inner_div_row_number;
           var Value_class = "value_"+serial_no;
           var Value_name = "value_" + serial_no + "[]";
           var deductionSelect = document.getElementById("deduction_id_hidden");
            var row = table.insertRow(table.rows.length);
            row.classList.add("form-group");
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML = `
                <div class="form-group">
                <div class="input-group">
                        <select class="form-control deduction_id" id="${Select_id}" name="${Select_name}">
                            ${cloneOptions(deductionSelect)}
                        </select>
                
                    </div>
                </div>
            `;
            cell2.innerHTML = `
                <div class="form-group">
                    <input type="text" id="${Percentage_id}" name="${Percentage_name}" class="form-control ${Percentage_class}">
                </div>
            `;
            cell3.innerHTML = `
                <div class="form-group">
                    <input type="text" id="${Value_id}" name="${Value_name}" class="form-control ${Value_class}">
                </div>
            `;
            cell4.innerHTML = '<button id="deleteButton" class="btn btn-danger" type="button" onclick="deleteDeductionRow(this)">Delete</button>';
}

    function deleteDeductionRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
     
    $(document).ready(function() {
        $(document).on('click', '.open-modal-button', function() {
            var rowID = $(this).closest("tr").data("rowid");
            var modal = document.querySelector('.open-modal-button');

       // Get the value of the data-serial attribute
        var serialValue = modal.getAttribute('data-serial');
                console.log("Modal opened from row from table row " + rowID);
                console.log("from modal class " + serialValue);
                $("#serial_no").val(rowID);
            });
    });
    ///////////////////// Deduction Type Change Action/////////////////////////////////////////////////////////////////
    $(document).ready(function() {
       $(document).on('change', '.deduction_id', function() {
            var serial_no = $('#serial_no').val();
            var selling_price = $('#selling_price_' + serial_no).val();
            var table = document.getElementById("deductionTable_"+serial_no);
            var previousRowNumber = table.rows.length;
            var row = $(this).closest('tr');
            var rowIndex = row.index() + 1; // Adding 1 because row index is zero-based
            var selectedValue = $(this).val();
            console.log(selectedValue + " " + rowIndex + " " + selling_price)
            let deduction_id = $(this).val();
            var csrf_test_name = $("[name=csrf_test_name]").val();
            var base_url = $("#base_url").val();
            jQuery.ajax({
                type: "POST",
                dataType: "html",
                url: base_url + "Cservice/getDeduction",
                data: {
                    'deduction_id': deduction_id,
                    'csrf_test_name': csrf_test_name
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    console.log(data[0].id);
                    $("#percentage_"+serial_no + "_" + rowIndex).val(data[0].percentage);
                    $("#value_"+serial_no + "_" + rowIndex).val((data[0].percentage * selling_price) / 100);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });
    });
    ///////////////////// Deduction Modal Close Action/////////////////////////////////////////////////////////////////
    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        if (modal) {
            $(modal).modal('hide');
        }
    }
    ///////////////////////////////////Deduction Modal Open Action///////////////////////////////////////////////////////
    $(document).ready(function() {
        $(document).on('click', '.open-modal-deduction_button', function() {
            var serial_no = $('#serial_no').val();
            var deduction_div = document.getElementById("Deduction_Form_Div_"+serial_no);
                if (deduction_div) {
                // Nothing
                $('#Deduction_Form_Div_'+serial_no).modal('show');
                }
                else{
                    var modal = document.createElement("div");
                    modal.id = 'Deduction_Form_Div_'+ serial_no;
                    modalId = 'Deduction_Form_Div_'+ serial_no;
                    modal.classList.add("modal");
                    modal.classList.add("modal-success");
                    modal.classList.add("fade");
                    modal.innerHTML = `
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                                    
                                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                                        <h3 class="modal-title"><?php echo "Add Deduction / Fund" ?></h3>
                        </div>
                        <div class="modal-body">
                                        <div id="deduction_message_${serial_no}" class="alert">
                                        </div>
                                        <button type="button" class="btn btn-secondaary" onclick="addRow()">Add Row</button>
                        </div>
                        
                        <button type="button" onclick="submitData()" class="btn btn-success" value="">Submit</button>
                        <button type="button" class="btn btn-danger" onclick="closeModal('${modalId}')">Close</button>
                        <br>
                    </div>
                    </div>
                    `;
                     var where_to_add = document.getElementById("form_table");
                     where_to_add.appendChild(modal);
                    //  document.body.appendChild(modal);
                     modal.style.display = "block";
                     var table = document.createElement("table");
                    table.id = "deductionTable_"+serial_no;
                    table.border = "1";
                    const dataContainer = document.getElementById('deduction_message_'+serial_no);
                    dataContainer.appendChild(table);
                    modal.classList.remove('fade');
                   $('#Deduction_Form_Div_'+serial_no).modal('show');

                }
           
                

            // Display the modal
            
                // var serial_no = $('#serial_no').val();
                // var modal = document.getElementById("Deduction_Form_Div"+serial_no);
            // console.log(serial_no);
            //     if (modal) {
            //     }
            //     else{
            //         var table = document.createElement("table");
            //         table.id = "deductionTable_"+serial_no;
            //         table.border = "1";
            //         const dataContainer = document.getElementById('deduction_message_'+serial_no);
            //         dataContainer.appendChild(table);
            // }
            // $('#Deduction_Form_Div_'+serial_no).modal('show');
        });
    });
    ///////////////////////////////////Deduction Modal View Action///////////////////////////////////////////////////////
    $(document).ready(function() { 
        $(document).on('click', '.open-modal-view_deduction', function() {
            var rowID = $(this).closest("tr").data("rowid");
            $("#serial_no").val(rowID);
            var innerDivs = document.querySelectorAll('.d_deduction');
                innerDivs.forEach(function(div) {
                    div.classList.remove('active');
                });
            var specificDiv = document.getElementById('div_' + rowID);
            if (specificDiv) {
                specificDiv.classList.add('active');
            }
        });
    });
      ///////////////////////////Deduction Form Submit Action/////////////////////////////////////////////////
    function submitData() {
        var serial_no = $("#serial_no").val();
        // const field1Value = document.getElementById('deduction_id').value;
        // const field2Value = document.getElementById('percentage').value;
        // const field3Value = document.getElementById('amount').value;

        // const field1 = document.createElement('input');
        // field1.setAttribute('type', 'text');
        // field1.setAttribute('name', 'deduction_id_'+serial_no+'[]');
        // field1.setAttribute('value', field1Value);

        // const field2 = document.createElement('input');
        // field2.setAttribute('type', 'text');
        // field2.setAttribute('name', 'percentage_'+serial_no+'[]');
        // field2.setAttribute('value', field2Value);

        // const field3 = document.createElement('input');
        // field3.setAttribute('type', 'text');
        // field3.setAttribute('name', 'amount_'+serial_no+'[]');
        // field3.setAttribute('class', 'deduction_amount_'+serial_no);
        // field3.setAttribute('value', field3Value);
        // const container = document.createElement('div');
        // container.id = 'div_' + serial_no;
        // container.setAttribute('class', 'd_deduction');
        // // container.style.display = 'none';
        // const dataContainer = document.getElementById('dataContainer');
        // dataContainer.appendChild(container);
        // const innerDiv = document.createElement('div');
        // innerDiv.className = 'modal-container'; // Add the modal-container class
        // innerDiv.id = 'container_' + serial_no;
        // innerDiv.class = 'modal';
        // container.appendChild(innerDiv);
        // innerDiv.appendChild(field1);
        // innerDiv.appendChild(field2);
        // innerDiv.appendChild(field3);
        // document.getElementById('deduction_id').value = '';
        // document.getElementById('percentage').value = '';
        // document.getElementById('amount').value = '';
        let total_deduction_by_row = 0;
        $(".value_"+serial_no).each(function () {
            isNaN(this.value) || 0 == this.value.length || (total_deduction_by_row += parseFloat(this.value))
        });
        $("#deduction_fund_" + serial_no).val(total_deduction_by_row);
        quantity_calculate(serial_no)
        $('#Deduction_Form_Div_'+serial_no).modal('hide');
    }    
</script>
<!-- Invoice Report End -->

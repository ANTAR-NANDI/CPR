<script src="<?php echo base_url() ?>my-assets/js/admin_js/account.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/multiselect/sumoselect.min.css" />
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('accounts') ?></h1>
            <small><?php echo "Payment Voucher" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo "Payment Voucher" ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
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
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                <?php echo display('debit_voucher') ?>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body">

                        <?php echo  form_open_multipart('accounts/create_debit_voucher', 'id="validate"') ?>
                        <div class="form-group row">
                            <div class="col-sm-6">
                               <label for="date" class="col-sm-4 col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input type="text" name="date" id="date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                            </div>
                            <div class="col-sm-6">
                                 <label for="vo_no" class="col-sm-4 col-form-label"><?php echo display('voucher_no') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                <input type="text" name="txtVNo" id="txtVNo" value="<?php if (!empty($voucher_no)) {
                                                                                            foreach ($voucher_no as $v) {
                                                                                                $max_array[] =  substr($v, 3);
                                                                                            }

                                                                                            $max = max($max_array);
                                                                                            $vn = $max + 1;
                                                                                            echo $voucher_n = 'DV-' . $vn;
                                                                                        } else {
                                                                                            echo $voucher_n = 'DV-1';
                                                                                        } ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="payment_type" class="col-sm-4 col-form-label">Pay From <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select name="paytype" class="form-control" required="" onchange="bank_paymet(this.value, 1)" tabindex="3">
                                            <option value="1"><?php echo display('cash_payment') ?></option>
                                            <option value="4"><?php echo display('bank_payment') ?></option>
                                            <option value="3">Bkash Payment</option>
                                            <option value="5">Nagad Payment</option>
                                            <option value="6">Card Payment</option>
                                        </select>
                                    </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="pay_to" class="col-sm-4 col-form-label">Available Balance :</label>
                                <div class="col-sm-8">
                                    <input readonly value ="2000" type="text" name="balance" id="balance" class="form-control" placeholder="Balance">
                                </div>
                            </div>
                        </div>
                        <div id="bank_div_m_1" style="display:none;">

                            <div class="form-group row">

                                <div class="col-sm-6">
                                        <label for="bank" class="col-sm-4 col-form-label"><?php
                                                            echo display('bank'). " A/C";
                                                            ?> <i class="text-danger">*</i></label>
                                            <div class="col-sm-8">
                                                <select name="bank_id_m" class="form-control bankpayment" id="bank_id_m_1">
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

                                <div class="col-sm-6">
                                    <label for="pay_to" class="col-sm-4 col-form-label">Cheque Number</label>
                                    <div class="col-sm-8">
                                        <input value ="" type="text" name="cheque_number" id="cheque_number" class="form-control" placeholder="Enter Cheque Number">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-6">
                                        <label for="bank" class="col-sm-4 col-form-label"><?php
                                                            echo "Cheque Date";
                                                            ?> <i class="text-danger">*</i></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="cheque_date" id="cheque_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="pay_to" class="col-sm-4 col-form-label">Cheque Type</label>
                                    <div class="col-sm-8">
                                        <select name="cheque_type" class="form-control bankpayment" id="cheque_type">
                                            <option value="cash">Cash Cheque</option>
                                            <option value="cross">Cross Cheque</option>
                                        </select>
                                                
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                        

                        <div class="col-sm-6" style="display: none" id="bkash_div_1">

                            <div class="form-group row">
                                <label for="bkash" class="col-sm-4 col-form-label">Bkash Number <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <select name="bkash_id" class="form-control bankpayment" id="bkash_id_1">
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

                        <div class="col-sm-6" style="display: none" id="nagad_div_1">
                            <div class="form-group row">
                                <label for="nagad" class="col-sm-4 col-form-label">Nagad Number <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <select name="nagad_id" class="form-control bankpayment" id="nagad_id_1">
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

                        <div class="col-sm-4" style="display: none" id="card_div_1">
                            <div class="form-group row">
                                <label for="card" class="col-sm-5 col-form-label">Card Type <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select name="card_id" class="form-control bankpayment" id="card_id_1">
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
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="pay_to" class="col-sm-4 col-form-label">Pay To :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="pay_to" id="pay_to" class="form-control" placeholder="Reciever Name">
                                        <br/>
                                    </div>
                                </div>
                                <br>
                                <div class="col-sm-6">
                                   <label for="remarks" class="col-sm-4 col-form-label"><?php echo display('remark')."s" ?></label>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                        
                        
                        <div class="table-responsive" >
                            <table class="table table-bordered table-hover" id="debtAccVoucher">
                                <thead style="padding:5px;border:1px solid black">
                                    <tr>
                                        <th class="text-center"><?php echo "Account Group" ?><i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('account_name') ?><i class="text-danger">*</i></th>
                                        
                                        <th class="text-center"><?php echo display('amount') ?><i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="debitvoucher">

                                    <tr>
                                        <td class="" width="400">
                                            <select name="parent_head[]" id="parent_head_1" onchange="get_childhead(this.value,1)" class="form-control" required="">
                                            <option value="">Select One</option>
                                                <?php foreach ($transaction_parent as $parent) { ?>
                                                    <option value="<?php echo $parent->HeadName; ?>"><?php echo $parent->HeadName; ?></option>
                                                <?php } ?>
                                            </select>

                                        </td>
                                        <td class="" width="400">
                                            <select name="child_head[]" id="child_head_1" class="form-control" onchange="load_dbtvouchercode(this.value,1)" required="">
                                                
                                            </select>

                                        </td>
                                        
                                        <td><input type="number" name="amount[]" value="" class="form-control total_price text-right" id="txtAmount_1" onkeyup="dbtvouchercalculation_new(1)" required="">
                                        </td>
                                        <td>
                                            <button class="btn btn-danger red text-right" type="button" value="<?php echo display('delete') ?>" onclick="deleteRowdbtvoucher(this)"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                        <td> <a id="add_more" class="btn btn-info" name="add_more" onClick="addaccountdbt_new('debitvoucher')"><i class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>

                                        </td>
                                        <td colspan="1" class="text-right"><label for="reason" class="  col-form-label"><?php echo display('total') ?></label>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="form-control text-right " name="grand_total" value="" readonly="readonly" />
                                        </td>
                                        
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="form-group row">

                            <div class="col-sm-12 text-right">

                                <input type="submit" id="add_receive" class="btn btn-success btn-large" name="save" value="<?php echo display('save') ?>" tabindex="9" />

                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>

                    <input type="hidden" id="headoption" value="<option value=''>Select One</option><?php foreach ($transaction_parent as $value) { ?><option value='<?php echo $value->HeadName; ?>'><?php echo $value->HeadName; ?></option><?php } ?>" name="">
                </div>
            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url(); ?>assets/plugins/multiselect/jquery.sumoselect.min.js"></script>
<script type="text/javascript">

    $(document).ready(() => {
        
        $("#txtRemarks").on("focus", () => {
            if ($("#txtRemarks").val() === "Debit Voucher")
                $("#txtRemarks").val('');
        });
    //     $('#parent_head').SumoSelect({
    //     selectAll: true,
    //     search: true
    // });
    })
    
</script>
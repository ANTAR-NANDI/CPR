<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Technician Payment</h1>
            <small>Technician Payment</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Technician Payment</a></li>
                <li class="active">Technician Payment</li>
                <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
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
               
                    <?php echo form_open_multipart('Cservice/technician_due_update', array('class' => 'form-vertical', 'id' => '', 'name' => '',))  ?>

                    <div class="panel-body">

                        <div class="">
                            <table class="datatable table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl_no') ?></th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Number</th>
                                        <th>Commission</th>
                                        <th>Previous Paid Amount</th>
                                        <th>Pay Amount</th>
                                        <th>Due</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($technician_payment)) ?>
                                    <?php $sl = 1; ?>
                                    <input hidden type="text" name="total_pay_amount" id="total_pay_amount" value="">
                                    <input hidden type="text" name="paid_amount" id="paid_amount" value="">
                                    <input hidden type="text" name="technician_id" value="<?php echo $technician_id ?>">
                                    <?php foreach ($technician_payment as $payment) { ?>
                                            <tr>
                                                <input hidden type="text" name="due_amount_hidden" id="due_amount_hidden_<?php echo $sl; ?>" value="<?php echo $payment['due_amount'] ?>">
                                                <input hidden  type="text" name="service_invoice_id[]" id="service_invoice_id" value="<?php echo $payment['service_invoice_id'] ?>">
                                                <input hidden type="text" name="previous_paid_amount[]" value="<?php echo $payment['paid_amount'] ?>">
                                                <input hidden type="text" name="commission_amount[]" value="<?php echo $payment['commission_amount'] ?>">
                                                <td><?php echo $sl++; ?></td>
                                                <td><?php echo $payment['invoice_date'] ?></td>
                                                <td><?php echo html_escape($payment['service_invoice_id']); ?></td>
                                                <td id="commission_amount_<?php echo $sl; ?>"><?php echo html_escape($payment['commission_amount']); ?></td>
                                                <td id="paid_amount_<?php echo $sl; ?>"><?php echo $payment['paid_amount']; ?></td>
                                                <td><input onkeyup="quantity_calculate(<?php echo $sl ?>);" onchange="quantity_calculate(<?php echo $sl ?>);" id="pay_amount_<?php echo $sl; ?>" style="width: 80px" type="number"   class="form-control pay_amount" name="pay_amount[]" value="" /></td>
                                                <td id="due_amount_<?php echo $sl; ?>"><?php echo html_escape($payment['due_amount']); ?></td>
                                               
                                            </tr>
                                            
                                           
                                    <?php
                                                                 
                                } ?>
                                </tbody>
                               
                            </table>

                        </div>
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
                                                        <select name="pay_type[]" class="form-control pay_type" required="" onchange="bank_paymet(this.value, 1)" tabindex="3">
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
                                                <!-- <div class="col-sm-1">
                                                    <a id="add_pt_btn" onclick="add_pay_row(1)" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                                </div> -->

                                            </div>
                                        </div>
                                    </div>
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
                            </div>
                            </div>
                            <input type="submit" id="add_invoice" class="btn btn-success" name="add-invoice" value="<?php echo display('submit') ?>" tabindex="15"/>
                    
                        </div>
                    <?php echo form_close() ?>
                    </form>



                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    "use strict";
    function quantity_calculate(item) {
        // alert("test")
        let commission_amount = 0;
        let previous_paid = 0;
        let pay_amount = 0;
        let due_amount= 0; 
        let due_amount_hidden = 0;
        commission_amount = parseFloat($('#commission_amount_'+item).html()); 
        previous_paid = parseFloat($('#paid_amount_'+item).html()); 
        pay_amount = $('#pay_amount_'+item).val() ? parseFloat($('#pay_amount_'+item).val()) : 0.00; 
        due_amount_hidden = parseFloat($('#due_amount_hidden_'+(item - 1)).val())
        console.log(due_amount_hidden + " " + pay_amount)
        if(pay_amount > due_amount_hidden)
        {
            $('#pay_amount_'+item).val(commission_amount - previous_paid)
            $('#due_amount_'+item).html(0);
            $('#due_payment_'+item).val(0);
        }
        else{
            $('#due_amount_'+item).html(commission_amount - (pay_amount + previous_paid ));
            $('#due_payment_'+item).val(commission_amount - (pay_amount + previous_paid ));

        }
        let total_pay_amount = 0;

        $(".pay_amount").each(function () {
            isNaN(this.value) || 0 == this.value.length || (total_pay_amount += parseFloat(this.value))
        });

        $("#total_pay_amount").val((total_pay_amount).toFixed(2, 2));
            
    }
    'use strict';
function delete_row(e) {
    // alert(e)
    e.closest('.row_div').remove();
}
    "use strict"
function calc_paid() {
    let paid_amount = 0;

    $(".p_amount").each(function () {
        isNaN(this.value) || 0 == this.value.length || (paid_amount += parseFloat(this.value))
    });

    $("#paid_amount").val((paid_amount).toFixed(2, 2));
    quantity_calculate()
}
    'use strict';
    function add_pay_row(sl) {
        var count = $("#count");
        sl = count.val();
        sl += 1;
        var bkash_list = $("#bkash_list").val();
        var nagad_list = $("#nagad_list").val();
        var rocket_list = $("#rocket_list").val();
        var bank_list = $("#bank_list").val();
        var card_list = $("#card_list").val();
        var pay_div = $("#pay_div");
        pay_div.append(
    
            '<div class="row_div">'
            + '<div class="row">'
                + ' <div class="col-sm-2">'
                + ' <p style="margin-left: 12px; padding: 0;"> <b>Payment Type <i class="text-danger">*</i></b></p>'
                + '</div>'
            + ' <div class="form-group col-md-2">'
                    + '  <div class="selectWrapper">'
                    + '<select name="paytype[]" class="form-control selectBox" required="" onchange="bank_paymet(this.value, ' + sl + ')" tabindex="3">'
                    + '<option value="1">Cash</option>'
                    + '<option value="2">Cheque</option>'
                    + '<option value="4">Bank</option>'
                    + ' <option value="3">Bkash</option>'
                    + ' <option value="5">Nagad</option>'
                    + ' <option value="7">Rocket</option>'
                    + ' <option value="6">Card</option>'
                    + '</select>'
                    + '</div>'
            + '</div>'
            + ' <div class="col-sm-2">'
            +    ' <p style="margin-left: 12px; padding: 0;"> <b>Amount<i class="text-danger">*</i></b></p>'
             + '</div>'
            + ' <div style="margin-top: 8px; padding: 0;" class="col-sm-2" id="ammnt_' + sl + '">'
                 + '<input class="form-control p_amount" placeholder="0.00" type="text" name="p_amount[]" onchange="calc_paid()" onkeyup="calc_paid()">'
            + '</div>'
    
            + '<div class="col-sm-1">'
            + '<a id="delete_btn" onclick="delete_row(this)" class=" client-add-btn btn btn btn btn-danger"><i class="fa fa-trash"></i></a>'
            + '</div>'
    
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="bank_div_' + sl + '"  style="display:none;">'
    
            + ' <div class="col-sm-12">'
    
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + ' <div class="col-sm-3">'
            + ' <input type="text" name="bank_id" class="form-control" id="bank_id_' + sl + '" placeholder="Bank" required>'
            + ' </div>'
    
            + '<div class="col-sm-1">'
            + ' <a href="#" class="client-add-btn btn btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>'
            + '</div>'
    
            + ' </div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '<div class="row margin-top10  m-0"  id="bank_div_m_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bank_id_m[]" required class="form-control bankpayment" id="bank_id_m_' + sl + '">'
            + bank_list
            + '</select>'
            + '</div>'
    
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
    
            + '<div class="row margin-top10  m-0" id="bkash_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bkash Number<i class="text-danger">*</i></b></p>'

            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bkash_id[]" required class="form-control bankpayment" id="bkash_id_' + sl + '">'
            + bkash_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
    
    
            + '<div class="row margin-top10  m-0"  id="nagad_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Nagad Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="nagad_id[]" class="form-control bankpayment" id="nagad_id_' + sl + '">'
            + nagad_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
    
            + '</div>'
            + ' </div>'
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="rocket_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Rocket Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="rocket_id[]" class="form-control bankpayment" id="rocket_id_' + sl + '">'
            + rocket_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="row margin-top10  m-0" id="card_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Card Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="card_id[]" class="form-control bankpayment" id="card_id_' + sl + '">'
            + card_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '</div>'
    
            + '</div>'
    
    
    
    
            + '</div>'
    
            + '</div>'
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        );
        count.val(sl + 1);
    }
    "use strict";
    function bank_paymet(val, sl) {

        if (val == 2 || 3 || 4 || 5 || 6 || 7) {

            if (val == 2) {
                var style = 'block';
                document.getElementById('bank_id_' + sl).setAttribute("required", true);
                document.getElementById('ammnt_' + sl).style.display = 'none';
            } else {
                var style = 'none';
                document.getElementById('bank_id_' + sl).removeAttribute("required");
                document.getElementById('ammnt_' + sl).style.display = 'block';
            }

            document.getElementById('bank_div_' + sl).style.display = style;

            if (val == 3) {
                var style = 'block';
                document.getElementById('bkash_id_' + sl).setAttribute("required", true);

            } else {
                var style = 'none';
                document.getElementById('bkash_id_' + sl).removeAttribute("required");

            }

            document.getElementById('bkash_div_' + sl).style.display = style;


            if (val == 4) {
                var style = 'block';
                document.getElementById('bank_id_m_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('bank_id_m_' + sl).removeAttribute("required");
            }

            document.getElementById('bank_div_m_' + sl).style.display = style;

            if (val == 5) {
                var style = 'block';
                document.getElementById('nagad_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('nagad_id_' + sl).removeAttribute("required");
            }

            document.getElementById('nagad_div_' + sl).style.display = style;

            if (val == 7) {
                var style = 'block';
                document.getElementById('rocket_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('rocket_id_' + sl).removeAttribute("required");
            }

            document.getElementById('rocket_div_' + sl).style.display = style;

            if (val == 6) {
                var style = 'block';
                document.getElementById('card_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('card_id_' + sl).removeAttribute("required");
            }

            document.getElementById('card_div_' + sl).style.display = style;
        }
    }
</script>
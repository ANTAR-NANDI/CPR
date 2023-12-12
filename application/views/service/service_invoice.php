<!-- Manage service Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('manage_service') ?></h1>
            <small><?php echo display('manage_your_service') ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('service') ?></a></li>
                <li class="active"><?php echo display('manage_service') ?></li>
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

      
        <!-- Manage service -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                         
                        </div>
                    </div>
                    <div class="panel-body">
                       <div class="row">
                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label for="product_sku" class="col-form-label">Outlet: </label>
                                <select name="outlet_id" class="form-control" id="outlet_id" required="" tabindex="3">
                                <option value="">Select</option>
                                    <?php foreach ($cw_list as $cw) { ?>
                                        <option  value="<?php echo html_escape($cw['warehouse_id']) ?>"><?php echo html_escape($cw['central_warehouse']); ?></option>
                                    <?php } ?>
                                    <?php foreach ($outlet_list as $outlet) { ?>
                                        <option value="<?php echo html_escape($outlet['outlet_id']) ?>"><?php echo html_escape($outlet['outlet_name']); ?></option>
                                    <?php } ?>

                                    <option value="All">Consolidated</option>


                                </select>

                            </div>
                            <div class="col-sm-6">
                                <label for="cat_list" class="col-form-label">Technician : </label>
                                <select name="technician_id" id="technician_id" required class="form-control">
                                <option value="">Select</option>
                                    <?php
                                    foreach ($technician_list as $technician) {
                                    ?>
                                        <option value="<?php echo $technician['user_id']; ?>"><?php echo $technician['first_name'] . " " . $technician['last_name']; ?></option>
                                    <?php
                                    
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>">
                                <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label for="payment_status" class="col-form-label">Payment Type: </label>
                                <select name="payment_status" class="form-control" id="payment_status" required="" tabindex="3">
                                <option value="">Select</option>
                                    <option value="paid">Paid</option>
                                    <option value="due">Due</option>
                                </select>

                            </div>
                            <div class="col-sm-6">
                                <label for="delivery_status" class="col-form-label">Delivery Status : </label>
                                <select name="delivery_status" class="form-control" id="delivery_status" required="" tabindex="3">
                                <option value="">Select</option>
                                    <option value="Placed">Placed</option>
                                    <option value="Ongoing">On Going</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Delivered">Delivered</option>
                                </select>

                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table id="service_invoice_list" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sl') ?></th>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>Customer Name</th>
                                        <th>Outlet Name</th>
                                        <th>Sales Person</th>
                                        <th>Technician Name</th>
                                        <th>Technician Percentage</th>
                                        <th style="width: 150px;">Deductions</th>
                                        <th>Total Amount</th>
                                        <th>Received Amount</th>
                                        <th>Due Amount</th>
                                        <th>Technician Amount</th>
                                        <th>Delivery Date</th>
                                        <th>Payment Status</th>
                                        <th>Status </th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                         
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <th colspan="9" class="text-right"><?php echo display('total') ?> :</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>

                                    </tfoot>
                            </table>
                           
                        </div>
                        <input type="hidden" name="" class="" id="total_service_invoice" value="<?php echo html_escape($total_service_invoice); ?>">

                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
</div>
<div class="modal fade modal-success updateModal" id="updateProjectModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <a href="#" class="close" data-dismiss="modal">&times;</a>
                <h3 class="modal-title">পেমেন্ট</h3>
            </div>

            <div class="modal-body">
                <div id="customeMessage" class="alert hide"></div>
                <form method="post" id="ProjectEditForm" action="<?php echo base_url('Cservice/due_payment/') ?>">
                    <div class="panel-body">
                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="service_invoice_id" id="service_invoice_id" value="">
                        <div class="form-group row">


                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        মোট টাকা </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="total_amount" name="total_amount" value="" placeholder="0.00" readonly>
                                        <input type="hidden" class="form-control" id="totalAmount" name="totalAamount" value="" placeholder="0.00" readonly>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        পূর্ববর্তী প্রাপ্তি </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="paid_amount" name="paid_amount" placeholder="0.00" value="" readonly>
                                        <input type="hidden" class="form-control" id="paidAmount" name="paidAmount" placeholder="0.00" value="" readonly>


                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        বকেয়া </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="due_amount" name="due_amount" value="" placeholder="0.00" readonly>
                                        <input type="hidden" class="form-control" id="dueAmount" name="dueAmount" value="" placeholder="0.00" readonly>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        প্রাপ্তি <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="pay_amount" name="pay_amount" placeholder="0.00" onkeyup="calculation_due()" onkeypress="calculation_due()" onchange="calculation_due()">

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        নোট </label>
                                    <div class="col-sm-6">
                                        <textarea name="notes" class="form-control" placeholder="..."></textarea>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        পেমেন্ট পদ্ধতি <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <select name="paytype" id="paytype" class="form-control" required="" onchange="bank_paymet(this.value)">
                                            <option value="1">ক্যাশ</option>
                                            <option value="4">ব্যাংক</option>
                                            <option value="3">বিকাশ</option>
                                            <option value="5">নগদ</option>
                                            <option value="7">রকেট</option>

                                        </select>


                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="bank_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">ব্যাংক</label>
                                    <div class="col-sm-8">
                                        <select name="bank_id" class="form-control bankpayment" id="bank_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($bank_list as $bank) { ?>
                                                <option value="<?php echo $bank['bank_id'] ?>"><?php echo $bank['bank_name']; ?> (<?php echo $bank['ac_number']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="bkash_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">বিকাশ</label>
                                    <div class="col-sm-8">
                                        <select name="bkash_id" class="form-control bankpayment" id="bkash_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                <option value="<?php echo $bkash['bkash_id'] ?>"><?php echo $bkash['bkash_no']; ?> (<?php echo $bkash['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="nagad_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">নগদ</label>
                                    <div class="col-sm-8">
                                        <select name="nagad_id" class="form-control bankpayment" id="nagad_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($nagad_list as $nagad) { ?>
                                                <option value="<?php echo $nagad['nagad_id'] ?>"><?php echo $nagad['nagad_no']; ?> (<?php echo $nagad['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="rocket_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">রকেট</label>
                                    <div class="col-sm-8">
                                        <select name="rocket_id" class="form-control bankpayment" id="rocket_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($rocket_list as $rocket) { ?>
                                                <option value="<?php echo $rocket['rocket_id'] ?>"><?php echo $rocket['rocket_no']; ?> (<?php echo $rocket['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>




                        </div>





                    </div>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                <button type="submit" id="ProjectUpdateConfirmBtn" class="btn btn-success">Update</button>
            </div>
            <!--                    <div class="modal-footer">-->
            <!---->
            <!--                        <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>-->
            <!---->
            <!--                        <input type="submit" id="ProjectUpdateConfirmBtn" class="btn btn-success" value="Submit">-->
            <!--                    </div>-->
            <?php echo form_close() ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Modal -->
<div id="add_status" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">

        <a href="#" class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title">Change Status</h3>
        </div>
      <div class="modal-body">
        <form id="form_add_status" name="form_add_status" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                    <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="entity_id" id="entity_id" value="">
                        <label class="control-label col-md-4"><?php echo "Status" ?><span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select required name="order_status" id="order_status" class="form-control form-filter input-sm">
                                                        
                            </select>                                               
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-12 text-center">
                         <button type="submit" class="btn btn-sm  btn-info filter-submit margin-bottom" name="submit_page" id="submit_page" value="Save"><span>Save</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<script type="text/javascript">
$(document).ready(function() {
        $('#ProjectEditForm').one('submit', function() {
            $("#ProjectUpdateConfirmBtn").prop('disabled', true);
        });
    });

    $('#form_add_status').submit(function(){
        var base_url = $("#base_url").val();
            $.ajax({
            type: "POST",
            dataType : "html",
            url: base_url + "Cservice/UpdateStatus",
            data: $('#form_add_status').serialize(),
            cache: false, 
            beforeSend: function(){
                $('#quotes-main-loader').show();
            },   
            success: function(html) {
                $('#quotes-main-loader').hide();
                $('#add_status').modal('hide');
                location.reload();
            }
            });
            return false;
});
    //Update Status
    function updateStatus(id,status){
        $('#entity_id').val(id);
        if(status == 'Placed'){
            $('#order_status').empty().append(
                '<option value="">Select</option><option value="Delivered">Delivered</option><option value="Ongoing">On Going</option>'
            );
        }
        if(status == 'Ongoing'){
            $('#order_status').empty().append(
                '<option value="">Select</option><option value="Delivered">Delivered</option>'
            );
        }
        $('#add_status').modal('show');
    }
    function payment_modal(id, total_amount, paid_amount, due_amount) {

        // alert(due_amount)
        //  return
        $('#updateProjectModal').modal('show');
        $('#service_invoice_id').val(id)
        $('#total_amount').val(total_amount.toFixed(2, 2))
        $('#totalAmount').val(total_amount.toFixed(2, 2))
        $('#paidAmount').val(paid_amount.toFixed(2, 2))
        $('#paid_amount').val(paid_amount.toFixed(2, 2))
        $('#due_amount').val(due_amount.toFixed(2, 2))
        $('#dueAmount').val(due_amount.toFixed(2, 2))

    }

    function calculation_due() {
        var p = 0,
            d = 0;

        var pay_amount = $('#pay_amount').val() ? parseFloat($('#pay_amount').val()): 0.00;
        var total_amount = parseFloat($('#totalAmount').val());
        var due_amount = parseFloat($('#dueAmount').val());
        var paid_amount = parseFloat($('#paidAmount').val());


        p = paid_amount;
        d = total_amount - p

        $('#paid_amount').val(p.toFixed(2, 2))
        $('#due_amount').val(due_amount.toFixed(2, 2) - pay_amount.toFixed(2, 2));

        if (due_amount < pay_amount) {
            toastr.error("You can't receive greater than customer due amount!")
            $('#pay_amount').val('')
            $('#due_amount').val(due_amount.toFixed(2, 2))
            $('#paid_amount').val(paid_amount.toFixed(2, 2))
        }

    }

    "use strict";

    function bank_paymet(val) {
        if (val == 4) {
            var style = 'block';
            document.getElementById('bank_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('bank_id').removeAttribute("required");
        }

        document.getElementById('bank_div').style.display = style;
        if (val == 3) {
            var style = 'block';
            document.getElementById('bkash_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('bkash_id').removeAttribute("required");
        }

        document.getElementById('bkash_div').style.display = style;
        if (val == 5) {
            var style = 'block';
            document.getElementById('nagad_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('nagad_id').removeAttribute("required");
        }

        document.getElementById('nagad_div').style.display = style;

        if (val == 7) {
            var style = 'block';
            document.getElementById('rocket_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('rocket_id').removeAttribute("required");
        }

        document.getElementById('rocket_div').style.display = style;
    }


    $(document).ready(function() {
        var paytype = $("#editpayment_type").val();
        if (paytype == 2) {
            $("#bank_div").css("display", "block");
        } else {
            $("#bank_div").css("display", "none");
        }

        if (paytype == 3) {
            $("#bkash_div").css("display", "block");
        } else {
            $("#bkash_div").css("display", "none");
        }

        if (paytype == 4) {
            $("#nagad_div").css("display", "block");
        } else {
            $("#nagad_div").css("display", "none");
        }

        if (paytype == 7) {
            $("#rocket_div").css("display", "block");
        } else {
            $("#rocket_div").css("display", "none");
        }

        $(".bankpayment").css("width", "100%");
    });
</script>
<!-- Manage service End -->





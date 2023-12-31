<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_customer') ?></h1>
            <small><?php echo display('add_new_customer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('customer') ?></a></li>
                <li class="active"><?php echo display('add_customer') ?></li>
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

        <div class="row">
            <div class="col-sm-12">

                <?php if ($this->permission1->method('manage_customer', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer/manage_customer') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_customer') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('credit_customer', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer/credit_customer') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('credit_customer') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('paid_customer', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer/paid_customer') ?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('paid_customer') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('add_customer', 'create')->access()) { ?>
                    <button type="button" class="btn btn-info m-b-5 m-r-2" data-toggle="modal" data-target="#Customer_modal"><?php echo display('customer_csv_upload') ?></button>
                <?php } ?>


            </div>
        </div>

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_customer') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Ccustomer/insert_customer', array('class' => 'form-vertical', 'id' => 'insert_customer')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">


                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?>(In Bangla) <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="customer_name_bn" id="customer_name_bn" type="text" placeholder="<?php echo display('customer_name') ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card" class="col-sm-4 col-form-label">Membership Type</label>
                                <div class="col-sm-8">

                                    <select name="membership_id" class="form-control" tabindex="3">
                                    <option value="">Select One</option>
                                        <?php foreach ($card_list as $card) { ?>
                                            <option value="<?php echo html_escape($card['id']) ?>"><?php echo $card['name'] ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card_number" class="col-sm-4 col-form-label"><?php echo "Card Number" ?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="card_number" id="card_number" type="text" placeholder="<?php echo "Card Number" ?>" tabindex="1">
                                </div>
                            </div>



                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label">Shop Name </label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="shop_name" id="shop_name" type="text" placeholder="Shop Name" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label"><?php echo display('customer_email') ?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="email" id="email" type="email" placeholder="<?php echo display('customer_email') ?>" tabindex="2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emailaddress" class="col-sm-4 col-form-label"><?php echo display('email') . ' ' . display('address'); ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="emailaddress" id="emailaddress" type="email" placeholder="<?php echo display('email') . ' ' . display('address') ?>">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('customer_mobile') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="mobile" id="mobile" type="number" placeholder="<?php echo display('customer_mobile') ?>" min="0" tabindex="3" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 col-form-label"><?php echo display('phone') ?> </label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="phone" id="phone" type="number" placeholder="<?php echo display('phone') ?>" min="0" tabindex="2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact_person" class="col-sm-4 col-form-label">Contact Person </label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="contact_person" id="contact_person" type="text" placeholder="Contact Person">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact" class="col-sm-4 col-form-label">Contact Mobile</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="contact" id="contact" type="number" placeholder="Contact Mobile">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fax" class="col-sm-4 col-form-label"><?php echo display('fax'); ?> </label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="fax" id="fax" type="text" placeholder="<?php echo display('fax') ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-sm-4 col-form-label">Customer Type</label>
                                <div class="col-sm-8">

                                    <select name="cus_type" class="form-control" tabindex="3">
                                        <option value="1">WholeSale Customer</option>
                                        <option value="2" selected>Retail Customer</option>

                                    </select>

                                </div>
                            </div>

                            <!--                         <div class="form-group row">-->
                            <!--                             <label for="discount_customer" class="col-sm-4 col-form-label">Discount</label>-->
                            <!--                             <div class="col-sm-8">-->
                            <!--                                 <input class="form-control" name="discount_customer" id="discount_customer" type="number" min="0" placeholder="Discount" tabindex="5">-->
                            <!--                             </div>-->
                            <!--                         </div>-->
                        </div>
                        <div class="col-sm-6">
                            <?php if(!($outlet_user)){ ?>
                                <div class="form-group row">
                                    <label for="outlet_id" class="col-sm-4 col-form-label">Outlet Name<i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select name="outlet_id" class="form-control" required="" tabindex="3">
                                            <?php foreach ($cw_list as $cw) { ?>
                                                <option value="<?php echo html_escape($cw['warehouse_id']) ?>" <?php echo html_escape($cw['warehouse_id']) == $outlet_id ? 'disabled' : '' ?>><?php echo html_escape($cw['central_warehouse']); ?></option>
                                            <?php } ?>
                                            <?php foreach ($outlet_list_to as $outlet) { ?>
                                                <option value="<?php echo html_escape($outlet['outlet_id']) ?>" <?php echo html_escape($outlet['outlet_id']) == $outlet_id ? 'disabled' : '' ?>><?php echo html_escape($outlet['outlet_name']); ?></option>
                                            <?php } ?>


                                        </select>

                                    </div>

                                </div>
                                <?php } ?>

                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="customer_name" id="customer_name" type="text" placeholder="<?php echo display('customer_name') ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-sm-4 col-form-label"><?php echo display('city'); ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="city" id="city" type="text" placeholder="<?php echo display('city') ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="state" class="col-sm-4 col-form-label">Police Station <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="state" id="state" type="text" placeholder="Police Station">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="zip" class="col-sm-4 col-form-label"><?php echo display('zip'); ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="zip" id="zip" type="text" placeholder="<?php echo display('zip') ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-sm-4 col-form-label"><?php echo display('country') ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="country" id="country" type="text" placeholder="<?php echo display('country') ?>">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="address " class="col-sm-4 col-form-label"><?php echo display('customer_address') ?></label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="address" id="address" rows="2" placeholder="<?php echo display('customer_address') ?>"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address2 " class="col-sm-4 col-form-label">District</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="address2" id="address2" rows="2" placeholder="District"></textarea>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label for="previous_balance" class="col-sm-4 col-form-label"><?php echo display('previous_balance') ?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="previous_balance" id="previous_balance" type="text" min="0" placeholder="<?php echo display('previous_balance') ?>" tabindex="5">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-sm-4 col-form-label">Website</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="website" id="website" type="text" placeholder="Website Link" tabindex="5">
                                </div>
                            </div>


                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" id="add-customer" class="btn btn-primary btn-large" name="add-customer" value="<?php echo display('save') ?>" tabindex="7" />
                                <input type="submit" value="<?php echo display('save_and_add_another') ?>" name="add-customer-another" class="btn btn-large btn-success" id="add-customer-another" tabindex="6">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
        <div id="Customer_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo display('customer_csv_upload'); ?></h4>
                    </div>
                    <div class="modal-body">

                        <div class="panel">
                            <div class="panel-heading">

                                <div><a href="<?php echo base_url('assets/data/csv/customer_csv_sample.csv') ?>" class="btn btn-primary pull-right"><i class="fa fa-download"></i><?php echo display('download_sample_file') ?> </a> </div>

                            </div>

                            <div class="panel-body">

                                <?php echo form_open_multipart('Ccustomer/uploadCsv_Customer', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_customer')) ?>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label for="upload_csv_file" class="col-sm-4 col-form-label"><?php echo display('upload_csv_file') ?> <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="upload_csv_file" type="file" id="upload_csv_file" placeholder="<?php echo display('upload_csv_file') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('submit') ?>" />
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>



                    </div>

                </div>

            </div>
        </div>
    </section>
</div>
<!-- Add new customer end -->

<script type="text/javascript">
    $("").on("click", function() {

        var name = $('#customer_name').val();
        var email = $('#email').val();
        var country = $('#country').val();
        var city = $('#city').val();
        var postal_code = $('#zip').val();
        var phone = $('#mobile').val();
        var address = $('#address').val();




        var form = new FormData();


        form.append("name", name);
        form.append("email", email);
        form.append("country", country)
        form.append("city", city);
        form.append("postal_code", postal_code);
        form.append("phone", phone);
        form.append("address", address);


        // form.append("logo", fileInput.files[0], logo);

        $.ajax({
            url: '<?= api_url() ?>' + "customers/store",
            method: 'POST',
            // dataType : 'json',
            data: form,
            processData: false,
            contentType: false,
            success: function(d) {
                toastr.success('Customer Added')
                // location.reload();


            },
            error: function(xhr) {


                alert('failed!');
            }
        });





    });
</script>
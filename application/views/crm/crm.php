<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('customer') ?></h1>
            <small><?php echo display('manage_customer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('customer') ?></a></li>
                <li class="active"><?php echo display('manage_customer') ?></li>
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
            <div class="col-sm-6">

                <?php if ($this->permission1->method('add_customer', 'create')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_customer') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('credit_customer', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer/credit_customer') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('credit_customer') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('paid_customer', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Ccustomer/paid_customer') ?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('paid_customer') ?> </a>
                <?php } ?>
                </div>
                <div class="col-md-3" style="margin-bottom: 15px;">
                    <label for="product_sku" class="col-form-label">Outlet: </label>
                    <select name="outlet_id" class="form-control" id="outlet_id" required="" tabindex="3">
                        <?php foreach ($cw_list as $cw) { ?>
                            <option value="<?php echo html_escape($cw['warehouse_id']) ?>"><?php echo html_escape($cw['central_warehouse']); ?></option>
                        <?php } ?>
                        <?php foreach ($outlet_list as $outlet) { ?>
                            <option value="<?php echo html_escape($outlet['outlet_id']) ?>"><?php echo html_escape($outlet['outlet_name']); ?></option>
                        <?php } ?>

                        <option value="All">Consolidated</option>
                    </select>

                </div>

                <!--                <a href="--><?php //echo base_url('Ccustomer/insert_customer_ecom') 
                                                ?><!--" class="sync btn btn-danger m-b-5 m-r-2 "><i class="fa fa-refresh"> </i>  Sync Customer</a>-->

            
        </div>





        <!-- Manage Product report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_customer') ?></h4>


                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" style="overflow-x: auto !important;">
                            <table class="table table-striped table-bordered" cellspacing="0" id="customerLIst" width="100%" style="overflow-x: auto !important;">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th>ID</th>
                                        <!-- <th>Chamak Card</th> -->
                                        <th><?php echo display('customer_name') ?>(In Bangla)</th>
                                        <th><?php echo display('customer_name') ?>(In English)</th>
                                        <th>Shop Name</th>
                                        <th>Address</th>
                                        <th>District</th>
                                        <th><?php echo display('mobile') ?></th>
                                        <!--                                        <th>--><?php //echo display('phone');
                                                                                            ?>
                                        <!--</th>-->
                                        <th><?php echo display('email'); ?></th>
                                        <th><?php echo display('balance') ?></th>
                                        <th><?php echo display('action') ?> </th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-right"><?php echo display('total') ?>:</th>
                                        <th id="stockqty"></th>
                                        <th></th>
                                    </tr>

                                </tfoot>
                            </table>

                        </div>
                        <input type="hidden" name="" class="" id="total_customer" value="<?php echo html_escape($total_customer); ?>">
                        <input type="hidden" id="currency" value="{currency}" name="">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
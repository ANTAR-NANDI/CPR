<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Purchase Report" ?></h1>
            <small><?= $heading_text ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Purchase Report" ?></a></li>
                <li class="active"><?= $heading_text ?></li>
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
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title text-right">

                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="" id="pr_status" value="<?= $pr_status ?>">
                        

                        <div class="row">

                            <div class="col-sm-6">
                                <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="" autocomplete="off">
                            </div>
                            <div class="col-sm-6" style="margin-bottom: 10px;">
                                <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="" autocomplete="off">
                            </div>
                            <?php 
                            $this->load->model('Warehouse');
                            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
                            if($outlet_id == "HK7TGDT69VFMXB7")
                            {
                            ?>

                           <div class="col-md-6" style="margin-bottom: 10px;">
                                <label for="product_sku" class="col-form-label">Outlet: </label>
                                <select name="outlet_id" class="form-control" id="outlet_id" required=""  tabindex="3">
                                <option selected value="">Select...</option>
                                    <?php foreach($cw_list as $cw){?>
                                        <option  value="<?php echo html_escape($cw['warehouse_id'])?>"><?php echo html_escape($cw['central_warehouse']) ;?></option>
                                    <?php }?>
                                    <?php foreach($outlet_list as $outlet){?>
                                        <option value="<?php echo html_escape($outlet['outlet_id'])?>"><?php echo html_escape($outlet['outlet_name']) ;?></option>
                                    <?php }?>

                                    <option value="All">Consolidated</option>
                                  


                                </select>

                            </div>
                            <?php 
                            }
                            ?>
                           <div class="col-sm-6">
                                <label for="supplier_id" class="col-form-label">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" tabindex="1">
                                    <option value="" selected disabled><?php echo display('select_one') ?></option>
                                    <?php foreach ($supplier_list as $supplier) { ?>
                                        <option value="<?php echo ($supplier['supplier_id']) ?>" <?php echo ($supplier['supplier_id']) == $supplier_id ? 'selected' : ''; ?>><?php echo ($supplier['supplier_name']); ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                        <div>

                            <div class="table-responsive" id="printableArea">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="PurchaseReport">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th>Outlet Name</th>
                                            <th class="text-center">Purchase Date</th>
                                            <th class="text-center">Purchase ID</th>
                                            <th>Supplier Name</th>
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                            <th class="text-center">Quantity</th>
                                            <th><?php echo display('rate') ?></th>
                                            <th><?php echo display('total_ammount') ?></th>
                                            <th>Purchased By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-right"><?php echo display('total') ?> :</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>

                                            

                                        </tr>

                                    </tfoot>

                                </table>
                            </div>
                        </div>
                         <input type="hidden" id="currency" value="{currency}" name=""> 
                        
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
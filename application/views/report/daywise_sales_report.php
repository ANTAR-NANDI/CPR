<!-- Sales Report Start -->

<?php
$currentURL = $this->uri->uri_string();
$params   = $_SERVER['QUERY_STRING'];
$fullURL = $currentURL . '?' . $params;
$_SESSION['redirect_uri'] = $fullURL;

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Report</h1>
            <small>Expense Report</small>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() ?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Report</a></li>
                <li class="active">Expense Report</li>
            </ol>
        </div>
    </section>

    <section class="content">


        <!-- Sales report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                <div class="panel-body"> 
                        <?php echo form_open('Creport/daywise_sales_report', array('class' => '', 'id' => 'validate')) ?>
                        <?php $today = date('Y-m-d'); ?>
                       <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select name="customer_id"  class="form-control">
                                    <option value=""></option>
                                   <?php foreach($customer as $customers){?>
                                    <option value="<?php echo html_escape($customers['customer_id'])?>"  <?php if($customers['customer_id'] == $customer_id){echo 'selected';}?>><?php echo html_escape($customers['customer_name'])?></option>
                                   <?php }?>
                                </select>
                            </div>
                            </div>
                            </div> 
                            <div class="col-sm-5">
                        <div class="form-group row">
                                <label for="from_date " class="col-sm-2 col-form-label"> <?php echo display('from') ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="from_date"  value="<?php echo (!empty($from_date)?$from_date:$today); ?>" class="datepicker form-control" id="from_date"/>
                                </div>
                                 <label for="to_date" class="col-sm-2 col-form-label"> <?php echo display('to') ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="to_date" value="<?php echo (!empty($to_date)?$to_date:$today); ?>" class="datepicker form-control" id="to_date"/>
                                </div>
                          
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                        <a class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>
                        <?php echo form_close() ?>
                </div>
            </div>
        </div>
       

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Expense Report</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="purchase_div" class="table-responsive">
                            <table class="print-table" width="100%">

                                <tr>
                                    <td align="left" class="print-table-tr">
                                        <img src="<?php echo $software_info[0]['logo']; ?>" alt="logo">
                                    </td>
                                    <td align="center" class="print-cominfo">
                                        <span class="company-txt">
                                            <?php echo $company[0]['company_name']; ?>

                                        </span><br>
                                        <?php echo $company[0]['address']; ?>
                                        <br>
                                        <?php echo $company[0]['email']; ?>
                                        <br>
                                        <?php echo $company[0]['mobile']; ?>

                                    </td>

                                    <td align="right" class="print-table-tr">
                                        <date>
                                            <?php echo display('date') ?>: <?php
                                                                            echo date('d-M-Y');
                                                                            ?>
                                        </date>
                                    </td>
                                </tr>



                            </table>
                            <h3>
                                <center>
                                    <?php if($from_date)
                                    { ?>
                                    Datewise Sale Report From <?php echo $from_date; ?> to <?= $to_date; ?>
                                    <?php }else{ ?>
                                        Datewise Sale Report
                                   <?php } ?> 
                                    
                                </center>
                            </h3>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="">
       
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">Unit</th>
                                        <?php foreach ($dateWiseSalesData as $date => $sales): ?>
                                        <th class="text-center"><?= $date ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-center">Total Sold</th>
                                        
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php $sum = 0; $base_url = base_url(); $sl = 0; foreach ($uniqueProducts as $product): ?>
                                            <tr>
                                            <td><?= ++$sl ?></td>
                                            <td>
                                                <a href="<?= $base_url . 'Cproduct/product_details/' . $product['product_id'] ?>">
                                                    <?= $product['product_name'] ?>
                                                </a>
                                            </td>
                                                <td><?= $product['unit']  ?></td>
                                                <?php foreach ($dateWiseSalesData as $sales): ?>
                                                    <td>
                                                        <?php
                                                        $total = 0;
                                                        $quantity = 0;
                                                        foreach ($sales['products'] as $productData) {
                                                            if ($productData['product_name'] == $product['product_name']) {
                                                                $quantity = $productData['quantity'];
                                                                break;
                                                            }
                                                        }
                                                        echo $quantity;
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                                <td><b><?= $product['total_quantity'] ?></b></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right"><?php echo display('total') ?>:</th>
                                        <?php foreach ($dateWiseSalesData as $sales): ?>
                                                    <th>
                                                        <?php
                                                        echo $sales['total_quantity'];
                                                        $sum += $sales['total_quantity'];
                                                             
                                                        ?>
                                                    </th>
                                                    
                                                <?php endforeach; ?>
                                                <th style="font-size:20px">
                                                        <?php
                                                        echo $sum;
                                                             
                                                        ?>
                                                    </th>

                                    </tr>

                                </tfoot>
                         </table>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Sales Report End -->
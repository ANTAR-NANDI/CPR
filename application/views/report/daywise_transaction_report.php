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
            <small>Transaction Report</small>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() ?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Report</a></li>
                <li class="active">Transaction Report
</li>
            </ol>
        </div>
    </section>

    <section class="content">


        <!-- Sales report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                <div class="panel-body"> 
                        <?php echo form_open('Creport/daywise_transaction_report', array('class' => '', 'id' => 'validate')) ?>
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
                            <h4>Transaction Report
</h4>
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
                                    Datewise Transaction Report From <?php echo $from_date; ?> to <?= $to_date; ?>
                                    <?php }else{ ?>
                                        Transaction Report
                                   <?php } ?> 
                                    
                                </center>
                            </h3>
                            <div class="table-responsive">
                            <!-- <table class="table table-bordered table-striped table-hover" id="">
       
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Contact Number</th>
                                        <th class="text-center">Opening Balance</th>
                                        <?php foreach ($dateWiseSalesData as $date => $sales): ?>
                                            <th style="width: 100%">
                                            <div class="text-center" style="width: 100%"><?= $date ?><hr style="flex: 1; border: none; border-top: 2px solid #000; margin: 0;" width="100%">

                                            </div><br>
                                            <div style="border-right: 2px solid #000; padding: 0 10px; display: inline-block; text-align: left;">Credit</div>
                                            <div style="padding: 0 10px; display: inline-block;">Debit</div>
                                            </th>
                                            
                                             
                                        

                                    <?php endforeach; ?>
                                    <th>
                                            <div class="text-center" style="width: 100%">Total<hr style="flex: 1; border: none; border-top: 2px solid #000; margin: 0;" width="100%">

                                            </div><br>
                                            <div style="border-right: 2px solid #000; padding: 0 10px; display: inline-block; text-align: left;">Credit</div>
                                            <div style="padding: 0 10px; display: inline-block;">Debit</div>
                                            </th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php $sum = 0; $base_url = base_url(); $sl = 0; foreach ($uniqueProducts as $product): ?>
                                            <tr>
                                            <td><?= ++$sl ?></td>
                                            <td>
                                                
                                                    <?= $product['customer_name'] ?>
                                            </td>
                                                <td><?= $product['customer_address']  ?></td>
                                                <td><?= $product['customer_mobile']  ?></td>
                                                <td><?= $product['opening_balance']  ?></td>
                                                <?php foreach ($dateWiseSalesData as $sales): ?>
                                                    <td>
                                                        <?php
                                                        $total = 0;
                                                        $quantity = 0;
                                                        foreach ($sales['products'] as $productData) {
                                                            if ($productData['customer_id'] == $product['customer_id']) {
                                                                $paid_amount = $productData['paid_amount'];
                                                                $total_amount = $productData['total_amount'];
                                                                break;
                                                            }
                                                            else{
                                                                $paid_amount = 0;
                                                                $total_amount = 0;
                                                            }
                                                        }
                                                        
                                                       
                                                        ?>
                                                        <div class="row">
                                                        <div class="col-md-6" style="border-right: 2px solid #000; padding: 0 10px; text-align: left;">
                                                           <?= $total_amount ?>
                                                        </div>
                                                        <div class="col-md-6" style="padding: 0 10px;">
                                                        <?= $paid_amount ?>
                                                        </div>

                                                        </div>
                                                    </td>
                                                <?php endforeach; ?>
                                                <td>
                                                <div style="border-right: 2px solid #000; padding: 0 10px; display: inline-block; text-align: left;"><?php
                                                        echo $product['total_amount'];
                                                             
                                                        ?></div>
                                                 <div style="padding: 0 10px; display: inline-block;"> <?php
                                                        echo $product['paid_amount'];
                                                             
                                                        ?></div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right"><?php echo display('total') ?>:</th>
                                        <?php foreach ($dateWiseSalesData as $sales): ?>
                                                    <th>
                                                    <div style="border-right: 2px solid #000; padding: 0 10px; display: inline-block; text-align: left;"><?php
                                                        echo $sales['total_amount'];
                                                             
                                                        ?></div>
                                                 <div style="padding: 0 10px; display: inline-block;"> <?php
                                                        echo $sales['paid_amount'];
                                                             
                                                        ?></div>
                                                        <?php
                                                        $total_sum += $sales['total_amount'];
                                                        $paid_sum += $sales['paid_aount'];   
                                                        ?>
                                                    </th>
                                                    
                                                <?php endforeach; ?>
                                                <th style="font-size:20px">
                                                <div style="border-right: 2px solid #000; padding: 0 10px; display: inline-block; text-align: left;"><?php
                                                        echo $total_sum;
                                                             
                                                        ?></div>
                                                 <div style="padding: 0 10px; display: inline-block;"> <?php
                                                        echo $paid_sum;
                                                             
                                                        ?></div>
                                                       
                                                    </th>

                                    </tr>

                                </tfoot>
                         </table> -->
                         <table class="table table-bordered table-striped table-hover" id="your-table-id">
    <thead>
        <tr>
            <th class="text-center">SL</th>
            <th class="text-center">Customer Name</th>
            <th class="text-center">Address</th>
            <th class="text-center">Contact Number</th>
            <th class="text-center">Opening Balance</th>
            <th class="text-center">Closing Balance</th>
            <?php foreach ($dateWiseSalesData as $date => $sales): ?>
                <th class="text-center">
                    <?= $date ?><br>
                    Credit - Debit
                </th>
            <?php endforeach; ?>
            <th class="text-center">Total<br>Credit - Debit</th>
        </tr>
    </thead>
    <tbody>
        <?php $sum = 0; $base_url = base_url(); $sl = 0; foreach ($uniqueProducts as $product): ?>
            <tr>
                <td><?= ++$sl ?></td>
                <td><?= $product['customer_name'] ?></td>
                <td><?= $product['customer_address'] ?></td>
                <td><?= $product['customer_mobile'] ?></td>
                <td><?= $product['opening_balance'] ?></td>
                <td><?= $product['opening_balance'] + ($product['total_amount'] - $product['paid_amount']) ?></td>
                <?php foreach ($dateWiseSalesData as $sales): ?>
                    <td class="text-center">
                        <?php
                        $total = 0;
                        $quantity = 0;
                        foreach ($sales['products'] as $productData) {
                            if ($productData['customer_id'] == $product['customer_id']) {
                                $paid_amount = $productData['paid_amount'];
                                $total_amount = $productData['total_amount'];
                                break;
                            } else {
                                $paid_amount = 0;
                                $total_amount = 0;
                            }
                        }
                        echo $total_amount . ' - ' . $paid_amount;
                        ?>
                    </td>
                <?php endforeach; ?>
                <td class="text-center">
                    <?php
                    echo $product['total_amount'] . ' - ' . $product['paid_amount'];
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-right"><?php echo display('total') ?>:</th>
            <?php foreach ($dateWiseSalesData as $sales): ?>
                <th class="text-center">
                    <?php
                    echo $sales['total_amount'] . ' - ' . $sales['paid_amount'];
                    $total_sum += $sales['total_amount'];
                    $paid_sum += $sales['paid_aount'];
                    ?>
                </th>
            <?php endforeach; ?>
            <th class="text-center">
                <?php
                echo $total_sum . ' - ' . $paid_sum;
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
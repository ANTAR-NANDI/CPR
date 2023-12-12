
<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Fund & Deduction" ?></h1>
            <small><?php echo "Edit Fund & Deduction" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Fund & Deduction" ?></a></li>
                <li class="active"><?php echo "Edit Fund & Deduction" ?></li>
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

        
       

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Edit Fund & Deduction" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Fund_Deduction/update_fund_deduction', array('class' => 'form-vertical', 'id' => 'insert_comission')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label">Fund & Deduction Name<i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" value="<?php echo $name ?>" name="name" id="name" type="text" placeholder="Fund & Deduction Name" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card" class="col-sm-4 col-form-label">Fund Deduction Type <i class="text-danger">*</i></label>
                                <div class="col-sm-8">

                                    <select required name="type" class="form-control" tabindex="3">
                                        <?php if($type == 'income'){ ?>
                                            <option selected value="income">Income</option>
                                            <option value="fund">Fund</option>
                                            
                                            <?php } else{ ?>
                                                <option selected value="fund">Fund</option>
                                                <option value="income">Income</option>
                                                <?php } ?>
                                                
                                                
                                           
                                            

                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label">Percentage ( % )</label>
                                <div class="col-sm-8">
                                    <input class="form-control" value="<?php echo $percentage ?>" name="percentage" id="percentage" type="number" placeholder="Percentage ( % )" required="" tabindex="1">
                                </div>
                            </div>
                            <input class="form-control" value="<?php echo $id ?>" name="fund_deduction_id" id="fund_deduction_id" type="hidden" placeholder="Percentage ( % )" required="" tabindex="1">
                            
                            

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" id="update-fund" class="btn btn-primary btn-large" name="update-fund" value="Update" tabindex="7" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
        
    </section>
</div>
<!-- Add new customer end -->


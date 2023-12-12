
<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo " Commission" ?></h1>
            <small><?php echo "Edit Commission" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Commission" ?></a></li>
                <li class="active"><?php echo "Edit Commission" ?></li>
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
                            <h4><?php echo "Edit Commission" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Ctechnician/update_commission', array('class' => 'form-vertical', 'id' => 'insert_comission')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">

                        <div class="form-group row">
                                <label for="card" class="col-sm-4 col-form-label">User Name</label>
                                <div class="col-sm-8">

                                    <select required name="user_id" class="form-control" tabindex="3">                                       
                                        <option selected value="<?php echo html_escape($technician_id) ?>"><?php echo $first_name. " ". $last_name ?></option>   
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="commission_rate" class="col-sm-4 col-form-label">Commission Rate<i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input value="<?php echo $rate ?>" class="form-control" name="commission_rate" id="commission_rate" type="text" placeholder="Commission Rate" required="" tabindex="1">
                                </div>
                            </div>
                            <input value="<?php echo $id ?>" class="form-control" name="commission_id" id="commission_id" type="hidden" placeholder="Commission Rate" required="" tabindex="1">
                            

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" id="add-comission" class="btn btn-primary btn-large" name="add-comission" value="<?php echo "Update" ?>" tabindex="7" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
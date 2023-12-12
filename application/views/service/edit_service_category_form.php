<!-- Add new Service start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_service') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('service') ?></a></li>
                <li class="active"><?php echo $title ?></li>
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
        <div class="col-sm-3">
           
              
           </div>
            <div class="col-sm-6">
              
  <?php if($this->permission1->method('manage_service','read')->access()){ ?>
                    <a href="<?php echo base_url('Cservice/manage_service') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_service') ?> </a>
                <?php }?>
                 <?php if($this->permission1->method('create_service','create')->access()){ ?>
                <button type="button" class="btn btn-info m-b-5 m-r-2" data-toggle="modal" data-target="#service_csv"><?php echo display('service_csv_upload')?></button>
                <?php }?>
              
            </div>
            
            <div class="col-sm-3">
              
              
            </div>
        </div>

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo $title ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Cservice/update_service_category', array('class' => 'form-vertical', 'id' => 'insert_service')) ?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="service_name" class="col-sm-3 col-form-label"><?php echo "Service Category Name" ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                            <input class="form-control" type="hidden" value="<?= $category_id ?>" name ="category_id" id="category_id">
                                <input class="form-control" value="<?= $category_name ?>" name ="category_name" id="category_name" type="text" placeholder="Enter Service Category Name"  required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-service-category" class="btn btn-success btn-large" name="add-service-category" value="<?php echo display('save') ?>" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
                 
        </div>
    </section>
</div>
<!-- Add new Service end -->





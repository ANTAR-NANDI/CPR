<!-- Edit User start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Technician" ?></h1>
            <small><?php echo "Technician Edit" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('web_settings') ?></a></li>
                <li class="active"><?php echo "Technician Edit" ?></li>
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

        <!-- New user -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Technician Edit" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Ctechnician/update_technician', array('class' => 'form-vertical', 'id' => 'validate')) ?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="bank_name" class="col-sm-3 col-form-label"><?php echo display('first_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="1" class="form-control" name="first_name" value="<?php echo $first_name ?>" placeholder="<?php echo display('first_name') ?>" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="bank_name" class="col-sm-3 col-form-label"><?php echo display('last_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" name="last_name" value="<?php echo $last_name ?>" placeholder="<?php echo display('last_name') ?>" required />
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label"><?php echo display('email') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="3" id="email" class="form-control" name="username" value="<?php echo $username ?>" placeholder="<?php echo display('email') ?>" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label"><?php echo display('password') ?> <i class="text-danger"></i></label>
                            <div class="col-sm-6">
                                <input type="password" tabindex="4" id="password" class="form-control" name="password" placeholder="<?php echo display('password') ?>" />
                                <input type="hidden" name="old_password" value="<?php echo $password ?>">
                            </div>
                        </div>

                       

                        <div class="form-group row">
                            <label for="outlet" class="col-sm-3 col-form-label">Outlet <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="outlet" id="outlet" tabindex="6" required="">
                                    <?php foreach ($outlet_list as $outlet) {
                                        if ($outlet['outlet_id'] == $outlet_id) { ?>
                                            <option value="<?= $outlet['outlet_id'] ?>" selected><?= $outlet['outlet_name'] ?></option>

                                        <?php } else { ?>
                                            <option value="<?= $outlet['outlet_id'] ?>"><?= $outlet['outlet_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bank_name" class="col-sm-3 col-form-label"><?php echo display('status') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status" tabindex="6">
                                    <option value="0"><?php echo display('select_one') ?></option>
                                    <option value="1" <?php if ($status == 1) {
                                                            echo 'selected';
                                                        } ?>><?php echo display('active') ?></option>
                                    <option value="0" <?php if ($status == 0) {
                                                            echo 'selected';
                                                        } ?>><?php echo display('inactive') ?></option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-Customer" class="btn btn-success btn-large" name="add-Customer" value="<?php echo display('save_changes') ?>" tabindex="6" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit user end -->
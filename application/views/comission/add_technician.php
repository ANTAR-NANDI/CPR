<!-- Add User start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_user') ?></h1>
            <small><?php echo display('add_new_user_information') ?></small>
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('web_settings') ?></a></li>
                <li class="active"><?php echo display('add_user') ?></li>
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
                            <h4><?php echo display('add_user') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Ctechnician/store_technician', array('class' => 'form-vertical', 'id' => 'validate')) ?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="first_name" class="col-sm-3 col-form-label"><?php echo display('first_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="1" class="form-control" name="first_name" id="first_name" placeholder="<?php echo display('first_name') ?>" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-sm-3 col-form-label"><?php echo display('last_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" name="last_name" id="last_name" placeholder="<?php echo display('last_name') ?>" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_name" class="col-sm-3 col-form-label"><?php echo display('mobile') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" name="mobile" id="mobile" placeholder="<?php echo display('last_name') ?>" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label"><?php echo display('email') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="email" required="" onkeypress="checkemail()" onkeyup="checkemail()" onchange="checkemail()" tabindex="3" class="form-control email" name="email" id="email" placeholder="<?php echo display('email') ?>" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label"><?php echo display('password') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="password" tabindex="4" required="" class="form-control" id="password" name="password" placeholder="<?php echo display('password') ?>" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label"><?php echo display('image') ?> <i class="text-danger"></i></label>
                            <div class="col-sm-6">
                                <input type="file" tabindex="5" class="form-control" id="logo" name="logo" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="outlet" class="col-sm-3 col-form-label">Outlet <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="outlet" id="outlet" tabindex="6" required="">
                                    {outlet_list}
                                    <option value="{outlet_id}">{outlet_name}</option>
                                    {/outlet_list}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user_type" class="col-sm-3 col-form-label"><?php echo display('user_type') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="user_type" id="user_type" tabindex="6" required="">
                                    <option value="3"><?php echo "Technician" ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-technician" class="btn btn-primary btn-large" name="add-technician" value="<?php echo display('save') ?>" tabindex="6" />

                                <input type="submit" value="<?php echo display('save_and_add_another') ?>e" name="add-technician-another" class="btn btn-success" id="add-technician-another" tabindex="7">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    function checkemail()
    {
        var email = $('#email').val();
            var csrf_test_name = $("[name=csrf_test_name]").val();
            var base_url = $("#base_url").val();
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Ctechnician/checkEmail",
                data: {
                    'email': email,
                    'csrf_test_name': csrf_test_name
                },
                success: function(response) {
                    console.log(response.length);  
                    if(response.length > 0)
                       {
                        $("#add-technician-another").prop('disabled', true);
                        $("#add-technician").prop('disabled', true);
                       }
                       else{
                        $("#add-technician-another").prop('disabled', false);
                        $("#add-technician").prop('disabled', false);
                       }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
    }
  $(document).ready(function() {
    function checkemail()
    {
        var email = $('#email').val();
            var csrf_test_name = $("[name=csrf_test_name]").val();
            var base_url = $("#base_url").val();
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Ctechnician/checkEmail",
                data: {
                    'email': email,
                    'csrf_test_name': csrf_test_name
                },
                success: function(response) {
                    console.log(response.length);  
                    if(response.length > 0)
                       {
                        $("#add-technician-another").prop('disabled', true);
                        $("#add-technician").prop('disabled', true);
                       }
                    //    else{
                    //     $("#add-technician-another").prop('disabled', false);
                    //     $("#add-technician").prop('disabled', false);
                    //    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
    }
    // $(document).on('keypress', '.email', function() {
    //         var email = $('#email').val();
    //         var csrf_test_name = $("[name=csrf_test_name]").val();
    //         var base_url = $("#base_url").val();
    //         jQuery.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: base_url + "Ctechnician/checkEmail",
    //             data: {
    //                 'email': email,
    //                 'csrf_test_name': csrf_test_name
    //             },
    //             success: function(response) {
    //                 console.log(response.length);  
    //                 if(response.length > 0)
    //                    {
    //                     $("#add-technician-another").prop('disabled', true);
    //                     $("#add-technician").prop('disabled', true);
    //                    }
    //                 //    else{
    //                 //     $("#add-technician-another").prop('disabled', false);
    //                 //     $("#add-technician").prop('disabled', false);
    //                 //    }
    //             },
    //             error: function(XMLHttpRequest, textStatus, errorThrown) {
    //                 alert(errorThrown);
    //             }
    //         });
    //     });
    });
</script>
<!-- Edit user end -->
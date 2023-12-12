
<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo " Commission" ?></h1>
            <small><?php echo "Add Commission" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Commission" ?></a></li>
                <li class="active"><?php echo "Add Commission" ?></li>
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
                            <h4><?php echo "Add Commission" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Ctechnician/insert_comission', array('class' => 'form-vertical', 'id' => 'insert_comission')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">

                        <div class="form-group row">
                                <label for="card" class="col-sm-4 col-form-label">User Name</label>
                                <div class="col-sm-8">

                                    <select required name="user_id" id="technician_id" class="form-control technician_id" tabindex="3">
                                    <option value="">Select One</option>
                                        <?php foreach ($user_list as $user) { ?>
                                            <option value="<?php echo html_escape($user['user_id']) ?>"><?php echo $user['first_name']. " ". $user['last_name'] ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label">Commission Rate<i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="commission_rate" id="commission_rate" type="text" placeholder="Commission Rate" required="" tabindex="1">
                                </div>
                            </div>
                            
                            

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" id="add-comission" class="btn btn-primary btn-large" name="add-comission" value="<?php echo display('save') ?>" tabindex="7" />
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

<script type="text/javascript">
  $(document).ready(function() {
       $(document).on('change', '.technician_id', function() {
            var technician_id = $('#technician_id').val();
            var csrf_test_name = $("[name=csrf_test_name]").val();
            var base_url = $("#base_url").val();
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Ctechnician/checkCommission",
                data: {
                    'technician_id': technician_id,
                    'csrf_test_name': csrf_test_name
                },
                success: function(response) {
                    console.log(response.length);  
                    if(response.length > 0)
                       {
                        alert('Technician Already Exist');
                        $("#add-comission").prop('disabled', true);
                       }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });
    });
</script>
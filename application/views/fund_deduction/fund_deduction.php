<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Fund Deduction" ?></h1>
            <small><?php echo "Manage Fund Deduction" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Fund Deduction" ?></a></li>
                <li class="active"><?php echo "Manage Fund Deduction" ?></li>
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

       





        <!-- Manage Product report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Manage Fund Deduction" ?></h4>


                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" style="overflow-x: auto !important;">
                        <table class="table table-striped table-bordered" cellspacing="0" id="fund_deduction_list" width="100%" style="overflow-x: auto !important;">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th>Fund Name</th>
                                        <th>Fund Type</th>
                                        <th>Percentage</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                </tfoot>
                            </table>

                        </div>
                        <input type="hidden" name="" class="" id="total_fund_deductions" value="<?php echo html_escape($total_fund_deductions); ?>">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
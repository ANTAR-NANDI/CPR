<!-- Add Phrase Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Phrase Edit</h1>
            <small>phrase Edit</small>
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="pe-7s-home"></i> Home</a></li>
                <li><a href="#">Language</a></li>
                <li class="active">Phrase Edit</li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Phrase Edit -->

        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>
            </div>
        <?php
        }
        ?>

        <div class="row">
            <div class="col-sm-12">
                <a href="<?php echo  base_url('Language') ?>" class="btn btn-info">Language Home</a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Phrase Edit</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo form_open('Language/editPhrase/{language}') ?>

                                    <div class="col-sm-8">
                                        <input type="text" name="search_phrase" class="form-control" placeholder="<?php echo display('search_phrase'); ?>" value="{search_phrase}">
                                    </div>


                                    <div class="col-sm-4">

                                        <button type="submit" class="btn btn-success "><i class="fa fa-search-plus" aria-hidden="true"></i> <?php echo display('search') ?></button>
                                    </div>
                                    <?php echo form_close() ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive">
                            <?php echo  form_open('language/addlebel') ?>
                            <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <td colspan="3">
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </td>

                                        <!-- <td colspan="2">

                                            <?php echo form_open('Language/searchPhrase/{language}', array('class' => 'form-vertical', 'id' => 'searchPhrase')) ?>

                                            <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <input type="text" name="search_phrase" class="form-control" placeholder="">
                                            </div>
                                        </div>

                                        <input type="submit" class="btn btn-success" value="Submit">

                                        <?php echo form_close() ?>
                                        </td> -->



                                    </tr>
                                    <tr>
                                        <td colspan="3"><?php echo $links; ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa fa-th-list"></i></th>
                                        <th>Phrase</th>
                                        <th>Label</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php echo  form_hidden('language', $language) ?>
                                    <?php if (!empty($phrases)) { ?>
                                        <?php $sl = 1 ?>
                                        <?php foreach ($phrases as $value) { ?>
                                            <tr class="<?php echo  html_escape((empty($value->$language) ? "bg-danger" : null)) ?>">

                                                <td><?php echo  $this->uri->segment(4) + $sl ?></td>
                                                <td><input type="text" name="phrase[]" value="<?php echo  html_escape($value->phrase) ?>" class="form-control" readonly></td>
                                                <td><input type="text" name="lang[]" value="<?php echo  html_escape($value->$language) ?>" class="form-control"></td>
                                            </tr>
                                        <?php $sl++;
                                        } ?>
                                    <?php } ?>

                                    <tr>
                                        <td colspan="3">
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <?php echo $links; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Phrase Edit End -->
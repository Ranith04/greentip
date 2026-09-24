<?php $this->load->view('includes/header_script');?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="wrapper-md">
            <?php
            $this->load->view('includes/msg_alert'); ?>

                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <h4><i class="fa fa-plug"></i> Add Question Category</h4>
                    </div>
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row wrapper">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <form class="form-horizontal" method="post" name="CategoryAdd" id="CategoryAdd" enctype="multipart/form-data" action="">

                                        <!-- Form Group Start -->





                                        <!-- Form Group Start -->

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="catname">Category Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="cat_name" id="cat_name" value="<?php echo set_value('cat_name'); ?>">
                                                <?php echo form_error('cat_name'); ?>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                        <!-- Form Group End -->




                                        <!-- Form Group Start -->

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="status">Status</label>
                                            <div class="col-sm-10">
                                                <?php
                                                echo form_dropdown('status', array('1'=>'Active','0'=>'Inactive'), array(set_value('status')),'class="input-sm form-control w-sm inline v-middle" id="status" ');
                                                ?>
                                                <?php echo form_error('status'); ?>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                        <!-- Form Group End -->
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-default">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer'); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script src="<?php echo assets_url('grocery_crud','themes/bootstrap/bower_components/bootstrap-filestyle/src/bootstrap-filestyle.js'); ?>"></script>
<script type="text/javascript">
    SyonApp.setPage('CategoryAdd');
    SyonApp.init();
</script>
</body>
</html>
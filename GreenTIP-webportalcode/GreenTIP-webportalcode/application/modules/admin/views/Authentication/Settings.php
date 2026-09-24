<?php $this->load->view('includes/header_script', $data); ?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="bg-light lter b-b wrapper-md">
                <h1 class="m-n font-thin h3">Website Settings</h1>
            </div>
            <div class="wrapper-md">
                <div class="row">
                    <?php $this->load->view('includes/msg_alert'); ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">Admin Profile</div>
                            <div class="panel-body">
                                <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo base_url('admin/profile'); ?>" method="post" name="Settings" id="Settings">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Full Name</label>
                                        <div class="col-lg-10">
                                        <input type="text" placeholder="Full Name" name="FullName" value="<?php echo set_value('FullName',$dbData['full_name']); ?>" class="form-control">
                                        <?php echo form_error('FullName'); ?>
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Profile Image</label>
                                        <div class="col-lg-10">
                                            <span class="thumb-lg w-auto-folded m-t-sm m-b-sm">
                                                <?php
                                                if(!empty($dbData['image'])){
                                                    ?>
                                                    <img alt="Admin Profile Pic" class="img-full" src="<?php echo image_url('AdminUser',$dbData['image'],'200','200'); ?>">
                                                <?php
                                                }else{
                                                    ?>
                                                    <img alt="Admin Profile Pic" class="img-full" src="<?php echo assets_url('grocery_crud','themes/bootstrap/img/a0.jpg'); ?>">
                                                <?php
                                                }
                                                ?>
                                                </span>
                                            <input type="file" name="AdminImage" class="form-control file-style">
                                            <?php echo form_error('AdminImage'); ?>
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="col-lg-8">
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <a class="btn btn-default m-r-lg" href="<?php echo base_url('admin'); ?>">Cancel</a>
                                        <input class="btn btn-sm btn-primary" type="submit" name="SettingSubmit" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer', $data); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script src="<?php echo assets_url('js','bower_components/bootstrap-filestyle/bootstrap-filestyle.js'); ?>"></script>
<script type="text/javascript">
    $(".file-style").filestyle({
        buttonText : 'Select Image',
        'iconName' : 'glyphicon-picture'
    });
</script>
</body>
</html>
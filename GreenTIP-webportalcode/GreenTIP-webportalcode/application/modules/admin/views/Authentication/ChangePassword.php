<?php $this->load->view('includes/header_script', $data); ?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="bg-light lter b-b wrapper-md">
                <h1 class="m-n font-thin h3">Change Admin Password</h1>
            </div>
            <div class="wrapper-md">
                <div class="row">
                    <?php $this->load->view('includes/msg_alert'); ?>
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                            <div class="panel panel-default">
                                <div class="panel-heading font-bold">Change Password</div>
                                <div class="panel-body">
                                    <form action="<?php echo base_url('admin/change-password'); ?>" method="post" name="ChangePassword" id="ChangePassword">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" placeholder="Change Password" name="OldPassword" class="form-control">
                                            <?php echo form_error('OldPassword'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" placeholder="New Password" name="NewPassword" class="form-control">
                                            <?php echo form_error('NewPassword'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" placeholder="Confirm Password" name="ConfirmPassword" class="form-control">
                                            <?php echo form_error('ConfirmPassword'); ?>
                                        </div>

                                        <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer', $data); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script src="<?php echo assets_url('grocery_crud','themes/bootstrap/bower_components/bootstrap-filestyle/src/bootstrap-filestyle.js'); ?>"></script>
<script type="text/javascript">
    SyonApp.setPage('ChangePwd');
    SyonApp.init();
</script>
</body>
</html>
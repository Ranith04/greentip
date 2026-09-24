<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
    <div class="container">
        <div class="row ask_Export">
            <?php $this->load->view('Includes/profilesidebar'); ?>
            <div class="col-sm-9 col-md-9 col-lg-9 UserSetting">
                <?php $this->load->view('Includes/msg_alert'); ?>
                <h3 class="title changpswLabl" style="text-align: center;">Change <font>Password</font></h3>
                <form method="post" id="ChangePasswordForm" name="ChangePasswordForm" enctype="multipart/form-data" action="">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Old Password*</label>
						<div class="input-group col-xs-12 col-md-9">
                         <input class="form-control" type="password" name="OldPassword" placeholder="Enter Current Password"> <?php echo form_error('OldPassword'); ?>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="control-label col-xs-12 col-md-3">New Password*</label>
						<div class="input-group col-xs-12 col-md-9">
                          <input class="form-control" type="password" name="NewPassword" placeholder="Enter New Password"> <?php echo form_error('NewPassword'); ?>
						 </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="control-label col-xs-12 col-md-3">Confirm New Password*</label>
						<div class="input-group col-xs-12 col-md-9">
                          <input class="form-control" type="password" name="ConPassword" placeholder="Enter Confirm New Password"> <?php echo form_error('ConPassword'); ?>
						</div>
                    </div>
                    <b>Password should be alphanumeric, minimum 8 characters etc.</b>
					 <div class="form-group col-xs-12 text-right">
                        <input type="submit" class="btn btn-success changePsw" value="Change Password">
					 </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo assets_url('js', 'authentication.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
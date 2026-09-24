<?php $this->load->view('includes/header_script', $data); ?>
<div class="app app-header-fixed ">
    <div class="container w-xxl w-auto-xs">
        <a href="" class="text-center block m-t">
            <img alt="<?php echo ADMIN_COMPANY;?> Admin Login" class="thumb-xl" src="<?php echo assets_url('img','logo.png');?>">
        </a>
        <div class="m-b-lg">
            <div class="wrapper text-center">
                <strong>Sign in to access <?php echo ADMIN_COMPANY;?> Admin Panel</strong>
            </div>
            <form name="LoginForm" id="LoginForm" method="post" action="<?php echo base_url('admin/login'); ?>" class="form-validation">
                <?php $this->load->view('includes/msg_alert'); ?>
                <div class="list-group list-group-sm">
                    <div class="list-group-item">
                        <input type="text" placeholder="Username" value="<?php echo set_value('username'); ?>" name="username" class="form-control no-border">
                        <?php echo form_error('username'); ?>
                    </div>
                    <div class="list-group-item">
                        <input type="password" placeholder="Password" class="form-control no-border" name="password">
                        <?php echo form_error('password'); ?>
                    </div>
                </div>
                <div class="checkbox m-b-md m-t-none">
                    <label class="i-checks">
                        <input name="remember" type="checkbox" class="ng-pristine ng-untouched ng-invalid ng-invalid-required"><i></i> Remember me
                    </label>
                </div>
                <input type="submit" class="btn btn-lg btn-primary dker btn-block" value="Log in">
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script src="<?php echo assets_url('js','validation/admin/login.js'); ?>"></script>
<script src="<?php echo assets_url('js','validation/CustomValidation.js'); ?>"></script>

</body>
</html>
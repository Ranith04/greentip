<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
    <section class="main-content">
        <div class="body-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-offset-3">
                        <h3 class="title">Reset <font>Password</font></h3>
                        <form id="FormResetPassword" name="FormResetPassword" method="post" class="login-form" action="<?php echo base_url(); ?>Authentication/doResetPassword" enctype="multipart/form-data">
                            <div class="form-group">
                                <input name="resetPassword" id="resetPassword" type="password" placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group">
                                <input name="resetConPassword" id="resetConPassword" type="password" placeholder="Confirm Password" class="form-control">
                            </div>
                            <input type="hidden" name="reset_token" value="<?php echo $reset_token; ?>"/>
                            <div class="submit-btn">
                                <button type="submit" class="btn btn-success btn-block" id="btnSubmit">Reset Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- body-container -->
    </section>
    <script src="<?php echo assets_url('js', 'authentication.js'); ?>"></script>
<?php $this->load->view('Includes/footer');?>
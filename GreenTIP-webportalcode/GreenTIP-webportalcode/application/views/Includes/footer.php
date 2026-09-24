</div>
<?php
$userData = (array)getSessionUserData('auth_user_data');
$UserDetail = array();
if (isset($userData) && !empty($userData)) {
    $UserDetail = $userData;
}
//if (empty($UserDetail['id'])) { 

    ?>

    <section <?php if (!empty($UserDetail['id'])){  ?> style= "margin-top: 2%;" <?php }?>  class="section-footer myfooter">
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="navbar">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" class="active">Home</a></li>
                                <li><a href="<?php echo base_url('page/about-us'); ?>">About Us</a></li>
                                <li><a href="<?php echo base_url('page/knowledge-center'); ?>">Knowledge Center</a></li>
                                <li><a href="<?php echo base_url('faq'); ?>">FAQs</a></li>
                                <li><a href="<?php echo base_url('contact'); ?>">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?= form_open(base_url('ajax/AjaxSubscribe'), array('class' => 'ajax_form display-none', 'id' => 'verifyForm')) ?>
                        <div class="newslatter">
                            <h3>Free Subscription</h3>

                            <input placeholder="Enter Email address"  data-val="true" data-val-required="The Email field is required." id="SubEmail" name="SubEmail" required="required" type="email">
                            <button type="submit" value="Subscribe">Subscribe</button>
                        </div>
                        <div class="alert ajax_report alert-hide" role="alert" style="display: none;width: 80%">
                            <span class="close">x</span> <span class="ajax_message"></span>
                        </div>
                        </form>
                    </div>
                    <div class="col-sm-12">
                        <p class="copyright">
                            <i style="font-size:16px" class="fa">&#xf1f9;</i>
                            <?php echo date('Y'); ?> GRC GreenTIP. All right reserved. <a href="<?php echo base_url('page/privacy-policy') ?>">Privacy Policy</a>
                        </p>
                    </div>
                </div>
            </div><!-- container -->
        </footer>
    </section>
<?php //} ?>

<!--Ajax Modal -->
<div class="modal fade" id="regularModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--<div class="loader"></div>-->
        </div>
    </div>
</div>
<!--Ajax Modal -->
<div class="exportModel">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--<div class="loader"></div>-->
        </div>
    </div>
</div>
</div>
</main>
<?php $this->load->view('Includes/footer_scripts');
/*$this->output->enable_profiler(TRUE);*/
?>
</body>
</html>
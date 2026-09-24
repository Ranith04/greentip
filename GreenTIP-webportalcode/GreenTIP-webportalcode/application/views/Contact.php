<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<section class="main-content">
    <div class="body-container">
	 <div class="container">
	  <!--<p class="text-center text-capitalize">contact us</p>-->
        
	  <div class="row" style="margin:0 2% 0 2%;">
         <h3 class="title" style="text-align: center;" ><font>Contact Us</font> </h3>
		    <!-- <div class="col-sm-4 col-md-4">
			  <div class="contact_side Address">
                   <div class="contact_icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                      <div class="addressFrame">
                          <?php echo ADMIN_CONTACT_ADDRESS;?>                                  
						</div>
                  </div>
			</div> -->
			<!-- <div class="col-sm-4 col-md-4">
			<div class="contact_side Address">
			     <?php if(ADMIN_CONTACT_NO!=''){?>
                            <div class="contact_side">
                                <div class="contact_icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                <div class="addressFrame">
                                    <?php echo ADMIN_CONTACT_NO;?>                                   </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
			  </div>
			</div> -->
			<!-- <div class="col-sm-4 col-md-4">
			    <div class="contact_side Address">
                            <div class="contact_icon"><i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="addressFrame">
                                <strong>-->
                                    <!--<a href="mailto:<?php /*echo ADMIN_CONTACT_EMAIL;*/?> "><?php /*echo ADMIN_CONTACT_EMAIL;*/?></a>-->
                                   <!-- <?php echo ADMIN_CONTACT_EMAIL;?>
                                </strong>
                            </div>
                        </div>
			</div> -->
	  </div>
	 </div>
        <div class="container">
            <div class="col-md-12">
                <div class="col-md-6">
                    <h4 class="title" style="text-align:left;"><font>Send us a message</font></h4>
                    <form class="form" method="post" name="Contact" id="Contact" enctype="multipart/form-data" action="">
                        <?php $userData = getSessionUserData('auth_user_data');?>
                        <div class="form-group">
                            <label for="name">Full Name*</label>
                            <input class="form-control gap" placeholder="Full Name" name="name" <?php echo set_value('name');?> value="<?php echo isset($userData['name']) ? $userData['name'] :'';?>">
                            <?php echo form_error('name'); ?>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address*</label>
                            <input class="form-control gap" placeholder="Email Address" type="email" required="" name="email" <?php echo set_value('email');?> value="<?php echo isset($userData['email']) ? $userData['email'] :'';?>">
                            <?php echo form_error('email'); ?>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number*</label>
                            <input class="form-control gap" placeholder="Phone Number" type="tel" name="phone" <?php echo set_value('contact_no');?>  value="<?php echo isset($userData['contact_no']) ? $userData['contact_no'] :'';?>">
                            <?php echo form_error('contact_no'); ?>
                        </div>
                        <div class="form-group">
                            <label for="message">Message*</label>
                            <textarea rows="4" cols="50" class="form-control gap" placeholder="Enter Message" required="" name="message"><?php echo set_value('message');?></textarea>
                            <?php echo form_error('message'); ?>
                        </div>
                        <div class="btn-wrapper">
                            <input type="submit" class="btn Cont_SubmitBtn" value="submit">
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">

              <div class="contact_side Address">
                   <div class="contact_icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                      <div class="addressFrame">
                          <?php echo ADMIN_CONTACT_ADDRESS;?>                                  
                        </div>
                 
            </div>
                    <div class="map">
                        <iframe width="523" height="240" id="gmap_canvas" src="https://maps.google.com/maps?q=F-374%20%26%20375%2C%20Sector%E2%80%9363%2C%20NOIDA%E2%80%93201%20301%20%2CIndia&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                    </div>

            </div>
        </div>
    </div><!-- body-container -->
</section>
<script src="<?php echo assets_url('js', 'authentication.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>

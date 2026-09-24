<?php $userData = (array)getSessionUserData('auth_user_data');
$UserDetail = array();
$userDashboard = '';
if (isset($userData) && !empty($userData)) {
    $UserDetail = $userData;
    $userDashboard = 'userDashboard';
}
$class = $this->router->fetch_class();
$method = $this->router->fetch_method();
if ($method == 'pageDetail' || $method == 'Contact' || $class == 'experts' || $class == 'faq' || $class == 'index') {
    $userDashboard = '';
}
?>

<section class="section-header <?php echo $userDashboard;?>" >
    <header class="is_header">
        <div class="container">
            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php if (empty($UserDetail)) { ?>
                        <a class="navbar-brand" href="<?php echo base_url(); ?>"> <img src="<?php echo assets_url('img', 'logo.png'); ?>" alt="GreenTip" class="responsive"> </a>
                    <?php } else { ?>
                        <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>"> <img src="<?php echo assets_url('img', 'logo.png'); ?>" alt="GreenTip" class="responsive"> </a>
                    <?php } ?>

                </div>
                <div class="collapse navbar-collapse mainmenu-area" id="myNavbar">
                    <div class="navv">
                        <?php if (empty($UserDetail['id']) || $method=='pageDetail' || $method=='Contact' || $class=='faq' || $class=='experts'  || $class=='index' || ((!empty($UserDetail['role_type'])) && ($UserDetail['role_type']=='1' || $UserDetail['role_type']=='2'))) { ?>
                        <ul class="nav navbar-nav main-menu">
                            <?php
                            if (!empty($UserDetail['id'])) { ?>
                                <li><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                            <?php } ?>
                                <li><a href="<?php echo base_url('page/about-us') ?>">About Us  </a></li>
                                <li><a href="<?php echo base_url('page/knowledge-center') ?>">Knowledge Center</a></li>
                                <li><a href="<?php echo base_url('faq') ?>">FAQs</a></li>
                                <li><a href="<?php echo base_url('contact') ?>">Contact Us</a></li>
                        </ul>
                        <?php } ?>
                        <?php if (empty($UserDetail['id'])) { ?>
                        <ul class="nav navbar-nav navbar-right">
                            <a href="javascript:void(0);" class="topnav-icons fa w3-right w3-bar-item w3-button" onclick="open_translate(this)"></a>
                            <li class="login">
                                <a title="Login?" href="javascript:void(0)" data-href="<?php echo base_url('ajax/login') ?>"  data-toggle="modal" class="loginModal">
                                    Login
                                </a>
                            </li>
                        </ul>
                        <?php } ?>
                        <?php
                        if (!empty($UserDetail['id'])) { ?>
                            <ul class="nav navbar-nav navbar-right loginUserInfo">
<!--                                 <li>
                                    <p><span>Welcome</span><span class="ActiveUser"><?php echo character_limiter($UserDetail['name'], 5); ?></span></p>
                                    <span ><a class="logoutlbl" href="<?php echo base_url('user/logout'); ?>">logout</a></span>
                                </li> -->
                                 <li>
                                    <p><span> </span> <span style="margin: 0%!important;     " class="ActiveUser"><?php
                                     //echo character_limiter($UserDetail['name'], 9);
                                    echo $UserDetail['name'];
                                      ?></span></p>
                                    <span ><a class="logoutlbl" href="<?php echo base_url('user/logout'); ?>">logout</a></span>
                                </li>
                                <li><?php if (!empty($UserDetail['image'])) { ?>
                                        <img src="<?php echo image_url('users', $UserDetail['image'], 128, 128); ?>" class="img-circle" style="width:50px;margin-right: 0px;">
                                    <?php }else{?>
                                        <img src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" class="img-circle" style="width:50px;margin-right: 0px;">
                                    <?php } ?>
                                </li>
                               <!-- <li>
                                    <span class="notiBackgound">
                                      <img src="<?php /*echo assets_url('img', 'bell_icon.png'); */?>" class="notificationIcon" style="width:17px;margin-right: 7px;">
                                      <span class="badge notificationNumber">0</span>
                                    </span>
                                </li>-->
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </nav>
            <?php if ($method=='pageDetail' || $method=='Contact' || $class=='faq' || $class=='index' || $class=='experts') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="banner-tag">
                        A place to <mark>Share Knowledge</mark> and have better </br> <mark>Understanding </mark> of the environmental aspects
                    </h4>
                </div>
                <div class="col-sm-8 banner-slide">
                    <ul class="slider list-unstyled">
                        <?php $sliders = getAllSliders();
                        if(!empty($sliders)){
                            foreach ($sliders as $slider){?>
                                <li>
                                    <img src="<?php echo assets_url('uploads/sliders', $slider['image']); ?>" alt="Slide1">
                                </li>
                        <?php }
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <?php } ?>
        </div><!--Container -->
        <style>
::placeholder {
  color: black!important;
 
}
.form-control {
   
    
    color: black!important;
    
}
body {
    
    color: black!important;
  
}
</style>
    </header>
</section>
<div class="clearfix"></div>
<?php $this->load->view('Includes/msg_alert'); ?>
<header id="header" class="app-header navbar" role="menu">
<!-- navbar header -->
<div class="navbar-header bg-dark">
    <button class="pull-right visible-xs dk" ui-toggle="show" target=".navbar-collapse">
        <i class="glyphicon glyphicon-cog"></i>
    </button>
    <button class="pull-right visible-xs" ui-toggle="off-screen" target=".app-aside" ui-scroll="app">
        <i class="glyphicon glyphicon-align-justify"></i>
    </button>
    <!-- brand -->
    <a href="<?php echo base_url('admin'); ?>" class="navbar-brand text-lt">
        <img alt="<?php echo ADMIN_COMPANY;?> Admin Login" class="thumb-xl" src="<?php echo assets_url('img','logo.png');?>">
    </a>
    <!-- / brand -->
</div>
<!-- / navbar header -->

<!-- navbar collapse -->
<div class="collapse pos-rlt navbar-collapse box-shadow bg-white-only">
<!-- buttons -->
<div class="nav navbar-nav hidden-xs">
    <a href="#" class="btn no-shadow navbar-btn" ui-toggle="app-aside-folded" target=".app">
        <i class="fa fa-dedent fa-fw text"></i>
        <i class="fa fa-indent fa-fw text-active"></i>
    </a>
</div>
<!-- / buttons -->


<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">
              <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm">
                <?php
                if(!empty($this->loggedInAdmin['image'])){
                    ?>
                    <img alt="Admin Profile Pic" class="img-full" src="<?php echo image_url('users',$this->loggedInAdmin['image'],50,50); ?>">
                <?php
                }else{
                    ?>
                    <img alt="Admin Profile Pic" class="img-full" src="<?php echo assets_url('grocery_crud','themes/bootstrap/img/a0.jpg'); ?>">
                <?php
                }
                ?>
                <i class="on md b-white bottom"></i>
              </span>
            <span class="hidden-sm hidden-md"><?php echo $this->loggedInAdmin['name']; ?></span> <b class="caret"></b>
        </a>



        <!-- dropdown -->
        <ul class="dropdown-menu animated fadeInRight w">
           <!-- <li>
                <a title="Admin Settings" href="<?php /*echo base_url('admin/profile'); */?>">
                    <span><i class="icon-user icon m-r-sm text-info-dker"></i></span> Profile
                </a>
            </li>-->
            <li>
                <a title="Admin Change Password" href="<?php echo base_url('admin/change-password'); ?>">
                    <span><i class="fa fa-eye icon m-r-sm text-success-dker"></i></span> Change Password</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo base_url('admin/logout'); ?>" title="Admin Logout"><span><i class="fa fa-power-off icon m-r-sm text-danger-dker"></i></span> Logout</a>
            </li>
        </ul>
        <!-- / dropdown -->
    </li>
</ul>
<!-- / navbar right -->
</div>
<!-- / navbar collapse -->
</header>
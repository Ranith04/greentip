<?php
$current_controller = $this->uri->segment(2);
$current_action = $this->uri->segment(3);
$admin_id = $this->loggedInAdmin['id'];
?>

<!-- aside -->
<aside id="aside" class="app-aside hidden-xs bg-dark">
    <div class="aside-wrap">
        <div class="navi-wrap">
            <!-- nav -->
            <nav ui-nav class="navi clearfix">

                <ul class="nav">

                    <li >
                        <a class="" title="Dashboard" href="<?php echo base_url('admin'); ?>">
                            <i class="icon-layers text-info-dker"></i>
                            <span></i> &nbsp;&nbsp;Dashboard</span>
                        </a>
                    </li>

                    <li <?php
                    if ($current_controller == 'categories' || $current_controller == 'Categories') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Question Category" href="<?php echo base_url('admin/categories/index/all'); ?>">
                            <i class="fa fa-list-alt text-danger-lter"></i><span title="Question Category">Question Category</span>
                        </a>
                    </li>

                    <li <?php
                    if ($current_controller == 'Industrial_categories' || $current_controller == 'industrial_categories') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Industrial Category" href="<?php echo base_url('admin/industrial_categories/index/all'); ?>">
                            <i class="fa fa-list-alt text-success-lter"></i><span>Industrial Category</span>
                        </a>
                    </li>

                    <li <?php
                    if ($current_controller == 'users' || $current_controller == 'Users') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Industrial Users" href="<?php echo base_url('admin/users/index/all'); ?>">
                            <i class="fa fa-user text-warning-lter"></i><span>Industrial Users</span>
                        </a>
                    </li>

                    <!--faqs Management-->

                    <li <?php
                    if ($current_controller == 'faqs' || $current_controller == 'Faqs') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Manage FAQs" href="<?php echo base_url('admin/faqs/index/all'); ?>">
                            <i class="fa fa-question-circle text-warning-dker"></i><span>Manage FAQs</span>
                        </a>
                    </li>

                    <!-- <li <?php
                    if ($current_controller == 'sliders' || $current_controller == 'Sliders') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a href="<?php echo base_url('admin/sliders/index/all'); ?>">
                            <i class="fa fa-sliders text-primary-lter"></i><span>Slider</span>
                        </a>
                    </li> -->

                    <li <?php
                    if ($current_controller == 'contact' || $current_controller == 'Contact') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Contact" href="<?php echo base_url('admin/contact/index/all'); ?>">
                            <i class="fa fa-phone text-warning-lter"></i><span>Contact</span>
                        </a>
                    </li>


                    <!--email-template Management-->


                    <li <?php
                    if ($current_controller == 'email-template' || $current_controller == 'Email-template') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a href="<?php echo base_url('admin/email-template/index/all'); ?>">
                            <i title="Manage Template" class="fa fa-envelope text-info-lter"></i><span>Manage Template</span>
                        </a>
                    </li>

                    <!--<li <?php /*echo in_array($current_controller, array('newsletters')) ? 'class="active"' : ''; */ ?>>
                        <a href class="auto">
                          <span class="pull-right text-muted">
                            <i class="fa fa-fw fa-angle-right text"></i>
                            <i class="fa fa-fw fa-angle-down text-active"></i>
                          </span> <i class="fa fa-newspaper-o icon text-warning"></i> <span>Newsletters</span> </a>
                        <ul class="nav nav-sub dk">
                            <li>
                                <a href="<?php /*echo base_url('admin/newsletters/index/all'); */ ?>"><span><i class="fa fa-user text-warning-lter"></i> Manage Newsletters</span> </a>
                            </li>
                            <li>
                                <a href="<?php /*echo base_url('admin/newsletters/send'); */ ?>"><span><i class="fa fa-list text-warning-lter"></i> Send Newsletters</span> </a>
                            </li>
                        </ul>
                    </li>-->


                    <!--Subscribers Management-->

                    <li <?php
                    if ($current_controller == 'subscribers' || $current_controller == 'Subscribers') {
                        echo ' class="active"';
                    };
                    ?>>
                        <a title="Subscriber" href="<?php echo base_url('admin/subscribers/index/all'); ?>">
                            <i class="fa fa-users text-danger-lter"></i><span>Subscriber</span>
                        </a>
                    </li>


                    <li<?php if ($current_controller == 'settings' || $current_controller == 'pages') {
                        echo ' class="active"';
                    }; ?>>
                        <a href class="auto">
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                            <i class="icon-wrench icon text-success"></i>
                            <span>Website Settings</span>
                        </a>
                        <ul class="nav nav-sub dk">
                            <li>
                                <a href="<?php echo base_url('admin/settings/index/all'); ?>">
                                    <span><i class="fa fa-database text-success"></i> Manage Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('admin/pages/index/all'); ?>">
                                    <span><i class="fa fa-file-text text-success"></i> Manage Pages</span>
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</aside>
<!-- / aside -->
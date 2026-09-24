<?php

//echo "<pre>";
//print_r($_SERVER);
$class = $this->router->fetch_class();
$method = $this->router->fetch_method();
//echo $method; die;
$active = '';
$style = '';
$desbordCard = "";
$manage_usersActive = '';
$manage_end_usersActive = '';
$user_icon = 'user-management_green.png';
$expert_user_icon = 'expert_user_green.png';
$end_user_icon = 'enduser_green.png';
$end_user_iconreport = 'report_green_icon.png';


if ($class == 'user' && ($method == 'manage_users' || $method == 'manage_end_users' || $method == 'add_user' || $method == 'edit_user' ||  $method =='view' || $method == 'add_enduser' || $method == 'edit_enduser' || $method =='view_end_users')) {
    $active = 'active';
    $style = "display:block";
    $desbordCard = "desbordCard";
    if($method == 'manage_users' || $method == 'add_user' || $method == 'edit_user' || $method == 'view'){
        $manage_usersActive = 'active'  ;
        $expert_user_icon = 'expert_user_white.png';
    }
    if($method == 'manage_end_users' || $method == 'add_enduser' || $method == 'edit_enduser' || $method == 'view_end_users'){
        $manage_end_usersActive = 'active'  ;
        $end_user_icon = 'enduser-white.png';
    }
    
}
if($method == 'mdNewQueriesReports' ||$method == 'mdPendingQueriesReports'|| $method == 'mdClosedQueriesReports'|| $method == 'mdTotalQueriesReports' || $method == 'reports'){
       $report = 'desbordCard'  ;
        $end_user_iconreport = 'report_white_icon.png';
  
}
$dashboard_icon = 'dashboard_green_icon.png';
$inbox_icon = 'inbox_green_icon.png';
if ($class == 'user' && $method == 'dashboard') {
    $dashboard_icon = 'dashboard_white_icon.png';
    $inbox_icon = 'inbox_white_icon.png';
}
$profile_icon = 'profile_green_icon.png';
if ($class == 'user' && $method == 'profile') {
    $profile_icon = 'profile_white_icon.png';
}
$setting_icon = 'setting_green_icon.png';
if ($class == 'user' && $method == 'change_password') {
    $setting_icon = 'setting_white_icon.png';
}
$bulk_email_icon = 'bulk-notification_green.png';
if ($class == 'user' && $method == 'bulk_email_notification') {
    $bulk_email_icon = 'bulk-notification-white.png';
}

?>
<!--1 for Expert 2 for End User 0 for super admin-->
<?php if($this->UserDetail['role_type']=='1' || $this->UserDetail['role_type']=='2' || $this->UserDetail['role_type']=='3'){?>
    <div class="col-sm-3 col-md-3 col-lg-3 leftside-bar">
        <div class="media desbordCard_Tnasp">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $dashboard_icon); ?>" class="media-object" style="width:30px"></div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></h4>
            </div>
        </div>

      <?php if($this->UserDetail['role_type']=='3') { ?>
         <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $bulk_email_icon); ?>" class="media-object" style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading"> <a href="<?php echo base_url('bulk-email-notification'); ?>">Bulk Email</a></h4>
            </div>
        </div>
         <div  class="media desbordCard_Tnasp  <?php echo $report; ?>">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $end_user_iconreport); ?>" class="media-object"
                     style="width:30px">
            </div>
            <div class="media-body">
            <h4 class="media-heading "><a  href="<?php echo base_url('reports'); ?>">Reports</a></h4>
            </div>
        </div>


        <div class="media desbordCard_Tnasp <?php /*echo $desbordCard; */?>">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $user_icon); ?>" class="media-object" style="width:30px">
                <div class="sidenav sidenavv">
                    <button class="dropdown-btn <?php echo $active;?>">
                        <h4 class="media-heading">Others<i class="fa fa-angle-down"></i></h4>
                    </button>

                    <div class="dropdown-container" style="<?php echo $style;?>">
                        <a href="<?php echo base_url('profile'); ?>" class="<?php echo $manage_usersActive; ?>"><img src="<?php echo assets_url('img', $profile_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">Edit Profile</span></a>
                        <a href="<?php echo base_url('user/manage_users/all');?>" class="<?php echo $manage_usersActive; ?>"><img src="<?php echo assets_url('img', $expert_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">Expert Management</span></a>
                        <a href="<?php echo base_url('user/manage_end_users/all');?>" class="<?php echo $manage_end_usersActive; ?>"><img src="<?php echo assets_url('img', $end_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">End User Management</span></a>

                        <a href="<?php echo base_url('change-password'); ?>" class="<?php echo $manage_end_usersActive; ?>"><img src="<?php echo assets_url('img', $setting_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">Change Password</span></a>
                    </div>
                </div>
            </div>
        </div>

         <!--  <div class="media desbordCard_Tnasp <?php /*echo $desbordCard; */?>">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $user_icon); ?>" class="media-object" style="width:30px">
                <div class="sidenav sidenavv">
                    <button class="dropdown-btn <?php echo $active;?>">
                        <h4 class="media-heading">User Management <i class="fa fa-angle-down"></i></h4>
                    </button>
                    <div class="dropdown-container" style="<?php echo $style;?>">
                        <a href="<?php echo base_url('user/manage_users/all');?>" class="<?php echo $manage_usersActive; ?>"><img src="<?php echo assets_url('img', $expert_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">Expert Management</span></a>
                        <a href="<?php echo base_url('user/manage_end_users/all');?>" class="<?php echo $manage_end_usersActive; ?>"><img src="<?php echo assets_url('img', $end_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">End User Management</span></a>
                    </div>
                </div>
            </div>
        </div>  -->
      





        
    <?php } ?>






       <!--  <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $profile_icon); ?>" class="media-object" style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo base_url('profile'); ?>">Profile</a></h4>
            </div>
        </div>
        <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $setting_icon); ?>" class="media-object" style="width:30px"></div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo base_url('change-password'); ?>">Settings</a></h4>
            </div>
        </div> -->
    </div>
<?php }
else{?>
    <?php
    /*-----Get inbox count-----*/
    $condition = array('q.status!=' => '1');
    $result = $this->user_model->getNewQuery('b_queries', '0', '', '*', $condition);
    $total1 = $result['total_rows'];
    $result1 = $this->user_model->getRespondQuery('b_query_assign', '', '', '*', $condition);
    $total2 = $result1['total_rows'];
    $condition2 = array('qa.status' => '1', 'review_status' => '1');
    $result2 = $this->user_model->getRespondedQuery('b_query_assign', '', '', '*', $condition2);
    $total3 = $result2['total_rows'];
    ?>
    <div class="col-sm-3 col-md-3 col-lg-3 leftside-bar">

        <div class="media desbordCard_Tnasp">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $inbox_icon); ?>" class="media-object" style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading inboxT"><a href="<?php echo base_url('dashboard'); ?>">Inbox</a></h4>
                <span class="inboxCount"><?php echo  $total1+$total2+$total3;?></span>
            </div>

        </div>

        <div class="media desbordCard_Tnasp <?php /*echo $desbordCard; */?>">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $user_icon); ?>" class="media-object" style="width:30px">
                <div class="sidenav sidenavv">
                    <button class="dropdown-btn <?php echo $active;?>">
                        <h4 class="media-heading">User Management <i class="fa fa-angle-down"></i></h4>
                    </button>
                    <div class="dropdown-container" style="<?php echo $style;?>">
                        <a href="<?php echo base_url('user/manage_users/all');?>" class="<?php echo $manage_usersActive; ?>"><img src="<?php echo assets_url('img', $expert_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">Expert Management</span></a>
                        <a href="<?php echo base_url('user/manage_end_users/all');?>" class="<?php echo $manage_end_usersActive; ?>"><img src="<?php echo assets_url('img', $end_user_icon); ?>"  style="width:20px;"> <span style="margin-left: 4px;">End User Management</span></a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php /*echo assets_url('img', 'report_green_icon.png'); */?>" class="media-object"
                     style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading">Report</h4>
            </div>
        </div>-->

        <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $profile_icon); ?>" class="media-object" style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading"> <a href="<?php echo base_url('profile'); ?>">Profile</a></h4>
            </div>
        </div>
        <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $bulk_email_icon); ?>" class="media-object" style="width:30px">
            </div>
            <div class="media-body">
                <h4 class="media-heading"> <a href="<?php echo base_url('bulk-email-notification'); ?>">Bulk Email</a></h4>
            </div>
        </div>
        <div class="media desbordCard_Tnasp ">
            <div class="media-left">
                <img src="<?php echo assets_url('img', $setting_icon); ?>" class="media-object" style="width:30px"></div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo base_url('change-password'); ?>">Settings</a></h4>
            </div>
        </div>
    </div>
<?php 



} ?>
<script type="text/javascript">
    jQuery(function () {
        var url = window.location.pathname;
        var urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");
        jQuery('.dropdown-container a,h4.media-heading a').each(function () {
            if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
                console.log(url);
                if (url == '/') {
                    jQuery(this).removeClass('active');
                }
                else {
                    var projectFolder = '<?php echo $this->config->item('project_name') == ''  ? '' : $this->config->item('project_name'); ?>';
                    if(url=='/'+projectFolder+'user/manage_users/all' || url=='/'+projectFolder+'user/manage_end_users/all'){


                    }else{
                       
                        jQuery(this).parents('.desbordCard_Tnasp').addClass('desbordCard');
                    }
                }
            }
        });
    });
</script>
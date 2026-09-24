<?php
/**
 * Created by PhpStorm.
 * User: abhishek@SYON.COM
 * Date: 3/10/16
 * Time: 10:56 AM
 */
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="wrapper-md">
            <?php
            $this->load->view('includes/msg_alert'); ?>

                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <h4><i class="fa fa-plug"></i> Edit Settings</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" method="post" name="SettingsEdit" id="SettingsEdit" enctype="multipart/form-data" action="">
                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="key"><?php echo  $dbdata['option_name']?></label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control" name="value" id="value" value="<?php echo ($dbdata['option_value'])?$dbdata['option_value']:set_value('option_value'); ?>">
                                                    <?php echo form_error('email_subject'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->
                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/settings')); ?>" class="btn btn-default">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer'); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script type="text/javascript">
    SyonApp.setPage('SettingsEdit');
    SyonApp.init();
</script>
</body>
</html>
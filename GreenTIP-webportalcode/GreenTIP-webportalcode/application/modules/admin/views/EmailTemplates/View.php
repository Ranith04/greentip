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
            <div class="bg-light lter b-b wrapper-md">
                <a href="<?php echo base_url() ;?>admin/email-template/index/all" class="btn btn-primary" style="float: right;">
                    <i class="fa fa-arrow-left"></i> Go Back
                </a>
                <h1 class="m-n font-thin h3">Template Manager</h1>
            </div>
            <div class="wrapper-md">
                <div class="row">
                    <?php
                    $this->load->view('includes/msg_alert'); ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">
                                <div class="panel-heading font-bold">
                                <div class="row"><span class="col-md-6">
                                    Template View
                                </span>
                                <span class="col-md-6 text-right">
                                    <a title="Edit" href="<?php echo base_url(); ?>admin/email-template/edit/<?php echo $dbdata['id'];?>" class="btn btn-rounded btn-sm btn-icon btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </span></div>

                                </div>
                            </div>
                            <div class="panel-body">
                                <form method="post" id="view_form" name="view_form"  class="form-horizontal">
                                    <div class="tab-pane">
                                        <div class="table-responsive">
                                            <table class="table table-striped b-t b-light">
                                                <tr>
                                                    <td>Email Subject</td>
                                                    <td><?php if($dbdata['email_subject']){echo $dbdata['email_subject'];}else{ echo "N/A";}?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email Keywords</td>
                                                    <td><?php if($dbdata['email_keywords']){echo $dbdata['email_keywords'];}else{ echo "N/A";}?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email Content</td>
                                                    <td><?php if($dbdata['email_content']){echo $dbdata['email_content'];}else{ echo "N/A";}?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
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
</body>
</html>
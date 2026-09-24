<?php
/**
 * @var $nameSingular string
 * @var $namePlural string
 * @var $nameSession string
 * @var $nameClass string
 * @var $dbdata array
 */
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="bg-light lter b-b wrapper-md">
                <a href="<?php echo base_url() ;?>admin/faqs/index/all" class="btn btn-primary" style="float: right;">
                    <i class="fa fa-arrow-left"></i> Go Back
                </a>
                <h1 class="m-n font-thin h3"><?php echo $namePlural ?> Manager</h1>
            </div>
            <div class="wrapper-md">
                <div class="row">
                    <?php
                    $this->load->view('includes/msg_alert'); ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">
                                <div class="panel-heading font-bold">
                                    <div class="panel-heading font-bold">
                                        <div class="row"><span class="col-md-6">
                                     <?php echo $nameSingular ?> View
                                </span>
                                <span class="col-md-6 text-right">
                                    <a title="Edit" href="<?php echo base_url("admin/{$nameClass}/edit/{$dbdata['id']}"); ?>" class="btn btn-rounded btn-sm btn-icon btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </span></div>

                                    </div>
                                </div>
                               </div>
                            <div class="panel-body">
                                <form method="post" id="view_form" name="view_form"  class="form-horizontal">
                                    <div class="tab-pane">
                                        <div class="table-responsive">
                                            <table class="table table-striped b-t b-light">

                                                <tr>
                                                    <td>Question</td>
                                                    <td><?php echo $dbdata['question'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Answer</td>
                                                    <td><?php echo $dbdata['answer'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        <?php if ($dbdata['status'] == '1') { ?>
                                                            <span class="label bg-success" title="Active">Active</span>
                                                        <?php } else if ($dbdata['status'] == '0') { ?>
                                                            <span class="label bg-danger" title="Pending">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Added On</td>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata['added_on']);?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <form id="getTransaction" name="getTransaction">
                                    <input type="hidden" name="FormData[equal][b_user_id]" id="equalId" value="">
                                    <input type="hidden" name="FormData[form_name]" id="form_name" value="" />
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
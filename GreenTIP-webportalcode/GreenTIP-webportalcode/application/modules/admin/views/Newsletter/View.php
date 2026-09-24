<?php
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="bg-light lter b-b wrapper-md">
                <h1 class="m-n font-thin h3">Newsletter Manager <a href="<?php echo base_url('admin/newsletters'); ?>" class="btn btn-primary backbtnLink">Back</a></h1>
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
                                     Newsletter View
                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form method="post" id="view_form" name="view_form" class="form-horizontal">
                                    <div class="tab-pane">
                                        <div class="table-responsive">
                                            <table class="table table-striped b-t b-light">
                                                <tbody>
                                                <tr>
                                                    <td>Send To</td>
                                                    <td><?php if ($dbdata['sent_to']) {
                                                            echo ucfirst($dbdata['sent_to']);
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>

                                                </tr>
                                                <tr>
                                                    <td>Subject</td>
                                                    <td><?php if ($dbdata['subject']) {
                                                            echo $dbdata['subject'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>

                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top">Message</td>
                                                    <td><?php if ($dbdata['message']) {
                                                            echo $dbdata['message'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Sent On</td>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata['created_at']); ?></td>
                                                </tr>
                                                </tbody>
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
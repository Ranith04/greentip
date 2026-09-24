<?php
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="bg-light lter b-b wrapper-md">
                <h1 class="m-n font-thin h3">Contact Manager</h1>
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
                                     Contact View
                                </span>
                                <span class="col-md-6 text-right">
                                    <a title="Edit"
                                       href="<?php echo base_url(); ?>admin/contact/reply/<?php echo $dbdata['id']; ?>"
                                       class="btn btn-rounded btn-sm btn-icon btn-info">
                                        <i class="fa fa-mail-reply"></i>
                                    </a>
                                </span></div>
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
                                                    <td>Name</td>
                                                    <td><?php echo ucfirst(strtolower($dbdata['name'])); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td>
                                                        <a href="mailto:<?php echo $dbdata['email']; ?>"><?php if ($dbdata['email']) {
                                                            echo $dbdata['email'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><?php if ($dbdata['contact_no']) {
                                                            echo $dbdata['contact_no'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>

                                                </tr>
                                                <tr>
                                                    <td>Message</td>
                                                    <td><?php if ($dbdata['message']) {
                                                            echo $dbdata['message'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>

                                                <?php
                                                if (!empty($replydata)) {
                                                    foreach ($replydata as $reply) {
                                                        ?>
                                                        <tr>
                                                            <td>Reply
                                                                on: <?php echo date('d M, Y', $reply->replied_on); ?></td>
                                                            <td><?php echo($reply->reply_msg); ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td>Added On</td>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata['added_on']); ?></td>
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
<?php
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <!-- BREADCRUMBS -->
            <ul class="breadcrumb">
                <li><i class="fa fa-home"></i> <a href="<?php echo base_url('admin'); ?>">Home </a></li>
                <li><?php echo $pageBreadCrumbs; ?></li>
            </ul>
            <!-- /BREADCRUMBS -->
            <div class="bg-light lter b-b wrapper-md usermanger">
			<div class="row">
			<div class="col-md-10">
			<h1 class="m-n font-thin h3 ">User Manager</h1>
			</div>
			<div class="col-md-2">
			<a href="<?php echo base_url(); ?>admin/users/index/all" class="btn btn-primary user_Manager" >
                    <i class="fa fa-arrow-left"></i> Go Back
                </a>
			</div>
			</div>
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
                                     User View

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
                                                <tr>
                                                    <th>Category ID</th>
                                                    <td><?php if ($dbdata['category_name']) {
                                                            echo $dbdata['category_name'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Company Name</th>
                                                    <td><?php if ($dbdata['company_name']) {
                                                            echo $dbdata['company_name'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Person</th>
                                                    <td><?php if ($dbdata['contact_person']) {
                                                            echo $dbdata['contact_person'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>
                                                        <a href="mailto:<?php echo $dbdata['email']; ?>"><?php if ($dbdata['email']) {
                                                            echo $dbdata['email'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Alternative Email</th>
                                                    <td>
                                                        <a href="mailto:<?php echo $dbdata['alternative_email']; ?>"><?php if ($dbdata['alternative_email']) {
                                                            echo $dbdata['alternative_email'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>

                                                <tr>
                                                    <th>CONTACTNO</th>
                                                    <td><?php if ($dbdata['contact_no']) {
                                                            echo $dbdata['contact_no'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Alternative NO</th>
                                                    <td><?php if ($dbdata['alter_contact_no']) {
                                                            echo $dbdata['alter_contact_no'];
                                                        } else {
                                                            echo "N/A";
                                                        } ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td><?php
                                                        if ($dbdata['status'] == '1') {
                                                            echo '<span title="Active" class="label bg-success">Active</span>';
                                                        }else{
                                                            echo '<span title="Inactive" class="label bg-warning">Inactive</span>';
                                                        } ?></td>
                                                </tr>

                                                <tr>
                                                    <th>DATE OF REGISTRATION</th>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata['created_on']); ?></td>
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
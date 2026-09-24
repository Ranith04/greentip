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
<!-- BREADCRUMBS -->
<ul class="breadcrumb">
<li><i class="fa fa-home"></i> <a href="<?php echo base_url('admin'); ?>">Home </a></li>
<li><?php echo $pageBreadCrumbs; ?></li>
</ul>
<!-- /BREADCRUMBS -->
<div class="wrapper-md">
<?php
$this->load->view('includes/msg_alert'); ?>

<div class="panel panel-default">
<div class="panel-heading font-bold">
<h4><i class="fa fa-plug"></i> Add User</h4>
</div>
<div class="panel-body">
<div class="row">
<div class="col-md-12">
<div class="row wrapper">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form class="form-horizontal" method="post" name="UserAdd" id="UserAdd" enctype="multipart/form-data" action="">
            <!-- Form Group Start -->

            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserFullName">Category</label>
                <div class="col-sm-9">
                    <select name="category_id" class="form-control">
                        <option value="">-Select Category-</option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo isset($_POST['category_id']) && $_POST['category_id']==$category['id'] ? 'selected' : ''; ?>><?php echo $category['cat_name']; ?></option>
                            <?php }
                        } ?>
                    </select>
                    <?php echo form_error('company_name'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

            <!-- Form Group End -->

            <!-- Form Group Start -->

            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserFullName">Company Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="company_name" value="<?php echo set_value('company_name'); ?>">
                    <?php echo form_error('company_name'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

            <!-- Form Group End -->

            <!-- Form Group Start -->

            <!-- <div class="form-group">
                <label class="col-sm-3 control-label" for="UserFullName">Contact Person</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="contact_person" value="<?php echo set_value('contact_person'); ?>">
                    <?php echo form_error('contact_person'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div> -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserFirstName">First Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="first_contact_person" value="<?php echo set_value('first_contact_person'); ?>">
                    <?php echo form_error('first_contact_person'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserMiddleName">Middle Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="middle_contact_person" value="<?php echo set_value('middle_contact_person'); ?>">
                    <?php echo form_error('middle_contact_person'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserLastName">Last Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="last_contact_person" value="<?php echo set_value('last_contact_person'); ?>">
                    <?php echo form_error('last_contact_person'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <!-- Form Group End -->

            <!-- Form Group Start -->

            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserEmail">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="email" id="UserEmail" value="<?php echo set_value('email'); ?>">
                    <?php echo form_error('email'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

            <!-- Form Group End -->

            <!-- Form Group Start -->

            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserEmail">Alternative Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="alternative_email" id="alternative_email" value="<?php echo set_value('alternative_email'); ?>">
                    <?php echo form_error('alternative_email'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

            <!-- Form Group End -->

            <!-- Form Group Start -->

            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserPhone">Contact Number</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control number" name="phone_number" value="<?php echo set_value('phone_number'); ?>">
                    <?php echo form_error('phone_number'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="UserPhone">Alternative Contact Number</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control number" name="alter_phone_number" value="<?php echo set_value('alter_phone_number'); ?>">
                    <?php echo form_error('alter_phone_number'); ?>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

            <!-- Form Group End -->

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-3">
                    <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-default">Cancel</a>
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
<script src="<?php echo assets_url('grocery_crud','themes/bootstrap/bower_components/bootstrap-filestyle/src/bootstrap-filestyle.js'); ?>"></script>
<script type="text/javascript">
SyonApp.setPage('UserAdd');
SyonApp.init();
</script>
</body>
</html>
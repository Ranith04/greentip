<?php $this->load->view('includes/header_script', $data);
$admin_id = $this->loggedInAdmin['id'];
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">

        <div class="app-content-body ">

            <div class="bg-light lter b-b wrapper-md">
                <h1 class="m-n font-thin h3">Dashboard</h1>
            </div>

            <div class="hbox hbox-auto-xs hbox-auto-sm">
                <div class="col">
                    <div class="wrapper-md">
                        <div class="row">
                            <?php $this->load->view('includes/msg_alert'); ?>
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row row-sm text-center">
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-success item"
                                           href="<?php echo base_url('admin/categories/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalCategories; ?></span>
                                            <span class="text-muted text-xs">Total Question Category</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-warning item"
                                           href="<?php echo base_url('admin/industrial_categories/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalIndustrialCategories; ?></span>
                                            <span class="text-muted text-xs">Total Industrial Category</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-primary item"
                                           href="<?php echo base_url('admin/users/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalIndustrialUsers; ?></span>
                                            <span class="text-muted text-xs">Total Industrial Users</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-info item"
                                           href="<?php echo base_url('admin/faqs/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalFaq; ?></span>
                                            <span class="text-muted text-xs">Total FAQ</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-black item"
                                           href="<?php echo base_url('admin/contact/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalContacts; ?></span>
                                            <span class="text-muted text-xs">Total Contact</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3">
                                        <a class="block panel padder-v bg-danger item"
                                           href="<?php echo base_url('admin/sliders/index/image/all'); ?>">
                                            <span class="text-white font-thin h1 block"><?= $TotalSliders; ?></span>
                                            <span class="text-muted text-xs">Total Slider</span>
                                            <span class="top text-left"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer', $data); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
</body>
</html>
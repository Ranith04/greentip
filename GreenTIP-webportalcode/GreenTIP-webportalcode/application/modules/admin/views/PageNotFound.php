<?php $this->load->view('includes/header_script', $data); ?>
<div class="app app-header-fixed  ">
    <div class="container w-xxl w-auto-xs">
        <div class="text-center m-b-lg">
            <h1 class="text-shadow text-white">404</h1>
        </div>
        <div class="list-group bg-info auto m-b-sm m-b-lg">
            <a href="<?php echo base_url('admin'); ?>" class="list-group-item">
                <i class="fa fa-chevron-right text-muted"></i>
                <i class="fa fa-fw fa-mail-forward m-r-xs"></i> Go to application
            </a>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
</body>
</html>
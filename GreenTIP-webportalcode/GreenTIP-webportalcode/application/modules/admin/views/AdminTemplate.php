<?php $this->load->view('includes/header_script', $css_files); ?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <?php
            $this->load->view('includes/msg_alert');
            echo $output;
            ?>
        </div>
    </div>
    <?php $this->load->view('includes/footer', $js_files); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
</body>
</html>
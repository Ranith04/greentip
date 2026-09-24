<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 saved_search">
                    <i class="fa  fa-exclamation-circle fa-4x"></i>
                    <h3 style="margin-top:10px;">Sorry, that page doesn’t exist!</h3>
                    <a class="btn btn-companies" href="<?php echo base_url(); ?>">Go to home</a>
                </div>
            </div>
        </div>
    </div><!-- body-container -->
</section>
<?php
$this->load->view('Includes/footer');
?>
<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<style type="text/css">
    .main-content .error {
        color: #0ea543;
        font-size: 128px;
        font-weight: 300;
        /* letter-spacing: -45px; */
        line-height: 128px;
        margin-bottom: 20px;
        margin-top: 0;
        position: relative;
        right: 40%;
        text-align: right;
        top: 57px;
    }

</style>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <div class="row">

                <div class="col-sm-12 text-center">
                    <div class="error">
                        404
                    </div>
                    <div class="wrapper-page" style="margin-top:60px;">
                        <h2 class="text-uppercase text-danger">Page Not Found</h2>
                        <p>
                            Sorry, but the page you're looking for has not been found<br>
                            Try checking the URL for errors, <a href="<?php echo base_url(); ?>">goto home</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- body-container -->
</section>
<?php
$this->load->view('Includes/footer');
?>

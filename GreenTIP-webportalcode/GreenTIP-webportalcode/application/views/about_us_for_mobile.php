<?php
$this->load->view('Includes/header_script');
?>
<section class="section-header">
    <header>
        <div class="container">
            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                        <a class="navbar-brand" href="javascript:void();"> <img src="<?php echo assets_url('img', 'logo.png'); ?>" alt="GreenTip" class="responsive"> </a>
                </div>
                
            </nav>
        </div><!--Container -->
    </header>
</section>
<br><br>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <?php echo $dbdata['content']; ?>
        </div>
    </div><!-- body-container -->
</section>

<?php //$this->load->view('Includes/footer');?>

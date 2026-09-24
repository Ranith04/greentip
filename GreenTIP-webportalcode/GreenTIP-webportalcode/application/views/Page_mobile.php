<?php
$this->load->view('Includes/header_script');
/*$this->load->view('Includes/header');*/
?>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <?php if($slug=='about-us'){?>
                <!--<h3 class="title">About<font> <font>Us</font></font></h3>-->
            <?php }else{?>
                <h3 class="title"><font><?php echo $dbdata['title']; ?></font></h3>
            <?php } ?>
            <?php echo $dbdata['content']; ?>
            <?php if($slug=='about-us'){?>
            
            <?php } ?>
        </div>
    </div><!-- body-container -->
</section>
<?php if($slug=='about-us11'){?>
    <section class="query_section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <div class="ask_query">
                        <h2>Ask Your Query</h2>
                        <p>(Real Estate, Construction Others)</p>

                        <div class="arrow_image">
                            <!--<button type="button" class="btn btn-postquery">Post your Query</button>-->
                            <a class="btn btn-postquery" href="<?php echo base_url('dashboard'); ?>">Post Your Query</a>
                            <img src="<?php echo assets_url('img', 'arrow.png'); ?>" alt="">
                        </div>
                    </div>

                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="gate_imgs">
                        <!-- <img src="images/door.png" alt=""> -->
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="divider">
        &nbsp;
    </div>
    <style type="text/css">
        .panel-default>.panel-heading a[aria-expanded=false]:after {
            content: none;
        }
        .panel-default>.panel-heading a[aria-expanded=true]:after {
            content: none;
        }
    </style>
<?php } ?>
<!-- <?php $this->load->view('Includes/footer');?> -->
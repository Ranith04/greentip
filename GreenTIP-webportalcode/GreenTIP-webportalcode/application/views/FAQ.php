<?php $this->load->view('Includes/header_script');
$this->load->view('Includes/header'); ?>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <div class="row">
                <h3 class="title"><font>FAQs</font></h3>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php if (!empty($dbdata)) {
                        foreach ($dbdata as $key => $data) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collapseOne">
                                            <?php echo $data['question']; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <?php echo $data['answer']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="alert alert-danger">No Faq's found here</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div><!-- body-container -->
</section>
<?php $this->load->view('Includes/footer'); ?>


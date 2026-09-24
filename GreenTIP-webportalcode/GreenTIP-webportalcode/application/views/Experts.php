<?php $this->load->view('Includes/header_script');
$this->load->view('Includes/header'); ?>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <div class="row">
                <h3 class="title"><font>Experts</font></h3>
                <div class="expert_container">
                    <?php if(!empty($dbdata)) {
                        foreach ($dbdata as $data){?>
                            <div class="col-sm-6 col-md-6 col-lg-3 outer_block">
                                <div class="col-sm-12 expert_block">
                                    <div class="padding">
                                        <div class="media">
                                            <div class="media-left">
                                                <?php if (!empty($data['image'])) { ?>
                                                    <img src="<?php echo image_url('users', $data['image'], 60, 60); ?>" class="media-object  circle-avatar nks-img" style="width:60px">
                                                <?php }else{?>
                                                    <img src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" class="media-object circle-avatar nks-img" style="width:60px">
                                                <?php } ?>
                                                <h4 class="media-heading"><?php echo character_limiter($data['name'],20); ?></h4>
                                                <h3><span>Company Name:</span><?php echo $data['company_name']!='' ? $data['company_name'] : '--'; ?></h3>
                                            </div>
                                            <div class="media-body">
                                                <p><span>Occupation:</span> <?php echo $data['occupation']!='' ? $data['occupation'] : '--'; ?></p>
                                                <p><span>Education Qualification:</span> <?php echo $data['education_qualification']!='' ? $data['education_qualification'] : '--'; ?></p>
                                                <p><span>Expertise Field:</span> <?php echo $data['expertise_field']!='' ? $data['expertise_field'] : '--'; ?></p>
                                            </div>
                                        </div>
                                        <p class="descrip">
                                            <?php
                                            $string = $data['description'];
                                            if (strlen($string) > 150) {
                                                $trimstring = substr($string, 0, 150). ' <a href="javascript:void(0)" data-href="'.base_url('ajax/view_expert_detail/'.$data['id']).'"  data-toggle="modal" class="assignExpertModal">readmore...</a>';
                                            } else {
                                                $trimstring = $string;
                                            }
                                            echo $trimstring; ?>
                                        </p>
                                    </div>
                                    <div class="icon-bar">
                                        <p><a href="<?php echo $data['linkdin']!='' ? $data['linkdin'] : '#'; ?>" target="_blank" class="first"><i class="fa fa-linkedin-square"></i>Linkedin</a></p>
                                        <p><a href="<?php echo $data['facebook']!='' ? $data['facebook'] : '#'; ?>" target="_blank" class="second"><i class="fa fa-facebook-square"></i>Facebook</a></p>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                    else{?>
                        <div class="alert alert-danger">No experts found here.</div>
                    <?php }?>


                </div>
                <div class="text-center"><?php echo isset($pagination) && $pagination!='' ? $pagination : '';?></div>
            </div>
        </div>
    </div><!-- body-container -->
</section>
<?php $this->load->view('Includes/footer'); ?>


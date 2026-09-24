<div class="modal-body">
    <div class="col-sm-12 expert_block_modal">
        <div class="padding">
            <div class="media">
                <div class="media-left">
                    <?php if (!empty($dbdata['image'])) { ?>
                        <img src="<?php echo image_url('users', $dbdata['image'], 60, 60); ?>" class="media-object  circle-avatar nks-img" style="width:60px">
                    <?php }else{?>
                        <img src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" class="media-object nks-img circle-avatar" style="width:60px">
                    <?php } ?>

                </div>
                <div class="media-body">
                    <h3 class="media-heading"><?php echo character_limiter($dbdata['name'],20); ?></h3>
                    <p><b>Company Name:</b><?php echo $dbdata['company_name']!='' ? $dbdata['company_name'] : '--'; ?></p>
                    <p><b>Occupation: </b> <?php echo $dbdata['occupation']!='' ? $dbdata['occupation'] : '--'; ?></p>
                    <p><b>Education Qualification: </b> <?php echo $dbdata['education_qualification']!='' ? $dbdata['education_qualification'] : '--'; ?></p>
                    <p><b>Expertise Field: </b> <?php echo $dbdata['expertise_field']!='' ? $dbdata['expertise_field'] : '--'; ?></p>
                </div>
            </div>
            <p>
                <?php
                $string = $dbdata['description'];
                echo $string; ?>
            </p>
            <div class="clearfix">
                <div class="row">
                    <div class="pull-right">
                        <button type="button" class="btn  transprantBtn" data-dismiss="modal">Cancel</button>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

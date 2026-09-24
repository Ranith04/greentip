<div class="modal-body">
    <div class="exportTextAria" style="border:none;">
        <div class="exportMsgCard desCard">
            <div class="topheader">
                <div class="media-left">
                    <?php if (!empty($dbdata['user_image'])) { ?>
                        <img src="<?php echo image_url('users', $dbdata['user_image']); ?>" class="media-object img-circle" style="width:40px">
                    <?php } else { ?>
                        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
                    <?php } ?>
                </div>
                <div class="media-right w-6adminD">
                    <h5><?php echo $dbdata['question']; ?></h5>
                    <?php
 if ($dbdata['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$dbdata['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$dbdata['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
                    <span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($dbdata['added_on']); ?></span>
                    <span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $dbdata['category_name']; ?></span>
                </div>
            </div>
            <div class="clearfix">
                <div class="pull-right">
                    <?php if ($dbdata['respond_date'] == NULL) { ?>
                        <?php if ($dbdata['status'] == '2') { ?>
                            <button type="submit" class="btn btn-danger">Discarded</button>
                        <?php } else { ?>
                            <button type="submit" class="btn transprantBtn redBtn discard_query" data-id="<?php echo $dbdata['id']; ?>">Discard</button>
                            <a title="Respond" href="javascript:void(0)" data-href="<?php echo base_url('ajax/respond_by_expert/'.$dbdata['id']) ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
                                Respond
                            </a>
                        <?php } ?>
                    <?php }
                    else { ?>
                        <!--<button type="button" class="btn AssignToExp transprantBtn">Responded</button>-->
                    <?php } ?>
                </div>
            </div>
            <!--answer of question-->
            <?php if(!empty($dbdata['answerData']) && $dbdata['status']!='2'){?>
            <div class="Answer">
                <div class="media-left">
                    <p>Answer</p>
                    <?php if (!empty($dbdata['answerData']['expert_image'])) { ?>
                        <img src="<?php echo image_url('users', $dbdata['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:40px">
                    <?php } else { ?>
                        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
                    <?php } ?>

                </div>
                <div class="userName">
                    <p><b><?php echo $dbdata['answerData']['expert_name'];?></b></p>
                    <p class="greenlable"><?php echo time_elapsed_string($dbdata['answerData']['answer_date']);?></p>
                </div>
                <p class="desMessage reviewDesMessage"><?php echo $dbdata['answerData']['expert_answer'];?></p>
                <?php } ?>
            </div>
        </div>
    </div>
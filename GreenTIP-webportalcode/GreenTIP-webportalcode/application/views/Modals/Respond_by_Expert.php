<div class="modal-body">
    <div class="exportTextAria" style="border:none;">
        <div class="exportMsgCard desCard">
            <div class="topheader">
                <div class="media-left">
                    <?php if (!empty($queryData['enduser_image'])) { ?>
                        <img src="<?php echo image_url('users', $queryData['enduser_image']); ?>" class="media-object img-circle" style="width:40px">
                    <?php } else { ?>
                        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
                    <?php } ?>
                </div>
                <div class="media-right w-6adminD">
                    <h5><?php echo $queryData['query'];
                    ?></h5>
                     <?php
 if ($queryData['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$queryData['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$queryData['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
                    <span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($queryData['added_on']); ?></span>
                    <span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $queryData['category_name']; ?></span>
                </div>
            </div>
        </div>
    </div>
    <?= form_open('', array('class' => 'form-horizontal ajax_form login-form', 'id' => 'login-form')) ?>
    <div class="alert ajax_report" role="alert" style="display:none;">
        <span class="close">x</span>
        <span class="ajax_message"></span>
    </div>
    <div class="form-group">
        <textarea name="answer" placeholder="Enter Answer Here." required class="form-control  summernote"></textarea>
    </div>
    <div class="form-group">
     <input  type="file" name="attachment_file" >
     </div>
    <div class="clearfix canc_submitBtn">
        <div class="row">
            <div class="pull-right">
                <button type="button" class="btn  transprantBtn dismis" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn transprantBtn submit">Submit</button>
            </div>
        </div>
    </div>
    </form>
</div>
<!-- include libraries(jQuery, bootstrap) -->
<link rel="stylesheet" href="<?php echo assets_url('css','summernote.css'); ?>" type="text/css" />
<script src="<?php echo assets_url('js', 'summernote.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
  
    $('.summernote') .summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
            ]
        })
   
    });
</script>
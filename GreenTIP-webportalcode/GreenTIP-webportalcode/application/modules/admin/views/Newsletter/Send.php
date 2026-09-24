<?php
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="wrapper-md">
                <?php $this->load->view('includes/msg_alert'); ?>
                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <h4><i class="fa fa-envelope"></i> Send Newsletter <a href="<?php echo base_url('admin/newsletters'); ?>" class="btn btn-primary backbtnLink">Back</a></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row wrapper">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <form class="form-horizontal" method="post" name="SendNewsletter" id="SendNewsletter" enctype="multipart/form-data" action="">
                                            <!-- Form Group Start -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Send To</label>
                                                <div class="col-sm-10">
                                                    <label class="radio-inline"> <input type="radio" class="uniform" name="sent_to" value="all" onClick="showhide(this.value);" <?php if((isset($_POST['sent_to'])) && ($_POST['sent_to']=="all")){ ?> checked="checked" <?php } else { ?> checked="checked" <?php } ?>> All </label>
                                                    <label class="radio-inline"> <input type="radio" class="uniform" name="sent_to" value="subscribers" onClick="showhide(this.value);" <?php if((isset($_POST['sent_to'])) && ($_POST['sent_to']=="subscribers")){ ?> checked="checked" <?php }?>> Only Subscribers </label>
                                                    <label class="radio-inline"> <input type="radio" class="uniform" name="sent_to" id="emailid" value="emailid" onClick="showhide(this.value);" <?php if((isset($_POST['sent_to'])) && ($_POST['sent_to']=="emailid")){ ?> checked="checked" <?php }?>> Only Email Id(s) </label>
                                                </div>
                                            </div>
                                            <div class="form-group" id="emilfrm" style="display: none;">
                                                <label class="col-sm-2 control-label">Email Id</label>
                                                <div class="col-sm-10"><input type="text" placeholder="Add comma separated email address" name="email" id="email" class="form-control" pattern="^([\w+-.%]+@[\w-.]+\.[A-Za-z]{2,4},*[\W]*)+$" value="<?php if((isset($_POST['email'])) && ($_POST['email']!='')){ echo $_POST['email'];} ?>"></div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Subject</label>
                                                <div class="col-sm-10">
                                                 <input type="text" maxlength="255" placeholder="Subject" name="subject" id="subject" class="form-control" value="<?php echo set_value('subject'); ?>">
                                                 <?php echo form_error('subject');?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <!-- Form Group End -->
                                            <!-- Form Group Start -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Message</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" name="message"  id="message" cols="5"><?php echo set_value('message'); ?></textarea>
                                                    <?php echo form_error('message');?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <!-- Form Group End -->
                                            <div class="form-group">
                                                <div class="col-sm-3 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/contact')); ?>"
                                                       class="btn btn-default">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Send</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer'); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<link rel="stylesheet" href="<?php echo assets_url('css','summernote.css'); ?>" type="text/css" />
<script src="<?php echo assets_url('js','summernote.js'); ?>"></script>
<script type="text/javascript">
    $('.summernote').summernote({
        height: 150,   //set editable area's height
        codemirror: { // codemirror options
            theme: 'monokai'
        },
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>
<script type="text/javascript">
    SyonApp.setPage('SendNewsletter');
    SyonApp.init();
    function showhide(val)
    {

        $('#emilfrm').hide();
        if(val=='emailid')
        {
            $('#emilfrm').show();
            var bootstrapValidator = $('#SendNewsletter').data('formValidation');
            bootstrapValidator.enableFieldValidators('email', true);
            bootstrapValidator.enableFieldValidators('subject', true);
            bootstrapValidator.enableFieldValidators('message', true);
        }else{
            var bootstrapValidator = $('#SendNewsletter').data('formValidation');
            bootstrapValidator.enableFieldValidators('email', false);
            bootstrapValidator.enableFieldValidators('subject', true);
            bootstrapValidator.enableFieldValidators('message', true);
        }
    }
</script>
</body>
</html>
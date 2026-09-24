<?php

$this->load->view('includes/header_script');
?>

<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="wrapper-md">
            <?php
            $this->load->view('includes/msg_alert'); ?>

                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <a href="<?php echo base_url() ;?>admin/email-template/index/all" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </a>
                        <h4><i class="fa fa-plug"></i> Edit Email Template</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <form class="form-horizontal" method="post" name="TemplateEdit" id="TemplateEdit" enctype="multipart/form-data" action="<?php echo (base_url('admin/email-template/edit/'.$dbdata['id'])); ?>">

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="EmailName">Email Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="email_name" id="EmailName" value="<?php echo ($dbdata['email_name'])?$dbdata['email_name']:set_value('email_name'); ?>">
                                                    <?php echo form_error('email_name'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->


                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="Subject">Email Subject</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="email_subject" id="Subject" value="<?php echo ($dbdata['email_subject'])?$dbdata['email_subject']:set_value('email_subject'); ?>">
                                                    <?php echo form_error('email_subject'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->


                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="Keywords">Email Keywords</label>
                                                <div class="col-sm-10">
                                                    <?php $keywordArr=explode(',',$dbdata['email_keywords']);?>
                                                    <select class="form-control" id="keyword_select" name="keyword_select" style="width: auto;">
                                                        <?php
                                                        for($keyCount=0;$keyCount<count($keywordArr); $keyCount++)
                                                        {
                                                            if(isset($keywordArr[$keyCount]) && trim($keywordArr[$keyCount]) !="")
                                                            {
                                                                ?>
                                                                <option value="<?php echo trim($keywordArr[$keyCount]); ?>"><?php echo trim($keywordArr[$keyCount]); ?></option>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->


                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="EmailContent">Email Content</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" name="email_content" id="email_content" cols="5" rows="3"><?php if((isset($_POST['email_content'])) && ($_POST['email_content']!='')){ echo $_POST['email_content'];} else { echo $dbdata['email_content']; }?></textarea>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->
                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/email-template/index')); ?>" class="btn btn-default">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2"></div>
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
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto','Cambria'],
        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48' , '64', '82', '150']
    });
</script>
<script type="text/javascript">
    SyonApp.setPage('TemplateEdit');
    SyonApp.init();
</script>
</body>
</html>
<?php
/**
 * @var $nameSingular string
 * @var $namePlural string
 * @var $nameClass string
 * @var $nameSession string
 * @var $dbdata array
 */
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
                        <a href="<?php echo base_url() ;?>admin/faqs/index/all" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </a>
                        <h4><i class="fa fa-plug"></i> Edit <?php echo $nameSingular ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">

                                        <?php echo form_open("admin/{$nameClass}/edit/{$dbdata['id']}", [
                                            'name' => $nameSession.'Form',
                                            'id' => $nameSession.'Form',
                                            'class' => 'form-horizontal',
                                        ]) ?>





                                        <!-- Question -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="Question">Question</label>
                                            <div class="col-sm-9">
                                                <?php echo form_input([
                                                    'id' => 'Question',
                                                    'name' => 'question',
                                                    'class' => 'form-control'
                                                ], set_value('question', $dbdata['question'])) ?>
                                                <?php echo form_error('question'); ?>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>



                                        <!-- Answer -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="Answer">Answer</label>
                                            <div class="col-sm-9">
                                                <?php echo form_textarea([
                                                    'id' => 'Answer',
                                                    'name' => 'answer',
                                                    'class' => 'form-control summernote'
                                                ], set_value('answer', $dbdata['answer'],false)) ?>
                                                <?php echo form_error('answer'); ?>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>



                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <a href="<?php echo getUrl(base_url("admin/{$nameClass}")); ?>" class="btn btn-default">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    <?php echo form_close() ?>
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
    SyonApp.setPage('<?php echo $nameSession ?>Edit');
    SyonApp.init();
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
</body>
</html>
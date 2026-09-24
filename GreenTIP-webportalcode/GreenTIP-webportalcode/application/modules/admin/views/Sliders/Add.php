<?php
/**
 * @var $nameSingular string
 * @var $namePlural string
 * @var $nameClass string
 * @var $nameSession string
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
                        <h4><i class="fa fa-plug"></i> Add <?php echo $nameSingular ?></h4>
                    </div>
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row wrapper">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">

                                    <?php echo form_open('', [
                                        'id' => $nameSession.'Form',
                                        'name' => $nameSession.'Form',
                                        'class' => 'form-horizontal',
                                        'enctype'=>'multipart/form-data'
                                    ]) ?>

                                    <!-- Title -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="Title">Title</label>
                                        <div class="col-sm-9">
                                            <?php echo form_input([
                                                'id' => 'Title',
                                                'name' => 'title',
                                                'class' => 'form-control'
                                            ], set_value('title')) ?>
                                            <?php echo form_error('title'); ?>
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>


                                    <!-- Form Group Start -->

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Slider Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userFile" data-icon="false" class="file-style" data-classButton="btn btn-default" data-classInput="form-control inline v-middle input-s">
                                            <?php echo form_error('userFile'); ?>
                                          <!--  <span> <strong>Note: </strong> Image width must be 1500px & height 900px </span>-->
                                        </div>

                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>





                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-3">
                                            <a href="<?php echo base_url("admin/{$nameClass}"); ?>" class="btn btn-default">Cancel</a>
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
<script src="<?php echo assets_url('grocery_crud','themes/bootstrap/bower_components/bootstrap-filestyle/src/bootstrap-filestyle.js'); ?>"></script>
<script type="text/javascript">
    SyonApp.setPage('<?php echo $nameSession ?>Add');
    SyonApp.init();
</script>
</body>
</html>

<?php 

$this->load->view('includes/header_script');?>
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
                        <a href="<?php echo base_url() ;?>admin/pages/index/all" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </a>
                        <h4><i class="fa fa-plug"></i> Edit Page</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <form class="form-horizontal" method="post" name="PageEdit" id="PageEdit" enctype="multipart/form-data" action="">

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageTitle">Page Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" readonly="true" class="form-control" name="title" id="title" value="<?php echo ($dbdata['title'])?$dbdata['title']:set_value('title'); ?>">
                                                    <?php echo form_error('title'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageContent">Page Content</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control summernote" name="description" id="description"  cols="5" rows="3"><?php if((isset($_POST['description'])) && ($_POST['description']!='')){ echo $_POST['description'];} else { echo $dbdata['content']; }?></textarea>
                                                    <?php echo form_error('description'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->


                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/pages')); ?>" class="btn btn-default">Cancel</a>
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

<!--<link rel="stylesheet" href="<?php /*echo assets_url('css','froala_editor.min.css'); */?>" type="text/css" />
<script src="<?php /*echo assets_url('js','froala_editor.min.js'); */?>"></script>-->
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
            ['insert', ['link','picture','video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    /*$('.summernote').editable({
        // Set the video upload URL.
        videoUploadURL: '/upload_video.php',
        autosave: false,// Enable autosave option. Enabling autosave helps preventing data loss.

        autosaveInterval: 1000,// Time in milliseconds to define when the autosave should be triggered.

        saveURL: null,// Defines where to post the data when save is triggered. The editor will initialize a POST request to the specified URL passing the editor content in the body parameter of the HTTP request.

        blockTags: ["n", "p", "blockquote", "pre", "h1", "h2", "h3", "h4", "h5", "h6"],// Defines what tags list to format a paragraph and their order.

        borderColor: "#252528",// Customize the appearance of the editor by changing the border color.

        buttons: ["bold", "italic", "underline", "strikeThrough", "fontSize", "color", "sep", "formatBlock", "align", "insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep", "selectAll", "createLink", "insertImage", "undo", "redo", "html"],// Defines the list of buttons that are available in the editor.

        crossDomain: false,// Make AJAX requests using CORS.

        direction: "ltr",// Sets the direction of the text.

        editorClass: "",// Set a custom class for the editor element.

        height: "auto",// Set a custom height for the editor element.

        imageMargin: 20,// Define a custom margin for image. It will be visible on the margin of the image when float left or right is active.

        imageErrorCallback: false,

        imageUploadParams: {
            id: 'my_editor'
        },// Customize the name of the param that has the image file in the upload request.

        imageUploadURL: '/greentip/upload_image.php', // A custom URL where to save the uploaded image.

        inlineMode: false,// Enable or disable inline mode.

        placeholder: "",// Set a custom placeholder to be used when the editor body is empty.

        shortcuts: true,// Enable shortcuts. The shortcuts are visible when you hover a button in the editor.

        spellcheck: false,// Enables spellcheck.

        typingTimer: 250,// Time in milliseconds to define how long the typing pause may be without the change to be saved in the undo stack.

        width: "auto" // Set a custom width for the editor element.

    });*/
</script>
<script type="text/javascript">
    SyonApp.setPage('PageEdit');
    SyonApp.init();
</script>
</body>
</html>
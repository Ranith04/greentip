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
                        <h4><i class="fa fa-plug"></i> Reply Contact</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <form class="form-horizontal" method="post" name="ReplyContact"
                                              id="ReplyContact" enctype="multipart/form-data" action="">


                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Email</label>

                                                <div class="col-sm-9"
                                                     style="margin-top:5px;"><?php echo $result['email']; ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->

                                            <!-- Form Group Start -->
                                            <?php
                                            if (!empty($replies)) {
                                                foreach ($replies as $reply) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"> Reply on
                                                            : <?php echo date('d M, Y', $reply->replied_on); ?></label>

                                                        <div class="col-sm-9"
                                                             style="margin-top:5px;text-align: justify;">

                                                            <?php
                                                            echo(str_replace('data:image/png;base64,', '', $reply->reply_msg)); ?>
                                                        </div>
                                                    </div>
                                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <?php
                                                } ?>

                                            <?php }
                                            ?>
                                            <!-- Form Group End -->

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Reply Message</label>


                                                <div class="col-sm-9">
                                                    <textarea class="form-control summernote" name="reply_msg"
                                                              id="reply_msg" cols="5"
                                                              rows="3"><?php if ((isset($_POST['reply_msg'])) && ($_POST['reply_msg'] != '')) {
                                                            echo $_POST['reply_msg'];
                                                        } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->

                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/contact')); ?>"
                                                       class="btn btn-default">Cancel</a>
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
    SyonApp.setPage('ReplyContact');
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
<!-- Toastr -->
<?php
/*$samePageErrorData = getSessionFlashDataSamePage('error');
$samePageSuccessData = getSessionFlashDataSamePage('success');
if (strlen(getSessionFlashData('success')) > 0 || strlen($samePageErrorData) > 0 || strlen($samePageSuccessData) > 0 || strlen(getSessionFlashData('error')) > 0 || strlen(getSessionFlashData('info')) > 0 || strlen(getSessionFlashData('warning')) > 0) {
    $notifyType = '';
    $notifyMsg = 'Information';
    if (strlen(getSessionFlashData('error')) > 0) {
        $notifyType = 'danger';
        $notifyMsg = getSessionFlashData('error');
    }

    if (strlen(getSessionFlashData('success')) > 0) {
        $notifyType = 'success';
        $notifyMsg = getSessionFlashData('success');
    }
    if (strlen($samePageErrorData) > 0) {
        $notifyType = 'danger';
        $notifyMsg = $samePageErrorData;
    }

    if (strlen($samePageSuccessData) > 0) {
        $notifyType = 'success';
        $notifyMsg = $samePageSuccessData;
    }
    */?><!--
    <script type="text/javascript">
        var type = "<?/*=$notifyType*/?>";
        var msg = "<?/*=$notifyMsg*/?>";
        $(function () {
            toastr.options.timeOut = 3000; // How long the toast will display without user interaction
            toastr.options.extendedTimeOut = 6000; // How long the toast will display after a user hovers over it
            toastr.options.progressBar = true;
            if (type == 'success') {
                toastr.success(msg);
            } else if (type == 'danger') {
                toastr.error(msg);
            } else {
                toastr.info(msg);
            }
        });
    </script>
<?php /*} */?>
<script src="<?php /*echo assets_url('js','toastr.min.js');*/?>"></script>-->
<?php
$samePageErrorData=getSessionFlashDataSamePage('error');
if(strlen(getSessionFlashData('error')) > 0 || strlen($samePageErrorData) > 0 )
{
    ?>
    <div class="front_flashdata">
        <div class="inputErrorMsg">
            <?php echo $samePageErrorData; ?>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    </div>
    <?php
}

$samePageSuccessData=getSessionFlashDataSamePage('success');
if(strlen(getSessionFlashData('success')) > 0 || strlen($samePageSuccessData) > 0 )
{
    ?>
    <div class="front_flashdata">
        <div class="inputSuccessMsg">
            <?php echo $samePageSuccessData; ?>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    </div>
    <?php
}
?>
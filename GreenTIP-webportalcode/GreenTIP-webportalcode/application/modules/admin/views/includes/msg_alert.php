<?php
/**
 * Created by PhpStorm.
 * User: abhishek
 * Date: 7/2/15
 * Time: 3:08 PM
 */

/*printArray(getSessionFlashData('error'),1);*/
?>
<?php
if (strlen(getSessionFlashData('success')) > 0 || strlen(getSessionFlashData('error')) > 0 || strlen(getSessionFlashData('info')) > 0 || strlen(getSessionFlashData('warning')) > 0) {
    ?>
    <div class="wrapper-md errorBlock" id="errorBlock">
        <div class="row">
            <div class="col-lg-12" style="">
                <?php
                if (strlen(getSessionFlashData('error')) > 0) {
                    ?>
                    <div class="alert alert-block alert-danger fade in">
                        <a class="close fa fa-times" data-dismiss="errorBlock" href="#" aria-hidden="true"></a>
                        <h4 style="font-size: 16px !important;"><?php
                            echo getSessionFlashData('error');
                            ?></h4>
                    </div>
                    <?php
                } ?>

                <?php
                if (strlen(getSessionFlashData('success')) > 0) {
                    ?>
                    <div class="alert alert-block alert-success fade in">
                        <a class="close fa fa-times" data-dismiss="errorBlock" href="#" aria-hidden="true"></a>
                        <p>
                            <?php
                            echo getSessionFlashData('success');
                            ?>
                        </p>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
    <?php
}

?>
<script type="text/javascript">
    //hide a div after 3 seconds
    <?php
    $class = $this->router->fetch_class();
    $method = $this->router->fetch_method();
    if($class != 'leads' && $method != 'importcsv'){?>
    setTimeout('$("#errorBlock").fadeOut();', 5000);
    <?php } ?>
</script>
<?php
/**
 * Created by PhpStorm.
 * User: abhishek@SYON.COM
 * Date: 3/10/16
 * Time: 10:56 AM
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
                <form method="post" id="filter_form" name="filter_form" action="<?php echo base_url('admin/settings'); ?>" class="form-horizontal">
                    <!--sorting-->
                    <input type="hidden" name="FormData[sort][field]" id="field" value="<?php if(isset($FormData['sort']['field'])){echo $FormData['sort']['field'];} ?>"/>
                    <input type="hidden" name="FormData[sort][order]" id="order" value="<?php if(isset($FormData['sort']['order'])){echo $FormData['sort']['order'];} ?>"/>
                    <!--page-->

                    <input type="hidden" name="FormData[form_name]" id="form_name" value="" />
                    <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if(isset($getData['page'])) {echo $getData['page']; } ?>"/>
                    <div style="display: none"><button type="button" class="btn btn-primary" id="filter"></button></div>
                </form>
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">
                                Settings Manager
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th style="width:20px;">#</th>
                                        <th><a class="heading" id="option_name">Keys</a></th>
                                        <th><a class="heading" id="option_value">Values</a></th>
                                        <th style="width:150px;"> Actions </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if($dbdata==false)
                                    { ?>
                                        <tr><td colspan="8">No Email Template Found!</td></tr>
                                    <?php }
                                    else {
                                        for ($i = 0; $i < count($dbdata); $i++) {
                                            ?>
                                            <tr>
                                                <td><?php echo $getData['page']+($i+1);?></td>
                                                <td><?php echo $dbdata[$i]['option_name']?></td>
                                                <td><?php echo $dbdata[$i]['option_value']?></td>
                                                <td>
                                                    <a title="Edit" href="<?php echo base_url(); ?>admin/settings/edit/<?php echo $dbdata[$i]['id'];?>" class="btn btn-rounded btn-sm btn-icon btn-info ">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">
                                    <div class="col-md-12  text-center">
                                        <?php echo $pagination;?>
                                    </div>
                                </div>
                            </footer>

                         </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer'); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script type="text/javascript">
    SyonApp.setPage('SettingsManager');
    SyonApp.init();
</script>
</body>
</html>
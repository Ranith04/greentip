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
                <form method="post" id="filter_form" name="filter_form"
                      action="<?php if (isset($getData['page']) && $getData['page'] != '0' && $getData['page'] != 'all') {
                          echo base_url('admin/subscribers/index/' . $getData['page']);
                      } else {
                          echo base_url('admin/subscribers/index');
                      } ?>" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">
                            <h4><i class="fa fa-search "></i> Filter Your Results <button style="float: right;" type="button" class="btn btn-primary"  onclick="$('#demo').toggle();"><i class="fa fa-plus"></i></button></h4>
                        </div>
                        <div class="panel-body" id="demo" style="display:none";>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row wrapper">
                                        <div class="form-group">
                                            <div class="col-xs-5"><input type="text" placeholder="Search By Email"
                                                                         class="form-control like" maxlength="255"
                                                                         value="<?php if (isset($FormData['like']['email'])) {
                                                                             echo $FormData['like']['email'];
                                                                         } ?>" name="FormData[like][email]" id="email">
                                            </div>
                                            <label class="col-md-1 control-label">Status: </label>

                                            <div class="col-xs-3">
                                                <label class="radio-inline"><input type="radio"
                                                                                   name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == '1') { ?> checked="checked"<?php } ?>
                                                                                   value="1"/> Activated</label>
                                                <label class="radio-inline"><input type="radio"
                                                                                   name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == 0) { ?> checked="checked"<?php } ?>
                                                                                   value="0"/> Deactivate</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <button type="button" class="btn btn-primary" id="filter"><i
                                                        class="fa fa-search"></i> Search
                                                </button>
                                                <a href="<?php echo base_url(); ?>admin/subscribers/index/all">
                                                    <button class="m-l-lg btn btn-inverse" type="button">Reset</button>
                                                </a>
                                            </div>
                                            <!--sorting-->
                                            <input type="hidden" name="FormData[sort][field]" id="field" value="<?php if (isset($FormData['sort']['field'])) {
                                                       echo $FormData['sort']['field'];
                                                   } ?>"/>
                                            <input type="hidden" name="FormData[sort][order]" id="order"
                                                   value="<?php if (isset($FormData['sort']['order'])) {
                                                       echo $FormData['sort']['order'];
                                                   } ?>"/>
                                            <!--page-->
                                            <input type="hidden" name="FormData[form_name]" id="form_name" value=""/>
                                            <input type="hidden" value="0" name="FormData[is_export]" id="is_export">
                                            <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if (isset($getData['page'])) {echo $getData['page'];} ?>"/>
                                            <input type="hidden" name="FormData[purpose_hidden]" id="purpose_hidden" value=""/>
                                            <input type="hidden" name="FormData[csv_ids_hidden]" id="csv_ids_hidden" value=""/>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        Subscriber Manager
                    </div>
                    <div class="row wrapper">
                        <div class="col-sm-5 m-b-xs">
                            <?php
                            echo form_dropdown('BulkAction', array('1' => 'Active', '0' => 'Deactivate', '2' => 'Delete'), array(set_value('BulkAction')), 'class="input-sm form-control w-sm inline v-middle" id="BulkAction" ');
                            ?>
                            <button class="btn btn-sm btn-default" id="SubmitBulk">Apply</button>
                        </div>
                        <div class="col-sm-7">
                          <!--  <?php /*if(!empty( $dbdata )){*/?>
                                <input style="float: right" type="button" class="btn btn-primary" onclick="javascript:doExportAction(1);" value="Export Data"></a>
                       --><?php /*}*/?>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped b-t b-light">
                            <thead>
                            <tr>
                                <th style="width:20px;">#</th>
                                <th style="width:20px;">
                                    <label class="i-checks m-b-none">
                                        <input type="checkbox" id="multicheck"><i></i>
                                    </label>
                                </th>
                                <th class="<?php echo ($field == 'email') ? $sorting_class : 'sorting'; ?>"><a class="heading" id="email">Email</a></th>
                                <th class="<?php echo ($field == 'status') ? $sorting_class : 'sorting'; ?>"><a class="heading" id="status">Status</a></th>
                                <th class="<?php echo ($field == 'created_at') ? $sorting_class : 'sorting'; ?>"><a class="heading" id="added_on">Created at</a></th>
                                <th style="width:150px;"> Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($dbdata == false) { ?>
                                <tr>
                                    <td colspan="8" align="center">No Subscribers Found!</td>
                                </tr>
                            <?php } else {
                                for ($i = 0; $i < count($dbdata); $i++) {
                                    $status_id = ($dbdata[$i]['status'] == 1) ? 0/*Deactivate*/ : 1;
                                    ?>
                                    <tr>
                                        <td><?php echo $getData['page'] + ($i + 1); ?></td>
                                        <td>
                                            <label class="i-checks m-b-none">
                                                <input type="checkbox" class="item_multicheck" name="item_id"
                                                       id="item_id<?php echo $i; ?>"
                                                       value="<?php echo $dbdata[$i]['id']; ?>"><i></i>
                                            </label>
                                        </td>
                                        <td><?php echo (isset($dbdata[$i]['email'])) ? $dbdata[$i]['email'] : '-'; ?></td>
                                        <td><?php
                                            if ($dbdata[$i]['status'] == '1') {
                                                ?>
                                                <span class="label bg-success" title="Active">Active</span>
                                            <?php
                                            } else if ($dbdata[$i]['status'] == '0') {
                                                ?>
                                                <span class="label bg-danger" title="Deactivate"> Deactivated</span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo convert_sqltime_to_calnderdate($dbdata[$i]['added_on']); ?></td>
                                        <td>
                                            <a title="<?php echo (isset($dbdata[$i]['status']) && $dbdata[$i]['status'] == 1) ? 'Deactivate' : 'Activate'; ?>"
                                               class="tip btn btn-rounded btn-sm btn-icon btn-<?php echo (isset($dbdata[$i]['status']) && $dbdata[$i]['status'] == 1) ? 'success' : 'danger'; ?>"
                                               href="javascript:singleOperation(<?php echo $status_id; ?>,'<?php echo $dbdata[$i]['id']; ?>');"><?php if ($dbdata[$i]['status'] == 0) {
                                                    echo '<i class="fa fa-ban fa-1x"></i>';
                                                } else {
                                                    echo '<i class="fa fa-check"></i>';
                                                } ?></a>
                                            <a href="javascript:singleOperation(2,'<?php echo $dbdata[$i]['id']; ?>');"
                                               class="btn btn-rounded btn-sm btn-icon btn-danger">
                                                <i class="fa fa-trash"></i>
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
                                <?php echo $pagination; ?>
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
<script type="application/javascript">
    function doExportAction(is_export){
        if(is_export){
            $('#is_export').val(is_export);
            document.filter_form.submit();
        }
    }
</script>
<script type="text/javascript">
    SyonApp.setPage('SubscriberManager');
    SyonApp.init();
</script>
</body>
</html>
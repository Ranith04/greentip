<?php
/**
 * @var $nameSingular string
 * @var $namePlural string
 * @var $nameSession string
 * @var $nameClass string
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

                    <?php echo form_open(
                        "admin/{$nameClass}/index" . (!empty($getData['page']) && $getData['page'] != 'all' ? '/'. $getData['page']:''),
                        [
                            'method'=>'post', 'id'=>'filter_form', 'name'=>'filter_form' , 'class'=>'form-horizontal'
                        ]
                    ) ?>

                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">
                            <h4><i class="fa fa-search "></i> Filter Your Results</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row wrapper">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <input type="text" placeholder="Title"
                                                     class="form-control like" maxlength="255"
                                                     value="<?php if (isset($FormData['like']['title'])) {
                                                         echo $FormData['like']['title'];
                                                     } ?>" name="FormData[like][title]"
                                                     id="title">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col-md-12">
                                        <label class="col-md-1 control-label">Status: </label>

                                        <div class="col-md-4">
                                            <label class="radio-inline">
                                                <input type="radio"
                                                   name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == '1') { ?> checked="checked"<?php } ?>
                                                   value="1"/> Active</label>
                                            <label class="radio-inline">
                                                <input type="radio"
                                                   name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == '0') { ?> checked="checked"<?php } ?>
                                                   value="0"/> Inactive</label>
                                            <!--sorting-->
                                            <input type="hidden" name="FormData[sort][field]" id="field"
                                                   value="<?php if (isset($FormData['sort']['field'])) {
                                                       echo $FormData['sort']['field'];
                                                   } ?>"/>
                                            <input type="hidden" name="FormData[sort][order]" id="order"
                                                   value="<?php if (isset($FormData['sort']['order'])) {
                                                       echo $FormData['sort']['order'];
                                                   } ?>"/>
                                            <!--page-->
                                            <input type="hidden" name="FormData[sort][page]" id="page"
                                                   value="<?php if (isset($getData['page'])) {
                                                       echo $getData['page'];
                                                   } ?>"/>
                                            <input type="hidden" name="FormData[purpose_hidden]" id="purpose_hidden"
                                                   value=""/>
                                            <input type="hidden" name="FormData[csv_ids_hidden]" id="csv_ids_hidden"
                                                   value=""/>
                                            <input type="hidden" name="FormData[form_name]" id="form_name" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" id="filter"><i
                                                class="fa fa-search"></i> Search
                                        </button>
                                        <a href="<?php echo base_url("admin/{$nameClass}/index/all"); ?>">
                                            <button class="m-l-lg btn btn-inverse" type="button">Reset</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--</form>-->
                <?php echo form_close() ?>

                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <?php echo $namePlural ?> Manager
                    </div>
                    <div class="row wrapper">
                        <div class="col-sm-5 m-b-xs">
                            <?php
                            echo form_dropdown('BulkAction', ['1' => 'Active', '0' => 'Inactive'],
                                [set_value('BulkAction')],
                                'class="input-sm form-control w-sm inline v-middle" id="BulkAction" ');
                            ?>
                            <button class="btn btn-sm btn-default" id="SubmitBulk">Apply</button>
                        </div>
                        <div class="col-sm-4">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped b-t b-light">
                            <thead>
                            <tr>
                                <th style="width:20px;">
                                    <label class="i-checks m-b-none">
                                        <input type="checkbox" id="multicheck"><i></i>
                                    </label>
                                </th>
                                <th><a class="heading" id="title">Title</a></th>

                                <th><a class="heading" id="status">Status</a></th>
                                <th><a class="heading" id="added_on">Added On</a></th>
                                <th style="width:15%;"> Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($dbdata == false) { ?>
                                <tr>
                                    <td colspan="8">No <?php echo $namePlural ?> Found!</td>
                                </tr>
                            <?php } else {

                                for ($i = 0; $i < count($dbdata); $i++) {
                                    ?>
                                    <tr>
                                        <td>
                                            <label class="i-checks m-b-none">
                                                <input type="checkbox" class="item_multicheck" name="item_id"
                                                       id="item_id<?php echo $i; ?>"
                                                       value="<?php echo $dbdata[$i]['id']; ?>"><i></i>
                                            </label></td>
                                        <td><?php echo character_limiter($dbdata[$i]['title'], 60); ?></td>

                                        <td><?php
                                            if ($dbdata[$i]['status'] == '1') {
                                                ?>
                                                <span class="label bg-success" title="Active">Active</span>
                                            <?php
                                            } else {
                                                if ($dbdata[$i]['status'] == '0') {
                                                    ?>
                                                    <span class="label bg-danger" title="Inactive">Inactive</span>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                       <!-- <td><?php /*echo date('M  d, Y', strtotime($dbdata[$i]['added_on'])) */?></td>-->
                                        <td><span id="date_<?php echo $dbdata[$i]['id'];?>"><?php echo convert_sqltime_to_calnderdate($dbdata[$i]['added_on']);?></span></td>
                                        <td>
                                            <a title="Edit"
                                               href="<?php echo base_url("admin/{$nameClass}/edit/{$dbdata[$i]['id']}"); ?>"
                                               class="btn btn-rounded btn-sm btn-icon btn-info ">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a title="View"
                                               href="<?php echo base_url("admin/{$nameClass}/view/{$dbdata[$i]['id']}"); ?>"
                                               class="btn btn-rounded btn-sm btn-icon btn-warning">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="javascript:singleOperation('3','<?php echo $dbdata[$i]['id']; ?>');"
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
<script type="text/javascript">
    SyonApp.setPage('<?php echo $nameSession ?>Manager');
    SyonApp.init();
</script>
</body>
</html>
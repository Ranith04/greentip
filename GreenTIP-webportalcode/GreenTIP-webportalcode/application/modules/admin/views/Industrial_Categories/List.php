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
                <form method="post" id="filter_form" name="filter_form" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('admin/industrial_categories/index/'.$getData['page']);}else{ echo base_url('admin/industrial_categories/index'); } ?>" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">
                            <h4><i class="fa fa-search "></i> Filter Your Results<button style="float: right;" type="button" class="btn btn-primary"  onclick="$('#demo').toggle();"><i class="fa fa-plus"></i></button></h4>
                        </div>
                        <div class="panel-body" id="demo" style="display:none";>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row wrapper">
                                        <div class="form-group">
                                            <div class="col-md-3"> <input type="text" placeholder="Search by category name" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['cat_name'])){echo $FormData['like']['cat_name'];} ?>" name="FormData[like][cat_name]" id="cat_name" ></div>
                                            <!--sorting-->
                                            <input type="hidden" name="FormData[sort][field]" id="field" value="<?php if(isset($FormData['sort']['field'])){echo $FormData['sort']['field'];} ?>"/>
                                            <input type="hidden" name="FormData[sort][order]" id="order" value="<?php if(isset($FormData['sort']['order'])){echo $FormData['sort']['order'];} ?>"/>
                                            <!--page-->

                                            <input type="hidden" name="FormData[form_name]" id="form_name" value="" />
                                            <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if(isset($getData['page'])) {echo $getData['page']; } ?>"/>
                                            <input type="hidden" name="FormData[purpose_hidden]" id="purpose_hidden" value="" />
                                            <input type="hidden" name="FormData[csv_ids_hidden]" id="csv_ids_hidden" value="" />
                                        </div>

                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-search"></i> Search</button>
                                        <a href="<?php echo base_url(); ?>admin/industrial_categories/index/all"><button class="m-l-lg btn btn-inverse" type="button">Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>
                            <div class="panel panel-default">
                                <div class="panel-heading font-bold">
                                    Industrial Category Manager
                                    <a href="<?php echo base_url(); ?>admin/industrial_categories/add" class="btn btn-primary" style="float: right; margin-top: -7px;">
                                        <i class="fa fa-plus"></i> Add Category
                                    </a>
                                </div>
                                <div class="row wrapper">
                                    <div class="col-sm-5 m-b-xs">
                                        <?php
                                        echo form_dropdown('BulkAction', array('1'=>'Active','0'=>'Inactive','3'=>'Deleted'), array(set_value('BulkAction')),'class="input-sm form-control w-sm inline v-middle" id="BulkAction" ');
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
                                            <th><a class="heading" id="cat_name">Category Name</a></th>
                                            <th><a class="heading" id="status">Status</a></th>
                                            <th><a class="heading" id="added_on">Added on</a></th>
                                            <th style="width:150px;"> Actions </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($dbdata==false)
                                        { ?>
                                            <tr><td colspan="8">No Category Found!</td></tr>
                                        <?php }
                                        else {
                                            for ($i = 0; $i < count($dbdata); $i++) {
                                                
                                                ?>
                                                <tr>
                                                    <td>
                                                        <label class="i-checks m-b-none">
                                                            <input type="checkbox" class="item_multicheck" name="item_id" id="item_id<?php echo $i; ?>" value="<?php echo $dbdata[$i]['id'];?>"><i></i>
                                                        </label></td>
                                                    <td><?php echo $dbdata[$i]['cat_name']?></td>

                                                    <td><?php
                                                        if ($dbdata[$i]['status'] == '1') {
                                                            ?>
                                                            <span class="label bg-success" title="Active">Active</span>
                                                        <?php
                                                        } else if ($dbdata[$i]['status'] == '2') {
                                                            ?>
                                                            <span class="label bg-warning" title="Blocked">Blocked</span>
                                                        <?php

                                                        } else if ($dbdata[$i]['status'] == '0') {
                                                            ?>
                                                            <span class="label bg-danger" title="Pending">Inactive</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata[$i]['added_on']);?></td>
                                                    <td>
                                                        <a title="Edit"
                                                           href="<?php echo base_url(); ?>admin/industrial_categories/edit/<?php echo $dbdata[$i]['id']; ?>"
                                                           class="btn btn-rounded btn-sm btn-icon btn-info ">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                       <!-- <a title="View"
                                                           href="<?php /*echo base_url(); */?>admin/industrial_categories/view/<?php /*echo $dbdata[$i]['id']; */?>"
                                                           class="btn btn-rounded btn-sm btn-icon btn-warning">
                                                            <i class="fa fa-eye"></i>
                                                        </a>-->
                                                        <!-- <a  href="javascript:singleOperation('3','<?php echo $dbdata[$i]['id'];?>');" class="btn btn-rounded btn-sm btn-icon btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a> -->
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
    SyonApp.setPage('IndustrialCategoryManager');
    SyonApp.init();
</script>
</body>
</html>
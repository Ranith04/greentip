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
                <form method="post" id="filter_form" name="filter_form" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('admin/newsletters/index/'.$getData['page']);}else{ echo base_url('admin/newsletters/index'); } ?>" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">
                            <h4><i class="fa fa-search "></i> Filter Your Results</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row wrapper">
                                        <div class="form-group">
                                             <div class="col-md-9">  <input type="text" placeholder="Search By Subject" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['subject'])){echo $FormData['like']['subject'];} ?>" name="FormData[like][subject]" id="email" ></div>
                                             <div class="col-md-3 text-right">
                                                <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-search"></i> Search</button>
                                                <a href="<?php echo base_url(); ?>admin/newsletters/index/all"><button class="m-l-lg btn btn-inverse" type="button">Reset</button></a>
                                            </div>
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
                            </div>
                            </div>
                        </div>
                    </form>
                            <div class="panel panel-default">
                                <div class="panel-heading font-bold">
                                    Newsletter Manager
                                </div>
                                <div class="row wrapper">
                                    <div class="col-sm-5 m-b-xs">
                                        <?php
                                        echo form_dropdown('BulkAction', array(/*'1'=>'Active','0'=>'Inactive',*/'3'=>'Delete'), array(set_value('BulkAction')),'class="input-sm form-control w-sm inline v-middle" id="BulkAction" ');
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
                                            <th style="width:20px;">#</th>
                                            <th style="width:20px;">
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" id="multicheck"><i></i>
                                                </label>
                                            </th>
                                            <th class="<?php echo ( $field == 'subject' ) ? $sorting_class : 'sorting';?>"><a class="heading" id="subject">Subject</a></th>
                                            <th class="<?php echo ( $field == 'status' ) ? $sorting_class : 'sorting';?>"><a class="heading" id="status">Status</a></th>
                                            <th class="<?php echo ( $field == 'created_at' ) ? $sorting_class : 'sorting';?>"><a class="heading" id="created_at">Sent on</a></th>
                                            <th style="width:150px;"> Actions </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($dbdata==false)
                                        { ?>
                                            <tr><td colspan="8" align="center">No Newsletters Found!</td></tr>
                                        <?php }
                                        else {
                                            for ($i = 0; $i < count($dbdata); $i++) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $getData['page']+($i+1);?></td>
                                                    <td>
                                                        <label class="i-checks m-b-none">
                                                            <input type="checkbox" class="item_multicheck" name="item_id" id="item_id<?php echo $i; ?>" value="<?php echo $dbdata[$i]['id'];?>"><i></i>
                                                        </label>
                                                    </td>
                                                    <td><?php echo (isset($dbdata[$i]['subject'])) ? $dbdata[$i]['subject'] : '-'; ?></td>
                                                    <td><?php
                                                        if ($dbdata[$i]['status'] == '1') {
                                                            ?>
                                                            <span class="label bg-success" title="Active">Sent</span>
                                                        <?php
                                                        } else if ($dbdata[$i]['status'] == '0') {
                                                            ?>
                                                            <span class="label bg-danger" title="Pending">Pending</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata[$i]['created_at']);?></td>
                                                    <td>
                                                        <a title="View"
                                                           href="<?php echo base_url(); ?>admin/newsletters/view/<?php echo $dbdata[$i]['id']; ?>"
                                                           class="btn btn-rounded btn-sm btn-icon btn-warning">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a  title="Delete" href="javascript:singleOperation(3,'<?php echo $dbdata[$i]['id']; ?>');"
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
    SyonApp.setPage('NewsletterManager');
    SyonApp.init();
</script>
</body>
</html>
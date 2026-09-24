<?php
$this->load->view('includes/header_script');
?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <!-- BREADCRUMBS -->
            <ul class="breadcrumb">
                <li><i class="fa fa-home"></i> <a href="<?php echo base_url('admin'); ?>">Home </a></li>
                <li><?php echo $pageBreadCrumbs; ?></li>
            </ul>
            <!-- /BREADCRUMBS -->
            <div class="wrapper-md">
            <?php
                $this->load->view('includes/msg_alert'); ?>
                <form method="post" id="filter_form" name="filter_form" action="<?php echo base_url('admin/users/index'); ?>" class="form-horizontal">
                <div class="panel panel-default userType1">
                    <div class="panel-heading font-bold">
                        <h4><i class="fa fa-search "></i> Filter Your Results <button style="float: right;" type="button" class="btn btn-primary"  onclick="$('#demo').toggle();"><i class="fa fa-plus"></i></button></h4>
                    </div>
                    <div class="panel-body" id="demo" style="display:none";>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row wrapper">
                            <div class="form-group">
                                <div class="col-md-3"> <input type="text" placeholder="Company Name" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['b_industrial_users-company_name'])){echo $FormData['like']['b_industrial_users-company_name'];} ?>" name="FormData[like][b_industrial_users-company_name]" id="company_name" ></div>
                                <div class="col-md-3">  <input type="text" placeholder="Email" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['b_industrial_users-email'])){echo $FormData['like']['b_industrial_users-email'];} ?>" name="FormData[like][b_industrial_users-email]" id="email" ></div>
                                <div class="col-md-3">  <input type="text" placeholder="Contact Number" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['b_industrial_users-contact_no'])){echo $FormData['like']['b_industrial_users-contact_no'];} ?>" name="FormData[like][b_industrial_users-contact_no]" id="contact_no" ></div>
                            </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row wrapper">
                                <div class="form-group">
                                    <div class="col-md-3"><input type="text" placeholder="Search by from date" class="form-control dPicker"  maxlength="255"  value="<?php if(isset($FormData['range']['b_industrial_users-created_on']['from'])){echo $FormData['range']['b_industrial_users-created_on']['from'];} ?>" name="FormData[range][b_industrial_users-created_on][from]"  ></div>
                                    <div class="col-md-3"><input type="text" placeholder="Search by to date" class="form-control dPicker"  maxlength="255"  value="<?php if(isset($FormData['range']['b_industrial_users-created_on']['to'])){echo $FormData['range']['b_industrial_users-created_on']['to'];} ?>" name="FormData[range][b_industrial_users-created_on][to]"></div>
                                    <label class="col-md-1 control-label">Status: </label>
                                    <div class="col-md-5">

                                        <label class="radio-inline"><input type="radio" name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == '1') { ?> checked="checked"<?php } ?> value="1"/> Active</label>
                                        <label class="radio-inline"><input type="radio" name="FormData[equal][status]" <?php if (isset($FormData['equal']['status']) && $FormData['equal']['status'] == '0') { ?> checked="checked"<?php } ?> value="0"/> Inactive</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-12">
                            <div class="col-md-4">

                                <input type="hidden" name="FormData[sort][field]" id="field" value="<?php if(isset($FormData['sort']['field'])){echo $FormData['sort']['field'];} ?>"/>
                                <input type="hidden" name="FormData[sort][order]" id="order" value="<?php if(isset($FormData['sort']['order'])){echo $FormData['sort']['order'];} ?>"/>
                                <!--page-->
                                <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if(isset($getData['page'])) {echo $getData['page']; } ?>"/>
                                <input type="hidden" name="FormData[purpose_hidden]" id="purpose_hidden" value="" />
                                <input type="hidden" name="FormData[csv_ids_hidden]" id="csv_ids_hidden" value="" />
                                <input type="hidden" name="FormData[form_name]" id="form_name" value="" />
                            </div>
                            </div>
                        </div>
                        <div class="row wrapper">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-search"></i> Search</button>
                                <a href="<?php echo base_url(); ?>admin/users/index/all"><button class="m-l-lg btn btn-inverse" type="button">Reset</button></a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                    </form>

            <div class="panel panel-default">
                <div class="panel-heading font-bold exhibitorManager">
                    User Manager
                    <a href="<?php echo base_url(); ?>admin/users/add" class="btn btn-primary" style="float: right; margin-top: -7px;">
                        <i class="fa fa-plus"></i> Add User
                    </a>
                </div>
                <div class="row wrapper">
                    <div class="col-sm-5 m-b-xs">
                        <?php
                        echo form_dropdown('BulkAction', array(''=>'-Select Status-','1'=>'Active','0'=>'Inactive','3'=>'Delete'), array(set_value('BulkAction')),'class="input-sm form-control w-sm inline v-middle" id="BulkAction" ');
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
                            <th><a class="heading" id="category_id">CATEGORY</a></th>
                            <th><a class="heading" id="company_name">COMPANY NAME</a></th>
                            <th><a class="heading" id="contact_person">CONTACT PERSON</a></th>
                            <th><a class="heading" id="email">EMAILID</a></th>
                            <th><a class="heading" id="contact_no">CONTACT NO</a></th>
                            <th><a class="heading" id="created_on">DATE OF REGISTRATION </a></th>
                            <th><a class="heading" id="status">Status</a></th>
                            <th style="width:15%;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($dbdata==false)
                        { ?>
                            <tr><td colspan="15"><div class="alert alert-danger">No Registered Users Found!</td></tr>
                        <?php }
                        else {
                            for ($i = 0; $i < count($dbdata); $i++) {
                                ?>
                                <tr>

                                    <td>
                                        <label class="i-checks m-b-none">
                                            <input type="checkbox" class="item_multicheck" name="item_id" id="item_id<?php echo $i; ?>" value="<?php echo $dbdata[$i]['id'];?>"><i></i>
                                        </label></td>

                                    <td><?php if($dbdata[$i]['category_name']){echo $dbdata[$i]['category_name'];}else{ echo "N/A";}?></td>
                                    <td><?php if($dbdata[$i]['company_name']){echo $dbdata[$i]['company_name'];}else{ echo "N/A";}?></td>
                                    <td><?php if($dbdata[$i]['contact_person']){echo $dbdata[$i]['contact_person'];}else{ echo "N/A";}?></td>
                                    <td><a href="mailto:<?php echo $dbdata[$i]['email'];?>"><?php if($dbdata[$i]['email']){echo $dbdata[$i]['email'];}else{ echo "N/A";}?></a></td>
                                    <td><?php if($dbdata[$i]['contact_no']){echo $dbdata[$i]['contact_no'];}else{ echo "N/A";}?></td>
                                    <td><?php echo convert_sqltime_to_calnderdate($dbdata[$i]['created_on']);?></td>
                                    <td><?php
                                         if ($dbdata[$i]['status'] == '1') {
                                            echo '<span title="Active" class="label bg-success">Active</span>';
                                        }else{
                                            echo '<span title="Inactive" class="label bg-danger">Inactive</span>';
                                        } ?></td>

                                    <td>
                                        <a title="Edit"
                                           href="<?php echo base_url(); ?>admin/users/edit/<?php echo $dbdata[$i]['id']; ?>"
                                           class="btn btn-rounded btn-sm btn-icon btn-info ">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a title="View"
                                           href="<?php echo base_url(); ?>admin/users/view/<?php echo $dbdata[$i]['id']; ?>"
                                           class="btn btn-rounded btn-sm btn-icon btn-warning">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a title="Delete"
                                           href="javascript:singleOperation('3','<?php echo $dbdata[$i]['id']; ?>');"
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
<!-- Modal -->
<div class="modal fade" id="regularModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>
<script type="text/javascript">
    SyonApp.setPage('UserManager');
    SyonApp.init();
    function ChangeStatus(status,userid)
    {
        if(userid>0 && status!='')
        {
            if(!singleOperation(status,userid))
            {

            }
        }

    }
</script>
</body>
</html>
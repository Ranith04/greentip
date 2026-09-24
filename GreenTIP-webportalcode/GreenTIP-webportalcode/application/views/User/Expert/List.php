<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<div class="container">
    <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <?php $this->load->view('Includes/msg_alert'); ?>
            <div class="panel-body removepadTop">
                <div class="row">
                    <form method="post" id="filter_form" name="filter_form" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('user/manage_users/'.$getData['page']);}else{ echo base_url('user/manage_users'); } ?>" class="form-horizontal">
                        <div class="panel panel-default panel-collapsed">
                            <div class="panel-heading font-bold filtrResult">
                                <h4><i class="fa fa-search "></i> &nbsp; Filter Your Results</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row wrapper">
                                            <div class="col-sm-4"> <input type="text" placeholder="Name" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['name'])){echo $FormData['like']['name'];} ?>" name="FormData[like][name]" id="name" ></div>
                                            <div class="col-sm-4">  <input type="text" placeholder="Email" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['u-email'])){echo $FormData['like']['u-email'];} ?>" name="FormData[like][u-email]" id="email" ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col-md-12">
                                        <label class="col-md-1 control-label">Status: </label>
                                        <div class="col-md-5">
                                            <label class="radio-inline"><input type="radio"  name="FormData[equal][u-status]" <?php if(isset($FormData['equal']['u.status']) && $FormData['equal']['u.status']=='1'){ ?> checked="checked"<?php }?> value="1" /> Approved</label>
                                            <label class="radio-inline"><input type="radio"  name="FormData[equal][u-status]" <?php if(isset($FormData['equal']['u.status']) && $FormData['equal']['u.status']=='2'){ ?> checked="checked"<?php }?> value="2" /> Blocked</label>
                                            <!--<label class="radio-inline"><input type="radio"  name="FormData[equal][u-status]" <?php /*if(isset($FormData['equal']['u.status']) && $FormData['equal']['u.status']=='0'){ */?> checked="checked"<?php /*}*/?> value="0" /> Pending</label>-->
                                            <!--sorting-->
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
                                <div class="mt2"></div>
                                <div class="row wrapper">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-filter"></i> Filter</button>
                                        <a href="<?php echo base_url(); ?>user/manage_users/all"><button class="m-l-lg btn ResetBtn" type="button">Reset</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row manage_user">
                    <div class="panel-heading font-bold clearfix">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 AddExportBtn text-right">
                            <button class="btn btn-warning"  id="SubmitEmailBulk" data-action="4">Send Email</button>
                            <a href="<?php echo  base_url('user/add_user')?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add Expert</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive manageUserTable">
                        <table class="table datatable dataTable no-footer">
                            <thead>
                            <tr>
                                <th>
                                    <label class="i-checks m-b-none"  style="margin-bottom: 0px">
                                        <input type="checkbox" id="multicheck"><i></i>
                                    </label>
                                </th>
                                <th><a class="heading" id="u.image">Image</a></th>
                                <th><a class="heading" id="u.name">Name</a></th>
                                <th><a class="heading" id="u.email">Email</a></th>
                                <th><a class="heading" id="u.added_on">Added On</a></th>
                                <th><a class="heading" id="u.status">Status</a></th>
                                <th style="width:120px;"> Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($records)){
                                $i = 0;
                                foreach ($records as $value){?>
                                    <tr>
                                        <td>
                                            <?php if($value['status'] == '1'){?>
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" class="item_multicheck" name="item_id" id="item_id<?php echo $i; ?>" value="<?php echo $value['id'];?>"><i></i>
                                                </label>
                                            <?php }
                                            else{
                                                echo "--";
                                            } ?>
                                            </td>
                                        <td>
                                        <?php if (!empty($value['image'])) { ?>
                                            <img class="img-circle img-responsive" alt="User Profile Pic" src="<?php echo image_url('users', $value['image'], 50, 50); ?>">
                                        <?php } else { ?>
                                            <img class="img-circle img-responsive" alt="User Profile Pic" src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" width="50px" height="50px">
                                        <?php } ?>

                                        </td>
                                        <td><?php echo  $value['name'] ?></td>
                                        <td><?php echo $value['email']?></td>
                                        <td><?php echo convert_sqltime_to_calnderdate($value['created_on']);?></td>
                                        <td>
                                            <?php if ($value['status'] == '1') { ?>
                                                <select class="form-control" onChange="ChangeStatus(this.value,'<?php echo $value['id']; ?>');">
                                                    <option value="">Active</option>
                                                    <option value="2">Block</option>
                                                </select>
                                            <?php } elseif ($value['status'] == '2') { ?>
                                                <select class="form-control" onChange="ChangeStatus(this.value,'<?php echo $value['id']; ?>');">
                                                    <option value="">Block</option>
                                                    <option value="1">Active</option>
                                                </select>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a title="View" href="<?=base_url('user/view/'.$value['id'])?>" class="btn btn-rounded btn-sm btn-icon btn-warning">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a title="Edit" href="<?=base_url('user/edit_user/'.$value['id'])?>" class="btn btn-rounded btn-sm btn-icon btn-info ">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript:singleOperation('3','<?=$value['id']?>');" class="btn btn-rounded btn-sm btn-icon btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $i++;}
                            }
                            else{?>
                                <tr>
                                    <td colspan="8"> <div class="alert alert-danger text-center">No expert found here.</div></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="clearfix"></div>
                        <div class="text-center"><?php echo isset($pagination) && $pagination!='' ? $pagination : '';?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo assets_url('js', 'CustomJs.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
    SyonApp.setPage('ExpertUserManager');
    SyonApp.init();
</script>
<script type="text/javascript">
  $("#btnfile").click(function () {
    $("#userFile").click();
  });
  function ChangeStatus(status,userid)
  {
      if(userid>0 && status!='')
      {
          if(!singleOperation(status,userid))
          {

          }
      }
  }

  var fileTypes = ['jpg', 'jpeg', 'png'];
  var flag = 0;

  function readURL(input) {
    if (flag != '1') {
      $('.previewImage').css("display", "block");
    }
    flag = 0;
    if (input.files && input.files[0]) {

      var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
          isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types
      if (isSuccess) {

        var reader = new FileReader();
        reader.onload = function (e) {
          var base64_string = e.target.result;

          $('#previewImageUser').attr('src', base64_string);
        }

        reader.readAsDataURL(input.files[0]);
      }

      else {
        alert('please select valid file type. The supported file types are .jpg , .png , .jpeg"');
      }
    }
  }

  $("#userFile").change(function () {
    readURL(this);
  });
$(document).ready(function () {
    $('body').delegate('#SubmitEmailBulk', 'click', function (e) {
        e.preventDefault();
        var el = $(this).data('action');
        var strUser = el;
        groupEmailOperation(strUser);
    });
  });
  function groupEmailOperation(actionStr) {
      var checkedIDs = "";
      $('.item_multicheck').each(function (e) {
          if (this.checked) {
              checkedIDs += this.value + ",";
          }
      });
      if (checkedIDs != "") {
          if (confirm("Are you sure you want to perform this action ?")) {
              checkedIDs = checkedIDs.substring(0, checkedIDs.length - 1);
              document.getElementById("csv_ids_hidden").value = checkedIDs;
              document.getElementById("purpose_hidden").value = actionStr;
              document.filter_form.submit();
          }
          return false;
      } else {
          alert("Please select at least 1 record");
          return false;
      }
  }
</script>
</body>
</html>
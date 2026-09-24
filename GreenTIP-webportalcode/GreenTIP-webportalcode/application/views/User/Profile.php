<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
    <div class="container">
        <div class="row ask_Export">
            <?php $this->load->view('Includes/profilesidebar'); ?>
            <div class="col-sm-9 col-md-9 col-lg-9">
			<div class="Userprofile">
                <?php $this->load->view('Includes/msg_alert'); ?>

                <form method="post" id="EditProfileForm" name="EditProfileForm" enctype="multipart/form-data" action="">
                 <h3 class="title" style="text-align: center;margin-bottom: 3.5%;">Edit <font>Profile</font></h3>
                <div class="form-group">
                    <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Full Name*</label>
					 <div class="input-group col-xs-12 col-md-9">
                      <input class="form-control" data-val-required="The Name field is required." id="Name" name="name" type="text"  value="<?php echo ($result['name']) ? $result['name'] : 'N/A'; ?>">
					</div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="control-label col-xs-12 col-md-3">Email Address*</label>
					 <div class="input-group col-xs-12 col-md-9">
                       <input class="form-control" id="Email" name="email" readonly="readonly" type="text" value="<?php echo ($result['email']) ? $result['email'] : 'N/A'; ?>">
					</div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="control-label col-xs-3 col-md-3"> Phone Number*</label>
                    <div class="input-group col-xs-12 col-md-9">
                      <input class="form-control" id="phone" name="phone" type="text" value="<?php echo ($result['contact_no']) ? $result['contact_no'] : ''; ?>">
					</div>
                </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Company Name</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Company Name field is required." id="company_name" name="company_name" type="text"  value="<?php echo ($result['company_name']) ? $result['company_name'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Company Location</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Company Location field is required." id="company_location" name="company_location" type="text"  value="<?php echo ($result['company_location']) ? $result['company_location'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Education Qualification</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Education Qualification field is required." id="education_qualification" name="education_qualification" type="text"  value="<?php echo ($result['education_qualification']) ? $result['education_qualification'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Areas of Expertise fields</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Areas of Expertise fields field is required." id="expertise_field" name="expertise_field" type="text"  value="<?php echo ($result['expertise_field']) ? $result['expertise_field'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Occupation</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Occupation field is required." id="occupation" name="occupation" type="text"  value="<?php echo ($result['occupation']) ? $result['occupation'] : ''; ?>">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Address</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The Address field is required." id="address" name="address" type="text"  value="<?php echo ($result['address']) ? $result['address'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">District</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The District field is required." id="district" name="district" type="text"  value="<?php echo ($result['district']) ? $result['district'] : ''; ?>">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">State</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The State field is required." id="state" name="state" type="text"  value="<?php echo ($result['state']) ? $result['state'] : ''; ?>">
                        </div>
                    </div>
                       <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label col-xs-12 col-md-3">Pin Code</label>
                        <div class="input-group col-xs-12 col-md-9">
                            <input class="form-control" data-val-required="The State field is required." id="pin" name="pin" type="text"  value="<?php echo ($result['pin']) ? $result['pin'] : ''; ?>">
                        </div>
                    </div>
                <div class="form-group">
                   <!-- <figure>-->
                        <div class="previewImage  col-md-3" id="">
                            <?php if (!empty($result['image'])) { ?>
                                <img width="128px" height="128px" class="img-responsive" alt="User Profile Pic" src="<?php echo image_url('users', $result['image'], 128, 128); ?>" id="previewImageUser"><br>
                            <?php } else { ?>
                                <img class="img-responsive" alt="User Profile Pic" src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" id="previewImageUser">
                            <?php } ?>
                        </div><br><br>
						<div class=" col-md-9">
                        <a class="btn btn-primary btn-sm"  id="btnfile" type="button"> Change Profile Picture </a>
                        <span style="display: none;" class="btn btn-primary btn-block">Change Profile <input type="file" name="userFile" id="userFile"></span>
                        <?php echo form_error('userFile'); ?>
						</div>
                   <!-- </figure>-->
                </div>
				
				<div class="row">
				  <div class="col-sm-12 text-right">
                    <input type="submit" class="btn btn-success saveChangeBtn" value="Save Changes">
					</div>
				</div>
            </form>
            </div>
			
        </div>
    </div>
<script type="text/javascript">
    $("#btnfile").click(function () {
        $("#userFile").click();
    });
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
</script>
    <script src="<?php echo assets_url('js', 'authentication.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
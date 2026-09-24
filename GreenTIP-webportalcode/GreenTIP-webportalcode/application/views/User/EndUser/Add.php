<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<div class="container">
    <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <?php $this->load->view('Includes/msg_alert'); ?>
            <?php
            $heading = 'Add';
            if (isset($detail) && $detail['name'] != '') {
                $name = trim($detail['name']);
                $detail['last_name'] = $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
                $detail['first_name'] = trim( preg_replace('#'.$last_name.'#', '', $name ) );
                $heading = 'Edit';
            }
            ?>


            <form method="post" name="AddUserForm" id="AddUserForm" enctype="multipart/form-data" action="">
                <h3 class="title" style="text-align: center;margin-bottom:3.5%;"><?php echo $heading; ?> <font>End User</font></h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group md-form-group">
                             <label for="first_name">First Name*</label>
                            <input type="text"  placeholder="First Name"  class="form-control" id="first_name" name="first_name" value="<?php echo isset($detail['first_name']) ? $detail['first_name'] : set_value('first_name'); ?>"> <?php echo form_error('first_name');  ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group md-form-group">
                             <label for="last_name">Last Name*</label>
                            <input type="text" placeholder="Last Name" class="form-control" id="last_name" name="last_name" value="<?php echo isset($detail['last_name']) ? $detail['last_name'] : set_value('last_name'); ?>"> <?php echo form_error('last_name'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group md-form-group">
                     <label for="email">Email Address*</label>
                    <input type="text" placeholder="Email Address" <?php if ($heading  == 'Edit') {
                              echo "readonly";
                            }  ?> class="form-control" name="email" id="email" value="<?php echo isset($detail['email']) ? $detail['email'] : set_value('email'); ?>"> <?php echo form_error('email'); ?>
                </div>
                <div class="form-group">
                    <div class="input-group col-xs-12 col-md-9">
                         <label for="contact_no">Phone Number*</label>
                        <input class="form-control" placeholder="Phone Number" id="contact_no" name="contact_no" type="text" value="<?php echo isset($detail['contact_no']) ? $detail['contact_no'] : set_value('contact_no'); ?>"><?php echo form_error('contact_no'); ?>
                    </div>
                </div>
                <div class="form-group md-form-group">
                     <label for="company_name">Company Name</label>
                    <input type="text" placeholder="Company Name" class="form-control" name="company_name" id="company_name" value="<?php echo isset($detail['company_name']) ? $detail['company_name'] : set_value('company_name'); ?>"> <?php echo form_error('company_name'); ?>
                </div>
                 <div class="form-group md-form-group">
                     <label for="company_location">Company Location</label>
                    <input type="text" placeholder="Company Location" class="form-control" name="company_location" id="company_location" value="<?php echo isset($detail['company_location']) ? $detail['company_location'] : set_value('company_location'); ?>"> <?php echo form_error('company_location'); ?>
                </div>
                <div class="form-group md-form-group">
                     <label for="occupation">Occupation</label>
                    <input type="text" placeholder="Occupation" class="form-control" name="occupation" id="occupation" value="<?php echo isset($detail['occupation']) ? $detail['occupation'] : set_value('occupation'); ?>"> <?php echo form_error('occupation'); ?>
                </div>
                <div class="form-group md-form-group">
                     <label for="education_qualification">Education Qualification</label>
                    <input type="text" placeholder="Education Qualification" class="form-control" name="education_qualification" id="education_qualification" value="<?php echo isset($detail['education_qualification']) ? $detail['education_qualification'] : set_value('occupation'); ?>"> <?php echo form_error('education_qualification'); ?>
                </div>
                 <div class="form-group md-form-group">
                     <label for="address">Address</label>
                    <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="<?php echo isset($detail['address']) ? $detail['address'] : set_value('address'); ?>"> <?php echo form_error('address'); ?>
                </div>
                 <div class="form-group md-form-group">
                     <label for="district">District</label>
                    <input type="text" placeholder="District" class="form-control" name="district" id="district" value="<?php echo isset($detail['district']) ? $detail['district'] : set_value('district'); ?>"> <?php echo form_error('district'); ?>
                </div>
                 
                 <div class="form-group md-form-group">
                     <label for="state">State</label>
                    <input type="text" placeholder="State" class="form-control" name="state" id="state" value="<?php echo isset($detail['state']) ? $detail['state'] : set_value('state'); ?>"> <?php echo form_error('state'); ?>
                </div>
                 <div class="form-group md-form-group">
                     <label for="pin">Pin</label>
                    <input type="text" placeholder="Pin Code" class="form-control" name="pin" id="pin" value="<?php echo isset($detail['pin']) ? $detail['pin'] : set_value('pin'); ?>"> <?php echo form_error('pin'); ?>
                </div>
                <div class="form-group md-form-group">
                     <label for="expertise_field">Areas of Expertise fields</label>
                    <input type="text" placeholder="Areas of Expertise fields" class="form-control" name="expertise_field" id="expertise_field" value="<?php echo isset($detail['expertise_field']) ? $detail['expertise_field'] : set_value('expertise_field'); ?>"> <?php echo form_error('expertise_field'); ?>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group md-form-group">
                            <figure>
                                <div class="previewImage" id="">
                                    <?php if (!empty($detail['image'])) { ?>
                                        <img class="img-responsive" alt="User Profile Pic" src="<?php echo image_url('users', $detail['image'], 128, 128); ?>" id="previewImageUser">                        <?php } else { ?>
                                        <img class="img-responsive" alt="User Profile Pic" src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" id="previewImageUser">                        <?php } ?>
                                </div>
                            </figure>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group md-form-group">
                            <button class="btn btn-primary " id="btnfile" type="button"><i class="fa fa-edit"></i>Add Image
                            </button>
                            <span style="display: none;" class="btn btn-primary btn-block">Change Profile <input type="file" name="userFile" id="userFile"></span> <?php echo form_error('userFile'); ?>
                            <button class="btn Add_U-Exp" type="submit">Save</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('Includes/footer'); ?>
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
    $('#AddUserForm').formValidation({
        excluded: ':disabled',
        framework: 'bootstrap',
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The First Name field is required.'
                    },
                    stringLength: {
                        min: 4,
                        max: 30,
                        message: 'The First Name field must be at least 4 characters in length.'
                    }
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'The Last Name field is required.'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'The Last Name field must be at least 4 characters in length.'
                    }
                }
            },
            email: {
                validators: {
                   /* stringCase: {
                        message: 'The Email must be in lowercase',
                        'case': 'lower'
                    },*/
                    regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'Please enter valid email address.'
                    },
                    notEmpty: {
                        message: 'The Email is required and cannot be empty.'
                    },
                    stringLength: {
                        max: 150,
                        message: 'The Email must less than 30 characters'
                    }
                }
            },
            contact_no: {
                validators: {
                    notEmpty: {
                        message: 'The Phone Number field is required.'
                    },
                    numeric: {
                        message: 'The phone number field must be a number.'
                    },
                    stringLength: {
                        message: 'Phone Number must be of 10 digits',
                        max: 10,
                        min: 10
                    }
                }
            }
        }
    });
</script>
</body>
</html>
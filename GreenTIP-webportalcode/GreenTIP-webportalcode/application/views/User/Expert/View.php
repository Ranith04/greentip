<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<style type="text/css">
    .bold{
       
    
    font-weight: 700; 
    }
</style>
<div class="container">
    <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="Userprofile">
                <h3 class="title" style="text-align: center;margin-bottom: 3.5%;">View
                    <?php if ($dbdata['role_type'] == '1') {
                        echo "Expert";
                    } else {
                        echo "End User";
                    } ?>
                    <font>Profile</font></h3>

                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <tbody><tr>
                            <td  class="bold">Full Name*</td>
                            <td><?php echo !empty($dbdata['name']) ? $dbdata['name'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Email Address*</td>
                            <td><?php echo !empty($dbdata['email']) ? $dbdata['email'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Facebook URL</td>
                            <td><?php echo !empty($dbdata['facebook']) ? '<a href="'.$dbdata['facebook'].'" target="_blank" >'.$dbdata['facebook'].'</a>' : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">LinkedIn URL</td>
                            <td><?php echo !empty($dbdata['linkdin']) ? '<a href="'.$dbdata['linkdin'].'" target="_blank" >'.$dbdata['linkdin'].'</a>' : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Company Name</td>
                            <td><?php echo !empty($dbdata['company_name']) ? $dbdata['company_name'] : 'N/A';  ?></td>
                        </tr>

                        <tr>
                            <td  class="bold">Company Location</td>
                            <td><?php echo !empty($dbdata['company_location']) ? $dbdata['company_location'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Occupation</td>
                            <td><?php echo !empty($dbdata['occupation']) ? $dbdata['occupation'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Education Qualification</td>
                            <td><?php echo !empty($dbdata['education_qualification']) ? $dbdata['education_qualification'] : 'N/A';  ?></td>
                        </tr>
                         <tr>
                            <td  class="bold">Address</td>
                            <td><?php echo !empty($dbdata['address']) ? $dbdata['address'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">District</td>
                            <td><?php echo !empty($dbdata['district']) ? $dbdata['district'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">State</td>
                            <td><?php echo !empty($dbdata['state']) ? $dbdata['state'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Pin Code</td>
                            <td><?php echo !empty($dbdata['pin']) ? $dbdata['pin'] : 'N/A';  ?></td>
                        </tr>
                        <tr>
                            <td  class="bold">Areas of Expertise fields</td>
                            <td><?php echo !empty($dbdata['expertise_field']) ? $dbdata['expertise_field'] : 'N/A';  ?></td>
                        </tr>
                        <!--<tr>
                            <td>Description</td>
                            <td><?php //echo !empty($dbdata['description']) ? $dbdata['description'] : 'N/A';  ?></td>
                        </tr>-->
                        <tr>
                            <td  class="bold">Image</td>
                            <td>
                                <?php
                                if(!empty($dbdata['image'])){
                                    ?>
                                   <img width="150px" height="150px" alt="Slider Pic" class="img-responsive img-thumbnail" src="<?php echo assets_url('uploads','users/'.$dbdata['image']); ?>"></span>
                                    <?php
                                }
                                else{?>
                                    <img class="img-circle img-responsive" alt="User Profile Pic" src="<?php echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); ?>" width="60px" height="60px">
                                <?php }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td  class="bold">Status</td>
                            <td>
                                <?php if($dbdata['status']=='1'){?>
                                    <span class="label bg-success" title="Active">Active</span>
                                <?php }
                                else if($dbdata['status']=='2'){?>
                                <span class="label bg-danger" title="Blocked">Blocked</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td  class="bold">Added On</td>
                            <td><?php echo !empty($dbdata['created_on']) ? convert_sqltime_to_calnderdate($dbdata['created_on']) : '';  ?></td>
                        </tr>
                        </tbody></table>
                </div> </div>

        </div>
    </div>
</div>

<?php $this->load->view('Includes/footer'); ?>
</body>
</html>

<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<style type="text/css">
    .card {

            height: 130px;
          
         border-radius:5px 5px 5px 5px;
         padding:5% 5%;border:var(--border);
         background-color:var(--white-background-color)
  /* Add shadows to create the "card" effect */
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.col-md-6 {
    width: 48%!important;

}
.UserSetting, #AddUserForm {
    height: 385px!important;
}
</style>
<div class="container">
   <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <?php $this->load->view('Includes/msg_alert'); ?>
           
            <div id="AddUserForm" >
			  <!-- <h5 class="title" style="text-align: right;margin-bottom:3.5%;"><?php echo $heading; ?> <font>All Queries</font><hr></h5> -->
              <br>
                <div class="row">
                    



                    <div style="    margin-left: 1.8%;" class="col-md-6">
                      
                    <a href="<?php echo base_url();?>user/mdNewQueriesReports/all"><div class="media card " style="border-bottom: 2px solid blue;" >
                                <div class="media-left">
                                    <img src="<?php echo base_url();?>assets/img/new-query.png" class="media-object" style="width:50px"></div> 
                                <div class="media-body"> 
                                <span class="media-heading"><span style="float: right; font-size: large;color: blue;"><?=$new?></span> <br><br><br>
                                <span style="float: right; font-size: large; color: blue;" >New Queries</span></span>
                                    
                                </div>
                      
                    </div>
                    </a>   
                    </div>
                    <!--  <div class="col-md-6">
                        <div class="card">
                         <div class="media desbordCard_Tnasp" style="border: 1px solid green;">
                                <div class="media-left">
                                    <img src="<?php echo base_url();?>assets/img/dashboard_green_icon_new.png" class="media-object" style="width:30px"></div>
                                <div class="media-body">
                                    <h4 class="media-heading"><a href="<?php echo base_url();?>user/mdNewQueriesReports/all">New Queries&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button style="margin-left: 20px;" type="button" class="btn btn-success"> <span class="badge"><?=$new?></span></button></a> </h4>

                            </div>
                        </div>
                    </div>
                    </div> -->

                    <div style="margin-left: 0.5%;" class="col-md-6">
                      <a href="<?php echo base_url();?>user/mdPendingQueriesReports/all">   <div class="media card " style="border-bottom: 2px solid #a89e32;" >
                                <div class="media-left">
                                    <img src="<?php echo base_url();?>assets/img/pending-query.png" class="media-object" style="width:50px"></div>
                                <div class="media-body">
                                     <span class="media-heading"><span style="float: right; color: #a89e32;font-size: large;"><?=$pending?></span> <br><br><br>
                                <span style="float: right; font-size: large; color: #a89e32" >Pending Queries</span></span>

                                  

                            </div>
                        </div> </a>
 
                    </div>

                    <div style="    margin-left: 1.8%;" class="col-md-6">
                       
                    <a href="<?php echo base_url();?>user/mdClosedQueriesReports/all"> 
                <div  class="media card " style="border-bottom: 2px solid green;  margin-top: 35px!important;" >
                                <div class="media-left">
                                    <img src="<?php echo base_url();?>assets/img/closed-query.png" class="media-object" style="width:50px"></div>
                                <div class="media-body">
                                      <span class="media-heading"><span style="float: right; color: green; font-size: large;"><?=$closed?></span> <br><br><br>
                                <span style="float: right; font-size: large; color: green" >Closed Queries</span></span>
                                 
                            </div>
                        </div></a>


                    </div>



                    <div style="margin-left: 0.5%;" class="col-md-6">
                       <a href="<?php echo base_url();?>user/mdTotalQueriesReports/all"> <div class="media card " style="border-bottom: 2px solid #eb4634;  margin-top: 35px!important;" >
                                <div class="media-left">
                                    <img src="<?php echo base_url();?>assets/img/total-query.png" class="media-object" style="width:50px"></div>
                                <div class="media-body">
                                    <span class="media-heading"><span style="float: right; color: #eb4634;font-size: large;"><?=$total?></span> <br><br><br>
                                <span style="float: right; font-size: large; color: #eb4634" >
                                Total Queries
                            </span>
                            </span>
                                    
                            </div>
                        </div></a>

                    </div>
                  
                </div>
               
               
                
             
                <div class="clearfix"></div>
            </divv>
        </div>
    </div>
</div>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
  
    $('#AddUserForm').formValidation({
        excluded: ':disabled',
        framework: 'bootstrap',
        fields: {
            start_date: {
                validators: {
                    notEmpty: {
                        message: 'The field is required.'
                    }
                }
            },
            end_date: {
                validators: {
                    notEmpty: {
                        message: 'The field is required.'
                    } 
                }
            },
        report_type: {
                validators: {
                    notEmpty: {
                        message: 'The field is required.'
                    } 
                }
            }
        
        }
    });
</script>
</body>
</html>
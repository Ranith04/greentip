<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
    
.Add_U-Exp {
    color: #fff;
    background-color: var(--background-color);
    display: block;
    float: right;
    top: 0px!important; 
    position: relative;
    padding: 6px 8% 6px 8%;
}

</style>
<div class="container">
   <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <?php $this->load->view('Includes/msg_alert'); ?>
           
            <form method="post" name="AddUserForm" id="AddUserForm" enctype="multipart/form-data" action="">
			  <h5 class="title" style="text-align: right;margin-bottom:0.5%;"><?php echo $heading; ?> <font>All Reports</font><hr></h5>
             
                <div class="row">
                      <div class="col-md-3">
                        <div class="form-group md-form-group">
                            <select name="report_type" id="report_type" class="form-control" required>

                                <option value="">--Select Report Type--</option>
                         
                                <option value="1">New Queries</option>
                                <option value="2">Pending Queries</option>
                                <option value="3">Closed Queries</option>
                                <option value="4">Total Queries</option>
                           
                        </select>
                <?php echo form_error('report_type'); ?>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="form-group md-form-group">
                            <input type="text" placeholder="Start Date" class="form-control datepicker" id="start_date" name="start_date"> <?php echo form_error('start_date'); ?>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group md-form-group">
                            <input type="text" placeholder="End Date" class="form-control datepicker" id="end_date" name="end_date"> <?php echo form_error('end_date'); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group md-form-group">
                            <button class="btn Add_U-Exp" type="submit">Filter</button>
                        </div>
                    </div>
                  
                </div>
               
               
              
                <div class="clearfix"></div>
            </form>
        </div>



        <div class="col-sm-9 col-md-9 col-lg-9">
            <hr>
<br>
        <div class="col-sm-12 table-responsive manageUserTable">
                        <table class="table datatable dataTable no-footer">
                            <thead>
                    <tr>
                        <th>#</th>
                        <th><a class="heading" id="u.Query">Query</a></th>
                        <th><a class="heading" id="u.Category">Category</a></th>
                        <th><a class="heading" id="u.Status">Status</a></th>
                        <th><a class="heading" id="u.added_on">Added On</a></th>
                    </tr>
                            </thead>
                            <tbody>
                            
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                             
                               
                            
                            </tbody>
                        </table>
                    </div>

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

 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>
</body>
</html>
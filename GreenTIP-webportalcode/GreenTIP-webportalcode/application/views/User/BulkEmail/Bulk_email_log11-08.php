<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href= 
'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'
          rel='stylesheet'> 
<style type="text/css">
    
.ui-widget-header {
    border: 1px solid #e78f08;
    background: #0d9e40!important;
    color: #ffffff;
    font-weight: bold;
}
.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
    border: 1px solid #fed22f;
    background: #0d9e40!important;;
    color: white;
}

</style>
<div class="container">
    <div class="row ask_Export">
        <?php $this->load->view('Includes/profilesidebar'); ?>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <?php $this->load->view('Includes/msg_alert'); ?>
            <div class="panel-body removepadTop">
                <div class="row">
                    <form method="post" id="filter_form" name="filter_form" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('user/bulkemail/'.$getData['page']);}else{ echo base_url('user/bulkemail'); } ?>" class="form-horizontal">
                        <div class="panel panel-default panel-collapsed">
                            <div class="panel-heading font-bold filtrResult">
                                <h4><i class="fa fa-search "></i>&nbsp;Filter Your Results</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row wrapper">
                                            <!-- <div class="col-sm-4"> <input type="text" placeholder="Search by email id" class="form-control like"  maxlength="255"  value="<?php if(isset($FormData['like']['email'])){echo $FormData['like']['email'];} ?>" name="FormData[like][email]" id="email" ></div> -->


                                            <div class="col-sm-4"> <input type="text" readonly placeholder="Please select the date" class="form-control datepicker like"  maxlength="255"  value="<?php if(isset($FormData['like']['date'])){echo $FormData['like']['date'];} ?>" name="FormData[like][date]" id="date" ></div> 
                                         
                                        </div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                            <!--sorting-->
                                            <input type="hidden" name="FormData[sort][field]" id="field" value="<?php if(isset($FormData['sort']['field'])){echo $FormData['sort']['field'];} ?>"/>
                                            <input type="hidden" name="FormData[sort][order]" id="order" value="<?php if(isset($FormData['sort']['order'])){echo $FormData['sort']['order'];} ?>"/>
                                            <!--page-->
                                            <input type="hidden" name="FormData[form_name]" id="form_name" value="" />
                                            <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if(isset($getData['page'])) {echo $getData['page']; } ?>"/>
                                        </div>
                                
                                <div class="mt2"></div>
                                <div class="row wrapper">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-search"></i> Search</button>
                                        <a href="<?php echo base_url(); ?>user/bulkemail/all"><button class="m-l-lg btn ResetBtn" type="button">Reset</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> 
                </div> 
               
                <div class="row">
                    <div class="col-sm-12 table-responsive manageUserTable">
                        <table style="width: 100%" class="table datatable dataTable no-footer">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th style="width:12%; "><a class="heading" id="date">Date</a></th>
                                <th style="width:30%; " ><a class="heading" id="date">Send To</a></th>
                               <!--  <th><a class="heading" id="email">Email</a></th> -->
                                <th style="width:35%;" ><a class="heading" id="subject">Subject</a></th>
                                <th><a class="heading" id="message">Message</a></th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($dbdata)){

                            	if($pageNum > 1)
                            	{ 
                            		$i = $pageNum+1;
                            	}
                            	else
                            	{
                            		$i = 1;
                            	}
                                
                                foreach ($dbdata as $value){?>
                                    <tr>

                                       <td><?php echo $i++; ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($value['date'])); ?></td>
                                         <td><?php 
                                         echo  ucwords(str_replace(",", " ", $value['sender_user_type']));
                                        $string = $value['sender_user_type']; 
                                        $str_arr = explode (",", $string);
                                        if (in_array('emailid', $str_arr)) {  
                                       //$emailList = explode(',', trim($value['email']));
                                       ?>
                                       <a type="button"   title="Read Message" class="open-AddBookDialog1 " data-id="<?php echo  $value['id']; ?>" href="#exampleModal">View Email</a>
                                        <?php
                                       //print_r($emailList['0']);
                                      }
                                         //echo  ucwords(str_replace(",", " ", $value['sender_user_type'])); ?></td>

                                       <!--  <td><?php echo  $value['email'] ?></td> -->
                                        <td style="text-align: justify;" ><?php echo $value['subject']?></td>
                                      
                                    <td><?php echo 
                                        implode(' ', array_slice(explode(' ', $value['message']), 0,0))."....";

                                        ?> <button type="button"   title="Read Message" class="btn btn-info btn-xs open-AddBookDialog " data-id="<?php echo  $value['id']; ?>" href="#exampleModal">View </button></td>
                                       
                                     
                                <?php 
                                }
                            }
                            else{?>
                                <tr>
                                    <td colspan="8"> <div class="alert alert-danger text-center">No user found here.</div></td>
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


<div id="myModal123" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: 100%!important; margin-left: 0px!important;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
      </div>

      <div style="text-align: center;">
     
      <div class="modal-body" id="myModelData" style="height: 150px;     overflow: scroll;">
      
      </div>
     </div>
      <div class="modal-footer">
       
      
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script src="<?php echo assets_url('js', 'CustomJs.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
    SyonApp.setPage('EmailLogManager');
    SyonApp.init();
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

<script type="text/javascript">
	
$( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );


$(document).on("click", ".open-AddBookDialog", function () {
  
     var url = "<?php echo base_url('user/bulkemailmessage'); ?>";
     var ur = "/"
     var myId = $(this).data('id');
    
    // alert(myId);
    
      jQuery.ajax({
      
        url:  url+ur+myId,      
        type: 'post',
       
        success: function(data) {
            //response = jQuery.parseJSON(data);
           console.log(data);
            $('#myModelData').html(data);
            //$(".modal-body #bookId").val( myId );
            $('#myModal123').modal('show');
        }             
    });



     //alert(myId);
    //  $(".modal-body #bookId").val( myId );
    // $('#myModal').modal('show');
});
$(document).on("click", ".open-AddBookDialog1", function () {
  
     var url = "<?php echo base_url('user/bulkemailemailid'); ?>";
     var ur = "/"
     var myId = $(this).data('id');
    
     //alert(myId);
    
      jQuery.ajax({
      
        url:  url+ur+myId,      
        type: 'post',
       
        success: function(data) {
            //response = jQuery.parseJSON(data);
           console.log(data);
            $('#myModelData').html(data);
            //$(".modal-body #bookId").val( myId );
            $('#myModal123').modal('show');
        }             
    });



     //alert(myId);
    //  $(".modal-body #bookId").val( myId );
    // $('#myModal').modal('show');
});

</script>
</body>
</html>




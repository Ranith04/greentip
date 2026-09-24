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
                   
            <div class="panel panel-default panel-collapsed">
            <div class="panel-heading font-bold filtrResult">
            <h4><i class="fa fa-filter "></i>&nbsp;Filter Your Results </h4>
            </div>
            <div class="panel-body">
            <div class="row">
              
            <div class="col-md-12">
            <div class="row wrapper">

  <form method="get" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('user/bulkemail/'.$getData['page']);}else{ echo base_url('user/bulkemail'); } ?>">


 
    <div class="col-sm-3" >  <input type="text" placeholder="Start Date"  class="form-control datepicker"  maxlength="255" autocomplete="off"  value="<?php if ($this->input->get('start_date')){
     echo $this->input->get('start_date');
    } ?>" name="start_date" id="start_date"  ></div>

    <div style="margin-left: -2%;" class="col-sm-3">  <input type="text" placeholder="End Date" id="end_date" class="form-control datepicker"  maxlength="255" autocomplete="off"  value="<?php  if ($this->input->get('end_date')){
     echo $this->input->get('end_date');
    } ?>" name="end_date"  ></div>

            <div style="margin-left: -2%;" class="col-sm-2">
            <button type="submit" class="btn btn-green" ><i class="fa fa-filter"></i> Filter</button>

            
            </div>
            <div style="margin-left: -5%;" class="col-sm-2"> 
<a href="<?php echo base_url('user/bulkemail/'); ?>"><button class="m-l-lg btn ResetBtn" type="button">Reset</button></a>
</div>
            </form>
            </div>
            </div>
            </div>
            </div>


            
            </div>
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

                                        ?> <button type="button"   title="Read Message" class="btn btn-default btn-xs open-AddBookDialog " data-id="<?php echo  $value['id']; ?>" href="#exampleModal">View </button></td>
                                       
                                     
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
      <div class="modal-header text-center bg-success">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        Message
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
	

    // $( "#start_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
  
$('#end_date').datepicker({
  dateFormat: 'yy-mm-dd' ,
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  new Date(),
    onSelect: function(dateText){
        var start_date = new Date($('#start_date').val()),
            end_date = new Date(dateText),
            diff  = new Date(end_date - start_date),
            days  = parseInt(diff/(1000*60*60*24), 10);
            var d = new Date();

if (end_date >= d) {
    alert('End date should not be greater then current date!');
    $(this).val('');
return false;
}
        if(days < 0){
            alert('End date should be greater than start date!')
            $(this).val('');
            return false;
        }

        //$("#startDate").datetimepicker('option', 'maxDate', dateText);

    }
});

$('#start_date').datepicker({
dateFormat: 'yy-mm-dd' ,
dayOfWeekStart : 1,
lang:'en',
startDate:  new Date(),
onSelect: function(dateText){
var end_date = new Date($('#end_date').val()),
start_date = new Date(dateText),


diff  = new Date(start_date - end_date),
days  = parseInt(diff/(1000*60*60*24), 10);
//var today = new Date().getTime();
//alert(today);
//alert(days);
var d = new Date();
//alert(start_date);
if (start_date >= d) {
    alert('Start date should not be greater than current date!');
    $(this).val('');
return false;
}

if(days >= 1){
alert('start date should be less than End date!')
$(this).val('');
return false;
}
//$("#startDate").datetimepicker('option', 'maxDate', dateText);

}
});

$("form").submit(function(){
   var start_date = $('#start_date').val();
     var end_date = $('#end_date').val();
     if (start_date) {
        if (end_date == "") {
            alert("Please enter last date");
            return false;
        }
     }
      if (end_date) {
        if (start_date == "") {
            alert("Please enter start date");
            return false;
        }
     }
});


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




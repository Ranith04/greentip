<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
//print_r($dbdata2);die;
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<div class="container">
<div class="container">
<div class="row ask_Export">
<?php $this->load->view('Includes/profilesidebar'); ?>
<div class="col-sm-9 col-md-9 col-lg-9 " id="end_user_listing">
<div class="exportTextAria">
<div class="exportMsgCard">
<div class="header media">
<h4 class="card-heading">Ask our experts<button style="float: right;" type="button" class="btn btn-green"  onclick="$('#demo').toggle();"><i style="color: green" class="fa fa-plus"></i></button> </h4>
</div>
<div class="card"  id="demo" style="display:none";>
<?php echo form_open(base_url('ajax/submit_query'), array('class' => 'form-horizontal ajax_form submit-query', 'id' => 'submit-query')) ?>
<div class="card-content" >
<div class="card-body">
<div class="alert ajax_report" role="alert" style="display:none;">
<span class="close">x</span>
<span class="ajax_message"></span>
</div>
<select name="category_id" id="category_id" >
<option style="color: black!important;" value=""> Select Category</option>
<?php
if (!empty($categories)) {
foreach ($categories as $category) {
    ?>
    <option style="color: black!important;" value="<?php echo $category['id']; ?>"><?php echo $category['cat_name']; ?></option>
<?php }
}
?>
</select>
<input type="hidden" name="user_id" value="<?php echo $result['id']; ?>">
<textarea rows="5"  name="query" id="query" placeholder="Start new question with what. How why etc.."></textarea>
 <input  type="file" name="attachment_file" >
</div>
<div class="exportBtn">
<button type="button" class="btn Clear" onClick="this.form.reset()">Clear</button>
<button type="submit" class="btn Submit">Submit</button>
</div>
<br>
</div>
</form>

</div>
</div>
</div>
<div class="panel-body removepadTop">
<div class="row">

<div class="panel panel-default panel-collapsed">
<!-- <div class="panel-heading font-bold filtrResult">
<h4><i class="fa fa-filter "></i>&nbsp;Filter Your Results </h4>
</div> -->
<div class="panel-body">
<div class="row">
<div class="col-md-12">
<div class="row wrapper">

<form method="get" action="<?php echo base_url('dashboard/');?>">


<div class="col-sm-3" > <input type="text"  placeholder=" Start Date" class="form-control datepicker" id="start_date" maxlength="255"  value="<?php if ($this->input->get('start_date')){
echo $this->input->get('start_date');
} ?>" name="start_date" autocomplete="off"  ></div>


<div style="margin-left: -2%;" class="col-sm-3">  <input type="text" placeholder="End Date" id="end_date" class="form-control datepicker"  maxlength="255"  value="<?php  if ($this->input->get('end_date')){
echo $this->input->get('end_date');
} ?>" name="end_date" autocomplete="off"  ></div>

<div style="margin-left: -2%;" class="col-sm-3">   
<select  class="form-control " name="category_idd"  >
<option value="">Category</option>
<?php
if (!empty($categories)) {
foreach ($categories as $category) {
?>
<option <?php if ($this->input->get('category_idd')== $category['id']) { echo "selected";  }?> value="<?php echo $category['id']; ?>"><?php echo $category['cat_name']; ?></option>
<?php }
}
?>
</select>   
</div>
<div style="margin-left: -2%;" class="col-sm-2">
<button type="submit" class="btn btn-green" ><i class="fa fa-filter"></i> Filter</button>
</div>
<div style="margin-left: -5%;" class="col-sm-2"> 
<a href="<?php echo base_url(); ?>dashboard"><button class="m-l-lg btn ResetBtn" type="button">Reset</button></a>
</div>
</form>
</div>
</div>
</div>
</div>


</div>
</div>

</div>
<div class="row steperConte">
<div class="tab">
<button class="tablinks tb1 active" onclick="openCity(event, 'expert_listing')">
<div class="borderB_step">
<span class="step-1 label"> New Query <p class="circle steperCount-1 active"><?php echo $total1;?></p></span>
</div>
</button>
<button class="tablinks tb2" onclick="openCity(event, 'answer_expert_listing')">
<div class="borderB_step inprog">
<span class="step-2 label">Answered<p class="circle steperCount-2 inprogress"><?php echo $total2;?></p></span>
</div>
</button>
<button class="tablinks tb3" onclick="openCity(event, 'unanswer_expert_listing')">
<div class="borderB_step">
<span class="step-3 label">Rejected <p class="circle steperCount-3 Responded"><?php echo $total3;?></p></span>
</div>
</button>
</div>
</div>
<div id="expert_listing" class="tabcontent" style="display: block;">
<?php
//pr($dbdata,1);
if (!empty($dbdata)) {
foreach ($dbdata as $data) {
?>
<div class="exportTextAria end_user_listing_box" >
<div class="exportMsgCard desCard">

<div class="topheader">

<h5 style="text-align: justify;"><?php echo $data['query']; ?></h5>
<?php
 if ($data['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['query_image']; ?>" download >Download</a><br></span>
     
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['added_on']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data['category_name']; ?></span>
<span class="lblMsg">Status</span>:

<?php
if($data['status']=='2'){?>
<span class=" btn btn-danger">Rejected By Admin</span>
<?php }else{?>
<?php if (!empty($data['answerData'])) { ?>
    <!-- <span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['answerData']['answer_date']);?></span> -->
<span class="greenlable mr-w">Response from Expert</span>
<?php } else { ?>
    <span class="greenlable mr-w">Pending</span>
<?php } ?>
<?php }  ?>

</div>

<!--answer of question-->
<?php if(!empty($data['answerData']) && $data['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:30px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:30px">
    <?php } ?>

</div>
<div class="userName">
    <p><b><?php echo $data['answerData']['expert_name'];?></b></p>
    <p class="greenlable"><?php echo time_elapsed_string($data['answerData']['answer_date']);?></p>
</div>
<!--<p class="desMessage"><?php /*echo $data['answerData']['answer'];*/?></p>-->
<?php if (strlen($data['answerData']['answer']) > 220) {
    $string = substr($data['answerData']['answer'], 0, 220) . '..';
    $new_string = substr($data['answerData']['answer'], 220);
    ?>
    <p class="desMessage" data-toggle="collapse" data-target="#demo_<?php echo $data['assign_query_id']; ?>" aria-expanded="false">
  <?php $demoid = 'demo_'.$answer['assign_query_id']; 
                $demoidsort = 'demosort_'.$answer['assign_query_id']; 
                                ?>

                     <i  class="Chevron" onclick="showdetails('<?php echo $demoid;?>','<?php echo $demoidsort;?>')"></i>

                           <span id=<?php echo $demoidsort;?>><?php echo $string; ?></span>



       <!--  <i class="Chevron"> </i><?php echo $string; ?></p> -->
<?php } else { ?>
    <p style="text-align: justify;" class="desMessage"><?php echo $data['answerData']['answer']; ?></p>
<?php } ?>
<div id="demo_<?php echo $data['assign_query_id']; ?>"
     class="collapse" aria-expanded="false" style="">
    <p style="text-align: justify;" class="desMessage"><?php echo $data['answerData']['answer']; ?></p>
</div>
</div>
<?php if ($data['upload_image']) { ?>
   <a href="<?php echo base_url().$data['upload_image']; ?>" target="_blank">View</a>
   <a href="<?php echo base_url().$data['upload_image']; ?>" download>Download</a>

<?php } }?>
</div>
</div>
<div class="text-center"><?php echo isset($pagination) && $pagination!='' ? $pagination : '';?></div>
<?php }
} else { ?>
<div class="alert alert-danger">No queries found here.</div>
<?php } ?>

</div>
<div id="answer_expert_listing" class="tabcontent" style="display: none;">
<?php
//pr($dbdata,1);
if (!empty($dbdata1)) {
foreach ($dbdata1 as $data) {
?>
<div class="exportTextAria answer_expert_listing_box" >
<div class="exportMsgCard desCard">

<div class="topheader">

<h5 style="text-align: justify;"><?php echo $data['query']; ?></h5>
<?php
 if ($data['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['query_image']; ?>" download >Download</a><br></span>
  
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['added_on']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data['category_name']; ?></span>
<span class="lblMsg">Status</span>:

<?php
if($data['status']=='2'){?>
<span class=" btn btn-danger">Rejected By Admin</span>
<?php }else{?>
<?php if (!empty($data['answerData'])) { ?>
     <span class="greenlable mr-w">Completed</span>
    <!-- <span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['answerData']['answer_date']);?></span> -->
<?php } else { ?>
    <span class="greenlable mr-w">Pending</span>
<?php } ?>
<?php }  ?>

</div>

<!--answer of question-->
<?php if(!empty($data['answerData']) && $data['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:30px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:30px">
    <?php } ?>

</div>
<div class="userName">
    <p><b><?php echo $data['answerData']['expert_name'];?></b></p>
    <p class="greenlable"><?php echo time_elapsed_string($data['answerData']['answer_date']);?></p>
</div>
<!--<p class="desMessage"><?php /*echo $data['answerData']['answer'];*/?></p>-->
<?php if (strlen($data['answerData']['answer']) > 220) {
    $string = substr($data['answerData']['answer'], 0, 220) . '..';
    $new_string = substr($data['answerData']['answer'], 220);
    ?>
<div class="more_detail">
             <i style="margin-top: -20px;"  class="Chevron more_icon" ></i>
          <span class="less"> <?php echo $string;?></span><span class="more" style="display:none;"><?php echo $data['answerData']['answer']?></span>
       </div>
   <!--  <p class="desMessage" data-toggle="collapse" data-target="#demo_respond_<?php echo $data['assign_query_id']; ?>" aria-expanded="false">

          <?php $demoidres = 'demo_respond_'.$data['assign_query_id']; 
             $demoidsortres = 'demosort_respond_'.$data['assign_query_id'];
                                ?>

         <i  class="Chevron" onclick="showdetails('<?php echo $demoidres;?>','<?php echo $demoidsortres;?>')"> </i>

         <span id=<?php echo $demoidsortres;?>><?php echo $string; ?></span>

     </p> -->
       <!--  <i class="Chevron"> </i><?php echo $string; ?></p> -->
<?php } else { ?>
    <p style="text-align: justify;" class="desMessage"><?php echo $data['answerData']['answer']; ?></p>
<?php } ?>
<div id="<?php echo $demoidres; ?>"
     class="collapse" aria-expanded="false" style="">
    <p class="desMessage"><?php echo $data['answerData']['answer']; ?></p>
</div>
</div>
<?php if ($data['upload_image']) { ?>
   <a href="<?php echo base_url().$data['upload_image']; ?>" target="_blank">View</a>
   <a href="<?php echo base_url().$data['upload_image']; ?>" download >Download</a>

<?php }} ?>
</div>
</div>
<div class="text-center"><?php echo isset($pagination1) && $pagination1!='' ? $pagination1 : '';?></div>
<?php }
} else { ?>
<div class="alert alert-danger">No queries found here.</div>
<?php } ?>

</div>
<div id="unanswer_expert_listing" class="tabcontent" style="display: none;">
<?php
//pr($dbdata,1);
if (!empty($dbdata2)) {
foreach ($dbdata2 as $data) {
?>
<div class="exportTextAria unanswer_expert_listing_box" >
<div class="exportMsgCard desCard">

<div class="topheader">

<h5><?php echo $data['query']; ?></h5>
<?php
 if ($data['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['query_image']; ?>" download >Download</a><br></span>
  
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['added_on']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data['category_name']; ?></span>
<span class="lblMsg">Status</span>:

<?php
if($data['status']=='2'){?>
<span class=" btn btn-danger">Rejected</span>
<?php }else{?>
<?php if (!empty($data['answerData'])) { ?>

   <!--  <span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['answerData']['answer_date']);?></span> -->
<?php } else { ?>
    <span class="greenlable mr-w">Pending</span>
<?php } ?>
<?php }  ?>

</div>

<!--answer of question-->
<?php if(!empty($data['answerData']) && $data['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:30px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:30px">
    <?php } ?>

</div>
<div class="userName">
    <p><b><?php echo $data['answerData']['expert_name'];?></b></p>
    <p class="greenlable"><?php echo time_elapsed_string($data['answerData']['answer_date']);?></p>
</div>
<!--<p class="desMessage"><?php /*echo $data['answerData']['answer'];*/?></p>-->
<?php if (strlen($data['answerData']['answer']) > 220) {
    $string = substr($data['answerData']['answer'], 0, 220) . '..';
    $new_string = substr($data['answerData']['answer'], 220);
    ?>
    <p class="desMessage" data-toggle="collapse" data-target="#demo_<?php echo $data['assign_query_id']; ?>" aria-expanded="false"><i class="Chevron"> </i><?php echo $string; ?></p>
<?php } else { ?>
    <p class="desMessage"><?php echo $data['answerData']['answer']; ?></p>
<?php } ?>
<div id="demo_<?php echo $data['assign_query_id']; ?>"
     class="collapse" aria-expanded="false" style="">
    <p class="desMessage"><?php echo $new_string; ?></p>
</div>
</div>
<?php } ?>
</div>
</div>
<div class="text-center"><?php echo isset($pagination2) && $pagination2!='' ? $pagination2 : '';?></div>
<?php }
} else { ?>
<div class="alert alert-danger">No queries found here.</div>
<?php } ?>

</div>
</div>

</div>
</div>
<script src="<?php echo assets_url('js','loader.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
$(document).ready(function () {
<?php if($pagination && $pagination != ''){?>
LoadMoreDataDashboard('#expert_listing', '.end_user_listing_box', '#expert_listing div.pagination', '#expert_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination1 && $pagination1 != ''){?>
LoadMoreDataDashboard('#answer_expert_listing', '.answer_expert_listing_box', '#answer_expert_listing div.pagination', '#answer_expert_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination2 && $pagination2 != ''){?>
LoadMoreDataDashboard('#unanswer_expert_listing', '.unanswer_expert_listing_box', '#unanswer_expert_listing div.pagination', '#unanswer_expert_listing div.pagination li.active + li a', 'div');
<?php } ?>
});

$(document).ready(function(){
$(".test").click(function(evt){
var test1 = $(this).attr("id");
//alert (test1);
var u = "<?php echo base_url('dashboard/').'/';?>";
alert(u);
jQuery.ajax({

url:  u+test1,      
type: 'post',       
success: function(data) {
//response = jQuery.parseJSON(data);
console.log(data);
// $('#myModelData').html(data);
// $(".modal-body #bookId").val( myId );
// $('#myModal').modal('show');
}             
});

});
});
$('select').on('change', function() {
var test1 = this.value;
//alert( this.value );
jQuery.ajax({

url:  "<?php echo base_url('dashboard/')?>"+test1,   
type: 'post',


success: function() {
response = jQuery.parseJSON(data);
console.log(data);
// $('#myModelData').html(data);
// $(".modal-body #bookId").val( myId );
// $('#myModal').modal('show');
}             
});
});
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
$( function() {
$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
} );

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
if (end_date > d) {
    alert('End date Should not be greater than current date!');
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
if (start_date > d) {
    alert('Start date Should not be greater than current date!');
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

$(document).ready(function(){
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

$(document).on('click','.more_icon',function() {
                    console.log($(this).parent().find('.more').css('display'));    
                           if($(this).parent().find('.more').css('display') == 'none'){
                                $(this).parent().find('.less').slideUp('slow',function(){
                                   $(this).parent().find('.more').slideDown('slow');    
                                });
                                
                           }else{
                           $(this).parent().find('.more').slideUp('slow',function(){
                                $(this).parent().find('.less').slideDown('slow');
                                
                                }); 
                           }
  
 
});

showdetails = function(d,sd){
     $('#'+sd).toggle();
    // alert($('#'+sd).html());  

}

});

</script>
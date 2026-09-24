<?php
date_default_timezone_set('Asia/Kolkata');

$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />

<div class="container">
<div class="row ask_Export">
<div class="base_success_message"></div>
<div class="base_error_message"></div>
<?php $this->load->view('Includes/profilesidebar'); ?>

<div class="col-sm-9 col-md-9 col-lg-9 ">
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

<form method="get" action="<?php echo base_url('dashboard/');?>">

<div class="col-sm-3" > <input type="text"  placeholder=" Start Date" class="form-control datepicker" id="start_date" maxlength="255" autocomplete="off" value="<?php if ($this->input->get('start_date')){
echo $this->input->get('start_date');
} ?>" name="start_date" ></div>


<div style="margin-left: -2%;" class="col-sm-3">  <input type="text" placeholder="End Date" id="end_date" class="form-control datepicker"  maxlength="255" autocomplete="off" value="<?php  if ($this->input->get('end_date')){
echo $this->input->get('end_date');
} ?>" name="end_date"  ></div>


<div style="margin-left: -2%;" class="col-sm-3">   <select  class="form-control " name="category_idd"  >
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
<!-- Tab links -->
<div class="tab">
<button class="tablinks tb1 active" onclick="openCity(event, 'new_query_listing')">
<div class="borderB_step">
<span class="step-1 label"> New query <p class="circle steperCount-1 active"><?php echo $total1;?></p></span>
</div>
</button>
<button class="tablinks tb2" onclick="openCity(event, 'in_progress_listing')">
<div class="borderB_step inprog">
<span class="step-2 label">Respond<p class="circle steperCount-2 inprogress"><?php echo $total2;?></p></span>
</div>
</button>
<button class="tablinks tb3" onclick="openCity(event, 'responded_listing')">
<div class="borderB_step">
<span class="step-3 label"> Responded <p class="circle steperCount-3 Responded"><?php echo $total3;?></p></span>
</div>
</button>
</div>
<!-- Tab content -->
<div id="new_query_listing" class="tabcontent" style="display: block;">

<?php if (!empty($dbdata)) {
foreach ($dbdata as $data) {
?>
<div class="exportTextAria new_query_listing_box">
<div class="exportMsgCard desCard">
<div class="topheader">
<div class="media-left">
<?php if (!empty($data['enduser_image'])) { ?>
<img src="<?php echo image_url('users', $data['enduser_image']); ?>" class="media-object img-circle" style="width:40px">
<?php } else { ?>
<img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
<?php } ?>
</div>
<div class="media-right">
<h5 style="text-align: justify;"><?php echo $data['query']; ?></h5>
<?php
 if ($data['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['query_image']; ?>" download >Download</a><br></span>
  
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['added_on']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data['category_name']; ?></span>
</div>
</div>
<div class="clearfix">
<div class="pull-right">
<?php if($data['status']=='2') { ?>
<button type="submit" class="btn btn-danger">Discarded</button>
<?php }else{?>
<?php if($data['total_assign_to_expert']=='0') { ?>
    <button type="submit" class="btn transprantBtn redBtn discard_query" data-id="<?php echo $data['id'];?>">Discard</button>


<a style="border-color: green;
border-width: 2px;" title="Assign to expert" href="javascript:void(0)" data-href="<?php echo base_url('ajax/assign_to_expert/'.$data['id'].'/1') ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
        Assign to expert
    </a>


     <a style="border-color: #4886d2;
border-width: 2px;" title="Assign to expert" href="javascript:void(0)" data-href="<?php echo base_url('ajax/assign_to_expert/'.$data['id'].'/3') ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
        Assign to MD
    </a>



<?php } ?>
<?php } ?>
</div>
</div>
</div>
</div>
<?php }
} else { ?>
<div class="alert alert-danger">No queries found here.</div>
<?php } ?>
<div class="text-center"><?php echo isset($pagination) && $pagination!='' ? $pagination : '';?></div>
<!--end second card-->
</div><!-- first block -->

<div id="in_progress_listing" class="tabcontent" style="display: none;">
<?php
if (!empty($dbdata1)) {
foreach ($dbdata1 as $data1) {

$userIdForGettingType = $data1["answerData"][0]["user_id"];
$res = _getTheUserType($userIdForGettingType);
$typeUser = $res["role_type"];



?>
<div class="exportTextAria in_progress_listing_box">
<div class="exportMsgCard desCard">
<div class="topheader">
<div class="media-left">
<?php if (!empty($data1['user_image'])) { ?>
<img src="<?php echo image_url('users', $data1['user_image']); ?>" class="media-object img-circle" style="width:40px">
<?php } else { ?>
<img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
<?php } ?>
</div>
<div class="media-right">
<h5 style="text-align: justify;"><?php echo $data1['question']; ?></h5>
<?php

 if ($data1['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data1['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data1['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data1['asked_date']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data1['category_name']; ?></span>
<?php
$status = '1';
if (!empty($data1['answerData']) && count($data1['answerData'])=='1' && $data1['answerData'][0]['status']=='0') {
//$status = '0';
?>
<!-- <span class="lblMsg">Assign to</span>:<span class="greenlable mr-w"><?php echo $data1['answerData'][0]['expert_name']; ?></span>
<span class="lblMsg">Assign date</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data1['answerData'][0]['added_on']); ?></span> -->
<?php } ?>
</div>
</div>
<?php
if($data1['status']=='0'){?>
<div class="clearfix">
<div class="pull-right">

<?php if($typeUser == 3) { ?>
  <a style ="border-color: green;
border-width: 2px;" title="Re-assign to md" href="javascript:void(0)" data-href="<?php echo base_url('ajax/assign_to_expert/'.$data1['id'].'/3') ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
    Re-assign to md
</a>  

<!-- <a style="border-color: #4886d2;
border-width: 2px;" title="Re-assign to md" href="javascript:void(0)" class="btn AssignToExp transprantBtn assignExpertModal">
    Re-assign to MD
</a>  -->

<?php } else { ?>

<a style="border-color: green;
border-width: 2px;" title="Re-assign to expert" href="javascript:void(0)" data-href="<?php echo base_url('ajax/assign_to_expert/'.$data1['id'].'/1') ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
    Re-assign to expert
</a>

<?php }  ?>



</div>
</div>
<?php } ?>
<?php

if (!empty($data1['answerData']) && $status=='1') {
$status = 0;
foreach ($data1['answerData'] as $answer) {
?>

<div class="Answer">
    <div class="media-left">
        <p>Answer</p>
        <?php if (!empty($answer['expert_image'])) { ?>
            <img src="<?php echo image_url('users', $answer['expert_image']); ?>"
                 class="media-object img-circle" style="width:30px">
        <?php } else { ?>
            <img src="<?php echo assets_url('img', 'user.png'); ?>"
                 class="media-object img-circle" style="width:30px">
        <?php } ?>
    </div>
    <div class="userName">
        <p><b><?php echo $answer['expert_name']; ?></b></p>
        <?php
        $respond = '';
        if ($answer['status'] == '0') {
            $respond = '<span class="label bg-danger" title="Pending">Pending</span>';
        }
        if ($answer['status'] == '2') {
            $respond = '<span class="label bg-danger" title="Discarded By Expert">Discarded By Expert</span>';
        } ?>
        <p class="greenlable"><?php echo $answer['answer_date']!=NULL ? time_elapsed_string($answer['answer_date']) : $respond; ?></p>
    </div>
    <?php
    if ($answer['status'] == '1') {
    //print_r($answer); ?>
        <?php if (strlen($answer['answer']) > 220) {
            $string = substr($answer['answer'], 0, 220) . '...';
            $new_string = substr($answer['answer'], 220);
            ?>
            <div class="more_detail">
             <i style="margin-top: -20px;"  class="Chevron more_icon" ></i>
          <span class="less"> <?php echo $string;?></span><span class="more" style="display:none;"><?php echo $answer['answer']?></span>
       </div>
        <?php } else { ?>
            <p style="text-align: justify;" class="desMessage"><?php echo $answer['answer']; ?></p>
        <?php } ?>
        <div id="demo_<?php echo $answer['assign_query_id']; ?>"
             class="collapse" aria-expanded="false" style="">
            <p style="text-align: justify;" class="desMessage"><?php echo $answer['answer']; ?></p>
        </div>
        <?php if ($answer['upload_image']) { ?>
   <a href="<?php echo base_url().$answer['upload_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$answer['upload_image']; ?>" download >Download</a>
    
      <a href="<?php echo base_url(); ?>user/deleteAttachment/<?php echo $answer['assign_query_id'];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete
                                                </a>
    <?php } }?>
</div>
<?php
if ($answer['status'] == '1' && $data1['status']=='0') {
    ?>
    <div class="clearfix">
        <div class="pull-right">
           <!-- <button type="button" class="btn  transprantBtn">
                Review
            </button>-->
            <a title="Review" href="javascript:void(0)" data-href="<?php echo base_url('ajax/review/'.$answer['assign_query_id']) ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn ReviewModal">
                Review
            </a>
        </div>
    </div>
<?php } ?>
<?php
/*                                                    if ($answer['status'] == '2') {
    */?><!--
    <div class="clearfix">
        <div class="pull-right">
            <button type="button" class="btn  transprantBtn">
                Discarded By Expert
            </button>
        </div>
    </div>
<?php /*} */?>

<?php
/*                                                    if ($answer['status'] == '0') {
    */?>
    <div class="clearfix">
        <div class="pull-right">
            <button type="button" class="btn btn-danger">
                Respond Pending
            </button>
        </div>
    </div>
--><?php /*} */?>
<?php

}
} ?>
</div>
</div>
<?php }
}else { ?>
<div class="alert alert-danger">No respond queries found here.</div>
<?php } ?>
<div class="text-center"><?php echo isset($pagination1) && $pagination1!='' ? $pagination1 : '';?></div>

<!--end first card-->
</div><!-- second block -->

<div id="responded_listing" class="tabcontent" style="display: none;">
<?php if (!empty($dbdata2)) {
foreach ($dbdata2 as $data2) {

//print_r($data2);




$userIdForGettingTypeB = $data2["user_id"];
$res = _getTheUserType($userIdForGettingTypeB);
$typeUserB = $res["role_type"];
?>
<div class="exportTextAria responded_listing_box">
<div class="exportMsgCard desCard">
<div class="topheader">
<div class="media-left">
<?php if (!empty($data2['user_image'])) { ?>
<img src="<?php echo image_url('users', $data2['user_image']); ?>" class="media-object img-circle" style="width:40px">
<?php } else { ?>
<img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
<?php } ?>
</div>
<div class="media-right w-6adminD">
<h5 style="text-align: justify;"><?php echo $data2['query']; ?></h5>
 <?php
 if ($data2['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data2['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data2['query_image']; ?>" download >Download</a><br></span>
  
                                            <?php }?>
<span class="lblMsg">Asked</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data2['added_on']); ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data2['category_name']; ?></span>
</div>
</div>
<div class="clearfix">
<div class="pull-right">
<!-- <a title="Answer by Expert" href="javascript:void(0)" data-href="<?php echo base_url('ajax/answer_by_expert/'.$data2['id']);?>" data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
 -->    <a title="Answer by Expert" href="javascript:void(0)" data-href="<?php echo base_url('ajax/answer_by_expert/'.$data2['id']);?>" data-toggle="modal" class="btn AssignToExp transprantBtn "  style="pointer-events: none; border-color: green;border-width: 2px;" >
<?php if($typeUserB == 1) { ?>
    Answer by Expert
<?php } else { ?>
    Answer by Md
<?php }  ?>
</a>
</div>
</div>
<!--answer of question-->
<?php
// pr($data2,1);
if(!empty($data2['answerData'])){?>
<div class="Answer">
<div class="media-left">
<p>Answer</p>
<?php if (!empty($data2['answerData']['expert_image'])) { 
  
    ?>
    <img src="<?php echo image_url('users', $data2['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:30px">
<?php } else { ?>
    <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:30px">
<?php } ?>

</div>
<div class="userName">
<p><b><?php echo $data2['answerData']['expert_name'];?></b></p>
<p class="greenlable"><?php echo time_elapsed_string($data2['answerData']['answer_date']);?></p>
</div>
<!--<p class="desMessage"><?php /*echo $data2['answerData']['answer'];*/?></p>-->
<?php if (strlen($data2['answerData']['answer']) > 220) {
$string = substr($data2['answerData']['answer'], 0, 220) . '..';
$new_string = substr($data2['answerData']['answer'], 220);
?>
<!-- <p class="desMessage" data-toggle="collapse" data-target="#demo_respond_<?php echo $data2['id']; ?>" aria-expanded="false">

   <?php $demoidres = 'demo_respond_'.$data2['id']; 
             $demoidsortres = 'demosort_respond_'.$data2['id'];
                              ?>

         <i  class="Chevron" onclick="showdetails('<?php echo $demoidres;?>','<?php echo $demoidsortres;?>')"> </i>

        
<span id=<?php echo $demoidsortres;?>><?php echo $string; ?></span>
     </p> -->
      <div class="more_detail">
             <i style="margin-top: -20px;"  class="Chevron more_icon" ></i>
          <span class="less"> <?php echo $string;?></span><span class="more" style="display:none;"><?php echo $data2['answerData']['answer']?></span>
       </div>
<?php } else { ?>
<p style="text-align: justify;" class="desMessage"><?php echo $data2['answerData']['answer']; ?></p>
<?php } ?>
<div id="demo_respond_<?php echo $data2['id']; ?>"
 class="collapse" aria-expanded="false" style="">
<p style="text-align: justify;" class="desMessage"><?php echo ($data2['answerData']['answer']); ?></p>
</div>
</div>
<?php if ($data2['upload_image']) { ?>
   <a href="<?php echo base_url().$data2['upload_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data2['upload_image']; ?>" download>Download</a>
<?php }} ?>
</div>
</div>
<?php }
} else { ?>
<div class="alert alert-danger">No responded queries found here.</div>
<?php } ?>
<div class="text-center"><?php echo isset($pagination2) && $pagination2!='' ? $pagination2 : '';?></div>
<!--end first card-->
</div><!-- third block -->
</div>
<!-- ------------------------------------vcvcvcv------------------------------------- -->


</div>
</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo assets_url('css', 'toastr.min.css'); ?>">
<script src="<?php echo assets_url('js','toastr.min.js');?>"></script>
<script src="<?php echo assets_url('js','loader.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
$(document).ready(function ($) {
<?php if($pagination && $pagination != ''){?>
LoadMoreDataDashboard('#new_query_listing', '.new_query_listing_box', '#new_query_listing div.pagination', '#new_query_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination1 && $pagination1 != ''){ ?>

LoadMoreDataDashboard('#in_progress_listing', '.in_progress_listing_box', '#in_progress_listing div.pagination', '#in_progress_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination2 && $pagination2 != ''){?>
LoadMoreDataDashboard('#responded_listing', '.responded_listing_box', '#responded_listing div.pagination', '#responded_listing  div.pagination li.active + li a', 'div');
<?php } ?>
$(document).on('click', '.discard_query', function () {
var id = $(this).attr('data-id');
var _this = $(this);
if (confirm("Are you sure to discard this query ?")) {
var posturl = BASE_URL + 'ajax/discard_query';
$.ajax({
url: posturl,
dataType: 'json',
type: "POST",
data: {query_id: id},
beforeSend: function () {
ajaxLoaderStart();
},
complete: function () {
ajaxLoaderStop();
},
success: function (data) {
toastr.options.timeOut = 2000; // How long the toast will display without user interaction
toastr.options.extendedTimeOut = 1000; // How long the toast will display after a user hovers over it
toastr.options.progressBar = true;
if (data.message) {
_this.removeClass('transprantBtn redBtn discard_query').addClass('btn-danger').text('Discarded');
_this.siblings('a.AssignToExp').hide();
toastr.success(data.message);
}
else {
toastr.error(data.message);
}
}
});
}
});
});
</script>


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
//alert(start_date);
end_date.setHours(end_date.getHours() -5);
//alert(end_date);

if (end_date > d) {
    alert('End date should not be greater than current date!');
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
start_date.setHours(start_date.getHours() -5);
if (start_date > d) {
    alert('Start date should not be greater than current date!');
    $(this).val('');
return false;
}
if(days >= 1){
alert('start date should not be freater than End date!')
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
<?php
// echo $total1;
// echo $total2;
// echo $total3;die;
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<div class="container">
<div class="row ask_Export">
<?php $this->load->view('Includes/profilesidebar'); ?>

<div class="col-sm-9 col-md-9 col-lg-9">
<div class="panel-body removepadTop">
<div class="row steperConte">
<div class="row">
<div class="panel panel-default panel-collapsed">
<div class="panel-heading font-bold filtrResult">
<h4><i class="fa fa-filter "></i>&nbsp;Filter Your Results </h4>
</div>
<div class="panel-body">
<div class="row">
<div class="col-md-12">
<div class="row wrapper">

<form method="get" action="<?php echo base_url('dashboard/'); ?>">


    <div class="col-sm-3" > <input type="text"  placeholder=" Start Date" class="form-control datepicker" id="start_date" maxlength="255"  value="<?php if ($this->input->get('start_date')){
echo $this->input->get('start_date');
} ?>" name="start_date"autocomplete="off" ></div>


<div style="margin-left: -2%;" class="col-sm-3">  <input type="text" placeholder="End Date" id="end_date" class="form-control datepicker"  maxlength="255"  value="<?php  if ($this->input->get('end_date')){
echo $this->input->get('end_date');
} ?>" name="end_date" autocomplete="off" ></div>

    <div style="margin-left: -2%;" class="col-sm-3"><select
                class="form-control " name="category_idd">
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
        <button type="submit" class="btn btn-green"><i
                    class="fa fa-filter"></i> Filter
        </button>
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
<!-- Tab links -->
<div class="tab">
<button class="tablinks tb1 active" onclick="openCity(event, 'expert_listing')">
<div class="borderB_step">
<span class="step-1 label"> All Query <p class="circle steperCount-1 active"><?php echo $total1;?></p></span>
</div>
</button>
<button class="tablinks tb2" onclick="openCity(event, 'answer_expert_listing')">
<div class="borderB_step inprog">
<span class="step-2 label">Answered Query<p class="circle steperCount-2 inprogress"><?php echo $total2;?></p></span>
</div>
</button>
<button class="tablinks tb3" onclick="openCity(event, 'unanswer_expert_listing')">
<div class="borderB_step">
<span class="step-3 label"> UnAnswered Query <p class="circle steperCount-3 Responded"><?php echo $total3;?></p></span>
</div>
</button>
</div>
<!-- Tab content -->
<div id="expert_listing" class="tabcontent" style="display: block;">

<?php
//pr($dbdata,1);
if (!empty($dbdata)) {
foreach ($dbdata as $data) {
?>
<div class="exportTextAria expert_listing_box">
<div class="exportMsgCard desCard">
<div class="topheader">
<div class="media-left">
<?php if (!empty($data['user_image'])) { ?>
    <img src="<?php echo image_url('users', $data['user_image']); ?>" class="media-object img-circle" style="width:40px">
<?php } else { ?>
    <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
<?php } ?>
</div>
<div class="media-right w-6adminD">
<h5><?php echo nl2br($data['question']); ?></h5>
<?php
 if ($data['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
<span class="lblMsg">Asked on</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data['added_on']); ?></span>
<span class="lblMsg">Asked by</span>:<span class="greenlable mr-w"><?php echo $data['name']; ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data['category_name']; ?></span>
</div>
</div>
<div class="clearfix">
<div class="pull-right">
<?php if ($data['respond_date'] == NULL) { ?>
    <?php if ($data['status'] == '2') { ?>
        <button type="submit" class="btn btn-danger">Discarded</button>
    <?php } else { ?>
        <button type="submit" class="btn transprantBtn redBtn discard_query" data-id="<?php echo $data['id']; ?>">Discard</button>
        <a title="Respond" href="javascript:void(0)" data-href="<?php echo base_url('ajax/respond_by_expert/'.$data['id']) ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
            Respond
        </a>
    <?php } ?>
<?php }
else { ?>
    <!--<button type="button" class="btn AssignToExp transprantBtn">Responded</button>-->
<?php } ?>
</div>
</div>
<!--answer of question-->
<?php if(!empty($data['answerData']) && $data['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:40px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
    <?php } ?>

</div>
<div class="userName">
    <p><b style="text-align: justify;"><?php echo $data['answerData']['expert_name'];?></b></p>
    <p class="greenlable" style="text-align: justify;"><?php echo time_elapsed_string($data['answerData']['answer_date']);?></p>
</div>

<?php if (strlen($data['answerData']['expert_answer']) > 220) { ?>
   <div class="more_detail">
            <i style="margin-top: -20px;margin-right: 2%;"  class="Chevron more_icon" ></i>
            <div class="less" style=" line-height: 1.5em; height: 3em; overflow: hidden;"> <?php echo $data['answerData']['expert_answer'];?></div>

            <div class="more" style="display:none;"><?php echo $data['answerData']['expert_answer'];?></div>
          </div>
<?php } else { ?>
    <p style="text-align: justify;" class="desMessage"><?php echo $data['answerData']['expert_answer']; ?></p>
<?php } ?>
<div id="<?php echo $demoid; ?>"
     class="collapse" aria-expanded="false" >
    <p style="text-align: justify;" class="desMessage"><?php echo $data['answerData']['expert_answer']; ?></p>
</div>
</div>
<?php if ($data['answerData']['upload_image']) { 
   
    ?>

   <a href="<?php echo base_url().$data['answerData']['upload_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data['answerData']['upload_image']; ?>" download >Download</a>


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
foreach ($dbdata1 as $data1) {
?>
<div class="exportTextAria answer_expert_listing_box">
<div class="exportMsgCard desCard">
<div class="topheader">
<div class="media-left">
<?php if (!empty($data1['user_image'])) { ?>
    <img src="<?php echo image_url('users', $data1['user_image']); ?>" class="media-object img-circle" style="width:40px">
<?php } else { ?>
    <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
<?php } ?>
</div>
<div class="media-right w-6adminD">
<h5><?php echo nl2br($data1['question']); ?></h5>
<?php
 if ($data1['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data1['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data1['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
<span class="lblMsg">Asked on</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data1['added_on']); ?></span>
<span class="lblMsg">Asked by</span>:<span class="greenlable mr-w"><?php echo $data1['name']; ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data1['category_name']; ?></span>
</div>
</div>
<div class="clearfix">
<div class="pull-right">
<?php if ($data1['respond_date'] == NULL) { ?>
    <?php if ($data1['status'] == '2') { ?>
        <button type="submit" class="btn btn-danger">Discarded</button>
    <?php } else { ?>
        <button type="submit" class="btn transprantBtn redBtn discard_query" data-id="<?php echo $data1['id']; ?>">Discard</button>
        <a title="Respond" href="javascript:void(0)" data-href="<?php echo base_url('ajax/respond_by_expert/'.$data1['id']) ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
            Respond
        </a>
    <?php } ?>
<?php }
else { ?>
    <!--<button type="button" class="btn AssignToExp transprantBtn">Responded</button>-->
<?php } ?>
</div>
</div>
<!--answer of question-->
<?php if(!empty($data1['answerData']) && $data1['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data1['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data1['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:40px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
    <?php } ?>

</div>
<div class="userName">
    <p><b><?php echo $data1['answerData']['expert_name'];?></b></p>
    <p class="greenlable"><?php echo time_elapsed_string($data1['answerData']['answer_date']);?></p>
</div>

<?php if (strlen($data1['answerData']['expert_answer']) > 220) { ?>
    <div class="more_detail">
            <i style="margin-top: -20px;margin-right: 2%;"  class="Chevron more_icon" ></i>
            <div class="less" style=" line-height: 1.5em; height: 3em; overflow: hidden;"> <?php echo $data1['answerData']['expert_answer'];?></div>

            <div class="more" style="display:none;"><?php echo $data1['answerData']['expert_answer'];?></div>
          </div>
<?php } else { ?>
    <p style="text-align: justify;" class="desMessage"><?php echo $data1['answerData']['expert_answer']; ?></p>
<?php } ?>
<div id="<?php echo $demoid; ?>"
     class="collapse" aria-expanded="false" >
    <p style="text-align: justify;" class="desMessage"><?php echo $data1['answerData']['expert_answer']; ?></p>
</div>
</div>
<?php if ($data1['answerData']['upload_image']) { 
  
   
    ?>

   <a href="<?php echo base_url().$data1['answerData']['upload_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data1['answerData']['upload_image']; ?>" download >Download</a>

<?php } }?>
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
foreach ($dbdata2 as $data2) {
?>
<div class="exportTextAria unanswer_expert_listing_box">
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
<h5><?php echo nl2br($data2['question']); ?></h5>
<?php
 if ($data2['query_image']) { ?>
    <span class="lblMsg">Attachment</span>:<span> <a href="<?php echo base_url().$data2['query_image']; ?>" target="_blank">View</a>
    <a href="<?php echo base_url().$data2['query_image']; ?>" download >Download</a><br></span>
  
    
    
                                            <?php }?>
<span class="lblMsg">Asked on</span>:<span class="greenlable mr-w"><?php echo convert_sqltime_to_calnderdate($data2['added_on']); ?></span>
<span class="lblMsg">Asked by</span>:<span class="greenlable mr-w"><?php echo $data2['name']; ?></span>
<span class="lblMsg">Category</span>:<span class="greenlable mr-w"><?php echo $data2['category_name']; ?></span>
</div>
</div>
<div class="clearfix">
<div class="pull-right">
<?php if ($data2['respond_date'] == NULL) { ?>
    <?php if ($data2['status'] == '2') { ?>
        <button type="submit" class="btn btn-danger">Discarded</button>
    <?php } else { ?>
        <button type="submit" class="btn transprantBtn redBtn discard_query" data-id="<?php echo $data2['id']; ?>">Discard</button>
        <a title="Respond" href="javascript:void(0)" data-href="<?php echo base_url('ajax/respond_by_expert/'.$data2['id']) ?>"  data-toggle="modal" class="btn AssignToExp transprantBtn assignExpertModal">
            Respond
        </a>
    <?php } ?>
<?php }
else { ?>
    <!--<button type="button" class="btn AssignToExp transprantBtn">Responded</button>-->
<?php } ?>
</div>
</div>
<!--answer of question-->
<?php if(!empty($data2['answerData']) && $data2['status']!='2'){?>
<div class="Answer">
<div class="media-left">
    <p>Answer</p>
    <?php if (!empty($data2['answerData']['expert_image'])) { ?>
        <img src="<?php echo image_url('users', $data2['answerData']['expert_image']); ?>" class="media-object img-circle" style="width:40px">
    <?php } else { ?>
        <img src="<?php echo assets_url('img', 'user.png'); ?>" class="media-object img-circle" style="width:40px">
    <?php } ?>

</div>
<div class="userName">
    <p><b><?php echo $data2['answerData']['expert_name'];?></b></p>
    <p style="text-align: justify;" class="greenlable"><?php echo time_elapsed_string($data2['answerData']['answer_date']);?></p>
</div>

<?php if (strlen($data2['answerData']['expert_answer']) > 220) {?>
     <div class="more_detail">
            <i style="margin-top: -20px;margin-right: 2%;"  class="Chevron more_icon" ></i>
            <div class="less" style=" line-height: 1.5em; height: 3em; overflow: hidden;"> <?php echo $data2['answerData']['expert_answer'];?></div>

            <div class="more" style="display:none;"><?php echo $data2['answerData']['expert_answer'];?></div>
          </div>
<?php } else { ?>
    <p style="text-align: justify;" class="desMessage"><?php echo $data2['answerData']['expert_answer']; ?></p>
<?php } ?>
<div id="demo_<?php echo $data2['id']; ?>"
     class="collapse" aria-expanded="false" style="">
    <p class="desMessage"><?php echo $new_string; ?></p>
</div>
</div>
<?php } ?>
</div>
</div>
<div class="text-center"><?php echo isset($pagination2) && $pagination2!='' ? $pagination2 : '';?></div>
<?php }
}
else { ?>
<div class="alert alert-danger">No queries found here.</div>
<?php } ?>

</div>

</div>
</div>
</div>

</div>
</div>
<script src="<?php echo assets_url('js','loader.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo assets_url('css', 'toastr.min.css'); ?>">
<script src="<?php echo assets_url('js','toastr.min.js');?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript">
<?php if($pagination && $pagination != ''){?>
LoadMoreDataDashboard('#expert_listing', '.expert_listing_box', '#expert_listing div.pagination', '#expert_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination1 && $pagination1 != ''){?>
LoadMoreDataDashboard('#answer_expert_listing', '.answer_expert_listing_box', '#answer_expert_listing div.pagination', '#answer_expert_listing div.pagination li.active + li a', 'div');
<?php } ?>
<?php if($pagination2 && $pagination2 != ''){?>
LoadMoreDataDashboard('#unanswer_expert_listing', '.unanswer_expert_listing_box', '#unanswer_expert_listing div.pagination', '#unanswer_expert_listing div.pagination li.active + li a', 'div');
<?php } ?>

$(document).on('click', '.discard_query', function () {
var id = $(this).attr('data-id');
var _this = $(this);
if (confirm("Are you sure to discard this query ?")) {
var posturl = BASE_URL + 'ajax/discard_assigned_query';
$.ajax({
url: posturl,
dataType: 'json',
type: "POST",
data: {query_id: id},
beforeSend: function(){
ajaxLoaderStart();
},
complete: function(){
ajaxLoaderStop();
},
success: function (data) {
toastr.options.timeOut = 1000; // How long the toast will display without user interaction
toastr.options.extendedTimeOut = 1000; // How long the toast will display after a user hovers over it
toastr.options.progressBar = true;
if (data.success) {
_this.removeClass('transprantBtn redBtn discard_query').addClass('btn-danger').text('Discarded');
_this.siblings('a.AssignToExp').hide();
toastr.success(data.message);
location.reload();
}
else {
toastr.error(data.message);
}
}
});
}

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
// alert(d);
// alert(end_date);
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
if (start_date > d) {
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
 //alert($('#'+d).html());  

}
});

</script>
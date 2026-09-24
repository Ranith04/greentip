<style type="text/css">
  .modal-contentt {
         position: absolute;
    border-top: 5px solid #0ea543;
    background-color: white;
    width: inherit!important;

    
}
.pagination>li>a, .pagination>li>span {
    
     float: none!important;    
}
</style>
<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
$start = validateURI($this->segment) != '' ? validateURI($this->segment) : '0';
?>
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
                   <form method="post" id="filter_form" name="filter_form" action="<?php if(isset($getData['page']) && $getData['page']!='0' && $getData['page']!='all'){echo base_url('user/mdNewQueriesReports/'.$getData['page']);}else{ echo base_url('user/mdNewQueriesReports'); } ?>" class="form-horizontal">
                        <div class="panel panel-default panel-collapsed">
                            <div class="panel-heading font-bold filtrResult">
                                <h4><i class="fa fa-search "></i>&nbsp;Filter Your Results &nbsp;[&nbsp;New Query&nbsp;]</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row wrapper">
                                            <div class="col-sm-3" > <input type="text"  placeholder=" Start Date" class="form-control datepicker" id="start_date" maxlength="255"  value="<?php if(isset($FormData['range']['q-from'])){echo $FormData['range']['q-from'];} ?>" name="FormData[range][q-from]" id="start_date" autocomplete="off"></div>


                                            <div style="margin-left: -2%;" class="col-sm-3">  <input type="text" placeholder="End Date" id="end_date" class="form-control datepicker"  maxlength="255"  value="<?php if(isset($FormData['range']['q-to'])){echo $FormData['range']['q-to'];} ?>" name="FormData[range][q-to]" id="end_date" autocomplete="off" ></div>



                                             <div style="margin-left: -2%;" class="col-sm-2"> 
                                                 <button type="button" class="btn btn-primary" id="filter"><i class="fa fa-filter"></i> Filter</button>
                                             </div>


                                             <div style="margin-left: -5%;" class="col-sm-2"> 
                                                <a href="<?php echo base_url(); ?>user/mdNewQueriesReports/all"><button class="m-l-lg btn ResetBtn" type="button">Reset</button></a>
                                             </div>


                                             <div class="col-sm-2"> 

                                                <?php  if(isset($FormData) && count($FormData)>0) { 


                                        $startDate = $FormData['range']['q-from'];

                                        $endDate = $FormData['range']['q-to'];

                                      ?>

<a style=" margin-left: 112%!important;" href="<?php echo base_url(); ?>user/newExcel/<?php echo $startDate;?>/<?php echo $endDate;?>"><button class="m-l-lg btn btn-success" type="button">Excel</button></a>
                                       

                                                <?php } else { 

                                        $startDate = "";

                                        $endDate = "";

                                                    ?>

<a style=" margin-left: 112%!important;" href="<?php echo base_url(); ?>user/newExcel/<?php echo $startDate;?>/<?php echo $endDate;?>"><button class="m-l-lg btn btn-success" type="button">Excel</button></a>

                                                <?php } ?>

                                                
                                             </div>


                                            <input type="hidden" name="FormData[sort][page]" id="page" value="<?php if(isset($getData['page'])) {echo $getData['page']; } ?>"/>
                                           
                                            <input type="hidden" name="FormData[form_name]" id="form_name" value="" />

                                        </div>
                                    </div>
                                </div>
                              
                                
                            </div>
                        </div>
                    </form>
                </div>
              
                <div class="row">
                    <div class="col-sm-12 table-responsive manageUserTable">
                        <table class="table datatable dataTable no-footer">
                            <thead>
                            <tr>
                                <th>
                                   #
                                </th>
                                <th><a class="heading" >Query</a></th>
                                
                                <th><a class="heading">Category</a></th>
                                
                                 <th><a class="heading">Status</a></th>
                                <th><a class="heading">Added On</a></th>
                                <th><a class="heading">View</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($records)){

                              if($start == 0)
                              {
                                $i = 1;
                              }
                              else
                              {
                                $i = $start +1;
                              }
                                
                                foreach ($records as $value){?>
                                    <tr>
                                        <td>
                                            <?php echo $i;?>
                                        </td>

                                        
                                       
                                       <!--  <td><?php echo  ucwords($value['query']); ?></td> -->
                                        <td><?php
                                        $position=20; // Define how many character you want to display.
                                        $message= $value['query'];
                                        $post = substr($message, 0, $position);
                                        echo $post;
                                        echo "...";
                                        ?> </td>
                                       
                                        <td><?php echo $value['category_name']?></td>
                                        
                                        <td><?php if($value['status']==0)
                                                  {
                                                    echo " <span style='color: #a89e32;'>Pending </span>";
                                                  }
                                                  else if($value['status']==1)
                                                  {
                                                    echo "<span style='color: green;'>Completed </span>";
                                                  }
                                                  else
                                                  {
                                                    echo " <span style='color: red;'>Rejected </span>";
                                                  }



                                        ?></td>

                                        <td><?php echo date("Y-m-d", strtotime($value['added_on']))?></td>

                                        <td><button class="btn AssignToExp transprantBtn  "  data-id="<?php echo $value['id']; ?>"data-toggle="modal" >View</button></td>
                                    </tr>
                                <?php $i++;
                                }
                            }
                            else{?>
                                <tr>
                                    <td colspan="8"> <div class="alert alert-danger text-center">No query is found here.</div></td>
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

<div style="
    padding-left: 16px;" id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-contentt">
      <!-- <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
      </div> -->
     
      <div class="modal-body" id="myModelData" style="height: 300px; ">
      
      </div>
     
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script src="<?php echo assets_url('js', 'CustomJs.js'); ?>"></script>
<?php $this->load->view('Includes/footer'); ?>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">



$(document).on("click", ".AssignToExp", function () {
    
    var myId = $(this).data('id');
     //alert(myId);
     
    
      jQuery.ajax({
      
        url:  "<?php echo base_url();?>user/mdNewQueriesReportsmodel/all/"+myId,      
        type: 'post',
        
        success: function(data) {
            //response = jQuery.parseJSON(data);
           console.log(data);
            $('#myModelData').html(data);
           
            $('#myModal').modal('show');
        }             
    });
     }); 
    SyonApp.setPage('MdNewManager');
    SyonApp.init();
</script>

<script>
  $( function() {
   // $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
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

</script>

</body>
</html>
<?php $this->load->view('includes/header_script');?>
<div class="app app-header-fixed  ">
    <?php $this->load->view('includes/header'); ?>
    <?php $this->load->view('includes/sidebar'); ?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">
            <div class="wrapper-md">
                <?php
                $this->load->view('includes/msg_alert'); ?>

                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        <a href="<?php echo base_url() ;?>admin/pages/index/all" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </a>
                        <h4><i class="fa fa-plug"></i> Edit Page</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row wrapper">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <form class="form-horizontal" method="post" name="PageEdit" id="PageEdit" enctype="multipart/form-data" action="">

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageTitle">Page Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" readonly="true" class="form-control" name="title" id="title" value="<?php echo ($dbdata['title'])?$dbdata['title']:set_value('title'); ?>">
                                                    <?php echo form_error('title'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->

                                            <!-- Form Group Start -->

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageContent">Page Content</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="description" id="description"  cols="10" rows="3"><?php if((isset($_POST['description'])) && ($_POST['description']!='')){ echo $_POST['description'];} else { echo $dbdata['content']; }?></textarea>
                                                    <?php echo form_error('description'); ?>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageContent">Links</label>
                                                <div class="col-sm-10">

                                                    <div class="field_wrapper">
                                                        <div >
                                                            <input  style="width: 40%;" type="text" name="namee[]" value="" placeholder="Name" />
                                                            <input style="width: 45%;"  type="text" name="link_name[]" value="" placeholder="link" />
                                                            <a  style=" float: right;margin-right: 1%;" href="javascript:void(0);" class="add_button" title="Add field"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/add-icon.png"/></a>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <?php if(count($link)>0) { ?>
                                                
                                                 <?php $i=1;  foreach($link as $row) 
                                                         { ?>

                                                <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageContent"><?php echo  $i++; ?></label>
                                                 <div class="col-sm-4">

                                                            <p><?php echo strtoupper($row["name"]); ?></p>

                                                </div>
                                                <div class="col-sm-4">

                                                            <p><a style="color:#7266ba" href="<?php echo $row["value"]; ?>" target="_blank"><?php echo $row["value"]; ?></a></p>

                                                </div>

                                                <div class="col-sm-1">
                                                   <a href="<?php echo base_url();?>admin/pages/deletelinkdoc/<?php echo $row["id"]; ?>/<?php echo $pagesid; ?>"  onclick="return confirmation()">X</a>
                                                </div>
                                                </div>
                                                         <?php }
                                                 ?>
                                             <?php } ?>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>


                                            <div class="form-group">
                                                
                                                 <label class="col-sm-2 control-label" for="PageContent">Documents</label>
                                                <div style="margin-top: 0.5%;" class="col-sm-10">
                                                    
                                                  <div class="field_wrapper1">
                                                        <div style="width: 100%;" >
                                                            <div style="width: 40%; float: left;"><input style="width: 100%;" type="text"  name="name[]" placeholder="Name" ></div>
                                                              
                                                                <div style="width: 45%; float: left; margin-left: .5%;"><input  type="file"  name="userfile[]" id="doc" ></div>
                                                                 <div style=" float: right;margin-right: 1%;">
                                                                   <a href="javascript:void(0);" class="add_button1" title="Add field"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/add-icon.png"/></a></div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            
                                             <?php if(count($doc)>0) {

                                            // print_r($doc);die; ?>
                                                
                                                 <?php $i=1;  foreach($doc as $row1) 
                                                         { //print_r($row1); ?>

                                                <div class="form-group">
                                                <label class="col-sm-2 control-label" for="PageContent"><?php echo  $i++; ?></label>
                                                 <div class="col-sm-4">

                                                  <p><?php echo strtoupper($row1["name"]); ?></p>

                                                </div>
                                                <div class="col-sm-4">

                                                <a style="color:#7266ba" href="<?php echo $row1["value"]; ?>" download>Download</a></p>

                                                </div>

                                                <div class="col-sm-1">
                                                   <a href="<?php echo base_url();?>admin/pages/deletelinkdoc/<?php echo $row1["id"]; ?>/<?php echo $pagesid; ?>"  onclick="return confirmation()">X</a>
                                                </div>
                                                </div>
                                                         <?php }
                                                 ?>
                                             <?php } ?>


                                              <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <!-- Form Group End -->


                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <a href="<?php echo getUrl(base_url('admin/pages')); ?>" class="btn btn-default">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('includes/footer'); ?>
</div>
<?php $this->load->view('includes/footer_scripts'); ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var addButton1 = $('.add_button1');
    var fieldHTML = '<div style="margin-top:5px;"><input  style="width: 40%;" type="text" name="namee[]"  value="" placeholder="Name" /><input style="width: 45%;margin-left: .5%;" type="text" name="link_name[]" value="" placeholder="link" /><a style="padding-right: 1%; float: right;"  href="javascript:void(0);" class="remove_button"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/remove-icon.png"/></a></div>'; //New input field html 
     var wrapper1 = $('.field_wrapper1');
     var fieldHTMLL = ' <div style="width: 100%; margin-top:5px; float: left;" ><div style="width: 40%; float: left;"><input style="width: 100%;" type="text"  name="name[]" placeholder="Name" ></div> <div style="width: 48%; float: left; margin-left: .5%;"><input  type="file"  name="userfile[]" id="doc"  ></div><div style=" float: right;margin-right: 1%;"><a style="margin-left: .5%;"   href="javascript:void(0);" class="remove_button1"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/remove-icon.png"/></a></div></div>';
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    $(addButton1).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper1).append(fieldHTMLL); //Add field html
        }
    });
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
    $(wrapper1).on('click', '.remove_button1', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
function confirmation() {
   // alert("hello");
      return confirm('Are you sure you want to delete this record?');
    }



</script>

<script type="text/javascript">
    SyonApp.setPage('PageEdit');
    SyonApp.init();
</script>
</body>
</html>
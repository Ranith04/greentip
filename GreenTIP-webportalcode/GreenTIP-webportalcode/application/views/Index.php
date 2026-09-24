<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>

<section class="main-content">
    <div class="body-container">
        <div class="container">
            <!--<div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <h3 class="title bord-b">About GreenTIP</h3>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12 pl-5">
                    <h3 class="title text-left">GRC GreenTIP (Technical Interactive Platform)</h3>

                    <div class="panel-group">
                        <div class="panel panel-default">
                            <p class="text-content">
                                GreenTIP is an interactive platform developed by Grass Roots Research & Creation India (P) Limited to hand-hold with stakeholders in the field of environment for sustainable development. GRC India (P) Ltd. is a pioneer environmental consultancy organization working in the field of environment and EIA since 2006.  GRC India has been providing optimal solutions for Environmental Clearances for Industrial and Infrastructural sectoral projects. We have a pool of experts for the entire range of functional areas, who can guide and provide the most satisfactory solutions in the environmental field.</p>
                            <p class="text-content">
                                For more information regarding GRC India, you can visit our website www.grc-india.com<br><br>
                                The modus operandi for creating the GreenTIP platform is to make people aware of environmental
                                issues and their probable and possible solutions which they try and find out at various locations...
                            </p>
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1" class="down-arrow" aria-expanded="false" aria-controls="collapseOne"> <img src="<?php /*echo assets_url('img', 'down-arrow.png'); */?>" alt=""> </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-footer">
                                    <p class="text-content">
                                        GreenTIP is an interactive platform developed by Grass Roots Research & Creation India (P) Limited to hand-hold with stakeholders in the field of environment for sustainable development. GRC India (P) Ltd. is a pioneer environmental consultancy organization working in the field of environment and EIA since 2006.  GRC India has been providing optimal solutions for Environmental Clearances for Industrial and Infrastructural sectoral projects. We have a pool of experts for the entire range of functional areas, who can guide and provide the most satisfactory solutions in the environmental field.</p>
                                    <p class="text-content">
                                        For more information regarding GRC India, you can visit our website www.grc-india.com<br><br>
                                        The modus operandi for creating the GreenTIP platform is to make people aware of environmental
                                        issues and their probable and possible solutions which they try and find out at various locations...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <h3 class="title bord-b">How <br>GreenTIP Works?</h3>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12 pl-5">
                    <div class="howit-work">
                        <h3 class="title text-left">GreenTIP platform is a web-based portal developed for those professionals who are trying to find environmental solutions and this portal provides the best solutions with concrete answers. </h3>
                        <ul class="list-group">
                            <li class="list-item"><img src="<?php /*echo assets_url('img', 'work-icon.png'); */?>" alt=""> <p>A new user will initially have to visit this website grcgreentip.com and sign up as a user before posting any query</p> </li>
                            <li class="list-item"><img src="<?php /*echo assets_url('img', 'work-icon.png'); */?>" alt=""> <p>Once logged in as a user, one can ask questions related to Construction, Real Estate, Environmental Clearance, NOCs, Mining, Envio-legal matters, and similar sectoral queries by selecting the category from the drop-down list</p></li>
                            <li class="list-item"><img src="<?php /*echo assets_url('img', 'work-icon.png'); */?>" alt=""> <p>Once a query is posted, it will be notified on our portal and it will be assigned to our experts to provide timely answer and solution to it</p></li>
                            <li class="list-item"><img src="<?php /*echo assets_url('img', 'work-icon.png'); */?>" alt=""> <p>For posting any query, click on "Post Your Query" box at the About Us page, log in as a user and then select the category and type your comments in the query box.</p></li>
                        </ul>
                    </div>
                </div>
            </div>-->
            <?php echo $about_us['content']; ?>
        </div><!-- container -->
    </div><!-- body-container -->
</section>
<section class="query_section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="ask_query">
                    <h2>Ask Your Query</h2>
                    <p>(Real Estate, Construction Others)</p>

                    <div class="arrow_image">
                        <!--<button type="button" class="btn btn-postquery">Post your Query</button>-->
                        <a class="btn btn-postquery" href="<?php echo base_url('dashboard'); ?>">Post Your Query</a>
                        <img src="<?php echo assets_url('img', 'arrow.png'); ?>" alt="">
                    </div>
                </div>

            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="gate_imgs">
                    <!-- <img src="images/door.png" alt=""> -->
                </div>
            </div>
        </div>
    </div>

</section>
<div class="divider">
    &nbsp;
</div>


<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModalForPageLoad">Open Modal</button> -->
  <!-- Modal -->
  <!-- <div class="modal fade myModalForPageLoad" id="myModalForPageLoad" role="dialog" >
    <div class="modal-dialog">-->
    
      <!-- Modal content-->
      <!-- <div class="modal-content" style="width: 100%!important; margin-left: 0px!important;">-->
       <!--  <div class="modal-body"> -->
        <!--  <img class="img-responsive" src="<?php echo base_url("GRCGREENTIP.jpeg");?>">-->
       <!--  </div> -->
      <!-- </div>
      
    </div>
  </div> -->
<style type="text/css">
    .panel-default>.panel-heading a[aria-expanded=false]:after {
        content: none;
    }
    .panel-default>.panel-heading a[aria-expanded=true]:after {
        content: none;
    }
</style>
<?php $this->load->view('Includes/footer'); ?>
<script type="text/javascript"> 
$(function () {
  $("#myModalForPageLoad").modal("show");
});

setTimeout(function(){
  $('#myModalForPageLoad').modal('hide')
}, 10000);

</script>
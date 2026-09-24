<?php
$this->load->view('Includes/header_script');
$this->load->view('Includes/header');
?>
<section class="main-content">
    <div class="body-container">
        <div class="container">
            <?php if($slug=='about-us'){?>
                <!--<h3 class="title">About<font> <font>Us</font></font></h3>-->
            <?php }else{?>
                <h3 class="title"><font><?php echo $dbdata['title']; ?></font></h3>
            <?php } ?>

             <?php if($slug=='knowledge-center') { ?>

                   <section class="query_section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <img src="<?php echo base_url();?>knowledge-center.png">
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="">
                        <br> <br>
                      <p style="text-align: justify;"><?php echo nl2br($dbdata['content']); ?></p>
                    </div>

                </div>   
            </div>

            <?php
            //knowledge_center_links_doc
            //1-doc  for doc
            $query = $this->db->select("*")->from("knowledge_center_links_doc")->where("type", 1)->order_by("id", "DESC")->get();

            $doc = $query->result_array(); 

            //0-link  for link
            $query1 = $this->db->select("*")->from("knowledge_center_links_doc")->where("type", 0)->order_by("id", "DESC")->get();
            $link = $query1->result_array(); 


            ?>

             <hr style="height:2px;border-width:0;color:#0d9e40;background-color:#0d9e40">
             <!--  Links and documents -->
            
            <?php foreach($doc as $row) { ?>
             <div class="row" style="margin-bottom: 6px;">
                <div class="col-md-9 col-sm-9 col-xs-12">
                     <?php echo strtoupper($row["name"]);?>
                    <!-- <i style="color: #e43425;" class="fa fa-file-pdf-o " aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<a style="color: black;" href="<?php echo $row["value"];?>" download><?php echo end(explode("/", $row["value"]));?></a> -->
                </div>   
                <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                    <a style=" color: #0d9e40;margin-right: 1%;" href="<?php echo $row["value"];?> "  >Download</a>
                      <a title="Download" href="<?php echo $row["value"];?>" download><i style="color:#0d9e40" class="fa fa-download" aria-hidden="true"></i></a>
                </div>     
            </div>
           
           <?php } ?>
           <hr>
           <?php foreach($link as $row) { ?>
               <div class="row" style="margin-bottom: 6px;">
                <div class="col-md-9 col-sm-9 col-xs-12">
                     <?php echo strtoupper($row["name"]);?>
                   <!--  <a href="<?php echo $row["value"];?>" target="_blank"><?php echo $row["value"];?></a> -->
                </div> 
                <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                       <a title="<?php echo $row["value"];?>" href="<?php echo $row["value"];?>" target="_blank"> <span style="color: #0d9e40; margin-right: 1%;" >View</span> <i style="color:#0d9e40" class="fa fa-link" aria-hidden="true"></i></a>
                </div>   
                 
            </div>
         
           <?php } ?>

        </div>

    </section>


             <?php } else { ?>

            <?php echo $dbdata['content']; 

            } ?>

            <?php if($slug=='about-us'){?>
            <!--<div class="row">
                <div class="expert_container">
                    <h3 class="title">Ask Your <font>Query</font> (Real state,Construction,Others)</h3>
                    <center><a class="btn btn-success" href="<?php /*echo base_url('dashboard'); */?>">Post Your Query</a></center>
                </div>
            </div>-->
                <!--<div class="row">
                    <div class="expert_container">
                        <h3 class="title">Our <font>Experts</font></h3>
                        <?php /*if(!empty($experts)) {
                            foreach ($experts as $expert){*/?>
                                <div class="col-sm-6 col-md-6 col-lg-3 outer_block">
                                    <div class="col-sm-12 expert_block">
                                        <div class="padding">
                                            <div class="media">
                                                <div class="media-left">
                                                    <?php /*if (!empty($expert['image'])) { */?>
                                                        <img src="<?php /*echo image_url('users', $expert['image'], 60, 60); */?>" class="media-object  circle-avatar nks-img" style="width:60px">
                                                    <?php /*}else{*/?>
                                                        <img src="<?php /*echo assets_url('grocery_crud', 'themes/bootstrap/img/a0.jpg'); */?>" class="media-object circle-avatar nks-img" style="width:60px">
                                                    <?php /*} */?>
                                                    <h4 class="media-heading"><?php /*echo character_limiter($expert['name'],20); */?></h4>
                                                    <h3><span>Company Name:</span><?php /*echo $expert['company_name']!='' ? $expert['company_name'] : '--'; */?></h3>
                                                </div>
                                                <div class="media-body compny_block">
                                                    <p><span>Occupation:</span> <?php /*echo $expert['occupation']!='' ? $expert['occupation'] : '--'; */?></p>
                                                    <p><span>Education Qualification:</span> <?php /*echo $expert['education_qualification']!='' ? $expert['education_qualification'] : '--'; */?></p>
                                                    <p><span>Expertise Field:</span> <?php /*echo $expert['expertise_field']!='' ? $expert['expertise_field'] : '--'; */?></p>
                                                </div>
                                            </div>
                                            <p class="descrip">
                                                <?php
/*                                                $string = $expert['description'];
                                                if (strlen($string) > 120) {
                                                    $trimstring = substr($string, 0, 120). ' <a href="javascript:void(0)" data-href="'.base_url('ajax/view_expert_detail/'.$expert['id']).'"  data-toggle="modal" class="assignExpertModal">readmore...</a>';
                                                } else {
                                                    $trimstring = $string;
                                                }
                                                echo $trimstring; */?>
                                            </p>
                                        </div>
                                        <div class="icon-bar">
                                            <p><a href="<?php /*echo $expert['linkdin']!='' ? $expert['linkdin'] : '#'; */?>" target="_blank" class="first"><i class="fa fa-linkedin-square"></i>Linkedin</a></p>
                                            <p><a href="<?php /*echo $expert['facebook']!='' ? $expert['facebook'] : '#'; */?>" target="_blank" class="second"><i class="fa fa-facebook-square"></i>Facebook</a></p>
                                        </div>
                                    </div>
                                </div>
                            <?php /*}
                        }
                        else{*/?>
                            <div class="alert alert-danger">No experts found here.</div>
                        <?php /*}*/?>
                        <?php /*if($total_expert>4){*/?>
                            <div class="col-sm-12 experts-btn">
                                <a href="<?php /*echo base_url('experts'); */?>" class="btn btn-default">View All Experts</a>
                            </div>
                        <?php /*} */?>
                    </div>
                </div>-->
            <?php } ?>
        </div>
    </div><!-- body-container -->
</section>
<?php if($slug=='about-us'){?>
    <section class="query_section post_query_mobile">
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
    <div class="divider post_query_mobile">
        &nbsp;
    </div>
    <style type="text/css">
        .panel-default>.panel-heading a[aria-expanded=false]:after {
            content: none;
        }
        .panel-default>.panel-heading a[aria-expanded=true]:after {
            content: none;
        }
    </style>
<?php } ?>
<?php $this->load->view('Includes/footer');?>

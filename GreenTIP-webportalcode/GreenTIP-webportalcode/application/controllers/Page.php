<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller
{
    var $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('pages_model');
    }

    function pageDetail($slug = '')
    {
        if ($slug) {
            $row = $this->common_model->_selectById('b_pages', '', array('alias' => $slug));
            if ($row) {
                $data['slug'] = $slug;
                $data['dbdata'] = $row;
                $data['title'] = ADMIN_COMPANY." | " . $row['title'];
                if($slug=='about-us'){
                    $data['experts'] = $this->common_model->_select('b_users','*',array('status'=>'1','role_type'=>'1'),'id','DESC','0','4');
                    $total_expert = $this->common_model->_selectByID('b_users','COUNT(*) as total',array('status!='=>'3','role_type'=>'1'));
                    $data['total_expert'] = $total_expert['total'];
                }
                $this->load->view('Page', $data);
            } else {
                $this->output->set_status_header(404);
                $this->load->view('opps');
            }


        } else {
            $this->output->set_status_header(404);
            $this->load->view('opps');
        }
    }



 function pageDetailForMobile($slug = '')
    {
        if ($slug) {
            $row = $this->common_model->_selectById('b_pages', '', array('alias' => $slug));
            if ($row) {
                $data['slug'] = $slug;
                $data['dbdata'] = $row;
                $data['title'] = ADMIN_COMPANY." | " . $row['title'];
                if($slug=='about-us'){
                    $data['experts'] = $this->common_model->_select('b_users','*',array('status'=>'1','role_type'=>'1'),'id','DESC','0','4');
                    $total_expert = $this->common_model->_selectByID('b_users','COUNT(*) as total',array('status!='=>'3','role_type'=>'1'));
                    $data['total_expert'] = $total_expert['total'];
                }
                $this->load->view('Page_mobile', $data);
            } else {
                $this->output->set_status_header(404);
                $this->load->view('opps');
            }


        } else {
            $this->output->set_status_header(404);
            $this->load->view('opps');
        }
    }



function aboutUspageDetailForMobile()
    {  
        $slug = 'about-us';
        if ($slug) {
            $row = $this->common_model->_selectById('b_pages', '', array('alias' => $slug));
            if ($row) {
                $data['slug'] = $slug;
                $data['dbdata'] = $row;
                $data['title'] = ADMIN_COMPANY." | " . $row['title'];
                if($slug=='about-us'){
                    $data['experts'] = $this->common_model->_select('b_users','*',array('status'=>'1','role_type'=>'1'),'id','DESC','0','4');
                    $total_expert = $this->common_model->_selectByID('b_users','COUNT(*) as total',array('status!='=>'3','role_type'=>'1'));
                    $data['total_expert'] = $total_expert['total'];
                }
                $this->load->view('about_us_for_mobile', $data);
            } 
        } 
    }






}
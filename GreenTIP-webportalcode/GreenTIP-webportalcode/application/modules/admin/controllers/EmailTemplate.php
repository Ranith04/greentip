<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailTemplate extends MX_Controller
{
    var $perPage = '10';
    var $segment = '4';
    public $viewData = array();
    public $loggedInAdmin = array();

    public function __construct()
    {
        parent::__construct();
        isLoggedIn($type = 'admin');
        $this->loggedInAdmin = getSessionUserData('auth_admin_data');
        $this->load->model('admin_model');
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model('template_model');

        customPagination();
    }


    public function index()
    {
       $isAll = getStringSegment(4) ? getStringSegment(4) : false;
       if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('EmailManager');
        }
        $prevSessData = getSessionUserData('EmailManager');
        $conditionArray=$prevSessData;
        $conditionArray['equal']['template_type'] = 'email';
        if($isAll!='all')
        {
            $start =validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page']=$start;
        }
        else{
            $start='0';
            $getData['page']='';
        }
        $getField= $this->input->get();
        $sortField= isset($prevSessData['sort']['field'])?$prevSessData['sort']['field']:'id';
        $order= isset($prevSessData['sort']['order'])?$prevSessData['sort']['order']:'desc';
        $page_num = (int)$this->uri->segment(4);
        if($page_num==0) $page_num=1;
        if($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $emailDataCount = $this->template_model->record_count($conditionArray);
        $emailData = $this->template_model->get_EmailTemplate($start, $this->perPage,$conditionArray);
        $emailPagination = createPagination('admin/email-template/index',$emailDataCount,$this->perPage,$this->segment,$getField);
        $this->viewData['pagination'] = $emailPagination;
        $this->viewData['dbdata'] = $emailData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['FormData'] =$prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['title'] = 'Admin Panel | Manage Emails';
        $this->load->view('EmailTemplates/List',$this->viewData);
    }
    public function view()
    {
        $templateId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($templateId != '') {
            $dbdata = $this->template_model->_selectById(array('id'=>$templateId));
            if ($dbdata == false) {
                setSessionFlashData('error', 'No Template found.');
                redirect(base_url('admin/email-template/index'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin |Manage Emails";
                $this->load->view('EmailTemplates/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }
    public function edit()
    {
        $templateId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($templateId != '') {
            $dbdata = $this->template_model->_selectById(array('id'=>$templateId));
            if (!empty($dbdata)) {
                $this->set_rules('editTemplate');
                if ($this->form_validation->run($this) == true) {
                    $formData = $this->input->post();
                    if ($this->common_model->_CheckExistence('email_subject', $formData['email_subject'], 'b_email_templates', array('id !=' => $templateId, 'template_type =' => 'email')) == false) {
                        setSessionFlashData('error', 'Email Subject already exist. So please try another Subject');
                    }
                    else{
                        $dataArray['email_name'] = $this->input->post('email_name');
                        $dataArray['email_subject'] = $this->input->post('email_subject');
                        $dataArray['email_content'] = $this->input->post('email_content');
                        $this->common_model->_update('b_email_templates', $dataArray,array('id'=>$templateId));
                        setSessionFlashData('success', 'You have successfully updated detail of template');
                        redirect(base_url('admin/email-template'));
                    }
                }
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin | Manage Emails";
                $this->load->view('EmailTemplates/Edit', $this->viewData);
            }
        }
        else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('admin/email-template'));
        }

    }
    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error">', '</span>');
        if ($option == 'editTemplate') {
            $this->form_validation->set_rules('email_name', 'Email Name', 'required');
            $this->form_validation->set_rules('email_subject', 'Email Subject', 'required');
        }

    }

}

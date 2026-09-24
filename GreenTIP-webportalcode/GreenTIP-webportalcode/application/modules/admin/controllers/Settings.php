<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller
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
        $this->load->model('setting_model');

        customPagination();
    }


    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $getData['page']=$isAll;
        $getData=$this->input->get();
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('SettingsManager');
        }
        $prevSessData = getSessionUserData('SettingsManager');
        $conditionArray=$prevSessData;
        if($isAll!='all')
        {
            $start =validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page']=$start;
        }
        else{
            $start='0';
            $getData['page']='';
        }
        $this->viewData['title'] = 'Admin Panel | Settings Manager';
        $this->viewData['data'] = array();
        $getField= $this->input->get();
        $sortField= isset($prevSessData['sort']['field'])?$prevSessData['sort']['field']:'id';
        $order= isset($prevSessData['sort']['order'])?$prevSessData['sort']['order']:'desc';
        $page_num = (int)$this->uri->segment(4);
        if($page_num==0) $page_num=1;
        if($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $userDataCount = $this->setting_model->record_count($conditionArray);
        $userData = $this->setting_model->get_settings($start, $this->perPage,$conditionArray);
        $userPagination = createPagination('admin/settings/index',$userDataCount,$this->perPage,$this->segment,$getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['dbdata'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['FormData'] =$prevSessData;

        $this->load->view('Settings/List',$this->viewData);
    }
    public function edit()
    {
        $settingId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($settingId != '') {
            $dbdata = $this->setting_model->_selectById(array('id'=>$settingId));
            if (!empty($dbdata)) {
                $this->set_rules('editSettings');
                if ($this->form_validation->run($this) == true) {
                        $dataArray['option_value'] = $this->input->post('value');
                        $this->common_model->_update('b_settings', $dataArray,array('id'=>$settingId));
                        setSessionFlashData('success', 'You have successfully updated detail');
                        redirect(base_url('admin/settings'));
                    }
                }
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin | Manage Settings";
                $this->load->view('Settings/Edit', $this->viewData);
            }
        else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('admin/settings'));
        }

    }

    function change_country()
        {

        }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error">', '</span>');
        if ($option == 'editTemplate') {
            $this->form_validation->set_rules('email_name', 'Email Name', 'required|max_length[30]|min_length[6]');
            $this->form_validation->set_rules('email_subject', 'Email Subject', 'required|max_length[30]|min_length[6]');
        }
        if ($option == 'editSettings') {
            $this->form_validation->set_rules('value', 'Field', 'required');
        }
    }

}

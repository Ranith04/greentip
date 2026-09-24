<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller
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
        $this->module_id = 1;
        customPagination();
    }

    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $userId = $FormData['csv_ids_hidden'];
            $result = explode(',', $userId);
            $status_id = $FormData['purpose_hidden'];

            /*if ($status_id == "1") {
                $status = 'Approved';
                foreach ($result as $user_id) {
                    $user = $this->common_model->_selectById("b_industrial_users", 'name,email', array("id" => $user_id));
                    $pass = randomGenerateString(6);
                    $updateData['password'] = $this->user_model->hash($pass);
                    $keywords = array('NAME' => $user['name'], 'USER_EMAIL' => $user['email'], 'PASSWORD' => $pass);
                    SendEmailByTemplate(3, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    $this->common_model->_update('b_industrial_users', $updateData, array('id' => $user_id));
                }

            }
            else if ($status_id == "0") {
                $status = 'Awaited';
                foreach ($result as $user_id) {
                    $user = $this->common_model->_selectById("b_industrial_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(4, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
            }
            else if ($status_id == "2") {
                $status = 'Rejected';
                foreach ($result as $user_id) {
                    $user = $this->common_model->_selectById("b_industrial_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(5, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
            }
            else*/
            if ($status_id == "0") {
                $status = 'Inactive';
            }
            if ($status_id == "1") {
                $status = 'Active';
            } else if ($status_id == "3") {
                $status = 'Deleted';
            }
            if ($this->common_model->updateWhereIn('b_industrial_users', array('status' => $status_id), 'id', $result)) {
                $message = 'User ' . $status . ' successfully.';
            }

            setSessionFlashData('success', $message);
        }
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('UserManager');
        }
        $prevSessData = getSessionUserData('UserManager');
        $conditionArray = $prevSessData;

        $conditionArray['equal']['b_industrial_users.status!='] = '3';

        if ($isAll != 'all') {
            $start = validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $this->viewData['title'] = 'Admin Panel | User Manager';
        $this->viewData['data'] = array();
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $page_num = (int)$this->uri->segment(4);
        if ($page_num == 0) $page_num = 1;
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $userDataCount = $this->user_model->industrial_record_count($conditionArray);
        $userData = $this->user_model->get_industrial_users($start, $this->perPage, $conditionArray);
        //echo $this->db->last_query();die;
        $userPagination = createPagination('admin/users/index/', $userDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['dbdata'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['dataQuery'] = $this->db->last_query();
        /*pr($this->db->last_query(),1);*/
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['pageBreadCrumbs'] = "Manage Users";
        $this->load->view('Users/List', $this->viewData);
    }

    public function add()
    {
        
        
        $this->set_rules('Users');

        if ($this->form_validation->run($this) !== FALSE) {
          
            $dataArray['category_id'] = $this->input->post('category_id');
            $dataArray['company_name'] = $this->input->post('company_name');
            $dataArray['contact_person'] = $this->input->post('first_contact_person')." ".$this->input->post('middle_contact_person')." ".$this->input->post('last_contact_person');
            $dataArray['firstName'] = $this->input->post('first_contact_person');
            $dataArray['middleName'] = $this->input->post('middle_contact_person');
            $dataArray['lastName'] = $this->input->post('last_contact_person');
            $dataArray['email'] = $this->input->post('email');
            if($this->input->post('alternative_email')){
            $dataArray['alternative_email'] = $this->input->post('alternative_email');
            }
            $dataArray['contact_no'] = trim($this->input->post('phone_number')) != '' ? trim($this->input->post('phone_number')) : '';
            if($this->input->post('alter_phone_number')){
            $dataArray['alter_contact_no'] = $this->input->post('alter_phone_number');
             }
            // $dataArray['alter_contact_no'] = trim($this->input->post('alter_phone_number')) != '' ? trim($this->input->post('alter_phone_number')) : '';
            $dataArray['status'] = '1';
            $dataArray['created_on'] = set_local_to_gmt();
            $dataArray['updated_on'] = set_local_to_gmt();
            //print_r($dataArray);die;
            $userID = $this->common_model->_insertReturnId('b_industrial_users', $dataArray);
            if ($userID > 0) {

                 $industUser = $this->common_model->_select('b_industrial_categories','*',['id'=>$dataArray['category_id']],'cat_name','ASC');
                /*Email ------*/


                $subject = 'GreenTIP : Industrial User Creation';
                    $reply_msg = "<p><b>Dear ".$dataArray['contact_person'].",</b></p>
                    <p>Welcome to GRC GreenTIP.</p> <p>You're receiving this mail because your name has been recently registered as industrial user.</p>
     <p>Category : '<b>".$industUser[0]["cat_name"]."<b/>'</p>";


                    $this->viewData['msg_body'] = $reply_msg;
                  
                    $msg_header = $this->load->view('email/headerForBulk', $this->viewData, true);

                    $this->load->library('email');
                    // does not have to be gmail
                    $config['protocol'] = "smtp";
                    $config['smtp_host'] = SERVER;
                    $config['smtp_port'] = PORT;
                    $config['smtp_user'] = USERNAME;
                    $config['smtp_pass'] = PASSWORD;
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['newline'] = "\r\n";
                    $config['wordwrap'] = TRUE;
                    $this->email->initialize($config);
                    $this->email->from(ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    $this->email->to($dataArray['email']);
                    $this->email->subject($subject);
                    $this->email->message($msg_header);
                    $this->email->send();

               /* -----------*/
               
                setSessionFlashData('success', 'Congrats! You have successfully added new user');
                redirect(base_url('admin/users'));
            }
        }
        $this->viewData['categories'] = $this->common_model->_select('b_industrial_categories','*',['status'=>'1'],'cat_name','ASC');
        $this->viewData['title'] = 'Admin Panel | Add User';
        $this->viewData['pageBreadCrumbs'] = '<a href="' . base_url() . 'admin/users"> Users </a> > Add User';
        $this->viewData['data'] = array();
        $this->load->view('Users/Add', $this->viewData);
    }

    public function edit()
    {
        $userId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($userId != '') {
            $dbdata = $this->user_model->industrial_profile($userId, '', []);
            if (!empty($dbdata)) {
                $this->set_rules('editUser');
            
                if ($this->form_validation->run($this) == true) {
                    
                    $dataArray['category_id'] = $this->input->post('category_id');
                    $dataArray['company_name'] = $this->input->post('company_name');
                    // $dataArray['contact_person'] = $this->input->post('contact_person');
                     $dataArray['contact_person'] = $this->input->post('first_contact_person')." ".$this->input->post('middle_contact_person')." ".$this->input->post('last_contact_person');
                    $dataArray['firstName'] = $this->input->post('first_contact_person');
                    $dataArray['middleName'] = $this->input->post('middle_contact_person');
                    $dataArray['lastName'] = $this->input->post('last_contact_person');
                    $dataArray['email'] = $this->input->post('email');
                    if($this->input->post('alternative_email')){
                    $dataArray['alternative_email'] = $this->input->post('alternative_email');
                     }
                    $dataArray['contact_no'] = trim($this->input->post('phone_number')) != '' ? trim($this->input->post('phone_number')) : '';
                    if ($this->input->post('alter_phone_number')) {
                       
                    
                     $dataArray['alter_contact_no'] = trim($this->input->post('alter_phone_number')) != '' ? trim($this->input->post('alter_phone_number')) : '';
                     }
                    $dataArray['updated_on'] = set_local_to_gmt();
                    //print_r($dataArray);die;
                    $userID = $this->common_model->_update('b_industrial_users', $dataArray, array('id' => $userId));
                    if ($userID > 0) {

                        setSessionFlashData('success', 'You have successfully updated detail of user');
                        redirect(base_url('admin/users'));
                    }
                }
                $this->viewData['categories'] = $this->common_model->_select('b_industrial_categories','*',['status'=>'1'],'cat_name','ASC');
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['pageBreadCrumbs'] = '<a href="' . base_url() . 'admin/users"> Users </a> > Edit User';
                $this->viewData['title'] = "Admin | Manage Users";
                $this->load->view('Users/Edit', $this->viewData);
            } else {
                setSessionFlashData('error', 'No User found.');
                redirect('admin/users/');
            }
        } else {

            setSessionFlashData('error', 'Something went wrong....');
            redirect('admin/users/');
        }

    }

    public function view()
    {
        $userId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($userId != '') {
            $dbdata = $this->user_model->industrial_profile($userId, '', []);
            if ($dbdata == false) {
                setSessionFlashData('error', 'No User found.');
                redirect(base_url('admin/users/'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['pageBreadCrumbs'] = '<a href="' . base_url() . 'admin/users"> Users </a> > Manage User';
                $this->viewData['title'] = "Admin |Manage Users";
                $this->load->view('Users/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

    public function phone_number($email)
    {
        $id = $this->uri->segment(4);
        $condition = array('status!=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition = array('id !=' => $id, 'status!=' => '3');
        }
        if ($email != '') {
            if ($this->common_model->_CheckExistence('contact_no', $email, 'b_industrial_users', $condition)) {
                return true;
            } else {
                $this->form_validation->set_message('phone_number', 'Whoops! Phone Number already exists. Please try with another Phone Number');
                return false;
            }
        }
    }

    public function useremail_check($str)
    {
        $id = $this->uri->segment(4);
        $condition = array('status!=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition = array('id !=' => $id, 'status!=' => '3');
        }
        if ($this->common_model->_CheckExistence('email', $str, 'b_industrial_users', $condition)) {
            return true;
        } else {
            $this->form_validation->set_message('useremail_check', 'Whoops! User Email already exists. Please try with another User Email.');
            return false;
        }
    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
        if ($option == 'Users') {
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('first_contact_person', 'Contact Person FirstName', 'required');
             // $this->form_validation->set_rules('middle_contact_person', 'Contact Person Middle Name', 'required');
              $this->form_validation->set_rules('last_contact_person', 'Contact Person Last Name', 'required');

            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|integer|max_length[10]|callback_phone_number');
            //$this->form_validation->set_rules('phone_number', 'Phone Number', 'required|integer');
             // $this->form_validation->set_rules('alter_phone_number', 'Alternative Phone Number', 'required|integer');
        }
        if ($option == 'editUser') {
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            //$this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
            $this->form_validation->set_rules('first_contact_person', 'Contact Person FirstName', 'required');
            // $this->form_validation->set_rules('middle_contact_person', 'Contact Person Middle Name', 'required');
            $this->form_validation->set_rules('last_contact_person', 'Contact Person Last Name', 'required');

            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
           // $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|integer|max_length[10]|callback_phone_number');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|integer');
        }
    }

}

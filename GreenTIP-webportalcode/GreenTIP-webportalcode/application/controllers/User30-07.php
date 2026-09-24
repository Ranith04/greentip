<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    var $perPage = '10';
    var $segment = '3';
    public $viewData = array();
    public $loggedInUser = array();
    public $UserDetail = array();

    public function __construct()
    {

        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model("emaillog_model");
        $this->load->library('form_validation');
        $this->loggedInUser = getSessionUserData('id');

        if (isset($this->loggedInUser) && !empty($this->loggedInUser)) {
            $this->UserDetail = getUserInfo($this->loggedInUser, 'users');
        }
        else
        {
            redirect(base_url()); die;
        }
        // customPagination();
    }

    public function dashboard()
    {

         try
        {
             tryLogPrinter("dashboard", $params);


        /*0 for admin 1 for expert 2 for end user*/
        isLoggedIn($type = 'user');
        if ($this->input->post('short_by') == 'old') {
            // echo "ghii";die;
            $orderby = 'ASC';
            //$this->db->order_by('q.id', 'ASC');
        } else if ($this->input->post('short_by') == 'new') {
            $orderby = 'DESC';
            //$this->db->order_by('q.id', 'DESC');
        } else {
            $orderby = "";
        }
        $k = $this->input->post('category_idd');

        if ($this->input->post('category_idd') == $k) {
            $category_id = $k;
            // $this->db->where('q.category_id =', $k);
        }

        $profile = $this->user_model->profile($this->UserDetail['id']);

        $this->viewData['result'] = $profile;
        $start = validateURI($this->segment) != '' ? validateURI($this->segment) : '0';
        $response_type = getStringSegment(2);

        if (isset($this->UserDetail['role_type']) && $this->UserDetail['role_type'] == '0') {

            if ($response_type == '' || $response_type == 'new_query') {
                $condition = array('q.status!=' => '1');
                $result = $this->user_model->getNewQuery('b_queries', $start, $this->perPage, '*', $condition);
                $listings = [];
                $pagination = '';
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                    $pagination = createPaginationLoad('dashboard/new_query', $result['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['categories'] = $this->common_model->_select('b_categories', '*', ['status' => '1'], 'id', 'ASC');
                $this->viewData['total1'] = $result['total_rows'];
                $this->viewData['pagination'] = $pagination;
                $this->viewData['dbdata'] = $listings;
            }

            if ($response_type == '' || $response_type == 'in_progress_query') {
                /*Respond Process Data Get*/
                $condition = array('q.status!=' => '1');
                $result1 = $this->user_model->getRespondQuery('b_query_assign', $start, $this->perPage, '*', $condition);
                //pr($result1,1);
                $listings1 = [];
                $pagination1 = '';
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                    $pagination1 = createPaginationLoad('dashboard/in_progress_query', $result1['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination1'] = $pagination1;
                $this->viewData['total2'] = $result1['total_rows'];
                $this->viewData['dbdata1'] = $listings1;
                /*--End New Query Data*/
            }

            if ($response_type == '' || $response_type == 'responded_query') {
                $condition2 = array('qa.status' => '1', 'review_status' => '1');
                $result2 = $this->user_model->getRespondedQuery('b_query_assign', $start, $this->perPage, '*', $condition2);
                $listings2 = [];
                $pagination2 = '';

                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                    $pagination2 = createPaginationLoad('dashboard/responded_query', $result2['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination2'] = $pagination2;
                $this->viewData['dbdata2'] = $listings2;
                $this->viewData['total3'] = $result2['total_rows'];

            }

            $this->load->view('User/SuperAdminDashboard', $this->viewData);

        }


        if (isset($this->UserDetail['role_type']) && $this->UserDetail['role_type'] == '1') {
            if ($response_type == '' || $response_type == 'expert') {
               // $condition = array('qa.user_id' => $this->UserDetail['id']);
                 if ($k) {
                    $condition = array('qa.user_id' => $this->UserDetail['id'], 'q.category_id =' => $k);
                } else {
                    $condition = array('qa.user_id' => $this->UserDetail['id']);
                }
                $result = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition, $orderby);
                $listings = [];
                $pagination = '';
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                    $pagination = createPaginationLoad('dashboard/expert', $result['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['categories'] = $this->common_model->_select('b_categories', '*', ['status' => '1'], 'id', 'ASC');
                $this->viewData['pagination'] = $pagination;
                $this->viewData['dbdata'] = $listings;
                $this->viewData['total1'] = $result['total_rows'];
            }

            if ($response_type == '' || $response_type == 'answered_expert') {
                //$condition1 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status!=' => '0');
                if ($k) {
                    $condition1 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status!=' => '0', 'q.category_id =' => $k);
                } else {
                    $condition1 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status!=' => '0');
                }
                $result1 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition1, $orderby);
                $listings1 = [];
                $pagination1 = '';
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                    $pagination1 = createPaginationLoad('dashboard/answered_expert', $result1['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination1'] = $pagination1;
                $this->viewData['dbdata1'] = $listings1;
                $this->viewData['total2'] = $result1['total_rows'];
            }


            if ($response_type == '' || $response_type == 'unanswered_expert') {
                //$condition2 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status' => '0');
                 if ($k) {
                    $condition2 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status' => '0', 'q.category_id =' => $k);
                } else {
                    $condition2 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status' => '0');
                }
                $result2 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition2, $orderby);
                $listings2 = [];
                $pagination2 = '';
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                    $pagination2 = createPaginationLoad('dashboard/unanswered_expert', $result2['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination2'] = $pagination2;
                $this->viewData['dbdata2'] = $listings2;
                $this->viewData['total3'] = $result2['total_rows'];
            }
             $this->viewData['orderby'] = $orderby;
            $this->viewData['category123'] = $k;

            $this->load->view('User/ExpertDashboard', $this->viewData);
        }

        /*     if (isset($this->UserDetail['role_type']) && $this->UserDetail['role_type'] == '2') {

                 if ($response_type == '' || $response_type == 'end_user') {


                     $start = validateURI($this->segment) != '' ? validateURI($this->segment) : '0';
                     $condition = array('q.user_id' => $this->UserDetail['id']);
                     $result = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition);

                      $condition1 = array('q.user_id' => $this->UserDetail['id'],'q.status' => '1');
                     $result1 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition1);

                      $condition2 = array('q.user_id' => $this->UserDetail['id'],'q.status' => '2');
                     $result2 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition2);

                     $listings = [];
                     $pagination = '';
                     if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                         $listings = $result['results'];
                         $pagination = createPaginationLoad('dashboard/end_user', $result['total_rows'], $this->perPage, $this->segment);
                     }
                     $this->viewData['total1'] = $result['total_rows'];
                      $this->viewData['total2'] = $result1['total_rows'];
                       $this->viewData['total3'] = $result2['total_rows'];
                     $this->viewData['pagination'] = $pagination;
                     $this->viewData['dbdata'] = $listings;
                      $this->viewData['dbdata1'] = $result['results1'];
                       $this->viewData['dbdata2'] = $listings;
                     $this->viewData['categories'] = $this->common_model->_select('b_categories', '*', ['status' => '1'], 'id', 'ASC');



                 }
                  $this->load->view('User/EndUSerDashboard', $this->viewData);
             }
      */


        if (isset($this->UserDetail['role_type']) && $this->UserDetail['role_type'] == '2') {
            if ($response_type == '' || $response_type == 'expert') {
                $condition = array('q.user_id' => $this->UserDetail['id']);
                $result = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition);

                $listings = [];
                $pagination = '';
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                    $pagination = createPaginationLoad('dashboard/expert', $result['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['categories'] = $this->common_model->_select('b_categories', '*', ['status' => '1'], 'id', 'ASC');
                $this->viewData['pagination'] = $pagination;
                $this->viewData['dbdata'] = $listings;
                $this->viewData['total1'] = $result['total_rows'];
            }

            if ($response_type == '' || $response_type == 'answered_expert') {
                $condition1 = array('q.user_id' => $this->UserDetail['id'], 'q.status' => '1');
                $result1 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition1);

                //print_r($result1);die;

                $listings1 = [];
                $pagination1 = '';
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                    $pagination1 = createPaginationLoad('dashboard/answered_expert', $result1['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination1'] = $pagination1;
                $this->viewData['dbdata1'] = $listings1;
                $this->viewData['total2'] = $result1['total_rows'];
            }


            if ($response_type == '' || $response_type == 'unanswered_expert') {
                $condition2 = array('q.user_id' => $this->UserDetail['id'], 'q.status ' => '2');
                $result2 = $this->user_model->getQueryEnduser('b_queries', $start, $this->perPage, '*', $condition2);
                $listings2 = [];
                $pagination2 = '';
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                    $pagination2 = createPaginationLoad('dashboard/unanswered_expert', $result2['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination2'] = $pagination2;
                $this->viewData['dbdata2'] = $listings2;
                $this->viewData['total3'] = $result2['total_rows'];
            }
            
            $this->load->view('User/EndUSerDashboard', $this->viewData);
        }


        // for md user by monu kumar from 23-04-2020

        if (isset($this->UserDetail['role_type']) && $this->UserDetail['role_type'] == '3') {
            if ($response_type == '' || $response_type == 'expert') {
                if ($k) {
                    $condition = array('qa.user_id' => $this->UserDetail['id'], 'q.category_id =' => $k);
                } else {
                    $condition = array('qa.user_id' => $this->UserDetail['id']);
                }
                $result = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition, $orderby);
                $listings = [];
                $pagination = '';
                if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                    $listings = $result['results'];
                    $pagination = createPaginationLoad('dashboard/expert', $result['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['categories'] = $this->common_model->_select('b_categories', '*', ['status' => '1'], 'id', 'ASC');
                $this->viewData['pagination'] = $pagination;
                $this->viewData['dbdata'] = $listings;
                $this->viewData['total1'] = $result['total_rows'];
            }

            if ($response_type == '' || $response_type == 'answered_expert') {
                if ($k) {
                    $condition1 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status!=' => '0', 'q.category_id =' => $k);
                } else {
                    $condition1 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status!=' => '0');
                }
                $result1 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition1, $orderby);
                $listings1 = [];
                $pagination1 = '';
                if (isset($result1['total_rows']) && $result1['total_rows'] > 0) {
                    $listings1 = $result1['results'];
                    $pagination1 = createPaginationLoad('dashboard/answered_expert', $result1['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination1'] = $pagination1;
                $this->viewData['dbdata1'] = $listings1;
                $this->viewData['total2'] = $result1['total_rows'];
            }


            if ($response_type == '' || $response_type == 'unanswered_expert') {
                if ($k) {
                    $condition2 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status' => '0', 'q.category_id =' => $k);
                } else {
                    $condition2 = array('qa.user_id' => $this->UserDetail['id'], 'qa.status' => '0');
                }
                $result2 = $this->user_model->getAssignedQuery('b_query_assign', $start, $this->perPage, '*', $condition2, $orderby);
                $listings2 = [];
                $pagination2 = '';
                if (isset($result2['total_rows']) && $result2['total_rows'] > 0) {
                    $listings2 = $result2['results'];
                    $pagination2 = createPaginationLoad('dashboard/unanswered_expert', $result2['total_rows'], $this->perPage, $this->segment);
                }
                $this->viewData['pagination2'] = $pagination2;
                $this->viewData['dbdata2'] = $listings2;
                $this->viewData['total3'] = $result2['total_rows'];
            }
            $this->viewData['orderby'] = $orderby;
            $this->viewData['category123'] = $k;

            $this->load->view('User/MdDashboard', $this->viewData);
        }

        $this->viewData['title'] = ADMIN_COMPANY . " | Dashboard";
        }//try
    catch(Exception $e) 
        {  
                log_message('error', "\n Exception Caught", $e->getMessage());
        }
    }

    public function profile()
    {
        isLoggedIn($type = 'user');
        if ($this->input->post()) {
            $this->set_rules('EditProfile');
            if ($this->form_validation->run() === TRUE) {
                $dataArray['contact_no'] = $this->input->post('phone');
                $dataArray['company_name'] = $this->input->post('company_name');
                $dataArray['occupation'] = $this->input->post('occupation');
                $dataArray['education_qualification'] = $this->input->post('education_qualification');
                $dataArray['expertise_field'] = $this->input->post('expertise_field');
                if (isset($this->upload_data) && $this->upload_data['file_name']) {
                    if (!empty($this->UserDetail['image'])) {
                        if (file_exists('./assets/uploads/users/' . $this->UserDetail['image'])) {
                            unlink('./assets/uploads/users/' . $this->UserDetail['image']);
                        }
                    }
                    $dataArray['image'] = $this->upload_data['file_name'];
                }
                if ($this->common_model->_update('b_users', $dataArray, array('id' => $this->UserDetail['id']))) {
                    setSessionFlashData(array('success' => 'Congrats! You successfully updated your profile information.'));
                    redirect(base_url('profile'));
                } else {
                    setSessionFlashData(array('error' => 'We are facing some technical issue, Please try later.'));
                    redirect(base_url('profile'));
                }
            } else {
                setSessionFlashData('error', filter_validation_errors());
                redirect(base_url() . "profile");
            }
        }
        $profile = $this->user_model->profile($this->UserDetail['id']);
        $this->viewData['result'] = $profile;
        $this->viewData['title'] = ADMIN_COMPANY . " | Profile";
        $this->load->view('User/Profile', $this->viewData);
    }

    public function change_password()
    {
        isLoggedIn($type = 'user');
        if ($this->input->post()) {
            $this->set_rules('ChangePass');
            if ($this->form_validation->run() === TRUE) {
                $OldPassword = $this->input->post('OldPassword');
                $NewPassword = $this->input->post('NewPassword');
                $ConPassword = $this->input->post('ConPassword');
                if ($NewPassword != $ConPassword) {
                    setSessionFlashData('error', 'Please Match New Password and Confirm Password !');
                    redirect(base_url('change-password'));
                }
                $password = $this->UserDetail['password'];
                if ($this->user_model->check_password($OldPassword, $password)) {
                    $NewPassword = $this->user_model->hash($NewPassword);

                    if ($this->common_model->_update('b_users', array('password' => $NewPassword), array('id' => $this->UserDetail['id']))) {
                        setSessionFlashData('success', 'Yeah! You have successfully changed your password.');
                        redirect(base_url('change-password'));
                    } else {
                        setSessionFlashData('error', 'Whoops! Something went wrong. Please try again.');
                        redirect(base_url('change-password'));
                    }
                } else {
                    setSessionFlashData('error', 'Whoops! Old Password does not match.');
                    redirect(base_url('change-password'));
                }
            } else {
                setSessionFlashData('error', filter_validation_errors());
                redirect(base_url() . "change-password");
            }
        }
        $profile = $this->user_model->profile($this->UserDetail['id']);
        $this->viewData['result'] = $profile;
        $this->viewData['title'] = ADMIN_COMPANY . " | Change Password";
        $this->load->view('User/Change_password', $this->viewData);
    }

    public function manage_end_users()
    {

        isLoggedIn($type = 'user');
        customPagination();
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        $isAll = getStringSegment(3) ? getStringSegment(3) : false;
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $userId = $FormData['csv_ids_hidden'];
            $resultData = explode(',', $userId);
            $status_id = $FormData['purpose_hidden'];
            if ($status_id == '1' || $status_id == '2' || $status_id == '3' || $status_id == '0') {
                if ($status_id == "1") {
                    $status = 'Activate';
                } else if ($status_id == "2") {
                    $status = 'Blocked';
                } else if ($status_id == "3") {
                    $status = 'Deleted';
                } else if ($status_id == "0") {
                    $status = 'Pending';
                }
                if ($this->common_model->updateWhereIn('b_users', array('status' => $status_id), 'id', $resultData)) {
                    $message = 'End User ' . $status . ' successfully.';
                }
            } elseif ($status_id == '4') {
                foreach ($resultData as $user_id) {
                    $user = $this->common_model->_selectById("b_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(15, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
                $message = 'User Bulk Email sent successfully';
            }
            setSessionFlashData('success', $message);
        }

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('ExpertUserManager');
        }
        $prevSessData = getSessionUserData('ExpertUserManager');
        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'u.id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['field'] : 'desc';
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $conditionArray['equal']['u.status!='] = '3';
        $conditionArray['equal']['u.role_type'] = '2';

        $userDataCount = $this->user_model->expert_users_record_count($conditionArray);
        $userData = $this->user_model->get_expert_users($start, $this->perPage, $conditionArray);
        $userPagination = createPagination('user/manage_end_users/', $userDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Users';
        $this->viewData['records'] = $userData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['getData'] = $getData;
        $this->viewData['field'] = $sortField;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/EndUser/List', $this->viewData);
    }

    public function add_enduser()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        if ($this->input->post()) {
            $this->set_rules('UserAdd');
            if ($this->form_validation->run($this) !== FALSE) {
                $dataArray['role_type'] = '2';
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $dataArray['name'] = $first_name . ' ' . $last_name;
                $dataArray['email'] = $this->input->post('email');
                $dataArray['contact_no'] = $this->input->post('phone');
                $dataArray['company_name'] = $this->input->post('company_name');
                $dataArray['occupation'] = $this->input->post('occupation');
                $dataArray['education_qualification'] = $this->input->post('education_qualification');
                $dataArray['expertise_field'] = $this->input->post('expertise_field');
                $password = randomGenerateString();
                $dataArray['password'] = $this->user_model->hash($password);
                if (isset($this->upload_data) && $this->upload_data['file_name']) {
                    $dataArray['image'] = $this->upload_data['file_name'];
                }
                $dataArray['status'] = '1';
                $userID = $this->common_model->_insertReturnId('b_users', $dataArray);
                if ($userID > 0) {
                    $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                    SendEmailByTemplate(14, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    setSessionFlashData('success', 'Congrats! You have successfully added new user');
                    redirect(base_url('user/manage_end_users'));
                } else {
                    setSessionFlashData(array('error' => 'We are facing some technical issue, Please try later.'));
                    redirect(base_url('user/manage_end_users'));
                }
            }
        }
        $this->viewData['title'] = ADMIN_COMPANY . ' | End User Add';
        $this->load->view('User/EndUser/Add', $this->viewData);
    }

    public function edit_enduser()
    {
        isLoggedIn($type = 'user');
        $userId = validateURI(3) != '' ? validateURI(3) : 0;
        if ($userId != '') {
            if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
                redirect(base_url('profile'));
            }
            $profile = $this->user_model->profile($userId);
            $dbdata = $profile;
            if (!empty($dbdata)) {
                if ($this->input->post()) {
                    $this->set_rules('UserEdit');
                    if ($this->form_validation->run($this) !== FALSE) {
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $dataArray['name'] = $first_name . ' ' . $last_name;
                        $dataArray['email'] = $email = $this->input->post('email');
                        $dataArray['contact_no'] = $this->input->post('phone');
                        $dataArray['company_name'] = $this->input->post('company_name');
                        $dataArray['occupation'] = $this->input->post('occupation');
                        $dataArray['education_qualification'] = $this->input->post('education_qualification');
                        $dataArray['expertise_field'] = $this->input->post('expertise_field');
                        if (isset($this->upload_data) && $this->upload_data['file_name']) {
                            $dataArray['image'] = $this->upload_data['file_name'];
                        }
                        $keywords = '';
                        if ($email != $dbdata['email']) {
                            $password = randomGenerateString();
                            $dataArray['password'] = $this->user_model->hash($password);
                            $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                        }
                        if ($this->common_model->_update('b_users', $dataArray, array('id' => $userId))) {
                            if ($keywords != '') {
                                SendEmailByTemplate(14, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                            }
                            setSessionFlashData(array('success' => 'Congrats! You successfully updated profile information.'));
                            redirect(base_url('user/manage_end_users'));
                        } else {
                            setSessionFlashData(array('error' => 'We are facing some technical issue, Please try later.'));
                            redirect(base_url('user/manage_end_users'));
                        }
                    }

                }
            }
        } else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('profile'));
        }
        $this->viewData['detail'] = $dbdata;
        $this->viewData['title'] = ADMIN_COMPANY . ' | End User Edit';
        $this->load->view('User/EndUser/Add', $this->viewData);
    }

    public function view_end_users()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        $userId = validateURI(3) != '' ? validateURI(3) : 0;
        if ($userId != '') {
            $dbdata = $this->user_model->profile($userId);
            if ($dbdata == false) {
                setSessionFlashData('error', 'No End User found.');
                redirect(base_url('profile'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = ADMIN_COMPANY . ' | Manage End USer';
                $this->load->view('User/EndUser/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

    /*---Expert Module---*/
    public function manage_users()
    {
        // echo "<pre>";
        // print_r($_POST);
        // print_r($this->UserDetail); die;
        isLoggedIn($type = 'user');
        customPagination();
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        $isAll = getStringSegment(3) ? getStringSegment(3) : false;
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $userId = $FormData['csv_ids_hidden'];

            $resultData = explode(',', $userId);

            $status_id = $FormData['purpose_hidden'];
            //print_r($status_id);die;
            if ($status_id == '1' || $status_id == '2' || $status_id == '3') {
                if ($status_id == "1") {
                    $status = 'Activate';
                } else if ($status_id == "2") {
                    $status = 'Blocked';
                } else if ($status_id == "3") {
                    $status = 'Deleted';
                }

                if ($this->common_model->updateWhereIn('b_users', array('status' => $status_id), 'id', $resultData)) {
                    $message = 'Expert ' . $status . ' successfully.';
                }
            } elseif ($status_id == '4') {
                foreach ($resultData as $user_id) {
                    $user = $this->common_model->_selectById("b_users", 'name,email', array("id" => $user_id));
                    $keywords = array('NAME' => $user['name']);
                    SendEmailByTemplate(15, $keywords, $user['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                }
                $message = 'User Bulk Email sent successfully';
            }
            setSessionFlashData('success', $message);
        }

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('ExpertUserManager');
        }
        $prevSessData = getSessionUserData('ExpertUserManager');
        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'u.id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['field'] : 'desc';
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $conditionArray['equal']['u.status!='] = '3';
        $conditionArray['equal']['u.role_type'] = '1';

        $userDataCount = $this->user_model->expert_users_record_count($conditionArray);
        $userData = $this->user_model->get_expert_users($start, $this->perPage, $conditionArray);
        $userPagination = createPagination('user/manage_users/', $userDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Users';
        $this->viewData['records'] = $userData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['getData'] = $getData;
        $this->viewData['field'] = $sortField;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/Expert/List', $this->viewData);
    }

    public function add_user()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        if ($this->input->post()) {
            $this->set_rules('UserAdd');
            if ($this->form_validation->run($this) !== FALSE) {
                $dataArray['role_type'] = '1';
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $dataArray['name'] = $first_name . ' ' . $last_name;
                $dataArray['email'] = $this->input->post('email');
                $dataArray['description'] = $this->input->post('description');
                $dataArray['linkdin'] = $this->input->post('linkdin');
                $dataArray['facebook'] = $this->input->post('facebook');
                $dataArray['company_name'] = $this->input->post('company_name');
                $dataArray['occupation'] = $this->input->post('occupation');
                $dataArray['education_qualification'] = $this->input->post('education_qualification');
                $dataArray['expertise_field'] = $this->input->post('expertise_field');
                //$password = randomGenerateString();
                $password = $this->input->post('password');
                $password = $password != '' ? $password : randomGenerateString();

                $dataArray['password'] = $this->user_model->hash($password);
                if (isset($this->upload_data) && $this->upload_data['file_name']) {
                    $dataArray['image'] = $this->upload_data['file_name'];
                }
                $dataArray['status'] = '1';
                $userID = $this->common_model->_insertReturnId('b_users', $dataArray);
                if ($userID > 0) {
                    $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                    SendEmailByTemplate(6, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    setSessionFlashData('success', 'Congrats! You have successfully added new user');
                    redirect(base_url('user/manage_users'));
                } else {
                    setSessionFlashData(array('error' => 'We are facing some technical issue, Please try later.'));
                    redirect(base_url('user/manage_users'));
                }
            }
        }
        $this->viewData['title'] = ADMIN_COMPANY . ' | Expert Add';
        $this->load->view('User/Expert/Add', $this->viewData);
    }

    public function edit_user()
    {
        isLoggedIn($type = 'user');
        $userId = validateURI(3) != '' ? validateURI(3) : 0;
        if ($userId != '') {
            if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
                redirect(base_url('profile'));
            }
            $profile = $this->user_model->profile($userId);
            $dbdata = $profile;
            if (!empty($dbdata)) {
                if ($this->input->post()) {
                    $this->set_rules('UserEdit');
                    if ($this->form_validation->run($this) !== FALSE) {
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $dataArray['name'] = $first_name . ' ' . $last_name;
                        $dataArray['email'] = $email = $this->input->post('email');
                        $dataArray['description'] = $this->input->post('description');
                        $dataArray['linkdin'] = $this->input->post('linkdin');
                        $dataArray['facebook'] = $this->input->post('facebook');
                        $dataArray['company_name'] = $this->input->post('company_name');
                        $dataArray['occupation'] = $this->input->post('occupation');
                        $dataArray['education_qualification'] = $this->input->post('education_qualification');
                        $dataArray['expertise_field'] = $this->input->post('expertise_field');
                        if (isset($this->upload_data) && $this->upload_data['file_name']) {
                            $dataArray['image'] = $this->upload_data['file_name'];
                        }
                        $password = $this->input->post('password');

                        $keywords = '';

                        if ($password != '') {
                            $dataArray['password'] = $this->user_model->hash($password);
                            $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                        }

                        if ($email != $dbdata['email']) {
                            $password = isset($dataArray['password']) ? $dataArray['password'] : randomGenerateString();
                            $dataArray['password'] = $this->user_model->hash($password);
                            $keywords = array('NAME' => $first_name . ' ' . $last_name, 'EMAIL' => $dataArray['email'], 'PASSWORD' => $password, 'LOGIN_LINK' => base_url());
                        }

                        if ($this->common_model->_update('b_users', $dataArray, array('id' => $userId))) {
                            if ($keywords != '') {
                                SendEmailByTemplate(6, $keywords, $dataArray['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                            }
                            setSessionFlashData(array('success' => 'Congrats! You successfully updated profile information.'));
                            redirect(base_url('user/manage_users'));
                        } else {
                            setSessionFlashData(array('error' => 'We are facing some technical issue, Please try later.'));
                            redirect(base_url('user/manage_users'));
                        }
                    }

                }
            }
        } else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('profile'));
        }
        $this->viewData['detail'] = $dbdata;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Expert Edit';
        $this->load->view('User/Expert/Add', $this->viewData);
    }

    public function view()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        $userId = validateURI(3) != '' ? validateURI(3) : 0;
        if ($userId != '') {
            $dbdata = $this->user_model->profile($userId);
            if ($dbdata == false) {
                setSessionFlashData('error', 'No Expert User found.');
                redirect(base_url('profile'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Expert';
                $this->load->view('User/Expert/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

    /*---Expert End-------*/

    public function bulk_email_notification()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $isValidated = true;
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('attachment_file', 'Attachment File', 'callback_bulk_handle_upload');
            if ($this->form_validation->run($this) == true) {

                $sent_to = _inputPost('sent_to');

                if (in_array('industrial_category', $sent_to)) {
                    $category_id = _inputPost('category_id');
                    if (empty($category_id)) {
                        $isValidated = false;
                        setSessionFlashData('error', 'Industrial Category Field is required');
                    }
                }
                if (in_array('emailid', $sent_to)) {
                    $email_id = _inputPost('email');
                    if (empty($email_id)) {
                        $isValidated = false;
                        setSessionFlashData('error', 'Email Field is required');
                    }
                }
                if ($isValidated) {
                    $message = closetags(trim(_inputPost('message')));
                    $full_path = '""';
                    if (isset($this->upload_data) && $this->upload_data['file_name']) {
                        $full_path = $this->upload_data['full_path'];
                    }
                    $subject = trim(_inputPost('subject'));

                    $expertList = $endUserlist = $industryUserslist = $emailList = $Md = $Admin = [];

                    if (in_array('experts', $sent_to)) {
                        $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
                        if (!empty($expertList)) {
                            $expertList = array_column($expertList, 'email');
                        } else {
                            $expertList = array();
                        }
                    }
                    if (in_array('users', $sent_to)) {
                        $endUserlist = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '2'));
                        if (!empty($endUserlist)) {
                            $endUserlist = array_column($endUserlist, 'email');
                        } else {
                            $endUserlist = array();
                        }
                    }
                    if (in_array('industrial_category', $sent_to)) {
                        $this->db->select('email');
                        $this->db->from('b_industrial_users');
                        $this->db->where(array('status' => '1'));
                        $this->db->where_in('category_id', $category_id);
                        $resSQL = $this->db->get();
                        if ($resSQL->num_rows() > 0) {
                            $industryUserslist = $resSQL->result_array();
                        }
                        if (!empty($industryUserslist)) {
                            $industryUserslist = array_column($industryUserslist, 'email');
                        } else {
                            $industryUserslist = array();
                        }
                    }

                    /*  ------------------------------------------------------------*/

                    if (in_array('Md', $sent_to)) {
                        $Md = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '3'));
                        if (!empty($Md)) {
                            $Md = array_column($Md, 'email');
                        } else {
                            $Md = array();
                        }
                    }


                    if (in_array('Admin', $sent_to)) {
                        $Admin = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '0'));
                        if (!empty($Admin)) {
                            $Admin = array_column($Admin, 'email');
                        } else {
                            $Admin = array();
                        }
                    }

                    /* ------------------------------------------------------------*/


                    if (in_array('emailid', $sent_to)) {
                        $emailList = explode(',', trim(_inputPost('email')));
                    }

                    $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $Md, $Admin, $emailList));
                   //  echo"<pre>";
                   // print_r($list);die;

                    // For Bulk email Log Start

                    $emailLog = array();

                    $sent_to_1 = implode(',', $sent_to);
                    $emailid = implode(',', $emailList);

                    //foreach($list as $row) {

                    $emailLog[] = array(

                        "sender_user_id" => $this->UserDetail['id'],
                        "email" => $row,
                        "sender_user_type" => $sent_to_1,
                        "email" => $emailid,
                        "subject" => $subject,
                        "message" => $message,
                        "attachements" => $full_path,
                        "date" => date("Y-m-d")
                    );
                    //}
                    //     print_r($emailid);
                    // print_r($emailLog);die;  

                    $this->db->insert_batch("b_bulk_email_log", $emailLog);

                    // For Bulk email Log end


                   /* $sender_email_id = $this->UserDetail['id'];*/
                    if (!empty($list)) {

                        $url = base_url('sendemail');
                        $sent_to = implode(',', $sent_to);

                        $emails = trim(_inputPost('email'));
                        $emailIDs = $emails != '' ? $emails : '""';
                        $category_id = !empty($category_id) ? implode(',', $category_id) : '0';
                        $message = escapeshellarg(($message));
                        $subject = escapeshellarg(($subject));

                        $cmd = FCPATH . 'send_request.php';
                        //print_r($cmd);die;
                        //$command = "start /B php $cmd $sent_to $category_id $subject $url $message $emailIDs $full_path  > NUL";
                        // print_r($sent_to );die;
                        $command = "/usr/bin/php $cmd $sent_to $category_id $subject $url $message $emailIDs $full_path > /dev/null 2>&1 &";

                        exec($command);
                        $smsg = 'Bulk Email has been sent successfully.';
                        setSessionFlashData('success', $smsg);
                        redirect('bulk-email-notification');
                    } else {
                        setSessionFlashData('error', 'No active users found');
                    }
                }
            }
        }
        $this->viewData['title'] = ADMIN_COMPANY . ' | Bulk Email Notification';
        $this->load->view('User/BulkEmail/Send', $this->viewData);
    }

    /*-- Bulk email Background mail send */
    public function sendemail()
    { //echo "hi";die;
        if (!empty($_REQUEST)) {

            $sent_to = explode(',', $_REQUEST['1']);
            $categoryId = $_REQUEST['2'] != '0' ? explode(',', $_REQUEST['2']) : '0';
            $emailIDs = $_REQUEST['6'] != '' ? explode(',', $_REQUEST['6']) : '';
            $attachment_file = isset($_REQUEST['7']) && $_REQUEST['7'] != '' ? $_REQUEST['7'] : '';
            $subject = $_REQUEST['3'];
            $senderid = isset($_REQUEST['8']) && $_REQUEST['8'] != '' ? $_REQUEST['8'] : '';
            $message = $_REQUEST['5'];
            $expertList = $endUserlist = $industryUserslist = $emailList = [];
            $fp = fopen('sendemailRequest.txt', 'w+');
            fwrite($fp, print_r($_REQUEST, 1));
            fclose($fp);
            if (in_array('experts', $sent_to)) {
                $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
                if (!empty($expertList)) {
                    $expertList = array_column($expertList, 'email');
                } else {
                    $expertList = array();
                }
            }
            if (in_array('users', $sent_to)) {
                $endUserlist = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '2'));
                if (!empty($endUserlist)) {
                    $endUserlist = array_column($endUserlist, 'email');
                } else {
                    $endUserlist = array();
                }
            }
            if (in_array('industrial_category', $sent_to)) {
                $this->db->select('email');
                $this->db->from('b_industrial_users');
                $this->db->where(array('status' => '1'));
                $this->db->where_in('category_id', $categoryId);
                $resSQL = $this->db->get();
                if ($resSQL->num_rows() > 0) {
                    $industryUserslist = $resSQL->result_array();
                }
                if (!empty($industryUserslist)) {
                    $industryUserslist = array_column($industryUserslist, 'email');
                } else {
                    $industryUserslist = array();
                }
            }
            if (in_array('emailid', $sent_to)) {
                $emailList = $emailIDs != '' ? $emailIDs : [];
            }
            $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $emailList));
            
            $fp = fopen('list.txt', 'w+');
            fwrite($fp, print_r($list, 1));
            fclose($fp);
            if (!empty($list)) {
                foreach ($list as $email) {
                    $userEmail = $email;
                    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                        // $mailContent = '<table><tr><td align="left" valign="top" style="padding:20px;"><h3>Hello,' . $email . '</h3>' . $message . '<br/><br/>Regards,<br/<br/>' . ADMIN_NOTIFICATION_TITLE . '</td></tr></table>';

                         // $fp = fopen('perameterRequest.txt', 'a+');
                         // fwrite($fp, print_r($userEmail, 1));

                        $mailContent = $message;
                        sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);

                      /*  $emailLog = array(

                            "sender_user_id" => $senderid,
                            "email" => $userEmail,
                            "sender_user_type" => $_REQUEST['1'],
                            "subject" => $subject,
                            "message" => $mailContent,
                            "attachements" => $attachment_file,
                            "date" => date("Y-m-d")
                        );*/

                        // $this->db->insert("b_bulk_email_log", $emailLog);
                    }
                }
            }
        }
    }

    function bulk_handle_upload()
    {
        if (isset($_FILES['attachment_file']) && !empty($_FILES['attachment_file']['name'])) {
            if (!file_exists("assets/uploads/attachment_file")) {
                mkdir("assets/uploads/attachment_file", 0777, true);
            }
            $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
            $rand_val = date('YMDHIS') . rand(11111, 99999);
            $filename = md5($rand_val) . "." . $imgInfo;
            $_FILES['attachment_file']['name'] = $filename;
            $config2['upload_path'] = "assets/uploads/attachment_file";
            $config2['allowed_types'] = "*";
            $config2['max_size'] = "2048";
            $config2['remove_spaces'] = TRUE;
            $this->load->library('upload', $config2);
            $this->upload->set_upload_path($config2['upload_path']);
            $this->upload->initialize($config2);
            if ($this->upload->do_upload('attachment_file')) {
                // set a $_POST value for 'image' that we can use later
                $this->upload_data = $this->upload->data();
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        }
    }

    function logout()
    {
        $this->auth->logout('user');
        $this->session->unset_userdata('auth_user_data');
        $this->session->unset_userdata('RedirectUrl');
        //setSessionFlashData(array('success' => 'You have successfully logged out.'));
        redirect(base_url(''));
    }

    function set_rules($option)
    {
        $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
        if ($option == 'EditProfile') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('userFile', 'User Image', 'callback_handle_upload');
        }
        if ($option == 'ChangePass') {
            $this->form_validation->set_rules('OldPassword', 'Old Password', 'required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('NewPassword', 'New Password', 'required|min_length[6]|max_length[30]');
        }
        if ($option == 'UserAdd') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
            $this->form_validation->set_rules('userFile', 'User Image', 'callback_handle_upload');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

        }
        if ($option == 'UserEdit') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[75]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check');
            $this->form_validation->set_rules('userFile', 'User Image', 'callback_handle_upload');
        }

    }

    public function useremail_check($str)
    {
        $id = $this->uri->segment(3);
        $condition = array('status!=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition = array('id !=' => $id, 'status!=' => '3');
        }
        if ($this->common_model->_CheckExistence('email', $str, 'b_users', $condition)) {
            return true;
        } else {
            $this->form_validation->set_message('useremail_check', 'Whoops! User Email already exists. Please try with another User Email.');
            return false;
        }
    }

    function handle_upload()
    {
        if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
            $imgInfo = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
            $rand_val = date('YMDHIS') . rand(11111, 99999);
            $filename = md5($rand_val) . "." . $imgInfo;
            $_FILES['userFile']['name'] = $filename;
            $config['upload_path'] = "assets/uploads/users";
            $config['allowed_types'] = "gif|jpg|jpeg|png";
            $config['max_size'] = "2048";
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('userFile')) {
                // set a $_POST value for 'image' that we can use later
                $this->upload_data = $this->upload->data();
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        }
    }

    /* public function saved_search()
     {
         $segment = '2';
         $start = validateURI($segment) != '' ? validateURI($segment) : '0';
         $condition = array('user_id' => $this->loggedInUser['id']);
         $result = $this->user_model->getAllSavedActions($start, $this->perPage, $condition);
         $listings = [];
         $pagination = '';
         if (isset($result['total_rows']) && $result['total_rows'] > 0) {
             $listings = $result['results'];
             $pagination = createPagination('saved-search/', $result['total_rows'], $this->perPage, $segment);
         }
         $this->viewData['pagination'] = $pagination;
         $this->viewData['dbdata'] = $listings;
         $this->viewData['title'] = ADMIN_COMPANY . " | Lead Actions";
         $profile = $this->user_model->profile($this->loggedInUser['id']);
         $this->viewData['result'] = $profile;
         //printArray($this->viewData,1);
         $this->load->view('User/ActionList', $this->viewData);
     }

     public function remove_action($id)
     {
         $user_id = $this->loggedInUser['id'];
         $failure = false;
         if ($this->common_model->_delete('b_users_save_actions', ['id' => $id, 'user_id' => $user_id])) {
             $success_message = 'Action delete successfully.';
         } else {
             $failure = true;
             $error_message = 'some error occurred.';
         }

         if ($this->input->is_ajax_request()) {
             if ($failure) {
                 $data['success'] = false;
                 $data['message'] = $error_message;
             } else {
                 $data['success'] = true;
                 $data['message'] = $success_message;
             }
             //$data['slideToThisForm'] = true;
             echo json_encode($data);
             die;
         } else {
             exit('No direct script access allowed');
         }
     }*/


    function reports()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }

        /*-----------------------------------------------------------------------*/

        $condition = array('q.status!=' => '1');
        $result = $this->user_model->getNewQuery('b_queries', $start, $this->perPage, '*', $condition);
        $this->viewData['new'] = $result['total_rows'];
        /*-----------------------------------------------------------------------*/

        $condition = array('q.status!=' => '1');
        $result1 = $this->user_model->getRespondQuery('b_query_assign', $start, $this->perPage, '*', $condition);
        $this->viewData['pending'] = $result1['total_rows'];
        /*-----------------------------------------------------------------------*/

        $condition2 = array('qa.status' => '1', 'review_status' => '1');
        $result2 = $this->user_model->getRespondedQuery('b_query_assign', $start, $this->perPage, '*', $condition2);
        $this->viewData['closed'] = $result2['total_rows'];
        /*-----------------------------------------------------------------------*/
        $this->viewData['total'] = $this->db->count_all('b_queries');;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Md Reports';
        $this->load->view('User/Md/reports', $this->viewData);
        /*-----------------------------------------------------------------------*/
    }


    function mdTotalQueriesReports()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdManager');
        $conditionArray = $prevSessData;
        if ($isAll != 'all') {

            $start = validateURI(3) != '' ? validateURI(3) : '0';

            $getData['page'] = $start;
            //echo  $getData['page']; die;
        } else {

            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdTotalQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdTotalQueriesReports($start, $this->perPage, $conditionArray);
        //print_r($userData);die;
        $userPagination = createPagination('user/mdTotalQueriesReports/', $userDataCount, $this->perPage, $this->segment, $getField);

        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Reports';
        $this->viewData['records'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/Md/mdtotalqueries', $this->viewData);
    }


    function excel($startDate, $endDate)
    {
        $condition = array(
            "q-from" => trim($startDate),
            "q-to" => trim($endDate)
        );
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array("", "Query", "Status", "Category Name", "Assign To", "Answer", "Answer Date", "Admin Review Date", "Added On");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $queries = $this->user_model->mdTotalQueriesExcel($condition);
        $excel_row = 2;
        $row1 = 1;
        foreach ($queries as $row) {
            if ($row["status"] == 0) {
                $status = "Pending";
            } else if ($row["status"] == 1) {
                $status = "Completed";
            } else {
                $status = "Rejected";
            }

            if (empty($row["expert_id"]) && $row["expert_id"] == null && $row["expert_id"] == '') {
                $expertdetails = "";
            } else {
                $exp = _getExpertDetails($row["expert_id"]);
                if ($exp["role_type"] == 1) {
                    $designation = "Expert";
                } else if ($exp["role_type"] == 3) {
                    $designation = "MD";
                } else {
                    $designation = "";
                }

                if (isset($designation) && isset($exp["name"])) {
                    $expertdetails = $designation . ": " . $exp["name"];
                } else {
                    $expertdetails = $exp["name"];
                }

            }

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["query"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["category_name"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $expertdetails);

            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row["answer"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date("d-m-Y", strtotime($row["answer_date"])));

            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, date("d-m-Y", strtotime($row["admin_review_date"])));

            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, date("d-m-Y", strtotime($row["added_on"])));
            $excel_row++;
            $row1++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Total Query.xls"');
        $object_writer->save('php://output');
    }

    /*-----------------------------------------------------------------------------------*/

    function mdNewQueriesReports()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdNewManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdNewManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdNewQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdNewQueriesReports($start, $this->perPage, $conditionArray);
        //print_r($userData);die;
        $userPagination = createPagination('user/mdNewQueriesReports/', $userDataCount, $this->perPage, $this->segment, $getField);

        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Reports';
        $this->viewData['records'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/Md/mdNewqueries', $this->viewData);
    }

    function mdNewQueriesReportsmodel()
    {
        $this->db->select('*');
        $this->db->from('b_queries');
        $this->db->where('id', $this->uri->segment(4));
        $query = $this->db->get();
        $dataa = $query->row();
        // print_r($dataa);die;
        ?>

        <!DOCTYPE html>
        <html>
        <style>
            table {
                border-collapse: collapse;
            }

            th {
                background-color: green;
                Color: white;
            }

            th, td {

                border: 1px solid white;
                padding: 5px

            }

        </style>
        <body>
        <table width="100%" cellspacing="5">
            <tr>
                <th colspan="3" style="text-align: center; background-color: #0d9e40"> Queries</th>
            </tr>
            <tr>
                <td> &nbsp;</td>
                <td> &nbsp;</td>
            </tr>
            <tr>
                <td style="width: 20%;">Category</td>
                <td style="width: 2%;">:</td>
                <td><?php
                    $this->db->select('cat_name');
                    $this->db->from('b_categories');
                    $this->db->where('id', $dataa->category_id);
                    $query = $this->db->get();
                    $dataaa = $query->row();
                    print_r($dataaa->cat_name);
                    ?></td>
            </tr>
            <tr>
                <td>Query</td>
                <td>:</td>
                <td style="vertical-align: bottom;"><?php print_r($dataa->query); ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><?php
                    if ($dataa->status == 0) {
                        echo " <span style='color: #a89e32;'>Pending </span>";
                    } else if ($dataa->status == 1) {
                        echo "<span style='color: green;'>Completed </span> ";
                    } else {
                        echo " <span style='color: red;'>Rejected </span>";
                    }
                    ?></td>
            </tr>
        </table>

        </body>
        </html>


        <?php
        echo "<hrml>";

        die;

    }


    function newExcel($startDate, $endDate)
    {
        $condition = array(
            "q-from" => trim($startDate),
            "q-to" => trim($endDate)
        );

        $this->load->library("excel");
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $table_columns = array("", "Query", "Status", "Category Name", "Added On");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $queries = $this->user_model->mdNewQueriesExcel($condition);

        $excel_row = 2;
        $row1 = 1;
        foreach ($queries as $row) {
            if ($row["status"] == 0) {
                $status = "Pending";
            } else if ($row["status"] == 1) {
                $status = "Completed";
            } else {
                $status = "Rejected";
            }

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["query"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["category_name"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, date("d-m-Y", strtotime($row["added_on"])));
            $excel_row++;
            $row1++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="New Query.xls"');
        $object_writer->save('php://output');
    }


    /*-----------------------------------------------------------------------------------*/

    function mdClosedQueriesReports()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }
        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdClosedManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdClosedManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdClosedQueriesReports_count($conditionArray);

        $userData = $this->user_model->get_mdClosedQueriesReports($start, $this->perPage, $conditionArray);

        $userPagination = createPagination('user/mdClosedQueriesReports/', $userDataCount, $this->perPage, $this->segment, $getField);

        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Reports';
        $this->viewData['records'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/Md/mdclosedqueries', $this->viewData);
    }


    function closedExcel($startDate, $endDate)
    {
        $condition = array(
            "q-from" => trim($startDate),
            "q-to" => trim($endDate)
        );

        $this->load->library("excel");
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $table_columns = array("", "Query", "Status", "Category Name", "Assign To", "Answer", "Answer Date", "Admin Review Date", "Added On");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $queries = $this->user_model->mdClosedQueriesExcel($condition);

        $excel_row = 2;
        $row1 = 1;
        foreach ($queries as $row) {
            if ($row["status"] == 0) {
                $status = "Pending";
            } else if ($row["status"] == 1) {
                $status = "Completed";
            } else {
                $status = "Rejected";
            }

            if (empty($row["answerData"]['user_id']) && $row["answerData"]['user_id'] == null && $row["answerData"]['user_id'] == '') {
                $expertdetails = "";
            } else {
                $exp = _getExpertDetails($row["answerData"]['user_id']);
                if ($exp["role_type"] == 1) {
                    $designation = "Expert";
                } else if ($exp["role_type"] == 3) {
                    $designation = "MD";
                } else {
                    $designation = "";
                }

                if (isset($designation) && isset($exp["name"])) {
                    $expertdetails = $designation . ": " . $exp["name"];
                } else {
                    $expertdetails = $exp["name"];
                }

            }

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["query"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["category_name"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $expertdetails);

            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row["answer"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date("d-m-Y", strtotime($row["respond_date"])));

            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, date("d-m-Y", strtotime($row["admin_review_date"])));

            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, date("d-m-Y", strtotime($row["added_on"])));
            $excel_row++;
            $row1++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Closed Query.xls"');
        $object_writer->save('php://output');
    }


    /*-----------------------------------------------------------------------------------*/

    function mdPendingQueriesReports()
    {
        isLoggedIn($type = 'user');
        if ($this->UserDetail['role_type'] == '2' || $this->UserDetail['role_type'] == '1') {
            redirect(base_url('profile'));
        }

        customPagination();  // for generating the pagination HTML

        $isAll = getStringSegment(3) ? getStringSegment(3) : false;  // getting the url parameters

        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();

        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('MdPendingManager');  // for maintaining the session
        }
        $prevSessData = getSessionUserData('MdPendingManager');

        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $userDataCount = $this->user_model->mdPendingQueriesReports_count($conditionArray);


        $userData = $this->user_model->get_mdPendingQueriesReports($start, $this->perPage, $conditionArray);
        //print_r($userData); die;

        $userPagination = createPagination('user/mdPendingQueriesReports/', $userDataCount, $this->perPage, $this->segment, $getField);

        $this->viewData['pagination'] = $userPagination;
        $this->viewData['order'] = $order_seg;
        $this->viewData['title'] = ADMIN_COMPANY . ' | Manage Reports';
        $this->viewData['records'] = $userData;
        $this->viewData['getData'] = $getData;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->load->view('User/Md/mdpendingqueries', $this->viewData);
    }


    function pendingExcel($startDate, $endDate)
    {
        $condition = array(
            "q-from" => trim($startDate),
            "q-to" => trim($endDate)
        );

        $this->load->library("excel");
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $table_columns = array("", "Query", "Status", "Category Name", "Assign To", "Answer", "Assign Date", "Asked Date");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $queries = $this->user_model->mdPendingQueriesExcel($condition);

        $excel_row = 2;
        $row1 = 1;
        foreach ($queries as $row) {

            if ($row["status"] == 0) {
                $status = "Pending";
            } else if ($row["status"] == 1) {
                $status = "Completed";
            } else {
                $status = "Rejected";
            }

            $tot = count($row["answerData"]);
            $index = $tot - 1;

            $answer = $row["answerData"][$index]["answer"];
            if (!isset($answer) && empty($answer)) {
                $answer = '';
            }

            $respond_date = $row["answerData"][$index]["added_on"];

            if (empty($row["answerData"][$index]['user_id']) && $row["answerData"][$index]['user_id'] == null && $row["answerData"][$index]['user_id'] == '') {
                $expertdetails = "";
            } else {
                $exp = _getExpertDetails($row["answerData"][$index]['user_id']);
                if ($exp["role_type"] == 1) {
                    $designation = "Expert";
                } else if ($exp["role_type"] == 3) {
                    $designation = "MD";
                } else {
                    $designation = "";
                }

                if (isset($designation) && isset($exp["name"])) {
                    $expertdetails = $designation . ": " . $exp["name"];
                } else {
                    $expertdetails = $exp["name"];
                }

            }

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["question"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["category_name"]);

            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $expertdetails);

            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $answer);


            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date("d-m-Y", strtotime($respond_date)));


            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, date("d-m-Y", strtotime($row["asked_date"])));
            $excel_row++;
            $row1++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Pending Query.xls"');
        $object_writer->save('php://output');
    }


    public function bulkemail()
    {

        customPagination();
        $isAll = getStringSegment(3) ? getStringSegment(3) : false;
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('EmailLogManager');
        }
        $prevSessData = getSessionUserData('EmailLogManager');
        $conditionArray = $prevSessData;
        if ($isAll != 'all') {
            $start = validateURI(3) != '' ? validateURI(3) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $page_num = (int)$this->uri->segment(3);
        if ($page_num == 0) $page_num = 1;
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";

        $contactDataCount = $this->emaillog_model->record_count('b_bulk_email_log', $conditionArray);
        $contactData = $this->emaillog_model->get_records('b_bulk_email_log', $start, $this->perPage, $conditionArray);

        $pagination = createPagination('user/bulkemail', $contactDataCount, $this->perPage, $this->segment, $getField);

        $this->viewData['pagination'] = $pagination;
        $this->viewData['dbdata'] = $contactData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['title'] = 'Email Logs';
        $this->load->view('User/BulkEmail/Bulk_email_log', $this->viewData);

    }

    public function bulkemailmessage()
    {
        $this->db->select('message')
            ->from('b_bulk_email_log')
            ->where('id', $this->uri->segment(3));
        $query = $this->db->get();
        $result = $query->result();
        print_r($result[0]->message);
        die;
        print_r($this->uri->segment(3));
        die;
        echo "hi saurabh";
        die;
    }
    public function bulkemailemailid(){
        $this->db->select('email')
            ->from('b_bulk_email_log')
            ->where('id', $this->uri->segment(3));
        $query = $this->db->get();
        $result = $query->result();
       // print_r($result[0]->email);
        $string = $result[0]->email; 
       $str_arr = explode (",", $string);
       //print_r(count($str_arr));die;
       ?>
       <table style="width: 100%; text-align: center;" border="1">
            <thead>
            <tr>
                <th style="text-align: center;">Sl No</th>
                <th style="text-align: center;">Email</th>
            </tr>
            </thead>
            <tbody>
                
            
       <?php
       for ($i=0; $i < count($str_arr) ; $i++) { ?>
        <tr>
              <td><?php echo $i+1; ?></td> 
              <td  style="text-align: left!important;">&nbsp;&nbsp;<?php echo $str_arr[$i]; ?></td>      
        </tr>
        <?php
         
       }
       ?>
       </tbody>
            
        </table>
       <?php
    
       //print_r(  $str_arr);die;
        die;
    }

}
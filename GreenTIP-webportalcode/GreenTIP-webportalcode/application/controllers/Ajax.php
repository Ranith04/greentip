<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    public $viewData = array();
    public $loggedInUser = array();
    public $UserDetail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->loggedInUser = getSessionUserData('id');
        if (isset($this->loggedInUser) && !empty($this->loggedInUser)) {
            $this->UserDetail = getUserInfo($this->loggedInUser, 'users');
        }
    }

    public function ajaxCheckEmail()
    {
        $email = _inputPost('email');
        if (empty($email)) {
            $email = $this->input->post('FrmSocialEmail');
        }
        if ($email) {
            if ($this->common_model->_CheckExistence('email', $email, 'b_users', array('status' => '1'))) {
                if (isset($_POST['forgetPassword'])) {
                    echo json_encode(array('valid' => false));
                } else {
                    echo json_encode(array('valid' => true));
                }

                exit();
            } else {
                if (isset($_POST['forgetPassword'])) {
                    echo json_encode(array('valid' => true));
                } else {
                    echo json_encode(array('valid' => false));
                }
                exit();
            }
        } else {
            echo json_encode(array('valid' => false));
            exit();
        }
    }

    public function ajaxCheckEmailExists()
    {
        $email = _inputPost('Email');

        if ($email) {
            if ($this->common_model->_CheckExistence('email', $email, 'b_users', array('status' => '1'))) {
                echo json_encode(array('valid' => true));
                exit();
            } else {
                echo json_encode(array('valid' => false));
                exit();
            }
        } else {
            echo json_encode(array('valid' => false));
            exit();
        }
    }

    public function ajaxCheckPhoneExists()
    {
        $phone = _inputPost('Phone');
        if ($phone) {
            if ($this->common_model->_CheckExistence('contact_no', $phone, 'b_users', array('status' => '1'))) {
                echo json_encode(array('valid' => true));
                exit();
            } else {
                echo json_encode(array('valid' => false));
                exit();
            }
        } else {
            echo json_encode(array('valid' => false));
            exit();
        }
    }

    public function AjaxSubscribe()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $failure = false;
        $this->set_rules('Subscribe');
        if ($this->form_validation->run() !== FALSE) {
            $dataArray['email'] = $this->input->post('SubEmail');
            $dataArray['added_on'] = set_local_to_gmt();
            $subscriberExist = $this->common_model->_select('b_subscriber', '*', array('email' => $dataArray['email']));
            if (empty($subscriberExist)) {
                if ($this->common_model->_insert('b_subscriber', $dataArray)) {
                    /*$tokenArray['email'] = $this->input->post('SubEmail');
                    $tokenArray['time'] = time();
                    $token = get_auth_token($tokenArray);
                    $token = urlencode($token);*/
                    $this->viewData['data'] = $dataArray;
                    $mailto = $dataArray['email'];
                    /*$link = "<a title='Unsubscribe Newsletter' href='" . base_url() . "unsubscribe?token=" . $token . "'>Click Here</a>";*/
                    $webLink = "<a title='Green Tip' href='" . base_url() . "'>" . base_url() . "</a>";
                    /*$keyword = array('USER_EMAIL' => $dataArray['email'], 'UNSUBSCRIBE_LINK' => $link, 'WEBSITE_LINK' => $webLink);*/
                    $keyword = array('USER_EMAIL' => $dataArray['email'], 'WEBSITE_LINK' => $webLink);
                    SendEmailByTemplate(1, $keyword, $mailto, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    $success_message = 'Congrats! Your have successfully subscribed.';
                } else {
                    $failure = true;
                    $error_message = 'Whoops! Something went wrong. Please try again.';
                }
            } else {
                if ($this->common_model->_update('b_subscriber', array('status' => 1), array('email' => $dataArray['email']))) {
                    $success_message = 'Congrats! You have successfully subscribed.';
                } else {
                    $failure = true;
                    $error_message = 'Whoops! Something went wrong. Please try again.';
                }
            }
        } else {
            $failure = true;
            $error_message = validation_errors();
        }
        if ($this->input->is_ajax_request()) {
            if ($failure) {
                $data['success'] = false;
                $data['message'] = $error_message;
            } else {
                $data['success'] = true;
                $data['message'] = $success_message;
                $data['resetform'] = true;
            }
            echo json_encode($data);
            die;
        }
    }

    public function signup()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                //pr($_POST, 1);
                $failure = false;
                $this->load->library('form_validation');
                $firstname = $this->input->post('firstname');
                $lastname = $this->input->post('lastname');
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $this->form_validation->set_rules('firstname', 'First Name', 'required|trim', array('required' => 'Please Enter First Name.'));
                $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim', array('required' => 'Please Enter Last Name.'));
                $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check', array('required' => 'Please Enter Email.'));
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[30]', array('required' => 'Please Enter Password.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['name'] = $name = $firstname . ' ' . $lastname;
                    $dataArray['email'] = $email;
                    $dataArray['role_type'] = '2'; /*---2 for end user 1 for expert 0 for super admin*/
                    $dataArray['password'] = $this->user_model->hash($password);;
                    $dataArray['activation_code'] = $rawPass = randomGenerateNumber(8);
                    $userID = $this->common_model->_insertReturnId('b_users', $dataArray);
                    if ($userID) {
                        /*---Verify Mail send to user-----------*/
                        $verifyLink = '<a href="' . base_url() . 'index/verify_email/' . $rawPass . '" title="Activation Account">Click Here</a>';
                        $keywords = array('NAME' => $name, 'EMAIL' => $email, 'VERIFY_LINK' => $verifyLink);
                        SendEmailByTemplate(2, $keywords, $email, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                        $success_message = 'Congrats! Your have registered successfully, Please verify your account by click on email id.';
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['message'] = $success_message;
                    $data['resetform'] = true;
                }
                echo json_encode($data);
                die;
            }
            $this->viewData['title'] = ADMIN_COMPANY . " |  Sign Up";
            $this->load->view('Modals/Register', $this->viewData);
        }
    }

    public function login()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $email = $this->input->post('Email');
                $password = $this->input->post('Password');
                $this->form_validation->set_rules('Email', 'Email', 'required|trim|valid_email', array('required' => 'Please Enter Email.'));
                $this->form_validation->set_rules('Password', 'Password', 'required', array('required' => 'Please Enter Password.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $success_message = '';
                    $userData = $this->common_model->_selectById('b_users', '*', array('email' => $email, 'status !=' => '3'));
                    if (empty($userData)) {
                        $error_message = 'Email id or password is incorrect';
                        $failure = true;
                    } elseif (!$this->user_model->check_password($password, $userData['password'])) {
                        $error_message = 'Email id or password is incorrect';
                        $failure = true;
                    } elseif ($userData['status'] == '0') {
                        $error_message = 'Please verify your email id to get started. For any issues, please contact support team.';
                        $failure = true;
                    } elseif ($userData['status'] == '2') {
                        $error_message = 'Your account has been deactivated. Please contact support for more information.';
                        $failure = true;
                    } else {
                        $ip = $this->input->ip_address();
                        $this->common_model->_update('b_users', array('last_login' => set_local_to_gmt(), 'ip_address' => $ip), array('id' => $userData['id']));
                        setSessionUserData('id', $userData['id']);
                        $this->auth->login($userData['id'], 'user', 'FALSE');
                        $success_message = 'Great! You have successfully logged in.';
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['hideModel'] = true;
                    $data['loginurl'] = base_url('dashboard');
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
            $this->viewData['title'] = ADMIN_COMPANY . " |  Login";
            $this->load->view('Modals/Login', $this->viewData);

        }
    }

    public function forgot_password()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $email = $this->input->post('Email');
                $this->form_validation->set_rules('Email', 'Email', 'required|trim|valid_email', array('required' => 'Please Enter Email.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $success_message = '';
                    $userData = $this->common_model->_selectById('b_users', '*', array('email' => $email, 'status!=' => '3'));
                    if (!empty($userData) && $userData['status'] == '1') {
                        $userId = $userData['id'];
                        $rawPass = randomGenerateString(8);
                        $updateData['activation_code'] = $rawPass;
                        $update = $this->common_model->_update('b_users', $updateData, array('id' => $userId));
                        if ($update) {
                            $forgetLink = '<a href="' . base_url() . 'reset-password/' . $rawPass . '" title="Reset Password">Click Here</a>';
                            $keywords = array('NAME' => $userData['name'], 'USER_EMAIL' => $userData['email'], 'FORGOT_LINK' => $forgetLink);
                            SendEmailByTemplate(4, $keywords, $email, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                            $success_message = 'Reset password link has been sent to your email address. Please Check Your Email.';
                        } else {
                            $failure = 'true';
                            $error_message = 'Whoops! Something went wrong, Please try again.';
                        }
                    } else {
                        if (empty($userData)) {
                            $failure = 'true';
                            $error_message = 'You are trying with wrong email. Please try with valid email.';
                        } elseif ($userData['status'] == '0') {
                            $failure = 'true';
                            $error_message = 'Please verify your email id to get started. For any issues, please contact support team.';
                        } elseif ($userData['status'] == '2') {
                            $failure = 'true';
                            $error_message = 'Your account has been deactivated. Please contact support for more information.';
                        }

                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['resetform'] = true;
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
            $this->viewData['title'] = ADMIN_COMPANY . " |  Forgot Password";
            $this->load->view('Modals/Forgot-Password', $this->viewData);

        }
    }

    /*---end user submit query----*/
    public function submit_query()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                 

                 if ($_FILES) {
                //$filename       =            $_FILES['attachment_file']['name'] ; 
                $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $rand_val = date('YMDHIS') . rand(11111, 99999);
                 $filename = md5($rand_val) . "." . $imgInfo;
                $_FILES['attachment_file']['name'] = $filename;
            
                $config['upload_path']          = "assets/uploads/attachment_file";
                $config['allowed_types']        = 'gif|jpg|png|jpeg|mp4|doc|docx|pdf';
                $config['max_size']             = 100000;
                $config['max_width']            = 100024;
                $config['max_height']           = 10768;
                $attachment =  "assets/uploads/attachment_file/".$filename;
                $this->load->library('upload', $config);
                $this->upload->do_upload('attachment_file'); 
            }
                $failure = false;
                $this->load->library('form_validation');
                $category_id = $this->input->post('category_id');
                $query = $this->input->post('query');
                $user_id = $this->input->post('user_id');
                $this->form_validation->set_rules('category_id', 'Category', 'required|trim', array('required' => 'Please Enter Category.'));
                $this->form_validation->set_rules('query', 'Query', 'required|trim', array('required' => 'Please Enter Query.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['category_id'] = $category_id;
                    $dataArray['query'] = $query;
                    $dataArray['user_id'] = $user_id;
                     if ( $attachment) {
                         $dataArray['query_image'] =$attachment;
                    }
                   
                    $dataArray['added_on'] = set_local_to_gmt();
                    $dataArray['modified_on'] = set_local_to_gmt();
                    $userID = $this->common_model->_insertReturnId('b_queries', $dataArray);
                    if ($userID) {
                        /*----mail send to super admin----*/
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->UserDetail['name'], 'SENDER_EMAIL' => $this->UserDetail['email'], 'QUERY' => $query);
                        SendEmailByTemplate(8, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->UserDetail['name']);
                        setSessionFlashData('success', 'Congrats! Your query has been submitted.');
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['message'] = '';
                    $data['resetform'] = true;
                    $data['selfReload'] = true;
                }
                echo json_encode($data);
                die;
            }
            $this->viewData['title'] = ADMIN_COMPANY . " |  Sign Up";
            $this->load->view('Modals/Register', $this->viewData);

        }
    }

    public function discard_query()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $query_id = $this->input->post('query_id');
                $this->form_validation->set_rules('query_id', 'Query Id', 'required|trim', array('required' => 'Query id not found.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['status'] = '2';
                    if ($this->common_model->_update('b_queries', $dataArray, ['id' => $query_id])) {
                        /*----mail send to end user----*/
                        $queryData = $this->common_model->_selectById('b_queries', 'user_id,query', ['id' => $query_id]);
                        $endUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $queryData['user_id']));
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $endUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'QUERY' => $queryData['query']);
                        SendEmailByTemplate(9, $keyword, $endUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);
                        $success_message = 'Your query has been discarded.';
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
        }
    }

    public function discard_assigned_query()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $query_id = $this->input->post('query_id');
                $this->form_validation->set_rules('query_id', 'Query Id', 'required|trim', array('required' => 'Query id not found.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['status'] = '2';
                    if ($this->common_model->_update('b_query_assign', $dataArray, ['id' => $query_id])) {
                        $success_message = 'Your query has been discarded.';
                        /*----mail send to admin----*/
                        $queryAssignData = $this->common_model->_selectById('b_query_assign', 'query_id', ['id' => $query_id]);
                        $queryData = $this->common_model->_selectById('b_queries', 'query', ['id' => $queryAssignData['query_id']]);
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->UserDetail['name'], 'SENDER_EMAIL' => $this->UserDetail['email'], 'QUERY' => $queryData['query']);
                        SendEmailByTemplate(11, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->UserDetail['name']);
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
        }
    }

    public function assign_to_expert($id, $type)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $expert_id = $this->input->post('expert_id');
                $this->form_validation->set_rules('expert_id', 'Assign to expert', 'required|trim', array('required' => 'Please select expert.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['user_id'] = $expert_id;
                    $dataArray['query_id'] = $id;
                    $dataArray['added_on'] = set_local_to_gmt();
                    $userID = $this->common_model->_insertReturnId('b_query_assign', $dataArray);
                    if ($userID) {

                        $usertype = $this->common_model->_selectById('b_users', 'id,role_type', ['id' => $userID]);
                        if($usertype["role_type"] == 3)
                        {
                            $success_message = 'Congrats! Your query has been assigned to MD.';
                        }
                        else
                        {
                            $success_message = 'Congrats! Your query has been assigned to expert.';
                        }

                        
                        /*----mail send to expert user----*/
                        $queryData = $this->common_model->_selectById('b_queries', 'user_id,query', ['id' => $id]);
                        $expertUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $expert_id));
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $expertUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'QUERY' => $queryData['query']);
                        SendEmailByTemplate(10, $keyword, $expertUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['message'] = $success_message;
                    $data['success'] = true;
                    $data['resetform'] = true;
                    $data['selfReload'] = true;
                    $data['hideModel'] = true;
                }
                echo json_encode($data);
                die;
            }



            $this->viewData['queryData'] = $this->user_model->getQueryById($id);
            $assignExperts = $this->common_model->_select('b_query_assign', 'user_id', ['query_id' => $id]);
            $expertIds = '';
            if (!empty($assignExperts)) {
                $expertIds = ' AND id NOT IN (' . implode(',', array_column($assignExperts, 'user_id')) . ')';
            }
            

            $this->viewData['type'] = $type;
            if($type == 1)
            {
              $condition = " status = '1' AND role_type = '1'  " . $expertIds;
               $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition, 'id,name,email');
              $this->viewData['title'] = ADMIN_COMPANY . " |  Assign to Expert";
            }
            else
            {
              // for md users   -- done by monu from 23-04-2020
            $condition1 = " status = '1' AND role_type = '3'  " . $expertIds;
                $this->viewData['experts'] = $this->common_model->getMultipleRecord('b_users', $condition1, 'id,name,email');
              $this->viewData['title'] = ADMIN_COMPANY . " |  Assign to MD";
            }

            
            $this->load->view('Modals/Assign_to_Expert', $this->viewData);

        }
    }

    public function respond_by_expert($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
             if ($_FILES) {
                //$filename       =            $_FILES['attachment_file']['name'] ; 
                $imgInfo = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $rand_val = date('YMDHIS') . rand(11111, 99999);
                 $filename = md5($rand_val) . "." . $imgInfo;
                $_FILES['attachment_file']['name'] = $filename;
            
                $config['upload_path']          = "assets/uploads/attachment_file";
                $config['allowed_types']        = 'gif|jpg|png|jpeg|mp4|doc|docx|pdf';
                $config['max_size']             = 100000;
                $config['max_width']            = 100024;
                $config['max_height']           = 10768;
                $attachment =  "assets/uploads/attachment_file/".$filename;
                $this->load->library('upload', $config);
                $this->upload->do_upload('attachment_file'); 
            }
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $answer = $this->input->post('answer');
                $this->form_validation->set_rules('answer', 'Answer', 'required|trim', array('required' => 'Please enter Answer.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['expert_answer'] = $answer;
                    $dataArray['status'] = '1';
                    if ( $attachment) {
                         $dataArray['upload_image'] =$attachment;
                    }
                   
                    $dataArray['respond_Date'] = set_local_to_gmt();
                    $this->common_model->_update('b_query_assign', $dataArray, ['id' => $id]);
                    if ($id) {
                        /*----mail send to admin----*/
                        $queryAssignData = $this->common_model->_selectById('b_query_assign', 'query_id', ['id' => $id]);
                        $queryData = $this->common_model->_selectById('b_queries', 'query_image','query', ['id' => $queryAssignData['query_id']]);
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $superUserData['name'], 'SENDER_NAME' => $this->UserDetail['name'], 'SENDER_EMAIL' => $this->UserDetail['email'], 'QUERY' => $queryData['query'], 'RESPOND_MESSAGE' => $answer);
                        SendEmailByTemplate(12, $keyword, $superUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $this->UserDetail['name']);
                        $success_message = 'Congrats! Your respond has been submitted.';
                        setSessionFlashData('success', $success_message);
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['resetform'] = true;
                    $data['selfReload'] = true;
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
            $queryAssignData = $this->user_model->getAssignedQueryById($id);
            $this->viewData['title'] = ADMIN_COMPANY . " |  Respond By Expert";
            $this->viewData['queryData'] = $queryAssignData;
            $this->load->view('Modals/Respond_by_Expert', $this->viewData);

        }
    }

    public function review($query_assign_id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            if (!empty($_POST)) {
                $failure = false;
                $this->load->library('form_validation');
                $answer = $this->input->post('answer');
                $query_id = $this->input->post('query_id');
                $this->form_validation->set_rules('answer', 'Answer', 'required|trim', array('required' => 'Please enter Answer.'));
                if ($this->form_validation->run($this) !== FALSE) {
                    $dataArray['answer'] = $answer;
                    $dataArray['admin_review_date'] = set_local_to_gmt();
                    $dataArray['review_status'] = '1';
                    $this->common_model->_update('b_query_assign', $dataArray, ['id' => $query_assign_id]);
                    $this->common_model->_update('b_queries', ['status' => '1'], ['id' => $query_id]);
                    if ($query_assign_id) {
                        /*----mail send to end user----*/
                        $queryData = $this->common_model->_selectById('b_queries', 'query,user_id', ['id' => $query_id]);
                        $endUserData = $this->common_model->_selectById('b_users', 'name,email', array('id' => $queryData['user_id'], 'status !=' => '3'));
                        $superUserData = $this->common_model->_selectById('b_users', 'name,email', array('role_type' => '0', 'status !=' => '3'));
                        $keyword = array('RECEIVER_NAME' => $endUserData['name'], 'SENDER_NAME' => $superUserData['name'], 'SENDER_EMAIL' => $superUserData['email'], 'QUERY' => $queryData['query']);
                        SendEmailByTemplate(13, $keyword, $endUserData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $superUserData['name']);
                        $success_message = 'Congrats! Your review has been submitted.';
                        setSessionFlashData('success', $success_message);
                    } else {
                        $error_message = 'Whoops! Something went wrong. Please try again later.';
                        $failure = true;
                    }
                } else {
                    $error_message = validation_errors();
                    $failure = true;
                }
                if ($failure) {
                    $data['success'] = false;
                    $data['message'] = $error_message;
                } else {
                    $data['success'] = true;
                    $data['resetform'] = true;
                    $data['selfReload'] = true;
                    $data['message'] = $success_message;
                }
                echo json_encode($data);
                die;
            }
            $condition = array('qa.id' => $query_assign_id);
            $result = $this->user_model->getAssignedQuery('b_query_assign', '0', '0', '*', $condition);
            $listings = [];
            $pagination = '';
            if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                $listings = $result['results'][0];
            }
             $this->viewData['query_assign_id'] = $query_assign_id;
            $this->viewData['pagination'] = $pagination;
            $this->viewData['dbdata'] = $listings;
            $this->viewData['title'] = ADMIN_COMPANY . " |  Respond By Expert";
            $this->load->view('Modals/Review', $this->viewData);
        }
    }

    public function answer_by_expert($query_assign_id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $condition = array('qa.id' => $query_assign_id);
            $result = $this->user_model->getAssignedQuery('b_query_assign', '0', '0', '*', $condition);
            $listings = [];
            $pagination = '';
            if (isset($result['total_rows']) && $result['total_rows'] > 0) {
                $listings = $result['results'][0];
            }
            $this->viewData['pagination'] = $pagination;
            $this->viewData['dbdata'] = $listings;
            $this->viewData['title'] = ADMIN_COMPANY . " |  Respond By Expert";
            $this->load->view('Modals/Answer_by_Expert', $this->viewData);
        }
    }

    public function view_expert_detail($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $condition = array('id' => $id);
            $this->viewData['dbdata'] = $this->common_model->_selectByID('b_users', '*', $condition);
            $this->load->view('Modals/View_expert', $this->viewData);
        }
    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error-server">', '</span>');
        if ($option == 'Subscribe') {
            $this->form_validation->set_data($this->input->get());
            $this->form_validation->set_rules('SubEmail', 'Email', 'required|trim|valid_email');
        }

    }

    public function useremail_check($str)
    {
        $condition = array('status!=' => '3');
        if ($this->common_model->_CheckExistence('email', $str, 'b_users', $condition)) {
            return true;
        } else {
            $this->form_validation->set_message('useremail_check', 'Whoops! User email already exists. Please try with another email.');
            return false;
        }
    }

}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends Api_Controller
{

    public function __construct()
    {
        parent::__construct();
        /* Upload Profile Pic Path */
        $this->uploadPath = UPLOAD_PATH;
    }

    public function register_post()
    {

        try
        {
        $params = $this->post();

        $this->set_rules('Register');

        if ($this->form_validation->run() !== FALSE) {

            $first_name = isset($params['first_name']) ? $params['first_name'] : '';
            $last_name = isset($params['last_name']) ? $params['last_name'] : '';
            $full_name = $first_name . ' ' . $last_name;
            $email = isset($params['email']) ? $params['email'] : '';
            $pass = isset($params['password']) ? $params['password'] : '';

            $emailExist = $this->common_model->_selectById('b_users', '', array('email' => $email, 'status !=' => '3'));
            if (empty($emailExist)) {

                $insertData = array();

                $insertData['role_type'] = '2'; /*---2 for end user 1 for expert 0 for super admin*/
                $insertData['name'] = $full_name;
                $insertData['email'] = $email;
                $insertData['password'] = $this->user_model->hash($pass);
                $verificationCode = randomGenerateString(8);
                $insertData['activation_code'] = $verificationCode;

                $userID = $this->common_model->_insertReturnId('b_users', _xssClean($insertData));

                if ($userID > 0) {
                    $link = '<a href="' . base_url() . 'index/verify_email/' . $verificationCode . '" title="Activate Account">Click Here</a>';
                    $keywords = array('NAME' => $insertData['name'], 'EMAIL' => $email, 'VERIFY_LINK' => $link);
                    SendEmailByTemplate(2, $keywords, $insertData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    $this->set_response([
                        'status' => true,
                        'message' => 'A verification link has been sent to email account.',
                        'data' => array('user_id' => $userID)
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->set_response([
                    'status' => false,
                    'message' => 'Email address already exists',
                    'data' => $params
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->set_response([
                'status' => false,
                'message' => filter_validation_errors(),
                'data' => $params
            ], REST_Controller::HTTP_OK);
        }
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 

    }

    public function login_post()
    {
        try
        {
        $params = $this->post();
        //print_r( $params);die;
        $this->load->library('auth');
        $this->set_rules('Login');
        if ($this->form_validation->run() !== FALSE) {
            $email = $this->input->post('email');
            $password = trim($this->input->post('password'));
            if (isset($password) && !empty($password) && isset($email) && !empty($email)) {
                $userData = $this->common_model->_selectById('b_users', '*', array('email' => $email, 'status !=' => '3'));
                if (empty($userData)) {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Email address is not register with us.',
                        'data' => '',
                    ], REST_Controller::HTTP_OK); // OK (400) being the HTTP response code
                } elseif ($userData['status'] == '0') {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Your account is not active.Kindly contact to administrator.',
                        'data' => '',
                    ], REST_Controller::HTTP_OK); // OK (400) being the HTTP response code
                } elseif (!$this->user_model->check_password($password, $userData['password'])) {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Your password is incorrect.',
                        'data' => '',
                    ], REST_Controller::HTTP_OK); // OK (400) being the HTTP response code
                } elseif ($userData['status'] == '2') {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Your Account has been rejected by administrator',
                        'data' => ''
                    ], REST_Controller::HTTP_OK); // OK (400) being the HTTP response code
                } elseif ($userData['status'] == '3') {
                    $this->set_response([
                        'status' => false,
                        'message' => 'your account has been deleted by administrator'
                    ], REST_Controller::HTTP_OK); // OK (400) being the HTTP response code
                } else {
                    $ip = $this->input->ip_address();
                    $this->common_model->_update('b_users', array('last_login' => set_local_to_gmt(), 'ip_address' => $ip), array('id' => $userData['id']));
                   //print_r($this->uploadPath);
                   //print_r($userData);die; 
                    $userData['image'] = (!empty($userData['image'])) ? $this->uploadPath . "/users/" . $userData['image'] : base_url('assets/grocery_crud/themes/bootstrap/img/a0.jpg');

                    parent::loginReturnData($userData);
                }
            } else {
                $this->set_response([
                    'status' => false,
                    'message' => 'Kindly provide parameters',
                    'id' => '',
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->set_response([
                'status' => false,
                'message' => filter_validation_errors(),
                'data' => $params,
            ], REST_Controller::HTTP_OK);
        }
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 
    }

    public function forgot_password_post()
    {

        try
        {
        $params = $this->post();
         $failure = false;
              
                $email = $this->input->post('email');
              
               //echo  $email;die;
                if ( $email) {
                    $success_message = '';
                    $userData = $this->common_model->_selectById('b_users', '*', array('email' => $email, 'status!=' => '3'));
                    //print_r($userData );die;
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
                // echo json_encode($data);
                // die;
        // print_r($params);die;
        // $this->set_rules('Forgot');
        // if ($this->form_validation->run() !== FALSE) {
        //     $userData = $this->common_model->_selectById('b_users', '*', array('email' => $params['email'], 'status !=' => '3'));
        //     if (empty($userData)) {
        //         $flag = false;
        //         $message = 'Email address is not register with us.';
        //     } elseif ($userData['status'] == '0') {
        //         $flag = false;
        //         $message = 'Your account is not verify.Kindly check your email to verify your account.';
        //     } elseif ($userData['status'] == '2') {
        //         $flag = false;
        //         $message = 'Your Account has been rejected by administrator';
        //     } elseif ($userData['status'] == '3') {
        //         $flag = false;
        //         $message = 'your account has been deleted by administrator';
        //     } else {
        //         $userId = $userData['id'];
        //         $rawPass = randomGenerateString(8);
        //         $password = $this->user_model->hash(trim($rawPass));
        //         $updateData['password'] = $password;
        //         $update = $this->common_model->_update('b_users', _xssClean($updateData), array('id' => $userId));
        //         if ($update) {
        //             $keywords = array('NAME' => $userData['name'], 'USER_EMAIL' => $userData['email'], 'PASSWORD' => $rawPass);
        //             SendEmailByTemplate(16, $keywords, $params['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
        //             $flag = true;
        //             $message = 'Your password has been reset and password sent your registered email address.';
        //         } else {
        //             $flag = false;
        //             $message = 'Something went wrong.Please try again later.';
        //         }
        //     }
        // } else {
        //     $flag = false;
        //     $message = filter_validation_errors();
        // }
        $this->set_response([      
                   
            'status' => $data['success'],
            'message' => $data['message'],
            'data' => $data
        ], REST_Controller::HTTP_OK);
    }//try
    catch(Exception $e) 
            {  
                log_message('error', "\n Exception Caught", $e->getMessage());
                $flag = false;
                $message = $e->getMessage();

                  $this->set_response([
                    'status' => $flag,
                    'message' => $message,
                    'data' => array()
                ], REST_Controller::HTTP_OK);

            } // catch 
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

    function set_rules($option)
    {
        $this->load->library('form_validation');
        if ($option == 'Register') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim', array('required' => 'Please Enter First Name.'));
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array('required' => 'Please Enter Last Name.'));
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_useremail_check', array('required' => 'Please Enter Email.'));
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[30]', array('required' => 'Please Enter Password.'));
        }
        if ($option == 'Login') {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', array('required' => 'Please Enter Email.'));
            $this->form_validation->set_rules('password', 'Password', 'required', array('required' => 'Please Enter Password.'));
        }
        if ($option == 'Forgot') {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', array('required' => 'Please Enter Email.'));
        }
    }

}
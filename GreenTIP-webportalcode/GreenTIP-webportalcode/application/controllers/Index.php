<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller
{
    public $viewData = array();
    public $loggedInUser = array();
    public $UserDetail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->loggedInUser = (array)getSessionUserData('auth_user_data');
        if (isset($this->loggedInUser) && !empty($this->loggedInUser)) {
            $this->UserDetail = getUserInfo($this->loggedInUser['id'], 'users');
        }
    }

    public function index()
    { 
         //$this->load->view('User/maintenance');  
         try {
            tryLogPrinter("[info] :grcgreentip/index()", $params);

        $this->viewData['about_us'] = $this->common_model->_selectById('b_pages', '', array('alias' => 'about-us'));
        $this->viewData['experts'] = $this->common_model->_select('b_users', '*', array('status' => '1', 'role_type' => '1'), 'id', 'DESC', '0', '4');
        $total_expert = $this->common_model->_selectByID('b_users', 'COUNT(*) as total', array('status!=' => '3', 'role_type' => '1'));
        $this->viewData['total_expert'] = $total_expert['total'];
        $this->viewData['title'] = 'Green Tip';
        $this->load->view('Index', $this->viewData);
         }//try
        catch (Exception $e) {
            log_message('[error]: ', "\n Exception Caught", $e->getMessage());
        }//catch
    }
    

    function confirmPhone()
    { 
        try {
            tryLogPrinter("[info] :confirmPhone()", $params);
        $activation_code = getStringSegment(3) != '' ? getStringSegment(3) : 0;
        if ($activation_code) {
            if (isset($this->userData['id']) && $this->userData['id'] > 0) {
                setSessionFlashData('error', 'Please logout from current login account');
                redirect(base_url());
            } else {

                $this->load->model('loginmodel');
                $IsUserExist = $this->loginmodel->CheckUserEmailActivation($activation_code);
                if ($IsUserExist) {
                    if ($IsUserExist->status == 0) {
                        setSessionFlashData('error', 'Your account does not activate till now.!');
                    } else if ($IsUserExist->status == 2) {
                        setSessionFlashData('error', 'Your account has been Blocked by administrator');
                    } else if ($IsUserExist->status == 3) {
                        setSessionFlashData('error', 'Your account has been Deleted by administrator');
                    } else {
                        $data = array('email' => $IsUserExist->varify_email);
                        if ($this->commonmodel->_update('b_users', $data, array('id' => $IsUserExist->id))) {
                            $this->commonmodel->_delete('b_users_phone_verify', array('user_id' => $IsUserExist->id));
                            setSessionFlashData('success', 'Your phone number has been varified successfully');
                        } else {
                            setSessionFlashData('error', 'Sorry, please try again');
                        }
                    }
                } else {
                    setSessionFlashData('error', 'Not a valid otp');
                }
            }
        }
        redirect(base_url());
         }//try
        catch (Exception $e) {
            log_message('[error]: confirmPhone()', "\n Exception Caught", $e->getMessage());
        }//catch
    }

    public function verify_email()
    {
        try {
            tryLogPrinter("[info]: verify_email()", $params);
        $activate_code = getStringSegment(3) != '' ? getStringSegment(3) : 0;
        if (!empty($this->UserDetail)) {
            setSessionFlashData('error', 'Please logout from current login account');
            redirect(base_url());
        } else {
            if ($activate_code == '') {
                setSessionFlashData('error', 'Activation code cannot be empty');
                redirect(base_url());
            } else {
                $userData = $this->common_model->_selectById('b_users', 'name,email', array('activation_code' => $activate_code, 'status !=' => '3'));
                if (empty($userData)) {
                    setSessionFlashData('error', 'Activation code does not match.');
                    redirect(base_url());
                } else {
                    if ($userData['status'] == 0) {
                        $this->common_model->_update('b_users', array('status' => '1', 'activation_code' => ''), array('activation_code' => $activate_code, 'status !=' => '3'));
                        $keywords = array('NAME' => $userData['name'], 'EMAIL' => $userData['email']);
                        SendEmailByTemplate(3, $keywords, $userData['email'], ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                        setSessionFlashData(array('success' => 'Congrats! You successfully activated your email address.'));
                        redirect(base_url(''));
                    } else if ($userData['status'] == 2) {
                        setSessionFlashData('error', 'Your account has been Blocked by administrator');
                        redirect(base_url(''));
                    } else if ($userData['status'] == 3) {
                        setSessionFlashData('error', 'Your account has been Deleted by administrator');
                        redirect(base_url(''));
                    }
                }
            }
        }
        }//try
        catch (Exception $e) {
            log_message('[error]: verify_email()', "\n Exception Caught", $e->getMessage());
        }//catch
    }

    public function unsubscribe()
    { try {
            tryLogPrinter("[info]: unsubscribe()", $params);
        $rawToken = _inputGet('token') != '' ? _inputGet('token') : '';
        if (!empty($rawToken)) {
            $tokenArray = decrypt($rawToken);
            if (!empty($tokenArray)) {
                $tokenFresh = json_decode($tokenArray);
                $email = $tokenFresh[0];
                $isCourseExists = $this->common_model->_selectById('b_subscriber', '*', ['email' => $email]);
                if (!empty($isCourseExists)) {
                    $this->common_model->_update('b_subscriber', array('status' => '0'), array('email' => $email));
                    setSessionFlashData('success', 'You have successfully un-subscribed.');
                    redirect(base_url());
                } else {
                    setSessionFlashData('error', 'Whoops! Invalid token1');
                    redirect(base_url());
                }
            } else {
                setSessionFlashData('error', 'Whoops! Invalid token2.');
                redirect(base_url());
            }
        } else {
            setSessionFlashData('error', 'Whoops! Without token you can not verify your Greentip account.');
            redirect(base_url());
        }
        }//try
        catch (Exception $e) {
            log_message('[error]: unsubscribe()', "\n Exception Caught", $e->getMessage());
        }//catch
    }

    public function Contact()
    {  try {
            tryLogPrinter("[info]: Contact()", $params);

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="has-error-server">', '</span>');
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('message', 'Message', 'required|min_length[10]');
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            if ($this->form_validation->run($this) !== FALSE) {

                $dataArray['name'] = $this->input->post('name');
                $dataArray['email'] = $this->input->post('email');
                $dataArray['contact_no'] = $this->input->post('phone');
                $dataArray['message'] = $this->input->post('message');
                $dataArray['added_on'] = set_local_to_gmt();

                $enquiryID = $this->common_model->_insertReturnId('b_contact_forms', $dataArray);
                if ($enquiryID) {
                    $keywords = array('MESSAGE' => $dataArray['message'], 'EMAIL' => $dataArray['email'], 'NAME' => $dataArray['name'], 'PHONE' => $dataArray['contact_no']);
                    SendEmailByTemplate(5, $keywords, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE);
                    setSessionFlashData('success', 'We have received you enquiry and we will contact you soon');
                    redirect(base_url('contact'));
                }
            } else {
                setSessionFlashData('error', filter_validation_errors());
                redirect(base_url() . "contact");
            }
        }
        $this->viewData['title'] = "Contact Us | " . ADMIN_COMPANY;
        $this->load->view('Contact', $this->viewData);
         }//try
        catch (Exception $e) {
            log_message('[error]: Contact()', "\n Exception Caught", $e->getMessage());
        }//catch
    }

}
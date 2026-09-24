<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    var $data = array();
    var $viewData = array();
    var $loginUserData = array();
    var $UserDetail = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->loginUserData = (array)getSessionUserData('auth_user_data');
        if (isset($this->loginUserData) && !empty($this->loginUserData)) {
            $this->UserDetail = getUserInfo($this->loginUserData['id'], 'users');
        }
    }

    public function ResetPassword()
    {
         try
        {
            tryLogPrinter("ResetPassword", $params);
        $reset_token = getStringSegment(2) != '' ? getStringSegment(2) : 0;
        if ($reset_token) {
            if ($this->auth->loggedin('user')) {
                redirect(base_url('user/profile'));
            } else {
                $IsUserExist = $this->common_model->_selectById('b_users', '*', array('activation_code' => $reset_token, 'status !=' => '3'));
                if ($IsUserExist) {
                    $data['reset_token'] = $reset_token;
                    $data['title'] = ADMIN_COMPANY . " | Reset Password";
                    $this->load->view('reset_password', $data);
                } else {
                    setSessionFlashData('error', 'You have already reset the password with this link. ');
                    redirect(base_url());
                }
            }
        } else {
            redirect(base_url());
        }
         }//try
        catch(Exception $e) 
        {  
            log_message('error', "\n Exception Caught", $e->getMessage());
        }//catch
    }

    public function doResetPassword()
    {   try
        {
            tryLogPrinter("doResetPassword", $params);
        if ($this->input->post()) {
            $this->set_rules('Resetpassword');
            $reset_token = trim(_inputPost('reset_token'));
            $resetPassword = trim(_inputPost('resetPassword'));
            $resetConPassword = trim(_inputPost('resetConPassword'));
            if ($this->form_validation->run() == TRUE) {
                if ($resetPassword != $resetConPassword) {
                    setSessionFlashData('error', 'Please Match Password and Confirm Password !');
                    redirect(base_url() . "reset-password/" . $reset_token);
                }
                $userData = $this->common_model->_selectById('b_users', '*', array('activation_code' => $reset_token, 'status !=' => '3'));
                if (empty($userData)) {
                    setSessionFlashData('error', 'Not a valid activation link so please check your mail box.');
                    redirect(base_url());
                } elseif ($userData['status'] == '0') {
                    setSessionFlashData('error', 'Please verify your email id to get started. For any issues, please contact support team.');
                    redirect(base_url());
                } elseif ($userData['status'] == '2') {
                    setSessionFlashData('error', 'Your account has been deactivated. Please contact support for more information.');
                    redirect(base_url());
                } else {
                    $insertData = array();
                    $insertData['password'] = $this->user_model->hash($resetPassword);
                    $insertData['activation_code'] = '';
                    if ($this->common_model->_update('b_users', $insertData, array('id' => $userData['id']))) {
                        setSessionFlashData('success', 'Password has been changed successfully. Please login to continue.');
                        redirect(base_url(''));
                    } else {
                        setSessionFlashData('error', 'Whoops! Something went wrong, Please try again.');
                        redirect(base_url());
                    }
                }
            } else {
                setSessionFlashData('error', filter_validation_errors());
                redirect(base_url() . "reset-password/" . $reset_token);
            }
        } else {
            setSessionFlashData('error', 'Whoops! Something went wrong, Please try again.');
            redirect(base_url());
        }
        }//try
        catch(Exception $e) 
        {  
            log_message('error', "\n Exception Caught", $e->getMessage());
        }//catch

    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
        if ($option == 'Resetpassword') {
            $this->form_validation->set_rules('resetPassword', 'Password', 'required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('resetConPassword', 'Confirm Password', 'required|min_length[6]|max_length[30]');
        }

    }

}
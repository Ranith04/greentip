<?php
/**
 * Created by PhpStorm.
 * User: abhishek
 * Date: 7/2/15
 * Time: 10:50 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MX_Controller
{
    public $viewData = array();
    public $loggedInAdmin = array();
    private $upload_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->model('common_model', 'CommonModel');
        $this->load->model('admin_model');
        $this->load->model('auth_model');
        $this->viewData['data'] = array();
        $this->loggedInAdmin = getSessionUserData('auth_admin_data');
    }

    //log the user in
    function login()
    {
        //$this->load->view('User/maintenance');  
        if ($this->auth->loggedin('admin')) {
            redirect(base_url('admin'));
        }
        $this->viewData['title'] = "Admin Login";
        //validate form input
        $this->set_rules('login');
        if ($this->form_validation->run() !== FALSE) {
            $ip = $this->input->ip_address();
            $remember = $this->input->post('remember') ? TRUE : FALSE;
            // get user from database
            $user = $this->admin_model->get('username', $this->input->post('username'));
            if ($user) {
                // compare passwords
                if ($user['role_type'] != '0') {
                    setSessionFlashData('error', 'This account only access by admin');
                    redirect(base_url('admin/login'));
                } else {
                    if ($this->admin_model->check_password($this->input->post('password'), $user['password'])) {
                        $this->auth->login($user['id'], 'admin', $remember);
                        $this->CommonModel->_update('b_users', array('last_login' => set_local_to_gmt(), 'ip_address' => $ip), array('id' => $user['id']));
                        setSessionFlashData('success', 'Great! You have successfully logged in.');
                        redirect(base_url('admin'));

                    } else {
                        if ($this->auth_model->confirmIPAddress($ip, '1')) {
                            setSessionFlashData('error', 'Whoops! You reached maximum number of attempts. Your IP has been blocked for security reasons. Please login after 30 minutes.');
                            redirect(base_url('admin/login'));
                        }
                        setSessionFlashData('error', 'Whoops! Invalid access. Please enter valid credentials.');
                        redirect(base_url('admin/login'));
                    }
                }
            } else {
                setSessionFlashData('error', 'Whoops! Invalid access. Please enter valid credentials.');
                redirect(base_url('admin/login'));
            }
        }
        $this->load->view('Authentication/login', $this->viewData);
    }

    //log the user out
    function logout()
    {
        $this->session->unset_userdata('auth_admin_data');
        $this->auth->logout('admin');
        redirect(base_url('admin/login'));
    }

    function profile()
    {
        isLoggedIn($type = 'admin');
        $this->viewData['dbData'] = $this->admin_model->get('id', $this->loggedInAdmin['id']);
        $this->set_rules('Settings');
        if ($this->form_validation->run($this) === TRUE) {
            $dataArray['name'] = $this->input->post('FullName');
            if (isset($_FILES['AdminImage']) && !empty($_FILES['AdminImage']['name'])) {
                $dataArray['image'] = $this->upload_data['file_name'];;
            }
            $dataArray['updated_on'] = set_local_to_gmt();
            if ($this->CommonModel->_update('b_users', $dataArray, array('id' => $this->loggedInAdmin['id']))) {
                setSessionFlashData('success', 'Congrats! You have successfully updated Website Settings');
                redirect(base_url('admin'));
            }
        }
        $this->viewData['title'] = "Senior Citizen Security Admin | Profile";
        $this->load->view('Authentication/Settings', $this->viewData);
    }

    function change_password()
    {
        isLoggedIn($type = 'admin');

        $this->set_rules('ChangePassword');
        if ($this->form_validation->run($this) !== FALSE) {

            $NewPasswordPlane = $this->input->post('NewPassword');
            $NewPassword = $this->admin_model->hash($NewPasswordPlane);
            if ($this->CommonModel->_update('b_users', array('updated_on' => set_local_to_gmt(), 'password' => $NewPassword), array('id' => $this->loggedInAdmin['id']))) {
                $this->auth->logout('admin');
                setSessionFlashData('success', 'Congrats! You have successfully updated your Admin Panel password. Please login with new Password');
                redirect(base_url('admin/login'));
            } else {
                setSessionFlashData('error', 'Whoops! Seems like some thing technical problem occurred. Please try later.');
                redirect(base_url('admin/change-password'));
            }
        }
        $this->viewData['title'] = "Senior Citizen Security Admin | Change Password";
        $this->load->view('Authentication/ChangePassword', $this->viewData);
    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="has-error"><span class="help-block">', '</span></div>');
        if ($option == 'login') {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($option == 'ChangePassword') {
            $this->form_validation->set_rules('OldPassword', 'Old Password', 'trim|required|callback_check_old_password');
            $this->form_validation->set_rules('NewPassword', 'New Password', 'trim|required');
            $this->form_validation->set_rules('ConfirmPassword', 'Confirm Password', 'trim|required|matches[NewPassword]');
        }
        if ($option == 'Settings') {
            $this->form_validation->set_rules('FullName', 'Admin Full Name', 'required');
            $this->form_validation->set_rules('AdminImage', 'Profile Image', 'callback_handle_upload');
        }
    }

    public function check_old_password()
    {
        $password = $this->loggedInAdmin['password'];
        if ($this->admin_model->check_password($this->input->post('OldPassword'), $password)) {
            return true;
        } else {
            $this->form_validation->set_message('check_old_password', 'Whoops! Current Password does not match.');
            return false;
        }
    }

    function handle_upload()
    {
        if (isset($_FILES['AdminImage']) && !empty($_FILES['AdminImage']['name'])) {
            $config['upload_path'] = "assets/uploads/AdminUser";
            $config['allowed_types'] = "gif|jpg|jpeg|png";
            $config['max_size'] = "204800";
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('AdminImage')) {
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

}
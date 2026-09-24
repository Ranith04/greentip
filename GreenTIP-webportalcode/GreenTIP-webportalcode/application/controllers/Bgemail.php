<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bgemail extends CI_Controller
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
        $this->load->helpers('basic_helper');
        $this->loggedInUser = getSessionUserData('id');

        if (isset($this->loggedInUser) && !empty($this->loggedInUser)) {
            $this->UserDetail = getUserInfo($this->loggedInUser, 'users');
        }/* else {
            redirect(base_url());
            die;
        }*/
        // customPagination();
    }

    

    /*-- Bulk email Background mail send */
    // public function sendemail()
    // { //echo "hi";die;
    //     if (!empty($_REQUEST)) {
    //         $sent_to = explode(',', $_REQUEST['1']);
    //         $categoryId = $_REQUEST['2'] != '0' ? explode(',', $_REQUEST['2']) : '0';
    //         $emailIDs = $_REQUEST['6'] != '' ? explode(',', $_REQUEST['6']) : '';
    //         $attachment_file = isset($_REQUEST['7']) && $_REQUEST['7'] != '' ? $_REQUEST['7'] : '';
    //         $subject = $_REQUEST['3'];
    //         $senderid = isset($_REQUEST['8']) && $_REQUEST['8'] != '' ? $_REQUEST['8'] : '';
    //         $message = $_REQUEST['5'];
    //         $expertList = $endUserlist = $industryUserslist = $emailList = [];
    //         $fp = fopen('sendemailRequest.txt', 'w+');
    //         fwrite($fp, print_r($_REQUEST, 1));
    //         fclose($fp);
    //         if (in_array('experts', $sent_to)) {
    //             $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
    //             if (!empty($expertList)) {
    //                 $expertList = array_column($expertList, 'email');
    //             } else {
    //                 $expertList = array();
    //             }
    //         }
    //         if (in_array('users', $sent_to)) {
    //             $endUserlist = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '2'));
    //             if (!empty($endUserlist)) {
    //                 $endUserlist = array_column($endUserlist, 'email');
    //             } else {
    //                 $endUserlist = array();
    //             }
    //         }
    //         if (in_array('industrial_category', $sent_to)) {
    //             $this->db->select('email');
    //             $this->db->from('b_industrial_users');
    //             $this->db->where(array('status' => '1'));
    //             $this->db->where_in('category_id', $categoryId);
    //             $resSQL = $this->db->get();
    //             if ($resSQL->num_rows() > 0) {
    //                 $industryUserslist = $resSQL->result_array();
    //             }
    //             if (!empty($industryUserslist)) {
    //                 $industryUserslist = array_column($industryUserslist, 'email');
    //             } else {
    //                 $industryUserslist = array();
    //             }
    //         }
    //         if (in_array('emailid', $sent_to)) {
    //             $emailList = $emailIDs != '' ? $emailIDs : [];
    //         }
    //         $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $emailList));

    //         if (!empty($list)) {
    //             foreach ($list as $email) {
    //                 $userEmail = $email;
    //                 if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {

    //                     $mailContent = $message;
    //                     sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);

    //                 }
    //             }
    //         }
    //     }
    // }
 // new code By Saurabh 24-09-2020
    public function sendemail()
    {  $fp = fopen('sendemailRequest--4.txt', 'w+');
                fwrite($fp, print_r($_REQUEST, 1));
                fclose($fp);

    if (!empty($_REQUEST)) {
        $sent_to = explode(',', $_REQUEST['1']);
        $categoryId = $_REQUEST['2'] != '0' ? explode(',', $_REQUEST['2']) : '0';
        $emailIDs = $_REQUEST['6'] != '' ? explode(',', $_REQUEST['6']) : '';
        $attachment_file = isset($_REQUEST['7']) && $_REQUEST['7'] != '' ? $_REQUEST['7'] : '';
        $subject = $_REQUEST['3'];
        $senderid = isset($_REQUEST['8']) && $_REQUEST['8'] != '' ? $_REQUEST['8'] : '';
        $message = $_REQUEST['5'];
        $expertList = $endUserlist = $industryUserslist = $emailList = $mdtList = $admintList = [];
        
        if (in_array('experts', $sent_to)) {
            $expertList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '1'));
            if (!empty($expertList)) {
                $expertList = array_column($expertList, 'email');
            } else {
                $expertList = array();
            }
        }
        if (in_array('Md', $sent_to)) {
            $mdtList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '3'));
            if (!empty($mdtList)) {
                $mdtList = array_column($mdtList, 'email');
            } else {
                $mdtList = array();
            }
        }
        if (in_array('Admin', $sent_to)) {
            $admintList = $this->common_model->_select('b_users', 'id,email', array('status' => '1', 'role_type' => '0'));
            if (!empty($admintList)) {
                $admintList = array_column($admintList, 'email');
            } else {
                $admintList = array();
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

        $list = array_unique(array_merge($expertList, $endUserlist, $industryUserslist, $emailList,$mdtList,$admintList));

        $list_2 = array_unique(array_merge($emailList));

        $list_3 = array_unique(array_merge($expertList, $endUserlist,$mdtList,$admintList));

        $list_4 = array_unique(array_merge($industryUserslist));


        $fp = fopen('sendemailRequest--1.txt', 'w+');
        fwrite($fp, print_r($list_4, 1));
        fclose($fp);

        if (!empty($list_3)) {
            $list = $list_3;
            foreach ($list as $email) {
                $userEmail = $email;
                if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                  
                    $Listname = $this->common_model->_select('b_users', 'name', array('email' =>$userEmail));


                    $fp = fopen('sendemailRequest--2.txt', 'w+');
                    fwrite($fp, print_r($Listname, 1));
                    fclose($fp);

                    $name = $Listname[0]['name'];
                    $namee = "Dear ".$name.",<br>";
                    $mailContent = $namee.$message;

                    $this->sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);

                }
            }
        }

        if (!empty($list_2)) {
            $list = $list_2;
            foreach ($list as $email) {
                $userEmail = $email;
                if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                  
                        //$Listname = $this->common_model->_select('b_users', 'name', array('email' =>$userEmail));
                        //$name = $Listname[0]['name'];

                 /* $namee = "Dear ".$userEmail.",<br>";*/
                 $namee = "Dear User,<br>";
                 $mailContent = $namee.$message;

                 $this->sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);

             }
         }
     }

     if (!empty($list_4)) {
        $list = $list_4;
        foreach ($list as $email) {
            $userEmail = $email;
            if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
              
                $Listname = $this->common_model->_select('b_industrial_users', 'firstName', array('email' =>$userEmail));
                $name = $Listname[0]['firstName'];
                $namee = "Dear ".$name.",<br>";
                $mailContent = $namee.$message;
                $fp = fopen('sendemailRequest--4.txt', 'w+');
                fwrite($fp, print_r($Listname, 1));
                fclose($fp);

                $this->sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE, $attachment_file);

            }
        }
    }



}
}
public function bulk_handle_upload()
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


function sendMail($subject, $mailContent, $mailTo, $mailFromId, $mailFromName, $attachment_file = '')
    {
        $CI =& get_instance();
        $CI->load->library('email');

        //$this->load->helper('path');
        // does not have to be gmail
       $config['protocol'] = "smtp";
        $config['smtp_host'] = SERVER;
        $config['smtp_port'] = PORT;
        $config['smtp_user'] = USERNAME;
        $config['smtp_pass'] = PASSWORD;
        $config['mailtype'] = 'html';
        //$config['charset'] = 'iso-8859-1';
       $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $CI->email->clear(TRUE);
        $CI->email->initialize($config);
        $CI->email->from($mailFromId, $mailFromName);
        $CI->email->to($mailTo);
        $CI->email->subject($subject);

        $CI->viewData['msg_body'] = $mailContent;
        
            $msg_header = $CI->load->view('email/headerForBulk', $CI->viewData, true);
            $full_msg_body = $msg_header;


        $CI->email->message($full_msg_body);
        if ($attachment_file != '') {
            $CI->email->attach($attachment_file);  /* Enables you to send an attachment */
        }
        if ($CI->email->send()) {
            return true;
        } else {

            return false;

        }
    }

}
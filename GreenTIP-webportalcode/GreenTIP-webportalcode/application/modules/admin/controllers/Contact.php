<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MX_Controller
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
        $this->load->model('contact_model');

        customPagination();
    }

    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $prevSessData = getSessionUserData('ReplyContact');
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $userId = $FormData['csv_ids_hidden'];
            $result = explode(',', $userId);
            $status_id = $FormData['purpose_hidden'];
             if ($status_id == "1") {
                $status = 'Activate';
            }
            else if ($status_id == "0") {
                $status = 'Inactivate';
            }
            else if ($status_id == "2"){
                $status='Blocked';
            }
            else if ($status_id == "3"){
                $status='Deleted';
            }
            if ($this->common_model->updateWhereIn('b_contact_forms', array('status' => $status_id), 'id', $result)) {
                $message = 'Contact ' . $status . ' successfully.';
            }
            setSessionFlashData('success', $message);

        }
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('ReplyContact');
        }
        $prevSessData = getSessionUserData('ReplyContact');
        $conditionArray = $prevSessData;
        $conditionArray['equal']['status!='] = '3';
        if ($isAll != 'all') {
            $start = validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }

        $this->viewData['data'] = array();
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $page_num = (int)$this->uri->segment(4);
        if ($page_num == 0) $page_num = 1;
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $contactDataCount = $this->contact_model->record_count($conditionArray);
        $contactData = $this->contact_model->get_contacts($start, $this->perPage, $conditionArray);

        $userPagination = createPagination('admin/contact/index', $contactDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['dbdata'] = $contactData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['title'] = 'Admin Panel | Manage Contacts';
        $this->load->view('Contact/List', $this->viewData);
    }

    public function reply()
    {
        $contact_id = validateURI(4) != '' ? validateURI(4) : 0;


        if (!empty($contact_id)) {

            $this->viewData['result'] = $this->common_model->_selectById('b_contact_forms', '*', array('id' => $contact_id));

            $message = $this->viewData['result']['message']; 


            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
            $this->form_validation->set_rules('reply_msg', 'reply message', 'trim|required|strip_tags');

            $reply_msg = _inputPost('reply_msg');
            if($reply_msg!=''){

                if ($this->form_validation->run() == TRUE){

                    $email = $this->viewData['result']['email'];
                    $subject = 'Website Enquiry';
                    $replied = '1';
                    $replied_on = time();
                    $base_url = sprintf("%s://%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']);
                    $regex = '#<img([^>]*) src="([^"/]*/?[^".]*\.[^"]*)"([^>]*)>((?!</a>))#';
                    $replace = '<img$1 src="' . $base_url . '$2"$3 >';
                    $Content = preg_replace($regex, $replace, $reply_msg);
                    $reply_msg = $Content;
                    
                    // does not have to be gmail
                    /*$config['protocol'] = "smtp";
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
                    $this->email->to($email);
                    $this->email->subject('Reply - ' . $subject." ( ".$message." )");
                    $this->email->message($reply_msg);*/

                    $reply_msg_for_email = "<p><b>Dear ".$this->viewData['result']['name'].",</b></p>
                    <p>Query Title : ".ucwords($message)."</p>
                    <p>Admin Response : ".$Content."</p>";
                    $this->viewData['msg_body'] = $reply_msg_for_email;
                    $msg_header = $this->load->view('email/headerForBulk', $this->viewData, true);
                    $this->load->library('email');
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
                    $this->email->to($email);
                    $this->email->subject("GreenTIP : Admin Reply");
                    $this->email->message($msg_header);

                    if ($this->email->send()) {
                        $data1 = array('replied' => $replied);
                        $data2 = array(
                            'contact_id' => $contact_id,
                            'reply_msg' => $reply_msg,
                            'replied_on' => $replied_on
                        );

                        $data1 =($data1);
                        $data2 =($data2);


                        if ($this->contact_model->updateContact($contact_id, $data1) && $this->contact_model->insertReply($data2)) {
                            setSessionFlashData('success', "Reply has been sent successfully");
                            redirect(base_url('admin/contact/reply/'.$contact_id));
                        }

                        setSessionFlashData('success', "Reply has been sent successfully");
                        redirect(base_url('admin/contact/'));
                    }
                    else {
                        setSessionFlashData('error', 'mail didnt go trough.');
                        redirect(base_url('admin/contact/'));
                    }
                }
            }

            $this->viewData['replies'] = $this->contact_model->getReply($contact_id);
            if (empty($this->viewData['result'])) {
                show_404();
            } else {
                $this->viewData['title'] = "Reply Contact";
                $this->load->view('admin/Contact/Reply_View', $this->viewData);
            }
        } else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('admin/contact/'));
        }
    }

    public function view()
    {
        $item_id = validateURI(4) != '' ? validateURI(4) : 0;
        if ($item_id != '') {
            $dbdata = $this->common_model->_selectById('b_contact_forms', '*', array('id' => $item_id));
            $replydata = $this->contact_model->getReply($item_id);
            if ($dbdata == false) {
                setSessionFlashData('error', 'No Contacts found.');
                redirect(base_url('admin/contact/'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['replydata'] = $replydata;
                $this->viewData['title'] = "Admin |Manage Contacts";
                $this->load->view('Contact/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

}
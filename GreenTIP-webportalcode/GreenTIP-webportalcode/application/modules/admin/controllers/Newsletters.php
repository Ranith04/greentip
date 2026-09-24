<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletters extends MX_Controller
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
        $this->load->model('newsletter_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
        customPagination();
    }

    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $getData['page'] = $isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $newsletterId = $FormData['csv_ids_hidden'];
            $result = explode(',', $newsletterId);
            $status_id = (int)$FormData['purpose_hidden'];
            if (in_array($status_id, array(3))) {
                $dbArray = [];
                if ($status_id == 3) {
                    $status = 'Deleted';
                    $dbArray['is_deleted'] = 1;
                }
                if ($this->common_model->updateWhereIn('b_newsletters', $dbArray, 'id', $result)) {
                    $message = 'Newsletter ' . $status . ' successfully.';
                }
                setSessionFlashData('success', $message);
            } else {
                setSessionFlashData('error', 'Invalid action');
                redirect(base_url('admin/newsletters'));
            }

        }
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('NewsletterManager');
        }
        $prevSessData = getSessionUserData('NewsletterManager');
        $conditionArray = $prevSessData;
        $conditionArray['equal']['is_deleted'] = 0;
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
        $contactDataCount = $this->newsletter_model->record_count($conditionArray);
        $contactData = $this->newsletter_model->get_newsletters($start, $this->perPage, $conditionArray);

        $userPagination = createPagination('admin/newsletters/index', $contactDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $userPagination;
        $this->viewData['dbdata'] = $contactData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['start'] = $start;
        //sorting
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order;
        $this->viewData['sorting_class'] = 'sorting_' . $order;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['title'] = 'Admin Panel | Manage Newsletters';
        $this->load->view('Newsletter/List', $this->viewData);
    }

    public function send()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $isValidated = true;
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            if ($this->form_validation->run($this) == true) {
                $sent_to = strtolower(trim(_inputPost('sent_to')));
                if ($sent_to == "emailid") {
                    $emails = trim(_inputPost('email'));
                    if (empty($emails)) {
                        $isValidated = false;
                        setSessionFlashData('error', 'Email Id Field is required');
                    }
                }
                if ($isValidated) {
                    $message = closetags(trim(_inputPost('message')));
                    $message = replaceImgSrc($message);
                    $subject = trim(_inputPost('subject'));
                    $list = '';
                    if ($sent_to == 'all') {
                        /**Get Data*/
                        $SQL = ' SELECT email FROM b_subscriber WHERE status = "1"';
                        $list = $this->db->query($SQL)->result_array();
                    } else if ($sent_to == "subscribers") {
                        $list = $this->common_model->_select('b_subscriber', 'id,email', array('status' => 1, 'is_deleted' => 0));
                    } else if ($sent_to == "emailid") {
                        $list = explode(',', trim(_inputPost('email')));
                    }
                    $errorMailid = '';
                    $sendMailid = [];
                    if (!empty($list)) {
                        foreach ($list as $userData) {
                            $userEmail = (isset($userData['email'])) ? $userData['email'] : $userData;
                            if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                                $mailContent = '';
                                $data['name'] = $userEmail;
                                $data['content'] = $message;
                                // $mailContent =  $this->load->view('Newsletter/newsletter_template',$data,true);
                                $mailContent = '<table><tr><td align="left" valign="top" style="padding:20px;"><h3>Hello,' . $data['name'] . '</h3>' . $message . '<br/><br/>Regards,<br/<br/>' . ADMIN_NOTIFICATION_TITLE . '</td></tr></table>';
                                if (sendMail($subject, $mailContent, $userEmail, ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_TITLE)) {
                                    $sendMailid[] = $userEmail;
                                }
                            } else {
                                $errorMailid[] = $userEmail;
                            }
                        }

                        if (!empty($sendMailid)) {
                            $formdata = array('sent_to' => $sent_to, 'subject' => $subject, 'message' => $message, 'status' => 1, 'created_at' => set_local_to_gmt(), 'email_ids' => implode(',', $sendMailid));
                            $this->common_model->_insert('b_newsletters', $formdata);
                            $smsg = 'Newsletter has been sent successfully .';
                            setSessionFlashData('success', $smsg);
                        }
                        redirect('admin/newsletters');
                    } else {
                        setSessionFlashData('error', 'No active users found');
                    }
                }
            }
        }
        $this->viewData['title'] = "Send Newsletter";
        $this->load->view('Newsletter/Send', $this->viewData);
    }

    public function view()
    {
        $item_id = validateURI(4) != '' ? validateURI(4) : 0;
        if ($item_id != '') {
            $dbdata = $this->common_model->_selectById('b_newsletters', '*', array('id' => $item_id));
            if ($dbdata == false) {
                setSessionFlashData('error', 'No Newsletter found.');
                redirect(base_url('admin/newsletters/'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin |Manage Newsletters";
                $this->load->view('Newsletter/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

}
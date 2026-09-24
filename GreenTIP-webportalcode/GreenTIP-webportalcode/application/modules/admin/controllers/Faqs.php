<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Faq
 * @property Common_model common_model
 */
class Faqs extends MX_Controller
{
    var    $perPage       = '10';
    var    $segment       = '4';
    public $viewData      = [];
    public $loggedInAdmin = [];

    private $table        = 'b_faqs';
    private $nameSingular = 'FAQ';
    private $namePlural   = 'FAQs';
    private $nameSession  = 'Faq';
    private $nameClass;
    private $viewLocation = 'Faq/';

    public function __construct()
    {
        parent::__construct();
        isLoggedIn($type = 'admin');
        $this->loggedInAdmin = getSessionUserData('auth_admin_data');
        $this->load->model('admin_model');
        $this->load->model('common_model');
        $this->nameClass = static::class;
        $this->viewData = [
            'nameSingular' => $this->nameSingular,
            'namePlural' => $this->namePlural,
            'nameSession' => $this->nameSession,
            'nameClass' => $this->nameClass,
        ];
        customPagination();
    }


    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
//        $prevSessData = getSessionUserData($this->nameSession.'Manager');

//        $getData['page']=$isAll;
        $FormData = _inputPost('FormData');
        $getData = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $userId = $FormData['csv_ids_hidden'];
            $result = explode(',', $userId);
            $status_id = $FormData['purpose_hidden'];
            $status = '';
            if ($status_id == '1') {
                $status = 'Activated';
            } elseif ($status_id == '0') {
                $status = 'Deactivated';
            } elseif ($status_id == '3') {
                $status = 'Deleted';
            }

            if ($this->common_model->updateWhereIn($this->table, ['status' => $status_id], 'id', $result)) {
                $message = "{$this->nameSingular} {$status} successfully";

            }
            setSessionFlashData('success', $message);

        }
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata($this->nameSession . 'Manager');
        }
        $prevSessData = getSessionUserData($this->nameSession . 'Manager');
        $conditionArray = $prevSessData;
        $conditionArray['equal']['status!='] = '3';
        if ($isAll != 'all') {
            $start = validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }

        $this->viewData['title'] = "Admin Panel | {$this->namePlural} Manager";
        $getField = $this->input->get();
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $pageNum = (int)$this->uri->segment(4);
        if ($pageNum == 0) {
            $pageNum = 1;
        }
        $orderSegment = $order == "asc" ? "desc" : "asc";

        $result = $this->common_model->getList($this->table, $start, $this->perPage, '*', $conditionArray);
        $DataCount = $result['total_rows'];
        $data = $result['results'];


        $pagination = createPagination("admin/{$this->nameClass}/index", $DataCount, $this->perPage, $this->segment,
            $getField);

        $this->viewData['pagination'] = $pagination;
        $this->viewData['dbdata'] = $data;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $pageNum;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $orderSegment;
        $this->viewData['FormData'] = $prevSessData;
        $this->load->view($this->viewLocation . 'List', $this->viewData);
    }

    public function add()
    {
        $this->set_rules();
        if ($this->form_validation->run($this) !== false) {
            $dataArray['question'] = $this->input->post('question');
            $dataArray['answer'] = $this->input->post('answer');
            $dataArray['status'] = '1';
            $itemId = $this->common_model->_insertReturnId($this->table, $dataArray);
            if ($itemId > 0) {
                setSessionFlashData('success', "Congrats! You have successfully added a new {$this->nameSingular}");
                redirect(base_url("admin/{$this->nameClass}"));
            } else {
                setSessionFlashData('error', 'Insertion failed.');
            }
        }
        $this->viewData['title'] = "Admin Panel | Add {$this->nameSingular}";
        $this->load->view($this->viewLocation . 'Add', $this->viewData);
    }

    public function view()
    {
        $id = validateURI(4) != '' ? validateURI(4) : 0;
        if ($id != '') {
            $dbdata = $this->common_model->_selectById($this->table, '*', ['id' => $id]);

            if ($dbdata == false) {
                setSessionFlashData('error', "No {$this->nameSingular} found.");
                redirect(base_url("admin/{$this->nameClass}/"));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin | Manage {$this->nameSingular}";
                $this->load->view($this->viewLocation . 'View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

    public function edit()
    {
        $id = validateURI(4) != '' ? validateURI(4) : 0;
        if ($id != '') {
            $dbdata = $this->common_model->_selectById($this->table, '*', ['id' => $id]);
            if (!empty($dbdata)) {
                $this->set_rules();
                if ($this->form_validation->run($this) == true) {

                    $dataArray['question'] = $this->input->post('question');
                    $dataArray['answer'] = $this->input->post('answer');

                    $updated = $this->common_model->_update($this->table, $dataArray, ['id' => $id]);
                    if ($updated) {
                        setSessionFlashData('success', "You have successfully updated this {$this->nameSingular}.");
                        redirect(base_url("admin/{$this->nameClass}"));
                    } else {
                        setSessionFlashData('error', 'Update failed.');
                    }
                }

                $this->viewData['dbdata'] = $dbdata;

                $this->viewData['title'] = "Admin | Manage {$this->nameSingular}";
                $this->load->view($this->viewLocation . 'Edit', $this->viewData);
            }
        } else {
            setSessionFlashData('error', 'Something went wrong. Please try again.');
            redirect("admin/{$this->nameClass}/");
        }

    }

    function set_rules()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error text-danger">', '</span>');
        $this->form_validation->set_rules('question', 'Question', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('answer', 'Answer', 'required');

    }
}

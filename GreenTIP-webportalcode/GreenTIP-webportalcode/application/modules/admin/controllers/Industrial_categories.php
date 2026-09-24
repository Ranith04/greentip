<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industrial_categories extends MX_Controller
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
        $this->load->model('user_model');
        $this->load->model('industrial_category_model');
        customPagination();
    }

    public function add()
    {
        $this->set_rules('Category');
        if ($this->form_validation->run($this) !== FALSE) {

            $dataArray['cat_name'] = $cat_name = $this->input->post('cat_name');
            $dataArray['status'] = $this->input->post('status');
            $dataArray['added_on'] = set_local_to_gmt();
            $cat_title = $this->input->post('cat_name');
            $alias = $this->common_model->create_unique_slug($cat_title, 'b_industrial_categories', 'alias');
            $dataArray['alias'] = $alias;
            $categoryID = $this->common_model->_insertReturnId('b_industrial_categories', $dataArray);
            if ($categoryID > 0) {
                setSessionFlashData('success', 'Congrats! You have successfully added new category');
                redirect(base_url('admin/industrial_categories'));
            }

        }
        $this->viewData['title'] = 'Admin Panel | Add Category';
        $this->viewData['data'] = array();
        $this->load->view('Industrial_Categories/Add', $this->viewData);
    }

    public function index()
    {
        $isAll = getStringSegment(4) ? getStringSegment(4) : false;
        $FormData = $this->input->post('FormData');
        if ($isAll && $isAll == 'all') {
            $this->session->unset_userdata('IndustrialCategoryManager');
        }
        $prevSessData = getSessionUserData('IndustrialCategoryManager');

        $conditionArray = $prevSessData;
        $conditionArray['equal']['status!='] = '3';
        if ($isAll != 'all') {
            $start = validateURI(4) != '' ? validateURI(4) : '0';
            $getData['page'] = $start;
        } else {
            $start = '0';
            $getData['page'] = '';
        }
        $getField = $this->input->get();
        if (!empty($FormData) && $FormData['purpose_hidden'] != "" && $FormData['csv_ids_hidden'] != "") {
            $categoryId = $FormData['csv_ids_hidden'];
            $result = explode(',', $categoryId);
            $status_id = $FormData['purpose_hidden'];
            if ($status_id == "1") {
                $status = 'Activate';
            } else if ($status_id == "0") {
                $status = 'Inactivate';
            } else if ($status_id == "2") {
                $status = 'Blocked';
            } else if ($status_id == "3") {
                $status = 'Deleted';
            }
            if ($this->common_model->updateWhereIn('b_industrial_categories', array('status' => $status_id), 'id', $result)) {
                $message = 'Industrial Category ' . $status . ' successfully.';
            }
            setSessionFlashData('success', $message);

        }
        $sortField = isset($prevSessData['sort']['field']) ? $prevSessData['sort']['field'] : 'id';
        $order = isset($prevSessData['sort']['order']) ? $prevSessData['sort']['order'] : 'desc';
        $page_num = (int)$this->uri->segment(4);
        if ($page_num == 0) $page_num = 1;
        if ($order == "asc") $order_seg = "desc"; else $order_seg = "asc";
        $emailDataCount = $this->industrial_category_model->record_count($conditionArray);
        $emailData = $this->industrial_category_model->get_categories($start, $this->perPage, $conditionArray);
        $emailPagination = createPagination('admin/industrial_categories/index', $emailDataCount, $this->perPage, $this->segment, $getField);
        $this->viewData['pagination'] = $emailPagination;
        $this->viewData['dbdata'] = $emailData;
        $this->viewData['getData'] = $getData;
        $this->viewData['pageNum'] = $page_num;
        $this->viewData['field'] = $sortField;
        $this->viewData['order'] = $order_seg;
        $this->viewData['FormData'] = $prevSessData;
        $this->viewData['dataQuery'] = $this->db->last_query();
        $this->viewData['title'] = 'Admin Panel | Manage Categories';
        $this->load->view('Industrial_Categories/List', $this->viewData);
    }

    public function edit()
    {
        $categoryId = validateURI(4) != '' ? validateURI(4) : 0;
        if ($categoryId != '') {
            $dbdata = $this->industrial_category_model->_selectById(array('id' => $categoryId));
            if (!empty($dbdata)) {
                $this->set_rules('editCategory');
                if ($this->form_validation->run($this) == true) {
                    $dataArray['cat_name'] = $this->input->post('cat_name');
                    $cat_title = $this->input->post('cat_name');
                    $alias = $this->common_model->create_unique_slug($cat_title, 'b_industrial_categories', 'alias', 'id', $categoryId);
                    $dataArray['alias'] = $alias;
                    $dataArray['status'] = $this->input->post('status');
                    $this->common_model->_update('b_industrial_categories', $dataArray, array('id' => $categoryId));
                    setSessionFlashData('success', 'You have successfully updated category');
                    redirect(base_url('admin/industrial_categories'));
                }
            }
            $this->viewData['dbdata'] = $dbdata;
            $this->viewData['title'] = "Admin | Manage Categories";
            $this->load->view('Industrial_Categories/Edit', $this->viewData);
        } else {
            setSessionFlashData('error', 'Something went wrong....');
            redirect(base_url('admin/industrial_categories'));
        }
    }

    public function view()
    {
        $item_id = validateURI(4) != '' ? validateURI(4) : 0;
        if ($item_id != '') {
            $dbdata = $this->common_model->_selectById('b_industrial_categories', '*', array('id' => $item_id));
            if ($dbdata == false) {
                setSessionFlashData('error', 'No Categories found.');
                redirect(base_url('admin/industrial_categories'));
            } else {
                $this->viewData['dbdata'] = $dbdata;
                $this->viewData['title'] = "Admin |Manage Categories";
                $this->load->view('Industrial_Categories/View', $this->viewData);
            }
        } else {
            show_404();
        }
    }

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error">', '</span>');
        if ($option == 'Category' || $option == 'editCategory') {
            $this->form_validation->set_rules('cat_name', 'Category Name', 'required|max_length[30]|min_length[4]|callback_category_check');

            $this->form_validation->set_rules('status', 'Status', 'required');
        }
    }


    public function category_check($str)
    {
        $id = $this->uri->segment(4);
        $condition = array('status !=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition = array('id !=' => $id, 'status !=' => '3');
        }
        if ($this->common_model->_CheckExistence('cat_name', $str, 'b_industrial_categories', $condition)) {
            return true;
        } else {
            $this->form_validation->set_message('category_check', 'Whoops! Category  Name already exists. Please try with another Category Name.');
            return false;
        }
    }
}

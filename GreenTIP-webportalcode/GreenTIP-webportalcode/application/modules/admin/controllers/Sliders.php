<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Faq
 * @property Common_model common_model
 */
class Sliders extends MX_Controller
{
    var $perPage = '10';
    var $segment = '4';
    public $viewData = [];
    public $loggedInAdmin = [];

    private $table = 'b_sliders';
    private $nameSingular = 'Slider';
    private $namePlural = 'Slider';
    private $nameSession = 'Slider';
    private $nameClass;
    private $viewLocation = 'Sliders/';

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
        $this->set_rules('addArticle');
        if ($this->form_validation->run($this) !== false) {

            $dataArray['title'] = $this->input->post('title');

            $dataArray['status'] = '1';
            $dataArray['added_on'] = set_local_to_gmt();
            if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
                $dataArray['image'] = $this->upload_data['file_name'];
            }
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
                $this->set_rules('editArticle');
                if ($this->form_validation->run($this) == true) {

                    $dataArray['title'] = $this->input->post('title');

                    if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
                        $dataArray['image'] = $this->upload_data['file_name'];
                    }
                    $updated = $this->common_model->_update($this->table, $dataArray, ['id' => $id]);
                    if ($updated) {
                        if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
                            if (!empty($dbdata['userFile'])) {
                                if (file_exists('./assets/uploads/sliders/' . $dbdata['image'])) {
                                    unlink('./assets/uploads/sliders/' . $dbdata['image']);
                                }
                            }
                        }
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

    function set_rules($option)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="has-error">', '</span>');

        if ($option == 'addArticle') {
            $this->form_validation->set_rules('title', 'Title', 'required');

            if (empty($_FILES['userFile']['name'])) {
                $this->form_validation->set_rules('userFile', 'Slider Image', 'required');
            } else {
                $this->form_validation->set_rules('userFile', 'Slider Image', 'callback_handle_upload');
            }
            /* $this->form_validation->set_rules('parallaxFile', 'Slider Parallax Image', 'callback_handle_upload_parallax');*/
        }
        if ($option == 'editArticle') {
            $this->form_validation->set_rules('title', 'Title', 'required');

            $this->form_validation->set_rules('userFile', 'Slider Image', 'callback_handle_upload');
            /*$this->form_validation->set_rules('parallaxFile', 'Slider Image', 'callback_handle_upload_parallax');*/
        }
    }

    function handle_upload()
    {
        if (isset($_FILES['userFile']) && !empty($_FILES['userFile']['name'])) {
            if (!file_exists("assets/uploads/sliders")) {
                mkdir("assets/uploads/sliders", 0777, true);
            }
            $imgInfo = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
            $rand_val = date('YMDHIS') . rand(11111, 99999);
            $filename = md5($rand_val) . "." . $imgInfo;
            $_FILES['userFile']['name'] = $filename;
            $config2['upload_path'] = "assets/uploads/sliders";
            $config2['allowed_types'] = "gif|jpg|jpeg|png";
            $config2['remove_spaces'] = TRUE;
            $this->load->library('upload', $config2);
            $this->upload->set_upload_path($config2['upload_path']);
            $this->upload->initialize($config2);
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
}
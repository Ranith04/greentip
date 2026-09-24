<?php
/**
 * Created by PhpStorm.
 * User: abhishek
 * Date: 7/2/15
 * Time: 10:50 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    public $viewData = array();
    public $loggedInAdmin = array();

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

    function SessionCreate()
    {
        if (!$this->input->is_ajax_request()) {
            echo "Ajax Requests allowed.";
            die;
        } else {

            $isAll = getStringSegment(4) ? getStringSegment(4) : false;
            $FormData = $this->input->post('FormData');

            $sessionQuery = array('sort' => array('field' => 'id', 'order' => 'desc'));
            if (!empty($FormData) && isset($FormData['like']) && $FormData['like'] != '') {
                foreach ($FormData['like'] as $likeKey => $like) {
                    if ($FormData['like'][$likeKey] != '') {
                        $sessionQuery['like'][$likeKey] = trim($like);
                    }
                }
            }
            if (!empty($FormData) && isset($FormData['equal']) && $FormData['equal'] != '') {
                foreach ($FormData['equal'] as $key => $status) {
                    if (strpos($key, '-') !== false) {
                        $key = str_replace("-", ".", $key);
                    }
                    if ($key == 'status' || $key == 'type') {
                        if ($status == '0' || $status == '1' || $status == '2' || $status == '3' || $status == '4' || $status == '5') {
                            $sessionQuery['equal'][$key] = $status;
                        }
                    } else if ($status != '') {
                        $sessionQuery['equal'][$key] = $status;
                    }

                }
            }
            if (!empty($FormData) && isset($FormData['sort']) && $FormData['sort'] != '') {
                foreach ($FormData['sort'] as $equalKey => $equal) {
                    if ($equal != '') {
                        $sessionQuery['sort'][$equalKey] = trim($equal);
                    }
                }
            }
            if (!empty($FormData) && isset($FormData['range'])) {

                $rangeArray = array();
                foreach ($FormData['range'] as $key => $rangeKey) {

                    if (isset($rangeKey['from']) && isset($rangeKey['to']) && ($rangeKey['from'] != '') && ($rangeKey['to'] != '')) {
                        $rangeArray[$key] = array('from' => $rangeKey['from'], 'to' => $rangeKey['to']);
                    }

                }

                if (!empty($rangeArray)) {
                    $sessionQuery['range'] = $rangeArray;
                }

            }
            if ($sessionQuery && ($isAll != 'all' || $isAll == false)) {
                $sessionQueryNew = array_merge($FormData, $sessionQuery);
                setSessionUserData(array($FormData['form_name'] => $sessionQueryNew));
            }

            if (!empty($FormData)) {
                $this->output->set_output(json_encode(array('status' => true, 'data' => 'success')));
                $string = $this->output->get_output();
                echo $string;
                exit();
            }

        }
    }

    function GetStateByCountryID()
    {
        $country_id = validateURI(4) != '' ? validateURI(4) : 0;
        $HTML = '';
        if (!empty($country_id)) {
            $States = GetStateByCountryID($country_id);
            if ($States && count($States) > 0) {
                foreach ($States as $row) {
                    $state_name = $row->State_Name;
                    $HTML .= '<option value="' . $row->id . '">' . $state_name . '</option>';
                }
            }
            echo $HTML;
            exit;
        }
    }

    public function GetCityList($StateId, $select = "", $type = null)
    {
        $CityList = $this->CommonModel->_select("b_cities", '*', array('state_id' => $StateId), 'city_name,id');
        if ($CityList) {
            if ($type == 'json') {
                echo json_encode($CityList);
                die;
            } else {
                foreach ($CityList as $cities) {
                    $selected = "";
                    if ($select != "" && $cities["id"] == $select) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option value="' . $cities['id'] . '" ' . $selected . '>' . $cities['city_name'] . '</option>';
                }
            }
        }
        echo !empty($CityList) ? $CityList : '';
    }


    public function getAllIndustrialCategory()
    {
        $category_id = $this->uri->segment(4) != '' ? $this->uri->segment(4) : '';
        $HTML = '';
        $CategoryList = $this->CommonModel->_select("b_industrial_categories", '*', array('status' => '1'), 'cat_name', 'ASC');
        if ($CategoryList) {
            foreach ($CategoryList as $category) {
                $selected = "";
                if (!empty($category_id)) {
                    $categoryId = explode('_', $category_id);
                    if (in_array($category['id'], $categoryId)) {
                        $selected = 'selected="selected"';
                    }
                }
                $cat_name = $category['cat_name'];
                $cat_id = $category['id'];
                $HTML .= '<option value="' . $cat_id . '" ' . $selected . '>' . $cat_name . '</option>';
            }
        }
        echo $HTML;
        exit;
    }
}
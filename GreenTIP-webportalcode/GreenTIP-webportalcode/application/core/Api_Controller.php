<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Controller extends REST_Controller
{
    protected $loginUserData = array();
    protected $requested_headers = array();
    protected $jsonArray = array();
    protected $perPage;
    protected $start;
    protected $platForm;

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('user_model');
        $res = $this->apiAuthentication();
        $this->requested_headers = $res['requested_headers'];
        $this->loginUserData = $res['loginUserData'];
    }

    /**
     * @param string $type
     * @return array
     */
    protected function apiAuthentication($type = 'users')
    {
        //$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        //fwrite($myfile, print_r($_REQUEST,1));
        header("Access-Control-Allow-Origin: *");
        $headers = getallheaders();
        $params = $this->post();
        $loginUserData = array();
        $appVersion = isset($headers['Appversion']) && $headers['Appversion'] != '' ? $headers['Appversion'] : '';
        $apiVersion = isset($headers['Apiversion']) && $headers['Apiversion'] != '' ? $headers['Apiversion'] : '';
        $deviceType = isset($headers['Devicetype']) && $headers['Devicetype'] != '' ? ($headers['Devicetype']) : '';
        /* $authKey = isset($headers['Authkey']) && $headers['Authkey'] != '' ? $headers['Authkey'] : '';*/
        $authKey = '';
        $deviceId = isset($headers['Deviceid']) && $headers['Deviceid'] != '' ? ($headers['Deviceid']) : '';
        $this->platForm = isset($headers['Platform']) && $headers['Platform'] != '' ? $headers['Platform'] : 'android';
        $accessToken = isset($params['access_token']) && $params['access_token'] != '' ? $params['access_token'] : '';
        $userId = isset($params['user_id']) && $params['user_id'] != '' ? $params['user_id'] : 0;
        $this->perPage = isset($params['per_page']) ? $params['per_page'] : FRONT_END_LIMIT;
        $start = isset($params['start']) ? $params['start'] : 0;
        if ($start > 0) {
            $this->start = ($start - 1) * $this->perPage;
        } else {
            $this->start = $start;
        }

        $requested_headers = array(
            'app_version' => $appVersion,
            'api_version' => $apiVersion,
            'access_token' => $accessToken,
            'user_id' => $userId,
            'auth_key' => $authKey,
            'device_type' => $deviceType,
            'device_id' => $deviceId
        );
        /*if ($authKey == '' || ($authKey != API_AUTH_KEY)) {
            $this->jsonArray['status'] = false;
            $this->jsonArray['message'] = lang('text_rest_lang_bad_request');
            $this->jsonArray['data'] = array();
            $this->apiSetOutput($this->jsonArray);
        }*/
        if ($userId > 0) {
            $loginUserData = getUserInfo($userId, 'users');
        } elseif ($userId != '') {
            $this->jsonArray['status'] = false;
            $this->jsonArray['logout'] = 0;
            $this->jsonArray['message'] = lang('text_rest_logout_msg');
            $this->jsonArray['data'] = array();
            $this->apiSetOutput($this->jsonArray);
        }
        $response = array(
            'requested_headers' => $requested_headers,
            'loginUserData' => $loginUserData
        );
        return $response;
    }

    protected function loginReturnData($userDetail)
    {
        if (empty($userDetail)) {
            $this->response(['status' => false], REST_Controller::HTTP_OK);
        }
        $response = [
            'status' => true,
            'message' => 'You have successfully logged in',
            'data' => $userDetail,
        ];
        $this->apiSetOutput($response);
    }


    /**
     * @param $jsonArray
     */
    protected function apiSetOutput($jsonArray)
    {
        $this->response($jsonArray, REST_Controller::HTTP_OK, false);
    }

    /**
     * checkLogin
     *
     * @return void
     */
    protected function checkLogin()
    {
        if (empty($this->loginUserData)) {
            $this->apiSetOutput(['status' => false, 'message' => 'You are not Logged in.']);
        }
    }

    public function phone_number($phone_number, $id)
    {
        $condition = array('status!=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition['id!='] = $id;
        }
        if ($phone_number != '') {
            if ($this->common_model->_CheckExistence('contact_no', $phone_number, 'b_users', $condition)) {
                return true;
            } else {
                $this->form_validation->set_message('phone_number', 'Phone Number already exists');
                return false;
            }
        }
    }

    public function check_email($email, $id)
    {
        $condition = array('status!=' => '3');
        if (!empty($id) && is_numeric($id)) {
            $condition['id!='] = $id;
        }

        if ($email != '') {
            if ($this->common_model->_CheckExistence('email', $email, 'b_users', $condition)) {
                return true;
            } else {
                $this->form_validation->set_message('check_email', 'Email address already exists.');
                return false;
            }
        }
    }

    public function validate_image($name, $key)
    {
        $error = '';

        if (isset($_FILES[$key]) && $_FILES[$key]['name'] != '') {
            $filename = $_FILES[$key]['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            //For size
            // pr($_FILES);die;
            if ($_FILES[$key]["size"] >= MAX_FILE_SIZE) { //60 mb
                $error = "File size should be less than 500KB";
            } elseif (!in_array(strtolower($ext), App::getValidImgFormat())) {
                $error = "Only " . implode(',', App::getValidImgFormat()) . " file is allowed";
            }

            if ($error) {
                //$this->load->library('form_validation');
                $this->form_validation->set_message('validate_image', $error);
                return false;
            } else {
                return true;
            }
        }
        return true;
    }
}
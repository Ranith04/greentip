<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experts extends CI_Controller
{
    var $perPage = '12';
    var $segment = '2';
    public $viewData = array();
    public $loggedInUser = array();
    public $UserDetail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    public function index()
    {
        try
        {
            tryLogPrinter("Experts", $params);
        customPagination();
        $segment = '2';
        $start = validateURI($segment) != '' ? validateURI($segment) : '0';
        $result = $this->common_model->getNewList('b_users', $start, $this->perPage, '*', ['status' => '1', 'role_type' => '1'],'id','DESC');
        $listings = [];
        $pagination = '';
        if (isset($result['total_rows']) && $result['total_rows'] > 0) {
            $listings = $result['results'];
            $pagination = createPagination('experts/', $result['total_rows'], $this->perPage, $this->segment);
        }
        $this->viewData['pagination'] = $pagination;
        $this->viewData['dbdata'] = $listings;
        $this->viewData['title'] = 'Green Tip| Experts';
        $this->load->view('Experts', $this->viewData);
        }//try
        catch(Exception $e) 
        {  
            log_message('error', "\n Exception Caught", $e->getMessage());
        }//catch
    }
}